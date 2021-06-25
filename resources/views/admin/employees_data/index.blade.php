@extends('admin.layouts.app')
@include('admin.employees_data.parts.rating')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('Employees Data') }} </title>
@endsection

@section('content')
<link rel="stylesheet" href="{{ asset('accounting-module/daily-restriction.css') }}"/>
    <div class="row small-spacing">
        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Employees Data')}}</li>
            </ol>
        </nav>
        @include('admin.employees_data.parts.search')

        <div class="clearfix"></div>
        @if(session()->has("custom-message"))
            <div class="alert alert-danger">
                <strong> {{ session("custom-message") }} </strong>
            </div>
        @endif

        <div class="col-xs-12">
        <div class="box-content card bordered-all js__card">
                <h4 class="box-title bg-secondary with-control">
                <i class="fa fa-user"></i>  {{__('Employees Data')}}
                 </h4>

                 <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">
                        <li class="list-inline-item">
                            @include('admin.buttons.add-new', [
                           'route' => 'admin:employees_data.create',
                           'new' => '',
                          ])
                        </li>
                        <li class="list-inline-item">
                            @component('admin.buttons._confirm_delete_selected',[
                            'route' => 'admin:employees_data.deleteSelected',
                             ])
                            @endcomponent
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                    <div class="table-responsive">
                        @php
                            $view_path = 'admin.employees_data.options-datatable';
                        @endphp
                        @include($view_path . '.option-row')
                        <div class="clearfix"></div>
                        <table id="employeeData" class="table table-bordered" style="width:100%;margin-top:15px">
                            @include($view_path . '.table-thead')
                            <tfoot>
                            <tr>
                                <th class="text-center column-name" scope="col">{!! __('Employee Name') !!}</th>
                                <th class="text-center column-setting" scope="col">{!! __('Employee Category') !!}</th>
                                <!-- <th scope="col">{!! __('Functional Class') !!}</th> -->
                                <th class="text-center column-phone" scope="col">{!! __('Phone') !!}</th>

                                <th class="text-center column-status" scope="col">{!! __('Status') !!}</th>
                                <th class="text-center column-debit" scope="col">{!! __('words.employee-debit') !!}</th>
                                <th class="text-center column-credit" scope="col">{!! __('words.employee-credit') !!}</th>
                                <th class="text-center column-created-at" scope="col">{!! __('Created at') !!}</th>
                                <th class="text-center column-updated-at" scope="col">{!! __('Updated at') !!}</th>
                                <th scope="col">{!! __('Options') !!}</th>
                                <th scope="col">{!! __('Select') !!}</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($employeesData as $emp)
                                @php
                                    $balance = $emp->direct_balance();
                                @endphp
                                <tr>
                                    <td class="text-center column-name">{{$emp->name}}</td>
                                    <td class="text-center column-setting">
                                    <span class="label label-primary wg-label">
                                        {{optional($emp->employeeSetting)->name}}
                                    </span>
                                    </td>
                                    <!-- <td>{{$emp->Functional_class}}</td> -->
                                    <td class="text-center column-phone">{{$emp->phone1 ?? $emp->phone2}}</td>

                                    @if ($emp->status)
                                    <td class="text-center column-status">
                                        <div class="switch success">
                                            <input
                                                    disabled
                                                    type="checkbox"
                                                    {{$emp->status == 1 ? 'checked' : ''}}
                                                    id="switch-{{ $emp->id }}">
                                            <label for="emp-{{ $emp->id }}"></label>
                                        </div>

                                        </td>
                                         @else
                                         <td class="text-center column-status">
                                        <div class="switch success">
                                            <input
                                                    disabled
                                                    type="checkbox"
                                                    {{$emp->status == 1 ? 'checked' : ''}}
                                                    id="switch-{{ $emp->id }}">
                                            <label for="emp-{{ $emp->id }}"></label>
                                        </div>

                                        </td>
                                        @endif
                                    <td class="text-danger text-center column-debit"> {{ $balance['debit'] }} </td>
                                    <td class="text-danger text-center column-credit"> {{ $balance['credit'] }} </td>
                                    <td class="text-center column-created-at">{!! $emp->created_at->format('y-m-d h:i:s A') !!}</td>
                                    <td class="text-center column-updated-at">{!! $emp->updated_at->format('y-m-d h:i:s A') !!}</td>
                                    <td>
                                    @component('admin.employees_data.show',[
                                                  'id'=> $emp->id,
                                                  'employeeData'=> $emp,
                                                   ])
                                        @endcomponent

                                        @component('admin.buttons._edit_button',[
                                                    'id'=> $emp->id,
                                                    'route' => 'admin:employees_data.edit',
                                                     ])
                                        @endcomponent

                                        @component('admin.buttons._delete_button',[
                                                    'id'=> $emp->id,
                                                    'route' => 'admin:employees_data.destroy',
                                                     ])
                                        @endcomponent

                                    </td>
                                    <td>
                                    @component('admin.buttons._delete_selected',[
                                       'id' =>  $emp->id,
                                        'route' => 'admin:employees_data.deleteSelected',
                                        ])
                                        @endcomponent
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $employeesData->links() }}
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
