@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{__('words.locker-receives')}} </title>
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('accounting-module/daily-restriction.css') }}"/>
@endsection

@section('content')
    <div class="row small-spacing">
        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item">  {{__('words.locker-receives')}}</li>
            </ol>
        </nav>

        @if(filterSetting())
            <div class="col-xs-12">
                <div class="box-content card bordered-all js__card top-search">
                    <h4 class="box-title with-control">
                        <i class="fa fa-search"></i>{{__('Search filters')}}
                        <span class="controls">
                            <button type="button" class="control fa fa-minus js__card_minus"></button>
                            <button type="button" class="control fa fa-times js__card_remove"></button>
                        </span>
                    </h4>
                    <div class="card-content js__card_content">
                        {!! $search_form !!}
                    </div>
                </div>
            </div>
        @endif
        
        <div class="col-xs-12">
            <div class="box-content card bordered-all js__card">
                <h4 class="box-title bg-secondary with-control">
                    <i class="fa fa-money"></i>   {{__('Lockers Transfer')}}
                </h4>
                <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">
                        <li class="list-inline-item">
                            @include('admin.buttons.add-new', [
                                'route' => 'admin:locker-receives.create',
                                'new' => '',
                            ])
                        </li>
                        <li class="list-inline-item">
                            @component('admin.buttons._confirm_delete_selected',[
                                'route' => 'admin:locker-receives.delete_selected',
                            ])
                            @endcomponent
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                    <div class="table-responsive">
                        @php
                            $view_path = 'admin.money-permissions.locker-receive.options-datatable';
                        @endphp
                        @include($view_path . '.option-row')
                        <div class="clearfix"></div>
                        <table id="currencies" class="table table-bordered" style="width:100%;margin-top:15px">
                            @include($view_path . '.table-thead')
                            <tfoot>
                            <tr>
                                <th class="text-center column-permission-number" scope="col">{!! __('words.permission-number') !!}</th>
                                <th class="text-center column-exchange-number" scope="col">{!! __('words.exchange-number') !!}</th>
                                <th class="text-center column-source-type" scope="col">{!! __('words.source-type') !!}</th>
                                <th class="text-center column-money-receiver" scope="col">{!! __('words.money-receiver') !!}</th>
                                <th class="text-center column-amount" scope="col">{!! __('the Amount') !!}</th>
                                <th class="text-center column-operation-date" scope="col">{!! __('words.operation-date') !!}</th>
                                <th class="text-center column-status" scope="col">{!! __('words.permission-status') !!}</th>
                                <th class="text-center column-created-at" scope="col">{!! __('created at') !!}</th>
                                <th class="text-center column-updated-at" scope="col">{!! __('Updated at') !!}</th>
                                <th scope="col">{!! __('Options') !!}</th>
                                <th scope="col">{!! __('Select') !!}</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($receives as $exchange)
                                <tr>
                                    <td class="text-center column-permission-number">{!! $exchange->permission_number !!}</td>
                                    <td class="text-center column-exchange-number">
                                        @if($exchange->source_type == 'locker')
                                            {!! optional($exchange->exchange_permission)->permission_number !!}
                                        @else
                                            {!! optional($exchange->bank_exchange_permission)->permission_number !!}
                                        @endif
                                    </td>
                                    <td class="text-center column-source-type"> {{ __('words.'.$exchange->source_type) }} </td>
                                    <td class="text-center column-money-receiver">{!! optional($exchange->employee)->name !!}</td>
                                    <td class="text-danger text-center column-amount">{!! $exchange->amount !!}</td>
                                    <td class="text-center column-operation-date">{!! $exchange->operation_date !!}</td>
                                    <td class="text-center column-status">
                                        {!! $exchange->render_status($exchange->status ,__('words.'.$exchange->status)) !!}
                                    </td>
                                    <td class="text-center column-created-at">
                                        {!! optional($exchange->created_at)->format('y-m-d h:i:s A') !!}
                                    </td>
                                    <td class="text-center column-updated-at">
                                        {!! optional($exchange->updated_at)->format('y-m-d h:i:s A') !!}
                                    </td>
                                    <td>
                                        @if($exchange->status == 'pending')
                                            @component('admin.buttons._edit_button',[
                                                'id' => $exchange->id,
                                                'route' => 'admin:locker-receives.edit',
                                            ])
                                            @endcomponent

                                            @component('admin.buttons._delete_button',[
                                                'id' => $exchange->id,
                                                'route' => 'admin:locker-receives.destroy',
                                                'tooltip' => __('Delete '.$exchange->permission_number),
                                            ])
                                            @endcomponent
                                        @endif
                                        <a class="btn btn-info"
                                            onclick="load_money_permission_model('{{ route('admin:locker-receives.show' ,['id' => $exchange->id]) }}')">
                                            <i class="fa fa-eye"></i> {{ __('Show') }}
                                        </a>
                                    </td>
                                    <td>
                                        @if($exchange->status == 'pending')
                                            @component('admin.buttons._delete_selected',[
                                                'id' => $exchange->id,
                                                'route' => 'admin:locker-receives.delete_selected',
                                            ])
                                            @endcomponent
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $receives->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('accounting-module-modal-area')
    @include('admin.money-permissions.print-modal')
    @include($view_path . '.column-visible')
@endsection

@section('js')
    <script type="application/javascript">
        function load_money_permission_model(data_url) {
            var modal = $('[data-remodal-id=money-permission-modal]').remodal();
            $("[data-remodal-id=money-permission-modal]").find('div.remodal-content').text("{{ __('words.data-loading') }}")
            modal.open()

            $.ajax({
                dataType: 'json',
                type: 'GET',
                url: data_url,
                success: function (response) {
                    $("[data-remodal-id=money-permission-modal]").find('div.remodal-content').html(response.code)
                },
                error: function (err) {
                    console.log(err)
                    // alert(err.responseJSON.message)
                }
            })
        }

        function printExchange(id) {
            var element_id = 'printThis' + id ,page_title = document.title
            print_element(element_id ,page_title)
            return true
        }
    </script>
    <script type="application/javascript" src="{{ asset('accounting-module/options-for-dt.js') }}"></script>

@endsection
