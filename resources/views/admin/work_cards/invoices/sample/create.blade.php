@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{__('Edit Card Invoice')}} </title>
@endsection

@section('style')

    <!--  BEGIN CUSTOM STYLE FILE  -->
    <link href="{{asset('css/custom-accordions.css')}}" rel="stylesheet" type="text/css"/>
    <!--  END CUSTOM STYLE FILE  -->

@endsection
@section('content')
    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin:work-cards.index')}}"> {{__('Work Cards')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Create Card Invoice')}}</li>
            </ol>
        </nav>

        <div class="col-xs-12">
            <div class=" card box-content-wg-new bordered-all primary">
                <h1 class="box-title bg-info" style="text-align: initial"><i class="fa fa-car"></i>
                    {{__('Create Card Invoice')}}
                    <span class="controls hidden-sm hidden-xs pull-left">
                    <button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Save')}}<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f1.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Reset')}}<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f2.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px"> {{__('Back')}} <img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f3.png')}}"></button>

						</span>
                </h1>
                <div class="box-content">

                    <form method="post" action="{{$url}}" id="card_invoice_sample"
                          enctype="multipart/form-data" class="form">
                        @csrf
                        @method('post')

                        @include('admin.work_cards.invoices.sample.form')

                        <input type="hidden" name="request_long" required id="request_long" class=" form-control" readonly>

                        <input type="hidden" name="request_lat" required id="request_lat" class=" form-control" readonly>

                        <input type="checkbox" name="active_winch_box" id="active_winch_box" style="display: none;">

                        <input type="hidden" name="winch_discount_type" id="winch-discount-type-request">

                        <input  type="hidden" class="form-control" value="0" name="winch_discount" id="winch-discount-request">
                    </form>
                </div>

            </div>
            <!-- /.box-content -->
        </div>
        <!-- /.col-xs-12 -->
    </div>
    <!-- /.row small-spacing -->

@endsection

@section('modals')
    {{-- IMAGES --}}
    <div class="modal fade" id="maintenance_images" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel-1">{{__('Images')}}</h4>
                </div>

                <div style="text-align: center; display: none;" id="show_images_loading">
                    <img src="{{asset('default-images/loading.gif')}}"
                         style="width: 41px; height: auto;">
                </div>

                <div class="modal-body" id="show_images_region">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm waves-effect waves-light" data-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{--  TAXES  --}}
    <div class="modal fade" id="invoice_taxes" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel-1">{{__('Taxes')}}</h4>
                </div>

                <div class="modal-body">
                    <table class="table table-bordered" style="width:100%">
                        <thead>
                        <tr>
                            <th scope="col">{!! __('Name') !!}</th>
                            <th scope="col">{!! __('Type') !!}</th>
                            <th scope="col">{!! __('value') !!}</th>
                            <th scope="col">{!! __('value of total after discount') !!}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($taxes as $index=>$tax)
                            <tr>
                                <td>
                                    <input type="text" class="form-control" disabled
                                           id="tax_name_{{$index+1}}" value="{{$tax->name}}">
                                </td>
                                <td>
                                    <input type="text" class="form-control" disabled
                                           id="tax_type_{{$index+1}}" value="{{$tax->tax_type}}">
                                </td>
                                <td>
                                    <input type="text" class="form-control" disabled
                                           id="tax_value_{{$index+1}}" value="{{$tax->value}}">
                                </td>
                                <td>
                                    <input type="text" class="form-control" disabled
                                           id="tax_value_after_discount_{{$index+1}}" value="0">
                                </td>
                            </tr>
                        @endforeach
                        <input type="hidden" name="tax_count" id="tax_count" value="{{$taxes->count()}}">
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">
                        {{__('Close')}}
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- PACKAGE INFO --}}
    <div class="modal fade" id="package_info_data" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-body" id="package_info">

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">
                        {{__('Close')}}
                    </button>
                </div>

            </div>

        </div>
    </div>

    {{-- WINCH INFO DATA --}}
    <div class="modal fade" id="winch_info_data" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel-1">{{__('Winch')}}</h4>
                </div>

                <div class="modal-body" >

                    @include('admin.work_cards.invoices.sample.winch.winch_details')

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">
                        {{__('Close')}}
                    </button>
                </div>

            </div>

        </div>
    </div>

    {{-- POINTS RULES --}}
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

                                    <span id="select_customer" style="display: none;"> {{__('Please Select Customer')}} </span>

                                    <div class="form-group" id="points_rules_div">
                                        <label>{{__('Rules')}}</label>
                                        <select name="points_rule_id" class="form-control js-example-basic-single" id="point_rule_id" onchange="selectPointsRule()">
                                            <option value="" data-amount="0"> {{__('Select Rule')}}</option>
                                            @foreach($pointsRules as $pointsRule)
                                                <option value="{{$pointsRule->id}}" data-amount="{{$pointsRule->amount}}"
                                                    {{ $work_card->cardInvoice && $pointsRule->amount  == $work_card->cardInvoice->points_discount ? 'selected' : ''  }}>
                                                    {{ $pointsRule->text }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

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
    {!! JsValidator::formRequest('App\Http\Requests\Admin\CardInvoiceSample\CreateCardInvoiceRequest', '.form'); !!}
    @include('admin.partial.sweet_alert_messages')
@endsection

@section('js')

    <script type="application/javascript" defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD4YXeD4XTeNieaKWam43diRHXjsGg7aVY&callback=initMap"></script>

    <script type="application/javascript" src="{{asset('assets/ckeditor/ckeditor.js')}}"></script>
    <script type="application/javascript" src="{{asset('assets/ckeditor/samples/js/sample.js')}}"></script>

    <script type="application/javascript">
        CKEDITOR.replace('editor1', {});
    </script>

    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script type="application/javascript" src="{{asset('css/ui-accordions.js')}}"></script>
    <!-- END PAGE LEVEL SCRIPTS -->

    <script type="application/javascript">

        function saveAndPrint(type) {

            $("#save_type").val(type);
        }

        function getPackageInfo(id) {

            $.ajax({
                url: "{{ route('admin:quotations.package.info')}}",

                method: 'POST',

                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },

                data: {id:id},

                success: function (data) {
                    // $("#services_by_type_loading").hide();
                    $("#package_info").html(data);
                    // $('.js-example-basic-single').select2();

                },
                error: function (jqXhr, json, errorThrown) {
                    // $("#services_by_type_loading").hide();
                    var errors = jqXhr.responseJSON;
                    swal({
                        text: errors,
                        icon: "error",
                    });
                },
            });

        }

        function activeMaintenance(id) {

            $(".active_maintenance_" + id + "_check_box").prop('disabled', true);
            $(".maintenance_type_" + id).prop('disabled', true);

            if ($('#checkbox-' + id + '-type').is(':checked')) {
                $(".active_maintenance_" + id + "_check_box").prop('disabled', false);
            }

            $(".active_maintenance_" + id + "_check_box").prop('checked', false);

        }

        function activeMaintenanceForm(id) {

            $(".active_maintenance_" + id + "_form").prop('disabled', true);


            if ($('#checkbox-' + id + '-part').is(':checked')) {

                $(".active_maintenance_" + id + "_form").prop('disabled', false);
            }
        }

        function validation() {
            event.preventDefault();
            $.ajax({
                url: "{{ route('admin:work-cards-invoices.update.validation')}}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: $("#card_invoice").serialize(),

                beforeSend: function () {
                    $("#card_invoice_loading").show();
                },

                success: function (data) {
                    $("#card_invoice_loading").hide();
                    // toastr.success('customer saved successfully');
                    $("#card_invoice").submit();
                },
                error: function (jqXhr, json, errorThrown) {
                    $("#card_invoice_loading").hide();
                    var errors = jqXhr.responseJSON;

                    swal({
                        text: errors,
                        icon: "error",
                    });

                    // toastr.error(errors);
                },
            });
        }

        function showImages(maintenance_id, card_invoice_id) {

            event.preventDefault();

            $(".removeOldImages").remove();

            $.ajax({
                url: "{{ route('admin:work-cards-invoices.show.images')}}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },

                data: {maintenance_id: maintenance_id, card_invoice_id: card_invoice_id},

                beforeSend: function () {
                    $("#show_images_loading").show();
                },

                success: function (data) {
                    $("#show_images_loading").hide();
                    $("#show_images_region").html(data);
                },

                error: function (jqXhr, json, errorThrown) {
                    $("#show_images_loading").hide();
                    var errors = jqXhr.responseJSON;

                    swal({
                        text: errors,
                        icon: "error",
                    });
                },
            });
        }

        //js code for spare parts
        function partsByTypesFooter() {

            var part_type_id = $("#parts_types_footer").val();

            $.ajax({
                url: "{{ route('admin:card.invoices.sample.parts.by.type.footer')}}",

                method: 'POST',

                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },

                data: {part_type_id: part_type_id},

                success: function (data) {

                    $(".removeOldParts").remove();


                    var option = new Option('', '{{__('Select Part')}}');
                    option.text = "{{__('Select Part')}}";
                    option.value = '';

                    $("#part_footer_id").append(option);

                    $('#part_footer_id' + ' option').addClass(function () {
                        return 'removeOldParts';
                    });

                    $.each(data.parts, function (key, modelName) {
                        var option = new Option(modelName, modelName);

                        @if( app()->getLocale() == 'ar')
                            option.text = modelName.name_ar;
                        @else
                            option.text = modelName.name_en;
                        @endif

                            option.value = modelName.id;

                        $("#part_footer_id").append(option);

                        $('#part_footer_id option').addClass(function () {
                            return 'removeOldParts';
                        });
                    });

                    var option = new Option('', '{{__('Select Barcode')}}');
                    option.text = "{{__('Select Barcode')}}";
                    option.value = '';

                    $("#barcode_footer_id").append(option);

                    $('#barcode_footer_id option').addClass(function () {
                        return 'removeOldParts';
                    });

                    $.each(data.parts, function (key, modelName) {
                        var option = new Option(modelName, modelName);
                        option.text = modelName.barcode;
                        option.value = modelName.id;

                        if (modelName.barcode) {
                            $("#barcode_footer_id").append(option);
                        }

                        $('#barcode_footer_id option').addClass(function () {
                            return 'removeOldParts';
                        });
                    });
                },
                error: function (jqXhr, json, errorThrown) {
                    // $("#packages_loading").hide();
                    var errors = jqXhr.responseJSON;
                    swal({
                        text: errors,
                        icon: "error",
                    });
                },
            });

        }

        function getSparePartsById(id) {
            event.preventDefault();

            var branch_id = '{{$work_card->branch_id}}';

            $.ajax({
                url: "{{ route('admin:card.invoices.sample.parts.by.type') }}?spare_part_id=" + id + "&branch_id=" + branch_id,
                method: 'GET',
                data: { },

                success: function (data) {
                    $('#add_parts').html(data.parts);
                }
            });
        }

        function getPartsDetails(id) {
            event.preventDefault();

            let isExist = true;
            {{--$('#add_parts_details_' + maintenance_id + ' tr').each(function () {--}}
                {{--    if ($(this).data('id') == id) {--}}
                {{--        isExist = false;--}}
                {{--        swal({--}}
                {{--            text: "{{__('You have chosen this Service before, you cannot choose it again!')}}",--}}
                {{--            icon: "warning",--}}
                {{--        })--}}
                {{--    }--}}
                {{--});--}}
            if (isExist) {

                var items_count = $("#parts_items_count").val();

                $("#parts_items_count").remove();

                $.ajax({
                    url: "{{ route('admin:card.invoices.sample.parts.details') }}",
                    method: 'POST',
                    data: {
                        items_count: items_count, id: id
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        $('#add_parts_details').append(data);
                        // partsItemsCount();
                        $('.js-example-basic-single').select2();
                    }
                });
            }
        }

        function purchaseInvoiceData(part_id, index) {

            var invoice_id = $("#purchase-invoice-" + index ).val();

            $.ajax({
                url: "{{ route('admin:card.invoices.sample.purchase.invoice.data')}}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    invoice_id: invoice_id, part_id: part_id, index:index
                },

                success: function (data) {

                    $(".tr_" + index).html(data);
                    $('.js-example-basic-single').select2();
                },

                error: function (jqXhr, json, errorThrown) {
                    // $('.ajax-load-result').hide();
                    var errors = jqXhr.responseJSON;
                    toastr.info("{{__('sorry select value')}}", '', options);
                },
            });

        }

        //Remove from parts table
        function removePartsFromTable(id) {
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

                    $('#remove_part_' + id ).remove();
                    calculateTotalQuotation();
                }
            });
        }

        // Services Section
        function getServiceById(service_type_id) {
            event.preventDefault();

            var branch_id = '{{$work_card->branch_id}}';

            $(".remove_ajax_services").remove();

            $.ajax({
                url: "{{ route('admin:card.invoices.sample.services.by.type')}}",

                method: 'POST',

                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },

                data: {service_type_id: service_type_id, branch_id: branch_id},

                beforeSend: function () {
                    $("#services_by_type_loading").show();
                },

                success: function (data) {
                    $("#services_by_type_loading").hide();
                    $("#services_data").html(data);

                },
                error: function (jqXhr, json, errorThrown) {
                    $("#services_by_type_loading").hide();
                    var errors = jqXhr.responseJSON;
                    swal({
                        text: errors,
                        icon: "error",
                    });
                },
            });

        }

        function getServiceDetails(service_id) {

            event.preventDefault();

            let isExist = true;

            $('#add_service_details tr').each(function () {
                if ($(this).data('id') == service_id) {
                    isExist = false;
                    swal({
                        text: "{{__('You have chosen this Service before, you cannot choose it again!')}}",
                        icon: "warning",
                    })
                }
            });

            if (isExist) {

                var items_count = $("#services_items_count").val();
                $("#services_items_count").remove();

                $.ajax({
                    url: "{{ route('admin:card.invoices.sample.services.details')}}",

                    method: 'POST',

                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },

                    data: {
                        service_id: service_id, items_count: items_count
                    },

                    success: function (data) {

                        $("#add_service_details").append(data);
                        $('.js-example-basic-single').select2();

                    },
                    error: function (jqXhr, json, errorThrown) {
                        $("#services_by_type_loading").hide();
                        var errors = jqXhr.responseJSON;
                        swal({
                            text: errors,
                            icon: "error",
                        });
                    },
                });
            }
        }

        function servicesByTypesFooter() {

            var service_type_id = $("#service_type_footer").val();

            $.ajax({
                url: "{{ route('admin:card.invoices.sample.services.by.type.footer')}}",

                method: 'POST',

                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },

                data: {service_type_id: service_type_id},

                success: function (data) {

                    $(".removeOldServices").remove();

                    var option = new Option('', '{{__('Select Service')}}');
                    option.text = "{{__('Select Service')}}";
                    option.value = '';

                    $("#service_footer_id").append(option);

                    $('#service_footer_id' + ' option').addClass(function () {
                        return 'removeOldServices';
                    });

                    $.each(data.services, function (key, modelName) {
                        var option = new Option(modelName, modelName);
                        option.text = modelName;
                        option.value = key;

                        $("#service_footer_id").append(option);

                        $('#service_footer_id' + ' option').addClass(function () {
                            return 'removeOldServices';
                        });
                    });
                },
                error: function (jqXhr, json, errorThrown) {
                    // $("#packages_loading").hide();
                    var errors = jqXhr.responseJSON;
                    swal({
                        text: errors,
                        icon: "error",
                    });
                },
            });

        }

        //Remove from services table
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

                    $('#remove_service_' + id).remove();
                    $('#remove_employees_service_' + id).remove();
                    calculateTotalQuotation();
                }
            });
        }

        // Js Code Packages
        function getPackageDetails(package_id) {

            event.preventDefault();

            let isExist = true;
            $('#add_package_details' + ' tr').each(function () {
                if ($(this).data('id') == package_id) {
                    isExist = false;
                    swal({
                        text: "{{__('You have chosen this Service before, you cannot choose it again!')}}",
                        icon: "warning",
                    })
                }
            });
            if (isExist) {

                var items_count = $("#packages_items_count").val();
                $("#packages_items_count").remove();

                $.ajax({
                    url: "{{ route('admin:card.invoices.sample.package.details')}}",

                    method: 'POST',

                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },

                    data: {
                        package_id: package_id, items_count: items_count
                    },

                    success: function (data) {
                        // $("#services_by_type_loading").hide();
                        $("#add_package_details").append(data);
                        $('.js-example-basic-single').select2();

                    },
                    error: function (jqXhr, json, errorThrown) {
                        var errors = jqXhr.responseJSON;
                        swal({
                            text: errors,
                            icon: "error",
                        });
                    },
                });
            }
        }

        function removePackageFromTable(id) {
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

                    $('#remove_package_' + id).remove();
                    $('#remove_employees_package_' + id).remove();
                    calculateTotalQuotation();
                }
            });
        }


        // Search in parts types
        function searchInPartsTypes() {
            var value = $("#searchInSparePartsType").val().toLowerCase();
            $("#ulSparePartsType" + " td").filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        }

        // Search in parts data
        function searchInPartsData() {
            var value = $("#searchInParts").val().toLowerCase();
            $("#ul_parts" + " td").filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        }

        // Search in Services
        function searchInServiceData() {
            var value = $("#searchInServicesData").val().toLowerCase();
            $("#services_data" + " li a").filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        }

        // Search in Services types
        function searchInServicesTypes() {
            var value = $("#searchInServicesType").val().toLowerCase();
            $("#services_types" + " li a").filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        }

        // Search in packages
        function searchInPackageData() {
            var value = $("#searchInPackagesData").val().toLowerCase();
            $("#packages_data" + " li a").filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        }

        function getWinchDistance () {

            let lat = $("#lat").val();
            let long = $("#lng").val();

            $.ajax({
                url: "{{ route('admin:card.invoices.sample.winch.distance')}}",

                method: 'POST',

                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },

                data: {lat:lat, long:long},

                success: function (data) {

                    $("#branch_distance").val(data.distance);
                    $("#winch-total").val(data.total);
                    $("#winch-total-after-discount").val(data.total);

                    winchDiscount();

                },
                error: function (jqXhr, json, errorThrown) {

                    var errors = jqXhr.responseJSON;
                    swal({
                        text: errors,
                        icon: "error",
                    });
                },
            });

        }

        function winchDiscount () {

            let discount = $("#winch-discount").val();

            if(discount == ""){
                discount = 0;
            }

            let total = $("#winch-total").val();

            if($("#winch-discount-type-amount").is(':checked')) {

                var price_after_discount = parseFloat(total) - parseFloat(discount);

            }else{

                var discount_percentage_value = parseFloat(total) * parseFloat(discount)/parseFloat(100);
                var price_after_discount = parseFloat(total) - parseFloat(discount_percentage_value);
            }

            if(price_after_discount <= 0)
                price_after_discount = 0;

            $("#winch-total-after-discount").val(price_after_discount.toFixed(2));

            invoiceTotal();
        }

        function updateWinchRequest () {

            let lng = $("#lng").val();
            let lat = $("#lat").val();
            let winch_discount = $("#winch-discount").val();

            $("#request_long").val(lng);
            $("#request_lat").val(lat);

            if($("#winch-discount-type-amount").is(':checked')) {

                $("#winch-discount-type-request").val('amount');

            }else {

                $("#winch-discount-type-request").val('percent');
            }

            if($("#active_winch").is(':checked')) {

                $("#active_winch_box").prop('checked', true);
            }

            $("#winch-discount-request").val(winch_discount);
        }

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
            google.maps.event.addListener(map, 'click', function(event) {
                //Get the location that the user clicked.
                var clickedLocation = event.latLng;
                //If the marker hasn't been added.
                if(marker === false){
                    //Create the marker.
                    marker = new google.maps.Marker({
                        position: clickedLocation,
                        map: map,
                        draggable: true //make it draggable
                    });
                    //Listen for drag events!
                    google.maps.event.addListener(marker, 'dragend', function(event){
                        markerLocation();
                    });
                } else{
                    //Marker has already been added, so just change its location.
                    marker.setPosition(clickedLocation);
                }
                //Get the marker's location.
                markerLocation();
            });
        }

        //This function will get the marker's current location and then add the lat/long
        //values to our textfields so that we can save the location.
        function markerLocation(){
            //Get location.
            var currentLocation = marker.getPosition();
            //Add lat and lng values to a field that we can save.
            document.getElementById('lat').value = currentLocation.lat(); //latitude
            document.getElementById('lng').value = currentLocation.lng(); //longitude
        }

        //Load the map when the page has finished loading.
        google.maps.event.addDomListener(window, 'load', initMap);

        function selectPointsRule () {

            let rule_amount = $("#point_rule_id").find(':selected').data("amount");

            $("#points_discount").val(rule_amount);

            let rule_id = $("#point_rule_id").find(':selected').val();

            $(".rule_id").val(rule_id);

            invoiceDiscount();
        }

    </script>



    <script type="application/javascript" src="{{asset('js/card_invoices_sample.js')}}"></script>


@endsection


