@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('Edit Absence / vacation') }} </title>
@endsection

@section('content')
<div class="row small-spacing">
<nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin:employee-absence.index') }}"> {{__('Employees Rewards / Discounts')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Edit Absence / vacation')}}</li>
            </ol>
        </nav>
        <div class="col-xs-12">
        <div class=" card box-content-wg-new bordered-all primary">
                <h4 class="box-title with-control" style="text-align: initial"><i class="fa fa-user"></i>  {{__('Edit Absence / vacation')}}
                <span class="controls hidden-sm hidden-xs pull-left">
                <button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Save')}}<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f1.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Reset')}}<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f2.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px"> {{__('Back')}} <img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f3.png')}}"></button>
						</span>
            </h4>
                    <div class="box-content">
            <form method="post" action="{{ route('admin:employee-absence.update' ,['employee_absence' => $absence->id]) }}">
                @csrf
                <input type="hidden" name="_method" value="PUT"/>
                <input type="hidden" name="id" value="{{ $absence->id }}"/>
                <div class="row">

                    <div class="col-md-12" id="employee-allowed-vacation-parent">
                        <div class="form-group">
                            <div class="alert alert-info" role="alert" style="font-size: 20px; text-align: center; color: red">
                                {{ __('words.allowed-vacations-for') }} <span id="employee-name">{{ $absence->employee->name }}</span>
                                <a id="employee-allowed-vacation" class="alert-link"> {{ $absence->employee->my_allowed_vacations() }} </a>
                            </div>
                        </div>
                    </div>

                    @if (authIsSuperAdmin())
                        <div class="col-md-12">
                            <div class="form-group">
                                <label> {{ __('Branch') }} </label>
                                <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-file-text"></i></span>                                               
                                <select class="form-control select2" name="branch_id" onchange="branch_employee()">
                                    <option value=""> {{ __('Select Branch') }} </option>
                                    @foreach($branches as $branch)
                                        <option {{ $absence->branch_id == $branch->id ? 'selected' : '' }}
                                            value="{{ $branch->id }}"> {{ $branch->name }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        </div>
                        @endif

                        <div class="col-md-12">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> {{ __('Employee Name') }} </label>
                                <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-user"></i></span>   
                                <select class="form-control select2" name="employee_id" onchange="load_my_vacations(event)">
                                    <option value=""> {{ __('Select Employee Name') }} </option>
                                    @foreach($employees as $emp)
                                        @php
                                            if ($emp->branch_id != $absence->branch_id) continue;
                                        @endphp
                                        <option {{ $absence->employee_id == $emp->id ? 'selected' : '' }} data-my-allowed-vacations="{{ $emp->my_allowed_vacations() }}"
                                            value="{{ $emp->id }}"> {{ $emp->name }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label> {{ __('Date') }} </label>
                                <input type="date" value="{{now()->format('Y-m-d')}}" class="form-control" name="date" placeholder="{{ __('date') }}"/>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label> {{ __('Operation Type') }} </label>
                                <ul class="list-inline">
                                    <li>
                                        <div class="radio info">
                                            <input type="radio" name="absence_type" value="absence"
                                                {{ $absence->absence_type == "absence" ? "checked" : "" }}
                                                id="radio-91"><label for="radio-91">{{ __('Absence') }}</label></div>
                                        <!-- /.radio -->
                                    </li>
                                    <li>
                                        <div class="radio pink">
                                            <input type="radio" name="absence_type" value="vacation"
                                                {{ $absence->absence_type == "vacation" ? "checked" : "" }}
                                                id="radio-92"><label for="radio-92">{{ __('Vacation') }}</label></div>
                                    </li>
                                </ul>
                            </div>
                        </div>


                    </div>
                    
                    <div class="col-md-12">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label> {{ __('The Days') }} </label>
                                <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-file-text"></i></span>   
                                <input class="form-control" name="absence_days" value="{{ $absence->absence_days }}"
                                    placeholder="{{ __('The Days') }}"/>
                            </div>
                        </div>
                        </div>

                        <div class="col-md-8">
                            <div class="form-group">
                                <label> {{ __('Notes') }} </label>
                                <textarea class="form-control" name="notes" placeholder="{{ __('Notes') }}">{{ $absence->notes }}</textarea>
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
</div>
</div>
@stop

@section('js')
    {!! JsValidator::formRequest('App\Http\Requests\EmployeeAbsenceReq'); !!}
    <script type="application/javascript">
        $(document).ready(function() {
            $(".select2").select2()
            @if(authIsSuperAdmin())
                branch_employee()
            @endif
            $("select[name='employee_id']").change()
        })

        function branch_employee() {
            $("#employee-allowed-vacation-parent").hide()
            var branch_id = $("select[name='branch_id'] option:selected").val(),
                selected_employee = {{ $absence->employee_id }},
                my_accessible_employees = []
            @foreach($employees as $emp)
                my_accessible_employees.push({
                    id: {{ $emp->id }},
                    name: '{{ $emp->name }}',
                    branch_id: {{ $emp->branch_id }},
                    my_allowed_vacations: {{ $emp->my_allowed_vacations() }}
                })
            @endforeach
            var select_options = "<option value=''> {{ __('Select One') }} </option>"
            my_accessible_employees.map(function(employee) {
                if (branch_id == '' || branch_id == employee.branch_id) {
                    is_selected = selected_employee == employee.id ? 'selected' : ''
                    select_options += `<option ${is_selected} data-my-allowed-vacations='${employee.my_allowed_vacations}'
                        value='${employee.id}'> ${employee.name} </option>`
                }
            })
            console.log(select_options)
            $("select[name='employee_id']").html(select_options)
            $(".select2").select2()
        }

        function load_my_vacations(event) {
            var allowed_vacations = $(event.target).find("option:selected").data("my-allowed-vacations")
            if (!$(event.target).find("option:selected").val()) $("#employee-allowed-vacation-parent").hide()
            else {
                $("#employee-allowed-vacation").text(allowed_vacations)
                $("#employee-name").text('"' + $(event.target).find("option:selected").text() + '"')
                $("#employee-allowed-vacation-parent").show()
            }
        }
    </script>
@stop
