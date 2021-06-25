@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('Edit Expense Receipt') }} </title>
@endsection

@section('content')
    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a
                        href="{{route('admin:expenseReceipts.index')}}"> {{__('Expenses Receipts')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Edit Expense Receipt')}}</li>
            </ol>
        </nav>


        @if (isset($invoice))
            @include('admin.purchase-invoices.parts.header')
        @endif


        @if(isset($sales_invoice_return))
            @include('admin.expenseReceipts.parts.sales_invoice_return_header')
        @endif

        <div class="col-xs-12">

            <div class=" card box-content-wg-new bordered-all primary">
                <h4 class="box-title with-control" style="text-align: initial"><i
                        class="fa fa-money"></i> {{__('Edit Expense Receipt')}}
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

                    <form method="post"
                          action="{{route('admin:expenseReceipts.update', ['id' => $expenseReceipt->id])}}"
                          class="form">
                        @csrf
                        @method('put')
                        @if (isset($invoice))
                            <input type="hidden" name="purchase_invoice_id" value="{{$invoice->id}}">
                        @endif
                        <input type="hidden" name="expense_receipt_id" value="{{$expenseReceipt->id}}"
                               class="form-control" id="expense_receipt_id">

                        <div class="row">

                            <div class="col-xs-12">
                                @if(authIsSuperAdmin())
                                    <div class="col-md-12">
                                        <div class="form-group has-feedback">
                                            <label for="inputSymbolAR"
                                                   class="control-label">{{__('Select Branch')}}</label>
                                            <select name="branch_id" class="form-control  js-example-basic-single"
                                                    id="branch_id" {{isset($sales_invoice_return) || isset($invoice) ? 'disabled':''}}>
                                                <option value="">{{__('Select Branch')}}</option>
                                                @foreach(\App\Models\Branch::all() as $branch)
                                                    <option value="{{$branch->id}}"
                                                        {{$expenseReceipt->branch_id == $branch->id ? 'selected' : ''}}>
                                                        {{$branch->name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                            {{input_error($errors,'branch_id')}}
                                        </div>
                                        @if(isset($sales_invoice_return))
                                            <input type="hidden" name="branch_id" value="{{$branch_id}}">
                                        @endif
                                        @if (isset($invoice))
                                            <input type="hidden" name="branch_id" value="{{$invoice->branch_id}}">
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-12">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputNameAR" class="control-label">{{__('Expense No')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-file-text-o"></i></span>
                                            <input type="text" name="revenue_number"
                                                   value="{{$expenseReceipt->expenses_number}}"
                                                   readonly class="form-control" id="inputNameAR">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="type_en" class="control-label">{{__('Time')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-clock-o"></li></span>
                                            <input type="text" name="time" class="form-control"
                                                   value="{{$expenseReceipt->time}}" id="time"
                                                   placeholder="{{__('Time')}}">
                                        </div>
                                        {{input_error($errors,'time')}}
                                    </div>
                                </div>

                                <div class="col-md-4">

                                    <div class="form-group">
                                        <label for="date" class="control-label">{{__('Date')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-clock-o"></li></span>
                                            <input type="text" name="date" class="form-control"
                                                   value="{{$expenseReceipt->date}}" id="date"
                                                   placeholder="{{__('Date')}}">
                                        </div>
                                        {{input_error($errors,'date')}}
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-12">


                                <div class="col-md-4">
                                    <div class="form-group has-feedback">
                                        <label for="inputSymbolAR"
                                               class="control-label">{{__('Select Expense Type')}}</label>
                                        <select name="expense_type_id" class="form-control  js-example-basic-single"
                                                id="expenseType">
                                            <option value="">{{__('Select Expense Type')}}</option>
                                            @foreach(\App\Models\ExpensesType::where('branch_id',$expenseReceipt->branch_id )->get() as $type)
                                                <option
                                                    value="{{$type->id}}" {{$expenseReceipt->expense_type_id == $type->id ? 'selected' : ''}}>{{$type->type}}</option>
                                            @endforeach
                                        </select>
                                        {{input_error($errors,'expense_type_id')}}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group has-feedback">
                                        <label for="inputSymbolAR"
                                               class="control-label">{{__('Select Expenses Item')}}</label>
                                        <select name="expense_item_id" class="form-control  js-example-basic-single"
                                                id="expenseItems">
                                            <option value="">{{__('Select Expense Item')}}</option>
                                            @foreach(\App\Models\ExpensesItem::where('branch_id',$expenseReceipt->branch_id )->get() as $item)
                                                <option
                                                    value="{{$item->id}}" {{$expenseReceipt->expense_item_id == $item->id ? 'selected' : ''}}>{{$item->item}}</option>
                                            @endforeach
                                        </select>
                                        {{input_error($errors,'expense_item_id')}}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="cost" class="control-label">{{__('Pay For')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                            <input type="text" name="for" class="form-control" id="for"
                                                   value="{{$expenseReceipt->for}}">
                                        </div>
                                        {{input_error($errors,'for')}}
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-12">


                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="receiver" class="control-label">{{__('Receiver')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                            <input type="text" name="receiver" class="form-control" id="receiver"
                                                   value="{{$expenseReceipt->receiver}}">
                                        </div>
                                        {{input_error($errors,'receiver')}}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="cost" class="control-label">{{__('Cost')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                                            <input type="text" name="cost" value="{{$expenseReceipt->cost}}"
                                                   class="form-control" readonly
                                                   {{--                                                   {{isset($sales_invoice_return) && $sales_invoice_return->type == 'cash'? 'readonly':''}}--}}
                                                   {{--                                                   {{isset($invoice) && $invoice->type == 'cash'? 'readonly':''}}--}}
                                                   id="checkCost" placeholder="{{__('Cost')}}">
                                        </div>
                                        {{input_error($errors,'cost')}}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> {{__('Payment Type')}} <i class="req-star" style="color: red">*</i>
                                        </label>
                                        <ul class="list-inline">
                                            <li>
                                                <div class="radio info">
                                                    <input type="radio" name="payment_type" value="cash"
                                                           {{$expenseReceipt->payment_type === "cash" ? 'checked' : ''}}  id="cashId"><label
                                                        for="cashId">{{ __("words.cash") }}</label></div>
                                            </li>
                                            <li>
                                                <div class="radio info">
                                                    <input type="radio" name="payment_type"
                                                           {{$expenseReceipt->payment_type === "network" ? 'checked' : ''}} value="network"
                                                           id="networkId">
                                                    <label for="networkId">{{ __("words.network") }}</label></div>
                                            </li>
                                            <li>
                                                <div class="radio info">
                                                    <input type="radio" name="payment_type" value="check"
                                                           {{$expenseReceipt->payment_type === "check" ? 'checked' : ''}}   id="checkId"><label
                                                        for="checkId">{{ __("words.check") }}</label></div>
                                            </li>
                                        </ul>
                                    </div>
                                    {{input_error($errors,'payment_type')}}
                                </div>

                                <div class="col-md-4"
                                     style="display: {{$expenseReceipt->payment_type === "check" ? '' : 'none'}}"
                                     id="bankName">
                                    <div class="form-group">
                                        <label for="cost" class="control-label">{{__('Bank Name')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                                            <input type="text" name="bank_name" class="form-control"
                                                   value="{{$expenseReceipt->bank_name}}"
                                                   placeholder="{{__('Bank Name')}}">
                                        </div>
                                        {{input_error($errors,'bank_name')}}
                                    </div>
                                </div>

                                <div class="col-md-4"
                                     style="display: {{$expenseReceipt->payment_type === "check" ? '' : 'none'}}"
                                     id="checkNumber">
                                    <div class="form-group">
                                        <label for="cost" class="control-label">{{__('Check Number')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                                            <input type="text" name="check_number" class="form-control" id="checkNumber"
                                                   value="{{$expenseReceipt->check_number}}"
                                                   placeholder="{{__('Check Number')}}">
                                        </div>
                                        {{input_error($errors,'check_number')}}
                                    </div>
                                </div>


                                @if (isset($sales_invoice_return))
                                    <input type="hidden" name="sales_invoice_return_id"
                                           value="{{$sales_invoice_return->id}}">
                                @endif


                                @if($expenseReceipt->locker_id)
                                    <div class="col-md-2">
                                        <div class="form-group has-feedback">
                                            <label for="inputPhone" class="control-label">{{__('Locker')}}</label>
                                            <div class="switch primary">
                                                <input type="checkbox" id="switch-3" name="deportation" value="safe"
                                                       disabled
                                                       onchange="checkLocker()"
                                                    {{isset($expenseReceipt->locker_id) ? 'checked' : ''}}>
                                                <label for="switch-3">{{__('Active')}}</label>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if($expenseReceipt->account_id)
                                    <div class="col-md-2">
                                        <div class="form-group has-feedback" id="appendData">
                                            <label for="inputPhone" class="control-label">{{__('Bank account')}}</label>
                                            <div class="switch primary">
                                                <input type="checkbox" id="switch-2" name="deportation" value="bank"
                                                       disabled
                                                       onchange="checkLocker()"
                                                    {{isset($expenseReceipt->account_id) ? 'checked' : ''}}>
                                                <label for="switch-2">{{__('Bank account')}}</label>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>


                            <div class="col-md-12">


                                @if($expenseReceipt->account_id)
                                    <div class="col-md-4" id="showBanks"
                                         style="display: {{isset($expenseReceipt->account_id) ? '' : 'none'}} ">
                                        <div class="form-group has-feedback">
                                            <label for="inputSymbolAR"
                                                   class="control-label">{{__('Select Account')}}</label>
                                            <select name="account_id" class="form-control  js-example-basic-single"
                                                    id="account" onchange="checkLocker()"
                                                {{isset($expenseReceipt->account_id) ? '' : 'disabled'}}>
                                                <option value="">{{__('Select Account')}}</option>
                                                @foreach(\App\Models\Account::where('id', $expenseReceipt->account_id)->where('status', 1)->get() as $bank)
                                                    <option
                                                        value="{{$bank->id}}" {{$expenseReceipt->account_id == $bank->id ? 'selected' : ''}}>{{$bank->name}}</option>
                                                @endforeach
                                            </select>
                                            {{input_error($errors,'account_id')}}
                                        </div>
                                    </div>
                                @endif

                                @if($expenseReceipt->locker_id)
                                    <div class="col-md-4" id="showLockers"
                                         style="display: {{isset($expenseReceipt->locker_id) ? '' : 'none'}} ">
                                        <div class="form-group has-feedback">
                                            <label for="inputSymbolAR"
                                                   class="control-label">{{__('Select Locker')}}</label>
                                            <select name="locker_id" class="form-control  js-example-basic-single"
                                                    id="locker" onchange="checkLocker()"
                                                {{isset($expenseReceipt->locker_id) ? '' : 'disabled'}}>
                                                <option value="">{{__('Select Locker')}}</option>
                                                @foreach(\App\Models\Locker::where('id', $expenseReceipt->locker_id)->where('status', 1)->get() as $locker)
                                                    <option
                                                        value="{{$locker->id}}" {{$expenseReceipt->locker_id == $locker->id ? 'selected' : ''}}>{{$locker->name}}</option>
                                                @endforeach
                                            </select>
                                            {{input_error($errors,'locker_id')}}
                                        </div>
                                    </div>
                                @endif

                            </div>


                        </div>
                        <div class="clearfix"></div>
                        @include('admin.partial.receipts-accounts' ,[
                            'type' => $expenseReceipt->user_account_type,
                            'id' => $expenseReceipt->user_account_id,
                            'selected_id' => $expenseReceipt->cost_center_id,
                            'branch_id' => $expenseReceipt->branch_id,
                        ])
                        <div class="clearfix"></div>

                        <div class="form-group col-sm-12">

                            <input type="hidden" name="save_type" id="save_type">

                            <button type="submit" id="disabledForBalance" onclick="saveAndPrint('save'); checkLocker()"
                                    class="btn hvr-rectangle-in saveAdd-wg-btn  disabledForBalance">
                                <i class="ico ico-left fa fa-save"></i>
                                {{__('Save')}}
                            </button>

                            <button id="reset" type="button" class="btn hvr-rectangle-in resetAdd-wg-btn">
                                <i class="ico ico-left fa fa-trash"></i>
                                {{__('Reset')}}
                            </button>

                            <button id="back" type="button" class="btn hvr-rectangle-in closeAdd-wg-btn">
                                <i class="ico ico-left fa fa-close"></i>
                                {{__('Back')}}
                            </button>

                            @if($expenseReceipt->sales_invoice_return_id || $expenseReceipt->purchase_invoice_id)
                                <button type="submit" class="btn hvr-rectangle-in saveAdd-wg-btn disabledForBalance"
                                        onclick="saveAndPrint('save_and_print'); checkLocker()">
                                    <i class="ico ico-left fa fa-print"></i>
                                    {{__('Save and print invoice')}}
                                </button>
                            @endif

                            <button type="submit" class="btn hvr-rectangle-in saveAdd-wg-btn disabledForBalance"
                                    onclick="saveAndPrint('save_and_print_receipt'); checkLocker()">
                                <i class="ico ico-left fa fa-print"></i>

                                {{__('Save and print Receipt')}}
                            </button>


                        </div>
                    </form>
                </div>
                <!-- /.box-content -->
            </div>
        </div>
        <!-- /.col-xs-12 -->
    </div>
    <!-- /.row small-spacing -->
@endsection
@section('js-validation')
    {!! JsValidator::formRequest('App\Http\Requests\Admin\ExpenseReceipt\ExpenseReceiptRequest', '.form'); !!}
    @include('admin.partial.sweet_alert_messages')

    <script type="application/javascript">

        $(".select2").select2()

        change_user_accounts()

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
            let cost = $("#checkCost").val();//$(this).val();
            let id = $('#expense_receipt_id').val();
            if (($('#switch-2').is(':checked') === false) && ($('#switch-3').is(':checked') === false)) {
                swal("{{__('Attention Please!')}}", "{{__('Please Select Locker Or Account Bank Before Fill The balance')}}", "warning");
            }
            $.ajax({
                url: "{{ route('admin:expenseReceipts.checkBalance') }}?locker_id=" + locker_id + "&account_id=" + account_id + "&cost=" + cost + "&ex_id=" + id,
                method: 'GET',
                success: function (data) {

                    $('.disabledForBalance').attr('disabled', false);

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
            $.ajax({
                url: "{{ route('branch-cost-center') }}?branch_id=" + $(this).val(),
                method: 'GET',
                async: false,
                success: function (data) {
                    $('select[name="cost_center_id"]').html(data.options);
                    $('select[name="cost_center_id"]').select2()
                }
            });
        });
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


        function checkLocker() {

            let locker_id = $('#locker').children("option:selected").val();
            let account_id = $('#account').children("option:selected").val();

            if (locker_id == '' || account_id == '') {

                return false;
            }

            let cost = $("#checkCost").val();//$(this).val();
            let id = $('#expense_receipt_id').val();

            if (($('#switch-2').is(':checked') === false) && ($('#switch-3').is(':checked') === false)) {
                swal("{{__('Attention Please!')}}", "{{__('Please Select Locker Or Account Bank Before Fill The balance')}}", "warning");
            }
            $.ajax({
                url: "{{ route('admin:expenseReceipts.checkBalance') }}?locker_id=" + locker_id + "&account_id=" + account_id + "&cost=" + cost + "&ex_id=" + id,
                method: 'GET',
                success: function (data) {

                    $('.disabledForBalance').attr('disabled', false);

                    if ($('#switch-2').is(':checked') && data.account === false) {

                        $('.disabledForBalance').attr('disabled', true);

                        swal("{{__('Balance!')}}", "{{__('Sorry the balance is not enough')}}", "warning");

                    }

                    if ($('#switch-3').is(':checked') && data.locker === false) {

                        $('.disabledForBalance').attr('disabled', true);

                        swal("{{__('Balance!')}}", "{{__('Sorry the balance is not enough')}}", "warning");
                    }
                }
            });

        }

    </script>
@endsection
