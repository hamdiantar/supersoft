<?php

namespace App\SalaryCalculator;

use Carbon\Carbon;

class CardPercentCalculator extends CoreCalculator {
    function __construct($employee, $date_range) {
        parent::__construct($employee, $date_range);
    }
}