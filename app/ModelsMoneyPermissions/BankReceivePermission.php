<?php
namespace App\ModelsMoneyPermissions;

use App\Models\Branch;
use App\Models\EmployeeData;
use Illuminate\Database\Eloquent\Model;
use App\AccountingModule\Models\CostCenter;

class BankReceivePermission extends Model
{
    use StatusRenderTrait;
    
    protected $fillable = [
        'bank_exchange_permission_id' ,'permission_number' ,'amount' ,'branch_id' ,'source_type' ,
        'employee_id' ,'operation_date' ,'status' ,'note' ,'cost_center_id'
    ];

    function exchange_permission() {
        return $this->belongsTo(BankExchangePermission::class ,'bank_exchange_permission_id');
    }

    function locker_exchange_permission() {
        return $this->belongsTo(LockerExchangePermission::class ,'bank_exchange_permission_id');
    }

    function employee() {
        return $this->belongsTo(EmployeeData::class ,'employee_id');
    }

    function branch() {
        return $this->belongsTo(Branch::class ,'branch_id');
    }

    function cost_center() {
        return $this->belongsTo(CostCenter::class ,'cost_center_id');
    }
}
