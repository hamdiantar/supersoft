<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\PurchaseReceipt\CreateRequest;
use App\Models\Branch;
use App\Models\PurchaseReceipt;
use App\Models\SupplyOrder;
use App\Services\PurchaseReceiptServices;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PurchaseReceiptsController extends Controller
{
    public $lang;
    public $purchaseReceiptServices;

    public function __construct()
    {
        $this->lang = App::getLocale();
        $this->purchaseReceiptServices = new PurchaseReceiptServices();
    }

    public function index()
    {
        $data['purchase_receipts'] = PurchaseReceipt::get();
        return view('admin.purchase_receipts.index', compact('data'));
    }

    public function create(Request $request)
    {
        $branch_id = $request->has('branch_id') ? $request['branch_id'] : auth()->user()->branch_id;

        $data['supply_orders'] = SupplyOrder::with('supplier')
            ->where('branch_id', $branch_id)
            ->where('status', 'accept')
            ->select('id', 'number', 'supplier_id')->get();

        $data['branches'] = Branch::select('id', 'name_' . $this->lang)->get();

        $lastNumber = PurchaseReceipt::where('branch_id', $branch_id)
            ->orderBy('id', 'desc')
            ->first();

        $data['number'] = $lastNumber ? $lastNumber->number + 1 : 1;

        return view('admin.purchase_receipts.create', compact('data'));
    }

    public function store (CreateRequest $request) {

        try {

            $data = $request->all();

            $branch_id = authIsSuperAdmin() ? $request['branch_id'] : auth()->user()->branch_id;

            $supplyOrder = SupplyOrder::find($data['supply_order_id']);

            if ($supplyOrder->check_if_complete_receipt) {
                return redirect()->back()->with(['message'=> __('sorry, this supply order is complete'), 'alert-type'=>'error']);
            }

            $validateData = $this->purchaseReceiptServices->validateItemsQuantity($supplyOrder, $data['items']);

            if (!$validateData['status']) {
                $message = isset($validateData['message']) ? $validateData['message'] : 'sorry, please try later';
                return redirect()->back()->with(['message'=> $message, 'alert-type'=>'error']);
            }

            $purchaseReceiptData = $this->purchaseReceiptServices->purchaseReceiptData($data);

            $purchaseReceiptData['branch_id'] = $branch_id;
            $purchaseReceiptData['user_id'] = auth()->id();
            $purchaseReceiptData['supplier_id'] = $supplyOrder->supplier_id;

            $lastNumber = PurchaseReceipt::where('branch_id', $branch_id)
                ->orderBy('id', 'desc')
                ->first();

            $purchaseReceiptData['number'] = $lastNumber ? $lastNumber->number + 1 : 1;

            DB::beginTransaction();

            $purchaseReceipt = PurchaseReceipt::create($purchaseReceiptData);

            $this->purchaseReceiptServices->savePurchaseReceiptItems($supplyOrder, $data['items'], $purchaseReceipt->id);

            DB::commit();
        }catch (\Exception $e) {
            DB::rollBack();

//            dd($e->getMessage());
            return redirect()->back()->with(['message'=> __('sorry, please try later'), 'alert-type'=>'error']);
        }

        return redirect(route('admin:purchase-receipts.index'))
            ->with(['message'=> __('purchase.receipts.created.successfully'), 'alert-type'=>'success']);
    }

    public function edit (PurchaseReceipt $purchaseReceipt) {

        if ($purchaseReceipt->concession) {
            return redirect()->back()->with(['message'=> __('sorry, you can not edit that'), 'alert-type'=>'error']);
        }

        $branch_id = $purchaseReceipt->branch_id;

        $data['supply_orders'] = SupplyOrder::with('supplier')
            ->where('branch_id', $branch_id)
            ->where('status', 'accept')
            ->select('id', 'number', 'supplier_id')->get();

        $data['branches'] = Branch::select('id', 'name_' . $this->lang)->get();

        return view('admin.purchase_receipts.edit', compact('data', 'purchaseReceipt'));
    }

    public function update (CreateRequest $request, PurchaseReceipt $purchaseReceipt) {

        if ($purchaseReceipt->concession) {
            return redirect()->back()->with(['message'=> __('sorry, you can not edit that'), 'alert-type'=>'error']);
        }

        try {

            DB::beginTransaction();

            $data = $request->all();

            $this->purchaseReceiptServices->resetPurchaseReceiptItems($purchaseReceipt);

            $supplyOrder = SupplyOrder::find($data['supply_order_id']);

            if ($supplyOrder->check_if_complete_receipt) {
                return redirect()->back()->with(['message'=> __('sorry, this supply order is complete'), 'alert-type'=>'error']);
            }

            $validateData = $this->purchaseReceiptServices->validateItemsQuantity($supplyOrder, $data['items']);

            if (!$validateData['status']) {
                $message = isset($validateData['message']) ? $validateData['message'] : 'sorry, please try later';
                return redirect()->back()->with(['message'=> $message, 'alert-type'=>'error']);
            }

            $purchaseReceiptData = $this->purchaseReceiptServices->purchaseReceiptData($data);

            $purchaseReceiptData['supplier_id'] = $supplyOrder->supplier_id;

            $purchaseReceipt->update($purchaseReceiptData);

            $this->purchaseReceiptServices->savePurchaseReceiptItems($supplyOrder, $data['items'], $purchaseReceipt->id);

            DB::commit();
        }catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with(['message'=> __('sorry, please try later'), 'alert-type'=>'error']);
        }

        return redirect(route('admin:purchase-receipts.index'))
            ->with(['message'=> __('purchase.receipts.updated.successfully'), 'alert-type'=>'success']);

    }

    public function destroy (PurchaseReceipt $purchaseReceipt) {

        if ($purchaseReceipt->concession) {
            return redirect()->back()->with(['message'=> __('sorry, you can not delete that'), 'alert-type'=>'error']);
        }

        try {

            $purchaseReceipt->delete();

        }catch (\Exception $e) {
            return redirect()->back()->with(['message'=>'sorry, please try later', 'alert-type'=>'error']);
        }

        return redirect()->back()->with(['message'=> __('purchase.receipts.deleted.successfully'), 'alert-type'=>'success']);
    }

    public function selectSupplyOrder(Request $request)
    {
        $rules = [
            'supply_order_id'=>'required|integer|exists:supply_orders,id',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        try {

            $supplyOrder = SupplyOrder::find($request['supply_order_id']);

            $index = $supplyOrder->items->count();

            $supplerName = $supplyOrder->supplier ? $supplyOrder->supplier->name : '---';

            $view = view('admin.purchase_receipts.supply_order_items', compact('supplyOrder'))->render();

            return response()->json(['parts'=> $view, 'supplier_name'=> $supplerName, 'index'=> $index], 200);

        }catch (\Exception $e) {

            return response()->json('sorry, please try later', 400);
        }
    }

    public function show(Request $request)
    {
        $purchaseReceipt = PurchaseReceipt::findOrFail($request['purchase_receipt_id']);

        $view = view('admin.purchase_receipts.print', compact('purchaseReceipt'))->render();

        return response()->json(['view' => $view]);
    }
}
