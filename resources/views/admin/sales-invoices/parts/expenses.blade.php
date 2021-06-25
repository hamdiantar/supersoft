@extends('admin.layouts.app')
@section('content')
    <div class="row small-spacing">
        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a
                        href="{{route('admin:purchase-invoices.index')}}"> {{__('Purchase Invoices')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Expenses')}}</li>
            </ol>
        </nav>
        @include('admin.purchase-invoices.parts.header')
    </div>
    <div class="col-xs-12">
        <div class="box-content card bordered-all success">
            <h1 class="box-title bg-info"><i class="fa fa-check-square-o"></i>{{__('Expenses Purchase Invoices')}}</h1>
            <div class="box-content">

                <ul class="list-inline pull-left">
                    <li class="list-inline-item">
                        @if ($invoice->type === "credit" &&  $remaining > 0)
                            <a style="margin-bottom: 12px; border-radius: 5px"
                               type="button"
                               href="{{url('admin/expenseReceipts/create?invoice_id='.request()->segment(5))}}"
                               class="btn btn-icon btn-icon-left btn-primary waves-effect waves-light">
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
                            <th scope="col">{!! __('Receiver') !!}</th>
                            <th scope="col">{!! __('Expense Item') !!}</th>
                            <th scope="col">{!! __('Cost') !!}</th>
                            <th scope="col">{!! __('Date') !!}</th>
                            <th scope="col">{!! __('Created at') !!}</th>
                            <th scope="col">{!! __('Updated at') !!}</th>
                            <th scope="col">
                                <div class="checkbox danger">
                                    <input type="checkbox" id="select-all">
                                    <label for="select-all"></label>
                                </div>{!! __('Options') !!}
                            </th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th scope="col">{!! __('Expense No') !!}</th>
                            <th scope="col">{!! __('Receiver') !!}</th>
                            <th scope="col">{!! __('Expense Item') !!}</th>
                            <th scope="col">{!! __('Cost') !!}</th>
                            <th scope="col">{!! __('Date') !!}</th>
                            <th scope="col">{!! __('Created at') !!}</th>
                            <th scope="col">{!! __('Updated at') !!}</th>
                            <th scope="col">{!! __('Options') !!}</th>
                        </tr>
                        </tfoot>
                        <tbody>
                        @foreach($expenses as $index=>$expense)
                            <tr>
                                <td>{!! $expense->id !!}</td>
                                <td>{!! $expense->receiver !!}</td>
                                <td>{!! optional($expense->expenseItem)->item !!}</td>
                                <td>{!! $expense->cost !!}</td>
                                <td>{!! $expense->date !!}</td>
                                <td>{!! $expense->created_at->format('y-m-d h:i:s A') !!}</td>
                                <td>{!! $expense->updated_at->format('y-m-d h:i:s A')!!}</td>
                                <td>
                                    @component('admin.buttons._delete_selected',[
                                           'id' => $expense->id,
                                            ])
                                    @endcomponent

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
                                    @component('admin.expenseReceipts.parts.show', [
                                                'id' => $expense->id,
                                                'expense' => $expense,
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
