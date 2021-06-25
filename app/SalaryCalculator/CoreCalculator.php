<?php

namespace App\SalaryCalculator;

use App\Http\Controllers\Admin\EmployeeSalariesController;

class CoreCalculator {
    protected $date_range, $employee,
        $month_number_of_days, $total_reward, $total_discount, $total_additional, $total_delay, $absences, $advances,
        $additional_hour_value ,$delay_hour_value ,$absence_day_value ,$vacations ,$moth_salary;

    function __construct($employee, $date_range) {
        $this->employee = $employee;
        $this->date_range = $date_range;
        $this->month_number_of_days = 0;
        $this->total_reward = 0;
        $this->total_discount = 0;
        $this->total_additional = 0;
        $this->total_delay = 0;
        $this->absences = 0;
        $this->advances = 0;
        $this->additional_hour_value = 0;
        $this->delay_hour_value = 0;
        $this->absence_day_value = 0;
        $this->moth_salary = 0;
        $this->vacations = [];
        if ($this->employee->employeeSetting && $this->employee->employeeSetting->saturday) $this->vacations[] = 'sat';
        if ($this->employee->employeeSetting && $this->employee->employeeSetting->sunday) $this->vacations[] = 'sun';
        if ($this->employee->employeeSetting && $this->employee->employeeSetting->monday) $this->vacations[] = 'mon';
        if ($this->employee->employeeSetting && $this->employee->employeeSetting->tuesday) $this->vacations[] = 'tue';
        if ($this->employee->employeeSetting && $this->employee->employeeSetting->wednesday) $this->vacations[] = 'wed';
        if ($this->employee->employeeSetting && $this->employee->employeeSetting->thursday) $this->vacations[] = 'thu';
        if ($this->employee->employeeSetting && $this->employee->employeeSetting->friday) $this->vacations[] = 'fri';
    }

    protected function set_reward_discount() {
        foreach($this->employee->rewards as $reward) {
            if ($reward->type == __('reward')) $this->total_reward += $reward->amount;
            if ($reward->type == __('discount')) $this->total_discount += $reward->amount;
        }
    }

    protected function set_delay_additional() {    
        foreach($this->employee->delays as $delay) {
            $hours = $delay->hours_count + ($delay->minutes_count / 60);
            if ($delay->type == __('delay')) $this->total_delay += $hours;
            if ($delay->type == __('extra')) $this->total_additional += $hours;
        }
    }

    protected function set_absences() {
        $this->absences = isset($this->employee->absences[0]->absences) ? $this->employee->absences[0]->absences : 0;
    }

    protected function set_advances() {
        $totalWith = $totalDep = 0;
        foreach($this->employee->advances as $adv) {
            if ($adv->operation == __('withdrawal')) $totalWith += $adv->amount;
            if ($adv->operation == __('deposit')) $totalDep += $adv->amount;
        }
        $total = $totalWith - $totalDep;
        if ($total < 0) $total = 0;
        $this->advances = $total;
    }

    function get_employee_balance() {
        return $this->employee->direct_balance();
    }

    function get_employee_card_work() {
        try {
            return EmployeeSalariesController::employee_card_work($this->employee ,$this->date_range);
        } catch (\Exception $e) {
            return 0;
        }
    }

    function get_month_number_of_days() {
        return $this->month_number_of_days;
    }

    function get_total_reward() {
        return $this->total_reward;
    }

    function get_total_discount() {
        return $this->total_discount;
    }

    function get_total_additional() {
        return $this->total_additional;
    }

    function get_total_delay() {
        return $this->total_delay;
    }

    function get_absences() {
        return $this->absences;
    }

    function get_advances() {
        return $this->advances;
    }

    function get_additional_hour_value() {
        return $this->additional_hour_value;
    }

    function get_delay_hour_value() {
        return $this->delay_hour_value;
    }

    function get_absence_day_value() {
        return $this->absence_day_value;
    }

    function get_moth_salary() {
        return $this->moth_salary;
    }
}