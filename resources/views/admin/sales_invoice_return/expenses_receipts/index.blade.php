@extends('admin.layouts.app')


@section('title')
    <title>{{ __('Super Car') }} - {{ __('Return Sales Invoice Payments') }} </title>
@endsection


@section('content')
    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a
                            href="{{route('admin:sales.invoices.return.index')}}"> {{__('Sales Invoices Returns')}}</a>
                </li>
                <li class="breadcrumb-item active"> {{__('Expenses')}}</li>
            </ol>
        </nav>

        <div class="col-xs-12">
            <div class="box-content card bordered-all js__card top-search">
                <h4 class="box-title with-control">
                    <i class="fa fa-money"></i> {{__('Return Sales Invoice Payments')}}
                </h4>
                <div class="card-content js__card_conten">
                    <table class="table table-responsive table-bordered table-striped">
                        <thead>
                        <tr>
                            <th scope="col">{!! __('Invoice Number') !!}</th>
                            <th scope="col">{!! __('Total') !!}</th>
                            <th scope="col">{!! __('Paid') !!}</th>
                            <th scope="col">{!! __('Remaining') !!}</th>
                            <th scope="col">{!! __('Invoice Type') !!}</th>
                        </tr>
                        </thead>
                        <tbody>

                        <tr style="color:black">
                            <td>{{$invoice->invoice_number}}</td>
                            <td class="text-danger">{{number_format($invoice->total, 2)}}</td>
                            <td class="text-danger">{{number_format($invoice->paid, 2)}}</td>
                            <td class="text-danger">{{number_format( $invoice->remaining, 2)}}</td>
                            <td>
                                <span class="label label-warning wg-label">
                                {{__($invoice->type)}}
                                </span>
                            </td>
                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.card-content -->
        </div>
        <!-- /.box-content -->


    </div>

    <div class="col-xs-12">
        <div class="box-content card bordered-all js__card">
                <span class="ribbon-bottom ribbon-b-r">
                <span> <i class="fa fa-money"></i>   {{__('Return Sales Invoice Payments')}}</span>
                 </span>

            <div class="card-content js__card_content" style="">
                <ul class="list-inline pull-left top-margin-wg">
                    <li class="list-inline-item">
                        @if ($invoice->remaining > 0)
                            <a style="margin-bottom: 12px; border-radius: 5px"
                               type="button"
                               href="{{route('admin:expenseReceipts.create',['sales_invoice_return_id'=> $invoice->id])}}"
                               class="btn btn-icon btn-icon-left btn-primary waves-effect waves-light">
                                {{__('Create New Revenue Receipt')}}
                                <i class="ico fa fa-plus"></i>

                            </a>
                        @endif

                    </li>
                    <li class="list-inline-item">
                        @component('admin.buttons._confirm_delete_selected',[
                        'route' => 'admin:revenueReceipts.deleteSelected',
                         ])
                        @endcomponent
                    </li>
                </ul>
                <div class="clearfix"></div>
                <div class="table-responsive">
                    <table id="expensesInvoices" class="table table-bordered" style="width:100%">
                        <thead>
                        <tr>
                            <th scope="col">{!! __('Receipt No') !!}</th>
                            <th scope="col">{!! __('Receiver') !!}</th>
                            <th scope="col">{!! __('Expenses Item') !!}</th>
                            <th scope="col">{!! __('Cost') !!}</th>
                            <th scope="col">{!! __('Deportation Method') !!}</th>
                            <th scope="col">{!! __('Deportation') !!}</th>
                            <th scope="col">{!! __('Payment Type') !!}</th>
                            <th scope="col">{!! __('Date') !!}</th>
                            <th scope="col">{!! __('Created at') !!}</th>
                            <th scope="col">{!! __('Updated at') !!}</th>
                            <th scope="col">{!! __('Options') !!}
                            </th>
                            <th scope="col">
                                <div class="checkbox danger">
                                    <input type="checkbox" id="select-all">
                                    <label for="select-all"></label>
                                </div>
                                {!! __('Select') !!}</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th scope="col">{!! __('Receipt No') !!}</th>
                            <th scope="col">{!! __('Receiver') !!}</th>
                            <th scope="col">{!! __('Expenses Item') !!}</th>
                            <th scope="col">{!! __('Cost') !!}</th>
                            <th scope="col">{!! __('Deportation Method') !!}</th>
                            <th scope="col">{!! __('Deportation') !!}</th>
                            <th scope="col">{!! __('Payment Type') !!}</th>
                            <th scope="col">{!! __('Date') !!}</th>
                            <th scope="col">{!! __('Created at') !!}</th>
                            <th scope="col">{!! __('Updated at') !!}</th>
                            <th scope="col">{!! __('Options') !!}</th>
                            <th scope="col">{!! __('Select') !!}</th>
                        </tr>
                        </tfoot>
                        <tbody>
                        @foreach($invoice->expensesReceipts as $index=>$receipt)
                            <tr>
                                <td>{!! $receipt->expenses_number !!}</td>
                                <td>{!! $receipt->receiver !!}</td>
                                <td>{!! optional($receipt->expenseItem)->item !!}</td>
                                <td>{!! number_format($receipt->cost , 2)!!}</td>
                                <td>
                                    <span class="label label-primary wg-label">
                                         {{ __("words.".$receipt->deportation) }}
                                     </span>
                                </td>
                                <td>
                                <span class="label label-danger wg-label">
                                        @if($receipt->deportation == 'safe' && $receipt->locker)
                                        {{ $receipt->locker->name }}
                                    @endif
                                    @if($receipt->deportation == 'bank' && $receipt->bank)
                                        {{ $receipt->bank->name }}
                                    @endif
                                        </span>
                                </td>
                                <td>
                                    <span class="label label-primary wg-label">
                                         {{ __("words.".$receipt->payment_type) }}
                                     </span>
                                </td>
                                <td>{!! $receipt->date !!}</td>
                                <td>{!! $receipt->created_at->format('y-m-d h:i:s A') !!}</td>
                                <td>{!! $receipt->updated_at->format('y-m-d h:i:s A')!!}</td>
                                <td>

                                <!-- <a class="btn btn-info"
                                           href="{{route('admin:expenseReceipts.edit',
                                           ['id' => $receipt->id,'sales_invoice_return_id'=> $invoice->id])}}">
                                            <i class="fa fa-edit"></i>
                                        </a>  -->

                                    @component('admin.expenseReceipts.parts.show', [
                                      'id' => $receipt->id,
                                       'expense' => $receipt,
                                    ])
                                    @endcomponent
                                    @component('admin.buttons._edit_button',[
                                                  'id'=>$receipt->id,
                                                  'route' => 'admin:expenseReceipts.edit',
                                                  'sales_invoice_return_id'=> $invoice->id,
                                                    ])
                                    @endcomponent

                                    @component('admin.buttons._delete_button',[
                                                 'id'=> $receipt->id,
                                                'route' => 'admin:expenseReceipts.destroy',
                                                ])
                                    @endcomponent

                                </td>

                                <td>
                                    @component('admin.buttons._delete_selected',[
                                         'id' => $receipt->id,
                                       'route' => 'admin:expenseReceipts.deleteSelected',
                                        ])
                                    @endcomponent
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script type="application/javascript">
        invoke_datatable($('#expensesInvoices'))
    </script>
@endsection
