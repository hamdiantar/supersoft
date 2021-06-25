@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('Return Purchase Invoice Payments') }} </title>
@endsection

@section('content')
    <div class="row small-spacing">
    <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
            <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item"><a href="{{route('admin:purchase_returns.index')}}"> {{__('Purchase Invoices Returns')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Expenses')}}</li>
            </ol>
        </nav>
        @include('admin.purchase_returns.parts.header')
    </div>
    <div class="col-xs-12">
        <div class="box-content card bordered-all js__card">
                <span class="ribbon-bottom ribbon-b-r">
                <span> <i class="fa fa-money"></i>   {{__('Return Purchase Invoice Payments')}}</span>
                 </span>

                 <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">
                    <li class="list-inline-item">
                        @if ($invoice->remaining > 0)
                            <a style="margin-bottom: 12px; border-radius: 5px"
                               type="button"
                               href="{{url('admin/revenueReceipts/create?purchase_return_id='.request()->segment(5))}}"
                               class="btn btn-icon btn-icon-left btn-primary waves-effect waves-light">
                                {{__('Create New Revenue')}}
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
                            <th scope="col">{!! __('Revenue No') !!}</th>
                            <!-- <th scope="col">{!! __('Receiver') !!}</th> -->
                            <th scope="col">{!! __('Revenue Item') !!}</th>
                            <th scope="col">{!! __('Cost') !!}</th>
                            <!-- <th scope="col">{!! __('Date') !!}</th> -->
                            <th scope="col">{!! __('Deportation Method') !!}</th>
                            <th scope="col">{!! __('Deportation') !!}</th>
                            <th scope="col">{!! __('Payment Type') !!}</th>
                            <th scope="col">{!! __('Created at') !!}</th>
                            <th scope="col">{!! __('Updated at') !!}</th>
                            <th scope="col">{!! __('Options') !!}</th>
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
                            <th scope="col">{!! __('Revenue No') !!}</th>
                            <!-- <th scope="col">{!! __('Receiver') !!}</th> -->
                            <th scope="col">{!! __('Revenue Item') !!}</th>
                            <th scope="col">{!! __('Cost') !!}</th>
                            <!-- <th scope="col">{!! __('Date') !!}</th> -->
                            <th scope="col">{!! __('Deportation Method') !!}</th>
                            <th scope="col">{!! __('Deportation') !!}</th>
                            <th scope="col">{!! __('Payment Type') !!}</th>
                            <th scope="col">{!! __('Created at') !!}</th>
                            <th scope="col">{!! __('Updated at') !!}</th>
                            <th scope="col">{!! __('Options') !!}</th>
                            <th scope="col">{!! __('Select') !!}</th>
                        </tr>
                        </tfoot>
                        <tbody>
                        @foreach($revenues as $index=>$revenue)
                            <tr>
                                <td>{!! $revenue->revenue_number !!}</td>
                                <!-- <td>{!! $revenue->receiver !!}</td> -->
                                <td>
                                <span class="label label-primary wg-label">
                                {!! optional($revenue->revenueItem)->item !!}
                                </span>
                                </td>
                                <td class="text-danger">{!! number_format($revenue->cost , 2)!!}</td>
                                <!-- <td>{!! $revenue->date !!}</td> -->
                                <td>
                                    <span class="label label-primary wg-label">
                                         {{ __("words.".$revenue->deportation) }}
                                     </span>
                                </td>
                                <td>
                                 <span class="label label-danger wg-label">
                                        @if($revenue->deportation == 'safe' && $revenue->locker)
                                         {{ $revenue->locker->name }}
                                     @endif
                                     @if($revenue->deportation == 'bank' && $revenue->bank)
                                         {{ $revenue->bank->name }}
                                     @endif
                                        </span>
                                </td>

                                <td>
                                    <span class="label label-primary wg-label">
                                         {{ __("words.".$revenue->payment_type) }}
                                     </span>
                                </td>
                                <td>{!! $revenue->created_at->format('y-m-d h:i:s A') !!}</td>
                                <td>{!! $revenue->updated_at->format('y-m-d h:i:s A')!!}</td>
                                <td>

                                @component('admin.revenueReceipts.parts.show', [
                                                'id' => $revenue->id,
                                                'revenue' => $revenue,
                                        ])
                                    @endcomponent

                                    @component('admin.buttons._edit_button',[
                                                'id'=>$revenue->id,
                                                'route' => 'admin:revenueReceipts.edit',
                                                 ])
                                    @endcomponent

                                    @component('admin.buttons._delete_button',[
                                                'id'=> $revenue->id,
                                                'route' => 'admin:revenueReceipts.destroy',
                                                 ])
                                    @endcomponent

                                </td>
                                <td>
                                @component('admin.buttons._delete_selected',[
                                           'id' => $revenue->id,
                                           'route' => 'admin:revenueReceipts.deleteSelected',
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
