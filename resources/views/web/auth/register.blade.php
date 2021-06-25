<!DOCTYPE>
<html style="direction: {{app()->getLocale() == 'en' ? 'ltr':'rtl'}};">

<meta http-equiv="content-type" content="text/html;charset=utf-8"/>
<head>
    <meta charset="UTF-8">
    <title>{{__('Super Car')}}</title>

    <link href="https://fonts.googleapis.com/css2?family=Almarai&display=swap" rel="stylesheet">

@if (app()->getLocale() == "ar")
    @include('admin.partial.style-ar')
@endif

<!-- Stylesheets -->
    <link rel="stylesheet" href="{{ asset('assets-ar/Design/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('assets-ar/Design/css/style-login.css') }}">

    <!-- Toastr -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

</head>
<body>
<!-- Page Preloder -->
<div id="preloder">
    <div class="loader">
        <span class="bounce1">R</span>
        <span class="bounce2">A</span>
        <span class="bounce3">C</span>
        <span class="bounce4">R</span>
        <span class="bounce5">E</span>
        <span class="bounce6">P</span>
        <span class="bounce7">U</span>
        <span class="bounce8">S</span>
    </div>
</div>


<div id="div_Header">
    <!-- Audio -->
    <div class="play active" id="btn1"></div>
    <audio id="sound1" autoplay loop>

        <source src='{{asset("sound/sound.mp3")}}'>
    </audio>

    <div class="copyright visible-lg">
        <p>All Rights reserved - Deveoped by <br><img src="" alt=""><a href="#" target="_blank">

                Super M Soft for Advanced solutions</a></p>
    </div>

</div>
<!-- ==== Intro Section Start ==== -->
<form action="{{ route('web:register') }}" method="POST">
@csrf
<!-- <input type="hidden" name="lang" value="{{ isset($_GET['lang']) && in_array($_GET['lang'] ,['ar' ,'en']) ? $_GET['lang'] : config('default_values.language') }}"/> -->
    <div>
        <input type="hidden" name="__VIEWSTATE" id="__VIEWSTATE"
               value="5C7RdYUyINkr1xlAEE5lBHxUxWUViTAuUY/YesJQFpKn7q1lObT6qz0Owb+mkq4R/sN74GLk57QJLJVySSvl5vaaHfpDc0i6BRo5Ug++65I="/>
    </div>

    <div>

        <input type="hidden" name="__VIEWSTATEGENERATOR" id="__VIEWSTATEGENERATOR" value="9571527F"/>
        <input type="hidden" name="__EVENTVALIDATION" id="__EVENTVALIDATION"
               value="vZHwEDYfIzz7ZUbcRAjXQ4U8n1s34V8du0om4PeDrCwbJPG5Ao0Ww+RMN5AqbPQErdk2GPMn+ddXR92bhy+uC6mHo+Pjpz1N/6u01DRorBsHZOgLo7zbq2oFuSHzOKRWbtgFShm4lg0s4Omg1qqa0g=="/>

    </div>
    <div id="pnlLogin">

        <section class="intro-section fix" id="home">
            <div class="intro-bg bg-cms"></div>
            <div class="intro-inner">
                <div class="intro-content">
                    <div id="round"></div>
                    <div id="login-box2">
                        <div class="profile-img">
                            <img src="{{url('default-images/logo.png')}}"/>
                        </div>
                        <h2><span class="element"></span></h2>


                        <div class="icoPos icoPos-wg">
                            <div class="frm-input">
                                <input type="text" placeholder="{{__('Name')}}"
                                       class="frm-inp @error('name') is-invalid @enderror"
                                       name="name" value="{{ old('name') }}"
                                >
                            </div>
                        </div>

                        <div class="icoPos icoPos-wg">
                            <div class="frm-input">
                                <input type="text" placeholder="{{__('Phone')}}"
                                       class="frm-inp  @error('phone') is-invalid @enderror"
                                       name="phone" value="{{ old('phone') }}"
                                >
                            </div>
                        </div>

                        <div class="icoPos icoPos-wg">
                            <div class="frm-input">
                                <input type="text" placeholder="{{__('Address')}}"
                                       class="frm-inp @error('address') is-invalid @enderror"
                                       name="address" value="{{ old('address') }}"
                                >
                            </div>
                        </div>

                        <div class="icoPos icoPos-wg">
                            <div class="frm-input">
                                <input type="text" placeholder="{{__('UserName')}}"
                                       class="frm-inp @error('username') is-invalid @enderror"
                                       name="username" value="{{ old('username') }}">
                            </div>
                        </div>

                        <div class="icoPos icoPos-wg">
                            <div class="frm-input">
                                <input type="password" placeholder="{{__('Password')}}"
                                       class="frm-inp @error('password') is-invalid @enderror"
                                       name="password" required autocomplete="current-password">
                            </div>
                        </div>

                        <div class="icoPos icoPos-wg" style="margin-bottom: 10px;">
                            <div class="frm-input">

                                <select name="branch_id" class="form-control frm-inp @error('password') is-invalid @enderror" required>
                                    @foreach($branches as $k=>$v)
                                        <option value="{{$k}}">{{$v}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="icoPos icoPos-wg" style="margin-bottom: 10px;">
                            <button type="submit" class="frm-submit">
                                {{__('Register')}}
                                <i class="fa fa-arrow-circle-right"></i>
                            </button>
                        </div>

                        <div class="icoPos icoPos-wg icoLog">
                            <a href="{{route('web:login')}}" class="frm-submit btn btn-success">
                                {{__('Login')}}
                                <i class="fa fa-arrow-circle-right"></i>
                            </a>
                        </div>

                        <div class="row small-spacing">
                            <div class="col-sm-12">
                                <div class="txt-login-with txt-center"></div>
                                <!-- /.txt-login-with -->
                            </div>

                            <div class="row small-spacing">
                                <div class="col-sm-12">
                                    <div class="txt-login-with txt-center"> {{ __('switch language') }} ,
                                        {{ __('current language is') }}
                                        {{ LaravelLocalization::getCurrentLocaleNative() }}
                                    </div>
                                    <!-- /.txt-login-with -->
                                </div>

                                @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)

                                    <div class="col-sm-6">
                                        <a
                                            href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}"
                                            class="btn btn-sm btn-icon btn-icon-left btn-{{$localeCode == 'ar'? 'success':'info'}}
                                                col-md-12 text-white waves-effect waves-light">
                                            <i class="ico fa fa-globe"></i><span> {{ $properties['native'] }}</span>
                                        </a>
                                    </div>

                                @endforeach


                            </div>
                            <h6><span id="div_Error" class="element2"></span></h6>

                        </div>
                        <!-- login-box -->
                    </div>
                </div>
            </div>
        </section>

    </div>


</form>
<!-- ==== Intro Section End ==== -->

<!--====== Javascripts & Jquery ======-->
<script type="application/javascript" src="{{ asset('assets-ar/Design/js/jquery-2.1.4.min.js') }}"></script>
<script type="application/javascript" src="{{ asset('assets-ar/Design/js/plugin-login.js') }}"></script>
<script type="application/javascript" src="{{ asset('assets-ar/Design/js/main-login.js') }}"></script>


<!-- Toastr -->
<script type="application/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script type="application/javascript">

    var options = {"closeButton": true, "positionClass": "toast-top-left", "progressBar": true,};
    @if(count($errors))

    toastr.error("{{ $errors->first()}}", '', options);

    @endif

    @if(Session::has('message'))

    var type = "{{Session::get('alert-type','info')}}";

    switch (type) {
        case 'info':
            toastr.info("{{ Session::get('message') }}", '', options);
            break;
        case 'success':
            toastr.success("{{ Session::get('message') }}", '', options);
            break;
        case 'warning':
            toastr.warning("{{ Session::get('message') }}", '', options);
            break;
        case 'error':
            toastr.error("{{ Session::get('message') }}", '', options);
            break;
    }

    @endif


</script>

<!-- Login -->
<script type="application/javascript" type="text/javascript">
    $(function () {
        $("#form1").submit(function () {
            var LockName = GetParameterValues('Lock');
            var User = '';
            var Pass = '';
            var Remember = false;
            var isLock = false;
            if (LockName !== '' && typeof LockName !== 'undefined') {
                User = LockName;
                Pass = document.getElementById('txtUnLockPassword').value;
                isLock = true;
            } else {
                User = document.getElementById('txtusername').value;
                Pass = document.getElementById('txtPassword').value;
                //Remember = document.getElementById('chk_remember').checked;
                isLock = false;
            }

            // alert(Remember);
            var errorMsg = '';
            var valid = true;
            if (User == '') {
                valid = false;
                errorMsg += ' User name is Required.';
            }
            if (Pass == '') {
                valid = false;
                errorMsg += ' Password is Required.';
            }

            if (valid == false) {
                $("#div_Error").typed({strings: [errorMsg], typeSpeed: 10, loop: true, backDelay: 2000});
                return false;
            }

            var param = JSON.stringify({user: User, pass: Pass, remember: Remember});
            $.ajax({
                type: "POST",
                url: "Login.aspx/ValidateUser",
                data: param,
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                success: function (response) {
                    errorMsg = '';
                    if (response.d == 0) {
                        errorMsg = '';
                    } else if (response.d == 1) {
                        window.location = "login.html?login=True";
                    } else if (response.d == 2) {
                        window.location = "login.html?userguide=True";
                    } else if (response.d == 3) {
                        errorMsg = 'Access denied';
                    } else if (response.d == 4) {
                        errorMsg = 'Username / Password Wrong';
                    }

                    if (isLock)
                        $("#div_Error2").typed({strings: [errorMsg], typeSpeed: 10, loop: true, backDelay: 2000});
                    else
                        $("#div_Error").typed({strings: [errorMsg], typeSpeed: 10, loop: true, backDelay: 2000});
                }
            });

            return false;
        });

        $('#btn_Clear').click(function () {
            document.getElementById('txtusername').value = "";
            document.getElementById('txtPassword').value = "";
        });
    });

    function GetParameterValues(param) {
        var url = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
        for (var i = 0; i < url.length; i++) {
            var urlparam = url[i].split('=');
            if (urlparam[0] == param) {
                return urlparam[1];
            }
        }
    }
</script>
<style>
    /* Chrome autofill Yellow color */
    @-webkit-keyframes autofill {
        to {
            color: #fff;
            background: rgba(255, 255, 255, 0.20);
        }
    }

    input:-webkit-autofill {
        -webkit-animation-name: autofill;
        -webkit-animation-fill-mode: both;
    }
</style>
</body>

</html>
