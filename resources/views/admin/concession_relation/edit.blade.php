@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('Edit Concession Relation') }} </title>
@endsection

@section('content')

    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Edit Concession Relation')}}</li>
            </ol>
        </nav>

        <div class="col-xs-12">

            <div class=" card box-content-wg-new bordered-all primary">

                <h4 class="box-title with-control" style="text-align: initial"><i class="fa fa-gear"></i>
                    {{__('Edit Concession Relation')}}
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
                    <form method="post" action="{{route('admin:concession-relations.update', $type->id)}}"
                          class="form" enctype="multipart/form-data">
                        @csrf
                        @method('post')

                        @include('admin.concession_relation.form')

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

    {!! JsValidator::formRequest('App\Http\Requests\Admin\ConcessionRelation\CreateRequest', '.form'); !!}

    @include('admin.partial.sweet_alert_messages')

    <script >

        function showSelectedType () {

            if ($("#add").is(':checked')) {

                $("#concession_type_add").show();
                $("#select_type_add").prop('disabled', false);

                $("#concession_type_withdrawal").hide();
                $("#select_type_withdrawal").prop('disabled', true);

                //  concession items

                $("#concession_item_add").show();
                $("#select_item_add").prop('disabled', false);

                $("#concession_item_withdrawal").hide();
                $("#select_item_withdrawal").prop('disabled', true);

            }else {

                $("#concession_type_add").hide();
                $("#select_type_add").prop('disabled', true);

                $("#concession_type_withdrawal").show();
                $("#select_type_withdrawal").prop('disabled', false);

                //  concession items

                $("#concession_item_add").hide();
                $("#select_item_add").prop('disabled', true);

                $("#concession_item_withdrawal").show();
                $("#select_item_withdrawal").prop('disabled', false);

            }
        }

    </script>

@endsection
