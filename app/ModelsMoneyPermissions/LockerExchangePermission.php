<?php
namespace App\ModelsMoneyPermissions;

use App\Models\Branch;
use App\Models\Locker;
use App\Models\Account;
use App\Models\EmployeeData;
use Illuminate\Database\Eloquent\Model;
use App\AccountingModule\Models\CostCenter;

class LockerExchangePermission extends Model
{
    use StatusRenderTrait;
    
    protected $fillable = [
        'permission_number' ,'locker_receive_permission_id' ,'from_locker_id' ,'to_locker_id' ,'destination_type' ,
        'amount' ,'employee_id' ,'operation_date' ,'status' ,'branch_id' ,'note' ,'cost_center_id'
    ];

    function fromLocker() {
        return $this->belongsTo(Locker::class ,'from_locker_id');
    }

    function toLocker() {
        return $this->belongsTo(Locker::class ,'to_locker_id');
    }

    function toBank() {
        return $this->belongsTo(Account::class ,'to_locker_id');
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
