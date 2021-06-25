<?php
namespace App\Http\Controllers\Admin\MoneyPermissions;

use App\Models\Locker;
use Exception;

class LockerProcess {
    protected $amount;
    protected $locker;

    function __construct($amount ,Locker $locker) {
        $this->amount = $amount;
        $this->locker = $locker;
    }

    function increment() {
        $this->locker->balance += $this->amount;
        $this->locker->save();
    }

    function decrement() {
        if ($this->locker->balance < $this->amount) throw new Exception(__('words.cant-withdraw-locker'));
        $this->locker->balance -= $this->amount;
        $this->locker->save();
    }
}