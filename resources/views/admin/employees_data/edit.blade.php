@extends('admin.layouts.app')
@include('admin.employees_data.parts.rating')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('Edit Employee Data') }} </title>
@endsection

@section('content')
    <div class="row small-spacing">
    <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin:employees_data.index')}}"> {{__('Employees Data')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Edit Employee Data')}}</li>
            </ol>
        </nav>
        <div class="col-xs-12">
        <div class=" card box-content-wg-new bordered-all primary">
                <h4 class="box-title with-control" style="text-align: initial"><i class="fa fa-user"></i>  {{__('Edit Employee Data')}}
                <span class="controls hidden-sm hidden-xs pull-left">
                <button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Save')}}<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f1.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Reset')}}<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f2.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px"> {{__('Back')}} <img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f3.png')}}"></button>
						</span>
            </h4>
                    <div class="box-content">
                    <form method="post" action="{{route('admin:employees_data.update', ['id' => $employeeData->id])}}" class="form" enctype="multipart/form-data">
                        @csrf
                        @method('put')
                        <div class="row">
                        @if (authIsSuperAdmin())
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label> {{__('Select Branch')}} <i class="req-star" style="color: red">*</i></label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                                                <select name="branch_id" class="form-control js-example-basic-single">
                                                    <option value="">{{__('Select Branch')}}</option>
                                                    @foreach(\App\Models\Branch::all() as $branch)
                                                        <option value="{{$branch->id}}"
                                                            {{$employeeData->branch_id === $branch->id ? 'selected' : ''}}>
                                                            {{$branch->name}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                {{input_error($errors,'branch_id')}}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            <div class="col-md-12">
                              
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> {{__('Select Employee Category')}} <i class="req-star"
                                                                                      style="color: red">*</i></label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                                            <select name="employee_setting_id"
                                                    class="form-control js-example-basic-single">
                                                <option value="">{{__('Select Employee Category')}}</option>
                                                @foreach(\App\Models\EmployeeSetting::whereStatus(1)->get() as $emp)
                                                    <option value="{{$emp->id}}"
                                                        {{$employeeData->employee_setting_id === $emp->id ? 'selected' : ''}}>
                                                        {{$emp->name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                            {{input_error($errors,'employee_setting_id')}}
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{__('Employee Name In Arabic')}} <i class="req-star" style="color: red">*</i></label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                                            <input class="form-control" name="name_ar" value="{{$employeeData->name_ar}}"
                                                   placeholder="{{__('Employee Name In Arabic')}}">
                                            {{input_error($errors,'name_ar')}}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> {{__('Employee Name In English')}} <i class="req-star"
                                                                                      style="color: red">*</i></label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                                            <input class="form-control" name="name_en" value="{{$employeeData->name_en}}"
                                                   placeholder="{{__('Employee Name In English')}}">
                                            {{input_error($errors,'name_en')}}
                                        </div>
                                    </div>
                                </div>
                                </div>

                                <div class="col-md-12">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> {{__('Functional Class')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                                            <input class="form-control" name="Functional_class" value="{{$employeeData->Functional_class}}"
                                                   placeholder="{{__('Functional Class')}}">
                                            {{input_error($errors,'Functional_class')}}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> {{__('Select Country')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-home"></i></span>
                                            <select name="country_id" class="form-control js-example-basic-single"
                                                    id="country">
                                                <option value="">{{__('Select Country')}}</option>
                                                @foreach(\App\Models\Country::all() as $country)
                                                    <option value="{{$country->id}}"
                                                        {{$employeeData->country_id === $country->id ? 'selected' : ''}}>
                                                        {{$country->name}}</option>
                                                @endforeach
                                            </select>
                                            {{input_error($errors,'country_id')}}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> {{__('Select City')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-home"></i></span>
                                            <select name="city_id" class="form-control js-example-basic-single" id="city">
                                                <option value="">{{__('Select City')}}</option>
                                                @foreach(\App\Models\City::all() as $city)
                                                    <option value="{{$city->id}}"
                                                        {{$employeeData->city_id === $city->id ? 'selected' : ''}}>
                                                        {{$city->name}}</option>
                                                @endforeach
                                            </select>
                                            {{input_error($errors,'city_id')}}
                                        </div>
                                    </div>
                                </div>
                                </div>

                                <div class="col-md-12">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> {{__('Select Area')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-home"></i></span>
                                            <select name="area_id" class="form-control js-example-basic-single" id="area">
                                                <option value="">{{__('Select Area')}}</option>
                                                @foreach(\App\Models\Area::all() as $area)
                                                    <option value="{{$area->id}}"
                                                        {{$employeeData->area_id === $area->id ? 'selected' : ''}}>
                                                        {{$area->name}}</option>
                                                @endforeach
                                            </select>
                                            {{input_error($errors,'city_id')}}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{__('Address')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-home"></i></span>
                                            <input class="form-control" name="address" value="{{$employeeData->address}}" placeholder="{{__('Address')}}">
                                            {{input_error($errors,'address')}}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{__('Phone1')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                                            <input class="form-control" name="phone1" value="{{$employeeData->phone1}}" placeholder="{{__('Phone1')}}">
                                            {{input_error($errors,'phone1')}}
                                        </div>
                                    </div>
                                </div>
                                </div>

                                <div class="col-md-12">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{__('Phone2')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                                            <input class="form-control" name="phone2" value="{{$employeeData->phone2}}" placeholder="{{__('Phone2')}}">
                                            {{input_error($errors,'phone2')}}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> {{__('Select Nationality')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-home"></i></span>
                                            <select name="national_id" class="form-control js-example-basic-single">
                                                <option value="">{{__('Select Nationality')}}</option>
                                                @foreach(\App\Models\Country::all() as $country)
                                                    <option value="{{$country->id}}"
                                                        {{$employeeData->national_id === $country->id ? 'selected' : ''}}>
                                                        {{$country->name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                            {{input_error($errors,'national_id')}}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{__('ID Number')}}</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                        <input class="form-control" name="id_number" placeholder="{{__('ID Number')}}"
                                               value="{{$employeeData->id_number}}">
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-12">


                        <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{__('Number Card Work')}}</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-gear"></i></span>
                                        <input type="text" class="form-control" name="number_card_work"
                                               value="{{$employeeData->number_card_work}}" placeholder="{{__('Number Card Work')}}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label> {{__('End Date of ID Number')}}</label>
                                    <input type="date" class="form-control time" name="end_date_id_number" value="{{$employeeData->end_date_id_number}}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label> {{__('Starting date of stay')}}</label>
                                    <input type="date" class="form-control time" name="start_date_stay" value="{{$employeeData->start_date_stay}}" >
                                </div>
                            </div>

                        </div>

                        <div class="col-md-12">

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label> {{__('expiration date of stay')}}</label>
                                    <input type="date" class="form-control time" name="end_date_stay" value="{{$employeeData->end_date_stay}}" >
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label> {{__('Date of hiring')}}</label>
                                    <input type="date" class="form-control time" name="start_date_assign" value="{{$employeeData->start_date_assign}}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label> {{__('expiration date of the health insurance')}}</label>
                                    <input type="date" class="form-control time" name="end_date_health" value="{{$employeeData->end_date_health}}">
                                </div>
                            </div>

                            </div>

                            <div class="col-md-12">

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{__('E-Mail')}}</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-yahoo"></i></span>
                                        <input type="text" class="form-control" name="email" placeholder="{{__('E-Mail')}}"  value="{{$employeeData->email}}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{__('CV')}}</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-file-o"></i></span>
                                        <input type="file" class="form-control" name="cv" id="cv" placeholder="{{__('CV')}}" value="{{$employeeData->cv}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">

                                <label>{{__('Show CV')}}</label>

                                    <button type="button" {{ $employeeData->cv ? '' : 'disabled' }}
                                        onclick="viewCV(event, '{{asset('/employees/cv/').'/'.$employeeData->cv}}')"
                                        class="form-control btn btn-warning">{{__('view cv')}}</button>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            @php
                                $balance_details = $employeeData->direct_balance();
                            @endphp
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label> {{ __("words.employee-debit") }} </label>
                                    <input disabled value="{{ $balance_details['debit'] }}"
                                        class="form-control"/>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label> {{ __("words.employee-credit") }} </label>
                                    <input disabled value="{{ $balance_details['credit'] }}"
                                        class="form-control"/>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{__('Notes')}}</label>
                                    <div class="input-group">
                                        <textarea class="form-control" name="notes" placeholder="{{__('Notes')}}">
                                            {{$employeeData->notes}}
                                        </textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{__('Rating')}}</label>
                                    <div class="rate">
                                        <input type="radio" id="star5" name="rating" value="5" {{$employeeData->rating === 5 ? 'checked' : ''}}>
                                        <label for="star5" title="text">5 stars</label>
                                        <input type="radio" id="star4" name="rating" value="4" {{$employeeData->rating === 4 ? 'checked' : ''}}>
                                        <label for="star4" title="text">4 stars</label>
                                        <input type="radio" id="star3" name="rating" value="3"  {{$employeeData->rating === 3 ? 'checked' : ''}}>
                                        <label for="star3" title="text">3 stars</label>
                                        <input type="radio" id="star2" name="rating" value="2" {{$employeeData->rating === 2 ? 'checked' : ''}}>
                                        <label for="star2" title="text">2 stars</label>
                                        <input type="radio" id="star1" name="rating" value="1" {{$employeeData->rating === 1 ? 'checked' : ''}}>
                                        <label for="star1" title="text">1 star</label>
                                    </div>
                                </div>
                            </div>

                            
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="inputPhone" class="control-label">{{__('Status')}}</label>
                                <div class="switch primary" style="margin-top: 15px">
                                    <input type="hidden" name="status" value="0">
                                    <input type="checkbox" id="switch-1" name="status" value="1" CHECKED>
                                    <label for="switch-1">{{__('Active')}}</label>
                                </div>
                            </div>
                        </div>
                        </div>
                        </div>

                        <div class="col-md-12">
                            <div class="col-md-12">
                                <div class="form-group">
                                    @include('admin.buttons._save_buttons')
                                </div>
                            </div>
                        </div>
                </form>
            </div>
        </div>
    </div>
    </div>
  
@endsection
@section('js-validation')
    {!! JsValidator::formRequest('App\Http\Requests\Admin\EmployeeData\EmployeeDataUpdateRequest', '.form'); !!}
    @include('admin.partial.sweet_alert_messages')
    <script type="application/javascript">
        $("#country").change(function () {
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

        function viewCV(event, cv) {
            event.preventDefault();
            if (window.navigator && window.navigator.msSaveOrOpenBlob) {
                window.navigator.msSaveOrOpenBlob(newBlob);
                return;
            };
            window.open(cv,'resizable,scrollbars');
            return false;
        }
    </script>
@endsection
