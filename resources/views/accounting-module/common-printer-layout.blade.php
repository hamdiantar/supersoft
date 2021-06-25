@php
    $lang = app()->getLocale();
@endphp
<!DOCTYPE html>
<html lang="en" dir="{{ $lang == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> {{ $title }} </title>
    <link rel="stylesheet" href="{{ asset('assets-ar/Design/css/bootstrap.min.css') }}">
    <link rel="stylesheet"  href="{{asset('assets/styles/style.min.css')}}">
    <link rel="stylesheet" media="print" href="{{asset('assets/styles/style.min.css')}}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Almarai&display=swap"/>
    <link rel="stylesheet" href="https://fontlibrary.org/face/droid-arabic-kufi"/>
    <link href="{{ asset('css/datatable-print-styles.css') }}" rel='stylesheet'/>
    @yield('styles')
    <style>
        html ,body{
            height: 100%;
            width: 100%;
            margin: 0 auto !important;
            border: none;
            padding:0 !important
        }
        td {
            font-weight: normal
        }
        .title-print
        {
            margin-bottom: 0 !important;
            padding-bottom: 0 !important;
        }
    </style>
</head>
<body>
    <div class="">
   
        @include("admin.layouts.datatable-print")
        <h4 class="text-center title-print" style="margin-bottom: 0 !important">{{$title}}</h4>
        @yield('table')
    </div>
    <script type="application/javascript" src="{{asset('assets/scripts/jquery.min.js')}}"></script>
    <script type="application/javascript" src="{{ asset('assets/plugin/bootstrap/js/bootstrap.min.js') }}"></script>
    <script type="application/javascript">
        $(document).ready(function() {
            setTimeout(function() {
                window.print()
            } ,3000)
        })
    </script>
</body>
</html>