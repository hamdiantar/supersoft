@extends('admin.layouts.app')
@section('title')
    <title>{{ __('Super Car') }} - {{ __('Services Package Archive') }} </title>
@endsection
@section('content')
    <div class="row small-spacing">
        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a
                        href="{{route('admin:services_packages.index')}}"> {{__('Services Package')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Services Package Archive')}}</li>
            </ol>
        </nav>

        @include('admin.services_packages.parts.search')
        <div class="col-xs-12">
            <div class="box-content card bordered-all js__card">
                <h4 class="box-title bg-secondary with-control">
                    <i class="fa fa-gear"></i>
                    {{ __('Services Package Archive') }}
                </h4>

                <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">
                        <li class="list-inline-item">
                            @component('admin.buttons._confirm_force_delete_selected',[
                          'route' => 'admin:services_packages.deleteSelected',
                           ])
                            @endcomponent
                        </li>

                        <li class="list-inline-item">
                            @component('admin.buttons._confirm_restore_selected',[
                          'route' => 'admin:services_packages.restoreSelected',
                           ])
                            @endcomponent
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                    <div class="table-responsive">

                        <table id="services_packages" class="table table-bordered" style="width:100%">
                            <thead>
                            <tr>
                                <th scope="col">{!! __('#') !!}</th>
                                <th scope="col">{!! __('Service Package') !!}</th>
                                <th scope="col">{!! __('Total Before Discount') !!}</th>
                                <th scope="col">{!! __('Total After Discount') !!}</th>
                                <th scope="col">{!! __('Hours') !!}</th>
                                <th scope="col">{!! __('Minutes') !!}</th>
                                <th scope="col">{!! __('Created at') !!}</th>
                                <th scope="col">{!! __('Updated at') !!}</th>
                                <th scope="col">{!! __('Options') !!}</th>
                                <th scope="col">
                                    <div class="checkbox danger">
                                        <input type="checkbox" id="select-all">
                                        <label for="select-all"></label>
                                    </div>{!! __('Select') !!}</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th scope="col">{!! __('#') !!}</th>
                                <th scope="col">{!! __('Service Package') !!}</th>
                                <th scope="col">{!! __('Total Before Discount') !!}</th>
                                <th scope="col">{!! __('Total After Discount') !!}</th>
                                <th scope="col">{!! __('Hours') !!}</th>
                                <th scope="col">{!! __('Minutes') !!}</th>
                                <th scope="col">{!! __('Created at') !!}</th>
                                <th scope="col">{!! __('Updated at') !!}</th>
                                <th scope="col">{!! __('Options') !!}</th>
                                <th scope="col">{!! __('Select') !!}</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($servicesPackages as $index=>$package)
                                <tr>
                                    <td>{!! $index+1 !!}</td>
                                    <td>{!! $package->name !!}</td>
                                    <td class="text-danger">{!! $package->total_before_discount !!}</td>
                                    <td class="text-danger">{!! $package->total_after_discount !!}</td>
                                    @php

                                        $hours = floor($package->number_of_min  / 60);
                                        $min = ($package->number_of_min  -   floor($package->number_of_min  / 60) * 60);
                                    @endphp
                                    <td>{!! $package->number_of_hours + $hours!!}</td>
                                    <td>{!! $min !!}</td>
                                    <td>{!! $package->created_at->format('y-m-d h:i:s A') !!}</td>
                                    <td>{!! $package->updated_at->format('y-m-d h:i:s A') !!}</td>
                                    <td>

                                        @component('admin.buttons._force_delete',[
                                                                       'id' => $package->id,
                                                                       'route'=>'admin:services_packages.force_delete'
                                                                        ])
                                        @endcomponent


                                        @component('admin.buttons._restore_delete',[
                                                     'id'=>$package->id,
                                                     'route' => 'admin:services_packages.restore_delete',
                                                     'tooltip' => __('Force Delete '.$package['name']),
                                                      ])
                                        @endcomponent
                                    </td>
                                    <td>

                                        @component('admin.buttons._delete_selected',[
                                                      'id' => $package->id,
                                                      'route' => 'admin:services_packages.deleteSelected',
                                                       ])
                                        @endcomponent
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script type="application/javascript">
        invoke_datatable($('#services_packages'))
    </script>
@endsection
