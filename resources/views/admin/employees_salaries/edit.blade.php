@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('Edit Employee Salary') }} </title>
@endsection

@section('content')
    <div class="row small-spacing">
    <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="{{url('admin/employees_salaries')}}"> {{__('Employees Salaries')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Edit Employee Salary')}}</li>
            </ol>
        </nav>
        <div class="col-xs-12">
        <div class=" card box-content-wg-new bordered-all primary">
                <h4 class="box-title with-control" style="text-align: initial"><i class="fa fa-money"></i>  {{__('Edit Employee Salary')}}
                <span class="controls hidden-sm hidden-xs pull-left">
                <button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Save')}}<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f1.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Reset')}}<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f2.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px"> {{__('Back')}} <img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f3.png')}}"></button>
						</span>
            </h4>
                    <div class="box-content">
                    <form method="post" action="{{route('admin:employees_salaries.update', ['id' => $employeeSalary->id])}}" class="form">
                        @csrf
                        @method('put')
                        <div class="row">
                            @if ($employeeSalary->paid_amount != 0)
                                <div class="col-md-12">
                                    <div class="alert alert-warning"> {{ __("Salary is paid ,you can`t update data") }} </div>
                                </div>
                            @endif

                            @if (authIsSuperAdmin())
                                    @php
                                    $branch = \App\Models\Branch::find($employeeSalary->branch_id);
                                  @endphp
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label> {{ __('Branch') }} </label>
                                            <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-file"></i></span>
                                            <input disabled class="form-control" value="{{ $branch->name }}"/>
                                        </div>
                                    </div>
                                    </div>
                                @endif

                            <div class="col-md-12">

                                    <div class="col-md-3">
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
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label> {{ __('words.employee-debit') }} </label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-file"></i></span>
                                                <input disabled value="{{ $balance_details['debit'] }}"
                                                    class="form-control"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label> {{ __('words.employee-credit') }} </label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-file"></i></span>
                                                <input disabled value="{{ $balance_details['credit'] }}"
                                                    class="form-control"/>
                                            </div>
                                        </div>
                                    </div>

                                    @if ($employeeSalary->account_id)
                                    @php
                                        $account = \App\Models\Account::withTrashed()->find($employeeSalary->account_id);
                                    @endphp

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label> {{ __('words.account_name') }} </label>
                                            <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-file"></i></span>
                                            <input class="form-control" value="{{ $account->name }}" disabled/>
                                        </div>
                                    </div>
                                    </div>
                                @endif

                                    </div>

                                    <div class="col-md-12">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label> {{ __('words.date_from') }} </label>
                                            <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                            <input disabled class="form-control" value="{{ $employeeSalary->date_from }}"/>
                                        </div>
                                    </div>
                                    </div>


                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label> {{ __('words.date_to') }} </label>
                                            <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                            <input disabled class="form-control" value="{{ $employeeSalary->date_to }}"/>
                                        </div>
                                    </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label> {{ __('words.month_days') }} </label>
                                            <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                            <input disabled class="form-control" name="month_days" value="{{ isset($employeeSalary->employee_data['month_days']) ? $employeeSalary->employee_data['month_days'] : 0 }}"/>
                                        </div>
                                    </div>

                                    </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label> {{ __('words.salary') }} </label>
                                        <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                        <input class="form-control" value="{{ $employeeSalary->salary }}" disabled/>
                                    </div>
                                </div>
                                </div>
                                </div>

<div class="col-md-12">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label> {{ __('words.insurances') }} </label>
                                        <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                        <input class="form-control" onchange="makeMe_2_points('employee_data[insurances]');updateNetSalary()" name="employee_data[insurances]" value="{{ $employeeSalary->employee_data['insurances'] }}"/>
                                    </div>
                                </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label> {{ __('words.allowances') }} </label>
                                        <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                        <input class="form-control" onchange="makeMe_2_points('employee_data[allowances]');updateNetSalary()" name="employee_data[allowances]" value="{{ $employeeSalary->employee_data['allowances'] }}"/>
                                    </div>
                                </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label> {{ __('words.rewards') }} </label>
                                        <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                        <input class="form-control" value="{{ $employeeSalary->employee_data['rewards'] }}" disabled/>
                                    </div>
                                </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label> {{ __('words.discounts') }} </label>
                                        <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                        <input class="form-control" value="{{ $employeeSalary->employee_data['discounts'] }}" disabled/>
                                    </div>
                                </div>
                            </div>
                            </div>

                            <div class="col-md-12">


                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label> {{ __('words.absence_days') }} </label>
                                        <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                        <input class="form-control" value="{{ $employeeSalary->employee_data['absences'] }}" disabled/>
                                    </div>
                                </div>
                                </div>


                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label> {{ __('words.additional_hours') }} </label>
                                        <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                        <input class="form-control" value="{{ $employeeSalary->employee_data['additional_hours'] }}" disabled/>
                                    </div>
                                </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label> {{ __('words.delay_hours') }} </label>
                                        <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                        <input class="form-control" value="{{ $employeeSalary->employee_data['delay_hours'] }}" disabled/>
                                    </div>
                                </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label> {{ __('words.absence_days_value') }} </label>
                                        <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                        <input class="form-control" value="{{ $employeeSalary->employee_data['absences_value'] }}" disabled/>
                                    </div>
                                </div>
                            </div>
                            </div>

                            <div class="col-md-12">


                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label> {{ __('words.additional_hours_value') }} </label>
                                        <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                        <input class="form-control" value="{{ $employeeSalary->employee_data['additional_hours_value'] }}" disabled/>
                                    </div>
                                </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label> {{ __('words.delay_hours_value') }} </label>
                                        <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                        <input class="form-control" value="{{ $employeeSalary->employee_data['delay_hours_value'] }}" disabled/>
                                    </div>
                                </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>
                                            <input type="checkbox"  name="advance_included" {{ $employeeSalary->advance_included ? 'checked disabled' : '' }}  onclick="updateNetSalary()"/>
                                            {{ __('words.advances') }}
                                        </label>
                                        <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                        <input class="form-control" value="{{ $employeeSalary->employee_data['advances'] }}" disabled/>
                                    </div>
                                </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label> {{ __('Card Work Percent') }} </label>
                                        <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                        <input class="form-control" onkeypress="return false" onkeyup="return false"
                                            onkeydown="return false" value="{{ $employeeSalary->employee_data['card_work_percent'] }}"/>
                                    </div>
                                </div>
                            </div>
                            </div>

                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label> {{ __('words.net_salary') }} </label>
                                        <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                        <input class="form-control" id="input-net-salary" readonly
                                               name="employee_data[net_salary]"
                                               value="{{ $employeeSalary->employee_data['net_salary'] }}"
                                               data-salary="{{ $employeeSalary->salary}}"
                                               data-rewards="{{ $employeeSalary->employee_data['rewards'] }}"
                                               data-additional="{{ $employeeSalary->employee_data['additional_hours_value'] }}"
                                               data-advances="{{ $employeeSalary->advance_included ? $employeeSalary->employee_data['advances'] : 0 }}"
                                               data-absence-value="{{ $employeeSalary->employee_data['absences_value'] }}"
                                               data-discounts="{{ $employeeSalary->employee_data['discounts'] }}"
                                               data-delay="{{ $employeeSalary->employee_data['delay_hours_value'] }}"
                                               data-card-work-percent="{{ $employeeSalary->employee_data['card_work_percent'] }}"/>
                                    </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label> {{ __('words.date') }} </label>

                                        <input type="date" class="form-control" name="date" value="{{$employeeSalary->date}}"/>
                                    </div>
                                </div>


                                <div class="col-md-3" id="appendData">
                                    <div class="form-group">
                                        <label> {{__('words.Method_Of_Deportation')}}</label>
                                        <ul class="list-inline"><li>
                                                <div class="switch primary">
                                                    <input type="checkbox" name="deportation_method" readonly
                                                           {{$employeeSalary->deportation_method === "bank" ? 'checked' : ''}} value="bank" id="radio-22">
                                                    <label for="radio-22">{{__('Bank')}}</label>
                                                </div>

                                            </li>
                                            <li>
                                                <div class="switch primary">
                                                    <input type="checkbox" name="deportation_method" value="locker" readonly
                                                           {{$employeeSalary->deportation_method === "locker" ? 'checked' : ''}} id="radio-33">
                                                    <label for="radio-33">{{__('Safe')}}</label>
                                                </div>
                                            </li>
                                        </ul>
                                        {{input_error($errors,'deportation_method')}}
                                    </div>
                                </div>
                                @if ($employeeSalary->locker_id)
                                    @php
                                    $locker = \App\Models\Locker::withTrashed()->find($employeeSalary->locker_id);
                                    @endphp
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label> {{ __('words.locker_name') }} </label>
                                            <input class="form-control" value="{{$locker->name}}" disabled/>
                                        </div>
                                    </div>
                                @endif


                            </div>
                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> {{ __('accounting-module.cost-center') }} </label>
                                        <select name="cost_center_id" class="form-control select2">
                                            {!!
                                                \App\AccountingModule\Controllers\CostCenterCont::build_centers_options(
                                                    $employeeSalary->branch_id ,NULL ,1 ,true ,$employeeSalary->cost_center_id
                                                )
                                            !!}
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-12">
                                <div class="form-group">
                                    @include('admin.buttons._save_buttons')

                                    <input type="hidden" name="save_type" id="save_type">

                                    <button type="submit" id="btnSaveAndPrint" class="btn hvr-rectangle-in saveAdd-wg-btn"
                                            onclick="saveAndPrint('save_and_print_receipt')">
                                        <i class="ico ico-left fa fa-print"></i>

                                        {{__('Save and print invoice')}}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.box-content -->
        </div>
        <!-- /.col-xs-12 -->
    </div>
    <!-- /.row small-spacing -->
@endsection
@section('js-validation')
    @include('admin.partial.sweet_alert_messages')
    <script type="application/javascript">
        function updateNetSalary() {
            var salary = $("#input-net-salary").data("salary") ,
                rewards = $("#input-net-salary").data("rewards") ,
                additional = $("#input-net-salary").data("additional") ,
                advances = $("#input-net-salary").data("advances") ,
                absenceValue = $("#input-net-salary").data("absence-value") ,
                discounts = $("#input-net-salary").data("discounts") ,
                delay = $("#input-net-salary").data("delay") ,
                card_work_percent = $("#input-net-salary").data("card-work-percent")
                insurance = $('input[name="employee_data[insurances]"]').val(),
                allowance = $('input[name="employee_data[allowances]"]').val()
                // includeAdvance = $("input[name='advance_included']").prop('checked')
            var netSalary = parseFloat(salary) + parseFloat(allowance) + parseFloat(card_work_percent) +
                parseFloat(rewards) + parseFloat(additional)
                - parseFloat(absenceValue) - parseFloat(discounts) - parseFloat(delay) - parseFloat(insurance)
             // if (includeAdvance) netSalary = netSalary - parseFloat(advances)
            $("#input-net-salary").val(netSalary.toFixed(2))
        }

        $(document).ready(function() {
            @if ($employeeSalary->paid_amount != 0)
                $("input,button,select,textarea").prop('disabled' ,true)
            @endif
        })
        function makeMe_2_points(input_name) {
            let selector = $('input[name="'+ input_name +'"]')
            selector.val(parseFloat(selector.val()).toFixed(2))
        }

        function saveAndPrint(type) {

            $("#save_type").val(type);
        }
        </script>
@endsection
