@extends('admin.layouts.app')

@section('title')
    <title> {{ __('Create Concessions') }} </title>
@endsection

@section('content')
    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"><a href="{{route('admin:concessions.index')}}"> {{__('Concessions')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Create Concessions')}}</li>
            </ol>
        </nav>

        <div class="col-xs-12">

            {{--  box-content-wg-new  --}}

            <div class=" card box-content-wg-new bordered-all primary">

                <h4 class="box-title with-control" style="text-align: initial"><i class="fa fa-gear"></i>
                    {{__('Create Concessions')}}
                    <span class="controls hidden-sm hidden-xs pull-left">
                      <button class="control text-white"
                          style="background:none;border:none;font-size:12px">{{__('Save')}}
                      <img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px"
                           src="{{asset('assets/images/f1.png')}}">
                  </button>
                        <button class="control text-white" style="background:none;border:none;font-size:12px">
                            {{__('Reset')}}
                            <img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px"
                                 src="{{asset('assets/images/f2.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px"> {{__('Back')}} <img
                                    class="img-fluid"
                                    style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px"
                                    src="{{asset('assets/images/f3.png')}}"></button>
						</span>
                </h4>

                <div class="box-content">
                    <form method="post" action="{{route('admin:concessions.store')}}" class="form" enctype="multipart/form-data">
                        @csrf
                        @method('post')

                        @include('admin.concessions.form')

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
@endsection

@section('js-validation')

    {!! JsValidator::formRequest('App\Http\Requests\Admin\Concession\CreateRequest', '.form'); !!}

    @include('admin.partial.sweet_alert_messages')

    <script type="application/javascript">

        function changeBranch() {
            let branch_id = $('#branch_id').find(":selected").val();
            window.location.href = "{{route('admin:concessions.create')}}" + "?branch_id=" + branch_id;
        }

        function showSelectedTypes (type) {

            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            let branch_id = $('#branch_id').find(":selected").val();

            $('.remove_items').remove();

            $.ajax({

                type: 'post',

                url: '{{route('admin:concessions.get.types')}}',

                data: {
                    _token: CSRF_TOKEN,
                    type:type,
                    branch_id:branch_id
                },

                success: function (data) {

                    $("#concession_types").html(data.view);
                    $('.js-example-basic-single').select2();
                },

                error: function (jqXhr, json, errorThrown) {
                    // $("#loader_save_goals").hide();
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        }

        function getDataByBranch () {

            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            let branch_id = $('#branch_id').find(":selected").val();;

            $('.remove_items').remove();

            let type = 'add';

            if ($('#add').is(':checked')) {

                type = 'add';

            }else {

                type = 'withdrawal';
            }

            $.ajax({

                type: 'post',

                url: '{{route('admin:concessions.data.by.branch')}}',

                data: {
                    _token: CSRF_TOKEN,
                    branch_id:branch_id,
                    type:type
                },

                success: function (data) {

                    $("#concession_types").html(data.view);
                    $('.js-example-basic-single').select2();
                },

                error: function (jqXhr, json, errorThrown) {
                    // $("#loader_save_goals").hide();
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        }

        function getConcessionItems () {

            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            let branch_id = $('#branch_id').find(":selected").val();
            let concession_type_id = $('#concession_type_id').find(":selected").val();

            $.ajax({

                type: 'post',

                url: '{{route('admin:concessions.get.items')}}',

                data: {
                    _token: CSRF_TOKEN,
                    branch_id:branch_id,
                    concession_type_id:concession_type_id
                },

                success: function (data) {

                    $("#model").val(data.model);

                    $("#concession_items").html(data.view);
                    $('.js-example-basic-single').select2();
                },

                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        }

        function getConcessionPartsData () {

            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            let concession_item_id = $("#concession_item_id").val();
            let model = $("#model").val();

            $.ajax({

                type: 'post',

                url: '{{route('admin:concessions.parts.data')}}',

                data: {
                    _token: CSRF_TOKEN,
                    concession_item_id:concession_item_id,
                    model:model
                },

                success: function (data) {

                    $("#parts_data").html(data.view);

                    $("#item_quantity").val(data.total_quantity);
                    $("#total_price").val(data.total_price);

                    $('.js-example-basic-single').select2();
                },

                error: function (jqXhr, json, errorThrown) {
                    // $("#loader_save_goals").hide();
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        }

    </script>

@endsection
