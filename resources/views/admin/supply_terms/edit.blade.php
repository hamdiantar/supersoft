@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Edit Supply Terms') }} </title>
@endsection

@section('content')

    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"><a
                        href="{{route('admin:supply-terms.index')}}"> {{__('Supply & payments')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Edit Supply Terms')}}</li>
            </ol>
        </nav>

        <div class="col-xs-12">

            {{--  box-content-wg-new  --}}

            <div class=" card box-content-wg-new bordered-all primary">

                <h4 class="box-title with-control" style="text-align: initial"><i class="fa fa-file-o"></i>
                    {{__('Edit Supply Terms')}}
                    <span class="controls hidden-sm hidden-xs pull-left">
                      <button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Save')}}
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
                    <form method="post" action="{{route('admin:supply-terms.update', $supplyTerm->id)}}" class="form"
                          enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        @include('admin.supply_terms.form')

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

    {!! JsValidator::formRequest('App\Http\Requests\Admin\SupplyTerms\CreateRequest', '.form'); !!}

    @include('admin.partial.sweet_alert_messages')

@endsection
