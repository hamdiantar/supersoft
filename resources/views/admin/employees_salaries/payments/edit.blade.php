@extends('admin.layouts.app')


@section('title')
    <title>{{ __('Super Car') }} - {{ __('Edit Employee Salary Payment') }} </title>
@endsection

@section('content')
    <div class="row small-spacing">
    <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">

                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="{{url('admin/employees_salaries')}}"> {{__('Employee Salaries')}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin:employee-salary-payments.index' ,['salary_id' => $salary->id] )}}"> {{__('Employee Salary Payments')}}</a></li>               <li class="breadcrumb-item active"> {{__('Edit Employee Salary Payment')}}</li>

            </ol>
        </nav>

        @include('admin.employees_salaries.payments.rest-paid-salary' ,['salary' => $salary])

        <div class="col-xs-12">
        <div class=" card box-content-wg-new bordered-all primary">
                <h4 class="box-title with-control" style="text-align: initial"><i class="fa fa-money"></i>  {{__('Edit Employee Salary Payment')}}
                <span class="controls hidden-sm hidden-xs pull-left">
                <button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Save')}}<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f1.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Reset')}}<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f2.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px"> {{__('Back')}} <img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f3.png')}}"></button>
						</span>
            </h4>
                    <div class="box-content">
                    <form id="employee-salary-create" method="post"
                        action="{{route('admin:employee-salary-payments.update' ,['employee_salary_payment' => $salary_payment->id])}}" class="form">
                        @csrf
                        @method('put')
                        <input type="hidden" name="for" value="salaries"/>
                        <input type="hidden" name="employee_salary_id" value="{{ $salary->id }}"/>
                        <input type="hidden" name="branch_id" value="{{ $salary->branch_id }}"/>
                        <input type="hidden" name="receiver" value="{{ optional($salary->employee)->name }}"/>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <div class="form-group has-feedback">
                                        <label for="inputPhone" class="control-label">{{__('Locker')}}</label>
                                        <div class="switch primary">
                                            <input type="checkbox" id="switch-3" {{ $salary_payment->deportation == "safe" ? 'checked' : '' }} disabled>
                                            <label for="switch-3">{{__('Locker')}}</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group has-feedback" id="appendData">
                                        <label for="inputPhone" class="control-label">{{__('Bank account')}}</label>
                                        <div class="switch primary">
                                            <input type="checkbox" id="switch-2" {{ $salary_payment->deportation == "bank" ? 'checked' : '' }} disabled>
                                            <label for="switch-2">{{__('Bank account')}}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group has-feedback">
                                        <label for="inputSymbolAR" class="control-label">{{__($salary_payment->deportation == 'bank' ? 'Account' : 'Locker')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                                            <input type="text" class="form-control" disabled
                                                value="{{ $salary_payment->deportation == 'bank' ? optional($salary_payment->bank)->name : optional($salary_payment->locker)->name }}"/>
                                        </div>
                                    </div>
                                </div>
                                </div>

                                <div class="col-md-12">
                                <div class="col-md-6">
                                    <div class="form-group has-feedback">
                                        <label for="inputSymbolAR" class="control-label">{{__('Expense Type')}}</label>
                                        <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-file-text"></i></span>

                                        <input value="{{ optional($salary_payment->expenseType)->type }}" class="form-control" disabled>
                                    </div>
                                </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group has-feedback">
                                        <label for="inputSymbolAR" class="control-label">{{__('Expense Item')}}</label>
                                        <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-file-text"></i></span>

                                        <input value="{{ optional($salary_payment->expenseItem)->item }}" class="form-control" disabled/>
                                    </div>
                                </div>
                                </div>
                                </div>

                                <div class="col-md-12">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="cost" class="control-label">{{__('Cost')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                                            <input type="text" name="cost" class="form-control" id="checkCost" value="{{ $salary_payment->cost }}"
                                                placeholder="{{__('Cost')}}" onchange="checkMe(event ,{{ $salary_payment->cost + $salary->rest_amount }})">
                                        </div>
                                        {{input_error($errors,'cost')}}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="date" class="control-label">{{__('Date')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-clock-o"></li></span>
                                            <input type="text" name="date"
                                                value="{{ $salary_payment->date }}"
                                                class="form-control" id="date" placeholder="{{__('Date')}}">
                                        </div>
                                        {{input_error($errors,'date')}}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="type_en" class="control-label">{{__('Time')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-clock-o"></li></span>
                                            <input type="text" name="time"
                                                value="{{ $salary_payment->time }}"
                                                class="form-control" id="time" placeholder="{{__('Time')}}">
                                        </div>
                                        {{input_error($errors,'time')}}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label> {{ __('accounting-module.cost-center') }} </label>
                                        <select name="cost_center_id" class="form-control select2">
                                            {!!
                                                \App\AccountingModule\Controllers\CostCenterCont::build_centers_options(
                                                    $salary_payment->cost_center_id ,NULL ,1 ,true ,$salary->branch_id
                                                )
                                            !!}
                                        </select>
                                    </div>
                                </div>


                            </div>

                            <div class="col-md-12">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label> {{__('Payment Type')}} <i class="req-star" style="color: red">*</i>
                                    </label>
                                    <ul class="list-inline">
                                        <li>
                                            <div class="radio info">
                                                <input type="radio" name="payment_type" value="cash"
                                                       {{$salary_payment->payment_type === "cash" ? 'checked' : ''}}  id="cashId"><label
                                                    for="cashId">{{ __("words.cash") }}</label></div>
                                        </li>
                                        <li>
                                            <div class="radio info">
                                                <input type="radio" name="payment_type"
                                                       {{$salary_payment->payment_type === "network" ? 'checked' : ''}} value="network" id="networkId">
                                                <label for="networkId">{{ __("words.network") }}</label></div>
                                        </li>
                                        <li>
                                            <div class="radio info">
                                                <input type="radio" name="payment_type" value="check"
                                                       {{$salary_payment->payment_type === "check" ? 'checked' : ''}}   id="checkId"><label
                                                    for="checkId">{{ __("words.check") }}</label></div>
                                        </li>
                                    </ul>
                                </div>
                                {{input_error($errors,'payment_type')}}
                            </div>

                            <div class="col-md-4" style="display: {{$salary_payment->payment_type === "check" ? '' : 'none'}}" id="bankName">
                                <div class="form-group">
                                    <label for="cost" class="control-label">{{__('Bank Name')}}</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                                        <input type="text" name="bank_name" class="form-control" value="{{$salary_payment->bank_name}}"
                                               placeholder="{{__('Bank Name')}}">
                                    </div>
                                    {{input_error($errors,'bank_name')}}
                                </div>
                            </div>

                            <div class="col-md-4" style="display: {{$salary_payment->payment_type === "check" ? '' : 'none'}}" id="checkNumber">
                                <div class="form-group">
                                    <label for="cost" class="control-label">{{__('Check Number')}}</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                                        <input type="text" name="check_number" class="form-control" id="checkNumber"
                                               value="{{$salary_payment->check_number}}"    placeholder="{{__('Check Number')}}">
                                    </div>
                                    {{input_error($errors,'check_number')}}
                                </div>
                            </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-12">
                                <div class="form-group">
                                    @include('admin.buttons._save_buttons')

                                    <input type="hidden" name="save_type" id="save_type">

                                    <button type="submit" class="btn hvr-rectangle-in saveAdd-wg-btn"
                                            onclick="saveAndPrint('save_and_print')">
                                        <i class="ico ico-left fa fa-print"></i>
                                        {{__('Save and print invoice')}}
                                    </button>

                                    <button type="submit" id="btnSaveAndPrint" class="btn hvr-rectangle-in saveAdd-wg-btn"
                                            onclick="saveAndPrint('save_and_print_receipt')">
                                        <i class="ico ico-left fa fa-print"></i>

                                        {{__('Save and print receipt')}}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /.row small-spacing -->
@endsection

@section('js-validation')
    {!! JsValidator::formRequest('App\Http\Requests\Admin\ExpenseReceipt\ExpenseReceiptRequest', '.form'); !!}
    @include('admin.partial.sweet_alert_messages')
    <script type="application/javascript">
        $("#time").flatpickr({
            enableTime: true,
            noCalendar: true,
        });
        $("#date").flatpickr();
        $("#expenseType").on('change', function () {
            $.ajax({
                url: "{{ route('admin:expenseTypes.items') }}?expense_type_id=" + $(this).val(),
                method: 'GET',
                success: function (data) {
                    $('#expenseItems').html(data.items);
                }
            });
        });
        $('#switch-3').on('change', function () {
            if (this.checked) {
                $('#switch-2').prop("checked", false);
                $('#account').prop("disabled", true);
                $('#showBanks').hide();
                $('#locker').prop("disabled", false);
                $('#showLockers').show();
                $('.js-example-basic-single').select2();
            } else {
                $('#showLockers').hide();
            }
        });
        $('#switch-2').on('change', function () {
            if (this.checked) {
                $('#switch-3').prop("checked", false);
                $('#locker').prop("disabled", true);
                $('#showLockers').hide();
                $('#account').prop("disabled", false);
                $('#showBanks').show();
                $('.js-example-basic-single').select2();
            } else {
                $('#showBanks').hide();
            }
        });
        $('#checkCost').on('keyup', function () {
            let locker_id = $('#locker').children("option:selected").val();
            let account_id = $('#account').children("option:selected").val();

            if (($('#switch-2').is(':checked') === false) && ($('#switch-3').is(':checked') === false)) {
                swal("{{__('Attention Please!')}}", "{{__('Please Select Locker Or Account Bank Before Fill The balance')}}", "warning");
            }

            let cost = $(this).val();
            $.ajax({
                url: "{{ route('admin:expenseReceipts.checkBalance') }}?locker_id=" + locker_id + "&account_id=" + account_id + "&cost=" + cost,
                method: 'GET',
                success: function (data) {
                    $('#disabledForBalance').attr('disabled', false);
                    if (data.locker === false && data.account === false) {
                        $('#disabledForBalance').attr('disabled', true);
                        swal("{{__('Balance!')}}", "{{__('Sorry the balance is not enough')}}", "warning");
                    }
                }
            });
        });
        $('#branch_id').on('change', function () {
            $.ajax({
                url: "{{ route('admin:getExpensesTypesByBranchID') }}?branch_id=" + $(this).val(),
                method: 'GET',
                success: function (data) {
                    $('#expenseType').html(data.types);
                    $('#locker').html(data.lockers);
                    $('#account').html(data.banks);
                }
            });
        });

        function checkLocker() {
            let locker_id = $('#locker').children("option:selected").val();
            let account_id = $('#account').children("option:selected").val();

            let cost = $('#checkCost').val();
            $.ajax({
                url: "{{ route('admin:expenseReceipts.checkBalance') }}?locker_id=" + locker_id + "&account_id=" + account_id + "&cost=" + cost,
                method: 'GET',
                success: function (data) {
                    $('#disabledForBalance').attr('disabled', false);
                    if (data.locker === false && data.account === false) {
                        $('#disabledForBalance').attr('disabled', true);
                        swal("{{__('Balance!')}}", "{{__('Sorry the balance is not enough ,Please Select Another Locker')}}", "warning");
                    }
                }
            });
        }

        function checkMe(event ,max_amount) {
            if ($(event.target).val() > max_amount) {
                swal("{{ __('Warning') }}" ,"{{ __('Maximum amount here is') }} : " + max_amount ,"warning")
                $(event.target).val(max_amount)
            }
        }

        $('#checkId').on('click', function () {
            if (this.checked) {
                $("#bankName").show();
                $("#checkNumber").show();
            }
        });

        $('#cashId').on('click', function () {
            if (this.checked) {
                $("#bankName").hide();
                $("#checkNumber").hide();
            }
        });

        $('#networkId').on('click', function () {
            if (this.checked) {
                $("#bankName").hide();
                $("#checkNumber").hide();
            }
        });

        function saveAndPrint(type) {

            $("#save_type").val(type);
        }
    </script>
@endsection
