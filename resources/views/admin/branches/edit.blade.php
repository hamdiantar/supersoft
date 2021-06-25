@extends('admin.layouts.app')
@section('title')
<title>{{ __('Edit Branch') }} </title>
@endsection
@section('content')
    <div class="row small-spacing">
        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
            <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin:branches.index')}}"> {{__('Branches')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Edit Branch')}}</li>
            </ol>
        </nav>
        <div class="col-xs-12">
        <div class=" card box-content-wg-new bordered-all primary">
                <h4 class="box-title with-control" style="text-align: initial"><i class="fa fa-building-o"></i> 
                  {{__('Edit Branch')}}
                  <span class="controls hidden-sm hidden-xs pull-left">
                  <button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Save')}}<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f1.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Reset')}}<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f2.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px"> {{__('Back')}} <img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f3.png')}}"></button>
						</span>
                </h4>

                <div class="box-content">
                    <form method="post" action="{{route('admin:branches.update', ['id' => $branch->id])}}" class="form" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')


                        <div class="row">

                    <div class="col-md-12">

                        <div class="col-md-4">
                        <div class="form-group has-feedback">
                            <label for="inputNameAR" class="control-label">{{__('Name in Arabic')}}</label>
                            <div class="input-group">
                                <span class="input-group-addon"><li class="fa fa-file-text"></li></span>
                                <input type="text" name="name_ar" class="form-control" id="inputNameAR"
                                       placeholder="{{__('Name in Arabic')}}" value="{{$branch->name_ar}}">
                            </div>
                            {{input_error($errors,'name_ar')}}
                        </div>
                        </div>

                        <div class="col-md-4">
                        <div class="form-group has-feedback">
                            <label for="inputNameEN" class="control-label">{{__('Name in English')}}</label>
                            <div class="input-group">
                                <span class="input-group-addon"><li class="fa fa-file-text"></li></span>
                                <input type="text" name="name_en" class="form-control" id="inputNameEN"
                                       placeholder="{{__('Name in English')}}" value="{{$branch->name_en}}">
                            </div>
                            {{input_error($errors,'name_en')}}
                        </div>
                        </div>                                              

                        <div class="col-md-4">

                        <div class="form-group has-feedback">
                            <label for="country" class="control-label">{{__('Select Country')}}</label>
                            <div class="input-group">
                                <span class="input-group-addon fa fa-globe"></span>
                            <select name="country_id" class="form-control   js-example-basic-single" id="country">
                                <option value="">{{__('Select Country')}}</option>
                                @foreach(\App\Models\Country::all() as $country)
                                    <option value="{{$country->id}}" {{$country->id == $branch->country_id ? 'selected' : ''}}>{{$country->name}}</option>
                                @endforeach
                            </select>
                            {{input_error($errors,'country_id')}}
                        </div>
                        </div>
                        </div>
                        </div>

                        <div class="col-md-12">
                        <div class="col-md-4">
                        <div class="form-group has-feedback">
                            <label for="city" class="control-label">{{__('Select City')}}</label>
                            <div class="input-group">
                                <span class="input-group-addon fa fa-globe"></span>
                            <select name="city_id" class="form-control  js-example-basic-single" id="city">
                                @foreach(\App\Models\City::where('id', $branch->city_id)->get() as $city)
                                    <option value="{{$city->id}}">{{$city->name}}</option>
                                @endforeach
                            </select>
                            {{input_error($errors,'city_id')}}
                        </div>
                        </div>      
                        </div>                                        

                        <div class="col-md-4">
                        <div class="form-group has-feedback">
                            <label for="area" class="control-label">{{__('Select Area')}}</label>
                            <div class="input-group">
                                <span class="input-group-addon fa fa-globe"></span>
                            <select name="area_id" class="form-control  js-example-basic-single select2" id="area">
                                @foreach(\App\Models\Area::where('id', $branch->area_id)->get() as $area)
                                    <option value="{{$area->id}}">{{$area->name}}</option>
                                @endforeach
                            </select>
                            {{input_error($errors,'area_id')}}
                        </div>
                        </div>
                        </div>

                        <div class="col-md-4">
                        <div class="form-group has-feedback">
                            <label for="inputSymbolAR" class="control-label">{{__('Select Currency')}}</label>
                            <div class="input-group">
                                <span class="input-group-addon fa fa-globe"></span>
                            <select name="currency_id" class="form-control  js-example-basic-single" id="currency">
                                @foreach(\App\Models\Currency::where('id', $branch->currency_id)->get() as $currency)
                                    <option value="{{$currency->id}}">{{$currency->name}}</option>
                                @endforeach
                            </select>
                            {{input_error($errors,'currency_id')}}
                        </div>
                        </div>       
                        </div>                                       

                    </div>
                    
                    <div class="col-md-12">

  

                        <div class="col-md-4">
                        <div class="form-group has-feedback">
                            <label for="address" class="control-label">{{__('Address in Arabic')}}</label>
                            <div class="input-group">
                                <span class="input-group-addon"><li class="fa fa-home"></li></span>
                                <input type="text" name="address_ar" class="form-control" id="address"
                                       placeholder="{{__('Address in Arabic')}}" value="{{$branch->address_ar}}">
                            </div>
                            {{input_error($errors,'address_ar')}}
                        </div>
                        </div>           

                                              <div class="col-md-4">
                        <div class="form-group has-feedback">
                            <label for="address" class="control-label">{{__('Address in English')}}</label>
                            <div class="input-group">
                                <span class="input-group-addon"><li class="fa fa-home"></li></span>
                                <input type="text" name="address_en" class="form-control" id="address"
                                       placeholder="{{__('Address in English')}}" value="{{$branch->address_en}}">
                            </div>
                            {{input_error($errors,'address_en')}}
                        </div>
                        </div>                                  

                        <div class="col-md-4">
                        <div class="form-group has-feedback">
                            <label for="emial" class="control-label">{{__('Email')}}</label>
                            <div class="input-group">
                                <span class="input-group-addon"><li class="fa fa-mail-reply"></li></span>
                                <input type="text" name="email" class="form-control" id="emial"
                                       placeholder="{{__('Email')}}" value="{{$branch->email}}">
                            </div>
                            {{input_error($errors,'email')}}
                        </div>
                        </div>
                        </div>

                        <div class="col-md-12">
                        <div class="col-md-4">
                        <div class="form-group has-feedback">
                            <label for="address" class="control-label">{{__('Tax Card')}}</label>
                            <div class="input-group">
                                <span class="input-group-addon"><li class="fa fa-file-text-o"></li></span>
                                <input type="text" name="tax_card" class="form-control" id="address"
                                       placeholder="{{__('Tax Card')}}" value="{{$branch->tax_card}}">
                            </div>
                            {{input_error($errors,'tax_card')}}
                        </div>
                        </div>                                              

                        <div class="col-md-4">
                        <div class="form-group has-feedback">
                            <label for="mailbox_number" class="control-label">{{__('Mailbox Number')}}</label>
                            <div class="input-group">
                                <span class="input-group-addon"><li class="fa fa-file-text-o"></li></span>
                                <input type="text" name="mailbox_number" class="form-control" id="mailbox_number"
                                       placeholder="{{__('Mailbox Number')}}" value="{{$branch->mailbox_number}}">
                            </div>
                            {{input_error($errors,'mailbox_number')}}
                        </div>
                        </div>

                        <div class="col-md-4">
                        <div class="form-group has-feedback">
                            <label for="phone1" class="control-label">{{__('Phone 1')}}</label>
                            <div class="input-group">
                                <span class="input-group-addon"><li class="fa fa-phone"></li></span>
                                <input type="text" name="phone1" class="form-control" id="phone1"
                                       placeholder="{{__('Phone 1')}}" value="{{$branch->phone1}}">
                            </div>
                            {{input_error($errors,'phone1')}}
                        </div>
                        </div>                                              

                    </div>
                    
                    <div class="col-md-12">

                        <div class="col-md-4">
                        <div class="form-group has-feedback">
                            <label for="phone2" class="control-label">{{__('Phone 2')}}</label>
                            <div class="input-group">
                                <span class="input-group-addon"><li class="fa fa-phone"></li></span>
                                <input type="text" name="phone2" class="form-control" id="phone2"
                                       placeholder="{{__('Phone 2')}}" value="{{$branch->phone2}}">
                            </div>
                            {{input_error($errors,'phone2')}}
                        </div>
                        </div>

                        <div class="col-md-4">
                        <div class="form-group has-feedback">
                            <label for="postal_code" class="control-label">{{__('Postal Code')}}</label>
                            <div class="input-group">
                                <span class="input-group-addon"><li class="fa fa-code"></li></span>
                                <input type="text" name="postal_code" class="form-control" id="postal_code"
                                       placeholder="{{__('Postal Code')}}" value="{{$branch->postal_code}}">
                            </div>
                            {{input_error($errors,'postal_code')}}
                        </div>
                        </div>                                              

                        <div class="col-md-4">
                        <div class="form-group has-feedback">
                            <label for="fax" class="control-label">{{__('Fax')}}</label>
                            <div class="input-group">
                                <span class="input-group-addon"><li class="fa fa-fax"></li></span>
                                <input type="text" name="fax" class="form-control" id="fax"
                                       placeholder="{{__('Fax')}}" value="{{$branch->fax}}">
                            </div>
                            {{input_error($errors,'fax')}}
                        </div>
                        </div>
                        </div>

                        <div class="col-md-12">
                        <div class="col-md-4">
                        <div class="form-group has-feedback">
                            <label for="logo" class="control-label">{{__('Logo')}}</label>
                            <div class="input-group">
                                <span class="input-group-addon"><li class="fa fa-photo"></li></span>
                                <input type="file" name="logo" class="form-control" id="logo"
                                       placeholder="{{__('Logo')}}" value="{{$branch->logo}}">
                            </div>
                            {{input_error($errors,'logo')}}
                        </div>
                        </div>                                                

{{--                        <div class="col-md-4">--}}
{{--                        <div class="form-group has-feedback">--}}
{{--                            <label for="fax" class="control-label">{{__('Map')}}</label>--}}
{{--                            <div class="input-group">--}}
{{--                                <span class="input-group-addon"><li class="fa fa-map"></li></span>--}}
{{--                                <input type="file" name="map" class="form-control" id="map"--}}
{{--                                       placeholder="{{__('Map')}}" value="{{$branch->map}}">--}}
{{--                            </div>--}}
{{--                            {{input_error($errors,'map')}}--}}
{{--                        </div>--}}
{{--                        </div>--}}

                        <div class="col-md-2">
                        <div class="form-group has-feedback">
                            <label for="inputPhone" class="control-label">{{__('Status')}}</label>
                            <div class="switch primary" style="margin-top: 15px">
                                <input type="hidden" name="status" value="0">
                                <input type="checkbox" id="switch-1" name="status" value="1"
                                {{$branch->status == 1 ? 'checked' : ''}}>
                                <label for="switch-1">{{__('Active')}}</label>
                            </div>
                        </div>
                        </div>    
                        <div class="col-md-2">
                        <div class="form-group has-feedback">
                            <label for="inputPhone" class="control-label">{{__('Activating shifts')}}</label>
                            <div class="switch primary" style="margin-top: 15px">
                                <input type="hidden"  name="shift_is_active" value="0">
                                <input type="checkbox" id="switch-2" name="shift_is_active" value="1"
                                    {{$branch->shift_is_active == 1 ? 'checked' : ''}}>
                                <label for="switch-2">{{__('Active')}}</label>
                            </div>
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
    {!! JsValidator::formRequest('App\Http\Requests\Admin\Branch\BranchUpdateRequest', '.form'); !!}
    @include('admin.partial.sweet_alert_messages')
    <script type="application/javascript">
        $("#country").change(function () {
            $.ajax({
                url: "{{ route('admin:country.cities') }}?country_id=" + $(this).val(),
                method: 'GET',
                success: function (data) {
                    $('#city').html(data.cities);
                    $('#currency').html(data.currency);
                }
            });
        });

        $("#city").change(function () {
            $.ajax({
                url: "{{ route('admin:city.areas') }}?city_id=" + $(this).val(),
                method: 'GET',
                success: function (data) {
                    $('#area').html(data.html);
                }
            });
        });
    </script>
@endsection
