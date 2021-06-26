@extends('admin.layouts.app')


@section('title')
    <title>{{ __('Create Part') }} </title>
@endsection

@section('content')



    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"><a
                        href="{{route('admin:parts.index')}}"> {{__('Parts management')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Create Part')}}</li>
            </ol>
        </nav>

        <div class="col-xs-12">

            {{--  box-content-wg-new  --}}

            <div class=" card box-content-wg-new bordered-all primary">

                <h4 class="box-title with-control" style="text-align: initial"><i class="fa fa-gear"></i>
                    {{__('Create Part')}}
                    <span class="controls hidden-sm hidden-xs pull-left">
                      <button class="control text-white"
                              style="background:none;border:none;font-size:14px;font-weight:normal !important;">{{__('Save')}}
                      <img class="img-fluid" style="width:40px;height:40px;margin-top:-15px;margin-bottom:-13px"
                           src="{{asset('assets/images/f1.png')}}">
                  </button>
                        <button class="control text-white"    style="background:none;border:none;font-size:14px;font-weight:normal !important;">
                            {{__('Reset')}}
                            <img class="img-fluid" style="width:40px;height:40px;margin-top:-15px;margin-bottom:-13px"
                                 src="{{asset('assets/images/f2.png')}}"></button>
							<button class="control text-white"    style="background:none;border:none;font-size:14px;font-weight:normal !important;"> {{__('Back')}} <img
                                    class="img-fluid"
                                    style="width:40px;height:40px;margin-top:-15px;margin-bottom:-13px"
                                    src="{{asset('assets/images/f3.png')}}"></button>
						</span>
                </h4>

                <div class="box-content">
                    <form method="post" action="{{route('admin:parts.store')}}" class="form"
                          enctype="multipart/form-data">
                        @csrf
                        @method('post')

                        @include('admin.parts.form')

                        <div class="row top-data-wg" style="box-shadow: 0 0 7px 1px #DDD;margin:5px 5px 10px;padding-top:20px">

                        @include('admin.parts.units.index')
                        </div>
                        
                        <div class="row top-data-wg" style="box-shadow: 0 0 7px 1px #DDD;margin:5px 5px 10px;padding-top:20px">

                        @include('admin.parts.suppliers.supplier')
                        </div>
                        
                        <div class="form-group col-sm-12">
                            @include('admin.buttons._save_buttons')
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

    {!! JsValidator::formRequest('App\Http\Requests\Admin\Parts\CreatePartsRequest', '.form'); !!}

    @include('admin.partial.sweet_alert_messages')


    <script type="application/javascript">

        function changeBranch() {

            let branch_id = $('#branch_id').find(":selected").val();
            window.location.href = "{{route('admin:parts.create')}}" + "?branch_id=" + branch_id;
        }

        function getSparTypesByBranch() {

            var branch_id = $("#branch_id").val();
            loadMainPartTypes(branch_id)
            loadSubPartTypes(branch_id, '')
            $.ajax({
                url: "{{ route('admin:part.type.by.branch')}}",
                method: 'GET',
                data: {branch_id: branch_id},
                success: function (data) {

                    $('.js-example-basic-single').select2();

                    $(".removeToNewData").remove();

                    // $.each(data.partTypes, function (key, modelName) {

                    //     if (!modelName.spare_part_id) {

                    //         var option = new Option(modelName, modelName);
                    //         option.text = modelName['type_' + data.lang];
                    //         option.value = modelName['id'];

                    //         $("#parts_types_options").append(option);

                    //         $('.data_by_branch option').addClass(function () {
                    //             return 'removeToNewData';
                    //         });
                    //     }
                    // });

                    // $.each(data.partTypes, function (key, modelName) {

                    //     if (modelName.spare_part_id) {

                    //         var option = new Option(modelName, modelName);
                    //         option.text = modelName['type_' + data.lang];
                    //         option.value = modelName['id'];

                    //         $("#sub_parts_types_options").append(option);

                    //         $('.data_by_branch option').addClass(function () {
                    //             return 'removeToNewData';
                    //         });
                    //     }
                    // });

                    $.each(data.supplier, function (key, modelName) {

                        var option = new Option(modelName, modelName);
                        option.text = modelName;
                        option.value = key;

                        $("#suppliers_options").append(option);

                        $('.data_by_branch option').addClass(function () {
                            return 'removeToNewData';
                        });
                    });

                    $.each(data.parts, function (key, modelName) {

                        var option = new Option(modelName, modelName);
                        option.text = modelName;
                        option.value = key;

                        $("#parts_options").append(option);

                        $('.data_by_branch option').addClass(function () {
                            return 'removeToNewData';
                        });
                    });

                    $.each(data.stores, function (key, modelName) {

                        var option = new Option(modelName, modelName);

                        option.text = modelName['name_' + data.lang];
                        option.value = modelName['id'];

                        $("#store_id").append(option);

                        $('.data_by_branch option').addClass(function () {
                            return 'removeToNewData';
                        });
                    });

                    $.each(data.taxes, function (key, modelName) {

                        var option = new Option(modelName, modelName);

                        option.text = modelName['name_' + data.lang];
                        option.value = modelName['id'];

                        $("#tax_id").append(option);

                        $('.data_by_branch option').addClass(function () {
                            return 'removeToNewData';
                        });
                    });

                }
            });
        }

        function newUnit() {

            var defaultUnit = $("#part_units_default option:selected").val();

            if (defaultUnit == '') {

                swal("{{__('sorry please select default unit')}}", {icon: "error",});
                return false;
            }

            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            let units_count = $("#units_count").val();

            var defaultUnitValue = $("#part_units_default option:selected").val();

            var selectedUnitIds = [defaultUnitValue];

            for (var i = 1; i <= units_count; i++) {

                if (i != 1) {

                    let unitVal = $("#unit_" + i + " option:selected").val();

                    if (unitVal) {
                        selectedUnitIds.push($("#unit_" + i + " option:selected").val());
                    }
                }
            }

            $.ajax({

                type: 'post',

                url: '{{route('admin:part.units.new')}}',

                data: {
                    _token: CSRF_TOKEN,
                    units_count: units_count,
                    selectedUnitIds: selectedUnitIds
                },

                success: function (data) {

                    $("#units_count").val(data.index);
                    $(".form_new_unit").append(data.view);
                    $('.js-example-basic-single').select2();

                    let selectedUnit = $("#part_units_default" + " option:selected").text();
                    $(".default_unit_title").text(selectedUnit);

                },

                error: function (jqXhr, json, errorThrown) {
                    // $("#loader_save_goals").hide();
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        }

        function deleteUnit(index) {

            swal({

                title: "Delete Unit",
                text: "Are you sure want to delete this unit ?",
                icon: "warning",
                buttons: true,
                dangerMode: true,

            }).then((willDelete) => {

                if (willDelete) {

                    $("#part_unit_div_" + index).remove();
                }
            });
        }

        function hideBody(index) {

            $("#unit_form_body_" + index).slideToggle('slow');
        }

        function getDefaultUnit() {

            var defaultUnit = $("#part_units_default option:selected").text();

            var defaultUnitValue = $("#part_units_default option:selected").val();

            if (defaultUnitValue) {

                $("#default_unit_val").val(defaultUnitValue);

                $(".default_unit").val(defaultUnit.trim());

                $("#default_unit_title_" + 1).text(defaultUnit);

            } else {

                $(".default_unit").val('default unit');

                $(".default_unit").text('default unit');
            }
        }

        function calculatePrice(index) {

            if (index == 1) {
                return false;
            }

            var defaultIndex = 1;

            var default_selling_price = $("#selling_price_" + defaultIndex).val();
            var default_purchase_price = $("#purchase_price_" + defaultIndex).val();
            var default_less_selling_price = $("#less_selling_price_" + defaultIndex).val();
            var default_service_selling_price = $("#service_selling_price_" + defaultIndex).val();
            var default_less_service_selling_price = $("#less_service_selling_price_" + defaultIndex).val();

            var qty = $("#qty_" + index).val();

            if (!qty) {
                qty = 0;
            }

            var new_selling_price = parseFloat(default_selling_price) * parseFloat(qty);
            var new_purchase_price = parseFloat(default_purchase_price) * parseFloat(qty);
            var new_less_selling_price = parseFloat(default_less_selling_price) * parseFloat(qty);
            var new_service_selling_price = parseFloat(default_service_selling_price) * parseFloat(qty);
            var new_less_service_selling_price = parseFloat(default_less_service_selling_price) * parseFloat(qty);

            $("#selling_price_" + index).val(new_selling_price);
            $("#purchase_price_" + index).val(new_purchase_price);
            $("#less_selling_price_" + index).val(new_less_selling_price);
            $("#service_selling_price_" + index).val(new_service_selling_price);
            $("#less_service_selling_price_" + index).val(new_less_service_selling_price);
        }

        function openPriceSegment(id, index) {

            if ($('#price_segment_checkbox_' + index + '_' + id).is(":checked")) {

                $("#price_segment_" + index + '_' + id).prop('disabled', false);
                $("#segment_" + index + '_' + id).prop('disabled', false);
                $("#sales_price_segment_" + index + '_' + id).prop('disabled', false);
                $("#maintenance_price_segment_" + index + '_' + id).prop('disabled', false);

            } else {

                $("#price_segment_" + index + '_' + id).prop('disabled', true);
                $("#segment_" + index + '_' + id).prop('disabled', true);
                $("#sales_price_segment_" + index + '_' + id).prop('disabled', true);
                $("#maintenance_price_segment_" + index + '_' + id).prop('disabled', true);
            }
        }

        function subPartsTypes() {

            let spare_parts_ids = $("#parts_types_options").val(), branch_id = $("#branch_id").val()
            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            let order = $('#parts_types_options').find(":selected").data('order');

            $.ajax({

                type: 'post',
                url: '{{route('admin:sub.parts.get')}}',
                data: {
                    _token: CSRF_TOKEN,
                    spare_parts_ids: spare_parts_ids,
                    order: order
                },

                success: function (data) {

                    $("#newSubPartsTypes").html(data.view);
                    $('.js-example-basic-single').select2();
                },

                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        }

        {{--function loadMainPartTypes(branch_id) {--}}
        {{--    $.ajax({--}}
        {{--        url: "{{ route('admin:part-types.main-part-types')}}",--}}
        {{--        method: 'GET',--}}
        {{--        data: {branch_id: branch_id},--}}
        {{--        success: function (response) {--}}
        {{--            $("#parts_types_options").html(response.options)--}}
        {{--            $("#parts_types_options").select2()--}}
        {{--        }--}}
        {{--    })--}}
        {{--}--}}

        {{--function loadSubPartTypes(branch_id, main_part_id) {--}}
        {{--    const data = {branch_id: branch_id}--}}
        {{--    if (main_part_id) data.type_ids = main_part_id.join(",")--}}
        {{--    $.ajax({--}}
        {{--        url: "{{ route('admin:part-types.sub-part-types')}}",--}}
        {{--        method: 'GET',--}}
        {{--        data: data,--}}
        {{--        success: function (response) {--}}
        {{--            $("#sub_parts_types_options").html(response.options)--}}
        {{--            $("#sub_parts_types_options").select2()--}}
        {{--        }--}}
        {{--    })--}}
        {{--}--}}

        function getUnitName(index) {

            let selectedUnit = $("#unit_" + index + " option:selected").text();

            $("#default_unit_title_" + index).text(selectedUnit);
        }

        function newPartPriceSegment(index) {

            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            let part_price_segments_count = $("#part_price_" + index + "_segments_count").val();

            $.ajax({

                type: 'post',

                url: '{{route('admin:parts.new.price.segments')}}',

                data: {
                    _token: CSRF_TOKEN,
                    index: index,
                    part_price_segments_count: part_price_segments_count
                },

                success: function (data) {

                    $("#part_price_" + index + "_segments").append(data.view);
                    $("#part_price_" + index + "_segments_count").val(data.key)
                },

                error: function (jqXhr, json, errorThrown) {
                    // $("#loader_save_goals").hide();
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        }

        function removePartPriceSegment(index, key) {

            swal({

                title: "Delete Price Segment",
                text: "Are you sure want to delete this Segment ?",
                icon: "warning",
                buttons: true,
                dangerMode: true,

            }).then((willDelete) => {

                if (willDelete) {

                    $("#price_" + index + "_segment_" + key).remove();
                }
            });

        }

        function newSupplier() {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            let supplier_count = $("#supplier_count").val();
            let branchId = $("#branch_id").val();
            @if(authIsSuperAdmin())
            if (!is_numeric(branchId)) {
                swal({text: '{{__('please select the branch first')}}', icon: "warning"})
                return false;
            }
            @endif
            $.ajax({
                type: 'post',
                url: '{{route('admin:parts.new.supplier')}}',
                data: {
                    _token: CSRF_TOKEN,
                    supplier_count: supplier_count,
                    branchId: branchId,
                },
                success: function (data) {
                    $("#supplier_count").val(data.index);
                    $("#suppliers_ids" + data.index).select2()
                    $(".form_new_supplier").append(data.view);
                    $("#suppliers_ids" + data.index).select2()
                },
                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        }

        function deleteSupplier(index) {
            swal({
                title: "{{__('Delete')}}",
                text: "{{__('Are you sure want to Delete?')}}",
                type: "success",
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
                    $(".supplier-" + index).remove();
                }
            });
        }

        function getSupplierById(index) {
            let id = $('#suppliers_ids' + index).val();
            $.ajax({
                type: 'get',
                url: "{{ route('admin:parts.getBYId.supplier') }}?supplier_id=" + id,
                success: function (data) {
                    if (data.status) {
                        swal({text: "{{__('Please Select Valid Supplier')}}", icon: "error"})
                    }
                    $("#phone" + index).val(data.phone)
                    $("#address" + index).val(data.address)
                },
                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        }

        function defaultUnit() {

            let selectedUnit = $("#part_units_default" + " option:selected").text();
            $(".default_unit_title").text(selectedUnit);
        }
    </script>

@endsection
