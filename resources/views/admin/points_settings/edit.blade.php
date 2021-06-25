@extends('admin.layouts.app')

@section('title')
    <title>{{__('Points settings')}} </title>
@endsection

@section('content')
    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Points settings')}}</li>
            </ol>
        </nav>

        <div class="col-xs-12">
            <div class=" card box-content-wg-new bordered-all primary">
                <h1 class="box-title bg-info" style="text-align: initial"><i class="fa fa-gear"></i>
                    {{__('Points settings')}}
                    <span class="controls hidden-sm hidden-xs pull-left">
                    <button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Save')}}<img
                            class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px"
                            src="{{asset('assets/images/f1.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Reset')}}<img
                                    class="img-fluid"
                                    style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px"
                                    src="{{asset('assets/images/f2.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px"> {{__('Back')}} <img
                                    class="img-fluid"
                                    style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px"
                                    src="{{asset('assets/images/f3.png')}}"></button>

						</span>
                </h1>
                <div class="box-content">

                    <form method="post" action="{{route('admin:points.settings.update')}}" class="form">
                        @csrf
                        @method('post')

                        <div class="row">

                            <div class="col-md-12">
                                <div class="col-md-6">
                                    <div class="form-group col-md-6">
                                        <div class="form-group">
                                            <label for="date" class="control-label">{{__('Amount money')}}</label>

                                            <input type="text" name="amount" class="form-control"
                                                   value="{{isset($setting) ? $setting->amount : 0 }}">

                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group col-md-6">
                                        <div class="form-group">
                                            <label for="date" class="control-label">{{__('Points')}}</label>
                                            <input type="number" name="points" class="form-control"
                                                   value="{{isset($setting) ? $setting->points : 0 }}">

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        @include('admin.buttons._save_buttons')
                                    </div>
                                </div>

                            </div>
                        </div>

                    </form>
                </div>

            </div>
            <!-- /.box-content -->
        </div>
        <!-- /.col-xs-12 -->
    </div>
    <!-- /.row small-spacing -->


@endsection

@section('js-validation')
        {!! JsValidator::formRequest('App\Http\Requests\Admin\PointSettings\CreateRequest', '.form'); !!}
    @include('admin.partial.sweet_alert_messages')
@endsection

@section('js')

@endsection


