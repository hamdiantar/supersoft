@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Edit Parts') }} </title>
@endsection

@section('content')
    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"><a
                        href="{{route('admin:parts.index')}}"> {{__('Parts management')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Edit Parts')}}</li>
            </ol>
        </nav>

        {{--  box-content-wg-new --}}

        <div class="col-xs-12">

        <div class=" card box-content-wg-new bordered-all primary">
                <h4 class="box-title with-control" style="text-align: initial"><i class="fa fa-gear"></i>
                    {{__('Edit Parts')}}
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
                    <form method="post" action="{{route('admin:parts.update',$part->id)}}" class="form"
                          enctype="multipart/form-data">
                        @method('PATCH')
                        @csrf

                        @include('admin.parts.form')

                        <div class="row top-data-wg" style="box-shadow: 0 0 7px 1px #DDD;margin:5px 5px 10px;padding-top:20px">

                        @include('admin.parts.units.index')
                        </div>

                        <div class="row top-data-wg" style="box-shadow: 0 0 7px 1px #DDD;margin:5px 5px 10px;padding-top:20px">

                        @include('admin.parts.suppliers.form', ['sups' => $suppliers])
                        </div>
                        
                        <div class="form-group col-sm-12">
                            @include('admin.buttons._save_buttons')
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /.box-content -->
    <!-- /.row small-spacing -->
@endsection

@section('js')

    {!! JsValidator::formRequest('App\Http\Requests\Admin\Parts\UpdatePartsRequest', '.form'); !!}

    @include('admin.partial.sweet_alert_messages')


    <script type="application/javascript">

        function getSparTypesByBranch() {

            var branch_id = $("#branch_id").val();
            loadMainPartTypes(branch_id)
            loadSubPartTypes(branch_id ,'')
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

            for (var i = 1 ; i <= units_count; i++ ){

                if (i != 1) {

                    let unitVal = $("#unit_" + i + " option:selected").val();

                    if (unitVal ){
                        selectedUnitIds.push($("#unit_" + i + " option:selected").val()) ;
                    }
                }
            }

            $.ajax({

                type: 'post',

                url: '{{route('admin:part.units.new')}}',

                data: {
                    _token: CSRF_TOKEN,
                    units_count:units_count,
                    selectedUnitIds:selectedUnitIds
                },

                success: function (data) {

                    $("#units_count").val(data.index);
                    $(".form_new_unit").append(data.view);

                    $('.js-example-basic-single').select2();
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

        function getDefaultUnit () {

            var defaultUnit = $("#part_units_default option:selected").text();

            var defaultUnitValue = $("#part_units_default option:selected").val();

            if (defaultUnitValue) {

                $(".default_unit").val(defaultUnit.trim());

                $("#default_unit_title_" + 1).text(defaultUnit);

                $(".default_unit_title_" + 1).text(defaultUnit);

            }else {

                $(".default_unit").val('default unit');

                $(".default_unit").text('default unit');

            }
        }

        function calculatePrice (index) {

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

        function deleteOldUnit(price_id, index) {

            swal({

                title: "Delete Unit",
                text: "Are you sure want to delete this unit ?",
                icon: "warning",
                buttons: true,
                dangerMode: true,

            }).then((willDelete) => {

                if (willDelete) {

                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                    $.ajax({

                        type: 'post',

                        url: '{{route('admin:part.units.destroy')}}',

                        data: {
                            _token: CSRF_TOKEN,
                            // units_count:units_count,
                            price_id:price_id
                        },

                        success: function (data) {

                            $("#part_unit_div_" + index).fadeOut('slow');
                            $("#part_unit_div_" + index).remove();

                            swal({text: data.message, icon: "success"})
                        },

                        error: function (jqXhr, json, errorThrown) {
                            // $("#loader_save_goals").hide();
                            var errors = jqXhr.responseJSON;
                            swal({text: errors, icon: "error"})
                        }
                    });

                }
            });

        }

        function updateUnit(price_id, index) {

            swal({

                title: "Update Unit",
                text: "Are you sure want to update this unit ?",
                icon: "warning",
                buttons: true,
                dangerMode: true,

            }).then((willDelete) => {

                if (willDelete) {

                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                    var qty = $("#qty_" + index).val();

                    var unit = $("#unit_" + index).val();
                    var selling_price = $("#selling_price_" + index).val();
                    var purchase_price = $("#purchase_price_" + index).val();
                    var less_selling_price = $("#less_selling_price_" + index).val();
                    var service_selling_price = $("#service_selling_price_" + index).val();
                    var less_service_selling_price = $("#less_service_selling_price_" + index).val();
                    var maximum_sale_amount = $("#maximum_sale_amount_" + index).val();
                    var minimum_for_order = $("#minimum_for_order_" + index).val();
                    var biggest_percent_discount = $("#biggest_percent_discount_" + index).val();
                    var biggest_amount_discount = $("#biggest_amount_discount_" + index).val();
                    var last_selling_price = $("#last_selling_price_" + index).val();
                    var last_purchase_price = $("#last_purchase_price_" + index).val();
                    var barcode = $("#barcode_" + index).val();

                    $.ajax({

                        type: 'post',

                        url: '{{route('admin:part.units.update')}}',

                        data: {
                            _token: CSRF_TOKEN,
                            // units_count:units_count,
                            price_id:price_id,
                            quantity:qty,
                            unit_id:unit,
                            selling_price:selling_price,
                            purchase_price:purchase_price,
                            less_selling_price:less_selling_price,
                            service_selling_price:service_selling_price,
                            less_service_selling_price:less_service_selling_price,
                            maximum_sale_amount:maximum_sale_amount,
                            minimum_for_order:minimum_for_order,
                            biggest_percent_discount:biggest_percent_discount,
                            biggest_amount_discount:biggest_amount_discount,
                            last_selling_price:last_selling_price,
                            last_purchase_price:last_purchase_price,
                            barcode:barcode,
                        },

                        success: function (data) {

                            swal({text: data.message, icon: "success"})
                        },

                        error: function (jqXhr, json, errorThrown) {
                            // $("#loader_save_goals").hide();
                            var errors = jqXhr.responseJSON;
                            swal({text: errors, icon: "error"})
                        }
                    });

                }
            });

        }

        function openPriceSegment(id, index) {

            if ($('#price_segment_checkbox_' + index + '_'+ id).is(":checked")) {

                $("#segment_" + index + '_'+  id).prop('disabled', false);
                $("#price_segment_" + index + '_'+  id).prop('disabled', false);
                $("#sales_price_segment_" + index + '_'+  id).prop('disabled', false);
                $("#maintenance_price_segment_" + index + '_'+  id).prop('disabled', false);

            }else {
                $("#segment_" + index + '_'+  id).prop('disabled', true);
                $("#price_segment_" + index + '_'+  id).prop('disabled', true);
                $("#sales_price_segment_" + index + '_'+  id).prop('disabled', true);
                $("#maintenance_price_segment_" + index + '_'+  id).prop('disabled', true);
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
                    order: order,
                    part_id:'{{$part->id}}'
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

        function getUnitName (index) {

            let selectedUnit = $("#unit_" + index + " option:selected").text();

            $("#default_unit_title_" + index).text(selectedUnit);

            $(".default_unit_title_" + index).text(selectedUnit);
        }

        function newPartPriceSegment (index) {

            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            let part_price_segments_count = $("#part_price_" + index + "_segments_count").val();

            $.ajax({

                type: 'post',

                url: '{{route('admin:parts.new.price.segments')}}',

                data: {
                    _token: CSRF_TOKEN,
                    index:index,
                    part_price_segments_count:part_price_segments_count
                },

                success: function (data) {

                    $("#part_price_"+ index + "_segments").append(data.view);
                    $("#part_price_" + index + "_segments_count").val(data.key)
                },

                error: function (jqXhr, json, errorThrown) {
                    // $("#loader_save_goals").hide();
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        }

        function deleteOldPartPriceSegment (index, key, partPriceSegmentId) {

            swal({

                title: "Delete Price Segment",
                text: "Are you sure want to delete this Segment ?",
                icon: "warning",
                buttons: true,
                dangerMode: true,

            }).then((willDelete) => {

                if (willDelete) {

                    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                    $.ajax({

                        type: 'post',

                        url: '{{route('admin:parts.delete.price.segments')}}',

                        data: {
                            _token: CSRF_TOKEN,
                            partPriceSegmentId:partPriceSegmentId
                        },
                        success: function (data) {

                            $("#price_" + index + "_segment_" + key).remove();
                            swal({text: data.message, icon: "success"})
                        },

                        error: function (jqXhr, json, errorThrown) {
                            // $("#loader_save_goals").hide();
                            var errors = jqXhr.responseJSON;
                            swal({text: errors, icon: "error"})
                        }
                    });
                }
            });
        }

        function removePartPriceSegment (index, key) {

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

        $( document ).ready(function() {
            subPartsTypes()
        });

    </script>



@endsection
