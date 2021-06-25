@extends('admin.layouts.app')
@section('title')
<title>{{ __('Create Spare Parts Unit') }} </title>
@endsection
@section('content')
        <div class="row small-spacing">
            <nav>
                <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                    <li class="breadcrumb-item"><a href="{{route('admin:spare-parts.index')}}"> {{__('Spare Parts Units')}}</a></li>
                    <li class="breadcrumb-item active"> {{__('Create Spare Parts Unit')}}</li>
                </ol>
            </nav>
            <div class="col-xs-12">
            <div class=" card box-content-wg-new bordered-all primary">
                <h4 class="box-title with-control" style="text-align: initial"><i class="fa fa-check-square-o"></i>
                  {{__('Create Spare Parts Unit')}}
                  <span class="controls hidden-sm hidden-xs pull-left">
                  <button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Save')}}<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f1.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Reset')}}<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f2.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px"> {{__('Back')}} <img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f3.png')}}"></button>
						</span>
                </h4>
        

                    <div class="box-content">
                    <form  method="post" action="{{route('admin:spare-part-units.store')}}" class="form">
                        @csrf
                        @method('post')

                        <div class="row">

                    <div class="col-md-12">

                        <div class="col-md-6">
                        <div class="form-group">
                            <label for="inputNameAR" class="control-label">{{__('Unit In Arabic')}}</label>
                            <div class="input-group">
                                <span class="input-group-addon"><li class="fa fa-file-text-o"></li></span>
                                <input type="text" name="unit_ar" class="form-control" id="inputNameAR" placeholder="{{__('Unit In Arabic')}}">
                            </div>
                            {{input_error($errors,'unit_ar')}}
                        </div>
                        </div>

                        <div class="col-md-6">
                        <div class="form-group has-feedback">
                            <label for="inputNameEN" class="control-label">{{__('Unit In English')}}</label>
                            <div class="input-group">
                                <span class="input-group-addon"><li class="fa fa-file-text-o"></li></span>
                                <input type="text" name="unit_en" class="form-control" id="inputNameEN" placeholder="{{__('Unit In English')}}">
                            </div>
                            {{input_error($errors,'unit_en')}}
                        </div>
                        </div>                                              

                    </div>

                  </div>  




                        <div class="form-group col-sm-12">
                            @include('admin.buttons._save_buttons')
                        </div>
                    </form>
                </div>
                <!-- /.box-content -->
                </div>
            </div>
            <!-- /.col-xs-12 -->
        </div>
        <!-- /.row small-spacing -->
@endsection
@section('js-validation')
    {!! JsValidator::formRequest('App\Http\Requests\Admin\SparePartUnit\SparePartUnitRequest', '.form'); !!}
    @include('admin.partial.sweet_alert_messages')
@endsection
