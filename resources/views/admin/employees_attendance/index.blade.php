@extends('admin.layouts.app')
@include('admin.employees_data.parts.rating')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('Employees Attendance / Departure') }} </title>
@endsection

@section('content')
    <link rel="stylesheet" href="{{ asset('accounting-module/daily-restriction.css') }}"/>
    <div class="row small-spacing">
        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Employees Attendance / Departure')}}</li>
            </ol>
        </nav>
        @include('admin.employees_attendance.parts.search')

        <div class="col-xs-12">
        <div class="box-content card bordered-all js__card">
                <h4 class="box-title bg-secondary with-control">
                 <i class="fa fa-user"></i>  {{__('Employees Attendance / Departure')}}
                 </h4>

                <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">
                        <li class="list-inline-item">
                            @include('admin.buttons.add-new', [
                           'route' => 'admin:employees_attendance.create',
                           'new' => '',
                          ])
                        </li>
      
                        <li class="list-inline-item">
                            @component('admin.buttons._confirm_delete_selected',[
                            'route' => 'admin:employees_attendance.deleteSelected',
                             ])
                            @endcomponent
                        </li>
                    
                    </ul>
                    <div class="clearfix"></div>
                    <div class="table-responsive">
                        @php
                            $view_path = 'admin.employees_attendance.options-datatable';
                        @endphp
                        @include($view_path . '.option-row')
                        <div class="clearfix"></div>
                        <table id="employeeData" class="table table-bordered" style="width:100%;margin-top:15px">
                            @include($view_path . '.table-thead')
                            <tfoot>
                            <tr>
                                <th class="text-center column-name" scope="col">{!! __('Employee Name') !!}</th>
                                <th class="text-center column-status" scope="col">{!! __('Status') !!}</th>
                                <th class="text-center column-date" scope="col">{!! __('Date') !!}</th>
                                <th class="text-center column-time" scope="col">{!! __('Time') !!}</th>
                                <th class="text-center column-created-at" scope="col">{!! __('Created at') !!}</th>
                                <th class="text-center column-updated-at" scope="col">{!! __('Updated at') !!}</th>
                                <th scope="col">{!! __('Options') !!}</th>
                                <th scope="col">{!! __('Select') !!}</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($employees as $emp)
                                <tr>
                                    <td class="text-center column-name">{{optional($emp->employeeDate)->name}}</td>
                                    <td class="text-center column-status">
                                    @if($emp->type === __('attendance') )
                                    <span class="label label-primary wg-label"> {{ __('Attendance') }} </span>
                                    @else
                                    <span class="label label-danger wg-label"> {{ __('Departure') }} </span>
                                    @endif
                                    </td>
                                    <td class="text-center column-date">{{$emp->date}}</td>
                                    <td class="text-center column-time">{{$emp->time}}</td>
                                    <td class="text-center column-created-at">{!! $emp->created_at->format('y-m-d h:i:s A') !!}</td>
                                    <td class="text-center column-updated-at">{!! $emp->updated_at->format('y-m-d h:i:s A') !!}</td>
                                    <td>


                                        @component('admin.buttons._edit_button',[
                                                    'id'=> $emp->id,
                                                    'route' => 'admin:employees_attendance.edit',
                                                     ])
                                        @endcomponent

                                        @component('admin.buttons._delete_button',[
                                                    'id'=> $emp->id,
                                                    'route' => 'admin:employees_attendance.destroy',
                                                     ])
                                        @endcomponent
                                    </td>
                                    <td>
                                    @component('admin.buttons._delete_selected',[
                                       'id' =>  $emp->id,
                                        'route' => 'admin:employees_attendance.deleteSelected',
                                        ])
                                        @endcomponent
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $employees->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modals')
    @include($view_path . '.column-visible')
@endsection

@section('js')
    <script type="application/javascript">
        // invoke_datatable($('#employeeData'))
    </script>
    <script type="application/javascript" src="{{ asset('accounting-module/options-for-dt.js') }}"></script>
@endsection
