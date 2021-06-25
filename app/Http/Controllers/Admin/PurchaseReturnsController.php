<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Part;
use App\Models\Store;
use App\Models\TaxesFees;
use Illuminate\Http\Request;
use App\Model\PurchaseReturn;
use App\Models\RevenueReceipt;
use App\Models\PurchaseInvoice;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use App\Filters\PurchaseReturnInvoiceFilter;
use App\Http\Controllers\ExportPrinterFactory;
use App\Http\Requests\Admin\PurchaseReturn\PurchaseReturnRequest;
use App\Http\Controllers\DataExportCore\Invoices\PurchaseReturn as InvoicesPurchaseReturn;

class PurchaseReturnsController extends Controller
{
    /**
     * @var PurchaseReturnItemsController
     */
    protected $purchaseReturnItemsController;

    /**
     * @var PurchaseReturnInvoiceFilter
     */
    protected $purchaseReturnInvoiceFilter;

    public function __construct(
        PurchaseReturnItemsController $purchaseReturnItemsController,
        PurchaseReturnInvoiceFilter $purchaseReturnInvoiceFilter
    )
    {
        $this->purchaseReturnItemsController = $purchaseReturnItemsController;
        $this->purchaseReturnInvoiceFilter = $purchaseReturnInvoiceFilter;
//        $this->middleware('permission:view_purchase_return_invoices');
//        $this->middleware('permission:create_purchase_return_invoices', ['only' => ['create', 'store']]);
//        $this->middleware('permission:update_purchase_return_invoices', ['only' => ['edit', 'update']]);
//        $this->middleware('permission:delete_purchase_return_invoices', ['only' => ['destroy', 'deleteSelected']]);
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('view_purchase_return_invoices')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $invoices = PurchaseReturn::query();
        if ($request->hasAny((new PurchaseReturn())->getFillable())) {
            $invoices = $this->purchaseReturnInvoiceFilter->filter($request);
        }
        if ($request->has('sort_by') && $request->sort_by != '') {
            $sort_by = $request->sort_by;
            $sort_method = $request->has('sort_method') ? $request->sort_method :'asc';
            if (!in_array($sort_method ,['asc' ,'desc'])) $sort_method = 'desc';
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
            $invoices = $invoices->orderBy($sort_fields[$sort_by] ,$sort_method);
        } else {
            $invoices = $invoices->orderBy('id', 'DESC');
        }if ($request->has('key')) {
            $key = $request->key;
            $invoices->where(function ($q) use ($key) {
                $q->where('invoice_number' ,'like' ,"%$key%")
                ->orWhere('remaining' ,'like' ,"%$key%")
                ->orWhere('paid' ,'like' ,"%$key%");
            });
        }
        if ($request->has('invoker') && in_array($request->invoker ,['print' ,'excel'])) {
            $visible_columns = $request->has('visible_columns') ? $request->visible_columns : [];
            return (new ExportPrinterFactory(new InvoicesPurchaseReturn($invoices->with('supplier') ,$visible_columns), $request->invoker))();
        }
        $rows = $request->has('rows') ? $request->rows : 10;

        $invoices = $invoices->paginate($rows)->appends(request()->query());
        return view('admin.purchase_returns.index', compact('invoices'));
    }

    public function create()
    {
        if (!auth()->user()->can('create_purchase_return_invoices')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $branch_id = isset($_GET['branch_id']) && $_GET['branch_id'] != '' ? $_GET['branch_id'] : NULL;

        if (!authIsSuperAdmin()) {
            $branch_id = auth()->user()->branch_id;
        }

        $taxes = TaxesFees::where('active_purchase_invoice', 1)->where('branch_id', $branch_id)->get();

        $invoices = PurchaseInvoice::where('is_returned', 0)->when($branch_id, function ($q) use ($branch_id) {
            $q->where('branch_id', $branch_id);
        })->get();

        return view('admin.purchase_returns.create', compact('invoices', 'taxes'));
    }

    public function store(PurchaseReturnRequest $request)
    {
        if (!auth()->user()->can('create_purchase_return_invoices')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

//        dd($request->all());

        try {

            $purchaseReturn = PurchaseReturn::create([
                'invoice_number' => $request->invoice_number,
                'supplier_id' => $request->supplier_id ?? null,
                'branch_id' => $request->branch_id ?? auth()->user()->branch_id,
                'date' => $request->date,
                'time' => $request->time,
                'type' => $request->type,
                'number_of_items' => $request->number_of_items,
                'discount_type' => $request->discount_type,
                'discount' => $request->discount,
                'total' => $request->total_before_discount,
                'total_after_discount' => $request['total'],
                'tax' => $request['tax'],
                'purchase_invoice_id' => $request->purchase_invoice_id,
                'paid' => 0,
                'remaining' => $request->total_after_discount,
                'supplier_discount_status' => $request->has('supplier_discount_check') ? 1 : 0,
                'supplier_discount_type' => $request->discount_group_type,
                'supplier_discount' => $request->discount_group_value,
            ]);
            if ($purchaseReturn) {
                $this->updateIsReturnedInPurchaseInvoice($request->purchase_invoice_id);
                $this->purchaseReturnItemsController->createItemsInvoice($purchaseReturn, $request);
                return redirect()->to('admin/revenueReceipts/create?purchase_return_id=' . $purchaseReturn->id)
                    ->with(['message' => __('words.purchase-invoice-return-created'), 'alert-type' => 'success']);
            }
        } catch (Exception $exception) {
            logger(['can not add purchase invoice return', $exception->getMessage()]);
            return redirect()->to('admin/purchase-returns')
                ->with(['message' => __('words.purchase-invoice-return-cant-created'), 'alert-type' => 'error']);
        }
    }

    public function updateIsReturnedInPurchaseInvoice(int $inoviceId)
    {
        $invoice = PurchaseInvoice::find($inoviceId);
        $invoice->update(
            [
                'is_returned' => 1
            ]
        );
    }

    public function edit(PurchaseReturn $purchaseReturn)
    {
        if (!auth()->user()->can('update_purchase_return_invoices')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $invoices = PurchaseInvoice::get();
//        dd($purchaseReturn->purchase_invoice_id);
        $itemsInvoice = PurchaseInvoice::with('items')->findOrFail($purchaseReturn->purchase_invoice_id);
        $taxes = TaxesFees::where('active_purchase_invoice', 1)->where('branch_id', $purchaseReturn->branch_id)->get();

        $stores = Store::all();

        $itemsIdsReturned = [];

        foreach ($purchaseReturn->items as $item) {
            array_push($itemsIdsReturned, $item->part_id);
        }

        $purchase_return_all_quantity = $purchaseReturn->items()->sum('purchase_qty');

//        $purchase_all_quantity = $itemsInvoice->items()->sum('purchase_qty');
//        $purchase_supplier_group_discount = $itemsInvoice->discount_group_value;
//
//        $current_group_discount = ($purchase_supplier_group_discount / $purchase_all_quantity) * $purchase_return_all_quantity;


        return view('admin.purchase_returns.edit',
            compact('purchaseReturn', 'invoices', 'stores', 'itemsInvoice', 'itemsIdsReturned','taxes'));
    }

    public function update(PurchaseReturnRequest $request, PurchaseReturn $purchaseReturn)
    {
        if (!auth()->user()->can('update_purchase_return_invoices')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if ($purchaseReturn->revenueReceipt->count()) {
            return redirect()->back()->with(['message' => __('words.purchase-invoice-return-paid'), 'alert-type' => 'error']);
        }

        try {
            $purchaseReturn->update([
                'invoice_number' => $request->invoice_number,
                'date' => $request->date,
                'time' => $request->time,
                'type' => $request->type,
                'number_of_items' => $request->number_of_items,
                'discount_type' => $request->discount_type,
                'discount' => $request->discount,
                'total' => $request->total_before_discount,
                'total_after_discount' => $request['total'],
                'tax' => $request['tax'],
                'purchase_invoice_id' => $request->purchase_invoice_id,
                'supplier_discount_status' => $request->has('supplier_discount_check') ? 1 : 0,
                'supplier_discount_type' => $request->discount_group_type,
                'supplier_discount' => $request->discount_group_value,
            ]);
            if ($purchaseReturn) {
                $this->purchaseReturnItemsController->createItemsInvoice($purchaseReturn, $request);
                return redirect()->to('admin/purchase-returns')
                    ->with(['message' => __('words.purchase-invoice-return-updated'), 'alert-type' => 'success']);
            }
        } catch (Exception $exception) {
//            dd($exception->getMessage());
            logger(['can not add purchase invoice return', $exception->getMessage()]);
            return redirect()->to('admin/purchase-returns')
                ->with(['message' => __('words.purchase-invoice-return-cant-updated'), 'alert-type' => 'error']);
        }
    }

    public function destroy(PurchaseReturn $purchaseReturn)
    {
        if (!auth()->user()->can('delete_purchase_return_invoices')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $purchaseReturn->delete();
        return redirect()->back()
            ->with(['message' => __('words.purchase-invoice-return-deleted'), 'alert-type' => 'success']);
    }

    public function showRevenues(int $id)
    {
        $invoice = PurchaseReturn::find($id);
        $revenues = RevenueReceipt::where('purchase_return_id', $invoice->id)->get();
        $revenuesSum = RevenueReceipt::where('purchase_return_id', $invoice->id)->sum('cost');
        $remaining = $invoice->total_after_discount - $revenuesSum;
        return view('admin.purchase_returns.parts.revenues', compact('revenues', 'invoice', 'revenuesSum', 'remaining'));
    }

    public function getPurchaseInvoice(Request $request)
    {
        $items_count = $request['items_count'];
        $parts_count = $request['parts_count'];
        $purchaseInvoice = PurchaseInvoice::withTrashed()->find($request->invoice_id);
        $is_discount_group_added = $purchaseInvoice->is_discount_group_added;
        $discount_supplier_value = $purchaseInvoice->discount_group_value;
        $number_of_items = $purchaseInvoice->number_of_items;
        $discount_type = $purchaseInvoice->discount_type;
        $discount = $purchaseInvoice->discount;
        $total = $purchaseInvoice->total;
        $total_after_discount = $purchaseInvoice->total_after_discount;
        $supplierName = optional($purchaseInvoice->supplier)->name;
        $supplierDiscount = $purchaseInvoice->discount_group_value;
        $supplierDiscountType = __($purchaseInvoice->discount_group_type);
        $supplierId = optional($purchaseInvoice->supplier)->id;
        $stores = Store::all();
        $htmlParts = '';
        foreach ($purchaseInvoice->items as $part) {
            $partName = Part::find($part->part_id);
            $storeHtml = '';
            foreach ($stores as $store) {
                $selected = $store->id === $part->store_id ? 'selected' : '';
                $storeHtml .= "<option value='$store->id' $selected >$store->name</option>";
            }
            $discountTypeAmount = $part->discount_type == 'amount' ? 'selected' : '';
            $discountTypePercent = $part->discount_type == 'percent' ? 'selected' : '';
            $select2_qnt = "<select name='purchased_qy[]' class='form-control select2' onchange=setServiceValues(" . $part->id . ") id='purchased-qy-" . $part->id . "'>";
            for ($i = 0; $i < $part->purchase_qty; $i++) {
                $selected_qnt = ($i + 1) == $part->purchase_qty ? "selected" : "";
                $select2_qnt .= "<option " . $selected_qnt . " value='" . ($i + 1) . "'> " . ($i + 1) . " </option>";
            }
            $select2_qnt .= "</select>";
            $htmlParts .= '<tr data-id=' . $part->id . ' id=' . $part->id . '>
                        <input type="hidden" name="part_id" value=' . $part->id . ' id="part-' . $items_count . '">
                         <input type="hidden" name="part_ids[]" value=' . $part->part_id . '>
                        <td>' . $partName->name . '</td>' . '
                        <td><input type="number" style="width:100px" class="form-control" readonly value="' . $part->available_qty . '" name="available_qy[]"
                        id="available-qy-' . $part->id . '"></td>
                        <td> ' . $select2_qnt . ' </td>
                        <input type="hidden" style="width:100px" class="form-control" readonly value="' . $part->last_purchase_price . '" name="last_purchase_price[]"
                        id="last_purchase_price-' . $part->id . '">

                        <td><input type="text" style="width:100px" class="form-control" value="' . $part->purchase_price . '" name="purchase_price[]"
                        id="purchased-price-' . $part->id . '" onkeyup="setServiceValues(' . $part->id . ')"></td>
                        <td><select style="width:100px !important" name="store_id[]" class="js-example-basic-single select2">

                        ' . $storeHtml . '
                        </select></td>
                        <td>

                        <ul class="list-inline">
                                            <li>
                                                <div class="radio info">
                                                    <input type="radio" name="item_discount_type[' . $part->id . ']" value="amount" checked
                                                           id="radio-10-discount-amount-' . $part->id . '" onclick="setServiceValues(' . $part->id . ')"><label
                                                        for="radio-10-discount-amount-' . $part->id . '">' . __('Amount') . '</label></div>
                                            </li>
                                            <li>
                                                <div class="radio pink">
                                                    <input type="radio" name="item_discount_type[' . $part->id . ']" value="percent" onclick="setServiceValues(' . $part->id . ')"
                                                           id="radio-12-discount-percent-' . $part->id . '"><label
                                                        for="radio-12-discount-percent-' . $part->id . '">' . __('Percent') . '</label></div>
                                            </li>
                        </td>
                        <td><input type="number" style="width:100px" class="form-control" value="' . $part->discount . '" name="item_discount[]"
                          onkeyup="setServiceValues(' . $part->id . ')" onchange="setServiceValues(' . $part->id . ')" id="discount-' . $part->id . '"></td>
                        <td><input type="number" style="width:100px" class="form-control"  readonly value="' . $part->total_before_discount . '" name="item_total_before_discount[]"  id="total-' . $part->id . '"></td>
                        <td><input type="number" style="width:100px" class="form-control"  readonly value="' . $part->total_after_discount . '" name="item_total_after_discount[]"  id="total-after-discount-' . $part->id . '"></td>
                        <td>
                         <input type="checkbox" name="idsItemsToReturn[]" data-total="' . $part->total_after_discount . '" checked
                         onclick="setServiceValues(' . $part->id . ')" value="' . $part->part_id . '" id="item-' . $part->id . '"
                         class="sales_invoice_checkbox" style="color:#F44336; cursor: pointer"></td>
                        </tr>';
            $items_count++;
            $parts_count++;
        }
        $purchase_return_all_quantity = $purchase_all_quantity = $purchaseInvoice->items()->sum('purchase_qty');
        $purchase_supplier_group_discount = $purchaseInvoice->discount_group_value;
        return response()->json([
            'invoiceData' => $purchaseInvoice,
            'parts' => $htmlParts,
            'items_count' => $items_count,
            'parts_count' => $parts_count,
            'supplier_name' => $supplierName,
            'supplier_id' => $supplierId,
            'supplierDiscount' => $supplierDiscount,
            'supplierDiscountType' => $supplierDiscountType,
            'is_discount_group_added' => $is_discount_group_added,
            'discount_supplier_value' => $discount_supplier_value,
            'number_of_items' => $number_of_items,
            'discount_type' => $discount_type,
            'discount' => $discount,
            'total' => $total,
            'subtotal' => $total,
            'total_after_discount' => $total_after_discount,
            'purchase_return_qnt' => $purchase_return_all_quantity,
            'purchase_all_quantity' => $purchase_all_quantity,
            'purchase_supplier_group_discount' => $purchase_supplier_group_discount,
        ]);
    }

    public function show(Request $request)
    {
        if (!auth()->user()->can('view_purchase_return_invoices')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $purchase_invoice = PurchaseReturn::find($request->invoiceID);

        $taxes = TaxesFees::where('active_purchase_invoice', 1)->where('branch_id', $purchase_invoice->branch_id)->get();

        $totalTax =  TaxesFees::where('active_purchase_invoice', 1)->where('branch_id', $purchase_invoice->branch_id)->sum('value');

        $invoice = view('admin.purchase_returns.show', compact('purchase_invoice','taxes','totalTax'))->render();
        return response()->json(['invoice' => $invoice]);
    }

    public function getInvoiceByBranch(Request $request)
    {
        $invoices = PurchaseInvoice::where('is_returned', 0)->where('branch_id', $request->branch_id)->get();
        $invoicesHtml = '<option value="">' . __('Select Purchase Invoice') . '</option>';
        foreach ($invoices as $invoice) {
            $invoicesHtml .= '<option value="' . $invoice->id . '">' . $invoice->invoice_number . '</option>';
        }
        return response()->json(['invoices' => $invoicesHtml]);
    }

    public function deleteSelected(Request $request)
    {
        if (!auth()->user()->can('delete_purchase_return_invoices')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if (isset($request->ids)) {
            $invoicesReturn = PurchaseReturn::whereIn('id', $request->ids)->get();
            foreach ($invoicesReturn as $invoice) {
                $this->destroy($invoice);
            }
            return redirect()->back()
                ->with(['message' => __('words.selected-row-deleted'), 'alert-type' => 'success']);
        }
        return redirect()->back()
            ->with(['message' => __('words.select-one-least'), 'alert-type' => 'error']);
    }
}
