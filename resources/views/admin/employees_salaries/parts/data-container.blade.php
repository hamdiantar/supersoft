<div class="col-md-4 col-sm-4 col-xs-4">
    <div class="form-group">
        <label> {{ __('words.employee_name') }} </label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-file"></i></span>
            <input disabled class="form-control" value="{{ $employeeSalary->employee ? $employeeSalary->employee->name : '' }}"/>
        </div>
    </div>
</div>
@php
    $balance_details = $employeeSalary->employee->direct_balance();
@endphp
<div class="col-md-4 col-sm-4 col-xs-4">
    <div class="form-group">
        <label> {{ __('words.employee-debit') }} </label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-file"></i></span>
            <input disabled value="{{ $balance_details['debit'] }}"
                class="form-control"/>
        </div>
    </div>
</div>
<div class="col-md-4 col-sm-4 col-xs-4">
    <div class="form-group">
        <label> {{ __('words.employee-credit') }} </label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-file"></i></span>
            <input disabled value="{{ $balance_details['credit'] }}"
                class="form-control"/>
        </div>
    </div>
</div>
<div class="col-md-4 col-sm-4 col-xs-4">
    <div class="form-group">
        <label> {{ __('words.date_from') }} </label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
            <input disabled class="form-control" value="{{ $employeeSalary->date_from }}"/>
        </div>
    </div>
</div>
<div class="col-md-4 col-sm-4 col-xs-4">
    <div class="form-group">
        <label> {{ __('words.date_to') }} </label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
            <input disabled class="form-control" value="{{ $employeeSalary->date_to }}"/>
        </div>
    </div>
</div>
<div class="col-md-4 col-sm-4 col-xs-4">
    <div class="form-group">
        <label> {{ __('words.month_days') }} </label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
            <input disabled class="form-control" name="month_days"
                value="{{ isset($employeeSalary->employee_data['month_days']) ? $employeeSalary->employee_data['month_days'] : 0 }}"/>
        </div>
    </div>
</div>
<div class="col-md-4 col-sm-4 col-xs-4">
    <div class="form-group">
        <label> {{ __('words.salary') }} </label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-money"></i></span>
            <input class="form-control" value="{{ $employeeSalary->salary }}" disabled/>
        </div>
    </div>
</div>
<div class="col-md-4 col-sm-4 col-xs-4">
    <div class="form-group">
        <label> {{ __('words.insurances') }} </label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-money"></i></span>
            <input class="form-control" disabled name="employee_data[insurances]" value="{{ $employeeSalary->employee_data['insurances'] }}"/>
        </div>
    </div>
</div>
<div class="col-md-4 col-sm-4 col-xs-4">
    <div class="form-group">
        <label> {{ __('words.allowances') }} </label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-money"></i></span>
            <input class="form-control" disabled name="employee_data[allowances]" value="{{ $employeeSalary->employee_data['allowances'] }}"/>
        </div>
    </div>
</div>
<div class="col-md-4 col-sm-4 col-xs-4">
    <div class="form-group">
        <label> {{ __('words.rewards') }} </label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-money"></i></span>
            <input class="form-control" value="{{ $employeeSalary->employee_data['rewards'] }}" disabled/>
        </div>
    </div>
</div>

<div class="col-md-4 col-sm-4 col-xs-4">
    <div class="form-group">
        <label> {{ __('words.discounts') }} </label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-money"></i></span>
            <input class="form-control" value="{{ $employeeSalary->employee_data['discounts'] }}" disabled/>
        </div>
    </div>
</div>
<div class="col-md-4 col-sm-4 col-xs-4">
    <div class="form-group">
        <label> {{ __('words.absence_days') }} </label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-money"></i></span>
            <input class="form-control" value="{{ $employeeSalary->employee_data['absences'] }}" disabled/>
        </div>
    </div>
</div>

<div class="col-md-4 col-sm-4 col-xs-4">
    <div class="form-group">
        <label> {{ __('words.additional_hours') }} </label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-money"></i></span>
            <input class="form-control" value="{{ $employeeSalary->employee_data['additional_hours'] }}" disabled/>
        </div>
    </div>
</div>

<div class="col-md-4 col-sm-4 col-xs-4">
    <div class="form-group">
        <label> {{ __('words.delay_hours') }} </label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-money"></i></span>
            <input class="form-control" value="{{ $employeeSalary->employee_data['delay_hours'] }}" disabled/>
        </div>
    </div>
</div>

<div class="col-md-4 col-sm-4 col-xs-4">
    <div class="form-group">
        <label> {{ __('words.absence_days_value') }} </label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-money"></i></span>
            <input class="form-control" value="{{ $employeeSalary->employee_data['absences_value'] }}" disabled/>
        </div>
    </div>
</div>
<div class="col-md-4 col-sm-4 col-xs-4">
    <div class="form-group">
        <label> {{ __('words.additional_hours_value') }} </label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-money"></i></span>
            <input class="form-control" value="{{ $employeeSalary->employee_data['additional_hours_value'] }}" disabled/>
        </div>
    </div>
</div>

<div class="col-md-4 col-sm-4 col-xs-4">
    <div class="form-group">
        <label> {{ __('words.delay_hours_value') }} </label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-money"></i></span>
            <input class="form-control" value="{{ $employeeSalary->employee_data['delay_hours_value'] }}" disabled/>
        </div>
    </div>
</div>

<div class="col-md-4 col-sm-4 col-xs-4">
    <div class="form-group">
        <label>{{ __('words.advances') }}</label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-money"></i></span>
            <input class="form-control" value="{{ $employeeSalary->employee_data['advances'] }}" disabled/>
        </div>
    </div>
</div>

<div class="col-md-4 col-sm-4 col-xs-4">
    <div class="form-group">
        <label> {{ __('Card Work Percent') }} </label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-money"></i></span>
            <input class="form-control" disabled value="{{ $employeeSalary->employee_data['card_work_percent'] }}"/>
        </div>
    </div>
</div>
<div class="col-md-4 col-sm-4 col-xs-4">
    <div class="form-group">
        <label> {{ __('words.net_salary') }} </label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-money"></i></span>
            <input class="form-control" id="input-net-salary" readonly
                name="employee_data[net_salary]" value="{{ $employeeSalary->employee_data['net_salary'] }}"/>
        </div>
    </div>
</div>

<div class="col-md-4 col-sm-4 col-xs-4">
    <div class="form-group">
        <label> {{ __('words.date') }} </label>
        <input class="form-control" disabled value="{{$employeeSalary->date}}"/>
    </div>
</div>
<div class="clearfix"></div>
<div class="col-md-4 col-sm-4 col-xs-4">
    <div class="form-group">
        <label> {{__('words.Method_Of_Deportation')}}</label>
        <input class="form-control" disabled value="{{ __(ucfirst($employeeSalary->deportation_method)) }}"/>
    </div>
</div>
@if ($employeeSalary->locker_id)
    @php
    $locker = \App\Models\Locker::withTrashed()->find($employeeSalary->locker_id);
    @endphp
    <div class="col-md-4 col-sm-4 col-xs-4">
        <div class="form-group">
            <label> {{ __('words.locker_name') }} </label>
            <input class="form-control" value="{{$locker->name}}" disabled/>
        </div>
    </div>
@elseif ($employeeSalary->account_id)
    @php
    $account = \App\Models\Account::withTrashed()->find($employeeSalary->account_id);
    @endphp
    <div class="col-md-4 col-sm-4 col-xs-4">
        <div class="form-group">
            <label> {{ __('words.bank_name') }} </label>
            <input class="form-control" value="{{$account->name}}" disabled/>
        </div>
    </div>
@endif
<div class="clearfix"></div>
