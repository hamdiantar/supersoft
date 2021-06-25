@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('Create Sales Invoice') }} </title>
@endsection

@section('style')
    <style>
        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
@endsection
@section('content')
    <div class="row small-spacing">
        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a
                        href="{{route('admin:sales.invoices.index')}}"> {{__('Sales Invoices')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Create Sales Invoice')}}</li>
            </ol>
        </nav>

        <div class="col-xs-12">
            <div class=" card box-content-wg-new bordered-all primary">
                <h1 class="box-title bg-info" style="text-align: initial"><i class="fa fa-file-text-o"></i>
                    {{__('Create Sales Invoice')}}
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
                </h1>
                <div class="box-content">
                    <form id="sales_invoice_form" action="{{route('admin:sales.invoices.store')}}" class="form"
                          method="post">
                        @csrf
                        @method('post')

                        @if (authIsSuperAdmin())
                            <div class="form-group has-feedback col-md-12">
                                <label for="inputSymbolAR" class="control-label">{{__('Select Branch')}}</label>
                                <select name="branch_id" class="form-control select2 js-example-basic-single"
                                        id="branch_id"
                                        onchange="dataByBranch(); formUrl();
                                        document.getElementById('sales_invoice_form').submit();"
                                >
                                    <option value="">{{__('Select Branch')}}</option>
                                    @foreach($branches as $k=>$v)
                                        <option value="{{$k}}" @if(@request('branch_id')== $k)  selected @endif>
                                            {{$v}}
                                        </option>
                                    @endforeach
                                </select>
                                {{input_error($errors,'branch_id')}}
                            </div>
                        @endif

                        <div class="form-group  col-md-3">
                            <label for="invoice_number" class="control-label">{{__('Invoice Number')}}</label>
                            <div class="input-group">
                                <span class="input-group-addon"><li class="fa fa-file-text-o"></li></span>
                                <input type="text" name="invoice_number" class="form-control" id="invoice_number"
                                       placeholder="{{__('Invoice Number')}}" value="####" disabled>
                            </div>
                            {{input_error($errors,'invoice_number')}}
                        </div>

                        <div class="form-group col-md-3">
                            <div class="form-group">
                                <label for="type_en" class="control-label">{{__('Time')}}</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><li class="fa fa-clock-o"></li></span>
                                    <input type="time" name="time" class="form-control"
                                           value="{{now()->format('h:i')}}"
                                           id="time" placeholder="{{__('Time')}}"
                                    >
                                </div>
                                {{input_error($errors,'time')}}
                            </div>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="date" class="control-label">{{__('Date')}}</label>
                            <div class="input-group">
                                <span class="input-group-addon"><li class="fa fa-clock-o"></li></span>
                                <input type="date" name="date" class="form-control"
                                       value="{{now()->format('Y-m-d')}}" id="date"
                                       placeholder="{{__('Date')}}">
                            </div>
                            {{input_error($errors,'date')}}
                        </div>
                        <div class="form-group has-feedback col-md-3" id="customer_data">
                            <label for="inputSymbolAR" class="control-label">{{__('Select Customer')}}</label>

                            <a class="btn btn-danger   pull-left"
                               style="margin-top:-5px;cursor:pointer;font-size:11px;font-weight:bold;padding:2px 2px"
                               data-toggle="modal"
                               data-target="#addSupplierForm" title="{{__('Add Customer')}}">
                                <i class="fa fa-plus"> </i> {{__('New Customer')}}
                            </a>

                            <select name="customer_id" class="form-control js-example-basic-single" id="customer_value"
                                    onchange="getCustomersBalance()">

                                <option value="" data-points="0">{{__('Select Customer')}}</option>

                                @foreach($customers as $customer)
                                    <option value="{{$customer->id}}" data-points="{{$customer->points}}">
                                        {{$customer->name .'-'. $customer->phone1}}
                                    </option>
                                @endforeach

                            </select>

                            {{input_error($errors,'customer_id')}}
                        </div>

                        <div class=" form-group col-md-12" style="display: none;" id="customer_funds_inputs">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="funds_for"
                                               class="control-label">{{__('Funds For customer')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-money"></li></span>
                                            <input type="text" name="funds_for" class="form-control" id="funds_for"
                                                   placeholder="{{__('Funds For')}}" disabled="disabled">
                                        </div>
                                        {{input_error($errors,'funds_for')}}
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="funds_for" class="control-label">{{__('Funds On customer')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-money"></li></span>
                                            <input type="text" name="funds_on" class="form-control" id="funds_on"
                                                   placeholder="{{__('Funds On')}}" disabled="disabled">
                                        </div>
                                        {{input_error($errors,'funds_on')}}
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputQuantity"
                                               class="control-label">{{__('group Discount')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-money"></li></span>
                                            <input type="text" name="discount"
                                                   class="form-control customer_group_discount"
                                                   id="customer_group_discount"
                                                   placeholder="{{__('discount')}}" disabled="disabled">
                                        </div>
                                        {{input_error($errors,'discount')}}
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputQuantity" class="control-label">
                                            {{__('group Discount Type')}}
                                        </label>

                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-money"></li></span>
                                            <input type="text" id="show_customer_discount_type_value"
                                                   class="form-control " disabled="disabled">
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>


                        @include('admin.sales-invoices.parts.parts-card')

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                            <div class="anyClass1 table-responsive">
                                <table id="invoiceItemsDetails" class="table table-bordered" style="width:100%">
                                    @include('admin.sales-invoices.parts.table-footer')

                                    <thead style="background-color: #ada3a361">
                                    <tr>
                                        <th style="width:100px" scope="col">{!! __('Spare Part Name') !!}</th>
                                        <th style="width:100px" scope="col">{!! __('Invoices') !!}</th>
                                        <th style="width:100px" scope="col">{!! __('Available quantity') !!}</th>
                                        <th style="width:100px" scope="col">{!! __('Sold quantity') !!}</th>
                                        <th style="width:100px" scope="col">{!! __('last purchase price') !!}</th>
                                        <th style="width:100px" scope="col">{!! __('selling price') !!}</th>
                                        <th style="width:100px" scope="col">{!! __('Discount Type') !!}</th>
                                        <th style="width:100px" scope="col">{!! __('Discount') !!}</th>
                                        <th scope="col">{!! __('Total') !!}</th>
                                        <th scope="col">{!! __('Total After Discount') !!}</th>
                                        <th scope="col">{!! __('Action') !!}</th>
                                    </tr>
                                    </thead>
                                    <tbody id="add_parts_details">
                                    </tbody>


                                </table>
                                <input type="hidden" name="items_count" id="invoice_items_count" value="0">
                                <input type="hidden" name="parts_count" id="parts_count" value="0">
                            </div>
                        </div>


                        <div class="form-group has-feedback col-sm-12" style="margin-top:30px">
                            <table class="table table-bordered" style="width:100%">
                                <thead>
                                <tr>
                                    <th scope="col">{!! __('Invoice Type') !!}</th>
                                    <th scope="col">{!! __('Total') !!}</th>
                                    <th scope="col">{!! __('Parts Number') !!}</th>
                                    <th scope="col">{!! __('Discount Type') !!}</th>
                                    <th scope="col">{!! __('Discount') !!}</th>
                                    <th scope="col">{!! __('Customer Discount') !!}</th>
                                    <th scope="col">

                                        <a style="cursor:pointer" data-toggle="modal"
                                           data-target="#pointsDiscount" title="{{__('Points Rules')}}">
                                            <i class="fa fa-eye "
                                               style="background-color:#F44336;color:white;padding:3px;border-radius:5px"></i>
                                        </a>

                                        {!! __('Points Discount') !!}
                                    </th>
                                    <th scope="col">{!! __('Total After Discount') !!}</th>
                                    <th scope="col">
                                        <a style="cursor:pointer" data-remodal-target="m-2" title="get taxes">
                                            <i class="fa fa-eye "
                                               style="background-color:#F44336;color:white;padding:3px;border-radius:5px"></i>
                                        </a>
                                        {!! __('Taxes') !!}
                                    </th>
                                    <th scope="col">{!! __('Final Total') !!}</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>

                                    <td>
                                        <div class="radio primary">
                                            <input type="radio" name="type" id="type_cash" value="cash" checked>
                                            <label for="type_cash">{{__('Cash')}}</label>
                                        </div>

                                        <div class="radio primary">
                                            <input type="radio" name="type" id="type_credit" value="credit">
                                            <label for="type_credit">{{__('Credit')}}</label>
                                        </div>

                                    </td>

                                    <td>
                                        <input type="text" class="form-control" readonly name="total_before_discount"
                                               id="total_before_discount" value="0">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" readonly name="number_of_items"
                                               value="0" id="number_of_items">
                                    </td>
                                    <td>
                                        <div class="radio primary">
                                            <input type="radio" name="discount_type" id="discount_type_amount"
                                                   value="amount" checked onclick="implementInvoiceDiscount()">
                                            <label for="discount_type_amount">{{__('amount')}}</label>
                                        </div>
                                        <div class="radio primary">
                                            <input type="radio" name="discount_type"
                                                   id="discount_type_percent" value="percent"
                                                   onclick="implementInvoiceDiscount()">
                                            <label for="discount_type_percent">{{__('Percent')}}</label>
                                        </div>

                                    </td>
                                    <td>
                                        <input type="number" class="form-control" value="0" id="discount"
                                               onchange="implementInvoiceDiscount()"
                                               onkeyup="implementInvoiceDiscount()"
                                               name="discount" min="0">
                                    </td>

                                    {{--  CUSTOMER DISCOUNT --}}
                                    <td>
                                        <div class="row">
                                            <div class="col-md-2" style="margin-top: 10px;">
                                                <div class="form-group has-feedback">
                                                    <input type="checkbox" id="customer_discount_check"
                                                           name="customer_discount_check"
                                                           onclick="implementInvoiceDiscount()">
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <input type="text" class="form-control" style="width:42px;"
                                                       value="$" id="customer_discount_type"
                                                       name="customer_discount_type" min="0" disabled
                                                >
                                                <input type="hidden" value="amount" id="customer_discount_type_value">
                                            </div>

                                            <div class="col-md-7">
                                                <input type="number" class="form-control customer_group_discount"
                                                       value="0"
                                                       name="customer_group_discount" min="0" disabled="">
                                            </div>

                                        </div>
                                    </td>

                                    <td>

                                        <input type="hidden" name="points_rule_id" value="" class="rule_id">

                                        <input type="hidden" name="customer_points" id="customer_points" value="0">

                                        <input type="text" class="form-control" readonly value="0"
                                               name="points_discount" id="points_discount">
                                    </td>

                                    <td>
                                        <input type="text" class="form-control" readonly value="0"
                                               name="total_after_discount" id="total_after_discount">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" readonly name="invoice_tax"
                                               id="invoice_tax" value="0">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" readonly value="0"
                                               name="final_total" id="final_total">
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="form-group col-sm-12">
                            <button id="btnsave" type="submit" class="btn hvr-rectangle-in saveAdd-wg-btn  ">
                                <i class="ico ico-left fa fa-save"></i>
                                {{__('Save')}}
                            </button>

                            <button id="reset" type="button" class="btn hvr-rectangle-in resetAdd-wg-btn  ">
                                <i class="ico ico-left fa fa-trash"></i>
                                {{__('Reset')}}
                            </button>

                            <button id="back" type="button" class="btn hvr-rectangle-in closeAdd-wg-btn  ">
                                <i class="ico ico-left fa fa-close"></i>
                                {{__('Back')}}
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


    @include('admin.sales-invoices.taxes.index')

@endsection

@section('modals')
    @include('admin.sales-invoices.customers.form')

    <div class="modal fade" id="pointsDiscount" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class=" card box-content-wg-new bordered-all primary">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="box-title with-control" style="text-align: initial" id="myModalLabel-1">
                            {{__('Points Rules')}} <span class="controls hidden-sm hidden-xs pull-left"></span>
                            <span class="controls hidden-sm hidden-xs pull-left">
                                {{__('Customer Points')}} : <span id="real_customer_points">0</span>
                            </span>
                        </h4>
                    </div>

                    <form id="add_customer_form">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12" id="points_rules">
                                    <span id="select_customer"> {{__('Please Select Customer')}} </span>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-dismiss="modal" aria-label="Close">
                                {{__('Close')}}
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

@endsection

@section('js-validation')
    {!! JsValidator::formRequest('App\Http\Requests\Admin\salesInvoice\CreateSalesInvoiceRequest', '.form'); !!}
    @include('admin.partial.sweet_alert_messages')


    <script type="application/javascript">

        function formUrl() {
            $('#sales_invoice_form').attr('action', '{{url()->current()}}');
            $('#sales_invoice_form').attr('method', 'get');
        }

        function setServiceValues(id) {

            let sold_qty = $('#sold-qy-' + id);
            let selling_price = $('#selling-price-' + id);
            let total = $("#total-" + id);
            let discount_type = $("#discount-type-" + id).val();
            let discount = $("#discount-" + id).val();
            let total_after_discount = $('#total_after_discount');

            var item_total = (sold_qty.val() * selling_price.val()).toFixed(2);

            total.val(item_total);

            checkAmount(id, sold_qty);

            implementDiscount(discount, discount_type, total.val(), id);

            calculateInvoiceTotal();

            calculateTax();
        }

        function checkAmount(id) {

            var maximum_sale_amount = $("#maximum-sale-amount-" + id).val();
            var sold_qty = $('#sold-qy-' + id).val();

            if (parseInt(sold_qty) > parseInt(maximum_sale_amount)) {

                swal({
                    text: "{{__('sorry maximum sale amount is ')}}" + maximum_sale_amount,
                    icon: "warning",
                }).then(function (isConfirm) {
                    if (isConfirm) {
                        // $("#discount-"+id).val(0);
                    }
                });

                $('#sold-qy-' + id).val(0);
            }

        }

        function implementDiscount(discount, discount_type, total, id) {

            if (discount == "") {
                discount = 0;
            }

            if ($("#item-discount-type-amount-" + id).is(':checked')) {

                var big_amount_discount = $("#amount-discount-" + id).val();

                if (parseFloat(discount) > parseFloat(big_amount_discount)) {

                    swal({
                        text: "{{__('sorry big amount discount is ')}}" + big_amount_discount,
                        icon: "warning",
                    }).then(function (isConfirm) {
                        if (isConfirm) {
                            $("#discount-" + id).val(0);
                        }
                    });
                    discount = 0;
                }

                var price_after_discount = parseFloat(total) - parseFloat(discount);

            } else {

                var big_percent_discount = $("#percent-discount-" + id).val();

                if (parseFloat(discount) > parseFloat(big_percent_discount)) {

                    swal({
                        text: "{{__('sorry big percent discount is ')}}" + big_percent_discount,
                        icon: "warning",
                    }).then(function (isConfirm) {
                        if (isConfirm) {
                            $("#discount-" + id).val(0);
                        }
                    });
                    discount = 0;
                }

                var discount_percentage_value = parseFloat(total) * parseFloat(discount) / parseFloat(100);
                var price_after_discount = parseFloat(total) - parseFloat(discount_percentage_value);
            }

            if (price_after_discount < 0)
                price_after_discount = 0;

            $("#total-after-discount-" + id).val(price_after_discount.toFixed(2));
        }

        function resetItem(id) {
            $('#purchased-qy-' + id).val(0);
            $('#discount-' + id).val(0);
            $('#total-' + id).val(0);
            $('#total-after-discount-' + id).val(0);
        }

        function calculateInvoiceTotal() {

            var items_count = $("#invoice_items_count").val();

            var total = 0;

            for (var i = 1; i <= items_count; i++) {

                var part_id = $("#part-" + i).val();

                var tottal_after_discount = $("#total-after-discount-" + part_id).val();

                if (part_id && tottal_after_discount) {

                    total += parseFloat($("#total-after-discount-" + part_id).val());

                } else {
                    $("#part-" + i).remove();
                }
            }

            $("#total_before_discount").val(total.toFixed(2));

            implementInvoiceDiscount();
        }

        function partsItemsCount() {
            var parts_count = $("#parts_count").val();
            $("#number_of_items").val(parts_count);
        }

        function implementInvoiceDiscount() {

            var discount = $("#discount").val();
            var discount_type = $("#invoice_discount_type").val();
            var total = $("#total_before_discount").val();
            var parts_items_count = $("#invoice_items_count").val();

            var customer_discount = 0;
            var customer_value = $("#customer_value").val();

            if ($('#customer_discount_check').is(':checked')) {

                customer_discount = customerDiscount();

            } else {
                // $("#customer_discount_value").val(0);
                $("#customer_discount_check").prop('checked', false);
            }

            if (parts_items_count == 0) {

                swal({
                    text: "{{__('you can not add discount now!')}}",
                    icon: "warning",
                }).then(function (isConfirm) {
                    if (isConfirm) {
                        $("#discount").val(0);
                        $("#total_after_discount").val(total);
                        $("#customer_discount_check").prop('checked', false);
                        // $("#customer_discount_value").val(0);
                    }
                });
            }

            if (discount == "") {
                discount = 0;
            }

            if ($("#discount_type_amount").is(':checked')) {

                var price_after_discount = parseFloat(total) - parseFloat(discount);

            } else {

                var discount_percentage_value = parseFloat(total) * parseFloat(discount) / parseFloat(100);
                var price_after_discount = parseFloat(total) - parseFloat(discount_percentage_value);
            }

            price_after_discount -= parseFloat(customer_discount);

            var points_discount = $("#points_discount").val();

            price_after_discount -= parseFloat(points_discount);

            if (price_after_discount <= 0)
                price_after_discount = 0;

            $("#total_after_discount").val(price_after_discount.toFixed(2));

            calculateTax();
        }

        function customerDiscount() {

            var discount = $("#customer_group_discount").val();
            var customer_discount_type = $("#customer_discount_type_value").val();
            var total = $("#total_before_discount").val();

            if (discount == "") {
                discount = 0;
            }

            if (customer_discount_type == 'amount') {

                return discount;

            } else {

                var discount = parseFloat(total) * parseFloat(discount) / parseFloat(100);
                return discount;
            }
        }

        function getSparePartsById(id) {
            event.preventDefault();

            var branch_id = $("#branch_id").val();

            $.ajax({
                url: "{{ route('admin:purchase-invoices.parts') }}?spare_part_id=" + id + "&branch_id=" + branch_id,
                method: 'GET',
                success: function (data) {
                    $('#add_parts').html(data.parts);
                }
            });
        }

        function getPartsDetails(id = null) {
            event.preventDefault();
            let isExist = true;

            var barcode = $("#partOFBarcodes").val();

            {{--$('#add_parts_details tr').each(function () {--}}
                {{--    if ($(this).data('id') == id || $(this).data('barcode') == barcode) {--}}
                {{--        isExist = false;--}}
                {{--        swal({--}}
                {{--            text: "{{__('You have chosen this Service before, you cannot choose it again!')}}",--}}
                {{--            icon: "warning",--}}
                {{--        })--}}
                {{--    }--}}
                {{--});--}}
            if (isExist) {

                var items_count = $("#invoice_items_count").val();
                var parts_count = $("#parts_count").val();

                $("#invoice_items_count").remove();
                $("#parts_count").remove();


                $.ajax({
                    url: "{{ route('admin:sales.invoices.parts.details') }}",
                    method: 'POST',
                    data: {items_count: items_count, parts_count: parts_count, id: id, barcode: barcode},
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        $('#add_parts_details').append(data);
                        partsItemsCount();
                        $('.js-example-basic-single').select2();
                        $("#partOFBarcodes").val('');
                        $(".first_qty").focus()

                    }
                });
            }
        }

        function removeServiceFromTable(id) {
            swal({
                text: "{{__('Are you sure want to remove this service ?')}}",
                icon: "warning",
                buttons: {
                    confirm: {
                        text: "{{__('Ok')}}",
                    },
                    cancel: {
                        text: "{{__('Cancel')}}",
                        visible: true,
                    }
                }
            }).then(function (isConfirm) {
                if (isConfirm) {

                    var parts_count = $("#parts_count").val();
                    $('#parts_count').val(parseInt(parts_count) - parseInt(1));
                    $('#' + id).remove();
                    partsItemsCount();
                    calculateInvoiceTotal();
                    calculateTax();
                }
            });
        }

        function searchInPartsType() {

            let value = $("#searchInSparePartsType").val().toLowerCase();

            $(".searchResultSparePartsType td").filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        }

        function searchInPartsData() {

            let value = $("#searchInParts").val().toLowerCase();

            $("#add_parts td").filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        }

        function dataByBranch() {

            var options = {"closeButton": true, "positionClass": "toast-bottom-right", "progressBar": true,};

            event.preventDefault();

            var branch_id = $("#branch_id").val();

            $.ajax({

                url: "{{ route('admin:sales.invoices.data.by.branches')}}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {branch_id: branch_id},
                success: function (data) {
                    $("#data_by_branch").html(data);
                },
                error: function (jqXhr, json, errorThrown) {
                    // $('.ajax-load-result').hide();
                    var errors = jqXhr.responseJSON;
                    toastr.info("{{__('you not select customer')}}", '', options);
                },
            });
        }

        {{-- add supplier js  --}}

        $("#country").change(function () {
            $.ajax({
                url: "{{ route('admin:country.cities') }}?country_id=" + $(this).val(),
                method: 'GET',
                success: function (data) {
                    $('#city').html(data.cities);
                    $('#currency').html(data.currency);
                }
            });
        });

        $("#city").change(function () {
            $.ajax({
                url: "{{ route('admin:city.areas') }}?city_id=" + $(this).val(),
                method: 'GET',
                success: function (data) {
                    $('#area').html(data.html);
                }
            });
        });

        function getCompanyData() {

            var type = $("#customer_type").val();

            if (type == 'person') {

                $(".company_data").hide();
            } else {
                $(".company_data").show();
            }
        }

        function addCustomer() {

            event.preventDefault();

            let branch_id = $("#branch_id").val();

            $.ajax({
                url: "{{ route('admin:sales.invoices.add.customer')}}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: $("#add_customer_form").serialize() + "&branch_id=" + branch_id,
                success: function (data) {
                    $('#customer_data').html(data.customerData);
                    // $('#customer_data').html(data);
                    toastr.success('Customer saved successfully');
                    $('.js-example-basic-single').select2();
                    $("#addSupplierForm").modal('hide');
                },
                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    toastr.error(errors);
                },
            });

            var customer_id = $("#customer_value").val();

            customerPointsRules(customer_id);
        }

        function getCustomersBalance() {

            var options = {"closeButton": true, "positionClass": "toast-bottom-right", "progressBar": true,};

            event.preventDefault();

            let customer_points = $("#customer_value").find(':selected').data("points");

            $("#customer_points").val(customer_points);

            var customer_id = $("#customer_value").val();

            if (customer_id == '') {
                $("#customer_funds_inputs").hide();
            }

            $.ajax({
                url: "{{ route('admin:sales.invoice.customer.balance')}}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {customer_id: customer_id},
                success: function (data) {
                    $('#funds_for').val(data['funds_for']);
                    $('#funds_on').val(data['funds_on']);
                    $('#customer_discount_type_value').val(data['customer_discount_type']);

                    @if(\Illuminate\Support\Facades\App::getLocale() == 'ar')

                    if (data['customer_discount_type'] == 'percent')
                        $('#show_customer_discount_type_value').val('نسبة');
                    else
                        $('#show_customer_discount_type_value').val('قيمة');

                    @else

                    if (data['customer_discount_type'] == 'percent')
                        $('#show_customer_discount_type_value').val('percent');
                    else
                        $('#show_customer_discount_type_value').val('amount');

                    @endif

                    $('.customer_group_discount').val(data['customer_group_discount']);

                    $('#customer_discount_type').val('$');

                    if (data['customer_discount_type'] == 'percent') {
                        $('#customer_discount_type').val('%');
                    }

                    $("#customer_funds_inputs").show();
                },
                error: function (jqXhr, json, errorThrown) {

                    $('#customer_discount_type').val('$');
                    $('.customer_group_discount').val(0);
                    $("#customer_discount_check").prop('checked', false);

                    // $('.ajax-load-result').hide();
                    var errors = jqXhr.responseJSON;
                    toastr.info("{{__('you not select customer')}}", '', options);
                },
            });

            customerPointsRules(customer_id);
        }

        function purchaseInvoiceData(part_id, index) {

            var invoice_id = $("#purchase-invoice-" + index).val();

            $.ajax({
                url: "{{ route('admin:sales.invoices.get.purchase.invoice.data')}}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {invoice_id: invoice_id, part_id: part_id, index: index},
                success: function (data) {
                    $(".tr_" + index).html(data);
                    // setServiceValues(index);
                    $('.js-example-basic-single').select2();
                },
                error: function (jqXhr, json, errorThrown) {
                    // $('.ajax-load-result').hide();
                    var errors = jqXhr.responseJSON;
                    toastr.info("{{__('sorry select value')}}", '', options);
                },
            });

        }

        function calculateTax() {

            var total_after_discount = $("#total_after_discount").val();
            var tax_count = $("#tax_count").val();
            var total_tax = 0;
            var total_before_discount = $("#total_before_discount").val();

            if (total_before_discount == 0) {
                $("#invoice_tax").val(0);
                $("#total_afetr_discount").val(0);
                $("#final_total").val(0);
                return false;
            }

            for (var i = 1; i <= tax_count; i++) {

                var type = $("#tax_type_" + i).val();
                var value = $("#tax_value_" + i).val();

                if (type == 'amount') {

                    total_tax += parseFloat(value);
                    $("#tax_value_after_discount_" + i).val(value);
                } else {

                    var tax_value = parseFloat(total_after_discount) * parseFloat(value) / 100;
                    total_tax += parseFloat(tax_value);

                    $("#tax_value_after_discount_" + i).val(tax_value);
                }
            }

            var total = parseFloat(total_after_discount) + parseFloat(total_tax);

            if (total <= 0)
                total = 0;

            if (total_tax <= 0)
                total_tax = 0;

            $("#final_total").val(total.toFixed(2));

            $("#invoice_tax").val(total_tax.toFixed(2));
        }

        function store() {

            $.ajax({
                url: "{{ route('admin:sales.invoices.store')}}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: $("#sales_invoice_form").serialize(),
                success: function (data) {

                    swal({
                        text: "{{__('invoice created successfully!')}}",
                        icon: "success",
                    });

                    setTimeout(function () {
                        window.location.replace(data);
                    }, 1000);


                },
                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    toastr.error(errors, '', options);
                },
            });
        }

        function customerPointsRules (customer_id) {

            $.ajax({
                url: "{{ route('admin:customer.points.rules')}}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {customer_id:customer_id},

                success: function (data) {
                    $("#points_rules_div").remove();
                    $("#points_rules").append(data.view);
                    $("#real_customer_points").text(data.customer_points);
                    $('.js-example-basic-single').select2();
                    $("#select_customer").hide();
                },
                error: function (jqXhr, json, errorThrown) {
                    $("#points_rules_div").remove();
                    $("#select_customer").show();
                    var errors = jqXhr.responseJSON;
                    toastr.error(errors, '', options);
                },
            });
        }

        function selectPointsRule () {

            let rule_amount = $("#point_rule_id").find(':selected').data("amount");

            $("#points_discount").val(rule_amount);

            let rule_id = $("#point_rule_id").find(':selected').val();

            $(".rule_id").val(rule_id);

            implementInvoiceDiscount();
        }
    </script>
@endsection
