@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('accounting-module.account-relations-index') }} </title>
@endsection

@section('style')
    <style>
        ul.nav li{
            width: 100%
        }
        ul.nav li.active{
            border: 1px solid gray;
        }
        .card-content{
            padding: 25px;
        }
    </style>
@endsection

@section('content')
    <nav>
        <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
        <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item active"><a href="{{ route('account-relations.index')}}"> {{ __('accounting-module.account-relations-index') }}</a></li>
            <li class="breadcrumb-item active"> {{__('accounting-module.account-relations-create')}}</li>
        </ol>
    </nav>

    <div class="col-xs-12 ui-sortable-handle">
        <div class="box-content card bordered-all 
        ry">
            <h4 class="box-title with-control"><i class="ico fa fa-money"></i>
            {{__('accounting-module.account-relations-create')}}
                <span class="controls hidden-sm hidden-xs">
                    <button class="control text-primary">{{ __('words.save') }}<img class="img-fluid" style="width:60px;height:60px;margin-top:-20px" src="{{ asset('assets/images/f1.png') }}"/></button>
                    <button class="control text-info">{{ __('words.clear') }}<img class="img-fluid" style="width:60px;height:60px;margin-top:-20px" src="{{ asset('assets/images/f2.png') }}"/></button>
                    <button class="control text-danger">{{ __('words.cancel') }} <img class="img-fluid" style="width:60px;height:60px;margin-top:-20px" src="{{ asset('assets/images/f3.png') }}"/></button>
                </span>
            </h4>
		    <div class="card-content">
                <div class="col-md-3">
                    <ul class="nav nav-tabs">
                        <li class="{{ $action_for == 'types-items' ? 'active' : '' }}">
                            <a href="{{ route('account-relations.create' ,['action-for' => 'types-items']) }}">
                                {{ __('accounting-module.account-relation.types-items') }}
                            </a>
                        </li>
                        <li class="{{ $action_for == 'lockers-banks' ? 'active' : '' }}">
                            <a href="{{ route('account-relations.create' ,['action-for' => 'lockers-banks']) }}">
                                {{ __('accounting-module.account-relation.lockers-banks') }}
                            </a>
                        </li>
                        <li class="{{ $action_for == 'money-permissions' ? 'active' : '' }}">
                            <a href="{{ route('account-relations.create' ,['action-for' => 'money-permissions']) }}">
                                {{ __('accounting-module.account-relation.money-permissions') }}
                            </a>
                        </li>
                        <li class="{{ $action_for == 'customers' ? 'active' : '' }}">
                            <a href="{{ route('account-relations.create' ,['action-for' => 'customers']) }}">
                                {{ __('accounting-module.account-relation.customers') }}
                            </a>
                        </li>
                        <li class="{{ $action_for == 'suppliers' ? 'active' : '' }}">
                            <a href="{{ route('account-relations.create' ,['action-for' => 'suppliers']) }}">
                                {{ __('accounting-module.account-relation.suppliers') }}
                            </a>
                        </li>
                        <li class="{{ $action_for == 'employees' ? 'active' : '' }}">
                            <a href="{{ route('account-relations.create' ,['action-for' => 'employees']) }}">
                                {{ __('accounting-module.account-relation.employees') }}
                            </a>
                        </li>
                        <li class="{{ $action_for == 'stores-permissions' ? 'active' : '' }}">
                            <a href="{{ route('account-relations.create' ,['action-for' => 'stores-permissions']) }}">
                                {{ __('accounting-module.account-relation.stores-permissions') }}
                            </a>
                        </li>
                        <li class="{{ $action_for == 'stores' ? 'active' : '' }}">
                            <a href="{{ route('account-relations.create' ,['action-for' => 'stores']) }}">
                                {{ __('accounting-module.account-relation.stores') }}
                            </a>
                        </li>
                        <li class="{{ $action_for == 'taxes' ? 'active' : '' }}">
                            <a href="{{ route('account-relations.create' ,['action-for' => 'taxes']) }}">
                                {{ __('accounting-module.account-relation.taxes') }}
                            </a>
                        </li>
                        <li class="{{ $action_for == 'discounts' ? 'active' : '' }}">
                            <a href="{{ route('account-relations.create' ,['action-for' => 'discounts']) }}">
                                {{ __('accounting-module.account-relation.discounts') }}
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col-md-9">
                    <div class="tab-pane fade in active">
                        @include($view_path)
                        <div class="clearfix"></div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
@endsection

@section('accounting-scripts')
    {!! JsValidator::formRequest($validation_class ,$form_id) !!}
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

    @include('accounting-module.account-relations.main-script')
@endsection