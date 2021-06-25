@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Show Store Transfer') }} </title>
@endsection

@section('content')

    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"><a href="{{route('admin:stores-transfers.index')}}"> {{__('words.stores-transfers')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Show Store Transfer')}}</li>
            </ol>
        </nav>

        <div class="col-xs-12">

            <div class=" card box-content-wg-new bordered-all primary">

                <h4 class="box-title with-control" style="text-align: initial"><i class="fa fa-gear"></i>
                    {{__('Show Store Transfer')}}
                </h4>

                <div class="box-content">
                    @include('admin.stores_transfer.show_form')
                </div>

                <a href="{{route('admin:stores-transfers.index')}}"
                class="btn hvr-rectangle-in closeAdd-wg-btn"> <i class="ico ico-left fa fa-close"></i></i> {{__('Back')}}
                    </a>

            </div>
            <!-- /.box-content -->
        </div>
        <!-- /.col-xs-12 -->
    </div>

    <!-- /.row small-spacing -->
@endsection

@section('js-validation')

    @include('admin.partial.sweet_alert_messages')

@endsection
