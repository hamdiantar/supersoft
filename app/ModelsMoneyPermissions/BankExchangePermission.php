<?php
namespace App\ModelsMoneyPermissions;

use App\AccountingModule\Models\CostCenter;
use App\Models\Branch;
use App\Models\Account;
use App\Models\EmployeeData;
use App\Models\Locker;
use Illuminate\Database\Eloquent\Model;

class BankExchangePermission extends Model
{
    use StatusRenderTrait;

    protected $fillable = [        
        'bank_receive_permission_id' ,'from_bank_id' ,'to_bank_id' ,'branch_id' ,'destination_type' ,
        'permission_number' ,'amount' ,'employee_id' ,'operation_date' ,'status' ,'note' ,'cost_center_id'
    ];

    function fromBank() {
        return $this->belongsTo(Account::class ,'from_bank_id');
    }

    function toBank() {
        return $this->belongsTo(Account::class ,'to_bank_id');
    }

    function toLocker() {
        return $this->belongsTo(Locker::class ,'to_bank_id');
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
