<?php

namespace App\SalaryCalculator;

class CalculatorFactory {
    static function generate_calculator($setting_type_acc ,$params) {
        switch($setting_type_acc) {
            case __("month"):
                return new MonthlyCalculator(...$params);
            break;
            case __("days"):
                return new DailyCalculator(...$params);
            break;
            case __("work_card"):
                return new CardPercentCalculator(...$params);
            break;
        }
        throw new \Exception("Calculator say : setting account type not supported yet");
    }
}