@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('Create Employee Salary') }} </title>
@endsection

@section('content')
    <div class="row small-spacing">
        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a
                        href="{{url('admin/employees_salaries')}}"> {{__('Employees Salaries')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Create Employee Salary')}}</li>
            </ol>
        </nav>
        <div class="col-xs-12">
            <div class=" card box-content-wg-new bordered-all primary">
                <h4 class="box-title with-control" style="text-align: initial"><i
                        class="fa fa-money"></i> {{__('Create Employee Salary')}}
                    <span class="controls hidden-sm hidden-xs pull-left">
                <button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Save')}}<img
                        class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px"
                        src="{{asset('assets/images/f1.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Reset')}}<img
                                    class="img-fluid"
                                    style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px"
                                    src="{{asset('assets/images/f2.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px"> {{__('Back')}} <img
                                    class="img-fluid"
                                    style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px"
                                    src="{{asset('assets/images/f3.png')}}"></button>
						</span>
                </h4>
                <div class="box-content">
                    <form id="employee-salary-create" method="post" action="{{route('admin:employees_salaries.store')}}"
                          class="form">
                        @csrf
                        @method('post')
                        <div class="row">

                            @if (authIsSuperAdmin())
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label> {{__('Select Branch')}} <i class="req-star"
                                                                           style="color: red">*</i></label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                                            <select name="branch_id" class="form-control js-example-basic-single"
                                                    onchange="getEmpByBranch(event)">
                                                <option value="">{{__('Select Branch')}}</option>
                                                @foreach(\App\Models\Branch::all() as $branch)
                                                    <option value="{{$branch->id}}">{{$branch->name}}</option>
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
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> {{ __('Employee Name') }} </label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                            <select name="employee_id" class="form-control js-example-basic-single"
                                                    id="setEmpByBranch">
                                                <option value=""> {{ __('Select Employee Name') }} </option>
                                                @foreach($employees as $emp)
                                                    <option {{ old('employee_id') == $emp->id ? 'selected' : '' }}
                                                            value="{{ $emp->id }}"> {{ $emp->name }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> {{ __('Date From') }} </label>
                                        <input type="date" class="form-control" name="date_from"/>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> {{ __('Date to') }} </label>
                                        <input type="date" class="form-control" name="date_to"/>
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label> {{ __('View Details') }} </label>
                                        <div class="clearfix"></div>
                                        <button id='employee-data-btn' onclick="loadEmployeeData(event)" type="button"
                                                class="btn btn-info"> {{ __('View Details') }} </button>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>

                        </div>

                        <div id="employee-data"></div>

                        <div class="col-md-12">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label> {{ __('Date') }} </label>
                                    <input type="date" class="form-control" name="date"/>
                                </div>
                            </div>
                            <div class="col-md-3" id="appendData">
                                <div class="form-group">
                                    <label> {{__('Method Of Deportation')}} <i class="req-star" style="color: red">*</i>
                                    </label>
                                    <ul class="list-inline">
                                        <li>
                                            <div class="switch primary">
                                                <input type="checkbox" name="deportation_method" value="bank" disabled
                                                       id="radio-22">
                                                <label for="radio-22">{{__('Bank')}}</label>
                                            </div>

                                        </li>
                                        <li>
                                            <div class="switch primary">
                                                <input type="checkbox" name="deportation_method" value="locker" disabled
                                                       id="radio-33">
                                                <label for="radio-33">{{__('Safe')}}</label>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <label> {{__('Pay Type')}} <i class="req-star" style="color: red">*</i> </label>
                                    <ul class="list-inline">
                                        <li>
                                            <div class="radio info">
                                                <input type="radio" name="pay_type" value="cash" checked
                                                       id="radio-pay-type-cash-22"><label
                                                    for="radio-pay-type-cash-22">{{__('Cash')}}</label></div>
                                        </li>
                                        <li>
                                            <div class="radio pink">
                                                <input type="radio" name="pay_type" value="credit"
                                                       id="radio-pay-type-credit-23"><label
                                                    for="radio-pay-type-credit-23">{{__('Credit')}}</label></div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-3">
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
                        {{input_error($errors,'deportation_method')}}


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
    {!! JsValidator::formRequest('App\Http\Requests\Admin\EmployeeSalary\EmployeeSalaryRequest', '.form'); !!}
    @include('admin.partial.sweet_alert_messages')
    <script type="application/javascript">
        $('#radio-33').on('click', function () {
            if (this.checked) {
                $('#radio-22').prop("checked", false);
                $('#showBanks').remove();
                $('#appendData').after('<div class="col-md-3"  id="showLockers">' +
                    ' <label for="inputSymbolAR" class="control-label">{{__('Select Locker')}}</label>' +
                    ' <select name="locker_id" class="form-control  js-example-basic-single" id="locker" onchange="checkLocker()">' +
                    '<option value="">{{__('Select Locker')}}</option>' +
                    ' @foreach(\App\Models\Locker::where('status', 1)->get() as $locker)' +
                    '<option value="{{$locker->id}}">{{$locker->name}}</option>' +
                    ' @endforeach' +
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
                $('#radio-33').prop("checked", false);
                $('#showLockers').remove();
                $('#appendData').after('<div class="col-md-3"  id="showBanks">' +
                    ' <label for="inputSymbolAR" class="control-label">{{__('Select Bank Account')}}</label>' +
                    ' <select name="account_id" class="form-control  js-example-basic-single" id="account" onchange="checkLocker()">' +
                    '<option value="">{{__('Select Bank Account')}}</option>' +
                    ' @foreach(\App\Models\Account::where('status', 1)->get() as $bank)' +
                    '<option value="{{$bank->id}}">{{$bank->name}}</option>' +
                    ' @endforeach' +
                    ' <select>' +
                    '{{input_error($errors,'account_id')}}' +
                    '</div>');
                $('.js-example-basic-single').select2();
            } else {
                $('#showBanks').remove();
            }
        });

        function updateNetSalary() {
            var salary = $("#input-net-salary").data("salary"),
                rewards = $("#input-net-salary").data("rewards"),
                additional = $("#input-net-salary").data("additional"),
                advances = $("#input-net-salary").data("advances"),
                absenceValue = $("#input-net-salary").data("absence-value"),
                discounts = $("#input-net-salary").data("discounts"),
                delay = $("#input-net-salary").data("delay"),
                insurance = $('input[name="employee_data[insurances]"]').val(),
                allowance = $('input[name="employee_data[allowances]"]').val(),
                card_work_percent = $('input[name="employee_data[card_work_percent]"]').val(),
                includeAdvance = $("input[name='advance_included']").prop('checked')

            var netSalary = parseFloat(salary) + parseFloat(allowance) + parseFloat(card_work_percent) +
                parseFloat(rewards) + parseFloat(additional)
                - parseFloat(absenceValue) - parseFloat(discounts) - parseFloat(delay) - parseFloat(insurance)
            if (includeAdvance) netSalary = netSalary - parseFloat(advances)
            $("#input-net-salary").val(netSalary.toFixed(2))
        }

        function loadEmployeeData(event) {
            $(event.target).attr('disabled', 'disabled')
            var data = {
                _token: '{{ csrf_token() }}',
                employee_id: $('select[name="employee_id"]').find(":selected").val(),
                date_from: $('input[name="date_from"]').val(),
                date_to: $('input[name="date_to"]').val(),
                month_days: $('input[name="month_days"]').val()
            }
            $.ajax({
                dataType: 'json',
                type: 'POST',
                data: data,
                url: '{{ route("admin:employees_salaries.employee-data") }}',
                success: function (rData) {
                    $("#employee-data").html(rData.code)
                    $(event.target).removeAttr('disabled')
                    $('input[name="deportation_method"]').removeAttr('disabled')
                },
                error: function (err) {
                    swal({
                        text: err.responseJSON.message,
                        icon: "warning",
                    })
                    $(event.target).removeAttr('disabled')
                    $('input[name="deportation_method"]').removeAttr('disabled')
                }
            })
        }

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
        }

        function checkLocker() {
            let locker_id = $('#locker').children("option:selected").val();
            let account_id = $('#account').children("option:selected").val();

            let cost = $('input[name="employee_data[net_salary]"]').val();
            $.ajax({
                url: "{{ route('admin:expenseReceipts.checkBalance') }}?locker_id=" + locker_id + "&account_id=" + account_id + "&cost=" + cost,
                method: 'GET',
                success: function (data) {
                    $('#btnsave').attr('disabled', false);
                    $('#btnSaveAndPrint').attr('disabled', false);
                    if (data.locker === false && data.account === false) {
                        $('#btnsave').attr('disabled', true);
                        $('#btnSaveAndPrint').attr('disabled', true);
                        swal("{{__('Balance!')}}", "{{__('Sorry the balance is not enough ,Please Select Another Locker')}}", "warning");
                    }
                }
            });
        }

        $("#employee-salary-create").on('submit', function (event) {
            var deportation = $("input[name='deportation_method']:checked").val()

            if (deportation == "locker") {
                let selected_locker = $('select[name="locker_id"] option:selected').val()
                if (!selected_locker) {
                    swal("{{ __('Warning') }}", "{{ __('Select locker first') }}", "warning")
                    event.preventDefault()
                }
            } else if (deportation == "bank") {
                let selected_bank = $('select[name="account_id"] option:selected').val()
                if (!selected_bank) {
                    swal("{{ __('Warning') }}", "{{ __('Select bank first') }}", "warning")
                    event.preventDefault()
                }
            } else {
                swal("{{ __('Warning') }}", "{{ __('Select deportation method first') }}", "warning")
                event.preventDefault()
            }
        })

        function makeMe_2_points(input_name) {
            let selector = $('input[name="' + input_name + '"]')
            selector.val(parseFloat(selector.val()).toFixed(2))
        }

        function saveAndPrint(type) {

            $("#save_type").val(type);
        }
    </script>
@endsection
