@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('words.show-capital-balance') }} </title>
@endsection


@section('content')
<div class="row small-spacing">
    <nav>
        <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
            <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin:capital-balance.index') }}"> {{__('words.capital-balance')}}</a></li>
            <li class="breadcrumb-item active"> {{__('words.show-capital-balance')}}</li>
        </ol>
    </nav>
    <div class="col-xs-12">
        <div class=" card box-content-wg-new bordered-all primary">
            <h4 class="box-title with-control"><i class="fa fa-user"></i>  {{__('words.show-capital-balance')}}</h4>
            <div class="box-content">
                <div class="row">
                    <div class="col-md-6">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label> {{ __('Branch') }} </label>
                                <input disabled class="form-control" value="{{ optional($capitalBalance->branch)->name }}"/>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label> {{ __('words.capital-balance') }} </label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-money"></i></span>  
                                    <input class="form-control" disabled value="{{ $capitalBalance->balance }}"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop