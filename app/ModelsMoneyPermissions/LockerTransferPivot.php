<?php

namespace App\ModelsMoneyPermissions;

use App\Models\LockerTransfer;
use Illuminate\Database\Eloquent\Model;

class LockerTransferPivot extends Model
{
    protected $fillable = [
        'locker_transfer_id' ,'locker_exchange_permission_id' ,'locker_receive_permission_id'
    ];

    function locker_transfer() {
        return $this->belongsTo(LockerTransfer::class ,'locker_transfer_id');
    }

    function locker_exchange_permission() {
        return $this->belongsTo(LockerExchangePermission::class ,'locker_exchange_permission_id');
    }

    function locker_receive_permission() {
        return $this->belongsTo(LockerReceivePermission::class ,'locker_receive_permission_id');
    }
}
