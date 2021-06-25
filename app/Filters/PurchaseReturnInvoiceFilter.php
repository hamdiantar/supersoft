<?php

namespace App\Filters;

use App\Model\PurchaseReturn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class PurchaseReturnInvoiceFilter
{
    public function filter(Request $request): Builder
    {
        return PurchaseReturn::where(function ($query) use ($request) {
            if ($request->has('invoice_number') && $request->invoice_number != '' && $request->invoice_number != null) {
                $query->where('invoice_number', $request->invoice_number);
            }

            if ($request->has('purchase_invoice_id') && $request->purchase_invoice_id != '' && $request->purchase_invoice_id != null) {
                $query->where('purchase_invoice_id', $request->purchase_invoice_id);
            }

            if ($request->has('branch_id') && $request->branch_id != '' && $request->branch_id != null) {
                $query->where('branch_id', $request->branch_id);
            }

            if ($request->has('type') && $request->type != '' && $request->type != null) {
                $query->where('type', $request->type);
            }

            if ($request->has('supplier_id') && $request->supplier_id != '' && $request->supplier_id != null) {
                $query->where('supplier_id', $request->supplier_id);
            }

            if ($request->has('dateFrom') && $request->has('dateTo')
                && $request->dateFrom != '' && $request->dateTo != ''
                && $request->dateFrom != null && $request->dateTo != null) {

                $query->whereBetween('date', [$request->dateFrom, $request->dateTo]);
            }

        });
    }
}
