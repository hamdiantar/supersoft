@extends('admin.layouts.app')
@section('title')
    <title>{{ __('Purchase Requests') }} </title>
@endsection
@section('content')
    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Purchase Requests')}}</li>
            </ol>
        </nav>

        {{--        @include('admin.damaged_stock.search_form')--}}

        <div class="col-xs-12">
            <div class="box-content card bordered-all js__card">
                <h4 class="box-title bg-secondary with-control">
                    <i class="fa fa-file-text-o"></i> {{__('Purchase Requests')}}
                </h4>

                <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">

                        <li class="list-inline-item">
                            @include('admin.buttons.add-new', [  'route' => 'admin:purchase-requests.create',  'new' => '',])
                        </li>

                        {{--                        <li class="list-inline-item">--}}
                        {{--                            @component('admin.buttons._confirm_delete_selected',['route' => 'admin:damaged-stock.create.deleteSelected',])--}}
                        {{--                            @endcomponent--}}
                        {{--                        </li>--}}

                    </ul>

                    <div class="clearfix"></div>

                    <div class="table-responsive">
                        <table id="cities" class="table table-bordered wg-table-print table-hover" style="width:100%">
                            <thead>
                            <tr>
                                <th scope="col">{!! __('#') !!}</th>
                                @if(authIsSuperAdmin())
                                    <th scope="col">{!! __('Branch') !!}</th>
                                @endif
                                <th scope="col">{!! __('Date') !!}</th>
                                <th scope="col">{!! __('Purchase request Number') !!}</th>
                               
                                <th scope="col">{!! __('Status') !!}</th>
                                <th scope="col">{!! __('Execution Status') !!}</th>
                                <th scope="col">{!! __('Created Date') !!}</th>
                                <th scope="col">{!! __('Updated Date') !!}</th>
                                <th scope="col">{!! __('Options') !!}</th>

                                <th scope="col">
                                    <div class="checkbox danger">
                                        <input type="checkbox" id="select-all">
                                        <label for="select-all"></label>
                                    </div>{!! __('Select') !!}
                                </th>

                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th scope="col">{!! __('#') !!}</th>
                                @if(authIsSuperAdmin())
                                    <th scope="col">{!! __('Branch') !!}</th>
                                @endif
                                <th scope="col">{!! __('Date') !!}</th>
                                <th scope="col">{!! __('Purchase request Number') !!}</th>
                           
                                <th scope="col">{!! __('Status') !!}</th>
                                <th scope="col">{!! __('Execution Status') !!}</th>
                                <th scope="col">{!! __('Created Date') !!}</th>
                                <th scope="col">{!! __('Updated Date') !!}</th>
                                <th scope="col">{!! __('Options') !!}</th>
                                <th scope="col">{!! __('Select') !!}</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($data as $index => $item)
                                <tr>
                                    <td>{!! $index +1 !!}</td>
                                    @if(authIsSuperAdmin())
                                        <td class="text-danger">{!! optional($item->branch)->name !!}</td>
                                    @endif
                                    <td class="text-danger">{{ $item->date }}</td>
                                    <td>{{ $item->number }}</td>
                                   
                                    <td>

                                       @if($item->status == 'under_processing' )
                                        <span class="label label-info wg-label"> {{__('Under Processing')}}</span>
                                        @elseif($item->status == 'ready_for_approval' )
                                        <span class="label label-primary wg-label"> {{__('Ready For Approval')}} </span>
                                        @elseif($item->status == 'accept_approval' )
                                        <span class="label label-success wg-label"> {{__('Accept Approval')}} </span>
                                        @else
                                        <span class="label label-danger wg-label"> {{__('Reject Approval')}} </span>
                                        @endif

                                    </td>
                                    <td class="text-center column-date">

@if($item->execution)


@if($item->execution ->status == 'pending' )
<span class="label label-info wg-label"> {{__('Processing')}}</span>

@elseif($item->execution ->status == 'finished' )
<span class="label label-success wg-label"> {{__('Finished')}} </span>

@elseif($item->execution ->status == 'late' )
<span class="label label-danger wg-label"> {{__('Late')}} </span>
@endif


@else
<span class="label label-warning wg-label">
                                        {{__('Not determined')}}
                                        </span>

@endif

</td>
                                    <td>{{ $item->created_at }}</td>
                                    <td>{{ $item->updated_at }}</td>
                                    <td>

                                    <div class="btn-group margin-top-10">
                                        
                                        <button type="button" class="btn btn-options dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="ico fa fa-bars"></i>
                                        {{__('Options')}} <span class="caret"></span>
                                     
                                    </button> 
                                        <ul class="dropdown-menu dropdown-wg">
                                            <li>
                                            <a style="cursor:pointer" class="btn btn-print-wg text-white  "
                                           data-toggle="modal"
                                           onclick="getPrintData({{$item->id}})"
                                           data-target="#boostrapModal" title="{{__('print')}}">
                                            <i class="fa fa-print"></i> {{__('Print')}}
                                        </a>

                                            </li>
                                            <li>
                                                
                                            @component('admin.buttons._edit_button',[
                                                    'id'=>$item->id,
                                                    'route' => 'admin:purchase-requests.edit',
                                                     ])
                                        @endcomponent
                                            </li>

                                            <li class="btn-style-drop">
                                            @component('admin.buttons._delete_button',[
                                                    'id'=> $item->id,
                                                    'route' => 'admin:purchase-requests.destroy',
                                                     ])
                                        @endcomponent
                                            </li>

                                            @if($item->status == 'ready_for_approval')
                                            <li>
                                            
                                      

<a href="{{route('admin:purchase-requests.edit', ['id'=> $item->id, 'request_type'=>'approval'])}}"
   class="btn btn-approval-wg text-white hvr-radial-out">
    <i class="fa fa-check"></i>
    {{__('Approval')}}
</a>

                                            </li>
                                            @endif

                                            
                                            <li>
                                            @include('admin.partial.execution_period', ['id'=> $item->id])
                                            </li>

                                            <li>
                                            @include('admin.partial.upload_library.btn_upload', ['id'=> $item->id])
                                            </li>

                                        </ul>
                                    </div>

                                        <!-- <a style="cursor:pointer" class="btn btn-print-wg text-white  "
                                           data-toggle="modal"
                                           onclick="getPrintData({{$item->id}})"
                                           data-target="#boostrapModal" title="{{__('print')}}">
                                            <i class="fa fa-print"></i> {{__('Print')}}
                                        </a>

                                        @component('admin.buttons._edit_button',[
                                                    'id'=>$item->id,
                                                    'route' => 'admin:purchase-requests.edit',
                                                     ])
                                        @endcomponent

                                        @component('admin.buttons._delete_button',[
                                                    'id'=> $item->id,
                                                    'route' => 'admin:purchase-requests.destroy',
                                                     ])
                                        @endcomponent



                                        @if($item->status == 'ready_for_approval')

                                            <a href="{{route('admin:purchase-requests.edit', ['id'=> $item->id, 'request_type'=>'approval'])}}"
                                               class="btn btn-approval-wg text-white hvr-radial-out">
                                                <i class="fa fa-check"></i>
                                                {{__('Approval')}}
                                            </a>
                                        @endif

                                        @include('admin.partial.execution_period', ['id'=> $item->id])
                                        @include('admin.partial.upload_library.btn_upload', ['id'=> $item->id]) -->

                                    </td>
                                    <td>
                                        {{--                                        @component('admin.buttons._delete_selected',[--}}
                                        {{--                                                   'id' => $type->id,--}}
                                        {{--                                                    'route' => 'admin:concession-types.deleteSelected',--}}
                                        {{--                                                    ])--}}
                                        {{--                                        @endcomponent--}}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modals')

    @include('admin.partial.execution_period_form', [
    'items'=> $data, 'url'=> route('admin:purchase.requests.execution.save'), 'title' => __('Purchase Requests Execution') ])

    @include('admin.partial.upload_library.form', ['url'=> route('admin:purchase.requests.upload_library')])

    @include('admin.partial.print_modal', ['title'=> __('Purchase Requests')])

@endsection

@section('js-validation')

    {!! JsValidator::formRequest('App\Http\Requests\Admin\PurchaseRequestExecution\CreateRequest', '.form'); !!}

    <script type="application/javascript">

        function printDownPayment() {
            var element_id = 'concession_to_print', page_title = document.title
            print_element(element_id, page_title)
        }

        function getPrintData(id) {

            $.ajax({
                url: "{{ route('admin:purchase.requests.print') }}?purchase_request_id=" + id,
                method: 'GET',
                success: function (data) {

                    $("#data_to_print").html(data.view)
                }
            });
        }

        function getLibraryFiles(id) {

            $("#library_item_id").val(id);

            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            $.ajax({

                type: 'post',
                url: '{{route('admin:purchase.requests.library.get.files')}}',
                data: {
                    _token: CSRF_TOKEN,
                    id: id,
                },

                success: function (data) {

                    $("#files_area").html(data.view);
                },

                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });

        }

        function removeFile(id) {

            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            swal({

                title: "Delete File",
                text: "Are you sure want to delete this file ?",
                icon: "warning",
                buttons: true,
                dangerMode: true,

            }).then((willDelete) => {

                if (willDelete) {

                    $.ajax({

                        type: 'post',
                        url: '{{route('admin:purchase.requests.library.file.delete')}}',
                        data: {
                            _token: CSRF_TOKEN,
                            id: id,
                        },

                        success: function (data) {

                            $("#file_" + data.id).remove();
                            swal({text: 'file deleted successfully', icon: "success"});
                        },

                        error: function (jqXhr, json, errorThrown) {
                            // $("#loader_save_goals").hide();
                            var errors = jqXhr.responseJSON;
                            swal({text: errors, icon: "error"})
                        }
                    });
                }
            });

        }

        function uploadFiles() {

            var form_data = new FormData();

            var item_id = $("#library_item_id").val();

            var totalfiles = document.getElementById('files').files.length;

            for (var index = 0; index < totalfiles; index++) {
                form_data.append("files[]", document.getElementById('files').files[index]);
            }

            form_data.append("item_id", item_id);

            $.ajax({
                url: "{{route('admin:purchase.requests.upload_library')}}",
                type: "post",

                headers: {
                    "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
                },
                data: form_data,
                dataType: 'json',
                contentType: false,
                processData: false,

                beforeSend: function () {
                    $('#upload_loader').show();
                },

                success: function (data) {

                    $('#upload_loader').hide();

                    swal("{{__('Success')}}", data.message, "success");

                    $("#files_area").prepend(data.view);

                    $("#files").val('');

                    $("#no_files").remove();

                },
                error: function (jqXhr, json, errorThrown) {

                    $('#upload_loader').hide();
                    var errors = jqXhr.responseJSON;
                    swal("{{__('Sorry')}}", errors, "error");
                },
            });
        }

    </script>

    {{--    <script type="application/javascript" src="{{ asset('accounting-module/options-for-dt.js') }}"></script>--}}
@endsection

@section('js')
    <script type="application/javascript">
        invoke_datatable($('#cities'))
    </script>
@endsection
