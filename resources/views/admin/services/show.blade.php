
@extends('admin.layouts.app')
@section('title')
<title>{{ __('Super Car') }} - {{__('Service Info')}}</title>
@endsection
@section('content')

<div class="row small-spacing">


<nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
            <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item"><a href="{{route('admin:services.index')}}"> {{__('Services')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Service Info')}}</li>
            </ol>
        </nav>


 



    <div class="row">
        <div class="col-xs-12">
        <div class=" card box-content-wg-new bordered-all primary">
                    <h1 class="box-title bg-info" style="text-align: initial"><i class="fa fa-info-circle ico"></i>{{__('Service Info')}}
                    <span class="controls hidden-sm hidden-xs pull-left">
							<button class="control text-white" style="background:none;border:none;font-size:12px">حفظ<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f1.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px">تفريغ<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f2.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px">إلغاء <img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f3.png')}}"></button>

						</span>
                </h1>
                <div class="box-content">
                <div class="card-content">
                    <div class="row">

                    <div class="card-content wg-for-dt">
                        <div class="row">

                        <div class="col-md-6">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody>

                                        <tr>
                                        <th scope="row">{{__('Service Name Ar')}}</th>
                                        <td>{{$service->name_ar}}</td>
                                        </tr> 

                                        <tr>
                                        <th scope="row">{{__('Branch')}}</th>
                                        <td>{{optional($service->branch)->name}}</td>
                                        </tr> 

                                        <tr>
                                        <th scope="row">{{__('Price')}}</th>
                                        <td>{{$service->price}}</td>
                                        </tr> 

                                        <tr>
                                        <th scope="row">{{__('Minutes')}}</th>
                                        <td>{{$service->minutes}}</td>
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
                                        <th scope="row">{{__('Service Name En')}}</th>
                                        <td>{{$service->name_en}}</td>
                                        </tr> 

                                        <tr>
                                        <th scope="row">{{__('Status')}}</th>
                                        <td>{{$service->active}}</td>
                                        </tr> 

                                        <tr>
                                        <th scope="row">{{__('Hours')}}</th>
                                        <td>{{$service->hours}}</td>
                                        </tr> 

                                        <tr>
                                        <th scope="row">{{__('Description')}}</th>
                                        <td>{{$service->description}}</td>
                                        </tr> 
                       

                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- /.col-md-6 -->
                        </div>
                        <!-- /.row -->
                    </div>                    
                        

                    </div>
                    <!-- /.row -->
                    </div>
                    </div>
 
            <!-- /.box-content card -->
            <a href="{{route('admin:services.index')}}"
                                class="btn btn-danger waves-effect waves-light"><i class=" fa fa-reply"></i> {{__('Back')}}  
                                </a>  
        </div>


    </div>
</div>
</div>
@endsection
