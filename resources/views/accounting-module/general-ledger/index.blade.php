@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('accounting-module.accounting-general-ledger') }} </title>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('accounting-module/daily-restriction.css') }}"/>
@endsection

@section('content')
    <nav>
        <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
        <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item active"> {{ __('accounting-module.accounting-general-ledger') }} </li>
        </ol>
    </nav>
    <div class="col-md-12">

        @include($view_path . '.index-form')

        <div class="col-xs-12 ui-sortable-handle">
				<div class="box-content card bordered-all primary">
					<h4 class="box-title bg-primary"><i class="ico fa fa-money"></i>
                    {{ __('accounting-module.accounting-general-ledger') }} 
                    </h4>


					<div class="card-content">
            </div>

            <div class="col-md-12">
                    
            @include($view_path . '.options-datatable.option-row')
            <div class="clearfix"></div>



            <table class="table table-responsive table-striped table-bordered" style="margin-top: 10px">
                @include($view_path . '.options-datatable.table-thead')
                <tbody>
                    @foreach($collection as $collect)
                        <tr>
                            <td class="text-center column-restriction-number">
                                {{ $collect->restriction_number }}
                            </td>
                            <td class="text-center column-operation-date">
                                {{ $collect->operation_date }}
                            </td>
                            <td class="text-center column-debit-amount">
                                {{ $collect->debit_amount }}
                            </td>
                            <td class="text-center column-credit-amount">
                                {{ $collect->credit_amount }}
                            </td>
                            <td class="text-center column-balance">
                                {{ $collect->balance }}
                            </td>
                            <td class="text-center column-account-name">
                                {{ $collect->account_name }}
                            </td>
                            <td class="text-center column-account-code">
                                {{ $collect->account_code }}
                            </td>
                            @if($show_cost_center)
                                <td class="text-center column-cost-center">
                                    {{ $collect->cost_center_code .' '. $collect->cost_center }}
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $collection->links() }}
        </div>
        <div class="clearfix"></div>
        <div class="col-md-4 col-sm-4 col-xs-4">

            <div class="bordered-div">
                <h3>
                {{ __('accounting-module.debit-amount') }}
                </h3>
                <input disabled="" value="{{ $totals->total_debit }}" class="form-control">
            </div>            
        </div>

        <div class="col-md-4 col-sm-4 col-xs-4">

            <div class="bordered-div">
                <h3>
                {{ __('accounting-module.credit-amount') }}
                </h3>
                <input disabled="" value="{{ $totals->total_credit }}" class="form-control">
            </div>

        </div>
        <div class="col-md-4 col-sm-4 col-xs-4">
 
            <div class="bordered-div">
                <h3>
                {{ __('accounting-module.balance') }}
                </h3>
                <input disabled="" value="{{ $totals->balance }}" class="form-control">
            </div>

        </div>
        <div class="clearfix"></div>
    </div>
    </div>
        </div>
        </div>
@endsection

@section('accounting-module-modal-area')
    @include($view_path . '.options-datatable.column-visible')
@endsection

@section('accounting-scripts')
    <script type="application/javascript">

    </script>
    <script type="application/javascript" src="{{ asset('accounting-module/options-for-dt.js') }}"></script>
@endsection