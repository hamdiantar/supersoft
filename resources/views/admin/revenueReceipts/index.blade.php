@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('Revenues Receipts') }} </title>
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('accounting-module/daily-restriction.css') }}"/>
@endsection

@section('content')
    <div class="row small-spacing">
    <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Revenues Receipts')}}</li>
            </ol>
        </nav>

        @include('admin.revenueReceipts.parts.search')

        <div class="col-xs-12">
        <div class="box-content card bordered-all js__card">
                <h4 class="box-title bg-secondary with-control">
                <i class="fa fa-money"></i>  {{__('Revenues Receipts')}}
                 </h4>

                 <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">
                       <li class="list-inline-item">
                       @include('admin.buttons.add-new', [
                  'route' => 'admin:revenueReceipts.create',
                      'new' => '',
                     ])
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
                        @php
                            $view_path = 'admin.revenueReceipts.options-datatable';
                        @endphp
                        @include($view_path . '.option-row')
                        <div class="clearfix"></div>
                <table id="revenueReceipts" class="table table-bordered" style="width:100%;margin-top:15px">
                    @include($view_path . '.table-thead')
                    <tfoot>
                    <tr>
                        <th class="text-center column-revenue-no" scope="col">{!! __('Revenue No') !!}</th>
                        <th class="text-center column-receiver" scope="col">{!! __('Receiver') !!}</th>
                        <th class="text-center column-revenue-item" scope="col">{!! __('Revenue Item') !!}</th>
                        <th class="text-center column-cost" scope="col">{!! __('Cost') !!}</th>
                        <th class="text-center column-deportation-method" scope="col">{!! __('Deportation Method') !!}</th>
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
                    @foreach($revenueReceipts as $index=>$revenue)
                        <tr>
                            <td class="text-center column-revenue-no">{!! $revenue->revenue_number !!}</td>
                            <td class="text-center column-receiver">{!! $revenue->receiver !!}</td>
                            <td class="text-center column-revenue-item">
                            <span class="label label-primary wg-label">
                                {!! optional($revenue->revenueItem)->item !!}
                                </span>
                            </td>
                            <td class="text-danger text-center column-cost">{!! $revenue->cost !!}</td>
                            <!-- <td>{!! $revenue->date !!}</td> -->
                            <td class="text-center column-deportation-method">
                                    <span class="label label-primary wg-label">
                                         {{ __("words.".$revenue->deportation) }}
                                     </span>
                            </td>

                            <td class="text-center column-deportation">
                                 <span class="label label-danger wg-label">
                                        @if($revenue->deportation == 'safe' && $revenue->locker)
                                         {{ $revenue->locker->name }}
                                     @endif
                                     @if($revenue->deportation == 'bank' && $revenue->bank)
                                         {{ $revenue->bank->name }}
                                     @endif
                                        </span>
                            </td>

                            <td class="text-center column-payment-type">
                                    <span class="label label-primary wg-label">
                                         {{ __("words.".$revenue->payment_type) }}
                                     </span>
                            </td>
                            <td class="text-center column-created-at">{!! $revenue->created_at->format('y-m-d h:i:s A') !!}</td>
                            <td class="text-center column-updated-at">{!! $revenue->updated_at->format('y-m-d h:i:s A')!!}</td>
                            <td class="text-center column-options">

                            @component('admin.revenueReceipts.parts.show', [
                                    'id' => $revenue->id,
                                    'revenue' => $revenue,
                            ])
                                    @endcomponent

                                @if($revenue->uneditable())
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
                                @endif

                            </td>
                            <td class="text-center column-select">
                                @if($revenue->uneditable())
                                    @component('admin.buttons._delete_selected',[
                                        'id' => $revenue->id,
                                        'route' => 'admin:revenueReceipts.deleteSelected',
                                    ])
                                    @endcomponent
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{ $revenueReceipts->links() }}
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
        // invoke_datatable($('#revenueReceipts'))
    </script>
    <script type="application/javascript" src="{{ asset('accounting-module/options-for-dt.js') }}"></script>
@endsection
