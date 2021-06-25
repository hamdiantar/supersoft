
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
                <h4 class="box-title" style="text-align: initial"><i class="fa fa-info-circle ico"></i>{{__('Type Info')}}
                <span class="controls hidden-sm hidden-xs pull-left">
							<button class="control text-white" style="background:none;border:none;font-size:12px">حفظ<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f1.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px">تفريغ<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f2.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px">إلغاء <img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f3.png')}}"></button>

						</span>
            </h4>

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
