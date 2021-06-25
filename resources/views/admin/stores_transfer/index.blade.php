@extends('admin.layouts.app')

@section('title')
    <title>{{ __('words.stores-transfers') }} </title>
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('accounting-module/daily-restriction.css') }}"/>
@endsection

@section('content')
    <div class="row small-spacing">
        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{ route('admin:home') }}"> {{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item active"> {{ __('words.stores-transfers') }}</li>
            </ol>
        </nav>

        @include('admin.stores_transfer.search_form')
        <div class="col-xs-12">
            <div class="box-content card bordered-all js__card">
                <h4 class="box-title bg-secondary with-control">
                    <i class="fa fa-home"></i>  {{ __('words.stores-transfers') }}
                </h4>

                <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">
                        <li class="list-inline-item">
                            @include('admin.buttons.add-new', [
                                'route' => 'admin:stores-transfers.create',
                                'new' => '',
                            ])
                        </li>
                        <li class="list-inline-item">
                            @component(
                                'admin.buttons._confirm_delete_selected', ['route' => 'admin:stores-transfers.deleteSelected']
                            )
                            @endcomponent
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                    @php
                        $view_path = 'admin.stores_transfer.options-datatable';
                    @endphp

                    @include($view_path . '.option-row')

                    <div class="clearfix"></div>
                  <div class="table-responsive">
                    <table id="stores" class="table table-bordered wg-table-print table-hover" style="width:100%;margin-top:15px">
                        @include($view_path . '.table-thead')
                        <tfoot>
                        <tr>
                        <th class="text-center column-index" scope="col">#</th>
                            @if(authIsSuperAdmin())
                                <th scope="col" class="text-center column-branch">{!! __('Branch') !!}</th>
                            @endif
                        <th class="text-center column-transfer-date" scope="col">{!! __('words.transfer-date') !!}</th>

                            <th class="text-center column-transfer-number" scope="col">{{ __('opening-balance.serial-number') }}</th>

                            <th class="text-center column-store-from" scope="col">{!! __('words.store-from') !!}</th>
                            <th class="text-center column-store-to" scope="col">{!! __('words.store-to') !!}</th>
                            <th class="text-center column-total" scope="col">{!! __('Total') !!}</th>
                            <th class="text-center column-status" scope="col">{!! __('Concession Status') !!}</th>
                            <th class="text-center column-created-at" scope="col">{!! __('Created At') !!}</th>
                            <th class="text-center column-updated-at" scope="col">{!! __('Updated At') !!}</th>
                            <th scope="col">{!! __('Options') !!}</th>
                            <th scope="col">{!! __('Select') !!}</th>
                        </tr>
                        </tfoot>
                        <tbody>
                        @foreach($collection as $index => $store_transfer)
                            <tr>
                            <td class="text-center column-index">{{ $index+1 }}</td>
                                @if(authIsSuperAdmin())
                                    <td class="text-danger column-branch">{!! optional($store_transfer->branch)->name !!}</td>
                                @endif
                            <td class="text-center column-transfer-date text-danger">{!! $store_transfer->transfer_date !!}</td>



                                <td class="text-center column-transfer-number">{!! $store_transfer->transfer_number !!}</td>
                                <td class="text-center column-store-from">
                                <span class="label wg-label" style="background:#7165DA !important">
                                    {!! $lang == 'ar' ? optional($store_transfer->store_from)->name_ar : optional($store_transfer->store_from)->name_en !!}
                                    </span>
                                </td>
                                <td class="text-center column-store-to">
                                <span class="label wg-label" style="background:#7165DA !important">

                                {!! $lang == 'ar' ? optional($store_transfer->store_to)->name_ar : optional($store_transfer->store_to)->name_en !!}


                                 </span>

                                </td>
                                <td class="text-center column-total" style="background:#FBFAD4 !important">
                                    {!! $store_transfer->total !!}
                                </td>
                                <td class="text-center column-status" > 
                                @if( $store_transfer->concession )                 

@if( $store_transfer->concession->status == 'pending' )
<span class="label label-info wg-label"> {{__('Pending')}}</span>
@elseif( $store_transfer->concession->status == 'accepted' )
<span class="label label-success wg-label"> {{__('Accepted')}} </span>
@elseif( $store_transfer->concession->status == 'rejected' )
<span class="label label-danger wg-label"> {{__('Rejected')}} </span>
@endif

@else
<span class="label label-warning wg-label">  {{__('Not determined')}} </span>
@endif

                                                                <td class="text-center column-created-at">{!! optional($store_transfer->created_at)->format('y-m-d h:i:s A') !!}</td>
                                <td class="text-center column-updated-at">{!! optional($store_transfer->updated_at)->format('y-m-d h:i:s A') !!}</td>
                                <td>

                                <div class="btn-group margin-top-10">

                                        <button type="button" class="btn btn-options dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="ico fa fa-bars"></i>
                                        {{__('Options')}} <span class="caret"></span>

                                    </button>
                                        <ul class="dropdown-menu dropdown-wg">
                                            <li>
                                            @component('admin.buttons._show_button',[
                                         'id' => $store_transfer->id,
                                         'route'=>'admin:stores-transfers.show'
                                          ])
                                        @endcomponent

                                            </li>
                                            <li>
                                            @component('admin.buttons._edit_button',[
                                        'id' => $store_transfer->id,
                                        'route' => 'admin:stores-transfers.edit',
                                    ])
                                    @endcomponent

                                            </li>

                                            <li class="btn-style-drop">
                                  @component('admin.buttons._delete_button',[
                                        'id' => $store_transfer->id,
                                        'route' => 'admin:stores-transfers.destroy',
                                    ])
                                    @endcomponent
                                            </li>

                                        </ul>
                                    </div>
<!--
                                @component('admin.buttons._show_button',[
                                         'id' => $store_transfer->id,
                                         'route'=>'admin:stores-transfers.show'
                                          ])
                                        @endcomponent
                                    @component('admin.buttons._edit_button',[
                                        'id' => $store_transfer->id,
                                        'route' => 'admin:stores-transfers.edit',
                                    ])
                                    @endcomponent
                                    @component('admin.buttons._delete_button',[
                                        'id' => $store_transfer->id,
                                        'route' => 'admin:stores-transfers.destroy',
                                    ])
                                    @endcomponent

 -->

                                </td>
                                <td>
                                    @component('admin.buttons._delete_selected',[
                                        'id' => $store_transfer->id,
                                        'route' => 'admin:stores-transfers.deleteSelected',
                                    ])
                                    @endcomponent
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table></div>
                    {{ $collection->links() }}
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
                    <h4 class="modal-title" id="myModalLabel-1">{{__('words.stores-transfers')}}</h4>
                </div>

                <div class="modal-body" id="store-transfer-print">
                </div>
                <div class="modal-footer" style="text-align:center">

                    <button type="button" class="btn btn-primary waves-effect waves-light"
                            onclick="printStoreTransfer()">
                        <i class='fa fa-print'></i>
                        {{__('Print')}}
                    </button>

                    <button type="button" class="btn btn-danger waves-effect waves-light"
                            data-dismiss="modal"> <i class='fa fa-close'></i>
                        {{__('Close')}}</button>

                </div>

            </div>
        </div>
    </div>
    @include($view_path . '.column-visible')
@endsection

@section('js')
    <script type="application/javascript" src="{{ asset('accounting-module/options-for-dt.js') }}"></script>
    <script type="application/javascript">
        function printStoreTransfer() {
            var element_id = 'store-transfer-content' ,page_title = document.title
            print_element(element_id ,page_title)
        }

        function getPrintData(url) {
            $("#store-transfer-print").html('{{ __('words.data-loading') }}')
            $.ajax({
                url: url,
                method: 'GET',
                success: function (response) {
                    $("#store-transfer-print").html(response.code)
                }
            });
        }
    </script>
    @include('opening-balance.common-script')
@endsection
