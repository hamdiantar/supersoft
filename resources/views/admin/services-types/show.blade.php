
@extends('admin.layouts.app')
@section('title')
<title>{{ __('Super Car') }} - {{ __('Show Services Type') }} </title>
@endsection
@section('content')

<div class="row small-spacing">

<nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
            <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item"><a href="{{route('admin:services-types.index')}}"> {{__('Services Types')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Show Services Type')}}</li>
            </ol>
        </nav>


    <div class="row">
        <div class="col-xs-12">
            <div class="box-content card">
                <h4 class="box-title" style="text-align: initial"><i class="fa fa-info-circle ico"></i>{{__('Show Services Type')}}
                <span class="controls hidden-sm hidden-xs pull-left">
							<button class="control text-white" style="background:none;border:none;font-size:12px">حفظ<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f1.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px">تفريغ<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f2.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px">إلغاء <img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f3.png')}}"></button>

						</span>
            </h4>
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
            <a href="{{route('admin:services-types.index')}}"
                                class="btn btn-danger waves-effect waves-light"><i class=" fa fa-reply"></i> رجوع 
                                </a>
            <!-- /.box-content card -->
        </div>


    </div>
</div>
@endsection
