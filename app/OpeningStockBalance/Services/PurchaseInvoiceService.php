<?php
namespace App\OpeningStockBalance\Services;

use App\Models\PurchaseInvoice;
use App\Models\PurchaseInvoiceItem;
use App\Models\OpeningBalance;

class PurchaseInvoiceService {
    private function create_purchase_invoice(OpeningBalance $openingBalance) {
        $purchaseInvoice = PurchaseInvoice::create([
            'invoice_number' => $openingBalance->serial_number, // this need a mechanism to confirm it
            'branch_id' => $openingBalance->branch_id,
            'date' => $openingBalance->operation_date,
            'time' => $openingBalance->operation_time,
            'type' => 'cash',
            'number_of_items' => 0,
            'discount_type' => 'amount',
            'discount' => 0,
            'total' => $openingBalance->total_money,
            'total_after_discount' => $openingBalance->total_money,
            'paid' => $openingBalance->total_money,
            'subtotal' => $openingBalance->total_money,
            'is_opening_balance' => 1
        ]);
        $openingBalance->update(['purchase_invoice_id' => $purchaseInvoice->id]);
        return $purchaseInvoice;
    }

    private function create_purchase_items(OpeningBalance $openingBalance ,PurchaseInvoice $invoice) {
        $invoiceItems = [];
        $number_of_items = 0;
        foreach($openingBalance->items as $item) {
            $qnt = $item->quantity * $item->default_unit_quantity;
            $invoiceItems[] = [
                'purchase_invoice_id' => $invoice->id,
                'part_id' => $item->part_id,
                'store_id' => $item->store_id,
                'available_qty' => $qnt,
                'purchase_qty' => $qnt,
                'last_purchase_price' => $item->buy_price,
                'purchase_price' => $item->buy_price,
                'discount_type' => 'amount',
                'total_after_discount' => $qnt * $item->buy_price,
                'total_before_discount' => $qnt * $item->buy_price,
                'subtotal' => $qnt * $item->buy_price,
                'quantity' => $qnt,
                'part_price_id' => $item->part_price_id,
                'part_price_segment_id' => $item->part_price_price_segment_id,
            ];
            $number_of_items++;
        }
        PurchaseInvoiceItem::insert($invoiceItems);
        $invoice->update(['number_of_items' => $number_of_items]);
    }

    function after_create(OpeningBalance $openingBalance) {//is_opening_balance
        $purchaseInvoice = $this->create_purchase_invoice($openingBalance);
        $this->create_purchase_items($openingBalance ,$purchaseInvoice);
    }

    function after_edit(OpeningBalance $openingBalance) {
        if ($openingBalance->purchaseInvoice) {
            $openingBalance->purchaseInvoice->update([
                'branch_id' => $openingBalance->branch_id,
                'date' => $openingBalance->operation_date,
                'time' => $openingBalance->operation_time,
                'total' => $openingBalance->total_money,
                'total_after_discount' => $openingBalance->total_money,
                'paid' => $openingBalance->total_money,
                'subtotal' => $openingBalance->total_money,
            ]);
            $openingBalance->purchaseInvoice->items()->delete();
            $this->create_purchase_items($openingBalance ,$openingBalance->purchaseInvoice);
        } else {
            $purchaseInvoice = $this->create_purchase_invoice($openingBalance);
            $this->create_purchase_items($openingBalance ,$purchaseInvoice);
        }
    }

    function after_delete(OpeningBalance $openingBalance) {
        if ($openingBalance->purchaseInvoice) {
            $openingBalance->purchaseInvoice->items()->forceDelete();
            $openingBalance->purchaseInvoice->forceDelete();
        }
    }
}
