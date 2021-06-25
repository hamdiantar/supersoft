@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('accounting-module.income-list-index') }} </title>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('accounting-module/daily-restriction.css') }}"/>
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css"/>
@endsection

@section('content')
    <nav>
        <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
        <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item active"> {{ __('accounting-module.income-list-index') }} </li>
        </ol>
    </nav>
    <div class="col-md-12">

        @include($view_path . '.index-form')
        <div class="col-xs-12 ui-sortable-handle">
				<div class="box-content card bordered-all primary">
					<h4 class="box-title bg-primary"><i class="ico fa fa-money"></i>
                    {{ __('accounting-module.income-list-index') }} 
                    </h4>


					<div class="card-content">
            </div>


        <div class="col-md-12">
            @include($view_path . '.options-datatable.option-row')
            <div class="clearfix"></div>
            <table class="table table-responsive table-striped table-bordered" style="margin-top: 10px" id="data-table">
                @include($view_path . '.options-datatable.table-thead')
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
        </div>
        <div class="clearfix"></div>
        <div style="margin-bottom: 25px"></div>
        <div class="col-md-4 col-sm-4 col-xs-4">
            <div class="bordered-div">
                <h3>
                    {{ __('accounting-module.expense') }} : {{ __('accounting-module.debit-amount') }}
                </h3>
                <input disabled="" value="{{ $expense_totals->total_debit }}" class="form-control">

            </div>
        </div>
        <div class="col-md-4 col-sm-4 col-xs-4">
            <div class="bordered-div">
                <h3>
                    {{ __('accounting-module.expense') }} : {{ __('accounting-module.credit-amount') }}
                </h3>
                <input disabled="" value="{{ $expense_totals->total_credit }}" class="form-control">

            </div>
        </div>
        <div class="col-md-4 col-sm-4 col-xs-4">
            <div class="bordered-div">
                <h3>
                    {{ __('accounting-module.expense') }} : {{ __('accounting-module.balance') }}
                </h3>
                <h3></h3>
                <input disabled="" value="{{ $expense_totals->balance }}" class="form-control">
            </div>
        </div>
        <div class="clearfix" style="margin-bottom: 15px"></div>
        <div class="col-md-4 col-sm-4 col-xs-4">
            <div class="bordered-div">
                <h3>
                    {{ __('accounting-module.revenue') }} : {{ __('accounting-module.debit-amount') }}
                </h3>
                <input disabled="" value="{{ $revenue_totals->total_debit }}" class="form-control">

            </div>
        </div>
        <div class="col-md-4 col-sm-4 col-xs-4">
            <div class="bordered-div">
                <h3>
                    {{ __('accounting-module.revenue') }} : {{ __('accounting-module.credit-amount') }}
                </h3>
                <input disabled="" value="{{ $revenue_totals->total_credit }}" class="form-control">
            </div>
        </div>
        <div class="col-md-4 col-sm-4 col-xs-4">
            <div class="bordered-div">
                <h3>
                    {{ __('accounting-module.revenue') }} : {{ __('accounting-module.balance') }}
                </h3>
                <input disabled="" value="{{ $revenue_totals->balance }}" class="form-control">
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="clearfix" style="margin-bottom: 15px"></div>
        <div class="col-md-6 col-sm-6 col-xs-6">
            <div class="bordered-div">
                <h3>
                    {{ __('accounting-module.profit') }}
                </h3>
                @php
                    $profit = $revenue_totals->balance - abs($expense_totals->balance);
                @endphp
                <input disabled="" value="{{ $profit > 0 ? $profit : 0 }}" class="form-control">

            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-6">
            <div class="bordered-div">
                <h3>
                    {{ __('accounting-module.loose') }}
                </h3>
                @php
                    $loose = $revenue_totals->balance - abs($expense_totals->balance);
                @endphp
                <input disabled="" value="{{ $loose < 0 ? abs($loose) : 0 }}" class="form-control">

                
            </div>
        </div>
    </div>
    </div>
        </div>
@endsection

@section('accounting-scripts')
    <script type="application/javascript" src="//cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
    <script type="application/javascript">
        $(document).ready(function() {
            $("#data-table").DataTable({
                "ordering":false,
                "language":{
                    "lengthMenu": "{{ __('accounting-module.display') }} _MENU_ {{ __('accounting-module.records_per_page') }}",
                    "zeroRecords": "{{ __('accounting-module.no_data_sorry') }}",
                    "info": "{{ __('accounting-module.show_page') }} _PAGE_ {{ __('accounting-module.of') }} _PAGES_",
                    "infoEmpty": "{{ __('accounting-module.no_data') }}",
                    "infoFiltered": "({{ __('accounting-module.filtered_from') }} _MAX_ {{ __('accounting-module.total_record') }})",
                    // "search": "{{ __('accounting-module.search') }} :",
                    // "paginate": {
                    //     "next" : "{{ __('accounting-module.next') }}",
                    //     "previous" : "{{ __('accounting-module.previous') }}",
                    }
                }
            })
        })
    </script>
    <script type="application/javascript" src="{{ asset('accounting-module/options-for-dt.js') }}"></script>
@endsection