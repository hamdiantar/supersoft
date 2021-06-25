@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('Purchase Invoices') }} </title>
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('accounting-module/daily-restriction.css') }}"/>
@endsection

@section('content')
    <div class="row small-spacing">

         <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Purchase Invoices')}}</li>
            </ol>
        </nav>


        @include('admin.purchase-invoices.parts.search')

        <div class="col-xs-12">
        <div class="box-content card bordered-all js__card">
                <h4 class="box-title bg-secondary with-control">
                <i class="fa fa-file-o"></i>  {{__('Purchase Invoices')}}
                 </h4>

                 <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">

                        <li class="list-inline-item">
                            @include('admin.buttons.add-new', [
                           'route' => 'admin:purchase-invoices.create',
                           'new' => '',
                          ])
                        </li>

                            <li class="list-inline-item">
                                @component('admin.buttons._confirm_delete_selected',[
                                                      'route' => 'admin:purchase-invoices.deleteSelected',
                                                       ])
                                @endcomponent
                            </li>

                    </ul>
                    <div class="clearfix"></div>
                    <div class="table-responsive">
                        @php
                            $view_path = 'admin.purchase-invoices.options-datatable';
                        @endphp
                        @include($view_path . '.option-row')
                        <div class="clearfix"></div>
                        <table id="purchaseInvoices" class="table table-bordered" style="width:100%;margin-top:15px">
                            @include($view_path . '.table-thead')
                            <tfoot>
                            <tr>
                                <th class="text-center column-invoice-number" scope="col">{!! __('Invoice Number') !!}</th>
                                <th class="text-center column-supplier" scope="col">{!! __('Supplier Name') !!}</th>
                                <!-- <th scope="col">{!! __('Supplier Phone') !!}</th> -->
                                <th class="text-center column-invoice-type" scope="col">{!! __('Invoice Type') !!}</th>
                                <th class="text-center column-payment" scope="col">{!! __('Payment status') !!}</th>
                                <th class="text-center column-paid" scope="col">{!! __('Paid') !!}</th>
                                <th class="text-center column-remaining" scope="col">{!! __('Remaining') !!}</th>
                                <!-- <th scope="col">{!! __('User Name') !!}</th> -->
                                <th class="text-center column-created-at" scope="col">{!! __('created at') !!}</th>
                                <th class="text-center column-updated-at" scope="col">{!! __('Updated at') !!}</th>
                                <th scope="col">{!! __('Expenses') !!}</th>
                                <th scope="col">{!! __('Options') !!}</th>
                                <th scope="col">{!! __('Select') !!}</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($invoices as $index=>$invoice)
                                <tr>
                                    <td class="text-center column-invoice-number">{!! $invoice->invoice_number !!}</td>
                                    <td class="text-center column-supplier">{!! optional($invoice->supplier)->name !!}</td>
                                    <!-- <td>{!! optional($invoice->supplier)->phone_1 ??  optional($invoice->supplier)->phone_2!!}</td> -->

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


                                    @if ($invoice->type === "cash")
                                        @if ($invoice->remaining  == 0)
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
                                    @endif
                                    @if ($invoice->type === "credit")
                                        @if ($invoice->remaining  == 0)
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
                                    @endif

                                    <!-- <td>{!! auth()->user()->name !!}</td> -->
                                    <td class="text-center column-created-at">{!! $invoice->created_at->format('y-m-d h:i:s A') !!}</td>
                                    <td class="text-center column-updated-at">{!! $invoice->updated_at->format('y-m-d h:i:s A')!!}</td>
                                    <td>

                                        <a href="{{route('admin:purchase-invoices.expenses', ['id' => $invoice->id])}}"
                                            class="btn btn-info-wg hvr-radial-out  ">
                                            <i class="fa fa-money"></i> {{__('Payments')}}
                                        </a>
                                    </td>
                                    <td>


                                        @component('admin.buttons._edit_button',[
                                                    'id'=>$invoice->id,
                                                    'route' => 'admin:purchase-invoices.edit',
                                                     ])
                                        @endcomponent

                                        @component('admin.buttons._delete_button',[
                                                    'id'=> $invoice->id,
                                                    'route' => 'admin:purchase-invoices.destroy',
                                                     ])
                                        @endcomponent

                                       @component('admin.purchase-invoices.parts.print',[
                                                   'id'=> $invoice->id,
                                                   'invoice'=> $invoice,
                                                    ])
                                        @endcomponent
                                    </td>
                                    <td>
                                    @component('admin.buttons._delete_selected',[
                                               'id' => $invoice->id,
                                               'route' => 'admin:purchase-invoices.deleteSelected',

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
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel-1">{{__('Purchase Invoice')}}</h4>
                </div>

                <div class="modal-body" id="invoiceDatatoPrint">
                </div>
                <div class="modal-footer" style="text-align:center">
                <button type="button" class="btn btn-primary waves-effect waves-light"
                            onclick="printDownPayment()">
                        <i class='fa fa-print'></i>
                        {{__('Print')}}
                    </button>
                    <button type="button" class="btn btn-danger waves-effect waves-light" data-dismiss="modal">
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

        $( document ).ready(function() {

            var id = '{{request()->query('invoice')}}'

            getPrintData(id);

            $('#boostrapModal').modal('show');
        });

        @endif

        // invoke_datatable($('#purchaseInvoices'))

        function printDownPayment() {
            var element_id = 'purchase_invoice_print' ,page_title = document.title
            print_element(element_id ,page_title)
        }

        function getPrintData(id) {
            $.ajax({
                url: "{{ route('admin:purchase-invoices.show') }}?invoiceID=" + id,
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
