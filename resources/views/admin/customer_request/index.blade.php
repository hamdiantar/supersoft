@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('customers Requests') }} </title>
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('accounting-module/daily-restriction.css') }}"/>
@endsection

@section('content')
    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"> {{__('customers requests')}}</li>
            </ol>
        </nav>


        <div class="col-xs-12">
            <div class="box-content card bordered-all js__card">
                <h4 class="box-title bg-secondary with-control">
                <i class="fa fa-car"></i>  {{__('customers requests')}}
                 </h4>

                <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">

                        <li class="list-inline-item">
                            @component('admin.buttons._confirm_delete_selected',[
                               'route' => 'admin:customers.requests.delete.selected',
                                ])
                            @endcomponent
                        </li>

                    </ul>
                    <form style="display: nonde" id="filtration-form">
                        <input type="hidden" name="rows" value="{{ isset($_GET['rows']) ? $_GET['rows'] : '' }}"/>
                        <input type="hidden" name="key" value="{{ isset($_GET['key']) ? $_GET['key'] : '' }}"/>
                        <input type="hidden" name="sort_method" value="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : '' }}"/>
                        <input type="hidden" name="sort_by" value="{{ isset($_GET['sort_by']) ? $_GET['sort_by'] : '' }}"/>
                        <input type="hidden" name="invoker"/>
                    </form>
                    <div class="clearfix"></div>
                    <div class="table-responsive">
                        @php
                            $view_path = 'admin.customer_request.options-datatable';
                        @endphp
                        @include($view_path . '.option-row')
                        <div class="clearfix"></div>
                        <table id="customers" class="table table-bordered" style="width:100%;margin-top:15px">
                            @include($view_path . '.table-thead')
                            <tfoot>
                            <tr>
                                <th class="text-center column-name" scope="col">{!! __('Name') !!}</th>
                                <th class="text-center column-phone" scope="col">{!! __('Phone') !!}</th>
                                <th class="text-center column-username" scope="col">{!! __('username') !!}</th>
                                <th class="text-center column-status" scope="col">{!! __('Status') !!}</th>
                                <th class="text-center column-created-at" scope="col">{!! __('Created At') !!}</th>
                                <th class="text-center column-updated-at" scope="col">{!! __('Updated At') !!}</th>
                                <th scope="col">{!! __('Options') !!}</th>
                                <th scope="col">
                                    <div class="checkbox danger">
                                        <input type="checkbox" id="select-all">
                                        <label for="select-all"></label>
                                    </div>
                                    {!! __('Select') !!}
                                </th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($customerRequests as $customerRequest)
                                <tr>
                                    <td class="text-center column-name">{!! $customerRequest->name !!}</td>
                                    <td class="text-center column-phone">{!! $customerRequest->phone !!}</td>
                                    <td class="text-center column-username">{!! $customerRequest->username !!}</td>
                                    <td class="text-center column-status">{!! $customerRequest->status !!}</td>
                                    <td class="text-center column-created-at">
                                        {!! optional($customerRequest->created_at)->format('y-m-d h:i:s A') !!}
                                    </td>
                                    <td class="text-center column-updated-at">
                                        {!! optional($customerRequest->updated_at)->format('y-m-d h:i:s A') !!}
                                    </td>

                                    <td>

                                        @if($customerRequest->status == 'pending')
                                            <a class="btn btn-wg-edit hvr-radial-out text-white"
                                               onclick="accept({{$customerRequest->id}})"
                                               href="#"><i class="fa fa-check"></i> {{__('Accept')}}
                                            </a>

                                            <a class="btn btn-wg-delete hvr-radial-out text-white" onclick="reject({{$customerRequest->id}})"
                                               data-toggle="modal" data-target="#boostrapModal-2"
                                               href="#"><i class="fa fa-times"></i> {{__('Reject')}}
                                            </a>
                                        @endif

                                        @component('admin.buttons._delete_button',[
                                                     'id'=>$customerRequest->id,
                                                     'route' => 'admin:customers.requests.destroy',
                                                     'tooltip' => __('Delete '.$customerRequest['name']),
                                                      ])
                                        @endcomponent

                                    </td>

                                    <td>
                                        @component('admin.buttons._delete_selected',[
                                                'id' => $customerRequest->id,
                                                'route' => 'admin:customers.requests.delete.selected',
                                                 ])
                                        @endcomponent
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <form id="accept_request_form" action="{{route('admin:customers.requests.accept')}}" method="post">
                            @csrf
                            <input type="hidden" name="request_id" value="" id="request_id">
                        </form>

                        {{ $customerRequests->links() }}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modals')
    <div class="modal fade" id="boostrapModal-2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel-1">{{__('Reject Data')}}</h4>
                </div>

                <form action="{{route('admin:customers.requests.reject')}}" method="post">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" value="" id="rejected_request_id" name="rejected_request_id">

                        <div class="form-group">
                            <label>{{__('Reject Reason')}}</label>
                            <textarea name="reject_reason" class="form-control" id="reject_reason"></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-default btn-sm waves-effect waves-light">
                            {{__('Save')}}
                        </button>

                        <button type="button" class="btn btn-default btn-sm waves-effect waves-light" data-dismiss="modal">
                            {{__('Close')}}
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection

@section('accounting-module-modal-area')
    @include($view_path . '.column-visible')
@endsection

@section('js')

    <script type="application/javascript">
        // invoke_datatable($('#customers'))


        function accept(request_id) {
            event.preventDefault();
            swal({
                title: "{{__('Customer Request')}}",
                text: "{{__('Are you sure want to Accept this request ?')}}",
                type: "success",
                buttons: {
                    confirm: {
                        text: "{{__('Ok')}}",
                    },
                    cancel: {
                        text: "{{__('Cancel')}}",
                        visible: true,
                    }
                }
            }).then(function (isConfirm) {

                if (isConfirm) {

                    $("#request_id").val(request_id);

                    document.getElementById("accept_request_form").submit();
                }
            });
        }

        function reject(request_id) {
            event.preventDefault();

            $("#reject_reason").val('');
            $("#rejected_request_id").val(request_id);
        }

    </script>

    <script type="application/javascript" src="{{ asset('accounting-module/options-for-dt.js') }}"></script>
@endsection
