<!DOCTYPE html>
<html>

<head>
    <title>Pusher Test</title>

    <link href="{{asset('css/app.css')}}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

<div id="app">

    <input type="hidden" id="auth_id" value="{{auth()->id()}}">


</div>


<script type="application/javascript" src="{{asset('js/app.js')}}"></script>


</body>
</html>
