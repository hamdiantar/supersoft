<?php


namespace App\Services;


use App\Models\WorkCard;

trait MaintenanceStatusServices
{

    public function search($status, $request)
    {
        $data = [];

        $cards = WorkCard::where('status', $status);

        if ($request->has('search') && $request['search'] != '') {

            $cards->whereHas('customer', function ($w) use ($request) {

                $w->where(function ($customer) use ($request) {

                    $customer->orWhere('name_ar', 'like', '%' . $request['search'] . '%')
                        ->orWhere('name_en', 'like', '%' . $request['search'] . '%')
                        ->orWhere('phone1', 'like', '%' . $request['search'] . '%');

                });
            });
        }

        if ($request->has('date_from') && $request['date_from'] != '') {

            $cards->whereDate('created_at', '>=', $request['date_from']);
        }

        if ($request->has('date_to') && $request['date_to'] != '') {

            $cards->whereDate('created_at', '<=', $request['date_to']);
        }

        $data ['count'] = $cards->count();
        $data ['cards'] = $cards;

        return $data;
    }

}
