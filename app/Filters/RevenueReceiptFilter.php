<?php

namespace App\Filters;

use App\Models\RevenueReceipt;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class RevenueReceiptFilter
{
    public function filter(Request $request): Builder
    {
        return RevenueReceipt::where(function ($query) use ($request) {

            if ($request->has('branch_id') && $request->branch_id != '' && $request->branch_id != null) {
                $query->where('branch_id', $request->branch_id);
            }

            if ($request->has('revenue_no') && $request->revenue_no != '' && $request->revenue_no != null) {
                $query->where('id', $request->revenue_no);
            }

            if ($request->has('receiver') && $request->receiver != '' && $request->receiver != null) {
                $query->where('receiver', 'like', '%' . $request->receiver . '%');
            }

            if ($request->has('deportation') && $request->deportation != '' && $request->deportation != null) {
                $query->where('deportation', $request->deportation);
            }


            if ($request->has('revenue_type_id') && $request->revenue_type_id != '' && $request->revenue_type_id != null) {
                $query->where('revenue_type_id', $request->revenue_type_id);
            }


            if ($request->has('revenue_item_id') && $request->revenue_item_id != '' && $request->revenue_item_id != null) {
                $query->where('revenue_item_id', $request->revenue_item_id);
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
}
