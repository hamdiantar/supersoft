@extends('admin.layouts.app')
@section('title')
    <title>{{ __('Super Car') }} - {{ __('Create Purchase Invoice') }} </title>
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
                        href="{{route('admin:purchase-invoices.index')}}"> {{__('Purchase Invoices')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Create Purchase Invoice')}}</li>
            </ol>
        </nav>
        <div class="col-xs-12">
            <div class=" card box-content-wg-new bordered-all primary">
                <h1 class="box-title bg-info" style="text-align: initial"><i
                        class="fa fa-file-text-o"></i>{{__('Purchase Invoices')}}
                    <span class="controls hidden-sm hidden-xs pull-left">
                    <button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Save')}}<img
                            class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px"
                            src="{{asset('assets/images/f1.png')}}"></button>

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
                    <form method="post" action="{{route('admin:purchase-invoices.store')}}" class="form">
                        @csrf
                        @method('post')

                        @if (authIsSuperAdmin())

                            <div class="form-group has-feedback col-md-12">
                                <label for="inputSymbolAR" class="control-label">{{__('Select Branch')}}</label>
                                <select name="branch_id" class="form-control select2 js-example-basic-single"
                                        id="branch_id" onchange="getDataByBranch(event)">
                                    <option value="">{{__('Select Branch')}}</option>
                                    @foreach($branches as $k=>$v)
                                        <option
                                            value="{{$k}}" {{ isset($_GET['branch_id']) && $_GET['branch_id'] == $k? 'selected' : '' }}>
                                            {{$v}}
                                        </option>
                                    @endforeach
                                </select>
                                {{input_error($errors,'branch_id')}}
                            </div>

                        @else

                            <input type="hidden" class="form-control" name="branch_id" id="branch_id"
                                   value="{{auth()->user()->branch_id}}">
                        @endif
                        {{-- INVOICE NUMBER --}}
                        <div class="form-group  col-md-3">
                            <label for="invoice_number" class="control-label">{{__('Invoice Number')}}</label>
                            <div class="input-group">
                                <span class="input-group-addon"><li class="fa fa-file-text-o"></li></span>
                                <input type="text" name="invoice_number" value="" class="form-control"
                                       id="invoice_number"
                                       placeholder="{{__('Invoice Number')}}">
                            </div>
                            {{input_error($errors,'invoice_number')}}
                        </div>
                        {{-- TIME --}}
                        <div class="form-group col-md-3">
                            <div class="form-group">
                                <label for="type_en" class="control-label">{{__('Time')}}</label>
                                <div class="input-group">
                                    <!-- <span class="input-group-addon"><li class="fa fa-clock-o"></li></span> -->
                                    <input type="time" name="time" class="form-control" value="{{now()->format('h:i')}}"
                                           id="time" placeholder="{{__('Time')}}">
                                </div>
                                {{input_error($errors,'time')}}
                            </div>
                        </div>
                        {{-- DATE --}}
                        <div class="form-group col-md-3">
                            <label for="date" class="control-label">{{__('Date')}}</label>
                            <div class="input-group">
                                <!-- <span class="input-group-addon"><li class="fa fa-clock-o"></li></span> -->
                                <input type="date" name="date" class="form-control" value="{{now()->format('Y-m-d')}}"
                                       id="date" placeholder="{{__('Date')}}">
                            </div>
                            {{input_error($errors,'date')}}
                        </div>

                        {{-- SUPPLIER --}}
                        <div class="form-group has-feedback col-md-3" id="supplier_data">
                            <label for="inputSymbolAR" class="control-label">{{__('Select Supplier')}}</label>
                            <a class="btn btn-danger   pull-left"
                               style="margin-top:-5px;cursor:pointer;font-size:11px;font-weight:bold;padding:2px 2px"
                               data-toggle="modal"
                               onclick="setBranchIdForSupplier()" data-target="#boostrapModal-2"
                               title="{{__('Add Supplier')}}">
                                <i class="fa fa-plus"></i> {{__('New Supplier')}}
                            </a>

                            <select name="supplier_id" class="form-control js-example-basic-single"
                                    id="supplier_value" onchange="getSupplierBalance()">
                                <option value="">{{__('Select Supplier')}}</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{$supplier->id}}">
                                        {{$supplier->name .'-'. $supplier->phone_1}}
                                    </option>
                                @endforeach
                            </select>
                            {{input_error($errors,'supplier_id')}}
                        </div>

                        <div class=" form-group col-md-12" style="display: none;" id="supplier_funds_inputs">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="funds_for" class="control-label">{{__('Funds For')}}</label>
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
                                        <label for="funds_for" class="control-label">{{__('Funds On')}}</label>
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
                                               class="control-label">{{__('Supplier group Discount')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-money"></li></span>
                                            <input type="text" name="discount" class="form-control"
                                                   id="supplier_group_discount"
                                                   placeholder="{{__('discount')}}" disabled="disabled">
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
                                            <input type="text" name="supplier_discount_type"
                                                   class="form-control" id="show_customer_discount_type_value"
                                                   placeholder="{{__('Supplier group Discount Type')}}"
                                                   disabled="disabled">
                                        </div>
                                        {{input_error($errors,'supplier_discount_type')}}
                                    </div>
                                </div>
                            </div>
                        </div>


                        @include('admin.purchase-invoices.parts.parts-card')


                        {{-- Invoice details --}}
                        <div class="form-group has-feedback col-sm-12">

                            <div class="table-responsive">
                                <table class="table  table-bordered" style="width:100%">
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
                                                   name="subtotal" id="subtotal" value="0">
                                        </td>

                                        <td>
                                            <input type="text" class="form-control" readonly name="number_of_items"
                                                   value="0" id="number_of_items">
                                        </td>

                                        <td>
                                            <div class="radio info">
                                                <input type="radio" name="discount_type" value="amount" checked
                                                       onclick="implementInvoiceDiscount()"
                                                       id="invoice_discount_amount">
                                                <label for="invoice_discount_amount">{{__('Amount')}}</label>
                                            </div>

                                            <div class="radio pink">
                                                <input type="radio" name="discount_type" value="percent"
                                                       onclick="implementInvoiceDiscount()"
                                                       id="invoice_discount_percent">
                                                <label for="invoice_discount_percent">{{__('Percent')}}</label>
                                            </div>
                                        </td>

                                        <td>
                                            <input type="number" class="form-control" value="0" id="discount"
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
                                                    <input type="text" style="width:42px;"
                                                           class="form-control supplier_discount_type"
                                                           value="$" disabled
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

                        @foreach($taxes as $index=>$tax)
                            <input type="hidden" name="taxes[{{$index}}]" value="{{$tax->id}}" id="tax_status_check_{{$index+1}}">
                        @endforeach

                        <div class="col-md-6">

                            <div class="form-group col-sm-12">
                                @include('admin.buttons._save_buttons')
                            </div>

                        </div>
                    </form>
                </div>
                <!-- /.box-content -->
            </div>
        </div>
        <!-- /.col-xs-12 -->
    </div>
    </div>
    </div>
    <!-- /.row small-spacing -->
@endsection

@section('modals')
    @include('admin.purchase-invoices.supplier.supplier_form')
    @include('admin.purchase-invoices.taxes.index')

    <div class="modal fade" id="boostrapModal-3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-1">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel-1">{{__('Supplier Locations')}}</h4>
                </div>
                <div class="modal-body">
                    <div class="modal-body" id="map" style="height: 400px;">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm waves-effect waves-light" data-dismiss="modal">
                        {{__('Close')}}
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js-validation')

    {!! JsValidator::formRequest('App\Http\Requests\Admin\PurchaseInvoice\PurchaseInvoiceRequest', '.form'); !!}

    @include('admin.partial.sweet_alert_messages')

    <script src="{{asset('js/invoices/parts_filter.js')}}"></script>
    <script src="{{asset('js/invoices/purchase.js')}}"></script>

    <script type="application/javascript">

        function setBranchIdForSupplier() {
            event.preventDefault();
            let branch_id = $("#branch_id").children("option:selected").val();
            $("#setBranchIdForSupplier").val(branch_id)
        }

        function supplierDiscount() {

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

                items_count = $("#invoice_items_count").val();
                parts_count = $("#number_of_items").val();
                branch_id = $("#branch_id").val();

                $.ajax({
                    url: "{{ route('admin:purchase-invoices.parts.details') }}?part_id=" + id,
                    method: 'GET',
                    data: {items_count: items_count, parts_count: parts_count, branch_id: branch_id},
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

                    var parts_count = $("#number_of_items").val();
                    $('#number_of_items').val(parseInt(parts_count) - parseInt(1));
                    $('#' + id).remove();
                    calculateInvoiceTotal();
                    calculateTax();
                }
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
                    $('.js-example-basic-single').select2();
                    $("#boostrapModal-2").modal('hide');
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
                    $('#supplier_discount_type').val(data['supplier_discount_type']);
                    $('.supplier_discount_type_value').val(data['supplier_discount_type']);


                    @if(\Illuminate\Support\Facades\App::getLocale() == 'ar')

                    if (data['supplier_discount_type'] == 'percent')
                        $('#show_customer_discount_type_value').val('نسبة');
                    else
                        $('#show_customer_discount_type_value').val('قيمة');

                    @else

                    if (data['supplier_discount_type'] == 'percent')
                        $('#show_customer_discount_type_value').val('percent');
                    else
                        $('#show_customer_discount_type_value').val('amount');

                    @endif


                    $('.new_supplier_group_discount').val(data['supplier_group_discount']);

                    $('.supplier_discount_type').val('$');

                    if (data['supplier_discount_type'] == 'percent') {
                        $('.supplier_discount_type').val('%');
                    }

                    $("#supplier_funds_inputs").show();
                },

                error: function (jqXhr, json, errorThrown) {

                    $('.supplier_discount_type_value').val('amount');
                    $('.supplier_discount_type').val('$');
                    $('.new_supplier_group_discount').val(0);
                    $("#supplier_discount_check").prop('checked', false);
                    implementInvoiceDiscount();

                    // $('.ajax-load-result').hide();
                    var errors = jqXhr.responseJSON;
                    toastr.info("{{__('you not select supplier')}}", '', options);
                },
            });
        }

        function addSupplierDisounctToInvoice(total = 0) {
            let supplierDiscount = $('#discount_allowed')
            let totalAfterDiscount = $('#total_after_discount').val()
            let supplierDiscountType = $('#supplier_discount_type').val()
            console.log(parseFloat(totalAfterDiscount).toFixed(2))
            if (totalAfterDiscount == 0) {
                swal({
                    text: "{{__('please fill the invoice , then you can add discount')}}",
                    icon: "warning",
                })
                supplierDiscount.click()
                return false
            }
            if (!supplierDiscount.val()) {
                swal({
                    text: "{{__('please Select Supplier')}}",
                    icon: "warning",
                })
                supplierDiscount.click()
                return false
            }
            if (supplierDiscount.prop('checked') && supplierDiscountType === 'percent') {
                let SD = parseFloat(supplierDiscount.val()).toFixed(2)
                let TAD = parseFloat(totalAfterDiscount).toFixed(2)
                let discountValue = TAD * SD / 100
                $('#total_after_discount').val((TAD - discountValue))
                var remaining = (TAD - discountValue) - parseFloat($('#paid').val());
                $('#remaining').val(remaining.toFixed(2))
                $('#customer_discount').val(discountValue)
            }

            if (supplierDiscount.prop('checked') && supplierDiscountType === 'amount') {
                let SD = parseFloat(supplierDiscount.val()).toFixed(2)
                let TAD = parseFloat(totalAfterDiscount).toFixed(2)
                $('#total_after_discount').val((TAD - SD))
                var remaining = (TAD - (SD)) - parseFloat($('#paid').val());
                $('#remaining').val(remaining.toFixed(2))
                $('#customer_discount').val(SD)
            }

            if (!supplierDiscount.prop('checked')) {
                let CD = $('#customer_discount').val()
                $('#total_after_discount').val((parseFloat(totalAfterDiscount) + parseFloat(CD)))
                $('#remaining').val((parseFloat(totalAfterDiscount) + parseFloat(CD)))
                $('#customer_discount').val(0)
            }
        }

        function getDataByBranch(event) {
            window.location = "{{ route('admin:purchase-invoices.create') }}?branch_id=" + event.target.value
            $.ajax({
                url: "{{ route('admin:purchase-invoices.getDataByBranch') }}?branch_id=" + event.target.value,
                method: 'GET',
                async: false,
                success: function (data) {
                    $('#setDataByBranch').html(data.invoice);
                    $('#supplier_value').html(data.suppliers);
                    $('#Spare_part_type').html(data.sparePartsSelect);
                    $('#partsSelect').html(data.partsSelect);
                    $('#partOFBarcode').html(data.barcodeSelect);
                    $("#supplier_funds_inputs").hide();
                }
            });
        }


    </script>

    <script type="application/javascript" defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD4YXeD4XTeNieaKWam43diRHXjsGg7aVY&callback=initMap"></script>

    <script type="text/javascript">


        //Set up some of our variables.
        var map; //Will contain map object.
        var marker = false; ////Has the user plotted their location marker?

        //Function called to initialize / create the map.
        //This is called when the page has loaded.
        function initMap() {

            //The center location of our map.
            var centerOfMap = new google.maps.LatLng(24.68731563631883, 46.719044971885445);


            //Map options.
            var options = {
                center: centerOfMap, //Set center.
                zoom: 7 //The zoom value.
            };

            //Create the map object.
            map = new google.maps.Map(document.getElementById('map'), options);

            //Listen for any clicks on the map.
            google.maps.event.addListener(map, 'click', function (event) {
                //Get the location that the user clicked.
                var clickedLocation = event.latLng;
                //If the marker hasn't been added.
                if (marker === false) {
                    //Create the marker.
                    marker = new google.maps.Marker({
                        position: clickedLocation,
                        map: map,
                        draggable: true //make it draggable
                    });
                    //Listen for drag events!
                    google.maps.event.addListener(marker, 'dragend', function (event) {
                        markerLocation();
                    });
                } else {
                    //Marker has already been added, so just change its location.
                    marker.setPosition(clickedLocation);
                }
                //Get the marker's location.
                markerLocation();
            });
        }

        //This function will get the marker's current location and then add the lat/long
        //values to our textfields so that we can save the location.
        function markerLocation() {
            //Get location.
            var currentLocation = marker.getPosition();
            //Add lat and lng values to a field that we can save.
            document.getElementById('lat').value = currentLocation.lat(); //latitude
            document.getElementById('lng').value = currentLocation.lng(); //longitude
        }


        //Load the map when the page has finished loading.
        google.maps.event.addDomListener(window, 'load', initMap);

    </script>

@endsection
