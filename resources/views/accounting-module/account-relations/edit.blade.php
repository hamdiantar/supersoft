@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('accounting-module.account-relations-index') }} </title>
@endsection

@section('content')
    <nav>
        <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
        <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item active"><a href="{{ route('account-relations.index')}}"> {{ __('accounting-module.account-relations-index') }}</a></li>
            <li class="breadcrumb-item active"> {{__('accounting-module.account-relations-update')}}</li>
        </ol>
    </nav>
    <div class="col-xs-12 ui-sortable-handle">
        <div class="box-content card bordered-all primary">
            <h4 class="box-title with-control"><i class="ico fa fa-money"></i>
            {{__('accounting-module.account-relations-update')}}
                <span class="controls hidden-sm hidden-xs">
                    <button class="control text-primary">{{ __('words.save') }}<img class="img-fluid" style="width:60px;height:60px;margin-top:-20px" src="{{ asset('assets/images/f1.png') }}"/></button>
                    <button class="control text-info">{{ __('words.clear') }}<img class="img-fluid" style="width:60px;height:60px;margin-top:-20px" src="{{ asset('assets/images/f2.png') }}"/></button>
                    <button class="control text-danger">{{ __('words.cancel') }} <img class="img-fluid" style="width:60px;height:60px;margin-top:-20px" src="{{ asset('assets/images/f3.png') }}"/></button>
                </span>
            </h4>
            <div class="tab-content">
                <div class="tab-pane fade in active">
                    @include($view_path)
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('accounting-scripts')
    {!! JsValidator::formRequest($validation_class) !!}
    <script type="application/javascript" src="{{ asset('js/sweet-alert.js') }}"></script>

    <script type="application/javascript">
        function alert(message) {
            swal({
                title:"{{ __('accounting-module.warning') }}",
                text:message,
                icon:"warning",
                reverseButtons: false,
                buttons:{
                    cancel: {
                        text: "{{ __('words.ok') }}",
                        className: "btn btn-primary",
                        value: null,
                        visible: true
                    }
                }
            })
        }
    </script>

    @include('accounting-module.form-actions')
    
    @if(isset($script_file_path) && $script_file_path != '')
        @include($script_file_path)
    @endif

    @if (!isset($without_main_script))
        @include('accounting-module.account-relations.main-script')
    @endif
@endsection