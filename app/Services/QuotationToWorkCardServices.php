<?php


namespace App\Services;


use App\Models\WorkCard;
use Carbon\Carbon;

trait QuotationToWorkCardServices
{
    public function createWorkCard($quotation, $car_id)
    {

        $data = [
            'branch_id' => $quotation->branch_id,
            'customer_id' => $quotation->customer_id,
            'car_id' => $car_id,
            'created_by' => auth()->id(),
            'receive_car_status' => 1,
            'receive_car_date' => Carbon::now()->format('Y-m-d'),
            'receive_car_time' => Carbon::now()->format('H:i:s'),
            'delivery_car_status' => 1,
            'delivery_car_date' => Carbon::now()->format('Y-m-d'),
            'delivery_car_time' => Carbon::now()->format('H:i:s'),
        ];

        $data['card_number'] = generateNumber($quotation->branch_id, 'App\Models\WorkCard', 'card_number');

        return WorkCard::create($data);
    }

    public function prepareQuotationData($quotation, $workCard)
    {

        $quotationWinchType = $quotation->types()->where('type', 'Winch')->first();

        $winchRequest = $quotationWinchType ? $quotationWinchType->winchRequest : null;

        $data = [
            'work_card_id' => $workCard->id,
            'date' => $quotation->date,
            'time' => $quotation->time,
            'type' => 'cash',
            'discount_type' => $quotation->discount_type,
            'discount' => $quotation->discount,
            'terms' => null,
            'save_type' => 'save',
            'request_long' => $winchRequest ? $winchRequest->request_long : null,
            'request_lat' => $winchRequest ? $winchRequest->request_lat : null,
            'branch_lat' => $winchRequest ? $winchRequest->branch_lat : null,
            'branch_long' => $winchRequest ? $winchRequest->branch_long : null,
            'winch_discount_type' => $winchRequest ? $winchRequest->discount_type : 'amount',
            'winch_discount' => $winchRequest ? $winchRequest->discount : 0,
            'distance' => $winchRequest ? $winchRequest->distance : 0,
            'price' => $winchRequest ? $winchRequest->price : 0,
            'tax' => $quotation->tax,
            'quotation_to_card'=>'on'
        ];

        if ($winchRequest) {

            $data['active_winch_box'] = 'on';
        }

        $types = $quotation->load('types')->types;

        foreach ($types as $type) {

            $items = $type->items;

            foreach ($items as $index => $item) {

                $index += 1;

                $data[strtolower($type->type) . 's'][$index]['id'] = $item->model_id;

                if ($type->type == 'Part') {

                    $data[strtolower($type->type) . 's'][$index]['purchase_invoice_id'] = $item->model_id;
                }

                $data[strtolower($type->type) . 's'][$index]['qty'] = $item->qty;
                $data[strtolower($type->type) . 's'][$index]['price'] = $item->price;
                $data[strtolower($type->type) . 's'][$index]['discount_type'] = $item->discount_type;
                $data[strtolower($type->type) . 's'][$index]['discount'] = $item->discount;
            }
        }

      return $data;
    }
}
