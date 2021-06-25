@extends('accounting-module.common-printer-layout')

@section('table')
    <style>
        #totals-area > .col-md-6 {
            float: {{ app()->getLocale() == 'ar' ? 'right' : 'left'}};
        }
    </style>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th> {{ __('accounting-module.debit') .' '. __('accounting-module.account-name') }} </th>
                    <th> {{ __('accounting-module.debit') .' '. __('accounting-module.amount') }} </th>
                    <th> {{ __('accounting-module.credit') .' '. __('accounting-module.account-name') }} </th>
                    <th> {{ __('accounting-module.credit') .' '. __('accounting-module.amount') }} </th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totals = new \stdClass;
                    $totals->total_debit = 0;
                    $totals->total_credit = 0;
                    $big_count = count($collections['debit_collection']) > count($collections['credit_collection']);
                @endphp
                @if ($big_count)
                    @foreach($collections['debit_collection'] as $index => $debit_acc)
                        @php
                            $credit_acc = isset($collections['credit_collection'][$index]) ? $collections['credit_collection'][$index] : NULL;
                            $totals->total_debit += $debit_acc->debit_balance;
                            $totals->total_credit += $credit_acc ? $credit_acc->credit_balance : 0;
                        @endphp
                        <tr>
                            <td> {{ $debit_acc->account_name .' '. $debit_acc->account_code }} </td>
                            <td> {{ $debit_acc->debit_balance }} </td>
                            <td> {{ $credit_acc ? ($credit_acc->account_name .' '. $credit_acc->account_code) : '' }} </td>
                            <td> {{ $credit_acc ? $credit_acc->credit_balance : '' }} </td>
                        </tr>
                    @endforeach
                @else
                    @foreach($collections['credit_collection'] as $index => $credit_acc)
                        @php
                            $debit_acc = isset($collections['debit_collection'][$index]) ? $collections['debit_collection'][$index] : NULL;
                            $totals->total_credit += $credit_acc->credit_balance;
                            $totals->total_debit += $debit_acc ? $debit_acc->debit_balance : 0;
                        @endphp
                        <tr>
                            <td> {{ $debit_acc ? ($debit_acc->account_name .' '. $debit_acc->account_code) : '' }} </td>
                            <td> {{ $debit_acc ? $debit_acc->debit_balance : '' }} </td>
                            <td> {{ $credit_acc ? ($credit_acc->account_name .' '. $credit_acc->account_code) : '' }} </td>
                            <td> {{ $credit_acc ? $credit_acc->credit_balance : '' }} </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
        <div class="clearfix"></div>
        @php
            $totals->balance = $totals->total_debit - $totals->total_credit;
        @endphp
        <div id="totals-area">
            <div class="col-md-6 col-sm-6 col-xs-6">
                @if ($totals->balance > 0)
                    <div class="bordered-div">
                        <h3>
                            {{ __('accounting-module.trading-balance-great') }}
                        </h3>
                        <input disabled value="{{ abs($totals->balance) }}" class="form-control"/>
                    </div>
                @endif
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6">
                @if ($totals->balance < 0)
                    <div class="bordered-div">
                        <h3>
                            {{ __('accounting-module.trading-balance-less') }}
                        </h3>
                        <input disabled value="{{ abs($totals->balance) }}" class="form-control"/>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection