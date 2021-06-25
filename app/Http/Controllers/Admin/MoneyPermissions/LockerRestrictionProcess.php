<?php
namespace App\Http\Controllers\Admin\MoneyPermissions;

use Exception;
use App\AccountingModule\Models\AccountsTree;
use App\ModelsMoneyPermissions\LockerReceivePermission;
use App\ModelsMoneyPermissions\LockerExchangePermission;

class LockerRestrictionProcess {
    use DailyRestrictionTrait;

    protected $money_permission_account;
    protected $source_account;
    protected $money_permission;
    protected $operation;

    function __construct(AccountsTree $money_permission_account ,AccountsTree $source_account) {
        $this->money_permission_account = $money_permission_account;
        $this->source_account = $source_account;
    }

    function set_exchange_permission(LockerExchangePermission $exchange_permission) {
        $this->money_permission = $exchange_permission;
        $this->operation = 'exchange';
    }

    function set_receive_permission(LockerReceivePermission $receive_permission) {
        $this->money_permission = $receive_permission;
        $this->operation = 'receive';
    }

    function __invoke() {
        if (!$this->money_permission) throw new Exception(__('words.set-money-permission'));
        $daily_restriction = $this->create_daily_restriction();
        $this->create_daily_restriction_rows($daily_restriction);
    }
}