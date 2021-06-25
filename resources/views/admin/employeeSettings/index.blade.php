@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('Employees Setting') }} </title>
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('accounting-module/daily-restriction.css') }}"/>
@endsection

@section('content')
    <div class="row small-spacing">

    <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Employees Setting')}}</li>
            </ol>
        </nav>

        @include('admin.employeeSettings.parts.search')

        <div class="clearfix"></div>
        @if(session()->has("custom-message"))
            <div class="alert alert-danger">
                <strong> {{ session("custom-message") }} </strong>
            </div>
        @endif

        <div class="col-xs-12">
        <div class="box-content card bordered-all js__card">
                <h4 class="box-title bg-secondary with-control">
                <i class="fa fa-user"></i>  {{__('The Employees Setting')}}
                 </h4>

                 <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">

                        <li class="list-inline-item">
                            @include('admin.buttons.add-new', [
                           'route' => 'admin:employee_settings.create',
                           'new' => '',
                          ])
                        </li>
             
                        <li class="list-inline-item">
                            @component('admin.buttons._confirm_delete_selected',[
                            'route' => 'admin:employee_settings.deleteSelected',
                             ])
                            @endcomponent
                        </li>
              
                    </ul>
                    <div class="clearfix"></div>
                    <div class="table-responsive">
                        @php
                            $view_path = 'admin.employeeSettings.options-datatable';
                        @endphp
                        @include($view_path . '.option-row')
                        <div class="clearfix"></div>
                        <table id="expenseReceipts" class="table table-bordered" style="width:100%;margin-top:15px">
                            @include($view_path . '.table-thead')
                            <tfoot>
                            <tr>
                                <th class="text-center column-name" scope="col">{!! __('Employee Category Name') !!}</th>
                                <th class="text-center column-type" scope="col">{!! __('Employee Category Type') !!}</th>
                                <th class="text-center column-status" scope="col">{!! __('Status') !!}</th>
                                <th class="text-center column-created-at" scope="col">{!! __('Created at') !!}</th>
                                <th class="text-center column-updated-at" scope="col">{!! __('Updated at') !!}</th>
                                <th scope="col">{!! __('Options') !!}</th>
                                <th scope="col">{!! __('Select') !!}</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($employees as $emp)
                                <tr>
                                    <td class="text-center column-name">{{$emp->name}}</td>
                                    <td class="text-center column-type">
                                    <span class="label label-primary wg-label">
                                        {{$emp->type_account}}
                                    </span>
                                    </td>
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

                                    <td class="text-center column-created-at">{!! $emp->created_at->format('y-m-d h:i:s A') !!}</td>
                                    <td class="text-center column-updated-at">{!! $emp->updated_at->format('y-m-d h:i:s A') !!}</td>
                                    <td>

                                    @component('admin.employeeSettings.show',[
                                                      'id'=> $emp->id,
                                                      'employeeSetting'=> $emp,
                                                      'shifts'=> \App\Models\Shift::all() ,
                                                       ])
                                            @endcomponent
                                        @component('admin.buttons._edit_button',[
                                                    'id'=> $emp->id,
                                                    'route' => 'admin:employee_settings.edit',
                                                     ])
                                        @endcomponent

                                        @component('admin.buttons._delete_button',[
                                                    'id'=> $emp->id,
                                                    'route' => 'admin:employee_settings.destroy',
                                                     ])
                                        @endcomponent

                                    </td>
                                    <td>
                                    @component('admin.buttons._delete_selected',[
                                       'id' =>  $emp->id,
                                         'route' => 'admin:employee_settings.deleteSelected',
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
@section('js')
    <script type="application/javascript">
        // invoke_datatable($('#expenseReceipts'))
    </script>
    <script type="application/javascript" src="{{ asset('accounting-module/options-for-dt.js') }}"></script>
@endsection

@section('modals')
    @include($view_path . '.column-visible')
@endsection
