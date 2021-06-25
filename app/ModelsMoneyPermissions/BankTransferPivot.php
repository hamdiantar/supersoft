<?php

namespace App\ModelsMoneyPermissions;

use App\Models\AccountTransfer;
use Illuminate\Database\Eloquent\Model;

class BankTransferPivot extends Model
{
    protected $fillable = [
        'bank_transfer_id' ,'bank_exchange_permission_id' ,'bank_receive_permission_id'
    ];

    function bank_transfer() {
        return $this->belongsTo(AccountTransfer::class ,'bank_transfer_id');
    }

    function bank_exchange_permission() {
        return $this->belongsTo(BankExchangePermission::class ,'bank_exchange_permission_id');
    }

    function bank_receive_permission() {
        return $this->belongsTo(BankReceivePermission::class ,'bank_receive_permission_id');
    }
}
