@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('Edit Sales Invoices Returns') }} </title>
@endsection


@section('style')
    <style>
        /* Chrome, Safari, Edge, Opera  #remove counter in input number*/
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
                        href="{{route('admin:sales.invoices.return.index')}}"> {{__('Sales Invoices Returns')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Edit Sales Invoices Returns')}}</li>
            </ol>
        </nav>

        <div class="col-xs-12">
            <div class="card box-content-wg-new bordered-all primary">
                <h1 class="box-title bg-info" style="text-align: initial"><i class="fa fa-file-text-o"></i>
                    {{__('Edit Sales Invoices Returns')}}
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
                    <form id="sales_invoice_return_form"
                          action="{{route('admin:sales.invoices.return.update', $invoice->id)}}"
                          class="form" method="post">
                        @csrf
                        @method('post')

                        @if (authIsSuperAdmin())
                            <div class="form-group has-feedback col-md-12">
                                <label for="inputSymbolAR" class="control-label">{{__('Select Branch')}}</label>
                                <select name="branch_id" class="form-control select2 js-example-basic-single"
                                        id="branch_id" disabled>
                                    <option value="">{{__('Select Branch')}}</option>
                                    @foreach($branches as $k=>$v)
                                        <option value="{{$k}}"
                                            {{isset($invoice) && $invoice->branch_id == $k ? 'selected':''}}
                                        >
                                            {{$v}}
                                        </option>
                                    @endforeach
                                </select>
                                {{input_error($errors,'branch_id')}}
                            </div>
                        @endif

                        <div class="form-group  col-md-4">
                            <label for="invoice_number" class="control-label">{{__('Invoice Number')}}</label>
                            <div class="input-group">
                                <span class="input-group-addon"><li class="fa fa-file-text-o"></li></span>
                                <input type="text" name="invoice_number" class="form-control" id="invoice_number"
                                       placeholder="{{__('Invoice Number')}}"
                                       value="{{$invoice->inv_number}}"
                                       disabled
                                >
                            </div>
                            {{input_error($errors,'invoice_number')}}
                        </div>

                        <div class="form-group col-md-4">

                            <label for="type_en" class="control-label">{{__('Time')}}</label>
                            <div class="input-group">
                                <span class="input-group-addon"><li class="fa fa-clock-o"></li></span>
                                <input type="time" name="time" class="form-control"
                                       value="{{$invoice->time}}"
                                       id="time" placeholder="{{__('Time')}}"
                                >
                            </div>
                            {{input_error($errors,'time')}}
                        </div>


                        <div class="form-group col-md-4">
                            <label for="date" class="control-label">{{__('Date')}}</label>
                            <div class="input-group">
                                <span class="input-group-addon"><li class="fa fa-clock-o"></li></span>
                                <input type="date" name="date" class="form-control"
                                       value="{{$invoice->date}}" id="date"
                                       placeholder="{{__('Date')}}">
                            </div>
                            {{input_error($errors,'date')}}
                        </div>

                        <div class="col-md-3">
                            <div class="form-group has-feedback">
                                <label for="inputSymbolAR" class="control-label">{{__('Select Sales Invoice')}}</label>
                                <select name="sales_invoice_id" class="form-control  js-example-basic-single"
                                        id="sales_invoice_id" onchange="selectSalesInvoice()">
                                    <option value="">{{__('Select Sales Invoice')}}</option>
                                    @foreach($salesInvoices as $salesInvoice)
                                        <option value="{{$salesInvoice->id}}"
                                            {{isset($invoice) && $invoice->sales_invoice_id == $salesInvoice->id ? 'selected':''}}>
                                            {{$salesInvoice->inv_number}}
                                        </option>
                                    @endforeach
                                </select>
                                {{input_error($errors,'sales_invoice_id')}}
                            </div>
                        </div>

                        <div id="sales_invoice_data">
                            <div class="form-group has-feedback col-md-3">
                                <label for="inputSymbolAR" class="control-label">{{__('Customer Name')}}</label>
                                <input type="text" name="customer_id" readonly="readonly" class="form-control"
                                       value="{{optional($invoice->customer)->name}}"
                                >
                                {{input_error($errors,'customer_id')}}
                            </div>

                            <div class="form-group has-feedback col-md-3">
                                <label for="inputSymbolAR"
                                       class="control-label">{{__('Customer Discount')}}</label>
                                <input type="text" id="customer_group_discount" readonly="readonly" class="form-control"
                                       value="{{$invoice->customer_discount}}">
                                {{input_error($errors,'customer_discount')}}
                            </div>

                            <div class="form-group has-feedback col-md-3">
                                <label for="inputSymbolAR" class="control-label">{{__('Discount Type')}}</label>
                                <input type="text" readonly="readonly" class="form-control"
                                       value="{{__($invoice->customer_discount_type)}}">
                                {{input_error($errors,'customer_discount_type')}}
                            </div>

                            {{--    Invoice Items      --}}
                            <div class="form-group has-feedback col-sm-12">
                                <div class="box-content card bordered-all js__card box-content-wg ">
                                    <h4 class="box-title bg-blue-1 box-title-wg with-control">
                                        {{__('Invoice Items Details')}}
                                        <span class="controls">
                                        <button type="button" class="control fa fa-minus js__card_minus"></button>
                                        <button type="button" class="control fa fa-times js__card_remove"></button>
                                    </span>
                                    </h4>
                                    <div class="card-content js__card_content" style="">
                                    <!-- <h3 style="text-align: center;">{{__('Invoice Items Details')}}</h3> -->
                                        <div class="">
                                            <table id="invoiceItemsDetails" class="table table-bordered"
                                                   style="width:100%">
                                                <thead style="background-color: #ada3a361">
                                                <tr>
                                                    <th scope="col">{!! __('Spare Part Name') !!}</th>
                                                    <th scope="col">{!! __('Invoices') !!}</th>
                                                    <th scope="col">{!! __('Available quantity') !!}</th>
                                                    <th scope="col">{!! __('Return quantity') !!}</th>
                                                    {{--                                        <th scope="col">{!! __('last purchase price') !!}</th>--}}
                                                    <th scope="col">{!! __('selling price') !!}</th>
                                                    <th scope="col">{!! __('Discount Type') !!}</th>
                                                    <th scope="col">{!! __('Discount') !!}</th>
                                                    <th scope="col">{!! __('Total') !!}</th>
                                                    <th scope="col">{!! __('Total After Discount') !!}</th>
                                                    <th scope="col">
                                                        <input type="checkbox" id="selectAllItems" checked
                                                               onclick="select_all()">
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody id="add_parts_details">

                                                @foreach($invoice->items as $index=>$returnItem)

                                                    @php
                                                        $part = $returnItem->part;
                                                        $sales_invoice_item = null;
                                                        $index +=1;
                                                        if($returnItem->salesInvoiceReturn->salesInvoice){
                                                         $sales_invoice_item = $returnItem->salesInvoiceReturn->salesInvoice->items()
                                                               ->where('part_id',$part->id)->first();
                                                        }

                                                    @endphp

                                                    <tr data-id="{{$index}}" id="{{$index}}" class="tr_{{$index}}">

                                                        <input type="hidden" name="part_id_{{$index}}"
                                                               value='{{$index}}'>

                                                        <input type="hidden" name="sales_invoice_items_id_{{$index}}"
                                                               value='{{$returnItem->sales_invoice_item_id}}'>

                                                        <input type="hidden" id="part-{{$index}}" value='{{$index}}'>

                                                        <td>{{$part->name}}</td>

                                                        <td>
                                                            <input type="text" name="purchase_invoice_id_{{$index}}"
                                                                   readonly
                                                                   value="{{$returnItem->purchaseInvoice->invoice_number}}"
                                                                   class="form-control">
                                                        </td>

                                                        <td>
                                                            <input type="number" class="form-control" readonly
                                                                   value="{{$returnItem->available_qty}}"
                                                                   name="available_qy_{{$index}}"
                                                                   id="available-qy-{{$index}}">
                                                        </td>

                                                        <td>
                                                            <select name="return_qty_{{$index}}"
                                                                    class="js-example-basic-single" style="width: 100%"
                                                                    id="sold-qy-{{$index}}"
                                                                    onchange="setServiceValues({{$index}})"
                                                            >
                                                                <option value="">{{__('select')}}</option>

                                                                @if($sales_invoice_item)
                                                                    @for($i=1; $i<= $sales_invoice_item->sold_qty; $i++)
                                                                        <option
                                                                            value="{{$i}}" {{$i == $returnItem->return_qty ? "selected":""}}>
                                                                            {{$i}}
                                                                        </option>
                                                                    @endfor
                                                                @endif
                                                            </select>
                                                        </td>


                                                        <input type="hidden" class="form-control"
                                                               value="{{$returnItem->last_selling_price}}"
                                                               name="last_selling_price_{{$index}}"
                                                               id="last_purchase_price-{{$index}}"
                                                        >

                                                        <td>
                                                            <input type="number" class="form-control"
                                                                   value="{{$returnItem->selling_price}}"
                                                                   name="selling_price_{{$index}}"
                                                                   id="selling-price-{{$index}}"
                                                                   onkeyup="setServiceValues({{$index}})"
                                                                   onchange="setServiceValues({{$index}})"
                                                            >
                                                        </td>

                                                        <td>

                                                            <div class="radio primary">
                                                                <input type="radio"
                                                                       name="item_discount_type_{{$index}}"
                                                                       id="item-discount-type-amount-{{$index}}"
                                                                       value="amount"
                                                                       {{$returnItem->discount_type == "amount"?"checked":""}}
                                                                       onclick="setServiceValues({{$index}})">
                                                                <label
                                                                    for="item-discount-type-amount-{{$index}}">{{__('Amount')}}</label>
                                                            </div>

                                                            <div class="radio primary">
                                                                <input type="radio"
                                                                       name="item_discount_type_{{$index}}"
                                                                       id="item-discount-type-percent-{{$index}}"
                                                                       value="percent"
                                                                       {{$returnItem->discount_type == "percent"?"checked":""}}
                                                                       onclick="setServiceValues({{$index}})">
                                                                <label
                                                                    for="item-discount-type-percent-{{$index}}">{{__('Percent')}}</label>
                                                            </div>

                                                        </td>

                                                        <td>
                                                            <input type="number" class="form-control"
                                                                   value="{{$returnItem->discount}}"
                                                                   name="item_discount_{{$index}}"
                                                                   onkeyup="setServiceValues({{$index}})"
                                                                   onchange="setServiceValues({{$index}})"
                                                                   id="discount-{{$index}}">
                                                        </td>

                                                        <td>
                                                            <input type="number" class="form-control"
                                                                   readonly value="{{$returnItem->sub_total}}"
                                                                   name="item_total_before_discount_{{$index}}"
                                                                   id="total-{{$index}}">
                                                        </td>

                                                        <td>
                                                            <input type="number" class="form-control"
                                                                   readonly
                                                                   value="{{$returnItem->total_after_discount}}"
                                                                   name="item_total_after_discount_{{$index}}"
                                                                   id="total-after-discount-{{$index}}">
                                                        </td>

                                                        <td>
                                                            <input type="checkbox" id="return_part_id_{{$index}}"
                                                                   class="sales_invoice_checkbox"
                                                                   name="return_part_ids[{{$index}}]"
                                                                   onclick="setServiceValues({{$index}}); disabledUnselected({{$index}})"
                                                                   checked value="{{$part->id}}">
                                                        </td>
                                                    </tr>

                                                @endforeach
                                                </tbody>
                                                <tfoot style="background-color: #ada3a361">
                                                <tr>
                                                    <th scope="col">{!! __('Spare Part Name') !!}</th>
                                                    <th scope="col">{!! __('Invoices') !!}</th>
                                                    <th scope="col">{!! __('Available quantity') !!}</th>
                                                    <th scope="col">{!! __('Return quantity') !!}</th>
                                                    {{--                                        <th scope="col">{!! __('last purchase price') !!}</th>--}}
                                                    <th scope="col">{!! __('selling price') !!}</th>
                                                    <th scope="col">{!! __('Discount Type') !!}</th>
                                                    <th scope="col">{!! __('Discount') !!}</th>
                                                    <th scope="col">{!! __('Total') !!}</th>
                                                    <th scope="col">{!! __('Total After Discount') !!}</th>
                                                    <th scope="col">
                                                        <input type="checkbox" id="selectAllItems" checked
                                                               onclick="select_all()">
                                                    </th>
                                                </tr>
                                                </tfoot>
                                            </table>
                                            <input type="hidden" name="items_count" id="invoice_items_count"
                                                   value="{{$index}}">
                                            <input type="hidden" name="parts_count" id="parts_count"
                                                   value="{{$index}}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            {{--    Invoice total      --}}
                            <div class="form-group has-feedback col-sm-12">

                                <table class="table table-bordered" style="width:100%">
                                    <thead>
                                    <tr>

                                        <th scope="col">{!! __('Invoice Type') !!}</th>
                                        <th scope="col">{!! __('Total') !!}</th>
                                        <th scope="col">{!! __('Parts Number') !!}</th>
                                        <th scope="col">{!! __('Discount Type') !!}</th>
                                        <th scope="col">{!! __('Discount') !!}</th>
                                        <th scope="col">{!! __('Customer Discount') !!}</th>
                                        <th scope="col">{!! __('Points Discount') !!}</th>
                                        <th scope="col">{!! __('Total After Discount') !!}</th>
                                        <th scope="col">

                                            <a style="cursor:pointer" data-remodal-target="m-2"
                                               onclick="implementInvoiceDiscount()" title="get taxes">
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
                                            @if ($invoice->type === 'cash')
                                                <div class="radio info">
                                                    <input type="radio" name="type" value="cash" checked id="type-cash">
                                                    <label for="type-cash">{{__('Cash')}}</label>
                                                </div>
                                            @endif
                                            @if ($invoice->type === 'credit')
                                                <div class="radio pink">
                                                    <input type="radio" name="type" value="credit" id="type-credit"
                                                           checked>
                                                    <label for="type-credit">{{__('Credit')}}</label>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" readonly
                                                   name="total_before_discount"
                                                   id="total_before_discount" value="{{$invoice->sub_total}}">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" readonly name="number_of_items"
                                                   value="{{$invoice->number_of_items}}" id="number_of_items">
                                        </td>
                                        <td>
                                            <div class="radio primary">
                                                <input type="radio" name="discount_type" id="discount_type_amount"
                                                       value="amount" onclick="implementInvoiceDiscount()"
                                                    {{$invoice->discount_type == 'amount'? 'checked':''}}
                                                >
                                                <label for="discount_type_amount">{{__('Amount')}}</label>
                                            </div>
                                            <div class="radio primary">
                                                <input type="radio" name="discount_type"
                                                       id="discount_type_percent" value="percent"
                                                       onclick="implementInvoiceDiscount()"
                                                    {{$invoice->discount_type == 'percent'? 'checked':''}}
                                                >
                                                <label for="discount_type_percent">{{__('Percent')}}</label>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control"
                                                   value="{{$invoice->discount}}" id="discount"
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
                                                               {{$invoice->customer_discount_status ? 'checked':''}}
                                                               onclick="implementInvoiceDiscount()">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <input type="text" class="form-control" style="width:42px;"
                                                           value="{{$invoice->customer_discount_type == 'amount'? '$':'%' }}"
                                                           id="customer_discount_type"
                                                           name="customer_discount_type" min="0" disabled
                                                    >
                                                    <input type="hidden" value="{{$invoice->customer_discount_type}}"
                                                           id="customer_discount_type_value">
                                                </div>

                                                <div class="col-md-7">
                                                    <input type="number" class="form-control customer_group_discount"
                                                           value="{{$invoice->customer_discount}}"
                                                           id="customer_group_discount"
                                                           name="customer_group_discount" min="0" disabled="">
                                                </div>

                                            </div>
                                        </td>

                                        <td>

                                            <input type="hidden" name="points_rule_id" value="{{$invoice->points_rule_id}}" class="rule_id">

                                            <input type="hidden" name="customer_points" id="customer_points" value="0">

                                            <input type="text" class="form-control" readonly value="{{$invoice->points_discount}}"
                                                   name="points_discount" id="points_discount">
                                        </td>

                                        <td><input type="text" class="form-control" readonly
                                                   value="{{$invoice->total_after_discount}}"
                                                   name="total_after_discount" id="total_after_discount">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" readonly name="invoice_tax"
                                                   id="invoice_tax" value="{{$invoice->tax}}">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" readonly value="{{$invoice->total}}"
                                                   name="final_total" id="final_total">
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>

                        <div class="form-group col-sm-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="ico ico-left fa fa-save"></i>
                                {{__('Save')}}
                            </button>
                            <button id="reset" type="button" class="btn btn-info"><i
                                    class="ico ico-left fa fa-trash"></i>{{__('Reset')}}
                            </button>
                            <button id="back" type="button" class="btn btn-danger"><i
                                    class="ico ico-left fa fa-close"></i>{{__('Back')}}
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

    {{--    @include('admin.sales-invoices.customers.form')--}}
    @include('admin.sales-invoices.taxes.index')

@endsection
@section('js-validation')
    {!! JsValidator::formRequest('App\Http\Requests\Admin\SalesInvoicesReturn\UpdateSalesInvoiceReturnRequest', '.form'); !!}
    @include('admin.partial.sweet_alert_messages')
    <script type="application/javascript">

        function selectSalesInvoice() {

            var sales_invoice_id = $("#sales_invoice_id").val();

            $.ajax({
                url: "{{ route('admin:sales.invoices.data')}}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {sales_invoice_id: sales_invoice_id},
                success: function (data) {

                    $("#sales_invoice_data").html(data);
                    $('.js-example-basic-single').select2();
                },
                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    toastr.error(errors, '', options);
                },
            });
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

            implementDiscount(discount, discount_type, total.val(), id);

            calculateInvoiceTotal();

            calculateTax();
        }

        function implementDiscount(discount, discount_type, total, id) {

            if (discount == "") {
                discount = 0;
            }

            if ($("#item-discount-type-amount-" + id).is(':checked')) {

                var price_after_discount = parseFloat(total) - parseFloat(discount);

            } else {

                var discount_percentage_value = parseFloat(total) * parseFloat(discount) / parseFloat(100);

                var price_after_discount = parseFloat(total) - parseFloat(discount_percentage_value);
            }

            if (price_after_discount <= 0)
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

            var part_items = 0;

            for (var i = 1; i <= items_count; i++) {

                var part_id = $("#part-" + i).val();

                var tottal_after_discount = $("#total-after-discount-" + part_id).val();

                var checked_item = $('#return_part_id_' + part_id).is(":checked");

                if (part_id && tottal_after_discount && checked_item) {

                    total += parseFloat($("#total-after-discount-" + part_id).val());
                    part_items += 1;
                }
            }

            if (total <= 0)
                total = 0;

            $("#number_of_items").val(part_items);
            $("#total_before_discount").val(total.toFixed(2));

            implementInvoiceDiscount();
        }

        function partsItemsCount() {
            var parts_count = $("#parts_count").val();
            $("#number_of_items").val(parts_count);
        }

        function implementInvoiceDiscount() {

            var discount = $("#discount").val();

            if (!discount) {
                discount = 0;
            }

            var discount_type = $("#invoice_discount_type").val();
            var total = $("#total_before_discount").val();
            var parts_items_count = $("#invoice_items_count").val();

            let customer_discount = 0;

            if ($('#customer_discount_check').is(':checked')) {

                customer_discount = customerDiscount();

            } else {

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
                    toastr.info("{{__('you not select supplier')}}", '', options);
                },
            });
        }

        function purchaseInvoiceData(part_id) {

            var invoice_id = $("#purchase-invoice-" + part_id).val();

            $.ajax({
                url: "{{ route('admin:sales.invoices.get.purchase.invoice.data')}}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {invoice_id: invoice_id, part_id: part_id},
                success: function (data) {
                    $(".tr_" + part_id).html(data);
                    setServiceValues();
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

            $("#final_total").val(total.toFixed(2));

            $("#invoice_tax").val(total_tax.toFixed(2));
        }

        function select_all() {

            var select_all = $('#selectAllItems').is(":checked");

            if (select_all) {
                $('.sales_invoice_checkbox').prop('checked', true);
            } else {
                $('.sales_invoice_checkbox').prop('checked', false);
            }

            calculateInvoiceTotal();
        }

        function disabledUnselected(part_id) {

            var checkbob = $('#return_part_id_' + part_id).is(":checked");

            if (!checkbob) {
                $('#sold-qy-' + part_id).prop('disabled', true);
                $('#selling-price-' + part_id).prop('disabled', true);
                $('#discount-type-' + part_id).prop('disabled', true);
                $('#discount-' + part_id).prop('disabled', true);
            } else {
                $('#sold-qy-' + part_id).prop('disabled', false);
                $('#selling-price-' + part_id).prop('disabled', false);
                $('#discount-type-' + part_id).prop('disabled', false);
                $('#discount-' + part_id).prop('disabled', false);
            }
        }

        function update() {

            $.ajax({
                url: "{{ route('admin:sales.invoices.return.update', $invoice->id)}}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },

                data: $("#sales_invoice_return_form").serialize(),

                success: function (data) {

                    swal({
                        text: "{{__('invoice updated successfully!')}}",
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
    </script>
@endsection
