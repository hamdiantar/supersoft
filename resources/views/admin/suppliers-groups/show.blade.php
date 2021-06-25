@extends('admin.layouts.app')
@section('title')
    <title>{{ __('Super Car') }} - {{ __('Show Supplier Group') }} </title>
@endsection
@section('content')

    <div class="row small-spacing">

    <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin:suppliers-groups.index')}}"> {{__('Suppliers Groups')}}</a></li>               
                <li class="breadcrumb-item active"> {{__('Show Supplier Group')}}</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-xs-12">
                <div class="box-content card">
                    <h4 class="box-title" style="text-align: initial"><i class="fa fa-user ico"></i>{{__('Show Supplier Group')}}
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
                                    <div class="col-xs-5"><label> {{__('Supplier Group Name')}}:</label></div>
                                    <!-- /.col-xs-5 -->
                                    <div class="col-xs-7">{{$suppliers_group->name}}</div>
                                    <!-- /.col-xs-7 -->
                                </div>
                                <!-- /.row -->
                            </div>
                            <!-- /.col-md-6 -->
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-xs-5"><label>{{__('Discount Type')}}:</label></div>
                                    <!-- /.col-xs-5 -->
                                    <div class="col-xs-7">{{$suppliers_group->discount_type}}</div>
                                    <!-- /.col-xs-7 -->
                                </div>
                                <!-- /.row -->
                            </div>

                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-xs-5"><label>{{__('Discount')}}:</label></div>
                                    <!-- /.col-xs-5 -->
                                    <div class="col-xs-7">{{$suppliers_group->discount}}</div>
                                    <!-- /.col-xs-7 -->
                                </div>
                                <!-- /.row -->
                            </div>

                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-xs-5"><label>{{__('Status')}}:</label></div>
                                    <!-- /.col-xs-5 -->
                                    <div class="col-xs-7">{{$suppliers_group->active}}</div>
                                    <!-- /.col-xs-7 -->
                                </div>
                                <!-- /.row -->
                            </div>

                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-xs-5"><label>{{__('Description')}}:</label></div>
                                    <!-- /.col-xs-5 -->
                                    <div class="col-xs-7">{{$suppliers_group->description}}</div>
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
