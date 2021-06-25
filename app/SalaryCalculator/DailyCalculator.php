<?php

namespace App\SalaryCalculator;

use Carbon\Carbon;

class DailyCalculator extends CoreCalculator {
    private $dailyWorkHour ,$workDayMoney ,$workHourMoney;

    function __construct($employee, $date_range) {
        parent::__construct($employee, $date_range);
        $this->set_month_number_of_days();
        $this->set_reward_discount();
        $this->set_delay_additional();
        $this->set_absences();
        $this->set_advances();

        $this->dailyWorkHour =
            $this->employee->employeeSetting ?
                $this->employee->employeeSetting->daily_working_hours
                :
                8;

        $this->workDayMoney = $this->employee->employeeSetting->amount_account;

        $this->workHourMoney = $this->employee->employeeSetting->amount_account / $this->dailyWorkHour;

        $this->set_additional_hour_value();
        $this->set_delay_hour_value();
        $this->set_absence_day_value();

        $this->moth_salary = $this->month_number_of_days * $this->workDayMoney;
    }

    private function set_month_number_of_days() {
        $this->month_number_of_days = 0;
        $start = new Carbon($this->date_range[0]);
        $end = new Carbon($this->date_range[1]);
        while($start <= $end) {
            $dayKey = strtolower(substr($start->format('l') ,0 ,3));
            if (in_array($dayKey , $this->vacations))
                $this->month_number_of_days++;
            $start = $start->addDay();
        }
    }

    private function set_additional_hour_value() {
        $this->additional_hour_value = 0;
        if ($this->employee->employeeSetting && $this->employee->employeeSetting->hourly_extra == 'fixed_salary') {
            $this->additional_hour_value = $this->employee->employeeSetting->hourly_extra_equal;
        } else if ($this->employee->employeeSetting) {
            $this->additional_hour_value = $this->employee->employeeSetting->hourly_extra_equal * $this->workHourMoney;
        }
    }

    private function set_delay_hour_value() {
        $this->delay_hour_value = 0;
        if ($this->employee->employeeSetting && $this->employee->employeeSetting->hourly_delay == 'fixed_salary') {
            $this->delay_hour_value = $this->employee->employeeSetting->hourly_delay_equal;
        } else if ($this->employee->employeeSetting) {
            $this->delay_hour_value = $this->employee->employeeSetting->hourly_delay_equal * $this->workHourMoney;
        }
    }

    private function set_absence_day_value() {
        $this->absence_day_value = 0;
        if ($this->employee->employeeSetting->type_absence == 'fixed_salary') {
            $this->absence_day_value = $this->employee->employeeSetting->type_absence_equal;
        } else if ($this->employee->employeeSetting) {
            $this->absence_day_value = $this->employee->employeeSetting->type_absence_equal * $this->workDayMoney;
        }
    }
}