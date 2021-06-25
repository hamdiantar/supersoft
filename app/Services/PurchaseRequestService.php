<?php


namespace App\Services;

use App\Models\Part;
use App\Models\SparePart;

class PurchaseRequestService
{

    public function purchaseRequestItemData($item)
    {
        $part = Part::find($item['part_id']);

        $data = [

            'part_id' => $item['part_id'],
            'part_price_id' => $item['part_price_id'],
            'quantity' => $item['quantity'],
            'approval_quantity' => $this->getApprovalQuantity($part, $item['quantity']),
        ];

        return $data;
    }

    public function purchaseRequestData($requestData)
    {

        $data = [

            'date' => $requestData['date'],
            'time' => $requestData['time'],
            'requesting_party' => $requestData['requesting_party'],
            'request_for' => $requestData['request_for'],
            'date_from' => $requestData['date_from'],
            'date_to' => $requestData['date_to'],
            'description' => isset($requestData['description']) ? $requestData['description'] : null,
            'status' => $requestData['status'],
        ];

        return $data;
    }

    public function resetItems($purchaseRequest)
    {
        foreach ($purchaseRequest->items as $item) {

            $item->spareParts()->detach();

            $item->forceDelete();
        }
    }

    public function getApprovalQuantity($part, $requestedQuantity)
    {

        $available = $part->quantity;

        if ($available <= $requestedQuantity) {

            return $requestedQuantity - $available;

        } else {

            return 0;
        }
    }

    public function deletePurchaseRequest($purchaseRequest)
    {
        foreach ($purchaseRequest->items as $item) {

            $item->spareParts()->detach();
            $item->delete();
        }

        $purchaseRequest->delete();
    }

    public function getPartTypes ($partMainTypes, $part_id) {

        $data = [];

        $level = 1;

        foreach ($partMainTypes as $type) {

            $data[$type->id] = $level . '.' . $type->type;
            $this->getChildrenTypes($type, $part_id, $level, $data);
            $level++;
        }

        return $data;
    }

    public function getChildrenTypes($partType, $part_id, $level, &$data)
    {
        $counter = 1;

        $types = $partType->children()->whereHas('allParts', function ($q) use($part_id) {
            $q->where('part_id', $part_id);
        })->get();

        $depthCounter = $level .'.'. $counter;

        foreach ($types as $type) {
            $data[$type->id] = $depthCounter . '.' .$type->type;

            if ($type->children) {
                $this->getChildrenTypes($type, $part_id, $depthCounter,$data);
            }

            $counter++;
        }
    }
}
