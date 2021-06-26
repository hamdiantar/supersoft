@extends('admin.layouts.app')

@section('title')
<title>{{__('Edit Rule data')}} </title>
@endsection

@section('content')
    <div class="row small-spacing">

    <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
            <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin:users.index')}}"> {{__('Points Rules')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Edit Rule data')}}</li>
            </ol>
        </nav>
        <div class="col-xs-12">
        <div class=" card box-content-wg-new bordered-all primary">
					<h4 class="box-title with-control" style="text-align: initial"><i class="ico fa fa-user"></i>
                {{__('Edit Rule data')}}
                <span class="controls hidden-sm hidden-xs pull-left">
                      <button class="control text-white"
                              style="background:none;border:none;font-size:14px;font-weight:normal !important;">{{__('Save')}}
                      <img class="img-fluid" style="width:40px;height:40px;margin-top:-15px;margin-bottom:-13px"
                           src="{{asset('assets/images/f1.png')}}">
                  </button>
                        <button class="control text-white"    style="background:none;border:none;font-size:14px;font-weight:normal !important;">
                            {{__('Reset')}}
                            <img class="img-fluid" style="width:40px;height:40px;margin-top:-15px;margin-bottom:-13px"
                                 src="{{asset('assets/images/f2.png')}}"></button>
							<button class="control text-white"    style="background:none;border:none;font-size:14px;font-weight:normal !important;"> {{__('Back')}} <img
                                    class="img-fluid"
                                    style="width:40px;height:40px;margin-top:-15px;margin-bottom:-13px"
                                    src="{{asset('assets/images/f3.png')}}"></button>
						</span>
                </h4>
                <div class="card-content">
                    <form method="post" action="{{route('admin:points-rules.update', $pointsRule->id)}}" class="form">
                        @method('PATCH')
                        @csrf
                        @include('admin.points_rules.form')
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /.box-content -->

@endsection
@section('js-validation')
    {!! JsValidator::formRequest('App\Http\Requests\Admin\PointsRules\PointsRuleRequest', '.form'); !!}
    @include('admin.partial.sweet_alert_messages')
@endsection
