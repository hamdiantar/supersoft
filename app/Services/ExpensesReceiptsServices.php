<?php


namespace App\Services;


use App\Models\Account;
use App\Models\ExpensesItem;
use App\Models\ExpensesType;
use App\Models\Locker;
use App\Models\SalesInvoiceReturn;

trait ExpensesReceiptsServices
{

    public function createFormForSalesInvoiceReturn($request){

        $sales_invoice_return = SalesInvoiceReturn::findOrFail($request['sales_invoice_return_id']);

        $branch_id = $sales_invoice_return->branch_id;

        $expensesItem = ExpensesItem::where('branch_id', $branch_id)->where('item_en','sales invoice return Payments')->first();
        $expensesType = ExpensesType::where('branch_id', $branch_id)->where('type_en','sales invoice return')->first();

        $expenses_type_id = '';

       if($expensesType){
           $expenses_type_id = $expensesType->id;
       }

        $expenses_item_id = '';

        if($expensesItem){
            $expenses_item_id = $expensesItem->id;
        }

        $lockers = Locker::where('status',1)->where('branch_id',$sales_invoice_return->branch_id)->get();
        $accounts = Account::where('status',1)->where('branch_id',$sales_invoice_return->branch_id)->get();

        return view('admin.expenseReceipts.create',
            compact('sales_invoice_return', 'branch_id', 'expenses_type_id',
                'expenses_item_id','lockers','accounts'));

    }

    public function editFormForSalesInvoiceReturn($expenseReceipt){

        $sales_invoice_return = $expenseReceipt->salesInvoiceReturn;

        $branch_id = $sales_invoice_return->branch_id;

        $expensesItem = ExpensesItem::where('branch_id', $branch_id)->where('item_en','sales invoice return Payments')->first();
        $expensesType = ExpensesType::where('branch_id', $branch_id)->where('type_en','sales invoice return')->first();

        $expenses_type_id = '';

        if($expensesType){
            $expenses_type_id = $expensesType->id;
        }

        $expenses_item_id = '';

        if($expensesItem){
            $expenses_item_id = $expensesItem->id;
        }


        $lockers = Locker::where('status',1)->where('branch_id', $branch_id)->get();
        $accounts = Account::where('status',1)->where('branch_id', $branch_id)->get();

        return view('admin.expenseReceipts.edit',
            compact('expenseReceipt','sales_invoice_return','branch_id',
                'expenses_type_id','expenses_item_id','lockers','accounts'));


    }

    public function reset($expenseReceipt)
    {
        $locker = $expenseReceipt->locker_id ? Locker::find($expenseReceipt->locker_id) : null;

        $account = $expenseReceipt->account_id ? Account::find($expenseReceipt->account_id) : null;

        if ($locker) {

            $locker->balance += $expenseReceipt->cost;

            $locker->save();

            $expenseReceipt->locker_id = null;

            $expenseReceipt->save();

            return true;

        } elseif ($account) {

            $account->balance += $expenseReceipt->cost;

            $account->save();

            $expenseReceipt->account_id = null;

            $expenseReceipt->save();

            return true;

        } else {

            return false;
        }
    }

}
