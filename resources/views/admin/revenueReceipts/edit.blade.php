@extends('admin.layouts.app')
@section('title')
    <title>{{ __('Super Car') }} - {{ __('Edit Revenue Receipt') }} </title>
@endsection
@section('content')
    <div class="row small-spacing">
        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a
                        href="{{route('admin:revenues_Items.index')}}"> {{__('Revenues Items')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Edit Revenue Receipt')}}</li>
            </ol>
        </nav>

        @if(isset($sales_invoice))
            @include('admin.revenueReceipts.parts.sales_invoice_header')
        @endif
        @if(isset($card_invoice))
            @include('admin.revenueReceipts.parts.card_invoice_header')
        @endif
        @if (isset($invoice))
            @include('admin.purchase_returns.parts.header')
        @endif
        @if (isset($invoice))
            <input type="hidden" name="purchase_return_id" value="{{$invoice->id}}">
        @endif

        <div class="col-xs-12">
            <div class=" card box-content-wg-new bordered-all primary">
                <h4 class="box-title with-control" style="text-align: initial"><i
                        class="fa fa-money"></i> {{__('Edit Revenue Receipt')}}
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
                          action="{{route('admin:revenueReceipts.update', ['id' => $revenueReceipt->id])}}"
                          class="form">
                        @csrf
                        @method('put')

                        <div class="row">

                            <div class="col-xs-12">
                                @if(authIsSuperAdmin())
                                    <div class="col-md-12">
                                        <div class="form-group has-feedback">
                                            <label for="inputSymbolAR"
                                                   class="control-label">{{__('Select Branch')}}</label>
                                            <select name="branch_id" class="form-control  js-example-basic-single"
                                                    id="branch_id"
                                                {{isset($sales_invoice) || isset($card_invoice) || isset($invoice)? 'disabled':''}}>
                                                <option value="">{{__('Select Branch')}}</option>
                                                @foreach(\App\Models\Branch::all() as $branch)
                                                    <option value="{{$branch->id}}"
                                                        {{$revenueReceipt->branch_id == $branch->id ? 'selected' : ''}}>
                                                        {{$branch->name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                            {{input_error($errors,'branch_id')}}
                                        </div>

                                        @if(isset($sales_invoice))
                                            <input type="hidden" name="branch_id" value="{{$branch_id}}">
                                        @endif

                                        @if(isset($card_invoice))
                                            <input type="hidden" name="branch_id" value="{{$branch_id}}">
                                        @endif

                                        @if(isset($invoice))
                                            <input type="hidden" name="branch_id" value="{{$invoice->branch_id}}">
                                        @endif

                                    </div>
                                @endif
                            </div>

                            <div class="col-md-12">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputNameAR" class="control-label">{{__('Revenue No')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-file-text-o"></i></span>
                                            <input type="text" name="revenue_number"
                                                   value="{{$revenueReceipt->revenue_number}}"
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
                                                   value="{{$revenueReceipt->time}}" id="time"
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
                                                   value="{{$revenueReceipt->date}}" id="date"
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
                                               class="control-label">{{__('Select Revenue Type')}}</label>
                                        <select name="revenue_type_id" class="form-control  js-example-basic-single"
                                                id="revenueType">
                                            <option value="">{{__('Select Revenue Type')}}</option>
                                            @foreach(\App\Models\RevenueType::where('branch_id',$revenueReceipt->branch_id )->get() as $type)
                                                <option
                                                    value="{{$type->id}}" {{$revenueReceipt->revenue_type_id == $type->id ? 'selected' : ''}}>{{$type->type}}</option>
                                            @endforeach
                                        </select>
                                        {{input_error($errors,'revenue_type_id')}}
                                    </div>
                                </div>


                                <div class="col-md-4">
                                    <div class="form-group has-feedback">
                                        <label for="inputSymbolAR"
                                               class="control-label">{{__('Select Revenue Item')}}</label>
                                        <select name="revenue_item_id" class="form-control  js-example-basic-single"
                                                id="revenueItems">
                                            <option value="">{{__('Select Revenue Item')}}</option>
                                            @foreach(\App\Models\RevenueItem::where('branch_id',$revenueReceipt->branch_id )->get() as $item)
                                                <option value="{{$item->id}}"
                                                    {{$revenueReceipt->revenue_item_id == $item->id ? 'selected' : ''}}>
                                                    {{$item->item}}
                                                </option>
                                            @endforeach
                                        </select>
                                        {{input_error($errors,'revenue_item_id')}}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="cost" class="control-label">{{__('Pay For')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                            <input type="text" name="for" class="form-control" id="for"
                                                   value="{{$revenueReceipt->for}}">
                                        </div>
                                        {{input_error($errors,'for')}}
                                    </div>
                                </div>
                                <div class="col-md-12">

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="receiver" class="control-label">{{__('Receiver')}}</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                                <input type="text" name="receiver" class="form-control" id="receiver"
                                                       value="{{$revenueReceipt->receiver}}">
                                            </div>
                                            {{input_error($errors,'receiver')}}
                                        </div>

                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="checkCost" class="control-label">{{__('Cost')}}</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                                                <input type="text" name="cost" class="form-control" id="checkCost"
                                                       readonly
                                                       {{--                                                       {{isset($sales_invoice) && $sales_invoice->type == 'cash'? 'readonly':''}}--}}
                                                       {{--                                                       {{isset($card_invoice) && $card_invoice->type == 'cash'? 'readonly':''}}--}}
                                                       {{--                                                       {{isset($invoice) && $invoice->type == 'cash'? 'readonly':''}}--}}
                                                       value="{{$revenueReceipt->cost}}">
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
                                                               {{$revenueReceipt->payment_type === "cash" ? 'checked' : ''}}  id="cashId"><label
                                                            for="cashId">{{ __("words.cash") }}</label></div>
                                                </li>
                                                <li>
                                                    <div class="radio info">
                                                        <input type="radio" name="payment_type"
                                                               {{$revenueReceipt->payment_type === "network" ? 'checked' : ''}} value="network"
                                                               id="networkId">
                                                        <label for="networkId">{{ __("words.network") }}</label></div>
                                                </li>
                                                <li>
                                                    <div class="radio info">
                                                        <input type="radio" name="payment_type" value="check"
                                                               {{$revenueReceipt->payment_type === "check" ? 'checked' : ''}}   id="checkId"><label
                                                            for="checkId">{{ __("words.check") }}</label></div>
                                                </li>
                                            </ul>
                                        </div>
                                        {{input_error($errors,'payment_type')}}
                                    </div>

                                    <div class="col-md-4"
                                         style="display: {{$revenueReceipt->payment_type === "check" ? '' : 'none'}}"
                                         id="bankName">
                                        <div class="form-group">
                                            <label for="cost" class="control-label">{{__('Bank Name')}}</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                                                <input type="text" name="bank_name" class="form-control"
                                                       value="{{$revenueReceipt->bank_name}}"
                                                       placeholder="{{__('Bank Name')}}">
                                            </div>
                                            {{input_error($errors,'bank_name')}}
                                        </div>
                                    </div>

                                    <div class="col-md-4"
                                         style="display: {{$revenueReceipt->payment_type === "check" ? '' : 'none'}}"
                                         id="checkNumber">
                                        <div class="form-group">
                                            <label for="cost" class="control-label">{{__('Check Number')}}</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                                                <input type="text" name="check_number" class="form-control"
                                                       id="checkNumber"
                                                       value="{{$revenueReceipt->check_number}}"
                                                       placeholder="{{__('Check Number')}}">
                                            </div>
                                            {{input_error($errors,'check_number')}}
                                        </div>
                                    </div>

                                    @if($revenueReceipt->locker_id)

                                        <div class="col-md-4">
                                            <div class="form-group has-feedback" id="showLockers"
                                                 style="display: {{isset($revenueReceipt->locker_id) ? '' : 'none'}} ">
                                                <label for="inputSymbolAR"
                                                       class="control-label">{{__('Select Locker')}}</label>
                                                <select name="locker_id" class="form-control  js-example-basic-single"
                                                        id="locker" {{isset($revenueReceipt->locker_id) ? '' : 'disabled'}}>
                                                    @foreach(\App\Models\Locker::where('id', $revenueReceipt->locker_id)->where('status', 1)->get() as $locker)
                                                        <option value="{{$locker->id}}"
                                                            {{$revenueReceipt->locker_id == $locker->id ? 'selected' : ''}}>
                                                            {{$locker->name}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                {{input_error($errors,'locker_id')}}
                                            </div>
                                        </div>
                                    @endif

                                    @if($revenueReceipt->account_id)
                                        <div class="col-md-4">
                                            <div class="form-group has-feedback" id="showBanks"
                                                 style="display: {{isset($revenueReceipt->account_id) ? '' : 'none'}} ">
                                                <label for="inputSymbolAR"
                                                       class="control-label">{{__('Select Locker')}}</label>
                                                <select name="account_id" class="form-control  js-example-basic-single"
                                                        id="account" {{isset($revenueReceipt->account_id) ? '' : 'disabled'}}>
                                                    @foreach(\App\Models\Account::where('id', $revenueReceipt->account_id)->where('status', 1)->get() as $bank)
                                                        <option
                                                            value="{{$bank->id}}" {{$revenueReceipt->account_id == $bank->id ? 'selected' : ''}}>{{$bank->name}}</option>
                                                    @endforeach
                                                </select>
                                                {{input_error($errors,'account_id')}}
                                            </div>
                                        </div>
                                    @endif
                                </div>


                                <div class="col-md-12">
                                    @if($revenueReceipt->locker_id)
                                        <div class="col-md-4">
                                            <div class="form-group has-feedback">
                                                <label for="inputPhone" class="control-label">{{__('Locker')}}</label>
                                                <div class="switch primary">
                                                    <input type="checkbox" id="switch-3" name="deportation" value="safe" disabled
                                                        {{isset($revenueReceipt->locker_id) ? 'checked' : ''}}>
                                                    <label for="switch-3">{{__('Active')}}</label>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if($revenueReceipt->account_id)
                                        <div class="col-md-4">
                                            <div class="form-group has-feedback" id="appendData">
                                                <label for="inputPhone"
                                                       class="control-label">{{__('Bank account')}}</label>
                                                <div class="switch primary">
                                                    <input type="checkbox" id="switch-2" name="deportation" value="bank" disabled
                                                        {{isset($revenueReceipt->account_id) ? 'checked' : ''}}>
                                                    <label for="switch-2">{{__('Active')}}</label>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="clearfix"></div>

                            @include('admin.partial.receipts-accounts' ,[
                                'type' => $revenueReceipt->user_account_type,
                                'id' => $revenueReceipt->user_account_id,
                                'selected_id' => $revenueReceipt->cost_center_id,
                                'branch_id' => $revenueReceipt->branch_id,
                            ])
                            <div class="clearfix"></div>


                            <div class="form-group col-sm-12">
                                <input type="hidden" name="save_type" id="save_type">
                                {{--                            @include('admin.buttons._save_buttons')--}}

                                <button id="btnsave" type="submit" onclick="saveAndPrint('save')"
                                        class="btn hvr-rectangle-in saveAdd-wg-btn">
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

                                @if($revenueReceipt->purchase_return_id || $revenueReceipt->sales_invoice_id || $revenueReceipt->card_invoice_id)
                                    <button type="submit" class="btn hvr-rectangle-in saveAdd-wg-btn"
                                            onclick="saveAndPrint('save_and_print')">
                                        <i class="ico ico-left fa fa-print"></i>
                                        {{__('Save and print invoice')}}
                                    </button>
                                @endif

                                <button type="submit" class="btn hvr-rectangle-in saveAdd-wg-btn"
                                        onclick="saveAndPrint('save_and_print_receipt')">
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
    </div>
    <!-- /.row small-spacing -->
@endsection
@section('js-validation')
    {!! JsValidator::formRequest('App\Http\Requests\Admin\RevenueReceipt\RevenueReceiptRequest', '.form'); !!}
    @include('admin.partial.sweet_alert_messages')
    <script type="application/javascript">
        $(".select2").select2()
        change_user_accounts()
        $("#time").flatpickr({
            enableTime: true,
            noCalendar: true,
        });
        $("#date").flatpickr();
        $("#revenueType").on('change', function () {
            $.ajax({
                url: "{{ route('admin:revenueTypes.items') }}?revenue_type_id=" + $(this).val(),
                method: 'GET',
                success: function (data) {
                    $('#revenueItems').html(data.items);
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

        $('#branch_id').on('change', function () {
            $.ajax({
                url: "{{ route('admin:getRevenuesTypesByBranchID') }}?branch_id=" + $(this).val(),
                method: 'GET',
                success: function (data) {
                    $('#revenueType').html(data.types);
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
        $('#checkCost').on('keyup', function () {
            if (($('#switch-2').is(':checked') === false) && ($('#switch-3').is(':checked') === false)) {
                swal("{{__('Attention Please!')}}", "{{__('Please Select Locker Or Account Bank Before Fill The balance')}}", "warning");
            }
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

    </script>
@endsection
