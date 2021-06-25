@extends('admin.layouts.app')

@section('title')
    <title>{{ __('opening-balance.index-title') }} </title>
@endsection

@section('accounting-module-modal-area')
    @include('admin.opening_balance.optional-datatable.column-visible')
@endsection

@section('content')
    <nav>
        <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
            <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item active"> {{ __('opening-balance.index-title') }} </li>
        </ol>
    </nav>

    @include('admin.opening_balance.search')

    <div class="col-md-12">
        <div class="box-content card bordered-all primary">
            <h4 class="box-title bg-primary"><i class="ico fa fa-gears"></i>
                {{ __('opening-balance.index-title') }}
            </h4>

            <div class="card-content js__card_content" style="">
                <ul class="list-inline pull-left top-margin-wg">

                    <li class="list-inline-item">
                        @include('admin.buttons.add-new', [
                            'route' => 'admin:opening-balance.create',
                            'new' => '',
                        ])
                    </li>

                    <li class="list-inline-item">
                        @component('admin.buttons._confirm_delete_selected',[
                            'route' => 'admin:opening-balance.deleteSelected',
                        ])
                        @endcomponent
                    </li>
                </ul>


                <div class="clearfix"></div>
                <div class="card-content">
                    <div class="table-responsive index-table">
                        @include('admin.opening_balance.optional-datatable.option-row')
                        <table class="table table-responsive table-bordered wg-table-print table-hover"
                               id='opening-balance-table'>
                            @include('admin.opening_balance.optional-datatable.table-thead')
                            <tbody>
                            @foreach($collection as $index=>$c)
                                <tr>
                                    <td class="column-id"> {{ $index+1 }} </td>
                                    @if(authIsSuperAdmin())
                                        <td class="text-danger column-name"> {{ optional($c->branch)->name }} </td>
                                    @endif
                                    <td class="text-danger column-operation_date"> {{ $c->operation_date }} </td>
                                    <td class="column-serial_number"> {{ $c->serial_number }} </td>
                                    <td  class="column-total_money" style="background:#FBFAD4 !important"> {{ $c->total_money }} </td>

                                    <td class="column-status">
                                    @if( $c->concession )                 

@if( $c->concession->status == 'pending' )
<span class="label label-info wg-label"> {{__('Pending')}}</span>
@elseif( $c->concession->status == 'accepted' )
<span class="label label-success wg-label"> {{__('Accepted')}} </span>
@elseif( $c->concession->status == 'rejected' )
<span class="label label-danger wg-label"> {{__('Rejected')}} </span>
@endif

@else
<span class="label label-warning wg-label">  {{__('Not determined')}} </span>
@endif

                                    
                                    </td>

                                    <td class="column-created_at"> {{ $c->created_at }} </td>
                                    <td class="column-updated_at"> {{ $c->updated_at }} </td>
                                    <td>
                                        <div class="btn-group margin-top-10">
                                            <button type="button" class="btn btn-options dropdown-toggle"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="ico fa fa-bars"></i>
                                                {{__('Options')}} <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu dropdown-wg">
                                                <li>
                                                    @component('admin.buttons._show_button',[
                                                               'id' => $c->id,
                                                               'route'=>'admin:opening-balance.show'
                                                                ])
                                                    @endcomponent

                                                </li>
                                                <li>

                                                    <a class="btn btn-wg-edit hvr-radial-out"
                                                       href="{{ route('admin:opening-balance.edit', $c->id) }}">
                                                        <i class="fa fa-edit"></i>
                                                        {{__('Edit')}}
                                                    </a>

                                                </li>

                                                <li class="btn-style-drop">
                                                    @component('admin.buttons._delete_button',[
                                                   'id'=> $c->id,
                                                   'route' => 'admin:opening-balance.destroy',
                                                    ])
                                                    @endcomponent
                                                </li>

                                            </ul>
                                        </div>

{{--                                   @component('admin.buttons._show_button',[--}}
{{--                                                       'id' => $c->id,--}}
{{--                                                       'route'=>'admin:opening-balance.show'--}}
{{--                                                        ])--}}
{{--                                    @endcomponent--}}



                                    <td>
                                        @component('admin.buttons._delete_selected',[
                                            'id' => $c->id,
                                            'route' => 'opening-balance.deleteSelected',
                                        ])
                                        @endcomponent
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th class="text-center column-id">#</th>
                                @if(authIsSuperAdmin())
                                    <th class="text-center column-name"> {{ __('opening-balance.branch') }} </th>
                                @endif
                                <th class="text-center column-operation_date"> {{ __('opening-balance.operation-date') }} </th>
                                <th class="text-center column-serial_number"> {{ __('opening-balance.serial-number') }} </th>
                                <th class="text-center column-total_money"> {{ __('opening-balance.total') }} </th>
                                <th class="text-center column-status"> {{ __('opening-balance.status') }} </th>
                                <th class="text-center column-created_at"> {{ __('created at') }} </th>
                                <th class="text-center column-updated_at"> {{ __('Updated at') }} </th>
                                <th class="text-center"> {{ __('Options') }} </th>
                                <th scope="col">
                                    <div class="checkbox danger">
                                        <input type="checkbox" id="select-all">
                                        <label for="select-all"></label>
                                    </div>
                                    {!! __('Select') !!}
                                </th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('accounting-scripts')
    <script type="application/javascript" src="{{ asset('js/sweet-alert.js') }}"></script>

    <script type="application/javascript">
        function alert(message) {
            swal({
                title: "{{ __('accounting-module.warning') }}",
                text: message,
                icon: "warning",
                reverseButtons: false,
                buttons: {
                    cancel: {
                        text: "{{ __('words.ok') }}",
                        className: "btn btn-primary",
                        value: null,
                        visible: true
                    }
                }
            })
        }
    </script>

    <script type="application/javascript">
        function delete_confirm(url) {
            swal({
                title: "{{ __('accounting-module.warning') }}",
                text: "{{ __('opening-balance.are-you-sure-to-deleted') }}",
                icon: "warning",
                reverseButtons: false,
                buttons: {
                    confirm: {
                        text: "{{ __('words.yes_delete') }}",
                        className: "btn btn-default",
                        value: true,
                        visible: true
                    },
                    cancel: {
                        text: "{{ __('words.no') }}",
                        className: "btn btn-default",
                        value: null,
                        visible: true
                    }
                }
            }).then(function (confirm_delete) {
                if (confirm_delete) {
                    window.location = url
                } else {
                    alert("{{ __('opening-balance.balance-not-deleted') }}")
                }
            })
        }
    </script>
    @include('opening-balance.common-script')
    <script type="application/javascript" src="{{ asset('accounting-module/options-for-dt.js') }}"></script>
@endsection
