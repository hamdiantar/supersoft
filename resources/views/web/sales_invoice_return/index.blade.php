@extends('web.layout.app')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('Sales Invoices Returns') }} </title>
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('accounting-module/daily-restriction.css') }}"/>
@endsection

@section('content')
    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('web:dashboard')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active">{{__('Sales Invoices Returns')}}</li>
            </ol>
        </nav>

        <div class="col-xs-12">
            <div class="box-content card bordered-all js__card top-search">
                <h4 class="box-title with-control">
                    <i class="fa fa-search"></i>{{__('Search filters')}}
                    <span class="controls">
							<button type="button" class="control fa fa-minus js__card_minus"></button>
							<button type="button" class="control fa fa-times js__card_remove"></button>
						</span>
                    <!-- /.controls -->
                </h4>
                <!-- /.box-title -->
                <div class="card-content js__card_content" style="padding:20px">
                    <form action="{{route('web:sales.invoices.return.index')}}" method="get" id="filtration-form">
                        <input type="hidden" name="rows" value="{{ isset($_GET['rows']) ? $_GET['rows'] : '' }}"/>
                        <input type="hidden" name="key" value="{{ isset($_GET['key']) ? $_GET['key'] : '' }}"/>
                        <input type="hidden" name="sort_method" value="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : '' }}"/>
                        <input type="hidden" name="sort_by" value="{{ isset($_GET['sort_by']) ? $_GET['sort_by'] : '' }}"/>
                        <input type="hidden" name="invoker"/>
                        <ul class="list-inline margin-bottom-0 row">

                            <li class="form-group col-md-3">
                                <label> {{ __('Return Sales Invoice Number') }} </label>
                                <select name="invoice_number" class="form-control js-example-basic-single">
                                    <option value="">{{__('Select Return Sales Invoice Number')}}</option>
                                    @foreach($salesInvoices as $invoice)
                                        <option value="{{$invoice->id}}">{{$invoice->inv_number}}</option>
                                    @endforeach
                                </select>
                            </li>
                            <li class="form-group col-md-3">
                                <label> {{ __('Invoice Type') }} </label>
                                <select name="type" class="form-control js-example-basic-single">
                                    <option value="">{{__('Select Type')}}</option>
                                    <option value="cash">{{__('Cash')}}</option>
                                    <option value="credit">{{ __('Credit') }}</option>
                                </select>
                            </li>

                            <li class="form-group col-md-3">
                                <label> {{ __('Date From') }} </label>
                                <input type="date" name="date_from" class="form-control"
                                       placeholder="{{__('Select Date')}}">
                            </li>

                            <li class="form-group col-md-3">
                                <label> {{ __('Date To') }} </label>
                                <input type="date" name="date_to" class="form-control"
                                       placeholder="{{__('Select Date')}}">
                            </li>

                            <li class="form-group col-md-4">

                            </li>
                        </ul>
                        <button type="submit" class="btn sr4-wg-btn   waves-effect waves-light hvr-rectangle-out">
                            <i class=" fa fa-search "></i>
                            {{__('Search')}}
                        </button>
                        <a href="{{route('web:sales.invoices.return.index')}}"
                           class="btn bc-wg-btn   waves-effect waves-light hvr-rectangle-out"><i
                                class=" fa fa-reply"></i> {{__('Back')}}
                        </a>
                    </form>

                </div>
            </div>
        </div>

        <div class="col-xs-12">
            <div class="box-content card bordered-all js__card">
                <h4 class="box-title with-control">
                <span> <i class="fa fa-file-text-o"></i>  {{__('Sales Invoices Returns')}}</span>
                 </h4>

                <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">

                    </ul>
                    <div class="clearfix"></div>
                    <div class="table-responsive">
                        @php
                            $view_path = 'web.sales_invoice_return.options-datatable';
                        @endphp
                        @include($view_path . '.option-row')
                        <div class="clearfix"></div>
                        <table id="purchaseInvoices" class="table table-bordered" style="width:100%;margin-top:15px">
                            @include($view_path . '.table-thead')
                            <tfoot>
                            <tr>
                                <th class="text-center column-invoice-number" scope="col">{!! __('Invoice Number') !!}</th>
                                <th class="text-center column-customer" scope="col">{!! __('Customer Name') !!}</th>
                                <th class="text-center column-invoice-type" scope="col">{!! __('Invoice Type') !!}</th>
                                <th class="text-center column-payment" scope="col">{!! __('Payment status') !!}</th>
                                <th class="text-center column-paid" scope="col">{!! __('Paid') !!}</th>
                                <th class="text-center column-remaining" scope="col">{!! __('Remaining') !!}</th>
                                <th class="text-center column-created-at" scope="col">{!! __('created at') !!}</th>
                                <th class="text-center column-updated-at" scope="col">{!! __('Updated at') !!}</th>
{{--                                <th scope="col">{!! __('Expenses') !!}</th>--}}
                                <th scope="col">{!! __('Options') !!}</th>

                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($invoices as $index=>$invoice)
                                @php
                                    $paid =  $invoice->paid;
                                    $remaining = $invoice->remaining;
                                @endphp
                                <tr>
                                    <td class="text-center column-invoice-number">{!! $invoice->inv_number !!}</td>
                                    <td class="text-center column-customer">{!! optional($invoice->customer)->name !!}</td>

                                    <td class="text-center column-invoice-type">
                                        @if ($invoice->type === "cash")
                                            <span class="label label-primary wg-label">
                                                {{__($invoice->type)}}
                                            </span>
                                        @else
                                            <span class="label label-info wg-label">
                                                {{__($invoice->type)}}
                                            </span>
                                        @endif
                                    </td>


                                    @if($remaining == 0)
                                        <td class="text-center column-payment">
                                              <span class="label label-warning wg-label">
                                                {!! __('Completed') !!}
                                                </span>
                                        </td>
                                    @else
                                        <td class="text-center column-payment">
                                            <span class="label label-danger wg-label">
                                                {!! __('Not Completed') !!}
                                                </span>
                                        </td>
                                    @endif

                                    <td class="text-danger text-center column-paid">{!! number_format($invoice->paid, 2) !!}</td>
                                    <td class="text-danger text-center column-remaining">{!! number_format($invoice->remaining ,2)!!}</td>

                                    <td class="text-center column-created-at">{!! $invoice->created_at->format('y-m-d h:i:s A') !!}</td>
                                    <td class="text-center column-updated-at">{!! $invoice->updated_at->format('y-m-d h:i:s A')!!}</td>

                                    <td>

                                        @component('admin.sales_invoice_return.parts.print',[
                                                     'id'=> $invoice->id,
                                                     'invoice'=> $invoice,
                                                      ])
                                        @endcomponent

                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $invoices->links() }}
                    </div>
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
                    <h4 class="modal-title" id="myModalLabel-1">{{__('Return Sales Invoice')}}</h4>
                </div>

                <div class="modal-body" id="invoiceDatatoPrint">
                </div>
                <div class="modal-footer" style="text-align:center">

                    <button type="button" class="btn btn-primary waves-effect waves-light"
                            onclick="printDownPayment()">
                        <i class='fa fa-print'></i>
                        {{__('Print')}}
                    </button>

                    <button type="button" class="btn btn-danger waves-effect waves-light"
                            data-dismiss="modal">

                        <i class='fa fa-close'></i>
                        {{__('Close')}}</button>

                </div>

            </div>
        </div>
    </div>
    @include($view_path . '.column-visible')
@endsection

@section('js')
    <script type="application/javascript">

        @if(request()->query('print_type'))

        $(document).ready(function () {

            var id = '{{request()->query('invoice')}}'

            getPrintData(id);

            $('#boostrapModal').modal('show');
        });

        @endif

        // invoke_datatable($('#purchaseInvoices'))

        function printDownPayment() {
            var element_id = 'sales_invoice_print', page_title = document.title
            print_element(element_id, page_title)
        }

        function getPrintData(id) {
            $.ajax({
                url: "{{ route('web:sales.invoices.return.show') }}?invoiceID=" + id,
                method: 'GET',
                success: function (data) {
                    $("#invoiceDatatoPrint").html(data.invoice)
                    let total = $("#totalInLetters").text()
                    $("#totalInLetters").html( new Tafgeet(total, '{{env('DEFAULT_CURRENCY')}}').parse())
                }
            });
        }
    </script>
    <script type="application/javascript" src="{{ asset('accounting-module/options-for-dt.js') }}"></script>
@endsection
