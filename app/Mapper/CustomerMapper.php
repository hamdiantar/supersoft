<?php

namespace App\Mapper;

use App\Models\Customer;
use Illuminate\Support\Collection;

class CustomerMapper
{
    public function MapCollection(Collection $customers): array
    {
        $customersData = [];
        foreach ($customers as $customer) {
            $data = $this->mapOne($customer);
            array_push($customersData, $data);
        }
        return $customersData;
    }

    public function mapOne(Customer $customer): array
    {
        return [
            'id' => $customer->id,
            'name' => $customer->name,
            'type' => $customer->responsible == null ? 'person' : 'company',
            'status' => $customer->status,
            'Balance_to' => $customer->balance_to,
            'Balance_for' => $customer->balance_for,
            'cars_number' => optional($customer->cars)->count(),
            'group' => optional($customer->customerCategory)->name_ar,
            'created_at' => $customer->created_at,
            'updated_at' => $customer->created_at,
        ];
    }
}
