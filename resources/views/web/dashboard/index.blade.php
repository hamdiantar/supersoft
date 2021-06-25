@extends('web.layout.app')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('Admin panel') }} </title>
@endsection

@section('style')
    <style>
        .small-container {
            padding-left: 2px;
            padding-right: 0
        }

        .small-container a {
            color: white;
            font-size: 13px;
        }

        th a {
            color: white;
            text-align: center
        }

        .input-group-btn button {
            height: 35px !important;
            padding-top: 2px !important;
        }

        .top-inputs-wg {
            width: 95%;
            margin-top: -35px
        }


        .box-12 .item1 {
    background: #1F618D ;
    display: block;
    color:white;
    padding:20px;
    text-align:center;
}

.box-12 .item2 {
    background: #673AB7;
    display: block;
    color:white;
    padding:20px;
    text-align:center
}

.box-12 .item3 {
    background: #5EA86E;
    display: block;
    color:white;
    padding:20px;
    text-align:center
}

.box-12 .item4 {
    background: #5DADE2 ;
    display: block;
    color:white;
    padding:20px;
    text-align:center
}

.box-12 .item5 {
    background: #D4AC0D;
    display: block;
    color:white;
    padding:20px;
    text-align:center
}

.box-12 .item6 {
    background: #3F51B5;
    display: block;
    color:white;
    padding:20px;
    text-align:center
}

.box-12 .item7 {
    background: #5c3773;
    display: block;
    color:white;
    padding:20px;
    text-align:center
}

.box-12 .item8 {
    background: #e6217c;
    display: block;
    color:white;
    padding:20px;
    text-align:center
}

.box-12 .item9 {
    background: #1e7fd2;
    display: block;
    color:white;
    padding:20px;
    text-align:center
}

.box-12 .item10 {
    background: #E74C3C;
    display: block;
    color:white;
    padding:20px;
    text-align:center
}

.box-12 .item11 {
    background: blueviolet;
    display: block;
    color:white;
    padding:20px;
    text-align:center
}

.box-12 .item12 {
    background: #CD6155 ;
    display: block;
    color:white;
    padding:20px;
    text-align:center
}


.box-12 h5
{
    margin-top:15px
}

.box-12 a
{
    transition: all 0.3s ease-in-out;
    margin-bottom: 10px;
}

.box-12 a:hover
{
    background:#333;
}

        @media (max-width: 978px) {
            .top-inputs-wg {
                width: 100%;
                margin-top: 0
            }

        }

    </style>
@endsection

@section('content')


<div class="row box-12 text-center">
   <div class="col-lg-3 col-md-4 col-sm-6">
      <a href="{{route('web:customer.show')}}" class="item1 text-center">
        <img src="{{asset('assets/images/3.png')}}">
        <h5>   {{__('My info')}}</h5>
      </a>
   </div>
   <div class="col-lg-3 col-md-4 col-sm-6">
   <a href="{{route('web:customer.edit')}}" class="item3 text-center">
        <img src="{{asset('assets/images/2.png')}}">
        <h5>{{__('Edit My info')}} </h5>
      </a>
   </div>
   <div class="col-lg-3 col-md-4 col-sm-6">
   <a href="{{route('web:quotations.index')}}" class="item11 text-center">
        <img src="{{asset('assets/images/10.png')}}">
        <h5>{{__('Quotations')}}</h5>
      </a>
   </div>
   <div class="col-lg-3 col-md-4 col-sm-6">
   <a href="{{route('web:sales.invoices.index')}}" class="item6 text-center">
        <img src="{{asset('assets/images/6.png')}}">
        <h5>{{__('Sales Invoices')}}</h5>
      </a>
   </div>
   <div class="col-lg-3 col-md-4 col-sm-6">
   <a href="{{route('web:sales.invoices.return.index')}}" class="item4 text-center">
        <img src="{{asset('assets/images/7.png')}}">
        <h5>{{__('Sales Invoices Return')}}</h5>
      </a>
   </div>
   <div class="col-lg-3 col-md-4 col-sm-6">
   <a href="{{route('web:work.cards.index')}}" class="item10 text-center">
        <img src="{{asset('assets/images/8.png')}}">
        <h5>{{__('Work Cards')}}</h5>
      </a>
   </div>   

   <div class="col-lg-3 col-md-4 col-sm-6">
   <a href="{{route('web:reservations.index')}}" class="item2 text-center">
        <img src="{{asset('assets/images/c.png')}}">
        <h5>{{__('Services Reservations')}}</h5>
      </a>
   </div>   


</div>


@endsection

@section('js')
    <script type="application/javascript">

    </script>
@endsection
