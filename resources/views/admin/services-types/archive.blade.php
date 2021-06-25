@extends('admin.layouts.app')
@section('title')
    <title>{{ __('Super Car') }} - {{ __('Archive Services Types') }} </title>
@endsection
@section('content')
    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"><a
                        href="{{route('admin:services-types.index')}}">  {{__('Services Types')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Archive Services Types')}}</li>
            </ol>
        </nav>


        @if(filterSetting())
            <div class="col-xs-12">
                <div class="box-content card white  js__card top-search">
                    <h4 class="box-title with-control">
                        <i class="fa fa-search"></i>
                        {{__('Search filters')}}
                        <span class="controls">
							<button type="button" class="control fa fa-minus js__card_minus"></button>
							<button type="button" class="control fa fa-times js__card_remove"></button>
						</span>
                    </h4>
                    <!-- /.box-title -->
                    <div class="card-content js__card_content">
                        <form action="{{route('admin:services-types.index')}}" method="get">
                            <div class="list-inline margin-bottom-0 row">

                                @if(authIsSuperAdmin())
                                    <div class="form-group col-md-12">
                                        <label> {{ __('Branch') }} </label>
                                        <select name="branch_id" class="form-control js-example-basic-single">
                                            <option value="">{{__('Select Branch')}}</option>
                                            @foreach($branches as $k=>$v)
                                                <option value="{{$k}}">{{$v}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                                <div class="form-group col-md-4">
                                    <label> {{ __('Service Type') }} </label>
                                    <select name="name" class="form-control js-example-basic-single">
                                        <option value="">{{__('Select Service Name')}}</option>
                                        @foreach($services_types as $k=>$v)
                                            <option value="{{$k}}">{{$v}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="switch primary col-md-4">
                                    <input type="checkbox" id="switch-2" name="active">
                                    <label for="switch-2">{{__('Active')}}</label>
                                </div>
                                <div class="switch primary col-md-4">
                                    <input type="checkbox" id="switch-3" name="inactive">
                                    <label for="switch-3">{{__('inActive')}}</label>
                                </div>
                            </div>

                            <button type="submit"
                                    class="btn sr4-wg-btn   waves-effect waves-light hvr-rectangle-out"><i
                                    class=" fa fa-search "></i> {{__('Search')}} </button>
                            <a href="{{route('admin:services-types.index')}}"
                               class="btn bc-wg-btn   waves-effect waves-light hvr-rectangle-out"><i
                                    class=" fa fa-reply"></i> {{__('Back')}}
                            </a>
                        </form>
                    </div>
                    <!-- /.card-content -->
                </div>
                <!-- /.box-content -->
            </div>
        @endif

        <div class="col-xs-12">
            <div class="box-content card bordered-all js__card">
                <h4 class="box-title bg-secondary with-control">
                    <i class="fa fa-list"></i>
                    {{ __('Archive Services Types') }}
                </h4>

                <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">
                        <li class="list-inline-item">
                            @component('admin.buttons._confirm_force_delete_selected',[
                          'route' => 'admin:services.types.deleteSelected',
                           ])
                            @endcomponent
                        </li>

                        <li class="list-inline-item">
                            @component('admin.buttons._confirm_restore_selected',[
                          'route' => 'admin:services.types.restoreSelected',
                           ])
                            @endcomponent
                        </li>

                    </ul>

                    <div class="clearfix"></div>
                    <div class="table-responsive">

                        <table id="currencies" class="table table-bordered" style="width:100%">
                            <thead>
                            <tr>
                                <th scope="col">{!! __('#') !!}</th>
                                <th scope="col">{!! __('Name') !!}</th>
                            <!-- <th scope="col">{!! __('Branch') !!}</th> -->
                                <th scope="col">{!! __('Status') !!}</th>
                                <th scope="col">{!! __('created at') !!}</th>
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
                                <th scope="col">{!! __('Name') !!}</th>
                            <!-- <th scope="col">{!! __('Branch') !!}</th> -->
                                <th scope="col">{!! __('Status') !!}</th>
                                <th scope="col">{!! __('created at') !!}</th>
                                <th scope="col">{!! __('Updated at') !!}</th>
                                <th scope="col">{!! __('Options') !!}</th>
                                <th scope="col">{!! __('Select') !!}</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($types as $index=>$type)
                                <tr>
                                    <td>{!! $index +1 !!}</td>
                                    <td>{!! $type->name !!}</td>
                                <!-- <td>{!! optional($type->branch)->name !!}</td> -->

                                    @if ($type->status == 1)
                                        <td>
                                            <div class="switch success">
                                                <input
                                                    disabled
                                                    type="checkbox"
                                                    {{$type->status == 1 ? 'checked' : ''}}
                                                    id="switch-{{ $type->id }}">
                                                <label for="type-{{ $type->id }}"></label>
                                            </div>

                                        </td>
                                    @else
                                        <td>
                                            <div class="switch success">
                                                <input
                                                    disabled
                                                    type="checkbox"
                                                    {{$type->status == 1 ? 'checked' : ''}}
                                                    id="switch-{{ $type->id }}">
                                                <label for="type-{{ $type->id }}"></label>
                                            </div>

                                        </td>
                                    @endif

                                    <td>{!! $type->created_at->format('y-m-d h:i:s A') !!}</td>

                                    <td>{!! $type->updated_at->format('y-m-d h:i:s A') !!}</td>

                                    <td>

                                        @component('admin.buttons._force_delete',[
                                                                       'id' => $type->id,
                                                                       'route'=>'admin:services.types.force_delete'
                                                                        ])
                                        @endcomponent


                                        @component('admin.buttons._restore_delete',[
                                                     'id'=>$type->id,
                                                     'route' => 'admin:services.types.restore_delete',
                                                     'tooltip' => __('Force Delete '.$type['name']),
                                                      ])
                                        @endcomponent
                                    </td>
                                    <td>

                                        @component('admin.buttons._delete_selected',[
                                                      'id' => $type->id,
                                                      'route' => 'admin:services.types.deleteSelected',
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
        invoke_datatable($('#currencies'))
    </script>
@endsection
