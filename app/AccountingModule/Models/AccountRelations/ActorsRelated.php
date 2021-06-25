<?php

namespace App\AccountingModule\Models\AccountRelations;

use App\Models\Customer;
use App\Models\CustomerCategory;
use App\Models\EmployeeData;
use App\Models\EmployeeSetting;
use App\Models\Supplier;
use App\Models\SupplierGroup;
use Illuminate\Database\Eloquent\Model;

class ActorsRelated extends Model
{
    /**
        actor_type : customer ,supplier ,employee
        related_as : global ,actor_group ,actor_id
            we use this column to identify the account relation for global actor (employees ,customers & suppliers)
            or for a group or for a custom actor');
        related_id
     */
    protected $fillable = [
        'account_relation_id' ,'actor_type' ,'related_as' ,'related_id'
    ];

    function customer() {
        return $this->belongsTo(Customer::class ,'related_id');
    }

    function customer_group() {
        return $this->belongsTo(CustomerCategory::class ,'related_id');
    }

    function employee() {
        return $this->belongsTo(EmployeeData::class ,'related_id');
    }

    function employee_group() {
        return $this->belongsTo(EmployeeSetting::class ,'related_id');
    }

    function supplier() {
        return $this->belongsTo(Supplier::class ,'related_id');
    }

    function supplier_group() {
        return $this->belongsTo(SupplierGroup::class ,'related_id');
    }
}
