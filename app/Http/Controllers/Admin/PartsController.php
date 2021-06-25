<?php

namespace App\Http\Controllers\Admin;

use App\Models\Part;
use App\Models\PartPrice;
use App\Models\PartPriceSegment;
use App\Models\PriceSegment;
use App\Models\Store;
use App\Models\Branch;
use App\Models\Supplier;
use App\Models\SparePart;
use App\Models\TaxesFees;
use App\Services\PartPriceServices;
use App\Traits\SubTypesServices;
use Exception;
use Illuminate\Http\Request;
use App\Models\SparePartUnit;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ExportPrinterFactory;
use App\Http\Controllers\DataExportCore\SpareParts;
use CodeItNow\BarcodeBundle\Utils\BarcodeGenerator;
use App\Http\Requests\Admin\Parts\CreatePartsRequest;
use App\Http\Requests\Admin\Parts\UpdatePartsRequest;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use function GuzzleHttp\Promise\all;
use function Matrix\trace;

class PartsController extends AbstractController
{
    use SubTypesServices;

    /**
     * @var string
     */
    public $lang;

    /**
     * @var PartPriceServices
     */
    public $partPriceServices;

    public function __construct()
    {
        $this->lang = App::getLocale();
        $this->partPriceServices = new PartPriceServices();
    }

    public function getSortFields(): array
    {
       return [
           'id' => 'id',
           'name' => 'name_' . $this->getLang(),
           'quantity' => 'quantity',
           'status' => 'status',
           'reviewable' => 'reviewable',
           'taxable' => 'taxable',
           'created-at' => 'created_at',
           'updated-at' => 'updated_at'
       ];
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('view_parts')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $parts = Part::with(['alternative', 'sparePartsType']);
        if ($request->has('part_name') && $request['part_name'] != '') {
            $parts->where('id', $request['part_name']);
        }

        if ($request->has('partId') && $request['partId'] != '') {
            $sparePart = SparePart::find($request['partId']);
            session()->forget('ids');
            getSparePartsIds($sparePart);
            $parts->whereHas('spareParts', function ($q) use ($request) {
                $q->whereIn('spare_part_type_id', array_unique(session('ids')));
            });
        }

        if ($request->has('barcode') && $request['barcode'] != '') {
            $parts->whereHas('prices', function ($q) use ($request) {
                $q->where('barcode', $request['barcode']);
            });
        }

        if ($request->has('supplier_barcode') && $request['supplier_barcode'] != '') {
            $parts->whereHas('prices', function ($q) use ($request) {
                $q->where('supplier_barcode', $request['supplier_barcode']);
            });
        }

        if ($request->has('store_id') && $request['store_id'] != '') {
            $parts->whereHas('stores', function ($q) use ($request) {
                $q->where('store_id', $request['store_id']);
            });
        }

        if ($request->has('active') && $request['active'] != '') {
            $parts->where('status', 1);
        }

        if ($request->has('inactive') && $request['inactive'] != '') {
            $parts->where('status', 0);
        }

        if ($request->has('supplier_id') && $request['supplier_id'] != '') {
            $parts->where('suppliers_ids', $request['supplier_id']);
        }

        if ($request->has('key')) {
            $key = $request->key;
            $parts->where(function ($q) use ($key) {
                $q->where('name_en', 'like', "%$key%")
                    ->orWhere('name_ar', 'like', "%$key%");
            });
        }

        if ($request->has('invoker') && in_array($request->invoker, ['print', 'excel'])) {
            $visible_columns = $request->has('visible_columns') ? $request->visible_columns : [];
            return (new ExportPrinterFactory(new SpareParts($parts, $visible_columns), $request->invoker))();
        }

        $rows = $request->has('rows') ? $request->rows : 25;
        $parts = $this->implementDataTableSearch($parts, $request);
        $parts = $parts->paginate($rows)->appends(request()->query());
        $partsSearch = filterSetting() ? Part::all()->pluck('name', 'id') : null;
        $stores = filterSetting() ? Store::all()->pluck('name', 'id') : null;
        $sparesPartsTypes = filterSetting() ? SparePart::with('parent')->where('spare_part_id', null)
            ->select('id', 'type_' . $this->lang)->get() : null;
        $suppliers = filterSetting() ? Supplier::all()->pluck('name', 'id') : null;
        $taxes = TaxesFees::where('on_parts', true)->get();
        return view('admin.parts.index',
            compact('parts', 'partsSearch', 'sparesPartsTypes', 'stores', 'suppliers', 'taxes'));
    }

    public function create(Request $request)
    {
        $branch_id = $request->has('branch_id') ? $request['branch_id'] : auth()->user()->branch->id;

        if (!auth()->user()->can('create_parts')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $lang = $this->lang;

        $branches = Branch::select('id', 'name_' . $lang)->get();

        $parts = Part::where('branch_id', $branch_id)->select('id', 'name_' . $lang)->get();

        $partUnits = SparePartUnit::select('id', 'unit_' . $lang)->get();

        $stores = Store::where('branch_id', $branch_id)->select('id', 'name_' . $lang)->get();

        $suppliers = Supplier::where('branch_id', $branch_id)->get()->pluck('name', 'id');

        $mainTypes = SparePart::where('status', 1)
            ->where('branch_id', $branch_id)
            ->where('spare_part_id', null)
            ->select('id', 'type_' . $this->lang, 'spare_part_id')
            ->get();

        $subTypes = $this->getSubPartTypes($mainTypes);

        return view('admin.parts.create',
            compact( 'partUnits', 'stores', 'parts', 'suppliers', 'branches', 'lang', 'mainTypes', 'subTypes'));
    }

    public function store(CreatePartsRequest $request)
    {
        if (!auth()->user()->can('create_parts')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        try {
            $data = $request->validated();

            $data['status'] = 0;

            if ($request->has('status')) {
                $data['status'] = 1;
            }

            if ($request->has('is_service')) {
                $data['is_service'] = 1;
            }

            if ($request->has('img') && $request->file('img') !== null) {
                $data['img'] = uploadImage($request->file('img'), 'parts');
            }

            if (!authIsSuperAdmin()) {
                $data['branch_id'] = auth()->user()->branch_id;
            }

            DB::beginTransaction();

            $part = Part::create($data);

            if ($request->has('stores')) {
                $part->stores()->attach($request['stores']);
            }

            if ($request->has('alternative_parts')) {
                $part->alternative()->attach($request['alternative_parts']);
            }

            if ($request->has('spare_part_type_ids')) {
                $part->spareParts()->attach($request['spare_part_type_ids']);
            }

            $units = $this->partPriceServices->defaultUnit($request);

            $this->partPriceServices->saveCartPrices($part, $units, $request['spare_part_unit_id']);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with(['message' => __('words.back-support'), 'alert-type' => 'error']);
        }

        return redirect(route('admin:parts.index'))
            ->with(['message' => __('words.parts-created'), 'alert-type' => 'success']);
    }

    public function show(Part $part)
    {
        if (!auth()->user()->can('view_parts')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        return view('admin.parts.show', compact('part'));
    }

    public function edit(Part $part)
    {
        if (!auth()->user()->can('update_parts')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $lang = $this->lang;

        $branches = Branch::select('id', 'name_' . $lang)->get();

        $parts = Part::where('branch_id', $part->branch_id)->where('id', '!=', $part->id)->select('id',
            'name_' . $lang)->get();

        $partUnits = SparePartUnit::select('id', 'unit_' . $lang)->get();

        $stores = Store::where('branch_id', $part->branch_id)->select('id', 'name_' . $lang)->get();

        $suppliers = Supplier::where('branch_id', $part->branch_id)->get();

        $mainTypes = SparePart::where('status', 1)
            ->where('branch_id', $part->branch_id)
            ->where('spare_part_id', null)
            ->select('id', 'type_' . $this->lang, 'spare_part_id')
            ->get();

        $subTypes = $this->getSubPartTypes($mainTypes);

        return view('admin.parts.edit',
            compact( 'partUnits', 'stores', 'part', 'parts', 'suppliers', 'branches', 'mainTypes','subTypes'));
    }

    public function update(UpdatePartsRequest $request, Part $part)
    {
        if (!auth()->user()->can('update_parts')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        try {
            $data = $request->validated();
            $data['status'] = 0;
            $data['is_service'] = 0;

            if ($request->has('status')) {
                $data['status'] = 1;
            }

            if ($request->has('is_service')) {
                $data['is_service'] = 1;
            }

            if ($request->has('img') && $request->file('img') !== null) {
                $data['img'] = uploadImage($request->file('img'), 'parts');
            }

            if (!authIsSuperAdmin()) {
                $data['branch_id'] = auth()->user()->branch_id;
            }

            if (!$request->has("suppliers_ids")) {
                $data['suppliers_ids'] = null;
            }

            DB::beginTransaction();

            $part->update($data);

            if ($request->has('alternative_parts')) {
                $part->alternative()->sync($request['alternative_parts']);
            } else {
                $part->alternative()->detach();
            }

            if ($request->has('stores')) {
                $part->stores()->sync($request['stores']);
            } else {
                $part->stores()->detach();
            }

            if ($request->has('spare_part_type_ids')) {
                $part->spareParts()->sync($request['spare_part_type_ids']);
            } else {
                $part->spareParts()->detach();
            }

            $units = $this->partPriceServices->defaultUnit($request);

            $this->partPriceServices->saveCartPrices($part, $units, $request['spare_part_unit_id']);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with(['message' => __('words.back-support'), 'alert-type' => 'error']);
        }
        return redirect(route('admin:parts.index'))
            ->with(['message' => __('words.parts-updated'), 'alert-type' => 'success']);
    }

    public function destroy(Part $part)
    {
        if (!auth()->user()->can('delete_parts')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if ($part->openingBalanceItems->count() || $part->concessionItems->count() || $part->damagedStockItems->count() || $part->settlementItems->count()) {

            return redirect()->back()->with(['message' => __('sorry this item in related data'), 'alert-type' => 'error']);
        }

        try {

            DB::beginTransaction();

            $this->partPriceServices->deletePart($part);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with(['message' => __('words.back-support'), 'alert-type' => 'error']);
        }

        return redirect()->back()
            ->with(['message' => __('words.parts-deleted'), 'alert-type' => 'success']);
    }

    public function deleteSelected(Request $request)
    {
        if (!auth()->user()->can('delete_parts')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }


        try {

            DB::beginTransaction();

            if (!isset($request->ids)) {

                return redirect(route('admin:parts.index'))
                    ->with(['message' => __('words.select-one-least'), 'alert-type' => 'error']);
            }


            foreach ($request->ids as $id) {

                $part = Part::find('id', $id);

                if (!$part) {

                    return redirect(route('admin:parts.index'))
                        ->with(['message' => __('words.parts.not.found'), 'alert-type' => 'error']);
                }

                $this->partPriceServices->deletePart($part);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with(['message' => __('words.back-support'), 'alert-type' => 'error']);
        }

        return redirect(route('admin:parts.index'))->with(['message' => __('words.selected-row-deleted'), 'alert-type' => 'success']);

    }

    public function printBarcode(Request $request)
    {
        $this->validate($request, [
            'qty' => 'required|integer|min:1',
            'id' => 'required|integer|exists:part_prices,id',
        ]);

        $qty = $request['qty'];

        $price = PartPrice::findOrFail($request['id']);

        if (!$price->barcode) {
            return response()->json(__('barcode is empty'), 400);
        }

        $barcode = new BarcodeGenerator();

        return view('admin.parts.barcode', compact('barcode', 'price', 'qty'));
    }

    public function getPartsBySparePartID(Request $request)
    {
        $parts = Part::where('spare_part_type_id', $request->spare_part_type_id)->get();

        $htmlParts = '<option value="">' . __('Select Part') . '</option>';
        foreach ($parts as $part) {
            $htmlParts .= '<option value="' . $part->id . '">' . $part->name . '</option>';
        }

        $htmlBarcode = '<option value="">' . __('Select BarCode') . '</option>';
        foreach ($parts as $part) {
            if ($part->barcode != null) {
                $htmlBarcode .= '<option value="' . $part->id . '">' . $part->barcode . '</option>';
            }
        }
        return response()->json([
            'parts' => $htmlParts,
            'barcode' => $htmlBarcode,
        ]);
    }

    public function partsTypeByBranch(Request $request)
    {
        $branch_id = $request['branch_id'];

        $data['partTypes'] = SparePart::where('status', 1)->where('branch_id', $branch_id)->select('id',
            'type_' . $this->lang, 'spare_part_id')->get();

        $data['supplier'] = Supplier::where('branch_id', $branch_id)->get()->pluck('name', 'id');

        $data['parts'] = Part::where('branch_id', $branch_id);

        $data['stores'] = Store::where('branch_id', $request['branch_id'])->select('id', 'name_' . $this->lang)->get();

        if ($request->has('part_id')) {
            $data['parts']->where('id', '!=', $request['part_id']);
        }

        $data['parts'] = $data['parts']->get()->pluck('name', 'id');

        $data['taxes'] = TaxesFees::where('branch_id', $request['branch_id'])
            ->where('on_parts', true)
            ->select('name_' . $this->lang, 'id')
            ->get();

        $data['lang'] = $this->lang;

        return $data;
    }

    public function getSubPartsTypes(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'spare_parts_ids.*' => 'integer|exists:spare_parts,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        try {

            $partsTypesIds = $request->has('spare_parts_ids') ? $request['spare_parts_ids'] : [];

            $mainTypes = SparePart::where('status', 1)
                ->where('spare_part_id', null)
                ->whereIn('id', $partsTypesIds)
                ->select('id', 'type_' . $this->lang, 'spare_part_id')
                ->get();

            $order = $request->has('order') ? $request['order'] : 1;

            $subTypes = $this->getSubPartTypes($mainTypes, $order);

            $part = $request->has('part_id') ? Part::find($request['part_id']) : null;

            $view = view('admin.parts.ajax_sub_parts_types', compact('subTypes', 'part'))->render();

            return response()->json(['view' => $view], 200);

        } catch (\Exception $e) {
            return response()->json(__('sorry, please try later'), 400);
        }
    }

    public function newPartPriceSegment(Request $request)
    {
        try {
            $index = $request['index'];
            $key = $request['part_price_segments_count'] + 1;

            $view = view('admin.parts.price_segments.ajax_price_segment', compact('index', 'key'))->render();

            return response()->json(['view' => $view, 'key' => $key], 200);
        } catch (\Exception $e) {
            return response()->json(__('sorry, please try later'), 400);
        }
    }

    public function deletePartPriceSegment(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'partPriceSegmentId' => 'required|integer|exists:part_price_segments,id'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        try {
            $partPriceSegment = PartPriceSegment::find($request['partPriceSegmentId']);

            $partPriceSegment->delete();

            return response()->json(['message' => __('Price segment deleted successfully')], 200);
        } catch (\Exception $e) {
            return response()->json(__('sorry, please try later'), 400);
        }
    }

    public function saveTaxes(Request $request)
    {
        $this->validate($request, [

            'part_id' => 'required|integer|exists:parts,id',
            'taxes.*' => 'required|integer|exists:taxes_fees,id',
        ]);

        try {
            $reviewable = 0;
            $taxable = 0;

            $part = Part::find($request['part_id']);

            if ($request->has('taxes')) {
                $part->taxes()->sync($request['taxes']);
            } else {
                $part->taxes()->detach();
            }

            if ($request->has('reviewable')) {
                $reviewable = 1;
            }

            if ($request->has('taxable')) {
                $taxable = 1;
            }

            $part->taxable = $taxable;
            $part->reviewable = $reviewable;

            $part->save();

            DB::commit();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with(['message' => __('words.back-support'), 'alert-type' => 'error']);
        }

        return redirect(route('admin:parts.index'))
            ->with(['message' => __('words.parts-taxes-created'), 'alert-type' => 'success']);
    }

    public function newSupplier(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'supplier_count' => 'required|integer|min:0'
        ]);
        if ($validator->failed()) {
            return response()->json($validator->errors()->first(), 400);
        }
        try {
            $index = $request['supplier_count'] + 1;
            $suppliersQuery = Supplier::query();
            if ($request->has('branchId') && $request->branchId !== null) {
                $suppliersQuery = $suppliersQuery->where('branch_id', $request->branchId);
            }
            $suppliers = $suppliersQuery->get();
            $view = view('admin.parts.suppliers.ajax_form_new_supplier', compact('index', 'suppliers'))->render();
        } catch (Exception $exception) {
            return response()->json('sorry, please try later', 400);
        }
        return response()->json([
            'view' => $view,
            'index' => $index
        ], 200);
    }

    public function getSupplierBYId(Request $request)
    {
        try {
            if ($request->supplier_id && $request->supplier_id != null) {
                $supplier = Supplier::find($request->supplier_id);
                if ($supplier) {
                    return response()->json([
                        'phone' => $supplier->phone_1 . ' | ' . $supplier->phone_2,
                        'address' => $supplier->address,
                    ], 200);
                }
                return response()->json([
                    'status' => 404
                ]);
            }
        } catch (Exception $exception) {
            return response()->json('sorry, please try later', 400);
        }
    }
}
