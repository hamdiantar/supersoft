<?php

namespace App\Filters;

use App\Models\PartPrice;
use App\Models\Settlement;
use App\Models\SparePart;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class SettlementFilter
{
    public function filter(Request $request): Builder
    {
        return Settlement::where(function ($query) use ($request) {
            if ($request->has('branch_id') && $request->branch_id != '' && $request->branch_id != null) {
                $query->where('branch_id', $request->branch_id);
            }

            if ($request->has('store_id') && $request->store_id != '' && $request->store_id != null) {
                $storeId = $request->store_id;
                $query->whereHas('items', function ($q) use ($storeId) {
                    $q->where('store_id', $storeId);
                });
            }

            if ($request->has('settlement_type') && $request->settlement_type != '' && $request->settlement_type != null) {
                if ($request->settlement_type == 'both') {
                    $query->whereIn('type', ['negative', 'positive']);
                } else {
                    $query->where('type', $request->settlement_type);
                }
            }

            if ($request->has('number') && $request->number != '' && $request->number != null) {
                $query->where('number', $request->number);
            }

            if ($request->has('dateFrom') && $request->has('dateTo')
                && $request->dateFrom != '' && $request->dateTo != ''
                && $request->dateFrom != null && $request->dateTo != null) {
                $query->whereBetween('date', [$request->dateFrom, $request->dateTo]);
            }

            if ($request->has('part_name') && $request->part_name != '' && $request->part_name != null) {
                $partId = $request->part_name;
                $query->whereHas('items', function ($q) use ($partId) {
                    $q->where('part_id', $partId);
                });
            }

            if ($request->has('barcode') && $request->barcode != '' && $request->barcode != null) {
                $barcode = $request->barcode;
                $partsIds = partPrice::where('barcode', $barcode)->pluck('part_id')->toArray();
                $query->whereHas('items', function ($q) use ($partsIds) {
                    $q->whereIn('part_id', $partsIds);
                });
            }

            if ($request->has('supplier_barcode') && $request->supplier_barcode != '' && $request->supplier_barcode != null) {
                $barcode = $request->supplier_barcode;
                $partsIds = PartPrice::where('supplier_barcode',  $barcode)->pluck('part_id')->toArray();
                $query->whereHas('items', function ($q) use ($partsIds) {
                    $q->whereIn('part_id', $partsIds);
                });
            }

            if ($request->has('partId') && $request->partId != '' && $request->partId != null) {
                session()->forget('allPartsIds');
                $partsId = $request->partId;
                $sparePart = SparePart::with('allParts')->find($partsId);
                if ($sparePart) {
                    getSupPartsByMainPart($sparePart);
                    $partsIds = session('allPartsIds');
                    $partsIdsSearch = [];
                    if (!empty($partsIds)) {
                        $partsIdsSearch = array_unique(array_flatten($partsIds));
                    }
                    $query->whereHas('items', function ($q) use ($partsIdsSearch) {
                        $q->whereIn('part_id',$partsIdsSearch);
                    });
                } else {
                    $query->whereHas('items', function ($q) {
                        $q->whereIn('part_id', []);
                    });
                }
            }

            if ($request->has('concession_status') && $request->concession_status != '' && $request->concession_status != null) {

                if ($request->concession_status == 'not_found') {

                    $query->doesntHave('concession');

                } else {

                    $query->whereHas('concession', function ($q) use ($request) {
                        $q->where('status', $request['concession_status']);
                    });
                }
            }

        });
    }
}
