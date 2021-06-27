@extends('admin.layouts.app')
@section('title')
    <title>{{ __('Super Car') }} - {{ __('Edit Purchase Invoice') }} </title>
@endsection


@section('content')
    <div class="row small-spacing">
        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a
                        href="{{route('admin:purchase-invoices.index')}}"> {{__('Purchase Invoices')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Edit Purchase Invoice')}}</li>
            </ol>
        </nav>
        @if ($purchase_invoice->invoiceReturn)
            <div class="alert alert-danger text-dark" role="alert"
                 style="margin-top:15px;text-align: center;font-size: 20px;">
                {{__('Sorry, this invoice cannot be modified')}}
            </div>
        @endif

        <div class="col-xs-12">
            <div class=" card box-content-wg-new bordered-all primary">
                <h1 class="box-title bg-info" style="text-align: initial"><i
                        class="fa fa-file-text-o"></i>
                    {{__('Purchase Invoices')}}
                    <span class="controls hidden-sm hidden-xs pull-left">
                            <button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Save')}}<img
                                    class="img-fluid"
                                    style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px"
                                    src="{{asset('assets/images/f1.png')}}">
                            </button>
							<button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Reset')}}<img
                                    class="img-fluid"
                                    style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px"
                                    src="{{asset('assets/images/f2.png')}}">
                            </button>
							<button class="control text-white" style="background:none;border:none;font-size:12px"> {{__('Back')}} <img
                                    class="img-fluid"
                                    style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px"
                                    src="{{asset('assets/images/f3.png')}}">
                            </button>
						</span>
                </h1>
                <div class="box-content">
                    <form method="post"
                          action="{{route('admin:purchase-invoices.update', ['id' => $purchase_invoice->id])}}"
                          class="form">
                        @csrf
                        @method('put')
                        <div class="row">
                            @if (authIsSuperAdmin())
                                <div class="form-group has-feedback col-md-12">

                                    <label for="inputSymbolAR" class="control-label">{{__('Branch')}}</label>

                                    <input type="text" class="form-control" readonly
                                           value="{{optional($purchase_invoice->branch)->name}}">

                                    <input type="hidden" class="form-control" name="branch_id" id="branch_id"
                                           value="{{$purchase_invoice->branch_id}}">
                                </div>

                            @else
                                <input type="hidden" class="form-control" name="branch_id" id="branch_id"
                                       value="{{$purchase_invoice->branch_id}}">
                            @endif

                            <div class="form-group  col-md-3">
                                <label for="invoice_number" class="control-label">{{__('Invoice Number')}}</label>
                                <div class="input-group">

                                    <span class="input-group-addon"><li class="fa fa-file-text-o"></li></span>
                                    <input type="text" name="invoice_number" readonly class="form-control"
                                           id="invoice_number" placeholder="{{__('Invoice Number')}}"
                                           value="{{$purchase_invoice->invoice_number}}">
                                </div>
                            </div>

                            <div class="form-group has-feedback col-md-3" id="supplier_data">
                                <label for="inputSymbolAR" class="control-label">{{__('Supplier')}}</label>

                                <input type="text" class="form-control" readonly
                                       value="{{optional($purchase_invoice->supplier)->name}}">

                                <input type="hidden" class="form-control" name="supplier_id" readonly
                                       value="{{$purchase_invoice->supplier_id}}">
                            </div>


                            <div class="form-group col-md-3">
                                <div class="form-group">
                                    <label for="type_en" class="control-label">{{__('Time')}}</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><li class="fa fa-clock-o"></li></span>
                                        <input type="time" name="time" class="form-control"
                                               value="{{$purchase_invoice->time}}"
                                               id="time" placeholder="{{__('Time')}}">
                                    </div>
                                    {{input_error($errors,'time')}}
                                </div>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="date" class="control-label">{{__('Date')}}</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><li class="fa fa-clock-o"></li></span>
                                    <input type="date" name="date" class="form-control"
                                           value="{{$purchase_invoice->date}}" id="date" placeholder="{{__('Date')}}">
                                </div>
                                {{input_error($errors,'date')}}
                            </div>

                            <div class=" form-group col-md-12"
                                 style="display: {{$purchase_invoice->supplier_id ? '':'none;'}}"
                                 id="supplier_funds_inputs">
                                <div class="row">
                                    @php
                                        $balance_details = optional($purchase_invoice->supplier)->direct_balance();
                                    @endphp
                                    @if($balance_details)
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="funds_for" class="control-label">{{__('Funds For')}}</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon"><li class="fa fa-money"></li></span>
                                                    <input type="text" name="funds_for" class="form-control"
                                                           id="funds_for"
                                                           placeholder="{{__('Funds For')}}" readonly
                                                           value="{{ $balance_details['debit'] }}">
                                                </div>
                                                {{input_error($errors,'funds_for')}}
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="funds_for" class="control-label">{{__('Funds On')}}</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon"><li class="fa fa-money"></li></span>
                                                    <input type="text" name="funds_on" class="form-control"
                                                           id="funds_on"
                                                           placeholder="{{__('Funds On')}}" readonly
                                                           value="{{ $balance_details['credit'] }}">
                                                </div>
                                                {{input_error($errors,'funds_on')}}
                                            </div>
                                        </div>
                                    @endif

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="inputQuantity"
                                                   class="control-label">{{__('Supplier group Discount')}}</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><li class="fa fa-money"></li></span>
                                                <input type="text" name="discount" class="form-control"
                                                       id="supplier_group_discount"
                                                       placeholder="{{__('discount')}}" readonly
                                                       value="{{$purchase_invoice->discount_group_value}}">
                                            </div>
                                            {{input_error($errors,'discount')}}
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="inputQuantity"
                                                   class="control-label">{{__('Supplier group Discount Type')}}</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><li class="fa fa-money"></li></span>
                                                <input type="text" name="supplier_discount_type" class="form-control"
                                                       id="supplier_discount_type"
                                                       placeholder="{{__('Supplier group Discount Type')}}"
                                                       disabled="disabled"
                                                       value="{{__($purchase_invoice->discount_group_type)}}">
                                            </div>
                                            {{input_error($errors,'supplier_discount_type')}}
                                        </div>
                                    </div>
                                </div>
                            </div>


                            @include('admin.purchase-invoices.parts.parts-card')


                            <div class="form-group has-feedback col-sm-12">

                            <!-- <h3 style="text-align: center;">{{__('Invoice Totals')}}</h3> -->
                                <div class="table-responsive">
                                    <table class="table table-bordered" style="width:100%">
                                        <thead>
                                        <tr>
                                            <th scope="col">{{__('Invoice Type')}}</th>

                                            <th scope="col">{!! __('Total') !!}</th>
                                            <th scope="col">{!! __('Parts Number') !!}</th>
                                            <th scope="col">{!! __('Discount Type') !!}</th>
                                            <th scope="col">{!! __('Discount') !!}</th>
                                            <th scope="col">{!! __('Supplier Discount') !!}</th>
                                            <th scope="col">{!! __('Total After Discount') !!}</th>
                                            <th scope="col">
                                                <a class="btn btn-xs btn-danger " data-toggle="modal"
                                                   data-target="#taxes-data" title="{{__('View Taxes')}}"
                                                   onclick="calculateTax()"
                                                >
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

                                                @if ($purchase_invoice->type == 'cash')
                                                    <div class="radio info">
                                                        <input type="radio" name="type" value="cash" checked
                                                               id="type-cash">
                                                        <label for="type-cash">{{__('Cash')}}</label>
                                                    </div>
                                                @endif
                                                @if ($purchase_invoice->type == 'credit')
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
                                                       id="subtotal" value="{{$purchase_invoice->subtotal}}">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" readonly name="number_of_items"
                                                       value="{{$purchase_invoice->number_of_items}}"
                                                       id="number_of_items">
                                            </td>
                                            <td>
                                                <div class="radio info">
                                                    <input type="radio" name="discount_type" value="amount"
                                                           {{$purchase_invoice->discount_type == 'amount' ? 'checked':''}}
                                                           onclick="implementInvoiceDiscount()"
                                                           id="invoice_discount_amount">
                                                    <label for="invoice_discount_amount">
                                                        {{__('Amount')}}
                                                    </label>
                                                </div>

                                                <div class="radio pink">
                                                    <input type="radio" name="discount_type" value="percent"
                                                           {{$purchase_invoice->discount_type == 'percent' ? 'checked':''}}
                                                           onclick="implementInvoiceDiscount()"
                                                           id="invoice_discount_percent">
                                                    <label for="invoice_discount_percent">{{__('Percent')}}</label>
                                                </div>
                                            </td>

                                            <td><input type="number" class="form-control"
                                                       value="{{$purchase_invoice->discount}}" id="discount"
                                                       onchange="implementInvoiceDiscount()"
                                                       onkeyup="implementInvoiceDiscount()"
                                                       name="discount" min="0">
                                            </td>

                                            {{--  SUPPLIER DISCOUNT --}}
                                            <td>
                                                <div class="col-xs-2">

                                                    <div class="" style="margin-top: 10px;">
                                                        <div class="form-group has-feedback">

                                                            <input type="checkbox" id="supplier_discount_check"
                                                                   name="supplier_discount_check"
                                                                   {{$purchase_invoice->is_discount_group_added == 1 ? 'checked' : ''}}
                                                                   onclick="implementInvoiceDiscount()">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-xs-3">
                                                    <input type="text" class="form-control supplier_discount_type"
                                                           style="width:42px;"
                                                           value="{{$purchase_invoice->discount_group_type == 'amount'? '$':'%'}}"
                                                           disabled
                                                    >

                                                    <input type="hidden" name="discount_group_type"
                                                           value="{{$purchase_invoice->discount_group_type}}"
                                                           class="supplier_discount_type_value">
                                                </div>

                                                <div class="col-xs-7">
                                                    <input type="number" style="width: 118px;margin-left: 19px;"
                                                           class="form-control new_supplier_group_discount"
                                                           value="{{$purchase_invoice->discount_group_value}}"
                                                           name="discount_group_value" min="0" readonly>
                                                </div>

                                            </td>


                                            <td>
                                                <input type="text" class="form-control" readonly
                                                       value="{{$purchase_invoice->total_after_discount}}"
                                                       name="total_after_discount" id="total_after_discount"
                                                >
                                            </td>

                                            <td>
                                                <input type="text" class="form-control" readonly name="tax"
                                                       id="invoice_tax" value="{{$purchase_invoice->tax}}">
                                            </td>

                                            <td>
                                                <input type="text" class="form-control" readonly
                                                       id="total" name="total"
                                                       value="{{$purchase_invoice->total}}"
                                                >
                                            </td>

                                        </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="form-group col-sm-12">
                                    @if (!$purchase_invoice->invoiceReturn)
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ico ico-left fa fa-save"></i>
                                            {{__('Save')}}
                                        </button>
                                        <button id="reset" type="button" class="btn btn-info"><i
                                                class="ico ico-left fa fa-trash"></i>{{__('Reset')}}</button>
                                    @endif
                                    <button id="back" type="button" class="btn btn-danger"><i
                                            class="ico ico-left fa fa-close"></i>{{__('Back')}}</button>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
                <!-- /.box-content -->
            </div>
        </div>
        <!-- /.col-xs-12 -->
    </div>
    <!-- /.row small-spacing -->

    {{--
        @include('admin.purchase-invoices.supplier.supplier_form')
    --}}

@endsection

@section('modals')
    @include('admin.purchase-invoices.taxes.index')
@endsection

@section('js-validation')

    {!! JsValidator::formRequest('App\Http\Requests\Admin\PurchaseInvoice\PurchaseInvoiceRequest', '.form'); !!}

    @include('admin.partial.sweet_alert_messages')

    <script type="application/javascript">

        function setServiceValues(id) {

            let purchase_qty = $('#purchased-qy-' + id);

            let purchase_price = $('#purchased-price-' + id);
            let total = $("#subtotal-" + id);
            let discount_amount = $("#radio-10-discount-amount-" + id);
            let discount_percent = $("#radio-12-discount-percent-" + id);
            let discount = $("#discount-" + id).val();

            var item_total = (purchase_qty.val() * purchase_price.val()).toFixed(2);

            total.val(item_total);

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

                if (part_id) {
                    total += parseFloat($("#total-after-discount-" + part_id).val());
                }
            }

            $("#subtotal").val(total);
            $("#total").val(total);

            implementInvoiceDiscount();

            calculateTax();
        }

        function implementInvoiceDiscount() {

            var discount = $("#discount").val();
            var invoice_discount_amount = $("#invoice_discount_amount");
            var invoice_discount_percent = $("#invoice_discount_percent");
            var total = $("#subtotal").val();
            var price_after_discount = 0;

            var parts_items_count = $("#invoice_items_count").val();
            let supplier_discount = 0;

            if ($('#supplier_discount_check').is(':checked')) {

                supplier_discount = supplierDiscountValue();

            } else {

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
                        $("#supplier_discount_check").prop('checked', false);
                    }
                });
            }

            if (invoice_discount_amount.val() == "" || invoice_discount_percent.val() == "") {
                discount = 0;
            }

            if (invoice_discount_amount.prop('checked')) {

                price_after_discount = parseFloat(total) - parseFloat(discount) - parseFloat(supplier_discount);

            } else {

                var discount_percentage_value = parseFloat(total) * parseFloat(discount) / parseFloat(100);

                price_after_discount = parseFloat(total) - parseFloat(discount_percentage_value) - parseFloat(supplier_discount);
            }

            if (price_after_discount <= 0) {
                price_after_discount = 0;
            }

            $("#total_after_discount").val(price_after_discount);

            calculateTax();
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

        function getPartsDetails(id) {

            event.preventDefault();
            let isExist = true;

            let items_count = $("#invoice_items_count").val();
            let parts_count = $("#number_of_items").val();
            let branch_id = $("#branch_id").val();

            $('#add_parts_details tr').each(function () {
                if ($(this).data('id') == id) {
                    isExist = false;
                    swal({
                        text: "{{__('You have chosen this Service before, you cannot choose it again!')}}",
                        icon: "warning",
                    })
                }
            });
            if (isExist) {

                $.ajax({
                    url: "{{ route('admin:purchase-invoices.parts.details') }}?part_id=" + id,
                    method: 'GET',
                    data: {items_count: items_count, parts_count: parts_count, branch_id:branch_id},
                    async: false,
                    success: function (data) {
                        $('#add_parts_details').append(data.view);
                        $('#invoice_items_count').val(data.items_count);
                        $('#number_of_items').val(data.parts_count);

                        $('.js-example-basic-single').select2();
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

                    var parts_count = $("#number_of_items").val();
                    $('#number_of_items').val(parseInt(parts_count) - parseInt(1));
                    $('#' + id).remove();

                    setServiceValues(id);
                    calculateInvoiceTotal();
                    calculateTax();
                }
            });
        }

        $('#searchInSparePartsType').on('keyup', function () {
            let value = $(this).val().toLowerCase();
            $(".searchResultSparePartsType li a").filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });

        $('#searchInParts').on('keyup', function () {
            let value = $(this).val().toLowerCase();
            $("#add_parts li a").filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });

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

            var type = $("#supplier_type").val();

            if (type == 'person') {

                $(".company_data").hide();
            } else {
                $(".company_data").show();
            }
        }

        function addSupplier() {

            event.preventDefault();
            $.ajax({
                url: "{{ route('admin:purchase.invoice.add.supplier')}}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: $("#add_supplier_form").serialize(),
                success: function (data) {
                    $('#supplier_data').html(data);

                    toastr.success('supplier saved successfully');
                    window.history.back();
                    $('.js-example-basic-single').select2();
                },
                error: function (jqXhr, json, errorThrown) {
                    // $('.ajax-load-result').hide();
                    var errors = jqXhr.responseJSON;
                    toastr.error(errors);
                },
            });
        }

        function getSupplierBalance() {

            var options = {"closeButton": true, "positionClass": "toast-bottom-right", "progressBar": true,};

            event.preventDefault();

            var supplier_id = $("#supplier_value").val();

            if (supplier_id == '') {
                $("#supplier_funds_inputs").hide();
            }

            $.ajax({
                url: "{{ route('admin:purchase.invoice.supplier.balance')}}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {supplier_id: supplier_id},
                success: function (data) {
                    $('#funds_for').val(data['funds_for']);
                    $('#funds_on').val(data['funds_on']);
                    $('#supplier_group_discount').val(data['supplier_group_discount']);
                    $('#discount_allowed').val(data['supplier_group_discount']);
                    $("#supplier_funds_inputs").show();
                },
                error: function (jqXhr, json, errorThrown) {
                    // $('.ajax-load-result').hide();
                    var errors = jqXhr.responseJSON;
                    toastr.info("{{__('you not select supplier')}}", '', options);
                },
            });
        }

        function addSupplierDisounctToInvoice() {
            let discount_allowed = $('#discount_allowed')
            let supplierDiscount = $('#supplier_group_discount')
            let totalAfterDiscount = $('#total_after_discount').val()
            let supplierDiscountType = $('#supplier_discount_type').val()
            if (!supplierDiscount.val()) {
                swal({
                    text: "{{__('please Select Supplier')}}",
                    icon: "warning",
                })
                supplierDiscount.click()
                return false
            }
            if (discount_allowed.prop('checked') && supplierDiscountType === 'percent') {
                let discountValue = parseFloat(totalAfterDiscount) * supplierDiscount.val() / 100

                $('#total_after_discount').val((parseFloat(totalAfterDiscount) - discountValue))
                var remaining = (parseFloat(totalAfterDiscount) - discountValue) - parseFloat($('#paid').val());
                $('#remaining').val(remaining.toFixed(2))
                $('#customer_discount').val(discountValue)
            }

            if (discount_allowed.prop('checked') && supplierDiscountType === 'amount') {
                $('#total_after_discount').val((parseFloat(totalAfterDiscount) - parseFloat(supplierDiscount.val())))
                var remaining = (parseFloat(totalAfterDiscount) - parseFloat(supplierDiscount.val())) - parseFloat($('#paid').val());
                $('#remaining').val(remaining.toFixed(2))
                $('#customer_discount').val(parseFloat(supplierDiscount.val()))
            }

            if (!discount_allowed.prop('checked') && supplierDiscountType === 'amount') {
                $('#total_after_discount').val((parseFloat(totalAfterDiscount) + parseFloat(supplierDiscount.val())))
                var remaining = (parseFloat(totalAfterDiscount) + parseFloat(supplierDiscount.val())) - parseFloat($('#paid').val());
                $('#remaining').val(remaining.toFixed(2))
                $('#customer_discount').val(parseFloat(0))
            }

            if (!discount_allowed.prop('checked') && supplierDiscountType === 'percent') {
                let discountValue = parseFloat($('#total_before_discount').val()) * supplierDiscount.val() / 100
                $('#total_after_discount').val((parseFloat(totalAfterDiscount) + discountValue))
                var remaining = (parseFloat(totalAfterDiscount) + discountValue) - parseFloat($('#paid').val());
                $('#remaining').val(remaining.toFixed(2))
                $('#customer_discount').val(parseFloat(0))
            }
        }

        function calculateTax() {

            var total_after_discount = $("#total_after_discount").val();
            var tax_count = $("#tax_count").val();
            var total_tax = 0;
            var subtotal = $("#subtotal").val();

            if (subtotal == 0) {

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

            $("#total").val(total.toFixed(2));

            $("#invoice_tax").val(total_tax.toFixed(2));
        }
    </script>
@endsection
