@extends('admin.layouts.app')
@section('style')
    <style>
        /* USER PROFILE PAGE */
        .card {
            margin-top: 20px;
            padding: 30px;
            background-color: rgba(214, 224, 226, 0.2);
            -webkit-border-top-left-radius: 5px;
            -moz-border-top-left-radius: 5px;
            border-top-left-radius: 5px;
            -webkit-border-top-right-radius: 5px;
            -moz-border-top-right-radius: 5px;
            border-top-right-radius: 5px;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }

        .card.hovercard {
            position: relative;
            padding-top: 0;
            overflow: hidden;
            text-align: center;
            background-color: #fff;
            background-color: rgba(255, 255, 255, 1);
        }

        .card.hovercard .card-background {
            height: 130px;
        }

        .card-background img {
            -webkit-filter: blur(25px);
            -moz-filter: blur(25px);
            -o-filter: blur(25px);
            -ms-filter: blur(25px);
            filter: blur(25px);
            margin-left: -100px;
            margin-top: -200px;
            min-width: 130%;
        }

        .card.hovercard .useravatar {
            position: absolute;
            top: 15px;
            left: 0;
            right: 0;
        }

        .card.hovercard .useravatar img {
            width: 100px;
            height: 100px;
            max-width: 100px;
            max-height: 100px;
            -webkit-border-radius: 50%;
            -moz-border-radius: 50%;
            border-radius: 50%;
            border: 5px solid rgba(255, 255, 255, 0.5);
        }

        .card.hovercard .card-info {
            position: absolute;
            bottom: 14px;
            left: 0;
            right: 0;
        }

        .card.hovercard .card-info .card-title {
            padding: 0 5px;
            font-size: 20px;
            line-height: 1;
            color: #262626;
            background-color: rgba(255, 255, 255, 0.1);
            -webkit-border-radius: 4px;
            -moz-border-radius: 4px;
            border-radius: 4px;
        }

        .card.hovercard .card-info {
            overflow: hidden;
            font-size: 12px;
            line-height: 20px;
            color: #737373;
            text-overflow: ellipsis;
        }

        .card.hovercard .bottom {
            padding: 0 20px;
            margin-bottom: 17px;
        }

        .btn-pref .btn {
            -webkit-border-radius: 0 !important;
        }


    </style>
@endsection
@section('title')
    <title>{{ __('Super Car') }} - {{ __('Profile') }} </title>
@endsection

@section('content')
    <div class="row small-spacing">
        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active">
                <li class="breadcrumb-item active"> {{__('Profile')}}</li>
            </ol>
        </nav>

        <div class="col-lg-12 col-sm-10" style="padding: 0% 0% 0% 10%">
            <div class="card hovercard" style=" background-color: #fbbc0542;">
                <div class="card-background">
                </div>
                <div class="useravatar">
                    @php
                        $defaultImage = "http://iagonline.org/wp-content/uploads/2016/02/admin.png";
                    @endphp
                    <img alt="" id="output_image" src="{{$user->image ? url('storage/images/profiles/').'/'.$user->image : $defaultImage}}">
                </div>
                <div class="card-info"><span class="card-title chageName">{{$user->name}}</span>

                </div>
            </div>
            <div class="btn-pref btn-group btn-group-justified btn-group-lg" role="group" aria-label="...">
                <div class="btn-group" role="group">
                    <button type="button" id="btPersonalInfo" class="btn btn-primary" href="#tab1" data-toggle="tab">
                        <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
                        <div class="hidden-xs changePersonalInfo">{{__('Personal Info')}}</div>
                    </button>
                </div>
                <div class="btn-group" role="group">
                    <button type="button" id="btfavourites" class="btn btn-default" href="#tab2" data-toggle="tab"><span
                            class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                        <div class="hidden-xs">{{__('Update Personal Info')}}</div>
                    </button>
                </div>
            </div>

            <div class="well">
                <div class="tab-content">
                    <div class="tab-pane fade in active" id="tab1">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered">
                                    <tbody>
                                    <th width="50%">{{__('Name')}}</th>
                                    <td>{{$user->name}}</td>
                                    </tbody>
                                </table>
                                <!-- /.row -->
                            </div>
                            <!-- /.col-md-6 -->
                            <div class="col-md-12">
                                <table class="table table-bordered">
                                    <tbody>
                                    <th width="50%">{{__('Email')}}</th>
                                    <td>{{$user->email}}</td>
                                    </tbody>
                                </table>

                            </div>
                            <!-- /.col-md-6 -->
                            <div class="col-md-12">
                                <table class="table table-bordered">
                                    <tbody>
                                    <th width="50%">{{__('Phone')}}</th>
                                    <td>{{$user->phone}}</td>
                                    </tbody>
                                </table>

                            </div>

                            <div class="col-md-12">

                                <table class="table table-bordered">
                                    <tbody>
                                    <th width="50%">{{__('Branch')}}</th>
                                    <td>{{optional($user->branch)->name}}</td>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.col-md-6 -->
                        </div>
                    </div>
                    <div class="tab-pane fade in favbook" id="tab2">
                        <form method="post" action="{{route('admin:profile.update', $user->id)}}" class="form"  enctype="multipart/form-data">
                            @method('put')
                            @csrf
                            @include('admin.profile.edit')
                        </form>
                    </div>
                    <div class="tab-pane fade in clpay" id="tab3">
                        <div>This is tab 3</div>
                    </div>
                </div>
            </div>

        </div>


    </div>
    {{--    <!-- /.row small-spacing -->--}}
@endsection
@section('js-validation')
    @include('admin.partial.sweet_alert_messages')
    {!! JsValidator::formRequest('App\Http\Requests\ProfileRequest', '.form'); !!}
    <script type="application/javascript">
        $(document).ready(function () {
            $(".btn-pref .btn").click(function () {
                $(".btn-pref .btn").removeClass("btn-primary").addClass("btn-default");
                $(this).removeClass("btn-default").addClass("btn-primary");
            });
            $("#btPersonalInfo").on(click, function () {
            });
        });
    </script>

@endsection
