<!DOCTYPE html>
<html lang="en" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> {{ $title }} </title>
    <link rel="stylesheet" href="{{ asset('assets-ar/Design/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('accounting-module/tree-view.css') }}"/>
    <link rel="stylesheet" href="{{ asset('accounting-module/arabic-grid.css') }}"/>
    @yield('styles')
</head>
<body style="padding: 30px 0;background-color:gray">
    <div class="col-md-12" style="background-color: white;color:gray;padding:20px">
        @yield('table')
    </div>
    <script type="application/javascript" src="{{asset('assets/scripts/jquery.min.js')}}"></script>
    <script type="application/javascript" src="{{ asset('assets/plugin/bootstrap/js/bootstrap.min.js') }}"></script>
    <script type="application/javascript">
        $(document).ready(function() {
            window.print()
        })
    </script>
</body>
</html>