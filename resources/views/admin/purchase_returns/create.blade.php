@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('Create Return Purchase Invoice') }} </title>
@endsection

@section('style')

@endsection
@section('content')
    <div class="row small-spacing">
        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a
                            href="{{route('admin:purchase_returns.index')}}"> {{__('Purchase Invoices Returns')}}</a>
                </li>
                <li class="breadcrumb-item active"> {{__('Create Return Purchase Invoice')}}</li>
            </ol>
        </nav>

        <div class="col-xs-12">
            <div class=" card box-content-wg-new bordered-all primary">
                <h1 class="box-title bg-info" style="text-align: initial"><i
                            class="fa fa-file-text-o"></i>{{__('Purchase Invoices Returns')}}

                    <span class="controls hidden-sm hidden-xs pull-left">
                    <button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Save')}}<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f1.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Reset')}}<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f2.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px"> {{__('Back')}} <img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f3.png')}}"></button>

						</span>
                </h1>
                <div class="box-content">
                    <form method="post" action="{{route('admin:purchase_returns.store')}}" class="form">
                        @csrf
                        @method('post')

                        @if (authIsSuperAdmin())
                            <div class="form-group has-feedback col-md-12">
                                <label for="inputSymbolAR" class="control-label">{{__('Select Branch')}}</label>
                                <select name="branch_id" class="form-control select2 js-example-basic-single"
                                        onchange="getInvoicesByBranch(event)">
                                    <option value="">{{__('Select Branch')}}</option>
                                    @foreach(\App\Models\Branch::all() as $branch)
                                        <option {{ isset($_GET['branch_id']) && $_GET['branch_id'] == $branch->id ? 'selected' : '' }}
                                                value="{{$branch->id}}">{{$branch->name}}</option>
                                    @endforeach
                                </select>
                                {{input_error($errors,'branch_id')}}
                            </div>
                        @endif

                        <div class="col-md-12">
                            <div class="form-group  col-md-4">
                                <label for="invoice_number"
                                       class="control-label">{{__('Return Invoice Number')}}</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><li class="fa fa-file-text-o"></li></span>
                                    <input type="text" name="invoice_number" value="" class="form-control"
                                           id="invoice_number"
                                           placeholder="{{__('Invoice Number')}}">
                                </div>
                                {{input_error($errors,'invoice_number')}}
                            </div>

                            <div class="form-group col-md-4">
                                <div class="form-group">
                                    <label for="type_en" class="control-label">{{__('Time')}}</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><li class="fa fa-clock-o"></li></span>
                                        <input type="time" name="time" class="form-control"
                                               value="{{now()->format('h:i')}}" id="time" placeholder="{{__('Time')}}">
                                    </div>
                                    {{input_error($errors,'time')}}
                                </div>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="date" class="control-label">{{__('Date')}}</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><li class="fa fa-clock-o"></li></span>
                                    <input type="date" name="date" class="form-control"
                                           value="{{now()->format('Y-m-d')}}" id="date" placeholder="{{__('Date')}}">
                                </div>
                                {{input_error($errors,'date')}}
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group has-feedback col-md-3">
                                <label for="inputSymbolAR"
                                       class="control-label">{{__('Select Purchase Invoice')}}</label>
                                <select name="purchase_invoice_id" class="form-control select2 js-example-basic-single"
                                        id="invoiceData">
                                    <option value="">{{__('Select Purchase Invoice')}}</option>
                                    @foreach($invoices as $invoice)
                                        <option value="{{$invoice->id}}">{{$invoice->invoice_number}}</option>
                                    @endforeach
                                </select>
                                {{input_error($errors,'purchase_invoice_id')}}
                            </div>

                            <div style="display: none;" id="supplierData">
                                <div class="form-group has-feedback col-md-3">
                                    <label for="supplier_name" class="control-label">{{__('Supplier Name')}}</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><li class="fa fa-user"></li></span>
                                        <input type="text" readonly name="supplier_name" class="form-control" value=""
                                               id="supplier_name">
                                    </div>
                                    <input type="hidden" name="supplier_id" class="form-control" value=""
                                           id="supplier_id">
                                </div>
                                <div class="form-group has-feedback col-md-3">
                                    <label for="supplier_discount"
                                           class="control-label">{{__('Supplier Discount')}}</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><li class="fa fa-dollar"></li></span>
                                        <input type="text" readonly name="supplier_discount" class="form-control"
                                               value="" id="supplier_discount">
                                    </div>
                                </div>
                                <div class="form-group has-feedback col-md-3">
                                    <label for="supplier_discount" class="control-label">{{__('Discount Type')}}</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><li class="fa fa-dollar"></li></span>
                                        <input type="text" readonly name="supplier_discount_type" class="form-control"
                                               value="" id="supplier_discount_type">
                                    </div>
                                </div>
                            </div>
                        </div>

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

                                    <div class="table-responsive">
                                        <table id="invoiceItemsDetails" class="table table-bordered" style="width:100%">
                                            <thead style="background-color: #ada3a361">
                                            <tr>
                                                <th scope="col">{!! __('Spare Part Name') !!}</th>
                                                <th scope="col">{!! __('Available quantity') !!}</th>
                                                <th scope="col">{!! __('Returned quantity') !!}</th>
                                                <th scope="col">{!! __('purchase price') !!}</th>
                                                <th scope="col">{!! __('Store') !!}</th>
                                                <th scope="col">{!! __('Discount Type') !!}</th>
                                                <th scope="col">{!! __('Discount') !!}</th>
                                                <th scope="col">{!! __('Total') !!}</th>
                                                <th scope="col">{!! __('Total After Discount') !!}</th>
                                                <th scope="col">
                                                    {!! __('Action') !!}
                                                    <input type="checkbox" id="selectAllItems" checked
                                                           class="sales_invoice_checkbox" onclick="select_all()">
                                                    <label for="selectAllItems"></label>
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody id="add_parts_details">
                                            </tbody>
                                            <input type="hidden" name="items_count" id="invoice_items_count" value="0">
                                            <input type="hidden" name="parts_count" id="parts_count" value="0">
                                            <tfoot style="background-color: #ada3a361">
                                            <tr>
                                                <th scope="col">{!! __('Spare Part Name') !!}</th>
                                                <th scope="col">{!! __('Available quantity') !!}</th>
                                                <th scope="col">{!! __('Returned quantity') !!}</th>
                                                <th scope="col">{!! __('purchase price') !!}</th>
                                                <th scope="col">{!! __('Store') !!}</th>
                                                <th scope="col">{!! __('Discount Type') !!}</th>
                                                <th scope="col">{!! __('Discount') !!}</th>
                                                <th scope="col">{!! __('Total') !!}</th>
                                                <th scope="col">{!! __('Total After Discount') !!}</th>
                                                <th scope="col">
                                                    {!! __('Action') !!}
{{--                                                    <input type="checkbox" id="selectAllItems" --}}
{{--                                                           class="sales_invoice_checkbox">--}}
{{--                                                    <label for="selectAllItems"></label>--}}
                                                </th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group has-feedback col-sm-12">

                        <!-- <h3 style="text-align: center;">{{__('Invoice Totals')}}</h3> -->
                            <div class="table-responsive">
                                <table class="table table-bordered" style="width:100%">
                                    <thead>
                                    <tr>
                                        <th scope="col">{!! __('Invoice Type') !!}</th>
                                        <th scope="col">{!! __('Total') !!}</th>
                                        <th scope="col">{!! __('Parts Number') !!}</th>
                                        <th scope="col">{!! __('Discount Type') !!}</th>
                                        <th scope="col">{!! __('Discount') !!}</th>
                                        <th scope="col">{!! __('Supplier Discount') !!}</th>
                                        <th scope="col">{!! __('Total After Discount') !!}</th>
                                        <th scope="col">
                                            <a class="btn btn-xs btn-danger " data-toggle="modal"
                                               data-target="#taxes-data" title="{{__('View Taxes')}}">
                                                <i class="fa fa-eye"> </i>
                                            </a>
                                            {!! __('Taxes') !!}
                                        </th>
                                        <th scope="col">{!! __('Total') !!}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>

                                            <div class="radio info">
                                                <input type="radio" name="type" value="cash" checked id="type-cash">
                                                <label for="type-cash">{{__('Cash')}}</label>
                                            </div>
                                            <div class="radio pink">
                                                <input type="radio" name="type" value="credit" id="type-credit">
                                                <label for="type-credit">{{__('Credit')}}</label>
                                            </div>

                                        </td>
                                        <td>
                                            <input type="text" class="form-control" readonly
                                                   name="total_before_discount"
                                                   id="total_before_discount" value="0">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" readonly name="number_of_items"
                                                   value="0" id="number_of_items">
                                        </td>
                                        <td>
                                            <div class="radio info">
                                                <input type="radio" name="discount_type" value="amount" checked
                                                       onclick="implementInvoiceDiscount()"
                                                       implementInvoiceDiscount id="invoice_discount_amount"><label
                                                        for="invoice_discount_amount">{{__('Amount')}}</label></div>

                                            <div class="radio pink">
                                                <input type="radio" name="discount_type" value="percent"
                                                       onclick="implementInvoiceDiscount()"
                                                       id="invoice_discount_percent"><label
                                                        for="invoice_discount_percent">{{__('Percent')}}</label></div>
                                        </td>
                                        <td><input type="number" class="form-control" value="0" id="discount"
                                                   onchange="implementInvoiceDiscount()"
                                                   onkeyup="implementInvoiceDiscount()"
                                                   name="discount" min="0">
                                        </td>

                                        {{--  SUPPLIER DISCOUNT --}}
                                        <td>
                                            <div class="row">

                                                <div class="col-md-2" style="margin-top: 10px;">
                                                    <div class="form-group has-feedback">

                                                        <input type="checkbox" id="supplier_discount_check"
                                                               name="supplier_discount_check"
                                                               onclick="implementInvoiceDiscount()">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <input type="text" class="form-control supplier_discount_type"
                                                           style="width:42px;" value="$" disabled
                                                    >

                                                    <input type="hidden" name="discount_group_type"
                                                           value="amount" class="supplier_discount_type_value">
                                                </div>

                                                <div class="col-md-7">
                                                    <input type="number"
                                                           class="form-control new_supplier_group_discount"
                                                           value="0" name="discount_group_value"
                                                           min="0" readonly>
                                                </div>

                                            </div>
                                        </td>

                                        <td>
                                            <input type="text" class="form-control" readonly value="0"
                                                   name="total_after_discount" id="total_after_discount">
                                        </td>

                                        <td>
                                            <input type="text" class="form-control" readonly name="tax"
                                                   id="invoice_tax" value="0">
                                        </td>

                                        <td>
                                            <input type="text" class="form-control" readonly name="total"
                                                   id="total" value="0">
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="form-group col-sm-12">
                            @include('admin.buttons._save_buttons')
                        </div>
                    </form>
                </div>
                <!-- /.box-content -->
            </div>
        </div>
        <!-- /.col-xs-12 -->
    </div>
    {{-- hidden inputs to solve supplier group discount calculation --}}
    <input type="hidden" id="purchase_all_quantity"/>
    <input type="hidden" id="purchase_return_qnt"/>
    <input type="hidden" id="purchase_supplier_group_discount"/>
    <!-- /.row small-spacing -->
@endsection

@section('modals')
    @include('admin.purchase_returns.taxes.index')
@endsection

@section('js-validation')

    {!! JsValidator::formRequest('App\Http\Requests\Admin\PurchaseReturn\PurchaseReturnRequest', '.form'); !!}

    @include('admin.partial.sweet_alert_messages')

    <script type="application/javascript">

        function setServiceValues(id) {

            let available_qty = ('#available-qy-' + id);
            let purchase_qty = $('#purchased-qy-' + id);
            let purchase_price = $('#purchased-price-' + id);
            let total = $("#total-" + id);
            let discount_amount = $("#radio-10-discount-amount-" + id);
            let discount_percent = $("#radio-12-discount-percent-" + id);
            let discount = $("#discount-" + id).val();
            let total_after_discount = $('#total_after_discount');
            var item_total = (purchase_qty.val() * purchase_price.val()).toFixed(2);

            total.val(item_total);

            // update_supplier_group_discount()

            if (discount_amount.prop('checked')) {
                implementDiscount(discount, discount_amount.val(), total.val(), id);
            } else {
                implementDiscount(discount, discount_percent.val(), total.val(), id);
            }

            calculateInvoiceTotal();

            calculateTax();
        }

        function implementDiscount(discount, discount_type, total, id) {
            if (discount == "") {
                discount = 0;
            }
            if (discount_type == 'amount') {
                var price_after_discount = parseFloat(total) - parseFloat(discount);
            } else {
                var discount_percentage_value = parseFloat(total) * parseFloat(discount) / parseFloat(100);
                var price_after_discount = parseFloat(total) - parseFloat(discount_percentage_value);
            }
            $("#total-after-discount-" + id).val(price_after_discount);

            calculateTax();
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

            for (var i = 0; i < items_count; i++) {

                var part_id = $("#part-" + i).val();

                var total_after_discount = $("#total-after-discount-" + part_id).val();

                var checked_item = $('#item-' + part_id).is(":checked");

                if (part_id && total_after_discount && checked_item) {

                    // if (part_id) {
                    total += parseFloat($("#total-after-discount-" + part_id).val());
                    part_items += 1;
                }
            }

            if (total <= 0)
                total = 0;

            $("#total_before_discount").val(total.toFixed(2));
            $("#number_of_items").val(part_items);

            implementInvoiceDiscount();

            calculateTax();
        }

        function partsItemsCount() {
            var parts_count = $("#parts_count").val();
            $("#number_of_items").val(parts_count);
        }

        function supplierDiscountValue() {

            var discount = $(".new_supplier_group_discount").val();
            var supplier_discount_type = $(".supplier_discount_type_value").val();
            var total = $("#total_before_discount").val();

            if (discount == "") {
                discount = 0;
            }

            if (supplier_discount_type == 'amount') {

                return discount;

            } else {

                discount = parseFloat(total) * parseFloat(discount) / parseFloat(100);

                return discount;
            }
        }

        function implementInvoiceDiscount() {

            var discount = $("#discount").val();
            var invoice_discount_amount = $("#invoice_discount_amount");
            var invoice_discount_percent = $("#invoice_discount_percent");
            var total = $("#total_before_discount").val();
            var parts_items_count = $("#invoice_items_count").val();
            let supplier_discount = 0;

            if (!discount)
                discount = 0;

            if ($('#supplier_discount_check').is(':checked')) {

                supplier_discount = supplierDiscountValue();

            } else {
                // $("#customer_discount_value").val(0);
                $("#supplier_discount_check").prop('checked', false);
            }


            if (parts_items_count == 0) {
                swal({
                    text: "{{__('you can not add discount now!')}}",
                    icon: "warning",
                }).then(function (isConfirm) {
                    if (isConfirm) {
                        $("#discount").val(0);
                        $("#total_after_discount").val(total);
                    }
                });
            }
            if (invoice_discount_amount.val() == "" || invoice_discount_percent.val() == "") {
                discount = 0;
            }
            if (invoice_discount_amount.prop('checked')) {
                var price_after_discount = parseFloat(total) - parseFloat(discount) - parseFloat(supplier_discount);
            } else {
                // implementDiscount(discount, discount_percent.val(),total.val(),id);
                var discount_percentage_value = parseFloat(total) * parseFloat(discount) / parseFloat(100);
                var price_after_discount = parseFloat(total) - parseFloat(discount_percentage_value) - parseFloat(supplier_discount);
            }

            if (price_after_discount <= 0) {
                price_after_discount = 0;
            }

            $("#total_after_discount").val(price_after_discount);

            calculateTax();
        }

        function getSparePartsById(id) {
            event.preventDefault();
            $.ajax({
                url: "{{ route('admin:purchase-invoices.parts') }}?spare_part_id=" + id,
                method: 'GET',
                success: function (data) {
                    $('#add_parts').html(data.parts);
                }
            });
        }

        $('#discount_value').on('keyup', function () {
            let discountType = $('#discount_type').val();
            if (discountType === 'value') {
                let total_before_discount = document.getElementById('total_before_discount').value;
                let discount_value = $(this).val();
                document.getElementById('total_after_discount').value = (total_before_discount - discount_value).toFixed(2);
            }
            if (discountType === 'percent') {
                let total_before_discount = document.getElementById('total_before_discount').value;
                let discount_value = $(this).val();
                document.getElementById('total_after_discount').value = (total_before_discount - (total_before_discount * (discount_value / 100))).toFixed(2);
            }

        });

        $('#discount_type').on('change', function () {
            document.getElementById('total_after_discount').value = document.getElementById('total_before_discount').value;
            document.getElementById('discount_value').value = 0;
        });

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
                }
            });
        }

        $("#invoiceData").on('change', function () {

            emptyTotalInvoice();

            let invoice_id = $(this).val();

            $('#supplierData').hide();

            if (invoice_id !== '') {
                items_count = $("#invoice_items_count").val();
                parts_count = $("#parts_count").val();
                $('#supplierData').show();
                $.ajax({
                    url: "{{ route('admin:purchase_returns.getPurchaseInvoice')}}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {invoice_id: invoice_id, items_count: items_count, parts_count: parts_count},
                    success: function (data) {

                        $('.new_supplier_group_discount').val(data.invoiceData.discount_group_value);
                        $('.supplier_discount_type_value').val(data.invoiceData.discount_group_type);

                        $('.supplier_discount_type').val('$');

                        if (data.invoiceData.discount_group_type == 'percent') {
                            $('.supplier_discount_type').val('%');
                        }

                        $("#supplier_discount_check").prop('checked', false);

                        if (data.invoiceData.is_discount_group_added == 1) {
                            $("#supplier_discount_check").prop('checked', true);
                        }


                        if (data.invoiceData.discount_type == 'amount') {

                            $("#invoice_discount_amount").prop('checked', true);

                        } else {

                            $("#invoice_discount_percent").prop('checked', true);
                        }

                        $("#discount").val(data.invoiceData.discount);


                        $('#supplier_name').val(data.supplier_name);
                        $('#supplier_id').val(data.supplier_id);
                        $('#add_parts_details').html(data.parts);
                        $('#invoice_items_count').val(data.items_count);
                        $('#parts_count').val(data.parts_count);
                        $('#supplier_discount').val(data.supplierDiscount);
                        $('#supplier_discount_type').val(data.supplierDiscountType);
                        $('#customer_discount').val(data.discount_supplier_value);
                        $('#number_of_items').val(data.number_of_items);
                        $('#total').val(data.total);
                        $('#invoice_tax').val(data.invoiceData.tax);
                        $('#total_after_discount').val(data.total_after_discount);
                        $('#total_before_discount').val(data.invoiceData.subtotal);
                        $('#purchase_all_quantity').val(data.purchase_all_quantity);
                        $('#purchase_return_qnt').val(data.purchase_return_qnt);
                        $('#purchase_supplier_group_discount').val(data.purchase_supplier_group_discount);

                        $(".select2").select2()
                    },
                    error: function (jqXhr, json, errorThrown) {
                        toastr.info("{{__('Some Thing Went Wrong Please Try Again')}}", '', options);
                    },
                });
            }

        });

        function emptyTotalInvoice() {
            $('#total_before_discount').val(0);
            $('#number_of_items').val(0);
            $('#discount').val(0);
            $('#total_after_discount').val(0);
        }

        // NOT WORK NOW
        function addToInvoiceTotal(itemId) {

            let totalAfterDiscount = $("#total-after-discount-" + itemId).val();
            let finalTotalBefore = $('#total_before_discount').val();
            let finalTotalAfter = $('#total_after_discount').val();
            let numberOfReturnItems = $("#number_of_items").val();
            if ($("#item-" + itemId).is(":checked")) {
                $('#total_before_discount').val(Number(finalTotalBefore) + Number(totalAfterDiscount));
                $('#total_after_discount').val(Number(finalTotalAfter) + Number(totalAfterDiscount));
                $("#number_of_items").val(Number(numberOfReturnItems) + 1);

            } else {


                $('#total_before_discount').val(Number(finalTotalBefore) - Number(totalAfterDiscount));

                let value = Number(finalTotalAfter) - Number(totalAfterDiscount);

                if (value <= 0) {
                    value = 0
                }

                $('#total_after_discount').val(value);
                $("#number_of_items").val(Number(numberOfReturnItems) - 1);
            }

            implementInvoiceDiscount();

            // update_supplier_group_discount()
        }

        // NEW ALTERNATIVE OF SELECT ALL ITEMS
        function select_all() {

            var select_all = $('#selectAllItems').is(":checked");

            if (select_all) {
                $('.sales_invoice_checkbox').prop('checked', true);
            } else {
                $('.sales_invoice_checkbox').prop('checked', false);
            }

            calculateInvoiceTotal();
        }

        // NOT WORK NOW
        function addSupplierDisounctToInvoice() {

            let supplierDiscount = $('#customer_discount')
            let totalAfterDiscount = $('#total_after_discount').val()
            if (!supplierDiscount.val()) {
                return false
            }

            if ($('#discount_allowed').is(':checked')) {
                $('#total_after_discount').val((parseFloat(totalAfterDiscount) - parseFloat(supplierDiscount.val())))
            }

            if (!$('#discount_allowed').is(':checked')) {
                $('#total_after_discount').val((parseFloat(totalAfterDiscount) + parseFloat(supplierDiscount.val())))
            }
        }

        function getInvoicesByBranch(event) {
            window.location = "{{ route('admin:purchase_returns.create') }}?branch_id=" + event.target.value
            $.ajax({
                url: "{{ route('admin:purchase_returns.getInvoiceByBranch') }}?branch_id=" + event.target.value,
                method: 'GET',
                async: false,
                success: function (data) {
                    $('#invoiceData').html(data.invoices)
                }
            });
        }

        // NOT WORK NOW
        function update_supplier_group_discount() {
            var purchase_return_qnt = 0,
                purchase_all_quantity = $("#purchase_all_quantity").val(),
                purchase_supplier_group_discount = $("#purchase_supplier_group_discount").val()
            $("input[name='idsItemsToReturn[]']").each(function () {
                if ($(this).prop("checked")) {
                    let qnt = $(this).parent().siblings("td").find("select[name='purchased_qy[]'] option:selected").val()
                    purchase_return_qnt += parseFloat(qnt)
                }
            })

            var current_supplier_group_discount = (purchase_supplier_group_discount / purchase_all_quantity) * purchase_return_qnt;
            $("#customer_discount").val(current_supplier_group_discount)
        }


        function calculateTax() {

            var total_after_discount = $("#total_after_discount").val();
            var tax_count = $("#tax_count").val();
            var total_tax = 0;
            var subtotal = $("#total_before_discount").val();

            if (subtotal == 0) {

                $("#invoice_tax").val(0);
                $("#total_after_discount").val(0);
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

            $("#total").val(total.toFixed(2));

            $("#invoice_tax").val(total_tax.toFixed(2));
        }

    </script>
@endsection
