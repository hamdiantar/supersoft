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
    <link href='{{ asset("css/datatable-print-styles.css") }}' rel='stylesheet'/>
    @yield('styles')
    <style>
        html ,body{
            height: 99%;
            width: 99%;
            margin: 0 auto;
            border: none
        }
        td {
            font-weight: normal
        }
    </style>
</head>
<body style="background-color:gray">
    <div class="col-md-12" style="background-color: white;color:gray;padding:20px">
        <!-- <h3 class="text-center" style="margin-bottom: 10px"> {{$title}}</h3> -->
        @include("admin.layouts.datatable-print")
        <div class="clearfix"></div>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        @foreach($columns_keys as $key)
                            <th class="text-center"> {{ $header[$key] }} </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @php
                        $collection->chunk(100 ,function($data) use ($columns_keys ,$custom_echo) {
                            foreach($data as $record) {
                                $custom_echo($columns_keys ,$record);
                            }
                        });
                    @endphp
                </tbody>
            </table>
        </div>
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