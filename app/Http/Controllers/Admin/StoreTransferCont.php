<?php

namespace App\Http\Controllers\Admin;

use App\Models\Branch;
use App\Models\PartPrice;
use App\Models\SparePart;
use App\Services\StoreTransferServices;
use App\Traits\SubTypesServices;
use Exception;
use App\Models\Part;
use App\Models\Store;
use App\Scopes\BranchScope;
use Illuminate\Http\Request;
use App\Models\StoreTransfer;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ExportPrinterFactory;
use App\Http\Requests\Admin\StoresTransfersRequest;
use App\Http\Controllers\DataExportCore\StoresTransfers;
use App\Models\StoreTransferItem;
use Illuminate\Support\Facades\Validator;
use function foo\func;

class StoreTransferCont extends Controller
{
    use SubTypesServices;

    const view_path = 'admin.stores_transfer.';

    public $lang;
    public $storeTransferServices;

    public function __construct()
    {
        $this->lang = App::getLocale();
        $this->storeTransferServices = new StoreTransferServices();
    }

    public function index(Request $request)
    {

        if (!auth()->user()->can('view_stores_transfers')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $branch_id = NULL;

        if (authIsSuperAdmin()) {
            $branch_id = $request->has('branch_id') ? $request->branch_id : NULL;
        }

        $total = $request->has('total') ? $request->total : NULL;
        $concession_status = $request->has('concession_status') ? $request->concession_status : NULL;
        $store_from = $request->has('store_from') ? $request->store_from : NULL;
        $store_to = $request->has('store_to') ? $request->store_to : NULL;
        $date_from = $request->has('date_from') ? $request->date_from : NULL;
        $date_to = $request->has('date_to') ? $request->date_to : date('Y-m-d');
        $number = $request->has('transfer_number') ? $request->transfer_number : NULL;
        $barcode = $request->has('barcode') ? $request->barcode : NULL;
        $supplier_barcode = $request->has('supplier_barcode') ? $request->supplier_barcode : NULL;
        $partId = $request->has('partId') ? $request->partId : NULL;
        $key = $request->has('key') ? $request->key : NULL;
        $rows = $request->has('rows') ? $request->rows : 10;

        $orderBy = 'id';
        $orderMethod = 'DESC';

        if ($request->has('sort_by') && $request->sort_by != '') {
            $sort_by = $request->sort_by;
            $sort_method = $request->has('sort_method') ? $request->sort_method : 'asc';
            if (!in_array($sort_method, ['asc', 'desc'])) $sort_method = 'desc';
            $sort_fields = [
                'transfer-number' => 'transfer_number',
                'transfer-date' => 'transfer_date',
                'store-from' => 'store_from_id',
                'store-to' => 'store_to_id',
                'total' => 'total',
                'created_at' => 'created_at',
                'updated_at' => 'updated_at'
            ];
            if (authIsSuperAdmin()) {
                $sort_fields['branch'] = 'branch_id';
            }
            $orderBy = $sort_fields[$sort_by];
            $orderMethod = $sort_method;
        }

        $stores = Store::select('id', 'name_ar', 'name_en')->get();

        $parts = Part::select('id', 'name_' . $this->lang)->get();

        $numbers = StoreTransfer::select('id', 'transfer_number')->get();

        $collection = StoreTransfer::with(['store_from', 'store_to'])
            ->when($store_from, function ($q) use ($store_from) {
                $q->where('store_from_id', $store_from);
            })
            ->when($total, function ($q) use ($total) {
                $q->where('total', $total);
            })->when($store_to, function ($q) use ($store_to) {
                $q->where('store_to_id', $store_to);
            })
            ->when($date_from, function ($q) use ($date_from, $date_to) {
                $q->where('transfer_date', '>=', $date_from)->where('transfer_date', '<=', $date_to);
            })
            ->when($number, function ($q) use ($number) {
                $q->where('transfer_number', $number);
            })
            ->when($key, function ($q) use ($key) {
                $q->where('transfer_date', 'like', "%$key%")->orWhere('transfer_number', 'like', "%$key%");
            })
            ->when($branch_id, function ($q) use ($branch_id) {
                $q->where('branch_id', $branch_id);
            })
            ->when($barcode, function ($q) use ($barcode) {
                $partsIds = partPrice::where('barcode', $barcode)->pluck('part_id')->toArray();
                $q->whereHas('items', function ($q) use ($partsIds) {
                    $q->whereIn('part_id', $partsIds);
                });
            })->when($partId, function ($q) use ($partId) {
                $sparePart = SparePart::with('allParts')->find($partId);
                if ($sparePart) {
                    getSupPartsByMainPart($sparePart);
                    $partsIds = session('allPartsIds');
                    $partsIdsSearch = [];
                    if (!empty($partsIds)) {
                        $partsIdsSearch = array_unique(array_flatten($partsIds));
                    }
                    $q->whereHas('items', function ($q) use ($partsIdsSearch) {
                        $q->whereIn('part_id', $partsIdsSearch);
                    });
                } else {
                    $q->whereHas('items', function ($q) {
                        $q->whereIn('part_id', []);
                    });
                }
            })
            ->when($supplier_barcode, function ($q) use ($supplier_barcode) {
                $partsIds = partPrice::where('supplier_barcode', $supplier_barcode)->pluck('part_id')->toArray();
                $q->whereHas('items', function ($q) use ($partsIds) {
                    $q->whereIn('part_id', $partsIds);
                });

            })->when($concession_status, function ($q) use ($concession_status) {

                if ($concession_status == 'not_found') {

                    $q->doesntHave('concession');

                } else {

                    $q->whereHas('concession', function ($concession) use ($concession_status) {
                        $concession->where('status', $concession_status);
                    });
                }

            })->orderBy($orderBy, $orderMethod);

        if ($request->has('part_id') && $request['part_id'] != '') {

            $collection->whereHas('items', function ($q) use ($request) {

                $q->where('part_id', $request['part_id']);
            });
        }

        if ($request->has('invoker') && in_array($request->invoker, ['print', 'excel'])) {
            $visible_columns = $request->has('visible_columns') ? $request->visible_columns : [];
            return (new ExportPrinterFactory(new StoresTransfers($collection, $visible_columns), $request->invoker))();
        }

        $collection = $collection->paginate($rows)->appends(request()->query());
        return view(self::view_path . 'index', [
            'collection' => $collection,
            'stores' => $stores,
            'numbers' => $numbers,
            'parts' => $parts
        ]);
    }

    public function create(Request $request)
    {

        $branch_id = $request->has('branch_id') ? $request['branch_id'] : auth()->user()->branch_id;

        if (!auth()->user()->can('create_stores_transfers')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $stores = Store::where('branch_id', $branch_id)
            ->select('id', 'name_ar', 'name_en')
            ->get();

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

        $lastNumber = StoreTransfer::where('branch_id', $branch_id)->orderBy('id', 'desc')->first();

        $number = $lastNumber ? $lastNumber->transfer_number + 1 : 1;

        return view(self::view_path . 'create',
            compact('stores', 'branches', 'mainTypes', 'subTypes', 'parts', 'number'));
    }

    public function getStoreParts(Request $request)
    {

        $rules = [

            'store_id' => 'required|integer|exists:stores,id'
        ];

        $branch_id = auth()->user()->branch_id;

        if (authIsSuperAdmin()) {

            $rules['branch_id'] = 'required|integer|exists:branches,id';
            $branch_id = $request['branch_id'];
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return response()->json($validator->errors()->first(), 400);
        }

        try {

            $parts = Part::where('status', 1)->where('branch_id', $branch_id)->whereHas('stores', function ($q) use ($request) {

                $q->where('store_id', $request['store_id']);

            })->select('name_' . $this->lang, 'id')->get();

            $partsView = view('admin.parts_common_filters.ajax_parts', compact('parts'))->render();

            return response()->json(['parts' => $partsView], 200);

        } catch (\Exception $e) {

            return response()->json('sorry, please try later', 400);
        }
    }

    function get_branch_stores($branch_id)
    {

        $stores = Store::where('branch_id', $branch_id)->get();

        $options_html = '<option data-url="empty" value=""> ' . __('Select One') . '</option>';

        foreach ($stores as $store) {
            $url = route('admin:get-store-parts', ['store' => $store->id]);
            $options_html .= '<option value="' . $store->id . '" data-url="' . $url . '">' . $store->name . '</option>';
        }

        return response(['options' => $options_html]);
    }

    public function store(StoresTransfersRequest $request)
    {
        if (!auth()->user()->can('create_stores_transfers')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if ($this->storeTransferServices->checkMaxQuantityOfItem($request['items'], $request['store_from_id'])) {
            return redirect()->back()->with(['message' => __('quantity not available'), 'alert-type' => 'error']);
        }

        try {

            DB::beginTransaction();

            $data = $request->validated();

            $storeTransferData = $this->storeTransferServices->storeTransferData($data);

            $storeTransferData['branch_id'] = authIsSuperAdmin() ? $request['branch_id'] : auth()->user()->branch_id;

            $lastNumber = StoreTransfer::where('branch_id', $storeTransferData['branch_id'])->orderBy('id', 'desc')->first();

            $storeTransferData['transfer_number'] = $lastNumber ? $lastNumber->transfer_number + 1 : 1;

            $storeTransferData['user_id'] = auth()->id();

            $storeTransfer = StoreTransfer::create($storeTransferData);

            if (isset($data['items'])) {

                foreach ($data['items'] as $item) {

                    $itemData = $this->storeTransferServices->storeTransferItemData($item);

                    $itemData['store_transfer_id'] = $storeTransfer->id;

                    StoreTransferItem::create($itemData);
                }
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            return redirect()->back()->with(['message' => $e->getMessage(), 'alert-type' => 'error']);
        }

        return redirect(route('admin:stores-transfers.index'))->with(['message' => __('words.transfer-created'), 'alert-type' => 'success']);
    }

    public function show($id)
    {
        if (!auth()->user()->can('print_stores_transfers')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        try {

            $storeTransfer = StoreTransfer::findOrFail($id);
            return view(self::view_path . 'show', compact('storeTransfer'));

        } catch (Exception $e) {
            return response(['message' => __('words.transfer-not-found')], 400);
        }
    }

    public function edit($id)
    {

        $storeTransfer = StoreTransfer::findOrFail($id);

        if ($storeTransfer->concession) {
            return redirect()->back()->with(['message' => __('sorry, this item has related data'), 'alert-type' => 'error']);
        }

        if (!auth()->user()->can('create_stores_transfers')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $stores = Store::where('branch_id', $storeTransfer->branch_id)->select('id', 'name_ar', 'name_en')->get();

        $branches = Branch::select('id', 'name_' . $this->lang)->get();

        $mainTypes = SparePart::where('branch_id', $storeTransfer->branch_id)
            ->where('status', 1)
            ->where('spare_part_id', null)
            ->select('id', 'type_' . $this->lang)
            ->get();

        $subTypes = $this->getSubPartTypes($mainTypes);

        $parts = Part::where('branch_id', $storeTransfer->branch_id)
            ->where('status', 1)
            ->whereHas('stores', function ($q) use ($storeTransfer) {
                $q->where('store_id', $storeTransfer->store_from_id);
            })
            ->select('name_' . $this->lang, 'id')
            ->get();

        return view(self::view_path . 'edit',
            compact('stores', 'branches', 'mainTypes', 'subTypes', 'parts', 'storeTransfer'));

    }

    public function update(StoresTransfersRequest $request, $id)
    {
        if (!auth()->user()->can('edit_stores_transfers')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if ($this->storeTransferServices->checkMaxQuantityOfItem($request['items'], $request['store_from_id'])) {

            return redirect()->back()->with(['message' => __('quantity not available'), 'alert-type' => 'error']);
        }

        $storeTransfer = StoreTransfer::findOrFail($id);

        if ($storeTransfer->concession) {
            return redirect()->back()->with(['message' => __('sorry, this item has related data'), 'alert-type' => 'error']);
        }

        try {

            DB::beginTransaction();

            $this->storeTransferServices->resetItems($storeTransfer);

            $data = $request->validated();

            $storeTransferData = $this->storeTransferServices->storeTransferData($data);

            $storeTransfer->update($storeTransferData);

            if (isset($data['items'])) {

                foreach ($data['items'] as $item) {

                    $itemData = $this->storeTransferServices->storeTransferItemData($item);

                    $itemData['store_transfer_id'] = $storeTransfer->id;

                    StoreTransferItem::create($itemData);
                }
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            return redirect()->back()->with(['message' => $e->getMessage(), 'alert-type' => 'error']);
        }

        return redirect(route('admin:stores-transfers.index'))->with(['message' => __('words.transfer-updated'), 'alert-type' => 'success']);
    }

    public function destroy($id)
    {
        if (!auth()->user()->can('delete_stores_transfers')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $storeTransfer = StoreTransfer::findOrFail($id);

        if ($storeTransfer->concession) {
            return redirect()->back()->with(['message' => __('sorry, this item has related data'), 'alert-type' => 'error']);
        }

        try {

            DB::beginTransaction();

            foreach ($storeTransfer->items as $item) {

                $item->forceDelete();
            }

            $storeTransfer->forceDelete();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->back()->with(['message' => $e->getMessage(), 'alert-type' => 'error']);
        }

        return redirect(route('admin:stores-transfers.index'))->with(['message' => __('words.transfer-deleted'), 'alert-type' => 'success']);
    }

    function deleteSelected(Request $request)
    {
        if (!auth()->user()->can('delete_stores_transfers')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if (isset($request->ids)) {
            try {
                $sent_ids = [];
                DB::beginTransaction();
                foreach ($request->ids as $index => $id) {
                    $storeTransfer = StoreTransfer::find($id);
                    if ($storeTransfer) {
                        if ($storeTransfer->concession) {
                            return redirect()->back()->with(['message' => __('sorry, this item has related data'), 'alert-type' => 'error']);
                        }
                        foreach ($storeTransfer->items as $item) {
                            $item->forceDelete();
                        }
                        $storeTransfer->forceDelete();
                    }
                }
                DB::commit();
            } catch (Exception $e) {
                dd($e->getMessage(), $e->getLine(), $e->getFile());
                DB::rollBack();
                return redirect()->back()->with(['message' => $e->getMessage(), 'alert-type' => 'error']);
            }
            return redirect(route('admin:stores-transfers.index'))
                ->with(['message' => __('words.selected-row-deleted'), 'alert-type' => 'success']);
        }
        return redirect(route('admin:stores-transfers.index'))
            ->with(['message' => __('words.select-one-least'), 'alert-type' => 'error']);
    }

    public function selectPartRaw(Request $request)
    {
        $rules = [

            'part_id' => 'required|integer|exists:parts,id',
            'store_id' => 'required|integer|exists:stores,id',
            'index' => 'required|integer'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return response()->json($validator->errors()->first(), 400);
        }

        try {

            $index = $request['index'] + 1;

            $part = Part::find($request['part_id']);

            $max = 0;

            $store = $part->stores()->where('store_id', $request['store_id'])->first();

            if ($store) {

                $max += $store->pivot->quantity;
            }

            $view = view('admin.stores_transfer.part_raw', compact('part', 'index'))->render();

            return response()->json(['parts' => $view, 'index' => $index, 'max_quantity' => $max], 200);

        } catch (\Exception $e) {
            return response()->json('sorry, please try later', 400);
        }
    }

    public function getPriceSegments(Request $request)
    {

        $rules = [
            'part_price_id' => 'required|integer|exists:part_prices,id',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        try {

            $partPrice = PartPrice::find($request['part_price_id']);

            $options_html = '<option value=""> ' . __('Select') . '</option>';

            foreach ($partPrice->partPriceSegments as $partPriceSegment) {
                $options_html .= '<option value="' . $partPriceSegment->id . '" data-purchase-price="' . $partPriceSegment->purchase_price . '" >'
                    . $partPriceSegment->name . '</option>';
            }

            return response()->json(['segments' => $options_html], 200);

        } catch (\Exception $e) {

            return response()->json('sorry, please try later', 400);
        }

    }
}
