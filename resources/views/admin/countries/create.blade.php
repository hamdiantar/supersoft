@extends('admin.layouts.app')
@section('title')
<title>{{ __('Create Country') }} </title>
@endsection
@section('content')
    <div class="row small-spacing">
        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
            <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin:countries.index')}}"> {{__('countries')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Create Country')}}</li>
            </ol>
        </nav>
        <div class="col-xs-12">
        <div class=" card box-content-wg-new bordered-all primary">
                <h4 class="box-title with-control" style="text-align: initial"><i class="fa fa-check-square-o"></i>
                  {{__('Create Country')}}
                  <span class="controls hidden-sm hidden-xs pull-left">
                  <button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Save')}}<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f1.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Reset')}}<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f2.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px"> {{__('Back')}} <img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f3.png')}}"></button>
						</span>
                </h4>
        

                    <div class="box-content">
                        <form method="post" action="{{route('admin:countries.store')}}" class="form">
                            @csrf
                            @method('post')


                  <div class="row">

                    <div class="col-md-12">

                        <div class="col-md-6">
                        <div class="form-group">
                                <label for="inputNameAR" class="control-label">{{__('Name in Arabic')}}</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><li class="fa fa-file-text-o"></li></span>
                                    <input type="text" name="name_ar" class="form-control" id="inputNameAR"
                                           placeholder="{{__('Name in Arabic')}}">
                                </div>
                                {{input_error($errors,'name_ar')}}
                            </div>
                        </div>

                        <div class="col-md-6">
                        <div class="form-group has-feedback">
                                <label for="inputNameEN" class="control-label">{{__('Name in English')}}</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><li class="fa fa-file-text-o"></li></span>
                                    <input type="text" name="name_en" class="form-control" id="inputNameEN"
                                           placeholder="{{__('Name in English')}}">
                                </div>
                                {{input_error($errors,'name_en')}}
                            </div>
                        </div>                                              

                    </div>

                    <div class="col-md-12">

                        <div class="col-md-6">

                        <div class="form-group has-feedback">
                                <label for="inputSymbolAR" class="control-label">{{__('Select Currency')}}</label>
                                <div class="input-group">
                                <span class="input-group-addon fa fa-dollar"></span>
                                <select name="currency_id" class="form-control js-example-basic-single">
                                    @foreach(\App\Models\Currency::all() as $currency)
                                        <option value="{{$currency->id}}">{{$currency->name}}</option>
                                    @endforeach
                                </select>
                                {{input_error($errors,'currency_id')}}
                            </div>
                        </div>
                        </div>

                        <div class="col-md-6">

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
    {!! JsValidator::formRequest('App\Http\Requests\Admin\Country\CountryRequest', '.form'); !!}
    @include('admin.partial.sweet_alert_messages')
@endsection
