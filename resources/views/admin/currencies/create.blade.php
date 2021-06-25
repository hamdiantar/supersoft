@extends('admin.layouts.app')
@section('title')
<title>{{ __('Create Currency') }} </title>
@endsection
@section('content')
        <div class="row small-spacing">
            <nav>
                <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                    <li class="breadcrumb-item"><a href="{{route('admin:currencies.index')}}"> {{__('currencies')}}</a></li>
                <li class="breadcrumb-item"> {{__('Create Currency')}}</li>
                </ol>
            </nav>
            <div class="col-xs-12">
                <div class=" card box-content-wg-new bordered-all primary">
                    <h1 class="box-title bg-info" style="text-align: initial"><i class="fa fa-dollar"></i>{{__('Create Currency')}}
                    <span class="controls hidden-sm hidden-xs pull-left">
                    <button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Save')}}<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f1.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Reset')}}<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f2.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px"> {{__('Back')}} <img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f3.png')}}"></button>
						</span>
                </h1>
                <div class="box-content">
                    <form  method="post" action="{{route('admin:currencies.store')}}" class="form">
                        @csrf
                        @method('post')


                        <div class="row">
                    <div class="col-md-12">

                        <div class="col-md-6">

                        <div class="form-group">
                            <label for="inputNameAR" class="control-label">{{__('Name in Arabic')}}</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-file-o"></i></span>
                                <input type="text" name="name_ar" class="form-control" id="inputNameAR" placeholder="{{__('Name in Arabic')}}">
                            </div>
                            {{input_error($errors,'name_ar')}}
                        </div>
                        </div>

                        <div class="col-md-6">
                        <div class="form-group has-feedback">
                            <label for="inputNameEN" class="control-label">{{__('Name in English')}}</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-file-o"></i></span>
                                <input type="text" name="name_en" class="form-control" id="inputNameEN" placeholder="{{__('Name in English')}}">
                            </div>
                            {{input_error($errors,'name_en')}}
                        </div>
                        </div>                                              

                    </div>
                    <div class="col-md-12">

                        <div class="col-md-6">
                        <div class="form-group has-feedback">
                            <label for="inputSymbolAR" class="control-label">{{__('Symbol in Arabic')}}</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                                <input type="text" name="symbol_ar"  class="form-control" id="inputSymbolAR" placeholder="{{__('Symbol in Arabic')}}">
                            </div>
                            {{input_error($errors,'symbol_ar')}}
                        </div>
                        </div>

                        <div class="col-md-6">
                        <div class="form-group has-feedback">
                            <label for="inputSymbolEN" class="control-label">{{__('Symbol in English')}}</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                                <input type="text" name="symbol_en" class="form-control" id="inputSymbolEN" placeholder="{{__('Symbol in English')}}">
                            </div>
                            {{input_error($errors,'symbol_en')}}
                        </div>
                        </div>                                              

                    </div>                    
                  </div> 

                  <div class="col-md-12">
                        <div class="form-group">
                            @include('admin.buttons._save_buttons')
                        </div>
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
    {!! JsValidator::formRequest('App\Http\Requests\Admin\Currency\CurrencyRequest', '.form'); !!}
    @include('admin.partial.sweet_alert_messages')
@endsection
