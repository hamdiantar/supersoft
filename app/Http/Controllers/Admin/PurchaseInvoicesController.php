<?php

namespace App\Http\Controllers\Admin;

use App\Models\PartPrice;
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

    use PurchaseInvoiceServices;

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

        $branches = Branch::all()->pluck('name', 'id');

        $taxes = TaxesFees::where('on_parts', 0)->where('active_purchase_invoice', 1);

        $parts = Part::where('status', 1);

        $partsTypes = SparePart::where('status', 1);

        $suppliers = Supplier::where('status', 1);

        if ($request->has('branch_id') && $request['branch_id'] != null) {

            $taxes->where('branch_id', $request['branch_id']);

            $parts->whereHas('store', function ($q) use ($request) {
                $q->where('branch_id', $request['branch_id']);
            });

            $partsTypes->where('branch_id', $request['branch_id']);
            $suppliers->where('branch_id', $request['branch_id']);
        }

        $parts = $parts->get();
        $partsTypes = $partsTypes->get();
        $taxes = $taxes->get();
        $suppliers = $suppliers->get();

        return view('admin.purchase-invoices.create', compact('taxes', 'parts', 'partsTypes', 'branches', 'suppliers'));
    }

    public function edit(PurchaseInvoice $purchase_invoice)
    {
        if (!auth()->user()->can('update_purchase_invoices')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $parts = Part::where('status', 1)->whereHas('store', function ($q) use ($purchase_invoice) {
            $q->where('branch_id', $purchase_invoice->branch_id);
        })->get();

        $stores = Store::where('branch_id', $purchase_invoice->branch_id)->get()->pluck('name', 'id');

        $partsTypes = SparePart::where('status', 1)->where('branch_id', $purchase_invoice->branch_id)->get();

        $taxes = TaxesFees::where('on_parts', 0)->where('active_purchase_invoice', 1)->where('branch_id', $purchase_invoice->branch_id)->get();

        return view('admin.purchase-invoices.edit', compact('purchase_invoice', 'taxes', 'parts', 'partsTypes', 'stores'));
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

    public function destroy(PurchaseInvoice $purchase_invoice)
    {
        if (!auth()->user()->can('delete_purchase_invoices')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $purchase_invoice->delete();
        return redirect()->back()
            ->with(['message' => __('words.purchase-invoice-deleted'), 'alert-type' => 'success']);
    }

    public function deleteSelected(Request $request)
    {
        if (!auth()->user()->can('delete_purchase_invoices')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if (isset($request->ids)) {
            PurchaseInvoice::whereIn('id', $request->ids)->delete();
            return redirect()->back()
                ->with(['message' => __('words.selected-row-deleted'), 'alert-type' => 'success']);
        }
        return redirect()->back()
            ->with(['message' => __('words.select-one-least'), 'alert-type' => 'error']);
    }

    public function store(PurchaseInvoiceRequest $request)
    {

        if (!auth()->user()->can('create_purchase_invoices')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        try {

            DB::beginTransaction();

            $data = $request->all();

            if (!authIsSuperAdmin()) {
                $data['branch_id'] = auth()->user()->branch_id;
            }

            $invoice_data = $this->prepareInvoiceData($data);

            $purchaseInvoice = PurchaseInvoice::create($invoice_data);

            if (isset($data['taxes'])) {

                $purchaseInvoice->taxes()->attach($data['taxes']);
            }

            foreach ($data['items'] as $item) {

                $item_data = $this->calculateItemTotal($item);
                $item_data['purchase_invoice_id'] = $purchaseInvoice->id;

                $purchaseInvoiceItem = PurchaseInvoiceItem::create($item_data);

                if (isset($item['taxes'])) {

                    $purchaseInvoiceItem->taxes()->attach($item['taxes']);
                }

                $this->affectedPart($item['id'], $item['purchase_qty'], $item['purchase_price'], $item['part_price_id']);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();

            return redirect()->to('admin/purchase-invoices')
                ->with(['message' => __('words.purchase-invoice-cant-created'), 'alert-type' => 'error']);
        }

        return redirect()->to('admin/expenseReceipts/create?invoice_id=' . $purchaseInvoice->id)
            ->with(['message' => __('words.purchase-invoice-created'), 'alert-type' => 'success']);

    }

    public function update(PurchaseInvoiceRequest $request, PurchaseInvoice $purchase_invoice)
    {
        if (!auth()->user()->can('update_purchase_invoices')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if ($purchase_invoice->expenseReceipt->count()) {
            return redirect()->back()->with(['message' => __('words.purchase-invoice-paid'), 'alert-type' => 'error']);
        }

        if ($purchase_invoice->invoiceReturn) {
            return redirect()->back()->with(['message' => __('words.sale-invoice-returned'),
                'alert-type' => 'error']);
        }

        try {

            DB::beginTransaction();

            $data = $request->all();

            $invoice_data = $this->prepareInvoiceData($data);

            $purchase_invoice->update($invoice_data);

            $purchase_invoice_items_ids = $purchase_invoice->items->pluck('id')->toArray();

            $requestItemsIds = array_column($data['items'], 'item_id');

            $this->deletePartsNotInRequest($purchase_invoice_items_ids, $requestItemsIds);

            foreach ($data['items'] as $item) {

                $invoice_item = isset($item['item_id']) ? PurchaseInvoiceItem::find($item['item_id']) : null;

                $item_data = $this->calculateItemTotal($item);
                $item_data['purchase_invoice_id'] = $purchase_invoice->id;

                if ($invoice_item) {

                    $this->restPart($item['id'], $invoice_item->purchase_qty);
                    $invoice_item->update($item_data);

                } else {

                    PurchaseInvoiceItem::create($item_data);
                }

                $this->affectedPart($item['id'], $item['purchase_qty'], $item['purchase_price']);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
//            dd($e->getMessage());

            return redirect()->to('admin/purchase-invoices')
                ->with(['message' => __('words.purchase-invoice-cant-updated'), 'alert-type' => 'error']);
        }

        return redirect()->to('/admin/purchase-invoices')
            ->with(['message' => __('words.purchase-invoice-updated'), 'alert-type' => 'success']);

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
}
