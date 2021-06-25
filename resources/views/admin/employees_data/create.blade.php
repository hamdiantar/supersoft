@extends('admin.layouts.app')
@include('admin.employees_data.parts.rating')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('Create Employee Data') }} </title>
@endsection

@section('content')
    <div class="row small-spacing">
    <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin:employees_data.index')}}"> {{__('Employees Data')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Create Employee Data')}}</li>
            </ol>
        </nav>
        <div class="col-xs-12">
        <div class=" card box-content-wg-new bordered-all primary">
                <h4 class="box-title with-control" style="text-align: initial"><i class="fa fa-user"></i>  {{__('Create Employee Data')}}
                <span class="controls hidden-sm hidden-xs pull-left">
                <button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Save')}}<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f1.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Reset')}}<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f2.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px"> {{__('Back')}} <img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f3.png')}}"></button>
						</span>
            </h4>
                    <div class="box-content">
                    <form method="post" action="{{route('admin:employees_data.store')}}" class="form" enctype="multipart/form-data">
                        @csrf
                        @method('post')
                        <div class="row">
                          
                                @if (authIsSuperAdmin())
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label> {{__('Select Branch')}} <i class="req-star" style="color: red">*</i></label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                                                <select name="branch_id" class="form-control js-example-basic-single" onchange="getEmpSettingByBranch(event)">
                                                    <option value="">{{__('Select Branch')}}</option>
                                                    @foreach(\App\Models\Branch::all() as $branch)
                                                        <option value="{{$branch->id}}">{{$branch->name}}</option>
                                                    @endforeach
                                                </select>
                                                {{input_error($errors,'branch_id')}}
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <input type="hidden" name="branch_id" value="{{ auth()->user()->branch_id }}"/>
                                @endif

                                <div class="col-md-12">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> {{__('Select Employee Category')}} <i class="req-star"
                                                                                      style="color: red">*</i></label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                                            <select name="employee_setting_id"
                                                    class="form-control js-example-basic-single" id="empSettingByBranch">
                                                <option value="">{{__('Select Employee Category')}}</option>
                                                @foreach(\App\Models\EmployeeSetting::whereStatus(1)->get() as $emp)
                                                    <option value="{{$emp->id}}">{{$emp->name}}</option>
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
                                            <input class="form-control" name="name_ar"
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
                                            <input class="form-control" name="name_en"
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
                                            <input class="form-control" name="Functional_class"
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
                                                    <option value="{{$country->id}}">{{$country->name}}</option>
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
                                            <select name="city_id" class="form-control js-example-basic-single"
                                                    id="city">
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
                                            <select name="area_id" class="form-control js-example-basic-single"
                                                    id="area">
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
                                            <input class="form-control" name="address" placeholder="{{__('Address')}}">
                                            {{input_error($errors,'address')}}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{__('Phone1')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                                            <input class="form-control" name="phone1" placeholder="{{__('Phone1')}}">
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
                                            <input class="form-control" name="phone2" placeholder="{{__('Phone2')}}">
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
                                                    <option value="{{$country->id}}">{{$country->name}}</option>
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
                                        <input class="form-control" name="id_number" placeholder="{{__('ID Number')}}">
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
                                        <input type="text" class="form-control" name="number_card_work" placeholder="{{__('Number Card Work')}}">
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group">
                                    <label> {{__('End Date of ID Number')}}</label>
                                    <input type="date" class="form-control time" name="end_date_id_number" value="" >
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label> {{__('Starting date of stay')}}</label>
                                    <input type="date" class="form-control time" name="start_date_stay" value="" >
                                </div>
                            </div>


                        </div>

                        <div class="col-md-12">


                            <div class="col-md-4">
                                <div class="form-group">
                                    <label> {{__('expiration date of stay')}}</label>
                                    <input type="date" class="form-control time" name="end_date_stay" value="" >
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label> {{__('Date of hiring')}}</label>
                                    <input type="date" class="form-control time" name="start_date_assign" value="{{now()->format('Y-m-d')}}" >
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label> {{__('expiration date of the health insurance')}}</label>
                                    <input type="date" class="form-control time" name="end_date_health" value="" >
                                </div>
                            </div>

                            </div>

                            <div class="col-md-12">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{__('E-Mail')}}</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-yahoo"></i></span>
                                        <input type="text" class="form-control" name="email" placeholder="{{__('E-Mail')}}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{__('CV')}}</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-file-o"></i></span>
                                        <input type="file" id="cv" class="form-control" name="cv" placeholder="{{__('CV')}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                <label>{{__('Show CV')}}</label>
                                    <button type="button" onclick="viewCV(event)" class="form-control btn btn-warning">{{__('view cv')}}</button>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{__('Notes')}}</label>
                                    <div class="input-group">
<textarea class="form-control" name="notes" placeholder="{{__('Notes')}}">
</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{__('Rating')}}</label>
                                    <div class="rate">
                                        <input type="radio" id="star5" name="rating" value="5"/>
                                        <label for="star5" title="text">5 stars</label>
                                        <input type="radio" id="star4" name="rating" value="4" />
                                        <label for="star4" title="text">4 stars</label>
                                        <input type="radio" id="star3" name="rating" value="3" />
                                        <label for="star3" title="text">3 stars</label>
                                        <input type="radio" id="star2" name="rating" value="2" />
                                        <label for="star2" title="text">2 stars</label>
                                        <input type="radio" id="star1" name="rating" value="1" />
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
                        <div class="col-md-12">
                            <div class="col-md-12">
                                <div class="form-group">
                                    @include('admin.buttons._save_buttons')
                                </div>
                            </div>
                        </div>
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
    {!! JsValidator::formRequest('App\Http\Requests\Admin\EmployeeData\EmployeeDataCreateRequest', '.form'); !!}
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
        function getEmpSettingByBranch(event) {
            let branchId = event.target.value
            $.ajax({
                url: "{{ url('admin/getEmpSettingByBranch') }}?branch_id=" + branchId,
                method: 'GET',
                success: function (data) {
                    $('#empSettingByBranch').html(data.empSetting);
                }
            });
        }
        function viewCV(event) {
            event.preventDefault();
            var input = document.querySelector('input[type=file]');
            var newBlob = new Blob([input.files[0]], {type: "application/pdf"})
            if (window.navigator && window.navigator.msSaveOrOpenBlob) {
                window.navigator.msSaveOrOpenBlob(newBlob);
                return;
            }
            const data = window.URL.createObjectURL(newBlob);
            var link = document.createElement('a');
            link.href = data;
            window.open(link.href,'resizable,scrollbars');
            return false;
        }
    </script>
@endsection
