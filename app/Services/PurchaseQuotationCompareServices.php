<?php


namespace App\Services;


use App\Models\PurchaseQuotation;
use App\Models\PurchaseQuotationItem;

class PurchaseQuotationCompareServices
{
    public function filter ($request, $branch_id) {

        $quotations = PurchaseQuotation::with('items')->where('branch_id', $branch_id);

        if ($request->has('part_id') && $request['part_id'] != null) {

            $quotations->whereHas('items', function ($q) use($request) {
               $q->where('part_id', $request['part_id']);
            });
        }

        if ($request->has('part_type_id') && $request['part_type_id'] != null) {
            $quotations->whereHas('items', function ($q) use ($request) {
                $q->whereHas('part', function ($q) use ($request) {
                    $q->whereHas('spareParts', function ($q) use ($request) {
                        $q->where('spare_part_type_id', $request['part_type_id']);
                    });
                });
            });
        }

        if ($request->has('supplier_id') && $request['supplier_id'] != null) {
            $quotations->where('supplier_id', $request['supplier_id']);
        }

        if ($request->has('quotation_number') && $request['quotation_number'] != null) {
            $quotations->where('number', $request['quotation_number']);
        }

        if ($request->has('purchase_request_id') && $request['purchase_request_id'] != null) {
            $quotations->where('purchase_request_id', $request['purchase_request_id']);
        }

        if ($request->has('date_from') && $request['date_from'] != null) {
            $quotations->where('created_at', '>=', $request['date_from']);
        }

        if ($request->has('date_to') && $request['date_to'] != null) {
            $quotations->where('created_at', '<=', $request['date_to']);
        }

        $quotations = $quotations->select('id', 'number', 'purchase_request_id', 'supplier_id')->get();

        return $quotations;
    }

    public function checkItems ($purchaseQuotationsItems) {

        $data = ['status' => true];
        $suppliers = [];
        $purchaseRequests = [];

        foreach ($purchaseQuotationsItems as $index => $itemId) {

            $item = PurchaseQuotationItem::find($itemId);

            if (!$item) {
                $data['status'] = false;
                $data['message'] = 'sorry, purchase quotation item not valid';
                return $data;
            }

            $purchaseQuotation = $item->purchaseQuotation;

            if (!$purchaseQuotation) {
                $data['status'] = false;
                $data['message'] = 'sorry, purchase quotation not valid';
                return $data;
            }

            $data['purchase_quotations'][] = $purchaseQuotation->id;

            if (!empty($suppliers) && !in_array($purchaseQuotation->supplier_id, $suppliers)) {

                $data['status'] = false;
                $data['message'] = 'sorry, supplier is different';
                return $data;
            }

            $suppliers[] = $purchaseQuotation->supplier_id;

            if (!empty($purchaseRequests) && !in_array($purchaseQuotation->purchase_request_id, $purchaseRequests)) {

                $data['status'] = false;
                $data['message'] = 'sorry, purchase request is different';
                return $data;
            }

            $purchaseRequests[] = $purchaseQuotation->purchase_request_id;
        }

        $data['purchase_request_ids'] = $purchaseRequests;
        $data['suppliersId'] = $suppliers;

        return $data;
    }
}
