@extends($layout_path)

@section('styles')
    <link rel="stylesheet" href="{{ asset('accounting-module/daily-restriction.css') }}"/>
@endsection

@section('table')
    <table class="table table-responsive table-bordered table-striped">
        <thead>
            <tr>
                <th> {{ __('accounting-module.account-code') }} </th>
                <th> {{ __('accounting-module.account-name') }} </th>
                <th> {{ __('accounting-module.debit-balance') }} </th>
                <th> {{ __('accounting-module.credit-balance') }} </th>
            </tr>
        </thead>
        <tbody>
            @php
                if ($expense_collection) {
                    echo
                    "<tr>".
                        "<td colspan='4' class='text-center'> ".__('accounting-module.expense')." </td>".
                        "<td style='display:none'></td>".
                        "<td style='display:none'></td>".
                        "<td style='display:none'></td>".
                    "</tr>";
                    $expense_collection->chunk(50 ,function ($accounts) {
                        foreach($accounts as $acc) {
                            echo "<tr>";
                                echo "<td class='text-center'> {$acc->account_code} </td>";
                                echo "<td class='text-center'> {$acc->account_name} </td>";
                                echo "<td class='text-center'> {$acc->debit_balance} </td>";
                                echo "<td class='text-center'> {$acc->credit_balance} </td>";
                            echo "</tr>";
                        }
                    });
                }
                if ($revenue_collection) {
                    echo
                    "<tr>".
                        "<td colspan='4' class='text-center'> ".__('accounting-module.revenue')." </td>".
                        "<td style='display:none'></td>".
                        "<td style='display:none'></td>".
                        "<td style='display:none'></td>".
                    "</tr>";
                    $revenue_collection->chunk(50 ,function ($accounts) {
                        foreach($accounts as $acc) {
                            echo "<tr>";
                                echo "<td class='text-center'> {$acc->account_code} </td>";
                                echo "<td class='text-center'> {$acc->account_name} </td>";
                                echo "<td class='text-center'> {$acc->debit_balance} </td>";
                                echo "<td class='text-center'> {$acc->credit_balance} </td>";
                            echo "</tr>";
                        }
                    });
                }
            @endphp
        </tbody>
    </table>
    <div class="clearfix"></div>
    <div style="margin-bottom: 25px"></div>
    {{-- <div style="margin-top:15px" class="col-md-4 col-sm-4 col-xs-6">

        <div class="bordered-div">
                <h3>
                {{ __('accounting-module.expense') }} : {{ __('accounting-module.debit-amount') }}
                </h3>
                <input disabled="" value="{{ $expense_totals->total_debit }}" class="form-control">

            </div>        
    </div>
    <div style="margin-top:15px" class="col-md-4 col-sm-4 col-xs-6">
        <div class="bordered-div">
            <h3>
                {{ __('accounting-module.expense') }} : {{ __('accounting-module.credit-amount') }}
            </h3>
            
            <input disabled value="{{ $expense_totals->total_credit }}" class="form-control"/>
        </div>
    </div> --}}
    <div style="margin-top:15px" class="col-md-6 col-sm-4 col-xs-6">
        <div class="bordered-div">
            <h3>
                {{ __('accounting-module.expense-total') }}
            </h3>
            <input disabled value="{{ abs($expense_totals->balance) }}" class="form-control"/>
        </div>
    </div>
    {{-- <div style="margin-top:15px" class="col-md-4 col-sm-4 col-xs-6">
        <div class="bordered-div">
            <h3>
                {{ __('accounting-module.revenue') }} : {{ __('accounting-module.debit-amount') }}
            </h3>
            <input disabled value="{{ $revenue_totals->total_debit }}" class="form-control"/>
        </div>
    </div>
    <div style="margin-top:15px" class="col-md-4 col-sm-4 col-xs-6">
        <div class="bordered-div">
            <h3>
                {{ __('accounting-module.revenue') }} : {{ __('accounting-module.credit-amount') }}
            </h3>
            <input disabled value="{{ $revenue_totals->total_credit }}" class="form-control"/>
        </div>
    </div> --}}
    <div style="margin-top:15px" class="col-md-6 col-sm-4 col-xs-6">
        <div class="bordered-div">
            <h3>
                {{ __('accounting-module.revenue-total') }}
            </h3>
            <input disabled value="{{ abs($revenue_totals->balance) }}" class="form-control"/>
        </div>
    </div>
    <div style="margin-top:15px" class="col-md-6 col-sm-6 col-xs-6">
        <div class="bordered-div">
            <h3>
                {{ __('accounting-module.profit') }}
            </h3>
            @php
                $profit = $revenue_totals->balance - abs($expense_totals->balance);
            @endphp
            <input disabled value="{{ $profit > 0 ? $profit : 0 }}" class="form-control"/>
        </div>
    </div>
    <div style="margin-top:15px" class="col-md-6 col-sm-6 col-xs-6">
        <div class="bordered-div">
            <h3>
                {{ __('accounting-module.loose') }}
            </h3>
            @php
                $loose = $revenue_totals->balance - abs($expense_totals->balance);
            @endphp
            <input disabled value="{{ $loose < 0 ? abs($loose) : 0 }}" class="form-control"/>
        </div>
    </div>
@endsection