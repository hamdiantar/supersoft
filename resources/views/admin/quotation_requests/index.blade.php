@extends('admin.layouts.app')


@section('title')
    <title>{{ __('Super Car') }} - {{ __('Quotations Requests') }} </title>
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('accounting-module/daily-restriction.css') }}"/>
@endsection

@section('content')
    <div class="row small-spacing">

    <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active">  {{__('Quotations Requests')}}</li>
            </ol>
        </nav>

        <div class="col-xs-12">

        <div class="box-content card bordered-all js__card">
                <h4 class="box-title bg-secondary with-control">
                <i class="fa fa-file-o"></i>  {{__('Quotations Requests')}}
                 </h4>

                 <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">
                        <!-- <li class="list-inline-item">
                            @include('admin.buttons.add-new', [
                           'route' => 'admin:quotations.create',
                           'new' => 'Quotation',
                          ])
                        </li> -->

                        @can('delete_quotations')
                            <li class="list-inline-item">
                                @component('admin.buttons._confirm_delete_selected',[
                              'route' => 'admin:quotations.deleteSelected',
                               ])
                                @endcomponent
                            </li>
                        @endcan
                    </ul>
                    <form id="filtration-form">
                        <input type="hidden" name="rows" value="{{ isset($_GET['rows']) ? $_GET['rows'] : '' }}"/>
                        <input type="hidden" name="key" value="{{ isset($_GET['key']) ? $_GET['key'] : '' }}"/>
                        <input type="hidden" name="sort_method" value="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : '' }}"/>
                        <input type="hidden" name="sort_by" value="{{ isset($_GET['sort_by']) ? $_GET['sort_by'] : '' }}"/>
                        <input type="hidden" name="invoker"/>
                    </form>

                    <div class="clearfix"></div>
                    <div class="table-responsive">
                        @php
                            $view_path = 'admin.quotation_requests.options-datatable';
                        @endphp
                        @include($view_path . '.option-row')
                        <div class="clearfix"></div>
                        <table id="quotations" class="table table-bordered" style="width:100%;margin-top:15px">
                            @include($view_path . '.table-thead')
                            <tfoot>
                            <tr>
                                <th class="text-center column-quotation-number" scope="col">{!! __('Number') !!}</th>
                                <th class="text-center column-customer-name" scope="col">{!! __('Customer') !!}</th>
                                <th class="text-center column-customer-phone" scope="col">{!! __('Customer Phone') !!}</th>
                                <!-- <th scope="col">{!! __('User') !!}</th> -->
                                <th class="text-center column-created-at" scope="col">{!! __('created at') !!}</th>
                                <th class="text-center column-updated-at" scope="col">{!! __('Updated at') !!}</th>
                                <th scope="col">{!! __('Options') !!}</th>
                                <th scope="col">{!! __('Select') !!}</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($quotations as $quotation)
                                <tr>
                                    <td class="text-center column-quotation-number">{{$quotation->quo_number}}</td>
                                    <td class="text-center column-customer-name">{{optional($quotation->customer)->name}}</td>
                                    <td class="text-center column-customer-phoner">{{optional($quotation->customer)->phone1}}</td>
                                    <td class="text-center column-created-at">{{$quotation->created_at->format('Y-m-d')}}</td>
                                    <td class="text-center column-updated-at">{{$quotation->updated_at->format('Y-m-d')}}</td>
                                    <td>


                                        @if($quotation->status == 'pending')
                                            <a class="btn btn-wg-edit hvr-radial-out text-white"
                                               onclick="accept({{$quotation->id}})"
                                               href="#"><i class="fa fa-check"></i> {{__('Accept')}}
                                            </a>

                                            <a class="btn btn-wg-delete hvr-radial-out text-white" onclick="reject({{$quotation->id}})"
                                               data-toggle="modal" data-target="#boostrapModal-2"
                                               href="#"><i class="fa fa-times"></i> {{__('Reject')}}
                                            </a>
                                        @endif

                                        @component('admin.buttons._delete_button',[
                                                    'id'=>$quotation->id,
                                                    'route' => 'admin:quotations.destroy',
                                                    'tooltip' => __('Delete '.$quotation['quotation_number']),
                                                     ])
                                        @endcomponent
                                        @component('admin.quotations.parts.print',[
                                             'id'=> $quotation->id,
                                             'quotation'=> $quotation,
                                            ])
                                        @endcomponent
                                    </td>
                                    <td>
                                    @component('admin.buttons._delete_selected',[
                                                  'id' => $quotation->id,
                                                   'route' => 'admin:quotations.deleteSelected',
                                                   ])
                                        @endcomponent
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $quotations->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form id="accept_request_form" action="{{route('admin:quotations.requests.accept')}}" method="post">
        @csrf
        <input type="hidden" name="quotation_id" value="" id="request_id">
    </form>

@endsection

@section('modals')

    <div class="modal fade" id="boostrapModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel-1">{{__('Quotation')}}</h4>
                </div>

                <div class="modal-body" id="quotation">
                </div>
                <div class="modal-footer" style="text-align:center">

                <button type="button" class="btn btn-primary waves-effect waves-light"
                            onclick="printDownPayment()">
                            <i class='fa fa-print'></i>
                        {{__('Print')}}
                    </button>

                    <button type="button" class="btn btn-danger waves-effect waves-light"
                            data-dismiss="modal">
                            <i class='fa fa-close'></i>
                    {{__('Close')}}</button>

                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="boostrapModal-2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel-1">{{__('Reject Data')}}</h4>
                </div>

                <form action="{{route('admin:quotations.requests.reject')}}" method="post">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" value="" id="rejected_request_id" name="rejected_quotation_id">

                        <div class="form-group">
                            <label>{{__('Reject Reason')}}</label>
                            <textarea name="reject_reason" class="form-control" id="reject_reason"></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-sm waves-effect waves-light">
                            {{__('Save')}}
                        </button>

                        <button type="button" class="btn btn-danger btn-sm waves-effect waves-light" data-dismiss="modal">
                            {{__('Close')}}
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    @include($view_path . '.column-visible')

@endsection


@section('js')
    <script type="application/javascript">

        @if(request()->query('print_type'))

        $( document ).ready(function() {

            var id = '{{request()->query('quotation')}}'

            getPrintData(id);

            $('#boostrapModal').modal('show');
        });

        @endif

        // invoke_datatable($('#quotations'))

        function printDownPayment() {
            var element_id = 'quotation', page_title = document.title
            print_element(element_id, page_title)
        }

        function getPrintData(id) {
            $.ajax({
                url: "{{ route('admin:quotations.show') }}?quotationId=" + id,
                method: 'GET',
                success: function (data) {
                    $("#quotation").html(data.quotation)
                    let total = $("#totalInLetters").text()
                    $("#totalInLetters").html( new Tafgeet(total, '{{env('DEFAULT_CURRENCY')}}').parse())
                }
            });
        }


        function accept(request_id) {
            event.preventDefault();
            swal({
                title: "{{__('Quotation Request')}}",
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
