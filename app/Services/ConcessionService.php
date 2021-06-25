<?php


namespace App\Services;


use App\Models\Concession;
use App\Models\ConcessionItem;
use App\Models\PartPrice;

class ConcessionService
{
    public function getModelNameSpace($modelName)
    {
        $model = "App\Models" . "\\" . $modelName;
        return $model;
    }

    public function concessionData($data, $actionType)
    {

        if (!authIsSuperAdmin()) {

            $data['branch_id'] = auth()->user()->branch_id;
        }

        if ($actionType == 'create') {

            $lastConcession = Concession::where('branch_id', $data['branch_id'])->where('type', $data['type'])->orderBy('id', 'desc')->first();

            $data['user_id'] = auth()->id();

            $data['number'] = $lastConcession ? $lastConcession->number + 1 : 1;
        }

        $data['concessionable_id'] = $data['item_id'];

        $model = $this->getModelNameSpace($data['concessionable_type']);

        $data['concessionable_type'] = $model;

        $modelRaw = $model::find($data['item_id']);

        $data['total_quantity'] = $modelRaw && $modelRaw->items ? $modelRaw->items->sum('quantity') : 0;

        return $data;
    }

    public function createConcessionItems($concession)
    {
        $items = $concession->concessionable ? $concession->concessionable->items : [];

        $className = $concession->concessionable ? class_basename($concession->concessionable) : '';

        foreach ($items as $item) {

            $data = [
                'concession_id' => $concession->id,
                'part_id' => $item->part_id,
                'part_price_id' => $item->part_price_id,
                'store_id' => $item->store_id,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'part_price_segment_id' => $item->part_price_segment_id
            ];

            if ($className == 'StoreTransfer' && $concession->type == 'add') {
                $data['store_id'] = $concession->concessionable->store_to_id;
            }

            ConcessionItem::create($data);
        }
    }

    public function deleteConcessionItems($concession)
    {
        $items = $concession->concessionItems;

        if ($items) {

            foreach ($items as $item) {

                $item->delete();
            }
        }
    }

    public function search($request, $onlyTrashed = false)
    {
        $concessions = $onlyTrashed ? Concession::onlyTrashed() : Concession::query();

        if ($request->has('user_id') && $request['user_id'] != '') {

            $concessions->where('user_id', $request['user_id']);
        }

        if($request->has('branch_id') && $request['branch_id'] != '') {
        $concessions->where('branch_id', $request['branch_id']);
        }

        if ($request->has('date_from') && $request['date_from'] != '') {

            $concessions->where('date', '>=', $request['date_from']);
        }

        if ($request->has('date_to') && $request['date_to'] != '') {

            $concessions->where('date', '<=', $request['date_to']);
        }

        if ($request->has('status') && $request['status'] != '') {

            $concessions->where('status', $request['status']);
        }

        if ($request->has('concession_type_id') && $request['concession_type_id'] != '') {

            $concessions->where('concession_type_id', $request['concession_type_id']);
        }

        if ($request->has('type') && $request['type'] != 'all') {

            $concessions->where('type', $request['type']);
        }

        if ($request->has('concession_id') && $request['concession_id'] != '') {

            $concessions->where('id', $request['concession_id']);
        }

        if ($request->has('execution_status') && $request['execution_status'] != '') {

            $concessions->whereHas('concessionExecution', function ($q) use ($request) {

                $q->where('status', $request['execution_status']);
            });
        }

        if ($request->has('item_id') && $request['item_id'] != '' && $request->has('model_name')) {

            $model = 'App\Models\\' . $request['model_name'];
            $concessions->where('concessionable_id', $request['item_id'])->where('concessionable_type', $model);
        }

        return $concessions;
    }

    public function acceptQuantity($concession)
    {
        $concessionItems = $concession->load('concessionItems')->concessionItems;

        $concessionType = $concession->type;

        foreach ($concessionItems as $item) {

            if ($concessionType != 'add') {

                $data = $this->checkConcessionItemQuantity($item);

                if (!$data['status']) {

                    $message = isset($data['message']) ? $data['message'] : '';

                    $this->updateConcessionItemStatus($item, 'failed', $message);

                    return ['status'=> false , 'message'=> $message];
                }
            }

            if (!$this->saveStoreQuantity($item, $concessionType)) {
                continue;
            }

            $this->savePartQuantity($item, $concessionType);

            $this->updateConcessionItemStatus($item, 'success', 'Quantity saved successfully');
        }

        return ['status'=> true];
    }

    public function savePartQuantity($concessionItem, $concessionType)
    {

        $part = $concessionItem->part;

        $unitQuantity = $concessionItem->partPrice ? $concessionItem->partPrice->quantity : 1;

        $requestedQuantity = $unitQuantity * $concessionItem->quantity;

        if ($concessionType == 'add') {

            $this->increaseQuantity($part, $requestedQuantity);

        } else {

            $this->reduceQuantity($part, $requestedQuantity);
        }

        return true;
    }

    public function saveStoreQuantity($concessionItem, $concessionType)
    {
        $part = $concessionItem->part;

        $partStorePivot = $part->stores()->where('store_id', $concessionItem->store_id)->first();

        if (!$partStorePivot) {
            $part->stores()->attach($concessionItem->store_id);
        }

        $partStorePivot = $part->stores()->where('store_id', $concessionItem->store_id)->first();

        $unitQuantity = $concessionItem->partPrice ? $concessionItem->partPrice->quantity : 1;

        $requestedQuantity = $unitQuantity * $concessionItem->quantity;

        if (!$partStorePivot || !$partStorePivot->pivot) {
            return false;
        }

        if ($concessionType == 'add') {

            $this->increaseQuantity($partStorePivot->pivot, $requestedQuantity);

        } else {

            $this->reduceQuantity($partStorePivot->pivot, $requestedQuantity);
        }

        return true;
    }

    public function increaseQuantity($model, $quantity)
    {
        $model->quantity += $quantity;

        $model->save();

        return true;
    }

    public function reduceQuantity($model, $quantity)
    {
        $model->quantity -= $quantity;

        if ($model->quantity < 0) {

            $model->quantity = 0;
        }

        $model->save();

        return true;
    }

    public function checkConcessionItemQuantity($concessionItem)
    {

        $data = [

            'status' => true
        ];

        $part = $concessionItem->part;

        $unitQuantity = $concessionItem->partPrice ? $concessionItem->partPrice->quantity : 1;

        $requestedQuantity = $unitQuantity * $concessionItem->quantity;

        $partStorePivot = $part->stores()->where('store_id', $concessionItem->store_id)->first();

        if (!$partStorePivot || !$partStorePivot->pivot) {

            $data['status'] = false;
            $data['message'] = __('store not valid or not related to this part');

            return $data;
        }

        if ($partStorePivot->pivot->quantity < $requestedQuantity) {

            $data['status'] = false;
            $data['message'] = __('Store quantity less than requested ');
        }

        if ($part->quantity < $requestedQuantity) {

            $data['status'] = false;
            $data['message'] = __('Part quantity less than requested');

            return $data;
        }

        return $data;
    }

    public function updateConcessionItemStatus($item, $status, $message)
    {

        $item->accepted_status = $status;
        $item->log_message = $message;
        $item->save();
    }

    public function checkStoreTransferHasConcession($concession)
    {

        $storeTransfer = $concession->concessionable;

        if ($storeTransfer->concession && $storeTransfer->concession->type == 'withdrawal' && $storeTransfer->concession->status == 'accepted') {
            return true;
        }

        return false;
    }

    public function checkItemHasOldConcession($className, $item_id, $type)
    {
        $model = $this->getModelNameSpace($className);

        $item = $model::find($item_id);

        if (!$item || $className == 'StoreTransfer' && $type == 'withdrawal' && $item->concession) {

            return true;

        } elseif (!$item || $item->concession && $className != 'StoreTransfer') {

            return true;
        }

        return false;
    }
}
