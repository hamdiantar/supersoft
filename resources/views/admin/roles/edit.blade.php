@extends('admin.layouts.app')

@section('title')
    <title>{{__('update role')}} </title>
@endsection

@section('content')
    <div class="row small-spacing">


        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin:roles.index')}}"> {{__('Roles')}}</a></li>
                <li class="breadcrumb-item active"> {{__('update role')}}</li>
            </ol>
        </nav>

        <div class="col-xs-12">
            <div class=" card box-content-wg-new bordered-all primary">
                <h4 class="box-title with-control" style="text-align: initial"><i class="ico fa fa-user"></i>
                    {{__('update role')}}
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
                </h4>
                <div class="box-content">

                    <form action="{{route('admin:roles.update',$role->id)}}" method="post" class="form">
                        @csrf
                        @method('PATCH')
                        @include('admin.roles.form')
                    </form>
                </div>
                <!-- /.box-content -->
            </div>
            <!-- /.col-xs-12 -->
        </div>

    </div>
    <!-- /.row small-spacing -->
@endsection
@section('js-validation')
    {!! JsValidator::formRequest('App\Http\Requests\Admin\Authorization\UpdateRoleRequest', '.form'); !!}
    @include('admin.partial.sweet_alert_messages')

    <script type="application/javascript">

        function checkThisModule(name) {

            if ($('#select-' + name + '-all').is(':checked')) {

                $(".checked_" + name).prop("checked", true);

            } else {

                $(".checked_" + name).prop("checked", false);
            }
        }

    </script>
@endsection



