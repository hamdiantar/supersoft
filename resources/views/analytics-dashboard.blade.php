@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Admin panel') }} </title>
@endsection

@section('style')
    <style>
        .small-container {
            padding-left: 2px;
            padding-right: 0
        }

        .small-container a {
            color: white;
            font-size: 13px;
        }

        th a {
            color: white;
            text-align: center
        }

        .input-group-btn button {
            height: 35px !important;
            padding-top: 2px !important;
        }

        .top-inputs-wg {
            width: 95%;
            margin-top: -35px
        }

    .box-12 .item1 {
    background: #1F618D ;
    display: block;
    color:white;
    padding:20px;
    text-align:center;
}

.box-12 .item2 {
    background: #673AB7;
    display: block;
    color:white;
    padding:20px;
    text-align:center
}

.box-12 .item3 {
    background: #5EA86E;
    display: block;
    color:white;
    padding:20px;
    text-align:center
}

.box-12 .item4 {
    background: #5DADE2 ;
    display: block;
    color:white;
    padding:20px;
    text-align:center
}

.box-12 .item5 {
    background: #D4AC0D;
    display: block;
    color:white;
    padding:20px;
    text-align:center
}

.box-12 .item6 {
    background: #3F51B5;
    display: block;
    color:white;
    padding:20px;
    text-align:center
}

.box-12 .item7 {
    background: #5c3773;
    display: block;
    color:white;
    padding:20px;
    text-align:center
}

.box-12 .item8 {
    background: #e6217c;
    display: block;
    color:white;
    padding:20px;
    text-align:center
}

.box-12 .item9 {
    background: #1e7fd2;
    display: block;
    color:white;
    padding:20px;
    text-align:center
}

.box-12 .item10 {
    background: #E74C3C;
    display: block;
    color:white;
    padding:20px;
    text-align:center
}

.box-12 .item11 {
    background: blueviolet;
    display: block;
    color:white;
    padding:20px;
    text-align:center
}

.box-12 .item12 {
    background: #CD6155 ;
    display: block;
    color:white;
    padding:20px;
    text-align:center
}

.box-12 h5
{
    margin-top:5px
}

.box-12 a
{
    transition: all 0.3s ease-in-out;
    margin-bottom: 10px;
}

.box-12 a:hover
{
    background:#333;
}

        @media (max-width: 978px) {
            .top-inputs-wg {
                width: 100%;
                margin-top: 0
            }

        }
        .plac-wg {
        font-size: 10px !important;
    }

    </style>
@endsection

@section('content')
    <div class="row small-spacing top-inputs-wg" style="">
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-btn">
                    <button class="btn btn-default"
                            onclick="global_search_redirect('word-card-search' ,'{{ route('admin:work-cards.index') }}')"
                            type="button"><i class="fa fa-search"></i></button>
                </span>
                <input type="text" class="form-control plac-wg"
                       id="word-card-search" placeholder="{{__('search1')}}"/>
            </div>

        </div>
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-btn">
                    <button class="btn btn-default"
                            onclick="global_search_redirect('sale-invoices-search' ,'{{ route('admin:sales.invoices.index') }}')"
                            type="button"><i class="fa fa-search"></i></button>
                </span>
                <input type="text" class="form-control plac-wg"
                       id="sale-invoices-search" placeholder="{{__('search2')}}"/>
            </div>

        </div>
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-btn">
                    <button class="btn btn-default"
                            onclick="global_search_redirect('customers-search' ,'{{ route('admin:customers.index') }}')"
                            type="button"><i class="fa fa-search"></i></button>
                </span>
                <input type="text" class="form-control plac-wg"
                       id="customers-search" placeholder="{{__('search3')}}"/>
            </div>

        </div>
    </div>

<br>

<div class="row box-12 text-center">
   <div class="col-lg-2 col-md-4 col-sm-6">
      <a href="{{ route('admin:parts.create') }}" class="item1 text-center">
        <img src="{{asset('assets/images/1.png')}}">
        <h5>{{__('analytics.add-part')}}</h5>
      </a>
   </div>
   <div class="col-lg-2 col-md-4 col-sm-6">
   <a href="{{route('admin:customers.create')}}" class="item2 text-center">
        <img src="{{asset('assets/images/2.png')}}">
        <h5>{{__('analytics.add-customer')}} </h5>
      </a>
   </div>
   <div class="col-lg-2 col-md-4 col-sm-6">
   <a href="{{route('admin:suppliers.create')}}" class="item3 text-center">
        <img src="{{asset('assets/images/3.png')}}">
        <h5>{{__('analytics.add-supplier')}}</h5>
      </a>
   </div>
   <div class="col-lg-2 col-md-4 col-sm-6">
   <a href="route('admin:purchase-invoices.create')" class="item4 text-center">
        <img src="{{asset('assets/images/4.png')}}">
        <h5>{{__('analytics.add-buy-invoice')}}</h5>
      </a>
   </div>
   <div class="col-lg-2 col-md-4 col-sm-6">
   <a href="{{route('admin:purchase_returns.create')}}" class="item5 text-center">
        <img src="{{asset('assets/images/5.png')}}">
        <h5>{{__('analytics.add-buy-invoice-return')}}</h5>
      </a>
   </div>
   <div class="col-lg-2 col-md-4 col-sm-6">
   <a href="{{route('admin:sales.invoices.create')}}" class="item6 text-center">
        <img src="{{asset('assets/images/6.png')}}">
        <h5>{{__('analytics.add-sale-invoice')}}</h5>
      </a>
   </div>
   <div class="col-lg-2 col-md-4 col-sm-6">
   <a href="{{route('admin:sales.invoices.return.create')}}" class="item7 text-center">
        <img src="{{asset('assets/images/7.png')}}">
        <h5>{{__('analytics.add-sale-invoice-return')}}</h5>
      </a>
   </div>

   <div class="col-lg-2 col-md-4 col-sm-6">
   <a href="{{route('admin:work-cards.create')}}" class="item9 text-center">
        <img src="{{asset('assets/images/8.png')}}">
        <h5>{{__('analytics.add-Card')}}</h5>
      </a>
   </div>
   <div class="col-lg-2 col-md-4 col-sm-6">
   <a href="{{route('admin:work-cards.index')}}" class="item10 text-center">
        <img src="{{asset('assets/images/9.png')}}">
        <h5>{{__('analytics.show-work-cards')}}</h5>
      </a>
   </div>
   <div class="col-lg-2 col-md-4 col-sm-6">
   <a href="{{route('admin:quotations.create')}}" class="item11 text-center">
        <img src="{{asset('assets/images/10.png')}}">
        <h5>{{__('analytics.add-price-offers')}}</h5>
      </a>
   </div>
   <div class="col-lg-2 col-md-4 col-sm-6">
   <a href="{{route('admin:maintenance.status.index.report')}}" class="item12 text-center">
        <img src="{{asset('assets/images/11.png')}}">
        <h5>{{__('analytics.cars.statusReport')}}</h5>
      </a>
   </div>
   <div class="col-lg-2 col-md-4 col-sm-6">
   <a href="{{route('admin:reservations.index')}}" class="item8 text-center">
        <img src="{{asset('assets/images/12.png')}}">
        <h5>{{__('analytics.Tasks')}}</h5>
      </a>
   </div>

</div>

<div class="row">
<div class="col-md-12">
        <div class="box-content js__card" style="border-top:4px solid #34A7D2">
                <h5 class="box-title with-control bg-white"
                    style="box-shadow:none !important;border-bottom: none !important;margin-top:-5px;margin-bottom:15px;font-weight:bold">
                  {{__('Maintenance status')}}
                    <span class="controls">
							<button type="button" class="control fa fa-minus js__card_minus"></button>
							<button type="button" class="control fa fa-times js__card_remove"></button>
						</span>
                </h5>
                <div class="card-content js__card_content">
           <div class="row new-row-dash">
               <div class="col-md-3 text-center">
               <div class="item item1">
                  <img src="http://localhost/cars/public/assets/images/f1.png" class="img-fluid" style="width: 50px; height: 50px; margin-top: 5px; margin-bottom: 0;"><br>
                  <h5>لوريم ابسوم</h5>
                </div>
               </div>
               <div class="col-md-3 text-center">
               <div class="item item2">
                  <img src="http://localhost/cars/public/assets/images/f1.png" class="img-fluid" style="width: 50px; height: 50px; margin-top: 5px; margin-bottom: 0;"><br>
                  <h5>لوريم ابسوم</h5>
                </div>
               </div>
               <div class="col-md-3 text-center">
               <div class="item item3">
                  <img src="http://localhost/cars/public/assets/images/f1.png" class="img-fluid" style="width: 50px; height: 50px; margin-top: 5px; margin-bottom: 0;"><br>
                  <h5>لوريم ابسوم</h5>
                </div>
               </div>
               <div class="col-md-3 text-center">
               <div class="item item4">
                  <img src="http://localhost/cars/public/assets/images/f1.png" class="img-fluid" style="width: 50px; height: 50px; margin-top: 5px; margin-bottom: 0;"><br>
                  <h5>لوريم ابسوم</h5>
                </div>
               </div>

               </div>
               </div>
           </div>
        </div>
</div>

    <div class="row small-spacing">

        <div class="col-lg-6 col-xs-12">
            <div class="box-content js__card" style="border-top:4px solid #34A7D2">
                <h5 class="box-title with-control bg-white"
                    style="box-shadow:none !important;border-bottom: none !important;margin-top:-5px;margin-bottom:15px;font-weight:bold">
                    {{ __('analytics.last-work-cards') }}
                    <span class="controls">
							<button type="button" class="control fa fa-minus js__card_minus"></button>
							<button type="button" class="control fa fa-times js__card_remove"></button>
						</span>
                </h5>
                <div class="card-content js__card_content">
                    <div class="table-responsive" style="height:300px;overflow-y:scroll;margin-bottom:10px">
                        <table class="table table-striped margin-bottom-10">
                            <thead>
                            <tr>
                                <th> {{ __('analytics.work-card-number') }} </th>
                                <th> {{ __('analytics.invoice-date') }} </th>
                                <th> {{ __('analytics.customer-name') }} </th>
                                <th> {{ __('analytics.status') }} </th>
                            <!-- <th> {{ __('analytics.user') }} </th> -->
                                <th> {{ __('analytics.print') }} </th>
                            </tr>
                            </thead>
                        <!-- <tfoot>
                        <tr>
                            <th colspan="2">
                                @can('create_work_card')
                            <a href="{{ route('admin:work-cards.create') }}"> {{ __('analytics.create-work-card') }} </a>
                                @endcan
                            </th>
                            <th colspan="2"></th>
                            <th colspan="2">
                                @can('view_work_card')
                            <a href="{{ route('admin:work-cards.index') }}"> {{ __('analytics.show-work-card') }} </a>
                                @endcan
                            </th>
                        </tr>
                    </tfoot> -->
                            <tbody>
                            @if($last_work_cards)
                                @foreach($last_work_cards as $card)
                                    <tr>
                                        <td>
                                            @if($card->cardInvoice && auth()->user()->can('update_work_card'))
                                                <a href="{{ route('admin:work.cards.invoices.edit' ,['work_card' => $card,'card_invoice' => $card->cardInvoice]) }}">
                                                    {{ $card->card_number }}
                                                </a>
                                            @else
                                                {{ $card->card_number }}
                                            @endif
                                        </td>
                                        <td><span class="bg-danger text-white"
                                                  style="padding:3px;border-radius:3px"> {{ optional($card->cardInvoice)->date }} </span>
                                        </td>
                                        <td> {{ optional($card->customer)->name }} </td>
                                        <td> {{ __($card->status) }} </td>
                                    <!-- <td> {{ optional($card->user)->name }} </td> -->
                                        <td>
                                            @component('admin.sales-invoices.parts.print',[
                                                'id'=> $card->id,
                                                'invoice'=> $card,
                                            ])
                                            @endcomponent
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        @can('create_work_card')
                            <a style="font-size:13px;padding:5px" class="btn  hvr-rectangle-in sr4-wg-btn  "
                               href="{{ route('admin:work-cards.create') }}"> {{ __('analytics.create-work-card') }} </a>
                        @endcan
                    </div>
                    <div class="col-md-6">
                        @can('view_work_card')
                            <a style="font-size:13px;padding:5px" class="btn hvr-rectangle-in sr4-wg-btn   wg-float"
                               href="{{ route('admin:work-cards.index') }}"> {{ __('analytics.show-work-card') }} </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-xs-12">
            <div class="box-content  js__card" style="border-top:4px solid #34A7D2">
                <h5 class="box-title with-control bg-white"
                    style="box-shadow:none !important;border-bottom: none !important;margin-top:-5px;margin-bottom:15px;font-weight:bold">
                    {{ __('analytics.last-sale-invoices') }}
                    <span class="controls">
							<button type="button" class="control fa fa-minus js__card_minus"></button>
							<button type="button" class="control fa fa-times js__card_remove"></button>
						</span>
                </h5>
                <div class="card-content js__card_content">
                    <div class="table-responsive" style="height:300px;overflow-y:scroll;margin-bottom:10px">
                        <table class="table table-striped margin-bottom-10">
                            <thead>
                            <tr>
                                <th> {{ __('analytics.invoice-number') }} </th>
                                <th> {{ __('analytics.invoice-date') }} </th>
                                <th> {{ __('analytics.customer-name') }} </th>
                                <th> {{ __('analytics.payment-type') }} </th>
                            <!-- <th> {{ __('analytics.user') }} </th> -->
                                <th> {{ __('analytics.print') }} </th>
                            </tr>
                            </thead>
                        <!-- <tfoot>
                        <tr>
                            <th colspan="2">
                                @can('create_sales_invoices')
                            <a href="{{ route('admin:sales.invoices.create') }}"> {{ __('analytics.create-sale-invoices') }} </a>
                                @endcan
                            </th>
                            <th colspan="2"></th>
                            <th colspan="2">
                                @can('view_sales_invoices')
                            <a href="{{ route('admin:sales.invoices.index') }}"> {{ __('analytics.show-sale-invoices') }} </a>
                                @endcan
                            </th>
                        </tr> -->
                            </tfoot>
                            <tbody>
                            @if($last_sale_invoices)
                                @foreach ($last_sale_invoices as $invoice)
                                    <tr>
                                        <td>
                                            @if(auth()->user()->can('update_sales_invoices'))
                                                <a href="{{ route('admin:sales.invoices.edit' ,['invoice' => $invoice]) }}">
                                                    {{ $invoice->invoice_number }}
                                                </a>
                                            @else
                                                {{ $invoice->invoice_number }}
                                            @endif
                                        </td>
                                        <td><span class="bg-danger text-white"
                                                  style="padding:3px;border-radius:3px">{{ $invoice->date }}</span></td>
                                        <td> {{ optional($invoice->customer)->name }} </td>
                                        <td>
                                            @if ($invoice->type === "cash")
                                                <span class="label label-success wg-label">
                                    {{__($invoice->type)}}
                                    </span>
                                            @else
                                                <span class="label label-info wg-label">
                                    {{__($invoice->type)}}
                                    </span>
                                            @endif
                                        </td>
                                    <!-- <td> {{ optional($invoice->user)->name }} </td> -->
                                        <td>
                                            <a style="cursor:pointer"
                                               class="btn btn-print-wg text-white  "
                                               data-toggle="modal" onclick="getSalePrintData({{$invoice->id}})"
                                               data-target="#boostrapModal-sale" title="{{__('print')}}">
                                                <i class="fa fa-print"></i> {{__('Print')}}
                                            </a>

                                        </td>
                                    </tr>
                                @endforeach
                            @endif

                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        @can('create_sales_invoices')
                            <a style="font-size:13px;padding:5px" class="btn hvr-rectangle-in sr4-wg-btn  "
                               href="{{ route('admin:sales.invoices.create') }}"> {{ __('analytics.create-sale-invoices') }} </a>
                        @endcan
                    </div>
                    <div class="col-md-6">
                        @can('view_sales_invoices')
                            <a style="font-size:13px;padding:5px" class="btn hvr-rectangle-in sr4-wg-btn   wg-float"
                               href="{{ route('admin:sales.invoices.index') }}"> {{ __('analytics.show-sale-invoices') }} </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row small-spacing">
        <div class="col-lg-6 col-xs-12">
            <div class="box-content card js__card">
                <h4 class="box-title with-control"> {{ __('analytics.counters-charts') }}
                    <span class="controls">
							<button type="button" class="control fa fa-minus js__card_minus"></button>
							<button type="button" class="control fa fa-times js__card_remove"></button>
						</span>
                </h4>
                <div class="card-content js__card_content">
                    <canvas id="counters-charts" class="chartjs-chart"
                            width="476" height="317"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-xs-12">
            <div class="box-content card js__card">
                <h4 class="box-title with-control"> {{ __('analytics.amounts-charts') }}
                    <span class="controls">
							<button type="button" class="control fa fa-minus js__card_minus"></button>
							<button type="button" class="control fa fa-times js__card_remove"></button>
						</span>
                </h4>
                <div class="card-content js__card_content">
                    <canvas id="amounts-charts" class="chartjs-chart"
                            width="476" height="317"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-xs-12 ">
            <div class="box-content card js__card">
                <h4 class="box-title with-control"> {{ __('analytics.receipts-abalytics') }}
                    <span class="controls">
							<button type="button" class="control fa fa-minus js__card_minus"></button>
							<button type="button" class="control fa fa-times js__card_remove"></button>
						</span>
                </h4>
                <div class="card-content js__card_content">
                    <canvas id="receipts-charts" class="chartjs-chart"
                            width="476" height="150"></canvas>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('modals')
    <div class="modal fade" id="boostrapModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel-1">{{__('Work Card Invoice')}}</h4>
                </div>

                <div class="modal-body" id="invoiceDatatoPrint">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm waves-effect waves-light"
                            data-dismiss="modal">{{__('Close')}}</button>
                    <button type="button" class="btn btn-default waves-effect waves-light"
                            onclick="printDownPayment()">
                        <i class='fa fa-print'></i>
                        {{__(' Print')}}
                    </button>
                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="boostrapModal-sale" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel-1">{{__('Sales Invoice')}}</h4>
                </div>

                <div class="modal-body" id="invoiceDatatoPrint-sale">
                </div>
                <div class="modal-footer" style="text-align:center">

                    <button type="button" class="btn btn-primary waves-effect waves-light"
                            onclick="printDownPayment()">
                        <i class='fa fa-print'></i>
                        {{__('Print')}}
                    </button>

                    <button type="button" class="btn btn-danger waves-effect waves-light"
                            data-dismiss="modal"><i class='fa fa-close'></i>
                        {{__('Close')}}</button>

                </div>

            </div>
        </div>
    </div>

@endsection

@section('js')
    <script type="application/javascript">
        function printDownPayment() {
            var element_id = 'card_invoice_print', page_title = document.title;
            print_element(element_id, page_title)
        }

        function getPrintData(id) {
            $.ajax({
                url: "{{ route('admin:card.invoices.show') }}?invoiceID=" + id,
                method: 'GET',
                success: function (data) {
                    $("#invoiceDatatoPrint").html(data.invoice);
                    let total = $("#totalInLetters").text();
                    $("#totalInLetters").html( new Tafgeet(total, '{{env('DEFAULT_CURRENCY')}}').parse())
                }
            });
        }

        function getSalePrintData(id) {
            $.ajax({
                url: "{{ route('admin:sales.invoices.show') }}?invoiceID=" + id,
                method: 'GET',
                success: function (data) {
                    $("#invoiceDatatoPrint-sale").html(data.invoice)
                    let selector = $('td[data-id="data-totalInLetters"]')
                    let total = selector.text()
                    selector.html(new Tafgeet(total, '{{env('DEFAULT_CURRENCY')}}').parse())
                }
            });
        }

        function global_search_redirect(input_id, url) {
            var input = $("#" + input_id).val()
            window.location = url + "?global_check=" + input
        }

        $(document).ready(function () {
            var labels = [], expenses = [], revenue = []
            @foreach($data['chart_analytics']['receipts'] as $index => $receipt)
            @php
                if ($index == 0) continue;
            @endphp
            labels.push("{{ __('analytics.month-' . ($index)) }}")
            expenses.push("{{ $receipt['expenses'] }}")
            revenue.push("{{ isset($receipt['revenue']) ? $receipt['revenue'] : 0 }}")
            @endforeach
            var receipts_config = {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: '{{ __('analytics.expenses-analytics') }}',
                        backgroundColor: 'rgb(54, 162, 235)',
                        borderColor: 'rgb(54, 162, 235)',
                        data: expenses,
                    }, {
                        label: '{{ __('analytics.revenue-analytics') }}',
                        backgroundColor: 'rgb(255, 99, 132)',
                        borderColor: 'rgb(255, 99, 132)',
                        data: revenue,
                    }]
                },
                options: {
                    responsive: true
                }
            };

            var ctx = document.getElementById('receipts-charts').getContext('2d');
            var chart = new Chart(ctx, receipts_config)

            var counters = [], counters_labels = [], amounts = [], amounts_labels = [], backgroundColors = []
            @foreach($invoices as $type => $data)
            backgroundColors.push("{{ $data['background'] }}")

            counters.push({{ $data['count'] }})
            counters_labels.push("{{ __('analytics.counts-invoices-' . $type) }}")

            amounts.push({{ $data['amount'] }})
            amounts_labels.push("{{ __('analytics.amounts-invoices-' . $type) }}")
            @endforeach

            var counter_config = {
                type: 'doughnut',
                data: {
                    datasets: [{
                        data: counters,
                        backgroundColor: backgroundColors
                    }],
                    labels: counters_labels
                },
                options: {
                    responsive: true
                }
            }
            var counter_ctx = document.getElementById('counters-charts').getContext('2d');
            var counter_chart = new Chart(counter_ctx, counter_config)

            var amount_config = {
                type: 'pie',
                data: {
                    datasets: [{
                        data: amounts,
                        backgroundColor: backgroundColors
                    }],
                    labels: amounts_labels
                },
                options: {
                    responsive: true
                }
            }

            var amount_ctx = document.getElementById('amounts-charts').getContext('2d');
            var amount_chart = new Chart(amount_ctx, amount_config)
        })
    </script>
@endsection
