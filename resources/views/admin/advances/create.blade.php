@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('Create Employee Advance') }} </title>
@endsection


@section('content')
    <div class="row small-spacing">
    <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin:advances.index')}}"> {{__('Employees Advances')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Create Employee Advance')}}</li>
            </ol>
        </nav>
        <div class="col-xs-12">
        <div class=" card box-content-wg-new bordered-all primary">
                <h4 class="box-title with-control" style="text-align: initial"><i class="fa fa-money"></i>  {{__('Create Employee Advance')}}
                <span class="controls hidden-sm hidden-xs pull-left">
                <button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Save')}}<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f1.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Reset')}}<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f2.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px"> {{__('Back')}} <img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f3.png')}}"></button>
						</span>
            </h4>
                    <div class="box-content">
                    <form id="create-advance-form" method="post" action="{{route('admin:advances.store')}}">
                        @csrf
                        @method('post')
                        <div class="row">
                        @if (authIsSuperAdmin())
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label> {{__('Select Branch')}} <i class="req-star" style="color: red">*</i></label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                                                <select name="branch_id" class="form-control js-example-basic-single" onchange="getEmpByBranch(event)">
                                                    <option value="">{{__('Select Branch')}}</option>
                                                    @foreach(\App\Models\Branch::all() as $branch)
                                                        <option value="{{$branch->id}}"
                                                        {{old('branch_id') === $branch->id ? 'selected' : '' }}>{{$branch->name}}</option>
                                                    @endforeach
                                                </select>
                                                {{input_error($errors,'branch_id')}}
                                            </div>
                                        </div>
                                    </div>
                                @else
                                <input type="hidden" name="branch_id" value="{{ auth()->user()->branch_id }}"/>
                                @endif
                            <div class="col-md-12">

                                <div class="col-md-12" style="display: none" id="showMaxAdvance">
                                    <div class="form-group">
                                        <div class="alert alert-info" role="alert" style="font-size: 20px; text-align: center; color: red">
                                            {{__('The Max Advance For This Employee is')}}
                                            <a id="value_of_max_advance" class="alert-link"></a>
                                           {{__(',And The Rest Amount Is')}}
                                            <a id="value_of_rest_advance" class="alert-link"></a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> {{__('Employee Name')}} <i class="req-star"
                                                                             style="color: red">*</i></label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                            <select name="employee_data_id" class="form-control js-example-basic-single"
                                                    onchange="changeMyAmount(event ,'#employee-rest-amount')" id="setEmpByBranch">
                                                <option value="">{{__('Select Employee Name')}}</option>
                                                @foreach(\App\Models\EmployeeData::all() as $emp)
                                                    @php
                                                        $totalWith = $totalDep = 0;
                                                        foreach($emp->advances as $adv) {
                                                            if ($adv->operation == __('withdrawal')) $totalWith += $adv->amount;
                                                            if ($adv->operation == __('deposit')) $totalDep += $adv->amount;
                                                        }
                                                        $total = $totalWith - $totalDep;
                                                        if ($total < 0) $total = 0;
                                                        $max_advance = $emp->employeeSetting->max_advance;
                                                         $restFromMaxAdvance = $max_advance - $total;
                                                    @endphp
                                                    <option value="{{$emp->id}}"
                                                            data-rest-amount="{{ $total }}"
                                                            data-rest-advance="{{ $restFromMaxAdvance }}"
                                                            data-max-advance="{{$max_advance}}">
                                                        {{$emp->name}}</option>
                                                @endforeach
                                            </select>
                                            {{input_error($errors,'employee_data_id')}}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> {{__('The Cost')}} <i class="req-star" style="color: red">*</i></label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                                            <input type="text" class="form-control" onkeyup="checkAmount(event)" name="amount" value="{{old('rest') ?? 0}}">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> {{__('The Rest Amount')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                                            <input id="employee-rest-amount" disabled class="form-control" name="rest" value="{{old('rest') ?? 0}}">
                                        </div>
                                    </div>
                                </div>
                                </div>

                                <div class="col-md-12">
                                <div class="col-md-4">
                            <div class="form-group">
                                <label> {{__('Date')}} <i class="req-star" style="color: red">*</i></label>
                                <input type="date" class="form-control time" name="date"
                                       value="{{now()->format('Y-m-d')}}">
                            </div>
                        </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> {{__('Operation Type')}} <i class="req-star" style="color: red">*</i>
                                        </label>
                                        <ul class="list-inline">
                                            <li>
                                                <div class="radio info">
                                                    <input type="radio" name="operation" value="deposit" checked
                                                           id="radio-10"><label
                                                        for="radio-10">{{__('Deposit')}}</label></div>
                                            </li>
                                            <li>
                                                <div class="radio pink">
                                                    <input type="radio" name="operation" value="withdrawal"
                                                           id="radio-12"><label
                                                        for="radio-12">{{__('Withdrawal')}}</label></div>
                                            </li>
                                        </ul>
                                        {{input_error($errors,'operation')}}
                                    </div>
                                </div>

                                <div class="col-md-4" id="appendData">
                                    <div class="form-group">
                                        <label> {{__('Method Of Deportation')}} <i class="req-star" style="color: red">*</i> </label>
                                        <ul class="list-inline"><li>
                                                <div class="switch primary">
                                                    <input type="checkbox" name="deportation" value="bank" id="radio-22">
                                                    <label for="radio-22">{{__('Bank')}}</label>
                                                </div>

                                            </li>
                                            <li>
                                                <div class="switch primary">
                                                    <input type="checkbox" name="deportation" value="safe" id="radio-33">
                                                    <label for="radio-33">{{__('Locker')}}</label>
                                                </div>
                                            </li>
                                        </ul>
                                        {{input_error($errors,'deportation')}}
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> {{ __('accounting-module.cost-center') }} </label>
                                        <select name="cost_center_id" class="form-control select2">
                                            {!!
                                                \App\AccountingModule\Controllers\CostCenterCont::build_centers_options(
                                                    NULL ,NULL ,1 ,true ,NULL
                                                )
                                            !!}
                                        </select>
                                    </div>
                                </div>


                        </div>



                        <div class="col-md-12">
                            <div class="form-group">
                                <label>{{__('Notes')}}</label>
                                <div class="input-group">
                                    <textarea class="form-control" name="notes" placeholder="{{__('Notes')}}">
                                    </textarea>
                                </div>
                            </div>
                        </div>
                        </div>


                        <div class="col-md-12">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" id="btnsave" class="btn btn-primary"><i class="ico ico-left fa fa-save"></i>{{__('Save')}}</button>
                                    <button id="reset"  type="button" class="btn btn-info"><i class="ico ico-left fa fa-trash"></i>{{__('Reset')}}</button>
                                    <button id="back" type="button" class="btn btn-danger"><i class="ico ico-left fa fa-close"></i>{{__('Back')}}</button>

                                    <input type="hidden" name="save_type" id="save_type">

                                    <button type="submit" class="btn hvr-rectangle-in saveAdd-wg-btn"
                                            onclick="saveAndPrint('save_and_print_receipt')">
                                        <i class="ico ico-left fa fa-print"></i>

                                        {{__('Save and print Receipt')}}
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
    </div>
    <!-- /.row small-spacing -->
@endsection
@section('js-validation')
    {!! JsValidator::formRequest('App\Http\Requests\Admin\Advance\AdvanceRequest'); !!}
    @include('admin.partial.sweet_alert_messages')

    <script type="application/javascript">
        function changeMyAmount(event, selector) {
            let $maxAdvance = $(event.target).find(":selected").data('max-advance');
            let $restAdvance = $(event.target).find(":selected").data('rest-advance');
            $('#showMaxAdvance').show();
            $('#value_of_max_advance').html($maxAdvance.toFixed(2));
            $('#value_of_rest_advance').html($restAdvance.toFixed(2));

            $(selector).val($(event.target).find(":selected").data('rest-amount'))
        }

        $('#radio-33').on('click', function () {
            if (this.checked) {
                var my_accessible_lockers = [] ,selected_branch = $('select[name="branch_id"] option:selected').val()
                @php
                    $user_accessible_locker = NULL;
                    if (!authIsSuperAdmin()) {
                        $user_accessible_locker = \DB::table('lockers_users')
                            ->where('user_id' ,auth()->user()->id)->pluck('locker_id')->toArray();
                    }
                @endphp
                @foreach(\App\Models\Locker::when($user_accessible_locker ,function ($q) use ($user_accessible_locker) {
                    $q->whereIn('id' ,$user_accessible_locker)->orWhereDoesntHave('accessible_users');
                })->where('status', 1)->get() as $locker)
                    my_accessible_lockers.push({
                        id: {{ $locker->id }},
                        name: "{{ $locker->name }}",
                        branch_id: {{ $locker->branch_id }}
                    })
                @endforeach
                var select_options = "<option value=''>{{__('Select Locker')}}</option>"
                my_accessible_lockers.map(function(access_locker) {
                    if (access_locker.branch_id == selected_branch || !selected_branch) {
                        select_options += `<option value="${access_locker.id}">${access_locker.name}</option>`
                    }
                })
                $('#radio-22').prop("checked", false);
                $('#showBanks').remove();
                $('#appendData').after('<div class="col-md-4"  id="showLockers">' +
                    ' <label for="inputSymbolAR" class="control-label">{{__('Select Locker')}}</label>' +
                    ' <select name="locker_id" class="form-control  js-example-basic-single" id="locker" onchange="checkLocker()">' +
                        select_options +
                    ' <select>' +
                    '{{input_error($errors,'locker_id')}}' +
                    '</div>');
                $('.js-example-basic-single').select2();
            } else {
                $('#showLockers').remove();
            }
        });

        $('#radio-22').on('click', function () {
            if (this.checked) {
                var my_accessible_accounts = [] ,selected_branch = $('select[name="branch_id"] option:selected').val()
                @php
                    $user_accessible_accounts = NULL;
                    if (!authIsSuperAdmin()) {
                        $user_accessible_accounts = \DB::table('accounts_users')
                            ->where('user_id' ,auth()->user()->id)->pluck('account_id')->toArray();
                    }
                @endphp
                @foreach(\App\Models\Account::when($user_accessible_accounts ,function ($q) use ($user_accessible_accounts) {
                    $q->whereIn('id' ,$user_accessible_accounts)->orWhereDoesntHave('accessible_users');
                })->where('status', 1)->get() as $bank)
                    my_accessible_accounts.push({
                        id: {{ $bank->id }},
                        name: "{{ $bank->name }}",
                        branch_id: {{ $bank->branch_id }}
                    })
                @endforeach
                var select_options = "<option value=''>{{__('Select Bank Account')}}</option>"
                my_accessible_accounts.map(function(access_bank) {
                    if (access_bank.branch_id == selected_branch || !selected_branch) {
                        select_options += `<option value="${access_bank.id}">${access_bank.name}</option>`
                    }
                })
                $('#radio-33').prop("checked", false);
                $('#showLockers').remove();
                $('#appendData').after('<div class="col-md-4"  id="showBanks">' +
                    ' <label for="inputSymbolAR" class="control-label">{{__('Bank Account')}}</label>' +
                    ' <select name="account_id" class="form-control  js-example-basic-single" id="account" onchange="checkLocker()">' +
                        select_options +
                    ' <select>' +
                    '{{input_error($errors,'account_id')}}' +
                    '</div>');
                $('.js-example-basic-single').select2();
            } else {
                $('#showBanks').remove();
            }
        });

        function getEmpByBranch(event) {
            let branchId = event.target.value
            $.ajax({
                url: "{{ route('branch-cost-center') }}?branch_id=" + branchId,
                method: 'GET',
                async: false,
                success: function (data) {
                    $('select[name="cost_center_id"]').html(data.options);
                    $('select[name="cost_center_id"]').select2()
                }
            });
            $.ajax({
                url: "{{ url('admin/getEmpByBranch') }}?branch_id=" + branchId,
                method: 'GET',
                success: function (data) {
                    $('#setEmpByBranch').html(data.emp);
                }
            });
            var deportation = $("input[name='deportation']:checked").val()
            if (deportation == "safe") {
                $("#radio-33").click()
                $("#radio-33").click()
            } else if (deportation == "bank") {
                $("#radio-22").click()
                $("#radio-22").click()
            }
        }

        function checkAmount(event) {
            let empId = $("#setEmpByBranch").val();
            let amount = event.target.value;
            let type = $("input[name=operation]:checked").val()
            if (!empId) {
                swal({
                    title: "{{__('Warning')}}",
                    text: "{{__('Please Select Employee')}}",
                    type: "success",
                    buttons: {
                        confirm: {
                            text: "{{__('Ok')}}",
                        },
                    }
                })
                return false
            }
            if (type === "withdrawal") {
                $.ajax({
                    url: "{{ url('admin/checkMaxAdvance') }}?empId="+empId+"&&"+"amount=" + amount,
                    method: 'GET',
                    success: function (data) {
                        if (!data.result) {
                            swal({
                                title: "{{__('Warning')}}",
                                text: "{{__('The amount should be equal or  less than the max Advance')}}",
                                type: "success",
                                buttons: {
                                    confirm: {
                                        text: "{{__('Ok')}}",
                                    },
                                }
                            })
                            $("#btnsave").attr('disabled', true)
                        }
                        if(data.result) {
                            $("#btnsave").attr('disabled', false)
                        }
                    }
                });
            }
        }

        function checkLocker() {
            let locker_id = $('#locker').children("option:selected").val();
            let account_id = $('#account').children("option:selected").val();

            let cost = $('input[name="amount"]').val();
            $.ajax({
                url: "{{ route('admin:expenseReceipts.checkBalance') }}?locker_id=" + locker_id + "&account_id=" + account_id + "&cost=" + cost,
                method: 'GET',
                success: function (data) {
                    $('#btnsave').attr('disabled', false);
                    if (data.locker === false && data.account === false) {
                        $('#btnsave').attr('disabled', true);
                        swal("{{__('Balance!')}}", "{{__('Sorry the balance is not enough ,Please Select Another Locker')}}", "warning");
                    }
                }
            });
        }

        $('#btnsave').on('click' ,function(e) {check_amount_vs_available(e)})
        $('#create-advance-form').on('submit' ,function(e) {return check_amount_vs_available(e)})

        function check_amount_vs_available(event) {
            let amount = parseFloat($('input[name="amount"]').val()),
                available_amount = parseFloat($('#value_of_rest_advance').text()),
                operation = $('input[name="operation"]:checked').val()
            if (amount > available_amount && operation == 'withdrawal') {
                swal("{{__('Balance!')}}", "{{__('Sorry the balance is not enough ,Please Select Another Locker')}}", "warning");
                event.preventDefault()
                return false
            }
            if ( !$('input[name="deportation"]:checked').val() ) {
                swal("{{__('Warning')}}", "{{__('words.choose-deportation-method')}}", "warning");
                event.preventDefault()
                return false
            }
        }

        function saveAndPrint(type) {

            $("#save_type").val(type);
        }
    </script>
@endsection
