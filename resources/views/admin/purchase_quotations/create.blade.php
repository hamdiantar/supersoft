@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Create Purchase Quotation') }} </title>
@endsection

{{--@section('style')--}}
{{--    --}}
{{--@endsection--}}

@section('content')

    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"><a
                        href="{{route('admin:purchase-quotations.index')}}"> {{__('Purchase Quotation')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Create Purchase Quotation')}}</li>
            </ol>
        </nav>

        <div class="col-xs-12">

            {{--  box-content-wg-new  --}}

            <div class=" card box-content-wg-new bordered-all primary">

                <h4 class="box-title with-control" style="text-align: initial"><i class="fa fa-file-text-o"></i>
                    {{__('Create Purchase Quotation')}}
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
                    <form method="post" action="{{route('admin:purchase-quotations.store')}}" class="form"
                          enctype="multipart/form-data">
                        @csrf
                        @method('post')

                        @include('admin.purchase_quotations.form')

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

@section('modals')

    <div id="modals_part_types">

    </div>

@endsection

@section('js-validation')

    {!! JsValidator::formRequest('App\Http\Requests\Admin\PurchaseQuotation\CreateRequest', '.form'); !!}

    @include('admin.partial.sweet_alert_messages')

@endsection

@section('js')

    <script src="{{asset('js/purchase_quotation/index.js')}}"></script>

    <script type="application/javascript">

        function changeBranch () {

            let branch_id = $('#branch_id').find(":selected").val();
            window.location.href = "{{route('admin:purchase-quotations.create')}}" + "?branch_id=" + branch_id ;
        }

        function dataByMainType() {

            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            let branch_id = $('#branch_id').find(":selected").val();
            let main_type_id = $('#main_types_select').find(":selected").val();
            let order = $('#main_types_select').find(":selected").data('order');

            $.ajax({

                type: 'post',

                url: '{{route('admin:parts.common.filter.data.by.main.type')}}',

                data: {
                    _token: CSRF_TOKEN,
                    branch_id: branch_id,
                    main_type_id: main_type_id,
                    order: order,
                },

                success: function (data) {

                    $("#sub_types").html(data.sub_types);
                    $("#parts").html(data.parts);

                    $('.js-example-basic-single').select2();
                },

                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        }

        function dataBySubType() {

            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            let branch_id = $('#branch_id').find(":selected").val();
            let sub_type_id = $('#sub_types_select').find(":selected").val();

            $.ajax({

                type: 'post',

                url: '{{route('admin:parts.common.filter.data.by.sub.type')}}',

                data: {
                    _token: CSRF_TOKEN,
                    branch_id: branch_id,
                    sub_type_id: sub_type_id
                },

                success: function (data) {

                    $("#parts").html(data.parts);
                    $('.js-example-basic-single').select2();
                },

                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        }

        function selectPart() {

            if (!checkBranchValidation()) {
                swal({text: '{{__('sorry, please select branch first')}}', icon: "error"});
                return false;
            }

            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            let part_id = $('#parts_select').find(":selected").val();

            let index = $('#items_count').val();

            $.ajax({

                type: 'post',

                url: '{{route('admin:purchase.quotations.select.part')}}',

                data: {
                    _token: CSRF_TOKEN,
                    part_id: part_id,
                    index: index,
                },

                success: function (data) {

                    $("#parts_data").append(data.parts);

                    $("#modals_part_types").append(data.partTypesView);

                    $("#items_count").val(data.index);

                    $('.js-example-basic-single').select2();

                    reorderItems();
                },

                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        }

        function priceSegments(index) {

            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            let price_id = $('#prices_part_' + index).val();

            $.ajax({

                type: 'post',

                url: '{{route('admin:purchase.quotations.price.segments')}}',

                data: {
                    _token: CSRF_TOKEN,
                    price_id: price_id,
                    index: index
                },

                success: function (data) {

                    $("#price_segments_part_" + index).html(data.view);
                    $('.js-example-basic-single').select2();
                },

                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        }

        function removeItem(index) {

            swal({

                title: "Delete Item",
                text: "Are you sure want to delete this item ?",
                icon: "warning",
                buttons: true,
                dangerMode: true,

            }).then((willDelete) => {

                if (willDelete) {

                    $('#tr_part_' + index).remove();
                    $('#part_types_' + index).remove();
                    calculateItem(index);
                    reorderItems();
                }
            });
        }

        function selectItemType(index, key) {

            if ($('#item_type_checkbox_' + index + '_' + key).is(':checked')) {

                $('#item_type_real_checkbox_' + index + '_' + key).prop('checked', true);

            } else {

                $('#item_type_real_checkbox_' + index + '_' + key).prop('checked', false);
            }
        }

        function ItemTypePrice (index, key) {

            var itemTypePrice = $('#item_type_price_' + index + '_' + key).val();
            $('#item_type_real_price_' + index + '_' + key).val(itemTypePrice);
        }

        function selectPurchaseRequest() {

            if (!checkBranchValidation()) {
                swal({text: '{{__('sorry, please select branch first')}}', icon: "error"});
                return false;
            }

            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            let purchase_request_id = $('#purchase_request_id').find(":selected").val();

            $.ajax({

                type: 'post',
                url: '{{route('admin:purchase.quotations.select.request')}}',
                data: {
                    _token: CSRF_TOKEN,
                    purchase_request_id: purchase_request_id
                },

                success: function (data) {

                    $("#parts_data").html(data.view);
                    $("#items_count").val(data.index);
                    $(".remove_for_new_purchase_request").remove();

                    for (var i in data.partTypesViews) {

                        $("#modals_part_types").append(data.partTypesViews[i]);
                    }

                    $('.js-example-basic-single').select2();
                },

                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        }

        function reorderItems() {

            let items_count = $('#items_count').val();

            let index = 1;

            for (let i = 1; i <= items_count; i++) {

                if ($('#price_' + i).length) {
                    $('#item_number_' + i).text(index);

                }else {
                    continue;
                }

                index++;
            }
        }

        function checkBranchValidation() {

            let branch_id = $('#branch_id').find(":selected").val();

            let isSuperAdmin = '{{authIsSuperAdmin()}}';

            if (!isSuperAdmin) {
                return true;
            }

            if (branch_id) {
                return true;
            }

            return false;
        }

        $('.dropdown-toggle').dropdown();


    </script>

@endsection
