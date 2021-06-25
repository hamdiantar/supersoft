<?php


namespace App\Services;


class OpeningBalanceServices
{

    public function openingBalanceItemData($item)
    {
        $data = [
            'part_id' => $item['part_id'],
            'part_price_id' => $item['part_price_id'],
            'part_price_price_segment_id' => isset($item['part_price_price_segment_id']) ? $item['part_price_price_segment_id'] : null,
            'quantity' => $item['quantity'],
            'buy_price' => $item['buy_price'],
            'store_id' => $item['store_id'],
        ];

        return $data;
    }

    public function openingBalanceData($requestData)
    {
        $data = [
            'operation_date' => $requestData['operation_date'],
            'operation_time' => $requestData['operation_time'],
            'notes' => $requestData['notes'],
        ];

        $data['total_money'] = 0;

        if (isset($requestData['items'])) {

            foreach ($requestData['items'] as $item) {

                $itemData = $this->openingBalanceItemData($item);
                $data['total_money'] += $itemData['buy_price'] * $itemData['quantity'];
            }
        }

        return $data;
    }

    function resetOpeningBalanceItems ($openingBalance) {

        foreach ($openingBalance->items as $item) {
            $item->delete();
        }
    }

}
