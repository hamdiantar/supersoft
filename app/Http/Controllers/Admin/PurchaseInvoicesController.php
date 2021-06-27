<?php

namespace App\Http\Controllers\Admin;

use App\Models\PartPrice;
use App\Models\PartPriceSegment;
use App\Models\PurchaseQuotation;
use App\Models\PurchaseReceipt;
use App\Models\SupplyOrder;
use App\Traits\SubTypesServices;
use Exception;
use App\Models\Part;
use App\Models\Store;
use App\Models\Branch;
use App\Models\Supplier;
use App\Models\SparePart;
use App\Models\TaxesFees;
use Illuminate\Support\Facades\App;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\ExpensesReceipt;
use App\Models\PurchaseInvoice;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Models\PurchaseInvoiceItem;
use App\Http\Controllers\Controller;
use App\Filters\PurchaseInvoiceFilter;
use Illuminate\Support\Facades\Response;
use App\Services\PurchaseInvoiceServices;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ExportPrinterFactory;
use App\Http\Controllers\DataExportCore\Invoices\Purchase;
use App\Http\Requests\Admin\PurchaseInvoice\PurchaseInvoiceRequest;

class PurchaseInvoicesController extends Controller
{

    use PurchaseInvoiceServices, SubTypesServices;

    /**
     * @var PurchaseInvoiceItemsController
     */
    protected $purchaseInvoiceItemsController;
    public $lang;

    /**
     * @var PurchaseInvoiceFilter
     */
    protected $purchaseInvoiceFilter;

    public function __construct(
        PurchaseInvoiceItemsController $purchaseInvoiceItemsController,
        PurchaseInvoiceFilter $purchaseInvoiceFilter
    )
    {

        $this->lang = App::getLocale();
        $this->purchaseInvoiceItemsController = $purchaseInvoiceItemsController;
        $this->purchaseInvoiceFilter = $purchaseInvoiceFilter;
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('view_purchase_invoices')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $invoices = PurchaseInvoice::query();
        if ($request->hasAny((new PurchaseInvoice())->getFillable())) {
            $invoices = $this->purchaseInvoiceFilter->filter($request);
        }
        if ($request->has('sort_by') && $request->sort_by != '') {
            $sort_by = $request->sort_by;
            $sort_method = $request->has('sort_method') ? $request->sort_method : 'asc';
            if (!in_array($sort_method, ['asc', 'desc'])) $sort_method = 'desc';
            $sort_fields = [
                'invoice-number' => 'invoice_number',
                'supplier' => 'supplier_id',
                'invoice-type' => 'type',
                'payment' => 'remaining',
                'paid' => 'paid',
                'remaining' => 'remaining',
                'created-at' => 'created_at',
                'updated-at' => 'updated_at'
            ];
            $invoices = $invoices->orderBy($sort_fields[$sort_by], $sort_method);
        } else {
            $invoices = $invoices->orderBy('id', 'DESC');
        }
        if ($request->has('key')) {
            $key = $request->key;
            $invoices->where(function ($q) use ($key) {
                $q->where('invoice_number', 'like', "%$key%")
                    ->orWhere('remaining', 'like', "%$key%")
                    ->orWhere('paid', 'like', "%$key%");
            });
        }
        if ($request->has('invoker') && in_array($request->invoker, ['print', 'excel'])) {
            $visible_columns = $request->has('visible_columns') ? $request->visible_columns : [];
            return (new ExportPrinterFactory(new Purchase($invoices->with('supplier'), $visible_columns), $request->invoker))();
        }
        $rows = $request->has('rows') ? $request->rows : 10;

        $invoices = $invoices->paginate($rows)->appends(request()->query());
        return view('admin.purchase-invoices.index', compact('invoices'));
    }

    public function create(Request $request)
    {
        if (!auth()->user()->can('create_purchase_invoices')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $branch_id = $request->has('branch_id') ? $request['branch_id'] : auth()->user()->branch_id;

        $data['branches'] = Branch::where('status', 1)->select('id', 'name_' . $this->lang)->get();

        $data['mainTypes'] = SparePart::where('status', 1)
            ->where('branch_id', $branch_id)
            ->where('spare_part_id', null)
            ->select('id', 'type_' . $this->lang)
            ->get();

        $data['subTypes'] = $this->getSubPartTypes($data['mainTypes']);

        $data['parts'] = Part::where('status', 1)
            ->where('branch_id', $branch_id)
            ->select('name_' . $this->lang, 'id')
            ->get();

        $data['taxes'] = TaxesFees::where('active_purchase_invoice', 1)
            ->where('branch_id', $branch_id)
            ->where('type', 'tax')
            ->select('id', 'value', 'tax_type', 'execution_time', 'name_' . $this->lang)
            ->get();

        $data['additionalPayments'] = TaxesFees::where('active_purchase_invoice', 1)
            ->where('branch_id', $branch_id)
            ->where('type', 'additional_payments')
            ->select('id', 'value', 'tax_type', 'execution_time', 'name_' . $this->lang)
            ->get();

        $data['suppliers'] = Supplier::where('status', 1)
            ->where('branch_id', $branch_id)
            ->select('id', 'name_' . $this->lang, 'group_id', 'sub_group_id')
            ->get();

        $data['supplyOrders'] = SupplyOrder::where('status', 'accept')
            ->where('branch_id', $branch_id)
            ->select('id', 'number')
            ->get();

        return view('admin.purchase-invoices.create', compact('data'));
    }

    public function store(PurchaseInvoiceRequest $request)
    {
        if (!auth()->user()->can('create_purchase_invoices')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        try {

            DB::beginTransaction();

            $data = $request->all();

            $invoice_data = $this->prepareInvoiceData($data);

            $invoice_data['branch_id'] = authIsSuperAdmin() ? $request['branch_id'] : auth()->user()->branch_id;

            $purchaseInvoice = PurchaseInvoice::create($invoice_data);

            $this->PurchaseInvoiceTaxes($purchaseInvoice, $data);

            if (isset($data['purchase_receipts'])) {
                $purchaseInvoice->purchaseReceipts()->attach($data['purchase_receipts']);
            }

            foreach ($data['items'] as $item) {

                $item_data = $this->calculateItemTotal($item);

                $item_data['purchase_invoice_id'] = $purchaseInvoice->id;

                $purchaseInvoiceItem = PurchaseInvoiceItem::create($item_data);

                if (isset($item['taxes'])) {
                    $purchaseInvoiceItem->taxes()->attach($item['taxes']);
                }

                if ($purchaseInvoice->status == 'accept' && $purchaseInvoice->invoice_type = 'normal') {
                    $this->affectedPart($purchaseInvoiceItem);
                }
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            dd($e->getMessage());

            return redirect()->to('admin/purchase-invoices')
                ->with(['message' => __('words.purchase-invoice-cant-created'), 'alert-type' => 'error']);
        }

        return redirect()->to(route('admin:purchase-invoices.index'))
            ->with(['message' => __('words.purchase-invoice-created'), 'alert-type' => 'success']);

//        return redirect()->to('admin/expenseReceipts/create?invoice_id=' . $purchaseInvoice->id)
//            ->with(['message' => __('words.purchase-invoice-created'), 'alert-type' => 'success']);

    }

    public function show(Request $request)
    {
        if (!auth()->user()->can('view_purchase_invoices')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $purchase_invoice = PurchaseInvoice::find($request->invoiceID);

        $taxes = TaxesFees::where('active_purchase_invoice', 1)->where('branch_id', $purchase_invoice->branch_id)->get();

        $totalTax = TaxesFees::where('active_purchase_invoice', 1)->where('branch_id', $purchase_invoice->branch_id)->sum('value');

        $invoice = view('admin.purchase-invoices.show', compact('purchase_invoice', 'taxes', 'totalTax'))->render();

        return response()->json(['invoice' => $invoice]);
    }

    public function edit(PurchaseInvoice $purchaseInvoice)
    {
        if (!auth()->user()->can('update_purchase_invoices')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if ($purchaseInvoice->status == 'accept' && $purchaseInvoice->invoice_type = 'normal') {

            return redirect()->back()->with(['message' => __('words.purchase-invoice-accepted'),
                'alert-type' => 'error']);
        }

        $data['branches'] = Branch::where('status', 1)->select('id', 'name_' . $this->lang)->get();

        $branch_id = $purchaseInvoice->branch_id;

        $data['mainTypes'] = SparePart::where('status', 1)
            ->where('branch_id', $branch_id)
            ->where('spare_part_id', null)
            ->select('id', 'type_' . $this->lang)
            ->get();

        $data['subTypes'] = $this->getSubPartTypes($data['mainTypes']);

        $data['parts'] = Part::where('status', 1)
            ->where('branch_id', $branch_id)
            ->select('name_' . $this->lang, 'id')
            ->get();

        $data['taxes'] = TaxesFees::where('active_purchase_invoice', 1)
            ->where('branch_id', $branch_id)
            ->where('type', 'tax')
            ->select('id', 'value', 'tax_type', 'execution_time', 'name_' . $this->lang)
            ->get();

        $data['additionalPayments'] = TaxesFees::where('active_purchase_invoice', 1)
            ->where('branch_id', $branch_id)
            ->where('type', 'additional_payments')
            ->select('id', 'value', 'tax_type', 'execution_time', 'name_' . $this->lang)
            ->get();

        $data['suppliers'] = Supplier::where('status', 1)
            ->where('branch_id', $branch_id)
            ->select('id', 'name_' . $this->lang, 'group_id', 'sub_group_id')
            ->get();

        $data['supplyOrders'] = SupplyOrder::where('status', 'accept')
            ->where('branch_id', $branch_id)
            ->select('id', 'number')
            ->get();

        $data['purchaseReceipts'] = PurchaseReceipt::where('supply_order_id', $purchaseInvoice->supply_order_id)
            ->where(function ($q) use ($purchaseInvoice) {

                $q->doesntHave('purchaseInvoices')
                    ->orWhereHas('purchaseInvoices', function ($supply) use ($purchaseInvoice) {
                        $supply->where('purchase_invoice_id', $purchaseInvoice->id);
                    });
            })
            ->select('id', 'number', 'supplier_id')->get();

        return view('admin.purchase-invoices.edit', compact('data', 'purchaseInvoice'));
    }

    public function update(PurchaseInvoiceRequest $request, PurchaseInvoice $purchaseInvoice)
    {
        if (!auth()->user()->can('update_purchase_invoices')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if ($purchaseInvoice->expenseReceipt->count()) {
            return redirect()->back()->with(['message' => __('words.purchase-invoice-paid'), 'alert-type' => 'error']);
        }

        if ($purchaseInvoice->invoiceReturn) {
            return redirect()->back()->with(['message' => __('words.purchase-invoice-returned'),
                'alert-type' => 'error']);
        }

        if ($purchaseInvoice->status == 'accept' && $purchaseInvoice->invoice_type = 'normal') {

            return redirect()->back()->with(['message' => __('words.purchase-invoice-accepted'),
                'alert-type' => 'error']);
        }

        try {

            DB::beginTransaction();

            $this->resetPurchaseInvoiceDataItems($purchaseInvoice);

            $data = $request->all();

            $invoice_data = $this->prepareInvoiceData($data);

            $purchaseInvoice->update($invoice_data);

            $this->PurchaseInvoiceTaxes($purchaseInvoice, $data);

            if (isset($data['purchase_receipts'])) {
                $purchaseInvoice->purchaseReceipts()->attach($data['purchase_receipts']);
            }

            foreach ($data['items'] as $item) {

                $item_data = $this->calculateItemTotal($item);

                $item_data['purchase_invoice_id'] = $purchaseInvoice->id;

                $purchaseInvoiceItem = PurchaseInvoiceItem::create($item_data);

                if (isset($item['taxes'])) {
                    $purchaseInvoiceItem->taxes()->attach($item['taxes']);
                }

                if ($purchaseInvoice->status == 'accept' && $purchaseInvoice->invoice_type = 'normal') {
                    $this->affectedPart($purchaseInvoiceItem);
                }
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
            dd($e->getMessage());

            return redirect()->to('admin/purchase-invoices')
                ->with(['message' => __('words.purchase-invoice-cant-updated'), 'alert-type' => 'error']);
        }

        return redirect()->to('/admin/purchase-invoices')
            ->with(['message' => __('words.purchase-invoice-updated'), 'alert-type' => 'success']);

    }

    public function destroy(PurchaseInvoice $purchaseInvoice)
    {
        if (!auth()->user()->can('delete_purchase_invoices')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if ($purchaseInvoice->status == 'accept' && $purchaseInvoice->invoice_type = 'normal') {

            return redirect()->back()->with(['message' => __('words.purchase-invoice-accepted'),
                'alert-type' => 'error']);
        }

        $purchaseInvoice->deletePurchaseInvoice();

        return redirect()->back()
            ->with(['message' => __('words.purchase-invoice-deleted'), 'alert-type' => 'success']);
    }

    public function deleteSelected(Request $request)
    {
        if (!auth()->user()->can('delete_purchase_invoices')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if (isset($request->ids)) {

            foreach ($request->ids as $invoiceId) {

                $purchaseInvoice = PurchaseInvoice::find($invoiceId);

                if ($purchaseInvoice->status == 'accept' && $purchaseInvoice->invoice_type = 'normal') {
                    continue;
                }

                $purchaseInvoice->deletePurchaseInvoice();
            }

            return redirect()->back()
                ->with(['message' => __('words.selected-row-deleted'), 'alert-type' => 'success']);
        }

        return redirect()->back()
            ->with(['message' => __('words.select-one-least'), 'alert-type' => 'error']);
    }

    public function showExpenseForInvoice(Request $request, int $id): View
    {
        $invoice = PurchaseInvoice::find($id);
        $expenses = ExpensesReceipt::where('purchase_invoice_id', $invoice->id);
        if ($request->hasAny((new ExpensesReceipt())->getFillable())) {
            $expenses = $this->expenseReceiptFilter->filterInvoices($expenses, $request);
        }
        $expenses = $expenses->get();
        $expenseSum = ExpensesReceipt::where('purchase_invoice_id', $invoice->id)->sum('cost');
        $remaining = $invoice->total_after_discount - $expenseSum;
        return view('admin.purchase-invoices.parts.expenses', compact('expenses', 'invoice', 'expenseSum', 'remaining'));
    }

    public function getPartsBySparePartId(Request $request)
    {
        if ($request->spare_part_id === 'all') {
            if (authIsSuperAdmin()) {
                $parts = Part::where('status', 1)->whereHas('store', function ($q) use ($request) {
                    $q->where('branch_id', $request['branch_id']);
                })->get();
            }
            if (false == authIsSuperAdmin()) {
                $parts = Part::where('status', 1)->whereHas('store', function ($q) use ($request) {
                    $q->where('branch_id', auth()->user()->branch_id);
                })->get();
            }
        } else {
            $parts = Part::where('spare_part_type_id', $request->spare_part_id)->where('status', 1)->get();
        }

        if ($parts->count() > 0) {
            $htmlParts = '';
            foreach ($parts as $part) {
                $imageUrl = $part->img ? url('storage/images/parts/' . $part->img) : url('default-images/defualt.png');
                $htmlParts .= '                                                    <td class="align-center col-xs-3">
                <div class="card">
    <div class="card__image-holder">
    <a class="example-image-link" href="' . $imageUrl . '" data-lightbox="example-1">
<img  class="example-image"
src="' . $imageUrl . '" id="output_image"/>

<div class="frame"></div>
</a>
    </div>
    <a class="nav-link active" onclick="getPartsDetails(' . $part->id . ')" href="#" id="part_details">
    <div class="card-title">
    <h2 class="text-center h2-inv" style="font-size: 12px !important">
            ' . $part->name . ' <p style="display: none">' . $part->barcode . '</p>
            <i class="fa fa-plus"></i>
        </h2>
    </div>
</div>
</a>
                </td>';
            }
            return response()->json([
                'parts' => $htmlParts,
            ]);
        }
        if ($parts->count() == 0) {
            return response()->json([
                'parts' => '<h4>' . __('words.No Data Available') . '</h4>',
            ]);
        }
    }

    public function getPartsDetailsByID(Request $request)
    {
        $items_count = $request['items_count'] + 1;
        $parts_count = $request['parts_count'] + 1;

        $part = Part::find($request->part_id);
        $stores = Store::where('branch_id', $request['branch_id'])->get()->pluck('name', 'id');

//        dd($part->prices->first());

        $view = view('admin.purchase-invoices.parts.part_details', compact('stores', 'part', 'items_count'))->render();

        return response()->json(['view' => $view, 'items_count' => $items_count, 'parts_count' => $parts_count]);
    }

    public function addSupplier(Request $request)
    {
        try {

            $rules = $this->validationRules();

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return Response::json($validator->errors()->first(), 400);
            }

            $data = $request->all();

            $data['status'] = 0;

            if ($request->has('status'))
                $data['status'] = 1;

            if (!authIsSuperAdmin())
                $data['branch_id'] = auth()->user()->branch_id;

            $supplier = Supplier::create($data);

            $suppliers = Supplier::all();

        } catch (\Exception $e) {

            return Response::json(__('words.back-support'), 400);
        }

        return view('admin.purchase-invoices.supplier.ajax_supplier', compact('suppliers', 'supplier'));
    }

    public function validationRules()
    {

        $rules = [
            'country_id' => 'nullable|integer|exists:countries,id',
            'city_id' => 'nullable|integer|exists:cities,id',
            'area_id' => 'nullable|integer|exists:areas,id',
            'group_id' => 'nullable|integer|exists:supplier_groups,id',
            'phone_1' => 'nullable|string', //|unique:suppliers,phone_1,NULL,id,deleted_at,NULL
            'phone_2' => 'nullable|string', //|unique:suppliers,phone_2,NULL,id,deleted_at,NULL
            'address' => 'nullable|string:max:255',
            'type' => 'required|string|in:person,company',
            'fax' => 'nullable|string|max:255',
            'commercial_number' => 'nullable|string|max:255',
            'tax_card' => 'nullable|string|max:255',
//            'funds_for'=>'required|numeric|min:0',
//            'funds_on'=>'required|numeric|min:0',
            'description' => 'nullable|string',

            'tax_number' => 'nullable|string',
            'lat' => 'nullable|numeric',
            'long' => 'nullable|numeric',
            'maximum_fund_on' => 'required|numeric|min:0',
        ];

        if (authIsSuperAdmin()) {
            $rules['branch_id'] = 'required|integer|exists:branches,id';
            $branch = request()->branch_id;

        } else {

            $branch = auth()->user()->branch_id;
        }

        $rules['name_en'] =
            [
                'required', 'string', 'max:150',
                Rule::unique('suppliers')->where(function ($query) use ($branch) {
                    return $query->where('name_en', request()->name_en)
                        ->where('branch_id', $branch)
                        ->where('deleted_at', null);
                }),
            ];

        $rules['name_ar'] =
            [
                'required', 'string', 'max:150',
                Rule::unique('suppliers')->where(function ($query) use ($branch) {
                    return $query->where('name_ar', request()->name_ar)
                        ->where('branch_id', $branch)
                        ->where('deleted_at', null);
                }),
            ];

        $rules['email'] =
            [
                'nullable', 'email', 'max:255',
                Rule::unique('suppliers')->where(function ($query) use ($branch) {
                    return $query->where('email', request()->email)
                        ->where('branch_id', $branch)
                        ->where('deleted_at', null);
                }),
            ];

        return $rules;
    }

    public function supplierBalance(Request $request)
    {

        $supplier = Supplier::findOrFail($request['supplier_id']);

        $data['supplier_group_discount'] = 0;

        if ($supplier->suppliersGroup) {
            $data['supplier_group_discount'] = $supplier->suppliersGroup->discount;
            $data['supplier_discount_type'] = $supplier->suppliersGroup->discount_type;
        }

        $balance_details = $supplier->direct_balance();

        $data['funds_for'] = $balance_details['debit']; //$supplier->funds_for;
        $data['funds_on'] = $balance_details['credit']; //$supplier->funds_on;
        return $data;
    }

    public function getDataByBranch(Request $request)
    {
        $spareP = SparePart::where('status', 1)->where('branch_id', $request->branch_id);
        $ids = $spareP->pluck('id')->toArray();
        $spareParts = $spareP->get();
        $parts = Part::where('status', 1)->whereIn('id', $ids)->get();
        $supplires = Supplier::where('branch_id', $request->branch_id)->get();
        $supHtml = '<option value="">' . __('Select Supplier') . '</option>';
        foreach ($supplires as $supplire) {
            $supHtml .= '<option value="' . $supplire->id . '">' . $supplire->name . '-' . $supplire->phone_1 . '</option>';
        }
        $view = view('admin.purchase-invoices.getPartsByajax', compact('parts', 'spareParts'))->render();
        $spareHtmlSelect = ' <option value=""> ' . __('Select Spare Part Type') . '</option>';
        foreach ($spareParts as $sp) {
            $spareHtmlSelect .= '<option value="' . $sp->id . '">' . $sp->type . '</option>';
        }
        $partsHtmlSelect = ' <option value=""> ' . __('Select Part') . '</option>';
        foreach ($parts as $part) {
            $partsHtmlSelect .= '<option value="' . $part->id . '">' . $part->name . '</option>';
        }
        $bracodes = Part::whereIn('id', $ids)->where('status', 1)->where('barcode', '!=', null)->get();
        $bracodesHtmlSelect = ' <option value=""> ' . __('Select BarCode') . '</option>';
        foreach ($bracodes as $bracode) {
            $bracodesHtmlSelect .= '<option value="' . $bracode->id . '">' . $bracode->barcode . '</option>';
        }
        return response()->json(
            [
                'invoice' => $view,
                'suppliers' => $supHtml,
                'sparePartsSelect' => $spareHtmlSelect,
                'partsSelect' => $partsHtmlSelect,
                'barcodeSelect' => $bracodesHtmlSelect,
            ]);
    }

    public function unitPrices(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'part_id' => 'required|integer',
            'price_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return \response()->json($validator->errors()->first(), 400);
        }

        try {

            $price = PartPrice::find($request['price_id']);

            $prices = $price->priceSegments;

            $part_id = $request['part_id'];

            $view = view('admin.purchase-invoices.parts.unit_prices', compact('prices', 'part_id'))->render();

        } catch (Exception $e) {
            return \response()->json('sorry, try later', 400);
        }

        return \response()->json(['view' => $view, 'message' => 'done'], 200);
    }


//  NEW VERSION
    public function getPurchaseReceipts(Request $request)
    {
        $rules = [
            'supply_order_id' => 'required|integer|exists:supply_orders,id',
//            'supplier_id' => 'nullable|integer|exists:suppliers,id'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        try {

            $purchaseReceipts = PurchaseReceipt::where('supply_order_id', $request['supply_order_id'])
                ->select('id', 'number', 'supplier_id')
                ->get();

            $view = view('admin.purchase-invoices.purchase_receipts', compact('purchaseReceipts'))->render();

            $real_purchase_receipts = view('admin.purchase-invoices.real_purchase_receipts', compact('purchaseReceipts'))->render();

            return response()->json(['view' => $view, 'real_purchase_receipts' => $real_purchase_receipts], 200);

        } catch (\Exception $e) {
            return response()->json('sorry, please try later', 400);
        }
    }

    public function addPurchaseReceipts(Request $request)
    {
        $rules = [
            'purchase_receipts' => 'required',
            'purchase_receipts.*' => 'required|integer|exists:purchase_receipts,id',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        try {

            $purchaseReceipts = PurchaseReceipt::with('items')
                ->whereIn('id', $request['purchase_receipts'])
                ->get();

            $itemsCount = 0;

            foreach ($purchaseReceipts as $purchaseReceipt) {
                $itemsCount += $purchaseReceipt->items()->count();
            }

            $view = view('admin.purchase-invoices.purchase_receipt_items',
                compact('purchaseReceipts'))->render();

            return response()->json(['view' => $view, 'index' => $itemsCount], 200);

        } catch (\Exception $e) {

            return response()->json('sorry, please try later', 400);
        }
    }

    public function selectPartRaw(Request $request)
    {
        $rules = [
            'part_id' => 'required|integer|exists:parts,id',
            'index' => 'required|integer'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        try {

            $index = $request['index'] + 1;

            $part = Part::find($request['part_id']);

            $view = view('admin.purchase-invoices.part_raw', compact('part', 'index'))->render();

            return response()->json(['parts' => $view, 'index' => $index], 200);

        } catch (\Exception $e) {

            return response()->json('sorry, please try later', 400);
        }
    }

    public function print(Request $request)
    {
        $supplyOrder = SupplyOrder::findOrFail($request['supply_order_id']);

        $view = view('admin.supply_orders.print', compact('supplyOrder'))->render();

        return response()->json(['view' => $view]);
    }

    public function priceSegments(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'price_id' => 'required|integer|exists:part_prices,id',
            'index' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        try {

            $index = $request['index'];

            $priceSegments = PartPriceSegment::where('part_price_id', $request['price_id'])->get();

            $view = view('admin.purchase-invoices.ajax_price_segments', compact('priceSegments', 'index'))->render();

            return response()->json(['view' => $view], 200);

        } catch (\Exception $e) {
            return response()->json('sorry, please try later', 400);
        }
    }
}
