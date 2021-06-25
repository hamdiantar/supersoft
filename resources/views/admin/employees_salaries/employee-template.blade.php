@php
    $employee_calculator = \App\SalaryCalculator\CalculatorFactory
        ::generate_calculator($employee->employeeSetting->type_account ,[$employee ,$dateRange]);
    $monthNumberOfDays = customCeil($employee_calculator->get_month_number_of_days());
    $month_salary = customCeil($employee_calculator->get_moth_salary());

    $totalReward = customCeil($employee_calculator->get_total_reward());
    $totalDiscount = customCeil($employee_calculator->get_total_discount());
    $total = customCeil($employee_calculator->get_advances());

    $totalAdditional = customCeil($employee_calculator->get_total_additional());
    $additionalHourValue = $employee_calculator->get_additional_hour_value();
    $totalAdditionalAmount = customCeil($totalAdditional * $additionalHourValue);

    $totalDelay = customCeil($employee_calculator->get_total_delay());
    $delayHourValue = $employee_calculator->get_delay_hour_value();
    $totalDelayAmount = customCeil($totalDelay * $delayHourValue);

    $absences = customCeil($employee_calculator->get_absences());
    $absenceDayValue = $employee_calculator->get_absence_day_value();
    $absencesAmount = customCeil($absences * $absenceDayValue);

    $employee_card_work = customCeil($employee_calculator->get_employee_card_work());
    $balance_details = $employee_calculator->get_employee_balance();

@endphp
<input type="hidden" name="employee_data[month_number_of_days]" value="{{ $monthNumberOfDays }}"/>

<div class="col-md-12">
    <div class="col-md-6">
        <div class="form-group">

            <label> {{ __('words.employee-debit') }} </label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-money"></i></span>
                <input disabled value="{{ customCeil($balance_details['debit']) }}"
                    class="form-control"/>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">

            <label> {{ __('words.employee-credit') }} </label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-money"></i></span>
                <input disabled value="{{ customCeil($balance_details['credit']) }}"
                    class="form-control"/>
            </div>
        </div>
    </div>
</div>
<div class="col-md-12">
<div class="col-md-4">
    <div class="form-group">
        <label> {{ __('words.salary') }} </label>
        <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-money"></i></span>
        <input class="form-control" onkeydown="return false" onkeyup="return false" name="employee_data[salary]" value="{{ $month_salary }}"/>
    </div>
    </div>
</div>
<div class="col-md-4">
    <div class="form-group">
        <label> {{ __('words.insurances') }} </label>
        <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-money"></i></span>
        <input class="form-control" onchange="makeMe_2_points('employee_data[insurances]');updateNetSalary()" name="employee_data[insurances]" value="0"/>
    </div>
    </div>
</div>
<div class="col-md-4">
    <div class="form-group">
        <label> {{ __('words.allowances') }} </label>
        <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-money"></i></span>
        <input class="form-control" onchange="makeMe_2_points('employee_data[allowances]');updateNetSalary()" name="employee_data[allowances]" value="0"/>
    </div>
</div>
</div>


</div>

<div class="col-md-12">

<div class="col-md-3">
    <div class="form-group">
        <label> {{ __('words.rewards') }} </label>
        <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-money"></i></span>
        <input class="form-control" onkeypress="return false" onkeyup="return false" onkeydown="return false" name="employee_data[rewards]" value="{{ $totalReward }}"/>
    </div>
</div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label> {{ __('words.discounts') }} </label>
        <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-money"></i></span>
        <input class="form-control" onkeypress="return false" onkeyup="return false" onkeydown="return false" name="employee_data[discounts]" value="{{ $totalDiscount }}"/>
    </div>
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label> {{ __('words.absence_days') }} </label>
        <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-money"></i></span>
        <input class="form-control" onkeypress="return false" onkeyup="return false" onkeydown="return false" name="employee_data[absences]" value="{{ $absences }}"/>
    </div>
    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
        <label> {{ __('words.additional_hours') }} </label>
        <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-money"></i></span>
        <input class="form-control" onkeypress="return false" onkeyup="return false" onkeydown="return false" name="employee_data[additional_hours]" value="{{ $totalAdditional }}"/>
    </div>
</div>
</div>
</div>

<div class="col-md-12">
<div class="col-md-3">
    <div class="form-group">
        <label> {{ __('words.delay_hours') }} </label>
        <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-money"></i></span>
        <input class="form-control" onkeypress="return false" onkeyup="return false" onkeydown="return false" name="employee_data[delay_hours]" value="{{ $totalDelay }}"/>
    </div>
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label> {{ __('words.absence_days_value') }} </label>
        <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-money"></i></span>
        <input class="form-control" onkeypress="return false" onkeyup="return false" onkeydown="return false" name="employee_data[absences_value]" value="{{ $absencesAmount }}"/>
    </div>
    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
        <label> {{ __('words.additional_hours_value') }} </label>
        <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-money"></i></span>
        <input class="form-control" onkeypress="return false" onkeyup="return false" onkeydown="return false" name="employee_data[additional_hours_value]" value="{{ $totalAdditionalAmount }}"/>
    </div>
    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
        <label> {{ __('words.delay_hours_value') }} </label>
        <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-money"></i></span>
        <input class="form-control" onkeypress="return false" onkeyup="return false" onkeydown="return false" name="employee_data[delay_hours_value]" value="{{ $totalDelayAmount }}"/>
    </div>
</div>
</div>
</div>

<div class="col-md-12">
<div class="col-md-3">
    <div class="form-group">
        <label>
            <input type="checkbox" name="advance_included" checked  onclick="updateNetSalary()"/>
            {{ __('words.Advances') }}
        </label>
        <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-money"></i></span>
        <input class="form-control" onkeypress="return false" onkeyup="return false" onkeydown="return false" name="employee_data[advances]" value="{{ $total }}"/>
    </div>
</div>
</div>

    <div class="col-md-3">
        <div class="form-group">
            <label> {{ __('Card Work Percent') }} </label>
            <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-money"></i></span>
            <input class="form-control" onkeypress="return false" onkeyup="return false"
                onkeydown="return false" name="employee_data[card_work_percent]" value="{{ $employee_card_work }}"/>
        </div>
    </div>
    </div>

<div class="col-md-6">
    <div class="form-group">
        <label> {{ __('words.net_salary') }} </label>
        <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-money"></i></span>
        <input class="form-control" onkeypress="return false" onkeyup="return false" onkeydown="return false" id="input-net-salary" name="employee_data[net_salary]"
            data-salary="{{ $month_salary }}" data-rewards="{{ $totalReward }}" data-additional="{{ $totalAdditionalAmount }}"
            data-advances="{{ $total }}" data-absence-value="{{ $absencesAmount }}" data-discounts="{{ $totalDiscount }}"
            data-delay="{{ $totalDelayAmount }}"
            value="{{
               $month_salary + $employee_card_work + $totalReward + $totalAdditionalAmount
                - $total - $absencesAmount - $totalDiscount - $totalDelayAmount
            }}"/>
    </div>
    </div>
</div>
</div>