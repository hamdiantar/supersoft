@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('Purchase Invoice Payments') }} </title>
@endsection

@section('content')
    <div class="row small-spacing">
        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
            <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item"><a href="{{route('admin:purchase-invoices.index')}}"> {{__('Purchase Invoices')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Expenses')}}</li>
            </ol>
        </nav>
        @include('admin.purchase-invoices.parts.header')
    </div>

    <div class="col-xs-12">
        <div class="box-content card bordered-all js__card">
                <span class="ribbon-bottom ribbon-b-r">
                <span> <i class="fa fa-money"></i>   {{__('Purchase Invoice Payments')}}</span>
                 </span>

                 <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">
                    <li class="list-inline-item">
                        @if ($invoice->remaining > 0)
                            <a style="margin-bottom: 12px; border-radius: 5px"
                               type="button"
                               href="{{url('admin/expenseReceipts/create?invoice_id='.request()->segment(5))}}"
                               class="btn btn-icon btn-icon-left btn-create-wg waves-effect waves-light hvr-bounce-to-left">
                                {{__('Create New Expense')}}
                                <i class="ico fa fa-plus"></i>

                            </a>
                        @endif

                    </li>
                    <li class="list-inline-item">
                        @component('admin.buttons._confirm_delete_selected',[
                        'route' => 'admin:expenseReceipts.deleteSelected',
                        ])
                        @endcomponent
                    </li>
                </ul>
                <div class="clearfix"></div>
                <div class="table-responsive">
                    <table id="expensesInvoices" class="table table-bordered" style="width:100%">
                        <thead>
                        <tr>
                            <th scope="col">{!! __('Expense No') !!}</th>
                            <!-- <th scope="col">{!! __('Receiver') !!}</th> -->
                            <th scope="col">{!! __('Expense Item') !!}</th>
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
                            <th scope="col">{!! __('Expense No') !!}</th>
                            <!-- <th scope="col">{!! __('Receiver') !!}</th> -->
                            <th scope="col">{!! __('Expense Item') !!}</th>
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
                        @foreach($expenses as $index=>$expense)
                            <tr>
                                <td>{!! $expense->expenses_number !!}</td>
                                <!-- <td>{!! $expense->receiver !!}</td> -->
                                <td>
                                <span class="label label-primary wg-label">
                                {!! optional($expense->expenseItem)->item !!}
                                </span>
                                </td>
                                <td class="text-danger">{!! number_format($expense->cost , 2)!!}</td>
                                <!-- <td>{!! $expense->date !!}</td> -->
                                <td>
                                    <span class="label label-primary wg-label">
                                         {{ __("words.".$expense->deportation) }}
                                     </span>
                                </td>
                                <td>
                                <span class="label label-danger wg-label">
                                        @if($expense->deportation == 'safe' && $expense->locker)
                                        {{ $expense->locker->name }}
                                    @endif
                                    @if($expense->deportation == 'bank' && $expense->bank)
                                        {{ $expense->bank->name }}
                                    @endif
                                        </span>
                                </td>

                                <td>
                                    <span class="label label-primary wg-label">
                                         {{ __("words.".$expense->payment_type) }}
                                     </span>
                                </td>
                                <td>{!! $expense->created_at->format('y-m-d h:i:s A') !!}</td>
                                <td>{!! $expense->updated_at->format('y-m-d h:i:s A')!!}</td>
                                <td>
                                @component('admin.expenseReceipts.parts.show', [
                                                'id' => $expense->id,
                                                'expense' => $expense,
                                        ])
                                    @endcomponent

                                        <a class="btn btn-wg-edit hvr-radial-out  " href="{{url('admin/expenseReceipts/edit/'.$expense->id.'?invoice_id='.request()->segment(5))}}">
                                             <i class="fa fa-edit"></i>  {{__('Edit')}}
                                        </a>
                                    @component('admin.buttons._delete_button',[
                                                'id'=> $expense->id,
                                                'route' => 'admin:expenseReceipts.destroy',
                                                 ])
                                    @endcomponent

                                </td>
                                <td>

                                @component('admin.buttons._delete_selected',[
                                           'id' => $expense->id,
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
