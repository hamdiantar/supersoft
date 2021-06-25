
@extends('admin.layouts.app')
@section('title')
    <title>{{ __('Super Car') }} - {{__('Show Maintenance Detection')}} </title>
@endsection
@section('content')

<div class="row small-spacing">


<nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin:maintenance-detections.index')}}"> {{__('Maintenance Detection')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Show Maintenance Detection')}} </li>
            </ol>
        </nav>


    <div class="row">
        <div class="col-xs-12">

        <div class=" card box-content-wg-new bordered-all primary">
                    <h1 class="box-title bg-info" style="text-align: initial"><i class="fa fa-info-circle ico"></i>{{__('Show Maintenance Detection')}}
                    <span class="controls hidden-sm hidden-xs pull-left">
							<button class="control text-white" style="background:none;border:none;font-size:12px">حفظ<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f1.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px">تفريغ<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f2.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px">إلغاء <img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f3.png')}}"></button>

						</span>
                </h1>
                <div class="box-content">

                <div class="card-content wg-for-dt">
                        <div class="row">

                        <div class="col-md-6">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody>

                                        <tr>
                                        <th scope="row">{{__('Maintenance Detection Name Ar')}}</th>
                                        <td>{{$maintenanceDetection->name_ar}}</td>
                                        </tr> 

                                        <tr>
                                        <th scope="row">{{__('Branch')}}</th>
                                        <td>{{optional($maintenanceDetection->branch)->name}}</td>
                                        </tr> 

                                        <tr>
                                        <th scope="row">{{__('Type')}}</th>
                                        <td>{{optional($maintenanceDetection->maintenanceType)->name}}</td>
                                        </tr> 

 

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody>

                                       <tr>
                                        <th scope="row">{{__('Maintenance Detection Name En')}}</th>
                                        <td>{{$maintenanceDetection->name_en}}</td>
                                        </tr> 

                                        <tr>
                                        <th scope="row">{{__('Status')}}</th>
                                        <td>{{$maintenanceDetection->active}}</td>
                                        </tr> 

                                        <tr>
                                        <th scope="row">{{__('Description')}}</th>
                                        <td>{{$maintenanceDetection->description}}</td>
                                        </tr>                                         

                                    </tbody>
                                </table>
                            </div>
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
</div>
@endsection
