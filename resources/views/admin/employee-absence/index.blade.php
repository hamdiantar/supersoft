@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('Employees Absences / vacations') }} </title>
@endsection

@section('content')
    <link rel="stylesheet" href="{{ asset('accounting-module/daily-restriction.css') }}"/>
    <div class="row small-spacing">
        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"> {{ __('Employees Absences / vacations') }}</li>
            </ol>
        </nav>

        @if(filterSetting())
        <div class="col-xs-12">
        <div class="box-content card bordered-all js__card top-search">
        <h4 class="box-title with-control">
        <i class="fa fa-search"></i>  {{__('Search filters')}}
            <span class="controls">
							<button type="button" class="control fa fa-minus js__card_minus"></button>
							<button type="button" class="control fa fa-times js__card_remove"></button>
						</span>
        </h4>
            <div class="card-content js__card_content">
                <form id="filtration-form">
                    <input type="hidden" name="rows" value="{{ isset($_GET['rows']) ? $_GET['rows'] : '' }}"/>
                    <input type="hidden" name="key" value="{{ isset($_GET['key']) ? $_GET['key'] : '' }}"/>
                    <input type="hidden" name="sort_method" value="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : '' }}"/>
                    <input type="hidden" name="sort_by" value="{{ isset($_GET['sort_by']) ? $_GET['sort_by'] : '' }}"/>
                    <input type="hidden" name="invoker"/>
                    @if (authIsSuperAdmin())
                    <div class="col-md-12">
                        <div class="form-group">
                            <label> {{ __('Branch') }} </label>
                            <select class="form-control select2" name="branch" onchange="changeEmployees(event)">
                                <option value=""> {{ __('Select Branch') }} </option>
                                @foreach(\App\Models\Branch::all() as $branch)
                                    <option {{ isset($_GET['branch']) && $_GET['branch'] == $branch->id ? 'selected' : '' }}
                                        value="{{ $branch->id }}"> {{ $branch->name }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endif

                    <div class="col-md-3">
                        <div class="form-group">
                            <label> {{ __('Date From') }} </label>
                            <input name="date_from" type="date" class="form-control date"/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label> {{ __('Date To') }} </label>
                            <input name="date_to" type="date" class="form-control date"/>

                        </div>
                    </div>
    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label> {{ __('Employee Name') }} </label>
                            <select class="form-control select2" name="name">
                                <option value=""> {{ __('Select Employee Name') }} </option>
                                @foreach($employees as $emp)
                                    <option {{ isset($_GET['name']) && $_GET['name'] == $emp->id ? 'selected' : '' }}
                                        value="{{ $emp->id }}"> {{ $emp->name }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label> {{ __('Operation Type') }} </label>
                            <ul class="list-inline">
                                <li>
                                    <div class="radio info">
                                        <input type="radio" name="type" value="absence"
                                            {{ isset($_GET['type']) && $_GET['type'] == 'absence' ? 'checked' : '' }}
                                            id="radio-10"><label for="radio-10">{{ __('Absence') }}</label></div>
                                    <!-- /.radio -->
                                </li>
                                <li>
                                    <div class="radio pink">
                                        <input type="radio" name="type" value="vacation"
                                            {{ isset($_GET['type']) && $_GET['type'] == 'vacation' ? 'checked' : '' }}
                                            id="radio-11"><label for="radio-11">{{ __('Vacation') }}</label></div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <button type="submit"
                                class="btn sr4-wg-btn   waves-effect waves-light hvr-rectangle-out"><i
                                    class=" fa fa-search "></i> {{__('Search')}} </button>
                        <a href="{{ route('admin:employee-absence.index') }}"
                           class="btn bc-wg-btn   waves-effect waves-light hvr-rectangle-out"><i class=" fa fa-reply"></i> {{__('Back')}}
                        </a>

                </form>
            </div>
        </div>
        </div>
        @endif


        <div class="col-xs-12">
        <div class="box-content card bordered-all js__card">
                <h4 class="box-title bg-secondary with-control">
                <i class="fa fa-user"></i>   {{ __('Employees Employees Absences / vacations') }}
                 </h4>

                <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">
                        <li class="list-inline-item">
                            @include('admin.buttons.add-new', [
                                'route' => 'admin:employee-absence.create',
                                'new' => '',
                            ])
                        </li>
                        <li class="list-inline-item">
                            @component('admin.buttons._confirm_delete_selected',[
                                'route' => 'admin:employee-absence.delete_selected',
                            ])
                            @endcomponent
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                    <div class="table-responsive">
                        @php
                            $view_path = 'admin.employee-absence.options-datatable';
                        @endphp
                        @include($view_path . '.option-row')
                        <div class="clearfix"></div>
                        <table id="datatable-with-btns" class="table table-striped table-bordered display" style="width:100%;margin-top:15px">
                            @include($view_path . '.table-thead')
                            <tfoot>
                                <tr>
                                    <th class="text-center column-name" scope="col"> {{ __('Employee Name') }} </th>
                                    <th class="text-center column-type" scope="col"> {{ __('Operation Type') }} </th>
                                    <th class="text-center column-days" scope="col"> {{ __('The days') }} </th>
                                    <th class="text-center column-date" scope="col"> {{ __('Date') }} </th>
                                    <th class="text-center column-created-at" scope="col">{!! __('Created at') !!}</th>
                                    <th class="text-center column-updated-at" scope="col">{!! __('Updated at') !!}</th>
                                    <th scope="col">{!! __('Options') !!}</th>
                                    <th scope="col">{!! __('Select') !!}</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                @foreach($absences as $emp)
                                    <tr>
                                        <td class="text-center column-name"> {{ optional($emp->employee)->name }} </td>
                                        <td class="text-center column-type">
                                        @if($emp->absence_type == 'absence' )
                                            <span class="label label-primary wg-label"> {{ __('Absence') }} </span>
                                        @else
                                        <span class="label label-danger wg-label"> {{ __('Vacation') }} </span>
                                        @endif
                                        </td>
                                        <td class="text-danger text-center column-date"> {{ $emp->absence_days }} </td>
                                        <td class="text-center column-date"> {{ $emp->date }} </td>
                                        <td class="text-center column-created-at"> {{ $emp->created_at }} </td>
                                        <td class="text-center column-updated-at"> {{ $emp->updated_at }} </td>
                                        <td>
                                            @component('admin.buttons._edit_button',[
                                                'id'=> $emp->id,
                                                'route' => 'admin:employee-absence.edit',
                                            ])
                                            @endcomponent
                                            @component('admin.buttons._delete_button',[
                                                'id'=> $emp->id,
                                                'route' => 'admin:employee-absence.delete',
                                            ])
                                            @endcomponent
                                        </td>
                                        <td>
                                            @component('admin.buttons._delete_selected',[
                                                'id' =>  $emp->id,
                                                'route' => 'admin:employee-absence.delete_selected',
                                            ])
                                            @endcomponent
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $absences->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('modals')
    @include($view_path . '.column-visible')
@endsection

@section('js')
    <script type="application/javascript">
        $(document).ready(function () {
            // invoke_datatable($('#datatable-with-btns'))
            $('input[name="date_from"]').val("{{ isset($_GET['date_from']) ? $_GET['date_from'] : '' }}")
            $('input[name="date_to"]').val("{{ isset($_GET['date_to']) ? $_GET['date_to'] : '' }}")
            $(".select2").select2()
        })

        function changeEmployees(event) {
            var branch_id = $(event.target).find("option:selected").val()
            var employees = []
            @foreach($employees as $emp)
                employees.push({
                    id: {{ $emp->id }},
                    name: '{{ $emp->name }}',
                    branch_id: {{ $emp->branch_id }}
                })
            @endforeach
            var select_options = "<option value=''>{{__('Select One')}}</option>"
            employees.map(function(employee) {
                if (employee.branch_id == branch_id || branch_id == '') {
                    select_options += `<option value="${employee.id}">${employee.name}</option>`
                }
            })
            $('select[name="name"]').html(select_options)
            $('select[name="name"]').select2()
        }
    </script>
    <script type="application/javascript" src="{{ asset('accounting-module/options-for-dt.js') }}"></script>
@stop
