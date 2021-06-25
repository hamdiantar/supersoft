<?php
namespace App\Http\Controllers\Admin\MoneyPermissions;

use App\Models\Account;
use Exception;

class BankProcess {
    protected $amount;
    protected $bank;

    function __construct($amount ,Account $bank) {
        $this->amount = $amount;
        $this->bank = $bank;
    }

    function increment() {
        $this->bank->balance += $this->amount;
        $this->bank->save();
    }

    function decrement() {
        if ($this->bank->balance < $this->amount) throw new Exception(__('words.cant-withdraw-account'));
        $this->bank->balance -= $this->amount;
        $this->bank->save();
    }
}