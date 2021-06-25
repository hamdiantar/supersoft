<?php


namespace App\Services;


use App\Models\PurchaseInvoice;
use App\Models\Setting;

trait SampleSalesInvoiceServices
{

    public function settingSellFromInvoiceStatus($branch_id) {

        $setting = Setting::where('branch_id', $branch_id)->first();

        $setting_sell_From_invoice_status = 'old';

        if($setting){

            $setting_sell_From_invoice_status = $setting->sell_from_invoice_status;
        }

        return $setting_sell_From_invoice_status;
    }

    public function getPurchaseInvoice($setting_sell_invoice_status, $branch_id, $purchase_invoice_id, $part_id){

        $purchase_invoice = PurchaseInvoice::find($purchase_invoice_id);

        if (!$purchase_invoice) {

            $sortType = $setting_sell_invoice_status == 'old' ? 'asc' : 'desc';

            $purchase_invoice = PurchaseInvoice::where('branch_id', $branch_id)
                ->orderBy('date', $sortType)
                ->whereHas('items', function($q) use($part_id){

                    $q->where('part_id', $part_id)->where('purchase_qty','!=', 0);

                })->first();
        }

        return $purchase_invoice;
    }

    public function getNextPurchaseInvoices($setting_sell_invoice_status, $branch_id, $current_purchase_invoice_id, $part_id){

        $sortType = $setting_sell_invoice_status == 'old' ? 'asc' : 'desc';

        $purchase_invoices = PurchaseInvoice::where('branch_id', $branch_id)
            ->orderBy('date', $sortType)
            ->whereHas('items', function($q) use($part_id){

                $q->where('part_id', $part_id)->where('purchase_qty','!=', 0);

            })->get();

        return $purchase_invoices;
    }

}
