@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('Create Employees Setting') }} </title>
@endsection


@section('content')
    <div class="row small-spacing">
    <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin:employee_settings.index')}}"> {{__('Employees Setting')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Create Employees Setting')}}</li>
            </ol>
        </nav>

        <div class="col-xs-12">
        <div class=" card box-content-wg-new bordered-all primary">
                <h4 class="box-title with-control" style="text-align: initial"><i class="fa fa-user"></i>  {{__('Create Employees Setting')}}
                <span class="controls hidden-sm hidden-xs pull-left">
                <button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Save')}}<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f1.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Reset')}}<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f2.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px"> {{__('Back')}} <img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f3.png')}}"></button>
						</span>
            </h4>
                    <div class="box-content">
                    <form method="post" action="{{route('admin:employee_settings.store')}}" class="form">
                        @csrf
                        @method('post')
                        <div class="row">
                        @if (authIsSuperAdmin())
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label> {{__('Select Branch')}} <i class="req-star" style="color: red">*</i></label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                                            <select name="branch_id" class="form-control js-example-basic-single" onchange="getShiftsByBranch(event)">
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
                                            <label> {{__('Select Shift')}} <i class="req-star" style="color: red">*</i></label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                                                <select name="shift_id" class="form-control js-example-basic-single" id="setShiftByBranch">
                                                    <option value="">{{__('Select Shift')}}</option>
                                                    @foreach($shifts as $shift)
                                                        <option value="{{$shift->id}}">{{$shift->name}}</option>
                                                    @endforeach
                                                </select>
                                                {{input_error($errors,'shift_id')}}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{__('Employee Category Arabic Name')}} <i class="req-star" style="color: red">*</i></label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                                        <input class="form-control" name="name_ar" placeholder="{{__('Employee Category Arabic Name')}}">
                                            {{input_error($errors,'name_ar')}}
                                    </div>
                                    </div>
                                </div>


                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> {{__('Employee Category English Name')}}  <i class="req-star" style="color: red">*</i></label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                                        <input class="form-control" name="name_en" placeholder="{{__('Employee Category English Name')}}">
                                            {{input_error($errors,'name_en')}}
                                    </div>
                                    </div>
                                </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="col-md-4" style="padding:0">
                                        <div class="form-group">
                                            <label> {{__('Employee Category Type')}} </label>
                                            <ul class="list-inline">
                                                <li>
                                                    <div class="radio info">
                                                        <input type="radio" name="type_account" value="work_card" checked="" id="radio-10">
                                                        <label for="radio-10">{{__('Percent Card Work')}}</label>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="radio info">
                                                        <input type="radio" name="type_account" value="days" checked="" id="radio-11">
                                                        <label for="radio-11">{{__('Days')}}</label>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="radio pink">
                                                        <input type="radio" name="type_account" value="month" id="radio-12">
                                                        <label for="radio-12">{{__('Monthly')}}</label>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-md-2" id="card-work-percent">
                                        <div class="form-group">
                                            <label> {{__('Percent Card Value')}} </label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                                                <input class="form-control" name="card_work_percent"
                                                    placeholder="{{__('Percentage')}}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                    <div class="form-group">
                                        <label> {{__('Account Amount')}}  <i class="req-star" style="color: red">*</i></label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                                        <input type="text" class="form-control time" name="amount_account" value="0" placeholder="{{__('Account Amount')}}">
                                    </div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>{{__('Max Advance')}}  <i class="req-star" style="color: red">*</i></label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                                        <input type="text" class="form-control" name="max_advance" value="0" placeholder="{{__('Max Advance')}}">
                                    </div>
                                    </div>
                                </div>
                           
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>{{__('Daily Work Hours')}}  <i class="req-star" style="color: red">*</i></label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                        <input type="number" class="form-control" name="daily_working_hours" placeholder="{{__('')}}">
                                    </div>
                                    </div>
                                </div>


                                
                                </div>
                           
               

                            <div class="col-md-12">

                            <div class="col-md-2">
                                <div class="form-group has-feedback col-sm-12">
                                    <label for="inputPhone" class="control-label">{{__('Status')}}</label>
                                    <div class="switch primary" style="margin-top: 15px">
                                        <input type="hidden"  name="status" value="0">
                                        <input type="checkbox" id="switch-1" name="status" value="1" CHECKED>
                                        <label for="switch-1">{{__('Active')}}</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group has-feedback col-sm-12">
                                    <label for="inputServiceStatus" class="control-label">{{__('Service Status')}}</label>
                                    <div class="switch primary" style="margin-top: 15px">
                                        <input type="checkbox" id="switch-service-status" name="service_status"
                                               value="1" CHECKED
                                        >
                                        <label for="switch-service-status">{{__('Active')}}</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                    <div class="form-group">
                                        <label> {{__('Attendance Time')}}  <i class="req-star" style="color: red">*</i></label>
                                        <input type="time" class="form-control time" name="time_attend" value="{{now()->format('h:i')}}" placeholder="{{__('Attendance Time')}}">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label> {{__('Leaving Time')}}  <i class="req-star" style="color: red">*</i></label>
                                        <input type="time" class="form-control time" name="time_leave" value="{{now()->format('h:i')}}" placeholder="{{__('Leaving Time')}}">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{__('Yearly Vacations Days')}}  <i class="req-star" style="color: red">*</i></label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-calendar-times-o"></i></span>
                                        <input type="number" class="form-control" name="annual_vocation_days" placeholder="{{__('Days')}}">
                                    </div>
                                    </div>
                                </div>



                            </div>

                            <div class="col-md-12">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>{{__('Absence Type')}}</label>
                                        <ul class="list-inline">
                                            <li>
                                                <div class="radio info">
                                                    <input type="radio" name="type_absence" value="discount_day" checked="" id="radio-91"><label for="radio-91">{{__('Deducted Day')}}</label></div>
                                            </li>
                                            <li>
                                                <div class="radio pink">
                                                    <input type="radio" name="type_absence" value="fixed_salary" id="radio-92"><label for="radio-92">{{__('Static Amount')}}</label></div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>{{__('Absence Day Equal To')}}<i class="req-star" style="color: red">*</i></label>
                                        <input type="text" class="form-control" name="type_absence_equal" value="1" placeholder="{{__('Absence Day Equal To')}}">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label> {{__('Delay Hours')}} </label>
                                        <ul class="list-inline">
                                            <li>
                                                <div class="radio info">
                                                    <input type="radio" name="hourly_delay" value="hourly_delay" checked="" id="radio-93"><label for="radio-93">{{__('Delay Hour')}}</label></div>
                                            </li>
                                            <li>
                                                <div class="radio pink">
                                                    <input type="radio" name="hourly_delay" value="fixed_salary" id="radio-94"><label for="radio-94">{{__('Static Amount')}}</label></div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>{{__('Delay Hour Equal To')}}<i class="req-star" style="color: red">*</i></label>
                                        <input class="form-control" name="hourly_delay_equal" value="1" placeholder="{{__('Delay Hour Equal To')}}">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label> {{__('Additional Hours')}} </label>
                                        <ul class="list-inline">
                                            <li>
                                                <div class="radio info">
                                                    <input type="radio" name="hourly_extra" value="hourly_extra" checked="" id="radio-95"><label for="radio-95">{{__('Additional Hour')}}</label></div>
                                                <!-- /.radio -->
                                            </li>
                                            <li>
                                                <div class="radio pink">
                                                    <input type="radio" name="hourly_extra" value="fixed_salary" id="radio-96"><label for="radio-96">{{__('Static Amount')}}</label></div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label> {{__('Additional Hour Equal To')}}  <i class="req-star" style="color: red">*</i></label>
                                        <input class="form-control" name="hourly_extra_equal" value="1" placeholder="{{__('Additional Hour Equal To')}}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{__('Weekly Official Vacations')}}</label>
                                        <ul class="list-inline">
                                            <li>
                                                <div class="checkbox circled info">
                                                    <input type="checkbox" id="checkbox-circled-sat" name="saturday" value="1">
                                                    <label for="checkbox-circled-sat">{{__('Saturday')}}</label>
                                                </div>
                                                <!-- /.checkbox -->
                                            </li>
                                            <li>
                                                <div class="checkbox circled info">
                                                    <input type="checkbox" id="checkbox-circled-sun" name="sunday" value="1">
                                                    <label for="checkbox-circled-sun">{{__('Sunday')}}</label>
                                                </div>
                                                <!-- /.checkbox -->
                                            </li>
                                            <li>
                                                <div class="checkbox circled info">
                                                    <input type="checkbox" id="checkbox-circled-mon" name="monday" value="1">
                                                    <label for="checkbox-circled-mon">{{__('Monday')}}</label>
                                                </div>
                                                <!-- /.checkbox -->
                                            </li>
                                            <li>
                                                <div class="checkbox circled info">
                                                    <input type="checkbox" id="checkbox-circled-tue" name="tuesday" value="1">
                                                    <label for="checkbox-circled-tue">{{__('Tuesday')}}</label>
                                                </div>
                                                <!-- /.checkbox -->
                                            </li>
                                            <li>
                                                <div class="checkbox circled info">
                                                    <input type="checkbox" id="checkbox-circled-wed" name="wednesday" value="1">
                                                    <label for="checkbox-circled-wed">{{__('Wednesday')}}</label>
                                                </div>
                                                <!-- /.checkbox -->
                                            </li>
                                            <li>
                                                <div class="checkbox circled info">
                                                    <input type="checkbox" id="checkbox-circled-thu" name="thursday" value="1">
                                                    <label for="checkbox-circled-thu">{{__('Thursday')}}</label>
                                                </div>
                                                <!-- /.checkbox -->
                                            </li>
                                            <li>
                                                <div class="checkbox circled info">
                                                    <input type="checkbox" id="checkbox-circled-fri" name="friday" value="1">
                                                    <label for="checkbox-circled-fri">{{__('Friday')}}</label>
                                                </div>
                                                <!-- /.checkbox -->
                                            </li>
                                        </ul>
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
    {!! JsValidator::formRequest('App\Http\Requests\Admin\EmployeeSettings\EmployeeSettingCreateRequest', '.form'); !!}
    @include('admin.partial.sweet_alert_messages')
    <script type="application/javascript">
        function getShiftsByBranch(event) {
            let branchId = event.target.value
            $.ajax({
                url: "{{ url('admin/employeeSetting/getShiftsByBranch') }}?branch_id=" + branchId,
                method: 'GET',
                success: function (data) {
                    $('#setShiftByBranch').html(data.shifts);
                }
            });
        }
    </script>
@endsection
