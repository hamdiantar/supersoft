@extends('admin.layouts.app')
@section('title')
    <title>{{ __('Concessions') }} </title>
@endsection
@section('content')
    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Concessions')}}</li>
            </ol>
        </nav>

        @include('admin.concessions.search_form')

        <div class="col-xs-12">
            <div class="box-content card bordered-all js__card">
                <h4 class="box-title bg-secondary with-control">
                    <i class="fa fa-file-text-o"></i> {{__('Concessions')}}
                </h4>

                <div class="card-content js__card_content" style="width:100%;margin-top: 15px">
                    <ul class="list-inline pull-left top-margin-wg">

                        <li class="list-inline-item">
                            @include('admin.buttons.add-new', [  'route' => 'admin:concessions.create',  'new' => '',])
                        </li>

                        <li class="list-inline-item">
                            @component('admin.buttons._confirm_delete_selected',['route' => 'admin:concession.deleteSelected',])
                            @endcomponent
                        </li>

                        <li class="list-inline-item">
                            @component('admin.buttons._confirm_archive_selected',[
                          'route' => 'admin:services.archiveSelected',
                           ])
                            @endcomponent
                        </li>
                        <li class="list-inline-item">
                            @include('admin.buttons._archive', [
                       'route' => 'admin:concessions.archive',
                           'new' => '',
                          ])
                        </li>

                    </ul>
                    <div class="clearfix"></div>
                    <div class="table-responsive">
                        @include('admin.concessions.options-datatable.option-row')
                        <table id="test_data" class="table table-bordered wg-table-print table-hover" style="width:100%">
                                                        @include('admin.concessions.options-datatable.table-thead')

                            <tfoot>
                            <tr>
                                <th scope="col" class="text-center column-index">#</th>
                                <th scope="col" class="text-center column-date">{!! __('Date') !!}</th>
                                <th scope="col" class="text-center column-number">{!! __('Concession number') !!}</th>
                                <th scope="col" class="text-center column-total">{!! __('Total') !!}</th>
                                <th scope="col" class="text-center column-type">{!! __('Type') !!}</th>
                                <th scope="col" class="text-center column-execution">{!! __('Item Number') !!}</th>
                                <th scope="col" class="text-center column-concession-type">{!! __('Concession Type') !!}</th>
                                <th scope="col" class="text-center column-status">{!! __('Status') !!}</th>
                                <th scope="col" class="text-center column-execution">{!! __('Execution Status') !!}</th>
                                <th class="text-center column-created-at" scope="col">{!! __('created at') !!}</th>
                                <th class="text-center column-updated-at" scope="col">{!! __('Updated at') !!}</th>
                                <th scope="col" class="text-center column-options">{!! __('Options') !!}</th>
                                <th scope="col" class="text-center column-select-all">{!! __('Select') !!}</th>
                            </tr>
                            </tfoot>
                            <tbody>

                            @if($concessions->count())

                                @foreach($concessions as $index=>$concession)
                                    <tr>

                                        <td class="text-center column-id">
                                            {{ $index + 1 }}
                                        </td>

                                        <td class="text-center column-date text-danger">{{ $concession->date }}</td>

                                        <td class="text-center column-number">
                                            {{ $concession->type == 'add' ? $concession->add_number : $concession->withdrawal_number }}
                                        </td>

                                        <td class="text-center column-total" style="background:#FBFAD4">{{$concession->total}}</td>

                                        <td class="text-center column-type">

                                        @if($concession->type == 'add' )
                                        <span class="label label-primary wg-label"> {{ __('Add Concession') }} </span>
                                        @else
                                        <span class="label label-info wg-label"> {{ __('Withdrawal Concession') }} </span>
                                        @endif
                                        </td>
                                        <td class="text-center column-date">{{ optional($concession->concessionable)->number }}</td>

                                        <td class="text-center column-concession-type" style="background:#E3EFFB !important">
                                            {{ optional($concession->concessionType)->name }}
                                        </td>

                                        <td class="text-center column-status">

                                        @if($concession->status == 'pending' )
                                        <span class="label label-info wg-label"> {{__('Pending')}}</span>
                                        @elseif($concession->status == 'accepted' )
                                        <span class="label label-success wg-label"> {{__('Accepted')}} </span>
                                        @else
                                        <span class="label label-danger wg-label"> {{__('Rejected')}} </span>
                                        @endif

                                        </td>



                                        <td class="text-center column-date">

                                        @if($concession->concessionExecution)


                                        @if($concession->concessionExecution ->status == 'pending' )
                                        <span class="label label-info wg-label"> {{__('Processing')}}</span>

                                        @elseif($concession->concessionExecution ->status == 'finished' )
                                        <span class="label label-success wg-label"> {{__('Finished')}} </span>

                                        @elseif($concession->concessionExecution ->status == 'late' )
                                        <span class="label label-danger wg-label"> {{__('Late')}} </span>
                                        @endif


                                        @else
                                        <span class="label label-warning wg-label">
                                        {{__('Not determined')}}
                                        </span>

                                        @endif

                                        </td>



                                        <td class="text-center column-created-at">{{ $concession->created_at }}</td>
                                        <td class="text-center column-created-at">{{ $concession->updated_at }}</td>
                                        <td class="text-center column-options">

                                        <div class="btn-group margin-top-10">

                                        <button type="button" class="btn btn-options dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="ico fa fa-bars"></i>
                                        {{__('Options')}} <span class="caret"></span>

                                    </button>
                                        <ul class="dropdown-menu dropdown-wg">
                                            <li>

                                            @component('admin.buttons._edit_button',[
                                                        'id'=>$concession->id,
                                                        'route' => 'admin:concessions.edit',
                                                         ])
                                            @endcomponent

                                            </li>
                                            <li class="btn-style-drop">

                                            @component('admin.buttons._delete_button',[
                                                        'id'=> $concession->id,
                                                        'route' => 'admin:concessions.destroy',
                                                         ])
                                            @endcomponent

                                            </li>

                                            <li class="btn-style-drop">
                                            @component('admin.buttons._add_to_archive',[
                                                              'id'=>$concession->id,
                                                              'route' => 'admin:concessions.archiveData',
                                                              'tooltip' => __('Delete '.$concession['name']),
                                                          ])
                                            @endcomponent
                                            </li>


                                            <li>
                                            <a style="cursor:pointer" class="btn btn-print-wg text-white  "
                                               data-toggle="modal"

                                               onclick="getPrintData({{$concession->id}})"
                                               data-target="#boostrapModal" title="{{__('print')}}">
                                                <i class="fa fa-print"></i> {{__('Print')}}
                                            </a>
                                            </li>


                                            <li>
                                            @include('admin.partial.execution_period', ['id'=> $concession->id])
                                            </li>


                                            <li>
                                            @include('admin.partial.upload_library.btn_upload', ['id'=> $concession->id])

                                            </li>

                                        </ul>
                                    </div>
<!--
                                            @component('admin.buttons._edit_button',[
                                                        'id'=>$concession->id,
                                                        'route' => 'admin:concessions.edit',
                                                         ])
                                            @endcomponent

                                            @component('admin.buttons._delete_button',[
                                                        'id'=> $concession->id,
                                                        'route' => 'admin:concessions.destroy',
                                                         ])
                                            @endcomponent
                                            @component('admin.buttons._add_to_archive',[
                                                              'id'=>$concession->id,
                                                              'route' => 'admin:concessions.archiveData',
                                                              'tooltip' => __('Delete '.$concession['name']),
                                                          ])
                                            @endcomponent

                                            <a style="cursor:pointer" class="btn btn-print-wg text-white  "
                                               data-toggle="modal"

                                               onclick="getPrintData({{$concession->id}})"
                                               data-target="#boostrapModal" title="{{__('print')}}">
                                                <i class="fa fa-print"></i> {{__('Print')}}
                                            </a>

                                            @include('admin.partial.execution_period', ['id'=> $concession->id])
                                            @include('admin.partial.upload_library.btn_upload', ['id'=> $concession->id]) -->

                                        </td>
                                        <td>
                                            @component('admin.buttons._delete_selected',[
                                                       'id' => $concession->id,
                                                        'route' => 'admin:concessions.deleteSelected',
                                                        ])
                                            @endcomponent
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="10"><span>{{__('NO DATA FOUND')}}</span></td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                        {{ $concessions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modals')
    @include('admin.concessions.options-datatable.column-visible')
    @include('admin.partial.execution_period_form', ['items'=> $concessions, 'url'=> route('admin:concessions.execution.save'), 'title' => __('Concession Execution') ])

    @include('admin.partial.upload_library.form', ['url'=> route('admin:concession.upload_library')])

    <div class="modal fade" id="boostrapModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span>
                    </button>
                    <!-- <h4 class="modal-title" id="myModalLabel-1">{{__('Concession')}}</h4> -->
                </div>

                <div class="modal-body" id="invoiceDatatoPrint">


                </div>
                <div class="modal-footer" style="text-align:center">

                    <button type="button" class="btn btn-primary waves-effect waves-light"
                            onclick="printDownPayment()" id="print_sales_invoice">
                        <i class='fa fa-print'></i>
                        {{__('Print')}}
                    </button>

                    <button type="button" class="btn btn-danger waves-effect waves-light"
                            data-dismiss="modal"><i class='fa fa-close'></i>
                        {{__('Close')}}</button>

                </div>

            </div>
        </div>
    </div>

@endsection

@section('js-validation')

    {!! JsValidator::formRequest('App\Http\Requests\Admin\ConcessionExecution\CreateRequest', '.form'); !!}

    <script type="application/javascript">

        function printDownPayment() {
            var element_id = 'concession_to_print', page_title = document.title
            print_element(element_id, page_title)
        }

        function getPrintData(id) {
            $.ajax({
                url: "{{ route('admin:concessions.show') }}?concession_id=" + id,
                method: 'GET',
                success: function (data) {

                    $("#invoiceDatatoPrint").html(data.view)
                }
            });
        }

        function getLibraryFiles(id) {

            $("#library_item_id").val(id);

            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            $.ajax({

                type: 'post',
                url: '{{route('admin:concession.library.get.files')}}',
                data: {
                    _token: CSRF_TOKEN,
                    id: id,
                },

                beforeSend: function () {
                    // $("#loader_get_test_video").show();
                },

                success: function (data) {

                    $("#files_area").html(data.view);
                },

                error: function (jqXhr, json, errorThrown) {
                    // $("#loader_save_goals").hide();
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
                        url: '{{route('admin:concession.library.file.delete')}}',
                        data: {
                            _token: CSRF_TOKEN,
                            id: id,
                        },

                        beforeSend: function () {
                            // $("#loader_get_test_video").show();
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
                url: "{{route('admin:concession.upload_library')}}",
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

        function getConcessionItems() {

            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            let concession_type_id = $('#concession_type_id').find(":selected").val();

            $.ajax({

                type: 'post',
                url: '{{route('admin:concessions.get.items.index.search')}}',
                data: {
                    _token: CSRF_TOKEN,
                    concession_type_id: concession_type_id,
                },

                success: function (data) {

                    $("#concession_items").html(data.view);
                    $("#model_name").val(data.model);
                    $('.js-example-basic-single').select2();
                },

                error: function (jqXhr, json, errorThrown) {
                    // $("#loader_save_goals").hide();
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        }

        function showSelectedTypes (type) {

            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $('.remove_items').remove();

            $.ajax({

                type: 'post',
                url: '{{route('admin:concessions.get.types.index.search')}}',
                data: {
                    _token: CSRF_TOKEN,
                    type:type,
                },

                success: function (data) {

                    $("#concession_types").html(data.view);

                    $('.remove_concession_for_new').remove();

                    let option = new Option();
                    option.text = '{{__('Select')}}';
                    option.value = '';

                    $(".concessions_numbers").append(option);

                    $.each(data.concessions, function (key, modelName) {

                        var option = new Option(modelName, modelName);
                        option.text = modelName.number;
                        option.value = modelName.id;

                        $(".concessions_numbers").append(option);

                        $('.concessions_numbers option').addClass(function () {
                            return 'remove_concession_for_new';
                        });
                    });


                    $('.js-example-basic-single').select2();
                },

                error: function (jqXhr, json, errorThrown) {
                    // $("#loader_save_goals").hide();
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        }

    </script>

@endsection

@section('js')
    <script type="application/javascript" src="{{ asset('accounting-module/options-for-dt.js') }}"></script>
@endsection
