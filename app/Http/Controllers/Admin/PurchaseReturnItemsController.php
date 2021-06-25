<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\PurchaseReturn;
use App\Model\PurchaseReturnItem;
use App\Models\Part;
use App\Models\PurchaseInvoice;
use Illuminate\Http\Request;

class PurchaseReturnItemsController extends Controller
{
    public function createItemsInvoice(PurchaseReturn $purchaseReturn, Request $request)
    {
        $itemToReturn = $request->idsItemsToReturn;
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
        $invoiceReturnId = $purchaseReturn->id;

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
        ) use ($invoiceReturnId, $itemToReturn, $request) {
            foreach ($itemToReturn as $item) {
                if ($item === $partsIds) {
                    $return_item_data = [
                        'part_id' => $partsIds,
                        'store_id' => $store_ids,
                        'available_qty' => $available_qys,
                        'purchase_qty' => $purchased_qys,
                        'last_purchase_price' => $last_purchase_prices,
                        'purchase_price' => $purchase_price,
                        'discount_type' => $item_discount_types,
                        'discount' => $item_discount,
                        'total_after_discount' => $item_total_after_discount,
                        'total_before_discount' => $item_total_before_discount,
                        'purchase_returns_id' => $invoiceReturnId,
                    ];
                    $invoiceReturnItem = PurchaseReturnItem::where([
                        'part_id' => $partsIds,
                        'purchase_returns_id' => $invoiceReturnId
                    ])->first();


                    $part = Part::find($partsIds);
                    $purchaseInvoice = PurchaseInvoice::find($request['purchase_invoice_id']);

                    if ($invoiceReturnItem) {

                        if($part) {
                            $part->quantity += $invoiceReturnItem->purchase_qty;
                            $part->quantity -= $purchased_qys;

                            if($part->quantity < 0){
                                $part->quantity = 0;
                            }

                            $part->save();
                        }

                        if($purchaseInvoice ) {

                            $invoice_item = $purchaseInvoice->items()->where('part_id', $partsIds)->first();

                            if($invoice_item) {

                                $invoice_item->purchase_qty +=  $invoiceReturnItem->purchase_qty;

                                $invoice_item->purchase_qty -= $purchased_qys;

                                if( $invoice_item->purchase_qty  < 0){
                                    $invoice_item->purchase_qty  = 0;
                                }

                                $invoice_item->save();
                            }
                        }

                        $invoiceReturnItem->update($return_item_data);
                    }
                    else {

                        $invoiceReturnItem = PurchaseReturnItem::create($return_item_data);

                        if($part) {
                            $part->quantity -= $purchased_qys;

                            if($part->quantity < 0) {
                                $part->quantity = 0;
                            }

                            $part->save();
                        }

                        if($purchaseInvoice) {

                            $invoice_item = $purchaseInvoice->items()->where('part_id', $partsIds)->first();

                            if($invoice_item){

                                $invoice_item->purchase_qty -= $purchased_qys;

                                if( $invoice_item->purchase_qty  < 0){
                                    $invoice_item->purchase_qty  = 0;
                                }

                                $invoice_item->save();
                            }
                        }
                    }
                }
            }
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

    public function updateItemsInvoice(PurchaseReturn $purchaseReturn, Request $request)
    {
        $itemToReturn = $request->idsItemsToReturn;
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
        $invoiceReturnId = $purchaseReturn->id;

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
        ) use ($invoiceReturnId, $itemToReturn, $purchaseReturn, $request) {
            foreach ($itemToReturn as $item) {
                if ($item === $partsIds) {

                    $part = Part::find($partsIds);

                    if($part){
                        $part->quantity += $invoiceReturnItem->purchase_qty;
                        $part->quantity -= $purchased_qys;

                        if($part->quantity < 0){
                            $part->quantity = 0;
                        }

                        $part->save();
                    }

                    $purchaseInvoice = PurchaseInvoice::find($request['purchase_invoice_id']);

                    if($purchaseInvoice ) {

                        $invoice_item = $purchaseInvoice->items()->where('part_id', $partsIds)->first();

                        if($invoice_item) {

                            $invoice_item->purchase_qty +=  $invoiceReturnItem->purchase_qty;

                            $invoice_item->purchase_qty -= $purchased_qys;

                            if( $invoice_item->purchase_qty  < 0){
                                $invoice_item->purchase_qty  = 0;
                            }

                            $invoice_item->save();
                        }
                    }

                    $invoiceReturnItem = $purchaseReturn->update([
                        'part_id' => $partsIds,
                        'store_id' => $store_ids,
                        'available_qty' => $available_qys,
                        'purchase_qty' => $purchased_qys,
                        'last_purchase_price' => $last_purchase_prices,
                        'purchase_price' => $purchase_price,
                        'discount_type' => $item_discount_types,
                        'discount' => $item_discount,
                        'total_after_discount' => $item_total_after_discount,
                        'total_before_discount' => $item_total_before_discount,
                        'purchase_returns_id' => $invoiceReturnId,
                    ]);

//                    $part->update([
//                        'quantity' => $available_qys - $purchased_qys,
//                    ]);
                }
            }
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
}
