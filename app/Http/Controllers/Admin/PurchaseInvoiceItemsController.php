<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Part;
use App\Models\PurchaseInvoice;
use App\Models\PurchaseInvoiceItem;
use Illuminate\Http\Request;

class PurchaseInvoiceItemsController extends Controller
{
    public function createItemsInvoice(PurchaseInvoice $purchaseInvoice, Request $request)
    {
        $partsIds = $request->part_ids;
        $available_qys = $request->available_qy;
        $purchased_qys = $request->purchased_qy;
        $last_purchase_prices = $request->last_purchase_price;
        $purchase_price = $request->purchase_price;
        $store_ids = $request->store_id;
        $item_discount_types = $request->item_discount_type;
        $item_discount = $request->item_discount;
        $item_total_before_discount = $request->item_total_before_discount;
        $item_total_after_discount = $request->item_total_after_discount;
        $invoiceId = $purchaseInvoice->id;

        $invoiceItems = array_map(function (
            $partsIds,
            $available_qys,
            $purchased_qys,
            $last_purchase_prices,
            $purchase_price,
            $store_ids,
            $item_discount_types,
            $item_discount,
            $item_total_after_discount,
            $item_total_before_discount
        ) use ($invoiceId) {
            $invoiceItem = PurchaseInvoiceItem::create([
                'purchase_invoice_id' => $invoiceId,
                'part_id' => $partsIds,
                'store_id' => $store_ids,
                'available_qty' => $purchased_qys,// $available_qys,
                'purchase_qty' => $purchased_qys,
                'last_purchase_price' => $purchase_price,
                'purchase_price' => $purchase_price,
                'discount_type' => $item_discount_types,
                'discount' => $item_discount,
                'total_after_discount' => $item_total_after_discount,
                'total_before_discount' => $item_total_before_discount
            ]);

            $part = Part::find($partsIds);

            if($part){

                $part->quantity += $purchased_qys;
                $part->last_purchase_price += $purchase_price;
                $part->save();
            }


//            $part->update([
//                'quantity' => $available_qys + $purchased_qys,
//                'last_purchase_price' => $purchase_price,
//            ]);
        },
            $partsIds,
            $available_qys,
            $purchased_qys,
            $last_purchase_prices,
            $purchase_price,
            $store_ids,
            $item_discount_types,
            $item_discount,
            $item_total_after_discount,
            $item_total_before_discount
        );
    }

    public function updateItemsInvoice(PurchaseInvoice $purchaseInvoice, Request $request)
    {
        $itemIds = $request->item_ids;
        $partsIds = $request->part_ids;
        $available_qys = $request->available_qy;
        $purchased_qys = $request->purchased_qy;
        $last_purchase_prices = $request->last_purchase_price;
        $purchase_price = $request->purchase_price;
        $store_ids = $request->store_id;
        $item_discount_types = $request->item_discount_type;
        $item_discount = $request->item_discount;
        $item_total_after_discount = $request->item_total_after_discount;
        $item_total_before_discount = $request->item_total_before_discount;
        $invoiceId = $purchaseInvoice->id;

        $invoiceItems = array_map(function (
            $itemIds,
            $partsIds,
            $available_qys,
            $purchased_qys,
            $last_purchase_prices,
            $purchase_price,
            $store_ids,
            $item_discount_types,
            $item_discount,
            $item_total_after_discount,
            $item_total_before_discount
        ) use ($invoiceId) {
            if ($itemIds) {
                $purchaseInvoiceItem = PurchaseInvoiceItem::find($itemIds);
                $purchaseInvoiceItem->update([
                    'purchase_invoice_id' => $invoiceId,
                    'part_id' => $partsIds,
                    'store_id' => $store_ids,
                    'available_qty' => $available_qys,
                    'purchase_qty' => $purchased_qys,
                    'last_purchase_price' => $last_purchase_prices,
                    'purchase_price' => $purchase_price,
                    'discount_type' => $item_discount_types,
                    'discount' => $item_discount,
                    'total_after_discount' => $item_total_after_discount,
                    'total_before_discount' =>  $item_total_before_discount,
                ]);
            }
            if (!$itemIds) {
                $invoiceItem = PurchaseInvoiceItem::create([
                    'purchase_invoice_id' => $invoiceId,
                    'part_id' => $partsIds,
                    'store_id' => $store_ids,
                    'available_qty' => $available_qys,
                    'purchase_qty' => $purchased_qys,
                    'last_purchase_price' => $last_purchase_prices,
                    'purchase_price' => $purchase_price,
                    'discount_type' => $item_discount_types,
                    'discount' => $item_discount,
                    'total_after_discount' => $item_total_after_discount,
                    'total_before_discount' =>  $item_total_before_discount,
                ]);
            }

            $part = Part::find($partsIds);

            if($part){

                $part->update([
                    'quantity' => $available_qys + $purchased_qys,
                ]);
            }


        },
            $itemIds,
            $partsIds,
            $available_qys,
            $purchased_qys,
            $last_purchase_prices,
            $purchase_price,
            $store_ids,
            $item_discount_types,
            $item_discount,
            $item_total_after_discount,
            $item_total_before_discount
        );
    }
}
