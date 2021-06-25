<?php

namespace App\Filters;

use App\Models\ExpensesReceipt;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ExpenseReceiptFilter
{
    public function filter(Request $request): Builder
    {
        return ExpensesReceipt::where(function ($query) use ($request) {
            if ($request->has('expense_no') && $request->expense_no != '' && $request->expense_no != null) {
                $query->where('id', $request->expense_no);
            }

            if ($request->has('branch_id') && $request->branch_id != '' && $request->branch_id != null) {
                $query->where('branch_id', $request->branch_id);
            }

            if ($request->has('receiver') && $request->receiver != '' && $request->receiver != null) {
                $query->where('receiver', 'like', '%' . $request->receiver . '%');
            }

            if ($request->has('deportation') && $request->deportation != '' && $request->deportation != null) {
                $query->where('deportation', $request->deportation);
            }


            if ($request->has('expense_type_id') && $request->expense_type_id != '' && $request->expense_type_id != null) {
                $query->where('expense_type_id', $request->expense_type_id);
            }


            if ($request->has('expense_item_id') && $request->expense_item_id != '' && $request->expense_item_id != null) {
                $query->where('expense_item_id', $request->expense_item_id);
            }


            if ($request->has('dateFrom') && $request->has('dateTo')
                && $request->dateFrom != '' && $request->dateTo != ''
                && $request->dateFrom != null && $request->dateTo != null) {

                $query->whereBetween('date', [$request->dateFrom, $request->dateTo]);
            }

            if ($request->has('user_account_id') && $request->user_account_id != '' && $request->user_account_id != null) {
                $query->where('user_account_id', $request->user_account_id);
            }

            if ($request->has('user_account_type') && $request->user_account_type != '' && $request->user_account_type != null) {
                $query->where('user_account_type', $request->user_account_type);
            }

            if ($request->has('payment_type') && $request->payment_type != ''
                && $request->payment_type != null) {
                $query->where('payment_type', $request->payment_type);
            }

            if ($request->has('bank_name') && $request->bank_name != ''
                && $request->bank_name != null ) {
                $query->where('bank_name', $request->bank_name);
            }

            if ($request->has('check_number') && $request->check_number != ''
                && $request->check_number != null ) {
                $query->where('check_number', $request->check_number);
            }

        });
    }

    public function filterInvoices(Builder $builder, Request $request)
    {
        return $builder->where(function ($query) use ($request) {
            if ($request->has('expense_no') && $request->expense_no != '' && $request->expense_no != null) {
                $query->where('id', $request->expense_no);
            }

            if ($request->has('branch_id') && $request->branch_id != '' && $request->branch_id != null) {
                $query->where('branch_id', $request->branch_id);
            }

            if ($request->has('receiver') && $request->receiver != '' && $request->receiver != null) {
                $query->where('receiver', 'like', '%' . $request->receiver . '%');
            }

            if ($request->has('deportation') && $request->deportation != '' && $request->deportation != null) {
                $query->where('deportation', $request->deportation);
            }


            if ($request->has('expense_type_id') && $request->expense_type_id != '' && $request->expense_type_id != null) {
                $query->where('expense_type_id', $request->expense_type_id);
            }


            if ($request->has('expense_item_id') && $request->expense_item_id != '' && $request->expense_item_id != null) {
                $query->where('expense_item_id', $request->expense_item_id);
            }


            if ($request->has('dateFrom') && $request->has('dateTo')
                && $request->dateFrom != '' && $request->dateTo != ''
                && $request->dateFrom != null && $request->dateTo != null) {

                $query->whereBetween('date', [$request->dateFrom, $request->dateTo]);
            }

        });
    }
}
