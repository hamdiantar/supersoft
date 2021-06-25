@extends('web.layout.app')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('Create Quotation') }} </title>
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

    <div class="row row-inline-block  small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('web:dashboard')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item">
                    <a href="{{route('web:quotations.index')}}"> {{__('Quotations')}}</a>
                </li>
                <li class="breadcrumb-item active"> {{__('Create Quotation')}}</li>
            </ol>
        </nav>

        <div class="col-xs-12">
            <div class=" card box-content-wg-new bordered-all primary">
                <h1 class="box-title bg-info" style="text-align: initial"><i class="fa fa-file-o"></i>
                    {{__('Create Quotation')}}
                    <span class="controls hidden-sm hidden-xs pull-left">

                <button class="control text-white" style="background:none;border:none;font-size:12px">
                    {{__('Save')}}
                    <img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px"
                         src="{{asset('assets/images/f1.png')}}"></button>

                        <button class="control text-white" style="background:none;border:none;font-size:12px">

                            {{__('Reset')}}

                            <img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f2.png')}}"></button>

                        <button class="control text-white" style="background:none;border:none;font-size:12px">
                                {{__('Back')}}
                                <img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f3.png')}}">
                            </button>
						</span>
                </h1>

                <div class="box-content">
                    <form id="quotation_forms" action="{{route('web:quotations.store')}}" class="form" method="post">
                        @csrf
                        @method('post')

                        <div class="row">

                            @include('web.quotations.info_section')

                            <div class="col-md-3">
                                <div class="form-group has-feedback">
                                    <label for="inputPhone" class="control-label">
                                        {{__('Spare Parts')}}
                                    </label>

                                    <input type="checkbox" id="spar_parts" name="parts_box" onclick="getPartsSection(); showTotalDetails()">

                                    <img src="{{asset('default-images/loading.gif')}}" id="parts_loading"
                                         style=" height: 32px; width: 32px;margin-top: -11px; display: none;">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group has-feedback">
                                    <label for="inputPhone" class="control-label">{{__('Services')}}</label>
                                    <input type="checkbox" id="services_section_checkbox"
                                           name="services_box"
                                           onclick="getServicesSection(); showTotalDetails()">

                                    <img src="{{asset('default-images/loading.gif')}}" id="services_loading"
                                         style=" height: 32px; width: 32px;margin-top: -11px; display: none;">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group has-feedback">
                                    <label for="inputPhone" class="control-label">{{__('Service Packages')}}</label>
                                    <input type="checkbox" id="package_section_checkbox"
                                           name="packages_box"
                                           onclick="getPackagesSection(); showTotalDetails()">

                                    <img src="{{asset('default-images/loading.gif')}}" id="packages_loading" style=" height: 32px; width: 32px;margin-top: -11px; display: none;">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group has-feedback">
                                    <label for="inputPhone" class="control-label">
                                        {{__('Winch')}}
                                    </label>

                                    <input type="checkbox" id="winch_section_checkbox" name="winch_box" onclick="getWinchSection(); showTotalDetails()">

                                    <img src="{{asset('default-images/loading.gif')}}" id="winch_loading" style=" height: 32px; width: 32px;margin-top: -11px; display: none;">
                                </div>
                            </div>
                        </div>

                        @include('web.quotations.parts.spareParts')
                        @include('web.quotations.services.services')
                        @include('web.quotations.packages.packages')
                        @include('web.quotations.winch.winch_details')
                        @include('web.quotations.total_details')

                        <div class="form-group col-sm-12">
                            <input type="hidden" name="save_type" id="save_type">

                            <button id="btnsave" type="submit" class="btn hvr-rectangle-in saveAdd-wg-btn  "
                                    onclick="saveAndPrint('save')">
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

                            <button type="submit" class="btn hvr-rectangle-in saveAdd-wg-btn"
                                    onclick="saveAndPrint('save_and_print')">
                                <i class="ico ico-left fa fa-print"></i>
                                {{__('Save and print invoice')}}
                            </button>

                        </div>

                    </form>
                </div>
            </div>
        </div>



    </div>

    @include('web.quotations.taxes.index')

@endsection

@section('modals')

    <div class="modal fade" id="package_info_data" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-body" id="package_info">

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
    {!! JsValidator::formRequest('App\Http\Requests\Web\Quotation\CreateQuotationRequest', '.form'); !!}
    @include('admin.partial.sweet_alert_messages')

@endsection


@section('js')

    <script type="application/javascript" defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD4YXeD4XTeNieaKWam43diRHXjsGg7aVY&callback=initMap"></script>

    <script type="application/javascript">

        function saveAndPrint(type) {

            $("#save_type").val(type);
        }

        // NOT WORK
        function setBranchId() {
            let branchId = localStorage.getItem('branch_id');
            $('#setBranchId').val(branchId)
            $.ajax({
                url: "{{ route('admin:customers.customerCategory') }}?id=" + branchId,
                method: 'GET',
                success: function (data) {
                    $('#customerCategory').html(data.customerCategory);
                }
            });
        }

        // NOT WORK
        function formUrl(e) {
            if (!localStorage.getItem('branch_id')) {
                localStorage.setItem('branch_id', '');
            }
            let branchId = e.target.value;
            localStorage.setItem('branch_id', branchId);
            $('#quotation_forms').attr('action', '{{url()->current()}}');
            $('#quotation_forms').attr('method', 'get');
        }

        {{-- add Customer js  --}} // NOT WORK
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
        // NOT WORK
        $("#city").change(function () {
            $.ajax({
                url: "{{ route('admin:city.areas') }}?city_id=" + $(this).val(),
                method: 'GET',
                success: function (data) {
                    $('#area').html(data.html);
                }
            });
        });

        // NOT WORK
        function getCompanyDataForQuotations() {

            var type = $("#customer_type").val();

            if (type == 'person') {

                $(".company_data").hide();
            } else {
                $(".company_data").show();
            }
        }

        // NOT WORK
        function addCustomerQuotations() {
            event.preventDefault();
            $.ajax({
                url: "{{ route('admin:sales.invoices.add.customer')}}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: $("#add_customer_form_e").serialize(),
                success: function (data) {
                    var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?customerId=' + data.customerId;
                    window.history.pushState({path: newurl}, '', newurl);
                    $('.customerIDFromController').val(data.customerId)
                    $('#customerName').html(data.customerName)
                    $('#customerAddress').html(data.customerAddress)
                    $('#customerPhone').html(data.customerPhone)
                    $('#customerType').html(data.customerType)
                    $('#carsCount').html(data.carsCount)
                    $('#customer_data').html(data.customerData);
                    toastr.success('supplier saved successfully');
                    $('#add_customer_form_e').hide();
                    $('#modal1Title').text('Add Cars');
                    $('.remodal').css('max-width', '1090px');
                    $('#CustomerCarFrom').show();
                    $('.js-example-basic-single').select2();
                },
                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    toastr.error(errors);
                },
            });
        }

        //js code for spare parts
        function getPartsSection() {

            var branch_id = $("#branch_id").val();

            if (branch_id == "") {
                swal({
                    text: "{{__('please select branch!')}}",
                    icon: "error",
                });

                $("#spar_parts").prop('checked', false);

                return false;
            }

            calculateTotalQuotation();

            if ($("#spar_parts").is(":checked")) {

                if ($("#ajax_parts_box").length) {
                    $("#ajax_parts_box").slideDown('slow');

                } else {

                    $.ajax({
                        url: "{{ route('web:quotations.get.parts')}}",

                        method: 'POST',

                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },

                        data: {branch_id: branch_id},

                        beforeSend: function () {
                            $("#parts_loading").show();
                        },

                        success: function (data) {
                            $("#parts_loading").hide();
                            $("#sparePartsCard").html(data);
                            $('.js-example-basic-single').select2();
                        },
                        error: function (jqXhr, json, errorThrown) {
                            $("#parts_loading").hide();
                            var errors = jqXhr.responseJSON;
                            swal({
                                text: errors,
                                icon: "error",
                            });
                        },
                    });
                }


            } else {
                $("#ajax_parts_box").slideUp('slow');
            }
        }

        function partsByTypesFooter() {

            var part_type_id = $("#parts_types_footer").val();

            $.ajax({
                url: "{{ route('web:quotations.parts.by.type.footer')}}",

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

                    $('#part_footer_id option').addClass(function () {
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
                    $("#packages_loading").hide();
                    var errors = jqXhr.responseJSON;
                    swal({
                        text: errors,
                        icon: "error",
                    });
                },
            });

        }

        function getSparePatrtsById(id) {
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
            {{--$('#add_parts_details tr').each(function () {--}}
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
                    url: "{{ route('web:quotations.parts.details') }}",
                    method: 'POST',
                    data: {items_count: items_count, id: id},
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        $('#add_parts_details').append(data);
                        $('.js-example-basic-single').select2();
                    }
                });
            }
        }

        function purchaseInvoiceData(part_id, index) {

            var invoice_id = $("#purchase-invoice-" + index).val();

            $.ajax({
                url: "{{ route('web:quotations.get.purchase.invoice.data')}}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {invoice_id: invoice_id, part_id: part_id, index: index},
                success: function (data) {
                    $(".tr_" + index).html(data);
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

        function searchInPartsTypesItems() {

            var value = $("#searchInSparePartsType").val().toLowerCase();

            $(".searchResultSparePartsType td").filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        }

        function searchInPartsItems() {

            var value = $("#searchInParts").val().toLowerCase();
            $(".search_in_parts td").filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
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

                    $('#remove_part_' + id).remove();
                    calculateTotalQuotation();
                }
            });
        }

        // Services Section
        function getServicesSection() {

            var branch_id = $("#branch_id").val();

            if (branch_id == "") {
                swal({
                    text: "{{__('please select branch!')}}",
                    icon: "error",
                });

                $("#services_section_checkbox").prop('checked', false);

                return false;
            }

            calculateTotalQuotation();

            if ($("#services_section_checkbox").is(":checked")) {

                if ($("#ajax_service_box").length) {
                    $("#ajax_service_box").slideDown('slow');

                } else {

                    $.ajax({
                        url: "{{ route('web:quotations.get.services')}}",

                        method: 'POST',

                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },

                        data: {branch_id: branch_id},

                        beforeSend: function () {
                            $("#services_loading").show();
                        },

                        success: function (data) {
                            $("#services_loading").hide();
                            $("#services_section").html(data);
                            $('.js-example-basic-single').select2();
                        },
                        error: function (jqXhr, json, errorThrown) {
                            $("#services_loading").hide();
                            var errors = jqXhr.responseJSON;
                            swal({
                                text: errors,
                                icon: "error",
                            });
                        },
                    });
                }
            } else {

                $("#ajax_service_box").slideUp('slow');

            }
        }

        function getServiceById(service_type_id) {
            event.preventDefault();

            var branch_id = $("#branch_id").val();

            if (branch_id == "") {
                swal({
                    text: "{{__('please select branch!')}}",
                    icon: "error",
                });

                $("#services_section_checkbox").prop('checked', false);

                return false;
            }

            $(".remove_ajax_services").remove();

            $.ajax({
                url: "{{ route('web:quotations.get.services.by.type')}}",

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
                    url: "{{ route('web:quotations.get.services.details')}}",

                    method: 'POST',

                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },

                    data: {service_id: service_id, items_count: items_count},

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
                url: "{{ route('web:quotations.services.by.type.footer')}}",

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

                    $('#service_footer_id option').addClass(function () {
                        return 'removeOldServices';
                    });

                    $.each(data.services, function (key, modelName) {
                        var option = new Option(modelName, modelName);
                        option.text = modelName;
                        option.value = key;

                        $("#service_footer_id").append(option);

                        $('#service_footer_id option').addClass(function () {
                            return 'removeOldServices';
                        });
                    });
                },
                error: function (jqXhr, json, errorThrown) {
                    $("#packages_loading").hide();
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
                    calculateTotalQuotation();
                }
            });
        }

        // Js Code Packages
        function getPackagesSection() {
            var branch_id = $("#branch_id").val();

            if (branch_id == "") {
                swal({
                    text: "{{__('please select branch!')}}",
                    icon: "error",
                });

                $("#package_section_checkbox").prop('checked', false);

                return false;
            }

            calculateTotalQuotation();

            if ($("#package_section_checkbox").is(":checked")) {

                if ($("#ajax_package_box").length) {

                    $("#ajax_package_box").slideDown('slow');

                } else {

                    $.ajax({
                        url: "{{ route('web:quotations.get.packages')}}",

                        method: 'POST',

                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },

                        data: {branch_id: branch_id},

                        beforeSend: function () {
                            $("#packages_loading").show();
                        },

                        success: function (data) {
                            $("#packages_loading").hide();
                            $("#packages_section").html(data);
                            $('.js-example-basic-single').select2();
                        },
                        error: function (jqXhr, json, errorThrown) {
                            $("#packages_loading").hide();
                            var errors = jqXhr.responseJSON;
                            swal({
                                text: errors,
                                icon: "error",
                            });
                        },
                    });
                }

            } else {

                $("#ajax_package_box").hide();
            }
        }

        function getPackageDetails(package_id) {

            event.preventDefault();

            let isExist = true;
            $('#add_package_details tr').each(function () {
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
                    url: "{{ route('web:quotations.package.details')}}",

                    method: 'POST',

                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },

                    data: {package_id: package_id, items_count: items_count},

                    success: function (data) {
                        // $("#services_by_type_loading").hide();
                        $("#add_package_details").append(data);
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

        function getPackageInfo(id) {

            $.ajax({
                url: "{{ route('web:quotations.package.info')}}",

                method: 'POST',

                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },

                data: {id: id},

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
                    calculateTotalQuotation();
                }
            });
        }

        function showTotalDetails() {

            var part_section = $("#spar_parts").is(":checked");
            var service_section = $("#services_section_checkbox").is(":checked");
            var package_section = $("#package_section_checkbox").is(":checked");
            var winch_section = $("#winch_section_checkbox").is(":checked");

            if (part_section || service_section || package_section || winch_section) {
                $("#quotation_total_details").show();
            } else {

                $("#quotation_total_details").hide();
            }
        }

        function store() {

            $.ajax({

                url: "{{ route('web:quotations.store')}}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },

                data: $("#quotation_forms").serialize(),

                success: function (data) {

                    swal({
                        text: "{{__('Quotation created successfully!')}}",
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

    //  JS CODE WINCH
        function getWinchSection() {

            var branch_id = $("#branch_id").val();

            if (branch_id == "") {
                swal({
                    text: "{{__('please select branch!')}}",
                    icon: "error",
                });

                $("#package_section_checkbox").prop('checked', false);

                return false;
            }

            calculateTotalQuotation();

            if ($("#winch_section_checkbox").is(":checked")) {

                if ($("#ajax_winch_box").length) {

                    $("#ajax_winch_box").slideDown('slow');

                } else {

                    $("#ajax_winch_box").slideDown('slow');
                }

            } else {

                $("#ajax_winch_box").slideUp('slow');
            }
        }

        function getWinchDistance () {

           let lat = $("#lat").val();
           let long = $("#lng").val();

            $.ajax({
                url: "{{ route('web:quotations.winch.get.distance')}}",

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

            calculateTotalQuotation();

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

    </script>

    <script type="application/javascript" src="{{asset('js/quotation.js')}}"></script>
    <script type="application/javascript" src="{{asset('js/quotation_services.js')}}"></script>
    <script type="application/javascript" src="{{asset('js/quotation_packages.js')}}"></script>


@endsection

