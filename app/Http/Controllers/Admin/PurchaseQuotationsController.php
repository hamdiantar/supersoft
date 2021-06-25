<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\PurchaseQuotation\CreateRequest;
use App\Models\Branch;
use App\Models\Part;
use App\Models\PartPriceSegment;
use App\Models\PurchaseQuotation;
use App\Models\PurchaseQuotationItem;
use App\Models\PurchaseRequest;
use App\Models\SparePart;
use App\Models\Supplier;
use App\Models\SupplyTerm;
use App\Models\TaxesFees;
use App\Services\PurchaseQuotationServices;
use App\Traits\SubTypesServices;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PurchaseQuotationsController extends Controller
{
    use SubTypesServices;

    public $lang;
    public $purchaseQuotationServices;

    public function __construct()
    {
        $this->lang = App::getLocale();
        $this->purchaseQuotationServices = new PurchaseQuotationServices();
    }

    public function index (Request $request) {

        $data = PurchaseQuotation::get();

        $paymentTerms = SupplyTerm::where('for_purchase_quotation', 1)->where('status', 1)->where('type', 'payment')
            ->select('id', 'term_' . $this->lang)->get();

        $supplyTerms = SupplyTerm::where('for_purchase_quotation', 1)->where('status', 1)->where('type', 'supply')
            ->select('id', 'term_' . $this->lang)->get();

        return view('admin.purchase_quotations.index', compact('data', 'supplyTerms', 'paymentTerms'));
    }

    public function create (Request $request) {

        $branch_id = $request->has('branch_id') ? $request['branch_id'] : auth()->user()->branch_id;

        $branches = Branch::select('id', 'name_' . $this->lang)->get();

        $mainTypes = SparePart::where('status', 1)
            ->where('branch_id', $branch_id)
            ->where('spare_part_id', null)
            ->select('id', 'type_' . $this->lang)
            ->get();

        $subTypes = $this->getSubPartTypes($mainTypes);

        $parts = Part::where('status', 1)
            ->where('branch_id', $branch_id)
            ->select('name_' . $this->lang, 'id')
            ->get();

        $purchaseRequests = PurchaseRequest::where('status','accept_approval')
            ->where('branch_id', $branch_id)
            ->select('id','number')
            ->get();

        $suppliers = Supplier::where('status', 1)
            ->where('branch_id', $branch_id)
            ->select('id','name_' . $this->lang, 'group_id', 'sub_group_id')
            ->get();

        $taxes = TaxesFees::where('purchase_quotation', 1)
            ->where('branch_id', $branch_id)
            ->where('type', 'tax')
            ->select('id','value', 'tax_type','execution_time', 'name_' . $this->lang)
            ->get();

        $additionalPayments = TaxesFees::where('purchase_quotation', 1)
            ->where('branch_id', $branch_id)
            ->where('type', 'additional_payments')
            ->select('id','value', 'tax_type','execution_time', 'name_' . $this->lang)
            ->get();

        return view('admin.purchase_quotations.create',
            compact('branches', 'mainTypes', 'subTypes','additionalPayments',
                'parts', 'purchaseRequests', 'suppliers', 'taxes'));
    }

    public function store(CreateRequest $request) {

        if (!$request->has('items')) {
            return redirect()->back()->with(['message'=>'sorry, please select items', 'alert-type'=>'error']);
        }

        try {

            DB::beginTransaction();

            $data = $request->all();

            $purchaseQuotationData =  $this->purchaseQuotationServices->PurchaseQuotationData($data);

            $purchaseQuotationData['user_id'] = auth()->id();
            $purchaseQuotationData['branch_id'] = authIsSuperAdmin() ? $data['branch_id']  : auth()->user()->branch_id;

            $purchaseQuotation = PurchaseQuotation::create($purchaseQuotationData);

            $this->purchaseQuotationServices->purchaseQuotationTaxes($purchaseQuotation, $data);

            if (isset($data['terms'])) {
                $purchaseQuotation->terms()->attach($data['terms']);
            }

            foreach ($data['items'] as $item) {

                $itemData = $this->purchaseQuotationServices->PurchaseQuotationItemData($item);
                $itemData['purchase_quotation_id'] = $purchaseQuotation->id;
                $purchaseQuotationItem = PurchaseQuotationItem::create($itemData);

                if (isset($item['taxes'])) {
                    $purchaseQuotationItem->taxes()->attach($item['taxes']);
                }

                if (isset($item['item_types'])) {

                    foreach ($item['item_types'] as $item_type) {

                        if (!isset($item_type['id'])) {
                            continue;
                        }

                        DB::table('purchase_quotation_items_spare_parts')
                            ->insert([
                                'item_id' => $purchaseQuotationItem->id,
                                'spare_part_id' => $item_type['id'],
                                'price' => $item_type['price'],
                            ]);
                    }
                }
            }

            DB::commit();
        }catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with(['message'=>'sorry, please try later', 'alert-type'=>'error']);
        }

        return redirect(route('admin:purchase-quotations.index'))->with(['message'=> __('purchase.quotations.created.successfully'), 'alert-type'=>'success']);
    }

    public function edit (PurchaseQuotation $purchaseQuotation) {

        $branch_id = $purchaseQuotation->branch_id;

        $branches = Branch::select('id', 'name_' . $this->lang)->get();

        $mainTypes = SparePart::where('status', 1)
            ->where('branch_id', $branch_id)
            ->where('spare_part_id', null)
            ->select('id', 'type_' . $this->lang)
            ->get();

        $subTypes = $this->getSubPartTypes($mainTypes);

        $parts = Part::where('status', 1)
            ->where('branch_id', $branch_id)
            ->select('name_' . $this->lang, 'id')
            ->get();

        $purchaseRequests = PurchaseRequest::where('status','accept_approval')
            ->where('branch_id', $branch_id)
            ->select('id','number')
            ->get();

        $suppliers = Supplier::where('status', 1)
            ->where('branch_id', $branch_id)
            ->select('id','name_' . $this->lang, 'group_id', 'sub_group_id')
            ->get();

        $taxes = TaxesFees::where('purchase_quotation', 1)
            ->where('branch_id', $branch_id)
            ->where('type', 'tax')
            ->select('id','value', 'tax_type', 'execution_time', 'name_' . $this->lang)
            ->get();

        $additionalPayments = TaxesFees::where('purchase_quotation', 1)
            ->where('branch_id', $branch_id)
            ->where('type', 'additional_payments')
            ->select('id','value', 'tax_type','execution_time', 'name_' . $this->lang)
            ->get();

        return view('admin.purchase_quotations.edit',
            compact('branches', 'mainTypes', 'subTypes', 'parts', 'purchaseQuotation',
                'purchaseRequests', 'suppliers', 'taxes', 'additionalPayments'));
    }

    public function update (CreateRequest $request, PurchaseQuotation $purchaseQuotation) {

        if (!$request->has('items')) {
            return redirect()->back()->with(['message'=>'sorry, please select items', 'alert-type'=>'error']);
        }

        try {

            DB::beginTransaction();

            $this->purchaseQuotationServices->resetPurchaseQuotationItems($purchaseQuotation);

            $data = $request->all();

            $purchaseQuotationData =  $this->purchaseQuotationServices->PurchaseQuotationData($data);

            $purchaseQuotation->update($purchaseQuotationData);

            $this->purchaseQuotationServices->purchaseQuotationTaxes($purchaseQuotation, $data);

            if (isset($data['terms'])) {
                $purchaseQuotation->terms()->attach($data['terms']);
            }

            foreach ($data['items'] as $item) {

                $itemData = $this->purchaseQuotationServices->PurchaseQuotationItemData($item);
                $itemData['purchase_quotation_id'] = $purchaseQuotation->id;
                $purchaseQuotationItem = PurchaseQuotationItem::create($itemData);

                if (isset($item['taxes'])) {
                    $purchaseQuotationItem->taxes()->attach($item['taxes']);
                }

                if (isset($item['item_types'])) {

                    foreach ($item['item_types'] as $item_type) {

                        if (!isset($item_type['id'])) {
                            continue;
                        }

                        DB::table('purchase_quotation_items_spare_parts')
                            ->insert([
                                'item_id' => $purchaseQuotationItem->id,
                                'spare_part_id' => $item_type['id'],
                                'price' => $item_type['price'],
                            ]);
                    }
                }
            }

            DB::commit();
        }catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with(['message'=>'sorry, please try later', 'alert-type'=>'error']);
        }

        return redirect(route('admin:purchase-quotations.index'))->with(['message'=> __('purchase.quotations.created.successfully'), 'alert-type'=>'success']);
    }

    public function destroy (PurchaseQuotation $purchaseQuotation) {

        try {

            $purchaseQuotation->delete();

        }catch (\Exception $e) {
            return redirect()->back()->with(['message'=>'sorry, please try later', 'alert-type'=>'error']);
        }

        return redirect()->back()->with(['message'=> __('purchase.quotations.deleted.successfully'), 'alert-type'=>'success']);
    }

    public function selectPurchaseRequest (Request $request) {

        $rules = [
            'purchase_request_id' => 'required|integer|exists:purchase_requests,id',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        try {

            $purchaseRequest = PurchaseRequest::with('items')->find($request['purchase_request_id']);

            $itemsCount = $purchaseRequest->items->count();

            $view = view('admin.purchase_quotations.purchase_request_items', compact('purchaseRequest'))->render();

            $partTypesViews = [];

            foreach($purchaseRequest->items as $index=>$item) {

                $selectedTypes = $item->spareParts->pluck('id')->toArray();

                $index +=1;

                $part = $item->part;

                $partMainTypes = $part->spareParts()->where('status', 1)->whereNull('spare_part_id')->orderBy('id', 'ASC')->get();

                $partTypes = $this->purchaseQuotationServices->getPartTypes($partMainTypes, $part->id);

                $partTypesViews[$index] = view('admin.purchase_quotations.part_types',
                    compact( 'part','index', 'partTypes', 'selectedTypes'))->render();
            }

            return response()->json(['view' => $view,  'index' => $itemsCount, 'partTypesViews'=>$partTypesViews], 200);

        } catch (\Exception $e) {

//            dd($e->getMessage());
            return response()->json('sorry, please try later', 400);
        }

    }

    public function selectPartRaw(Request $request)
    {
        $rules = [
            'part_id'=>'required|integer|exists:parts,id',
            'index'=>'required|integer'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        try {

            $index = $request['index'] + 1;

            $part = Part::find($request['part_id']);

            $view = view('admin.purchase_quotations.part_raw', compact('part', 'index'))->render();

            $partMainTypes = $part->spareParts()->where('status', 1)->whereNull('spare_part_id')->orderBy('id', 'ASC')->get();

            $partTypes = $this->purchaseQuotationServices->getPartTypes($partMainTypes, $part->id);

            $partTypesView = view('admin.purchase_quotations.part_types', compact( 'part','index', 'partTypes'))->render();

            return response()->json(['parts'=> $view, 'partTypesView'=> $partTypesView, 'index'=> $index], 200);

        }catch (\Exception $e) {

            return response()->json('sorry, please try later', 400);
        }
    }

    public function print(Request $request)
    {
        $purchaseQuotation = PurchaseQuotation::findOrFail($request['purchase_quotation_id']);

        $view = view('admin.purchase_quotations.print', compact('purchaseQuotation'))->render();

        return response()->json(['view' => $view]);
    }

    public function terms (Request $request) {

        $this->validate($request, [
            'purchase_quotation_id'=>'required|integer|exists:purchase_quotations,id'
        ]);

        try {

            $purchaseQuotation = PurchaseQuotation::find($request['purchase_quotation_id']);

            $purchaseQuotation->terms()->sync($request['terms']);

        }catch (\Exception $e) {
            return redirect()->back()->with(['message'=>'sorry, please try later', 'alert-type'=>'error']);
        }

        return redirect(route('admin:purchase-quotations.index'))->with(['message'=> __('purchase.quotations.terms.successfully'), 'alert-type'=>'success']);
    }

    public function priceSegments(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'price_id'=>'required|integer|exists:part_prices,id',
            'index'=>'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        try {

            $index = $request['index'];

            $priceSegments = PartPriceSegment::where('part_price_id', $request['price_id'])->get();

            $view = view('admin.purchase_quotations.ajax_price_segments', compact('priceSegments', 'index'))->render();

            return response()->json(['view'=> $view], 200);

        }catch (\Exception $e) {
            return response()->json('sorry, please try later', 400);
        }
    }
}
