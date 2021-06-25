@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('accounting-module.trial-balance-index') }} </title>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('accounting-module/daily-restriction.css') }}"/>
@endsection

@section('content')
    <nav>
        <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
        <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item active"> {{ __('accounting-module.trial-balance-index') }} </li>
        </ol>
    </nav>
    <div class="col-md-12">

        @include($view_path . '.index-form')
        <div class="col-xs-12 ui-sortable-handle">
				<div class="box-content card bordered-all primary">
					<h4 class="box-title bg-primary"><i class="ico fa fa-money"></i>
                    {{ __('accounting-module.trial-balance-index') }}
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
                            @foreach($sort_by_columns as $col)
                                @php
                                    $column_name = implode("-" ,explode("_" ,$col));
                                @endphp
                                <td class="text-center column-{{ $column_name }}">
                                    @if (($col == 'debit_balance' || $col == 'credit_balance') && !$collect->$col)
                                        0
                                    @else
                                        {{ $collect->$col }}
                                    @endif
                                </td>
                            @endforeach
                            @if($show_transactions)
                                <td class="text-center column-account-nature">
                                    {{ __('accounting-module.'.$collect->account_nature) }}
                                </td>
                                <td class="text-center column-transactions-debit">
                                    {{ abs($collect->debit_balance) }}
                                </td>
                                <td class="text-center column-transactions-credit">
                                    {{ abs($collect->credit_balance) }}
                                </td>
                            @endif
                        @endforeach
                    </tr>
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
                <input disabled value="{{ $totals->total_debit }}" class="form-control"/>

            </div>
        </div>
        <div class="col-md-4 col-sm-4 col-xs-4">
            <div class="bordered-div">
                <h3>
                    {{ __('accounting-module.credit-amount') }}
                </h3>
                <input disabled value="{{ $totals->total_credit }}" class="form-control"/>

            </div>
        </div>
        <div class="col-md-4 col-sm-4 col-xs-4">
            <div class="bordered-div">
                <h3>
                    {{ __('accounting-module.balance') }}
                </h3>
                <input disabled value="{{ $totals->balance }}" class="form-control"/>

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
    <script type="application/javascript">
        $(document).ready(function() {
            @if($totals->balance < 0)
                alert('{{ __('accounting-module.trial-balance-less').' '. abs($totals->balance) }}')
            @elseif($totals->balance > 0)
                alert('{{ __('accounting-module.trial-balance-great').' '. abs($totals->balance) }}')
            @endif
        })
    </script>
    <script type="application/javascript" src="{{ asset('accounting-module/options-for-dt.js') }}"></script>
@endsection