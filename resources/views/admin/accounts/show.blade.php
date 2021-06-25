
@extends('admin.layouts.app')
@section('title')
    <h1 class="page-title">
        <a style="color: white" href="{{route('admin:services-types.index')}}">
            {{__('Services types')}} </a>
        >
        {{__('Show')}}
    </h1>
@endsection
@section('content')

<div class="row small-spacing">

    <div class="row">
        <div class="col-xs-12">
            <div class="box-content card">
                <h4 class="box-title"><i class="fa fa-info-circle ico"></i>{{__('Type Info')}}</h4>
                <!-- /.box-title -->
                <div class="dropdown js__drop_down">
                    <a href="#" class="dropdown-icon glyphicon glyphicon-option-vertical js__drop_down_button"></a>
                    <ul class="sub-menu">
                        <li><a href="#">Action</a></li>
                        <li><a href="#">Another action</a></li>
                        <li><a href="#">Something else there</a></li>
                        <li class="split"></li>
                        <li><a href="#">Separated link</a></li>
                    </ul>
                    <!-- /.sub-menu -->
                </div>
                <!-- /.dropdown js__dropdown -->
                <div class="card-content">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-xs-5"><label> {{__('Name En')}}:</label></div>
                                <!-- /.col-xs-5 -->
                                <div class="col-xs-7">{{$services_type->name_en}}</div>
                                <!-- /.col-xs-7 -->
                            </div>
                            <!-- /.row -->
                        </div>

                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-xs-5"><label> {{__('Name Ar')}}:</label></div>
                                <!-- /.col-xs-5 -->
                                <div class="col-xs-7">{{$services_type->name_ar}}</div>
                                <!-- /.col-xs-7 -->
                            </div>
                            <!-- /.row -->
                        </div>

                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-xs-5"><label>{{__('Branch')}}:</label></div>
                                <!-- /.col-xs-5 -->
                                <div class="col-xs-7">{{optional($services_type->branch)->name}}</div>
                                <!-- /.col-xs-7 -->
                            </div>
                            <!-- /.row -->
                        </div>

                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-xs-5"><label>{{__('Status')}}:</label></div>
                                <!-- /.col-xs-5 -->
                                <div class="col-xs-7">{{$services_type->active}}</div>
                                <!-- /.col-xs-7 -->
                            </div>
                            <!-- /.row -->
                        </div>

                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-xs-5"><label>{{__('Description')}}:</label></div>
                                <!-- /.col-xs-5 -->
                                <div class="col-xs-7">{{$services_type->description}}</div>
                                <!-- /.col-xs-7 -->
                            </div>
                            <!-- /.row -->
                        </div>
                        <!-- /.col-md-6 -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.card-content -->
            </div>
            <!-- /.box-content card -->
        </div>


    </div>
</div>
@endsection
