@extends('admin.layouts.app')

@section('title')
    <title>{{ __('opening-balance.edit') }} </title>
@endsection

@section('content')

    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"><a
                        href="{{route('admin:opening-balance.index')}}"> {{__('opening-balance.index-title')}}</a></li>
                <li class="breadcrumb-item active"> {{__('opening-balance.edit')}}</li>
            </ol>
        </nav>

        <div class="col-xs-12">

            {{--  box-content-wg-new  --}}

            <div class=" card box-content-wg-new bordered-all primary">

                <h4 class="box-title with-control" style="text-align: initial"><i class="fa fa-cube"></i>
                    {{__('opening-balance.edit')}}
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
                    <form method="post" action="{{route('admin:opening-balance.update', $openingBalance->id)}}" class="form"
                          enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        @include('admin.opening_balance.form')

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

    {!! JsValidator::formRequest('App\Http\Requests\Admin\OpeningBalance\CreateRequest', '.form'); !!}

    @include('admin.partial.sweet_alert_messages')

    <script type="application/javascript">

        function changeBranch () {

            let branch_id = $('#branch_id').find(":selected").val();
            window.location.href = "{{route('admin:opening-balance.create')}}" + "?branch_id=" + branch_id ;
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

            let branch_id = $('#branch_id').find(":selected").val();

            $.ajax({

                type: 'post',

                url: '{{route('admin:opening-balance.select.part')}}',

                data: {
                    _token: CSRF_TOKEN,
                    part_id: part_id,
                    index: index,
                    branch_id: branch_id
                },

                success: function (data) {

                    $("#parts_data").append(data.parts);

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

            let purchase_price = $('#prices_part_' + index).find(":selected").data('purchase-price');

            $.ajax({

                type: 'post',

                url: '{{route('admin:opening-balance.price.segments')}}',

                data: {
                    _token: CSRF_TOKEN,
                    price_id: price_id,
                    index: index
                },

                success: function (data) {

                    $("#price_segments_part_" + index).html(data.view);
                    $("#price_" + index).val(purchase_price);

                    calculateItem(index);

                    $('.js-example-basic-single').select2();
                },

                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        }

    </script>

    <script src="{{asset('js/opening_balance/index.js')}}"></script>

@endsection
