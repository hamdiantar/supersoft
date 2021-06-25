@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('Edit Employee additional / Delay') }} </title>
@endsection

@section('content')
    <div class="row small-spacing">
    <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin:employees_delay.index')}}"> {{__('Employees additional / Delay')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Edit Employee additional / Delay')}}</li>
            </ol>
        </nav>
        <div class="col-xs-12">
        <div class=" card box-content-wg-new bordered-all primary">
                <h4 class="box-title with-control" style="text-align: initial"><i class="fa fa-user"></i>  {{__('Edit Employee additional / Delay')}}
                <span class="controls hidden-sm hidden-xs pull-left">
                <button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Save')}}<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f1.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Reset')}}<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f2.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px"> {{__('Back')}} <img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f3.png')}}"></button>
						</span>
            </h4>
                    <div class="box-content">
                    <form method="post" action="{{route('admin:employees_delay.update', ['id' => $employeeDelay->id])}}" class="form">
                        @csrf
                        @method('put')

                        @if (authIsSuperAdmin())
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label> {{__('Branch')}} <i class="req-star" style="color: red">*</i></label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                                                <select name="branch_id" class="form-control js-example-basic-single" onchange="getEmpByBranch(event)">
                                                    <option value="">{{__('Select Branch')}}</option>
                                                    @foreach(\App\Models\Branch::all() as $branch)
                                                        <option value="{{$branch->id}}"
                                                        {{$employeeDelay->branch_id === $branch->id ? 'selected' : ''}}>
                                                            {{$branch->name}}</option>
                                                    @endforeach
                                                </select>
                                                {{input_error($errors,'branch_id')}}
                                            </div>
                                        </div>
                                    </div>
                                @endif

                        <div class="row">

                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> {{__('Employee Name')}} <i class="req-star" style="color: red">*</i></label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                            <select name="employee_data_id" class="form-control js-example-basic-single" id="setEmpByBranch">
                                                <option value="">{{__('Select Employee Name')}}</option>
                                                @foreach(\App\Models\EmployeeData::all() as $emp)
                                                    <option value="{{$emp->id}}"
                                                        {{$employeeDelay->employee_data_id === $emp->id ? 'selected' : ''}}>
                                                        {{$emp->name}}</option>
                                                @endforeach
                                            </select>
                                            {{input_error($errors,'employee_data_id')}}
                                        </div>
                                    </div>
                                </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label> {{__('Date')}}  <i class="req-star" style="color: red">*</i></label>
                                            <input type="date" class="form-control time" name="date" value="{{$employeeDelay->date}}" >
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label> {{__('Operation Type')}}  <i class="req-star" style="color: red">*</i> </label>
                                            <ul class="list-inline">
                                                <li>
                                                    <div class="radio info">
                                                        <input type="radio" name="type" value="delay"
                                                               {{$employeeDelay->type === __('delay') ? 'checked' : ''}}
                                                               id="radio-10"><label
                                                            for="radio-10">{{__('Delay')}}</label></div>
                                                </li>
                                                <li>
                                                    <div class="radio pink">
                                                        <input type="radio" name="type" value="extra"
                                                               {{$employeeDelay->type === __('extra') ? 'checked' : ''}}
                                                               id="radio-12"><label
                                                            for="radio-12">{{__('Extra')}}</label></div>
                                                </li>
                                            </ul>
                                            {{input_error($errors,'type')}}
                                        </div>
                                      </div>

                                    </div>
                                  

                                    <div class="col-md-12">
                                      <div class="col-md-4">
                                        <div class="form-group">
                                            <label> {{__('Number Of Hours')}}  <i class="req-star" style="color: red">*</i></label>
                                            <div class="input-group">
<span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                            <input type="text" class="form-control time" name="number_of_hours" value="{{$employeeDelay->number_of_hours}}">
                                        </div>
                                      </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label> {{__('Number Of Minutes')}}  <i class="req-star" style="color: red">*</i></label>
                                            <div class="input-group">
<span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                            <input type="text" class="form-control time" name="number_of_minutes" value="{{$employeeDelay->number_of_minutes}}">
                                        </div>
                                      </div>
                                    </div>
                      
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('Notes')}}</label>
                                <div class="input-group">
                                    <textarea class="form-control" name="notes" placeholder="{{__('Notes')}}">
                                        {{$employeeDelay->notes}}
                                    </textarea>
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
            <!-- /.box-content -->
        </div>
        <!-- /.col-xs-12 -->
    </div>
    <!-- /.row small-spacing -->
@endsection
@section('js-validation')
    {!! JsValidator::formRequest('App\Http\Requests\Admin\EmployeeDelay\EmployeeDelayRequest', '.form'); !!}
    @include('admin.partial.sweet_alert_messages')
    <script type="application/javascript">
        function getEmpByBranch(event) {
            let branchId = event.target.value
            $.ajax({
                url: "{{ url('admin/getEmpByBranch') }}?branch_id=" + branchId,
                method: 'GET',
                success: function (data) {
                    $('#setEmpByBranch').html(data.emp);
                }
            });
        }
    </script>
@endsection
