@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('accounting-module.account-guide-index') }} </title>
@endsection

@section('content')
    <nav>
        <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
        <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item active"> {{ __('account-guide-index') }} </li>
        </ol>
    </nav>

    <div class="col-md-12">

    <div class="col-xs-12 ui-sortable-handle">
				<div class="box-content card bordered-all primary">
					<h4 class="box-title bg-primary"><i class="ico fa fa-money"></i>
                    {{ __('account-guide-index') }}
                    </h4>


					<div class="card-content">
        <table class="table table-responsive table-striped table-bordered">
            <thead>
                <tr>
                    <th class="text-center"> {{ __('accounting-module.account-nature') }} </th>
                    <th class="text-center"> {{ __('accounting-module.account-name') }} </th>
                    <th class="text-center"> {{ __('accounting-module.account-code') }} </th>
                </tr>
            </thead>
            <tbody>
                {!! \App\AccountingModule\Controllers\AccountGuide::get_tree_as_table() !!}
            </tbody>
        </table>
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>
@endsection