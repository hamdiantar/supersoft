<?php


namespace App\Services;


use App\Models\Account;
use App\Models\CardInvoice;
use App\Models\Locker;
use App\Models\RevenueItem;
use App\Models\RevenueType;
use App\Models\SalesInvoice;
use phpDocumentor\Reflection\Types\True_;
use function Matrix\trace;

trait RevenueReceiptsServices
{
    public function createForCardInvoice($request)
    {

        $card_invoice = CardInvoice::findOrFail($request['card_invoice_id']);

        $branch_id = $card_invoice->workCard->branch_id;

        $revenueItem = RevenueItem::where('branch_id', $branch_id)->where('item_en', 'card invoice Payments')->first();
        $revenueType = RevenueType::where('branch_id', $branch_id)->where('type_en', 'card invoice')->first();

        $revenue_type_id = '';

        if ($revenueType)
            $revenue_type_id = $revenueType->id;

        $revenue_item_id = '';

        if ($revenueItem)
            $revenue_item_id = $revenueItem->id;

        $lockers = Locker::where('status', 1)->where('branch_id', $branch_id)->get();
        $accounts = Account::where('status', 1)->where('branch_id', $branch_id)->get();

        return view('admin.revenueReceipts.create',
            compact('card_invoice', 'branch_id', 'revenue_item_id', 'revenue_type_id', 'lockers', 'accounts'));

    }

    public function editFormForCardInvoice($revenueReceipt)
    {

        $card_invoice = $revenueReceipt->cardInvoice;

        $branch_id = $card_invoice->workCard->branch_id;

        $revenueItem = RevenueItem::where('branch_id', $branch_id)->where('item_en', 'card invoice Payments')->first();
        $revenueType = RevenueType::where('branch_id', $branch_id)->where('type_en', 'card invoice')->first();

        $revenue_type_id = '';

        if ($revenueType) {
            $revenue_type_id = $revenueType->id;
        }

        $revenue_item_id = '';

        if ($revenueItem) {
            $revenue_item_id = $revenueItem->id;
        }

        $lockers = Locker::where('status', 1)->where('branch_id', $branch_id)->get();
        $accounts = Account::where('status', 1)->where('branch_id', $branch_id)->get();

        return view('admin.revenueReceipts.edit',
            compact('card_invoice', 'revenueReceipt', 'branch_id', 'revenue_type_id',
                'revenue_item_id', 'lockers', 'accounts'));

    }

    public function createFormForSalesInvoice($request)
    {

        $sales_invoice = SalesInvoice::findOrFail($request['sales_invoice_id']);
        $branch_id = $sales_invoice->branch_id;

        $revenueItem = RevenueItem::where('branch_id', $branch_id)->where('item_en', 'sales invoice Payments')->first();
        $revenueType = RevenueType::where('branch_id', $branch_id)->where('type_en', 'sales invoice')->first();

        $revenue_type_id = '';

        if ($revenueType) {
            $revenue_type_id = $revenueType->id;
        }

        $revenue_item_id = '';

        if ($revenueItem)
            $revenue_item_id = $revenueItem->id;

        $lockers = Locker::where('status', 1)->where('branch_id', $sales_invoice->branch_id)->get();
        $accounts = Account::where('status', 1)->where('branch_id', $sales_invoice->branch_id)->get();

        return view('admin.revenueReceipts.create',
            compact('sales_invoice', 'branch_id', 'revenue_item_id', 'revenue_type_id', 'lockers', 'accounts'));
    }

    public function editFormForSalesInvoice($revenueReceipt)
    {

        $sales_invoice = $revenueReceipt->salesInvoice;

        $branch_id = $sales_invoice->branch_id;

        $revenueItem = RevenueItem::where('branch_id', $branch_id)->where('item_en', 'sales invoice Payments')->first();
        $revenueType = RevenueType::where('branch_id', $branch_id)->where('type_en', 'sales invoice')->first();

        $revenue_type_id = '';

        if ($revenueType) {
            $revenue_type_id = $revenueType->id;
        }

        $revenue_item_id = '';

        if ($revenueItem) {
            $revenue_item_id = $revenueItem->id;
        }


        $lockers = Locker::where('status', 1)->where('branch_id', $sales_invoice->branch_id)->get();
        $accounts = Account::where('status', 1)->where('branch_id', $sales_invoice->branch_id)->get();

        return view('admin.revenueReceipts.edit',
            compact('sales_invoice', 'revenueReceipt', 'branch_id', 'revenue_type_id',
                'revenue_item_id', 'lockers', 'accounts'));

    }

    public function reset($revenueReceipt)
    {
        $bank = $this->getLockerOrAccount($revenueReceipt);

        if ($bank) {

            $bank->balance -= $revenueReceipt->cost;

            if ($bank->balance < 0)
                $bank->balance = 0;

            $bank->save();

            $revenueReceipt->locker_id = null;

            $revenueReceipt->save();

            return $bank;
        }

        return false;
    }

    public function getLockerOrAccount($revenueReceipt)
    {

        if ($revenueReceipt->locker) {

            return $revenueReceipt->locker;
        }

        if ($revenueReceipt->bank) {

            return $revenueReceipt->bank;
        }
    }

    public function checkReceiptCostInBank($revenueReceipt)
    {

        $bank = $this->getLockerOrAccount($revenueReceipt);

        if ($bank && $revenueReceipt->cost <= $bank->balance) {

            return true;
        }

        return false;
    }

    public function checkIfCanUpdateCost ($revenueReceipt, $requestCost) {

        $receiptCostInBank = $this->checkReceiptCostInBank($revenueReceipt);

//        && $requestCost >= $revenueReceipt->cost

        if ($receiptCostInBank) {

            return true;
        }

        return false;
    }

    public function updateReceiptCost($revenueReceipt, $request)
    {
        if ($request->has('locker_id')) {

            $locker = Locker::find($request['locker_id']);

            if (!$locker) {

                return false;
            }

            $revenueReceipt->cost = $request['cost'];
            $revenueReceipt->save();

            $locker->balance += $request['cost'];
            $locker->save();

            return true;
        }

        if ($request->has('account_id')) {

             $account = Account::find($request['account_id']);

            if (!$account) {

                return false;
            }

            $revenueReceipt->cost = $request['cost'];
            $revenueReceipt->save();

            $account->balance += $request['cost'];
            $account->save();

            return true;
        }
    }
}
