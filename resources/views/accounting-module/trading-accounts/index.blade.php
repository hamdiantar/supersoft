@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('accounting-module.trading-account-index') }} </title>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('accounting-module/daily-restriction.css') }}"/>
@endsection

@section('content')
    <nav>
        <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
        <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item active"> {{ __('accounting-module.trading-account-index') }} </li>
        </ol>
    </nav>
    <div class="col-md-12">
        @include($view_path . '.index-form')
        <div class="col-xs-12 ui-sortable-handle">
            <div class="box-content card bordered-all primary">
                <h4 class="box-title bg-primary"><i class="ico fa fa-money"></i>
                    {{ __('accounting-module.trading-account-index') }}
                </h4>
                <div class="col-md-12">
                    <div class="clearfix"></div>
                    <div class="col-md-12">
                        <form style="float: left;margin: 5px" target="blank">
                            <input type="hidden" name="date_from" value="{{ isset($_GET['date_from']) ? $_GET['date_from'] : '' }}"/>
                            <input type="hidden" name="date_to" value="{{ isset($_GET['date_to']) ? $_GET['date_to'] : '' }}"/>
                            <input type="hidden" name="form_action" value="print"/>
                            <button class="btn btn-success"> {{ __('Print') }} </button>
                        </form>
                        <form style="float: left;margin: 5px" target="blank">
                            <input type="hidden" name="date_from" value="{{ isset($_GET['date_from']) ? $_GET['date_from'] : '' }}"/>
                            <input type="hidden" name="date_to" value="{{ isset($_GET['date_to']) ? $_GET['date_to'] : '' }}"/>
                            <input type="hidden" name="form_action" value="excel"/>
                            <button class="btn btn-info"> {{ __('Excel') }} </button>
                        </form>
                    </div>
                    @php
                        $totals = new \stdClass;
                        $totals->total_debit = 0;
                        $totals->total_credit = 0;
                    @endphp
                    <div class="col-md-6">
                        <h3> {{ __('accounting-module.debit') }} </h3>
                        <table class="table table-responsive table-striped table-bordered" id="debit-data-table">
                            <thead>
                                <tr>
                                    <th> {{ __('accounting-module.account-name') }} </th>
                                    <th> {{ __('accounting-module.amount') }} </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($collections['debit_collection'] as $debit_acc)
                                    @php
                                        $totals->total_debit += $debit_acc->debit_balance;
                                    @endphp
                                    <tr>
                                        <td> {{ $debit_acc->account_name .' '. $debit_acc->account_code }} </td>
                                        <td> {{ $debit_acc->debit_balance }} </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h3> {{ __('accounting-module.credit') }} </h3>
                        <table class="table table-responsive table-striped table-bordered" id="credit-data-table">
                            <thead>
                                <tr>
                                    <th> {{ __('accounting-module.account-name') }} </th>
                                    <th> {{ __('accounting-module.amount') }} </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($collections['credit_collection'] as $credit_acc)
                                    @php
                                        $totals->total_credit += $credit_acc->credit_balance;
                                    @endphp
                                    <tr>
                                        <td> {{ $credit_acc->account_name .' '. $credit_acc->account_code }} </td>
                                        <td> {{ $credit_acc->credit_balance }} </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="clearfix"></div>
                    @php
                        $totals->balance = $totals->total_debit - $totals->total_credit;
                    @endphp
                    <div id="totals-area">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            @if ($totals->balance > 0)
                                <div class="bordered-div">
                                    <h3>
                                        {{ __('accounting-module.trading-balance-great') }}
                                    </h3>
                                    <input disabled value="{{ abs($totals->balance) }}" class="form-control"/>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
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
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
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
            @if ($totals->balance < 0)
                alert('{{ __('accounting-module.trading-balance-less').' '. abs($totals->balance) }}')
            @elseif ($totals->balance > 0)
                alert('{{ __('accounting-module.trading-balance-great').' '. abs($totals->balance) }}')
            @endif
            // $('#debit-data-table').DataTable({
            //     "ordering": false,
            //     "info": false,
            //     "searching": false,
            //     "language": {
            //         @if (app()->getLocale() == 'ar')
            //             "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Arabic.json"
            //         @else
            //             "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/English.json"
            //         @endif
            //     }
            // })
            // $('#credit-data-table').DataTable({
            //     "ordering": false,
            //     "info": false,
            //     "searching": false,
            //     "language": {
            //         @if (app()->getLocale() == 'ar')
            //             "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Arabic.json"
            //         @else
            //             "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/English.json"
            //         @endif
            //     }
            // })
        })
    </script>
@endsection