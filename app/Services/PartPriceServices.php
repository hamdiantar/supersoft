<?php


namespace App\Services;

use App\Models\PartPrice;
use App\Models\PartPriceSegment;

class PartPriceServices
{

    public function saveCartPrices($part, $units, $defaultUnitId)
    {
        if (isset($units)) {

            foreach ($units as $index => $unit) {

                if ($index == 1) {

                    $unit['unit_id'] = $defaultUnitId;
                }

                $unit['part_id'] = $part->id;

                if (isset($unit['default_purchase'])) {

                    $unit['default_purchase'] = 1;

                } else {

                    $unit['default_purchase'] = 0;
                }

                if (isset($unit['default_sales'])) {

                    $unit['default_sales'] = 1;

                } else {

                    $unit['default_sales'] = 0;
                }

                if (isset($unit['default_maintenance'])) {

                    $unit['default_maintenance'] = 1;

                } else {

                    $unit['default_maintenance'] = 0;
                }

                $partPrice = $this->partPrice($unit);

//              UNIT PRICES
                if (isset($unit['prices']) && $unit['prices']) {

                    foreach ($unit['prices'] as $priceSegment) {

                        $priceSegment['part_price_id'] = $partPrice->id;

                        if (isset($priceSegment['id'])) {

                            $partPriceSegment = PartPriceSegment::find($priceSegment['id']);
                            $partPriceSegment->update($priceSegment);

                        }else {

                            PartPriceSegment::create($priceSegment);
                        }
                    }
                }
            }
        }
    }

    public function partPrice($unit)
    {

        $partPrice = null;

        if (isset($unit['part_price_id'])) {

            $partPrice = PartPrice::find($unit['part_price_id']);
        }

        if ($partPrice) {

            $partPrice->update($unit);

        } else {

            $partPrice = PartPrice::create($unit);
        }

        return $partPrice;
    }

//    public function deletePartPriceSegment($partPrice, $unit)
//    {
//
//        $partPriceSegments = $partPrice->priceSegments()->pluck('price_segment_id')->toArray();
//
//        $priceSegmentsRequests = array_column($unit['prices'], 'id');
//
//        $diffIds = array_diff($partPriceSegments, $priceSegmentsRequests);
//
//        if (!empty($diffIds)) {
//
//            $partPrice->priceSegments()->detach($diffIds);
//        }
//    }

    public function defaultUnit($request)
    {

        $units = $request['units'];

        if ($request->has('default_purchase') && isset($units[$request['default_purchase']])) {

            $units[$request['default_purchase']]['default_purchase'] = 1;
        }

        if ($request->has('default_sales') && isset($units[$request['default_sales']])) {

            $units[$request['default_sales']]['default_sales'] = 1;
        }

        if ($request->has('default_maintenance') && isset($units[$request['default_maintenance']])) {

            $units[$request['default_maintenance']]['default_maintenance'] = 1;
        }

        return $units;
    }

    public function deletePart ($part) {

        foreach($part->prices as $price) {

            foreach( $price->partPriceSegments as $partPriceSegment) {

                $partPriceSegment->delete();
            }

            $price->delete();
        }

        $part->delete();
    }
}
