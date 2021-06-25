@extends('admin.layouts.app')
@section('title')
    <title>{{ __('Super Car') }} - {{ __('Expenses Receipts') }} </title>
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('accounting-module/daily-restriction.css') }}"/>
@endsection

@section('content')
    <div class="row small-spacing">
        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Expenses Receipts')}}</li>
            </ol>
        </nav>

        @include('admin.expenseReceipts.parts.search')

        <div class="col-xs-12">

            <div class="box-content card bordered-all js__card">
                <h4 class="box-title bg-secondary with-control">
                    <i class="fa fa-money"></i> {{__('Expenses Receipts')}}
                </h4>

                <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">

                        <li class="list-inline-item">
                            @include('admin.buttons.add-new', [
                       'route' => 'admin:expenseReceipts.create',
                           'new' => '',
                          ])
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
                        @php
                            $view_path = 'admin.expenseReceipts.options-datatable';
                        @endphp
                        @include($view_path . '.option-row')
                        <div class="clearfix"></div>
                        <table id="expenseReceipts" class="table table-bordered" style="width:100%;margin-top:15px">
                            @include($view_path . '.table-thead')
                            <tfoot>
                            <tr>
                                <th class="text-center column-expense-no" scope="col">{!! __('Expense No') !!}</th>
                                <th class="text-center column-receiver" scope="col">{!! __('Receiver') !!}</th>
                                <th class="text-center column-expense-item" scope="col">{!! __('Expense Item') !!}</th>
                                <th class="text-center column-cost" scope="col">{!! __('Cost') !!}</th>
                                <th class="text-center column-deportation-method"
                                    scope="col">{!! __('Deportation Method') !!}</th>
                                <th class="text-center column-deportation" scope="col">{!! __('Deportation') !!}</th>
                            <!-- <th class="text-center column-date" scope="col">{!! __('Date') !!}</th> -->
                                <th class="text-center column-payment-type" scope="col">{!! __('Payment Type') !!}</th>
                                <th class="text-center column-created-at" scope="col">{!! __('Created at') !!}</th>
                                <th class="text-center column-updated-at" scope="col">{!! __('Updated at') !!}</th>
                                <th class="text-center column-options" scope="col">{!! __('Options') !!}</th>
                                <th class="text-center column-select" scope="col">{!! __('Select') !!}</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($expenseReceipts as $index=>$expense)
                                <tr>
                                    <td class="text-center column-expense-no">{!! $expense->expenses_number !!}</td>
                                    <td class="text-center column-receiver">{!! $expense->receiver !!}</td>
                                    <td class="text-center column-expense-item">
                            <span class="label label-primary wg-label">
                            {!! optional($expense->expenseItem)->item !!}
                            </span>
                                    </td>
                                    <td class="text-danger text-center column-cost">{!! $expense->cost !!}</td>
                                    <td class="text-center column-deportation-method">
                                    <span class="label label-primary wg-label">
                                         {{ __("words.".$expense->deportation) }}
                                     </span>
                                    </td>
                                    <td class="text-center column-deportation">
                                <span class="label label-danger wg-label">
                                        @if($expense->deportation == 'safe' && $expense->locker)
                                        {{ $expense->locker->name }}
                                    @endif
                                    @if($expense->deportation == 'bank' && $expense->bank)
                                        {{ $expense->bank->name }}
                                    @endif
                                        </span>
                                    </td>
                                    <td class="text-center column-payment-type">
                                    <span class="label label-primary wg-label">
                                         {{ __("words.".$expense->payment_type) }}
                                     </span>
                                    </td>
                                <!-- <td class="text-center column-expense-no">{!! $expense->date !!}</td> -->
                                    <td class="text-center column-created-at">{!! $expense->created_at->format('y-m-d h:i:s A') !!}</td>
                                    <td class="text-center column-updated-at">{!! $expense->updated_at->format('y-m-d h:i:s A')!!}</td>
                                    <td>

                                        @component('admin.expenseReceipts.parts.show', [
                                                        'id' => $expense->id,
                                                        'expense' => $expense,
                                                ])
                                        @endcomponent

                                        @if($expense->uneditable())
                                            @component('admin.buttons._edit_button',[
                                                        'id'=>$expense->id,
                                                        'route' => 'admin:expenseReceipts.edit',
                                                         ])
                                            @endcomponent

                                            @component('admin.buttons._delete_button',[
                                                        'id'=> $expense->id,
                                                        'route' => 'admin:expenseReceipts.destroy',
                                                         ])
                                            @endcomponent
                                        @endif

                                    </td>
                                    <td class="text-center column-expense-no">
                                        @if($expense->uneditable())
                                            @component('admin.buttons._delete_selected',[
                                                       'id' => $expense->id,
                                                       'route' => 'admin:expenseReceipts.deleteSelected',
                                                        ])
                                            @endcomponent
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $expenseReceipts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modals')
    @include($view_path . '.column-visible')
@endsection

@section('js')
    <script type="application/javascript">
    </script>
    <script type="application/javascript" src="{{ asset('accounting-module/options-for-dt.js') }}"></script>
@endsection
