@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('Work Cards Payments') }} </title>
@endsection


@section('content')
    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin:work-cards.index')}}"> {{__('Work Cards')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Work Cards Payments')}}</li>
            </ol>
        </nav>

        @include('admin.work_cards.revenue_receipts.header')
    </div>
    <div class="col-xs-12">
        <div class="box-content card bordered-all js__card">
                <span class="ribbon-bottom ribbon-b-r">
                <span> <i class="fa fa-money"></i>  {{__('Work Cards Payments')}}</span>
                 </span>

                 <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">
                    <li class="list-inline-item">
                        @if ($invoice->remaining > 0)
                            <a style="margin-bottom: 12px; border-radius: 5px"
                               type="button"
                               href="{{route('admin:revenueReceipts.create',['card_invoice_id'=> $invoice->id])}}"
                               class="btn btn-icon btn-icon-left btn-create-wg waves-effect waves-light hvr-bounce-to-left">
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
                            <!-- <th scope="col">{!! __('Receiver') !!}</th> -->
                            <th scope="col">{!! __('Revenue Item') !!}</th>
                            <th scope="col">{!! __('Cost') !!}</th>
                            <th scope="col">{!! __('Deportation Method') !!}</th>
                            <th scope="col">{!! __('Deportation') !!}</th>
                            <th scope="col">{!! __('Payment Type') !!}</th>
                            <!-- <th scope="col">{!! __('Date') !!}</th> -->
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
                            <!-- <th scope="col">{!! __('Receiver') !!}</th> -->
                            <th scope="col">{!! __('Revenue Item') !!}</th>
                            <th scope="col">{!! __('Cost') !!}</th>
                            <th scope="col">{!! __('Deportation Method') !!}</th>
                            <th scope="col">{!! __('Deportation') !!}</th>
                            <th scope="col">{!! __('Payment Type') !!}</th>
                            <!-- <th scope="col">{!! __('Date') !!}</th> -->
                            <th scope="col">{!! __('Created at') !!}</th>
                            <th scope="col">{!! __('Updated at') !!}</th>
                            <th scope="col">{!! __('Options') !!}</th>
                            <th scope="col">{!! __('Select') !!}</th>
                        </tr>
                        </tfoot>
                        <tbody>
                        @foreach($invoice->RevenueReceipts as $index=>$receipt)
                            <tr>
                                <td>{!! $receipt->revenue_number !!}</td>
                                <!-- <td>{!! $receipt->receiver !!}</td> -->
                                <td>{!! optional($receipt->revenueItem)->item !!}</td>
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
                                <!-- <td>{!! $receipt->date !!}</td> -->
                                <td>{!! $receipt->created_at->format('y-m-d h:i:s A') !!}</td>
                                <td>{!! $receipt->updated_at->format('y-m-d h:i:s A')!!}</td>
                                <td>
{{--                                    @component('admin.buttons._delete_selected',[--}}
{{--                                      'id' => $receipt->id,--}}
{{--                                        'route' => 'admin:users.deleteSelected',--}}
{{--                                       ])--}}
{{--                                    @endcomponent--}}

                                    @component('admin.buttons._edit_button',[
                                                'id'=>$receipt->id,
                                                'route' => 'admin:revenueReceipts.edit',
                                                 ])
                                    @endcomponent

                                    @component('admin.buttons._delete_button',[
                                                'id'=> $receipt->id,
                                                'route' => 'admin:revenueReceipts.destroy',
                                                 ])
                                    @endcomponent
                                    @component('admin.sales-invoices.revenue_receipts.show', [
                                      'id' => $receipt->id,
                                       'revenue' => $receipt,
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
