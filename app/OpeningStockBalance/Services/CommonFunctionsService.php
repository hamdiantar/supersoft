<?php
namespace App\OpeningStockBalance\Services;

use App\Models\CardInvoice;
use App\Models\Concession;
use App\Models\Part;
use App\Models\Store;
use App\Models\PartPrice;
use App\Models\PurchaseInvoice;
use App\Models\SalesInvoice;
use App\Models\SparePart;
use App\Models\OpeningBalance;

class CommonFunctionsService {
    function fetchTypes($branch_id = NULL) {
        return SparePart::when($branch_id ,function ($q) use ($branch_id) {
            $q->where('branch_id' ,$branch_id);
        })
        ->select('id' ,'type_ar' ,'type_en')
        ->get();
    }

    function fetchSubTypes($branch_id = NULL ,$type_id = NULL) {
        return SparePart::when($branch_id ,function ($q) use ($branch_id) {
            $q->where('branch_id' ,$branch_id);
        })
        ->when($type_id ,function ($q) use ($type_id) {
            $q->where('spare_part_id' ,$type_id);
        })
        ->whereNotNull('spare_part_id')
        ->select('id' ,'type_ar' ,'type_en')
        ->get();
    }

    function fetchParts($branch_id = NULL ,$type_id = NULL) {
        return Part::where('status', 1)->when($branch_id ,function ($q) use ($branch_id) {
            $q->where('branch_id' ,$branch_id);
        })
        ->when($type_id ,function ($q) use ($type_id) {
            $q->whereHas('spareParts' ,function ($subQ) use ($type_id) {
                $subQ->where('spare_part_type_id' ,$type_id);
            });
        })
        ->select('id' ,'name_en' ,'name_ar')
        ->get();
    }

    function buildPartRow($part_id ,$branch_id) {
        $part = Part::with([
            'prices' => function ($q) {
                $q->select(
                    'id' ,'part_id' ,'unit_id' ,'purchase_price' ,'last_purchase_price' ,'quantity'
                    //,'is_default_unit' this column to know the lowest unit
                )
                ->with([
                    'unit' => function ($subQ) {
                        $subQ->select('id' ,'unit_ar' ,'unit_en');
                    },
//                    'partPriceSegments' => function ($subQ) {
//                        $subQ->select('part_price_segments.id as id' ,'part_price_segments.name as name_ar' ,'part_price_segments.name as name_en');
//                    }
                ]);
            }
        ])->find($part_id);

        $stores = Store::where('branch_id' ,$branch_id)->select('id' ,'name_ar' ,'name_en')->get();
        return view('opening-balance.part-row' ,compact('part' ,'stores'))->render();
    }

    function fetch_opening_balance($id) {

        return OpeningBalance::with(['items' => function ($q) {
            $q->with(['part' => function ($subQ) {
                $subQ->select('id' ,'name_ar' ,'name_en')->with([
                    'prices' => function ($q) {
                        $q->select(
                            'id' ,'part_id' ,'unit_id' ,'purchase_price' ,'last_purchase_price' ,'quantity'
                        )
                        ->with([
                            'unit' => function ($subQ) {
                                $subQ->select('id' ,'unit_ar' ,'unit_en');
                            },
                            'partPriceSegments' => function ($subQ) {
//                                $subQ->select(
//                                    'part_price_segments.id as id' ,
//                                    'part_price_segments.name as name_ar'
//                                );
                            }
                        ]);
                    }
                ]);
            }]);
        }])->findOrFail($id);
    }

    static function fetch_price_segments($price_id) {

        $part_price = PartPrice::with(['partPriceSegments'])->find($price_id);

        if ($part_price) return $part_price->partPriceSegments;

        return [];
    }

    function is_any_operation_exists() {
        return PurchaseInvoice::exists() || SalesInvoice::exists() || CardInvoice::exists();
    }
}
