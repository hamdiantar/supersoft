@extends('web.layout.app')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('Edit customers and cars') }} </title>
@endsection


@section('content')
    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('web:dashboard')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Edit customers and cars')}}</li>
            </ol>
        </nav>

        <div class="col-xs-12">
            <div class=" card box-content-wg-new bordered-all primary">
                <h1 class="box-title bg-info" style="text-align: initial">
                    <i class="fa fa-user"></i>{{__('Edit customers')}}
                    <span class="controls hidden-sm hidden-xs pull-left">
                    <button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Save')}}<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f1.png')}}"></button>
					<button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Reset')}}<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f2.png')}}"></button>
					<button class="control text-white" style="background:none;border:none;font-size:12px"> {{__('Back')}} <img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f3.png')}}"></button>
					</span>
                </h1>

                <div class="box-content">
                    <form method="post" action="{{route('web:customer.update')}}" class="form">
                        @csrf

                        <div class="row">
{{--                            <div class="col-xs-12">--}}
{{--                                <div class="col-md-12">--}}
{{--                                    @if (authIsSuperAdmin())--}}

{{--                                        <div class="form-group has-feedback">--}}
{{--                                            <label for="inputSymbolAR"--}}
{{--                                                   class="control-label">{{__('Select Branch')}}</label>--}}
{{--                                            <select name="branch_id" class="form-control js-example-basic-single"--}}
{{--                                                    onchange="getCustomerCate(event)">--}}
{{--                                                <option value="">{{__('Select Branch')}}</option>--}}
{{--                                                @foreach(\App\Models\Branch::all() as $branch)--}}
{{--                                                    <option value="{{$branch->id}}" {{$customer->branch_id == $branch->id ? 'selected' : ''}}>{{$branch->name}}</option>--}}
{{--                                                @endforeach--}}
{{--                                            </select>--}}
{{--                                            {{input_error($errors,'branch_id')}}--}}
{{--                                        </div>--}}
{{--                                    @endif--}}
{{--                                </div>--}}
{{--                            </div>--}}

                            <div class="col-xs-12">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputNameAR"
                                               class="control-label">{{__('Client Name in Arabic')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-file-text-o"></li></span>
                                            <input type="text" name="name_ar" class="form-control"
                                                   id="inputNameAR" value="{{$customer->name_ar}}"
                                                   placeholder="{{__('Client Name in Arabic')}}">
                                        </div>
                                        {{input_error($errors,'name_ar')}}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group has-feedback">
                                        <label for="inputNameEN"
                                               class="control-label">{{__('Client Name in English')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-file-text-o"></li></span>
                                            <input type="text" name="name_en" class="form-control"
                                                   id="inputNameEN" value="{{$customer->name_en}}"
                                                   placeholder="{{__('Client Name in English')}}">
                                        </div>
                                        {{input_error($errors,'name_en')}}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group has-feedback">
                                        <label for="inputSymbolAR"
                                               class="control-label">{{__('Customers Category')}}</label>
                                        <select name="customer_category_id" class="form-control js-example-basic-single"
                                                id="customerCategory">
                                            <option value="">{{__('Select Customer Category')}}</option>
                                            @foreach($customerCategories as $k=>$v)
                                                <option value="{{$k}}" {{$customer->customer_category_id == $k ? 'selected' : ''}}>
                                                    {{$v}}
                                                </option>
                                            @endforeach
                                        </select>
                                        {{input_error($errors,'customer_category_id')}}
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12">
                                <div class="col-md-4">
                                    <div class="form-group has-feedback">
                                        <label for="inputSymbolAR"
                                               class="control-label">{{__('Select Country')}}</label>
                                        <select name="country_id" class="form-control js-example-basic-single" id="country">
                                            <option value="">{{__('Select Country')}}</option>
                                            @foreach($countries as $k=>$v)
                                                <option value="{{$k}}" {{$customer->country_id == $k ? 'selected' : ''}}>
                                                    {{$v}}
                                                </option>
                                            @endforeach
                                        </select>
                                        {{input_error($errors,'country_id')}}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group has-feedback">
                                        <label for="inputSymbolAR" class="control-label">{{__('Select City')}}</label>
                                        <select name="city_id" class="form-control js-example-basic-single" id="city">
                                            @foreach($cities as $k=>$v)
                                                <option value="{{$k}}" {{$customer->city_id == $k ? 'selected' : ''}}>
                                                    {{$v}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>


                                <div class="col-md-4">
                                    <div class="form-group has-feedback">
                                        <label for="area" class="control-label">{{__('Select Area')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon fa fa-globe"></span>
                                            <select name="area_id" class="form-control  js-example-basic-single select2" id="area">
                                                @foreach($areas as $k=>$v)
                                                    <option value="{{$k}}"{{$customer->area_id == $k ? 'selected' : ''}}>
                                                        {{$v}}
                                                    </option>
                                                @endforeach
                                            </select>
                                            {{input_error($errors,'area_id')}}
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-4">
                                    <div class="form-group has-feedback" id="appendCompanyData">
                                        <label for="inputSymbolAR"
                                               class="control-label">{{__('Select Customer Type')}}</label>
                                        <select name="type" class="form-control js-example-basic-single" id="CompanyData">

                                            <option value="person" {{$customer->type == 'person'? 'selected':''}}>
                                                {{__('Person')}}
                                            </option>
                                            <option value="company" {{$customer->type == 'company'? 'selected':''}}>
                                                {{__('Company')}}
                                            </option>
                                        </select>
                                        {{input_error($errors,'type')}}
                                    </div>
                                </div>
                            </div>
                            <div class="">

                                @if ($customer->type == 'company')

                                    <div class="col-md-4">
                                        <div class="form-group" id="faxCompany">
                                            <label for="fax" class="control-label">{{__('Fax')}}</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><li class="fa fa-fax"></li></span>
                                                <input type="text" name="fax" class="form-control" id="fax"
                                                       value="{{$customer->fax}}" placeholder="{{__('Fax')}}">
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group" id="CompanyCommercialRegister">
                                            <label for="commercial_register"
                                                   class="control-label">{{__('Commercial Register')}}</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><li class="fa fa-fax"></li></span>
                                                <input type="text" name="commercial_register" class="form-control"
                                                       value="{{$customer->commercial_register}}"
                                                       id="commercial_register"
                                                       placeholder="{{__('Commercial Register')}}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">

                                        <div class="form-group" id="CompanyResponsible">
                                            <label for="responsible"
                                                   class="control-label">{{__('Responsible Name')}}</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><li class="fa fa-fax"></li></span>
                                                <input type="text" name="responsible" class="form-control"
                                                       id="responsible"
                                                       value="{{$customer->responsible}}"
                                                       placeholder="{{__('Responsible Name')}}">
                                            </div>
                                        </div>
                                    </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group" id="CompanyTaxCard">
                                    <label for="tax_card" class="control-label">{{__('Tax Card')}}</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><li class="fa fa-fax"></li></span>
                                        <input type="text" name="tax_card" class="form-control" id="tax_card"
                                               value="{{$customer->tax_card}}" placeholder="{{__('Responsible Name')}}">
                                    </div>
                                </div>

                                @endif
                            </div>
                            <div class="col-md-12">

                                <div class="col-md-4">
                                    <div class="form-group has-feedback">
                                        <label for="address" class="control-label">{{__('Address')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-home"></li></span>
                                            <input type="text" name="address" class="form-control" id="address"
                                                   value="{{$customer->address}}" placeholder="{{__('Address')}}">
                                        </div>
                                        {{input_error($errors,'address')}}
                                    </div>
                                </div>


                                <div class="col-md-4">
                                    <div class="form-group has-feedback">
                                        <label for="phone1" class="control-label">{{__('Phone 1')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-phone"></li></span>
                                            <input type="text" name="phone1" class="form-control" id="phone1"
                                                   value="{{$customer->phone1}}" placeholder="{{__('Phone 1')}}">
                                        </div>
                                        {{input_error($errors,'phone1')}}
                                    </div>
                                </div>

                                <div class="col-md-4">


                                    <div class="form-group has-feedback">
                                        <label for="phone2" class="control-label">{{__('Phone 2')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-phone"></li></span>
                                            <input type="text" name="phone2" class="form-control" id="phone2"
                                                   value="{{$customer->phone2}}" placeholder="{{__('Phone 2')}}">
                                        </div>
                                        {{input_error($errors,'phone2')}}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group has-feedback">
                                        <label for="email" class="control-label">{{__('Email')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">@</span>
                                            <input type="text" name="email" class="form-control" id="email"
                                                   value="{{$customer->email}}" placeholder="{{__('Email')}}">
                                        </div>
                                        {{input_error($errors,'email')}}
                                    </div>
                                </div>


                                <div class="col-md-4">
                                    <div class="form-group has-feedback">
                                        <label for="cars_number" class="control-label">{{__('Cars Number')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-car"></li></span>
                                            <input type="text" name="cars_number" class="form-control" id="cars_number"
                                                   value="{{$carsCount}}" placeholder="{{__('Cars Number')}}">
                                        </div>
                                        {{input_error($errors,'cars_number')}}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group has-feedback">
                                        <label for="email" class="control-label">{{__('Username')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">U</span>
                                            <input type="text" name="username" class="form-control" id="username"
                                                   value="{{$customer->username}}"
                                                   placeholder="{{__('Username')}}">
                                        </div>
                                        {{input_error($errors,'username')}}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group has-feedback">
                                        <label for="email" class="control-label">{{__('Password')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">P</span>
                                            <input type="password" name="password" class="form-control" id="password"
                                                   placeholder="{{__('Password')}}">
                                        </div>
                                        {{input_error($errors,'password')}}
                                    </div>
                                </div>

                                @php
                                    $balance_details = $customer->get_my_balance();
                                @endphp

                                <div class="col-md-4">
                                    <div class="form-group has-feedback">
                                        <label for="inputNameEN" class="control-label">{{ $balance_details['balance_title'] }}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-money"></li></span>
                                            <input type="text" class="form-control" disabled value="{{ $balance_details['balance'] }}">
                                        </div>
                                    </div>
                                </div>

                                <!-- </div> -->

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="notes" class="control-label">{{__('Notes')}}</label>
                                        <div class="input-group">
                                            <textarea name="notes" class="form-control" rows="4" cols="200">{{$customer->notes}}</textarea>
                                        </div>
                                        {{input_error($errors,'notes')}}
                                    </div>
                                </div>

                                <div class="col-xs-12">
                                    <div class="col-md-4">
                                        <div class="form-group has-feedback">
                                            <label for="inputPhone" class="control-label">{{__('Status')}}</label>
                                            <div class="switch primary" style="margin-top: 15px">
                                                <input type="hidden" name="status" value="0">
                                                <input type="checkbox" id="switch-1" name="status" value="1"
                                                    {{$customer->status == 1 ? 'checked' : ''}}>
                                                <label for="switch-1">{{__('Active')}}</label>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>


                            <div class="form-group col-sm-12">
                                @include('admin.buttons._save_buttons')
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js-validation')

    {!! JsValidator::formRequest('App\Http\Requests\Web\Customers\UpdateCustomerRequest', '.form'); !!}

    @include('admin.partial.sweet_alert_messages')

    <script type="application/javascript">
        $('#CompanyData').on('change', function () {
            var type = $("#CompanyData option:selected").val();
            if (type === 'company') {
                $('#appendCompanyData').parent().after('<div class="form-group  col-md-4" id="faxCompany">' +
                    '<label for="fax" class="control-label">{{__('Fax')}}</label>' +
                    '<div class="input-group">' +
                    '<span class="input-group-addon"><li class="fa fa-fax"></li></span>' +
                    '<input type="text" value="{{ $customer->fax }}" name="fax" class="form-control" id="fax"' +
                    'placeholder="{{__('Fax')}}">' +
                    '</div>' +
                    '</div>' +
                    '<div class="form-group  col-md-4" id="CompanyCommercialRegister">' +
                    '<label for="commercial_register" class="control-label">{{__('Commercial Register')}}</label>' +
                    '<div class="input-group">' +
                    '<span class="input-group-addon"><li class="fa fa-fax"></li></span>' +
                    ' <input type="text" name="commercial_register" value="{{ $customer->commercial_register }}" class="form-control" id="commercial_register" placeholder="{{__('Commercial Register')}}">' +
                    '</div>' +
                    ' </div>' +
                    '<div class="form-group  col-md-4" id="CompanyResponsible">' +
                    '<label for="responsible" class="control-label">{{__('Responsible Name')}}</label>' +
                    '<div class="input-group">' +
                    ' <span class="input-group-addon"><li class="fa fa-fax"></li></span>' +
                    '<input type="text" name="responsible" value="{{ $customer->responsible }}" class="form-control" id="responsible"' +
                    'placeholder="{{__('Responsible Name')}}" >' +
                    '</div>' +
                    '</div>' +
                    '<div class="form-group  col-md-4" id="CompanyTaxCard">' +
                    '<label for="tax_card" class="control-label">{{__('Tax Card')}}</label>' +
                    '<div class="input-group">' +
                    '<span class="input-group-addon"><li class="fa fa-fax"></li></span>' +
                    '<input type="text" name="tax_card" value="{{ $customer->tax_card }}" class="form-control" id="tax_card"' +
                    ' placeholder="{{__('Responsible Name')}}" >' +
                    '</div>' +
                    '</div>'
                );
            }
            if (type === 'person') {
                $('#faxCompany').remove();
                $('#CompanyCommercialRegister').remove();
                $('#CompanyResponsible').remove();
                $('#CompanyTaxCard').remove();
            }
        });

        $("#country").on('change', function () {
            $.ajax({
                url: "{{ route('admin:country.cities') }}?country_id=" + $(this).val(),
                method: 'GET',
                success: function (data) {
                    $('#city').html(data.cities);
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

        function getCustomerCate(event) {
            if (event.target.value) {
                $.ajax({
                    url: "{{ route('admin:customers.customerCategory') }}?id=" + event.target.value,
                    method: 'GET',
                    success: function (data) {
                        $('#customerCategory').html(data.customerCategory);
                    }
                });
            }
        }
    </script>
@endsection
