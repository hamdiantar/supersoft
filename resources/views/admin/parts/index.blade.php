@extends('admin.layouts.app')
@section('title')
    <title>{{ __('Parts') }} </title>
@endsection

@section('style')
    <link rel="stylesheet" href="{{asset('assets/plugin/iCheck/skins/square/blue.css')}}">
    <link rel="stylesheet" href="{{ asset('accounting-module/daily-restriction.css') }}"/>
    <link href="{{ asset('css/datatable-print-styles.css') }}" rel='stylesheet'/>
@endsection
@section('content')

    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Parts management')}}</li>
            </ol>
        </nav>

        @if(filterSetting())
           @include('admin.parts.search')
        @endif


        <div class="col-xs-12">
            <div class="box-content card bordered-all js__card">
                <h4 class="box-title bg-secondary with-control">
                    <i class="fa fa-gears"></i> {{__('Parts management')}}
                </h4>

                <div class="card-content js__card_content">
                    <ul class="list-inline pull-left top-margin-wg">
                        <li class="list-inline-item">
                            @include('admin.buttons.add-new', [
                       'route' => 'admin:parts.create',
                           'new' => '',
                          ])
                        </li>

                        <li class="list-inline-item">
                            @component('admin.buttons._confirm_delete_selected',[
                          'route' => 'admin:parts.deleteSelected',
                           ])
                            @endcomponent
                        </li>

                    </ul>
                    <div class="clearfix"></div>
                    <div class="table-responsive table-for-font-size">
                        @php
                            $view_path = 'admin.parts.options-datatable';
                        @endphp
                        @include($view_path . '.option-row')
                        <div class="clearfix"></div>
                        <table id="currencies" class="table table-bordered table-hover" style="width:100%;margin-top: 15px">
                            @include($view_path . '.table-thead')
                            <tfoot>
                            <tr>
                                <th class="text-center column-id" scope="col">{!! __('#') !!}</th>
                                <th class="text-center column-name" scope="col">{!! __('Name') !!}</th>
                                <th class="text-center column-type" scope="col">{!! __('Type') !!}</th>
                                <th class="text-center column-quantity" scope="col">{!! __('Quantity') !!}</th>
                                <th class="text-center column-status" scope="col">{!! __('Status') !!}</th>
                                <th class="text-center column-reviewable" scope="col">{!! __('Reviewable') !!}</th>
                                <th class="text-center column-taxable" scope="col">{!! __('taxable') !!}</th>
                                <th class="text-center column-created-at" scope="col">{!! __('created at') !!}</th>
                                <th class="text-center column-updated-at" scope="col">{!! __('Updated at') !!}</th>
                                <th scope="col">{!! __('Options') !!}</th>
                                <th scope="col">{!! __('Select') !!}</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($parts as $index=>$part)
                                <tr>

                                    <td class="text-center column-id">{!! $index +1 !!}</td>
                                    <td class="text-center column-name">
                                    @include('admin.parts.alternative_parts')
                                    </td>
                                    <td class="text-center column-type">
                                        <span class="text-danger">
                                            {{$part->spareParts->first() ? $part->spareParts->first()->type : '---'}}
                                        </span>
                                    </td>

                                    <td class="text-center column-quantity">

                                        <a data-toggle="modal" data-target="#part_quantity_{{$part->id}}"
                                           title="Part quantit" class="btn btn-info">
                                            {!! $part->quantity !!}
                                        </a>

                                    </td>


                                        <td class="text-center column-status">
                                        @if($part->status == 1 )
                                            <span class="label label-success wg-label"> {{ __('Active') }} </span>
                                        @else
                                        <span class="label label-danger wg-label"> {{ __('inActive') }} </span>
                                        @endif
                                        </td>


                                    <td class="text-center column-reviewable">
                                    @if($part->reviewable == 1 )
                                            <span class="label label-success wg-label"> {{ __('Reviewed') }} </span>
                                        @else
                                        <span class="label label-danger wg-label"> {{ __('Not reviewed') }} </span>
                                        @endif
                                    </td>

                                    <td class="text-center column-taxable">
                                    @if($part->taxable == 1 )
                                            <span class="label label-success wg-label"> {{ __('Active') }} </span>
                                        @else
                                        <span class="label label-danger wg-label"> {{ __('inActive') }} </span>
                                        @endif
                                    </td>

                                    <td class="text-center column-created-at">{!! $part->created_at->format('y-m-d h:i:s A') !!}</td>
                                    <td class="text-center column-updated-at">{!! $part->updated_at->format('y-m-d h:i:s A') !!}</td>


                                    <td>

                                    <div class="btn-group margin-top-10">
                                        
                                        <button type="button" class="btn btn-options dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="ico fa fa-bars"></i>
                                        {{__('Options')}} <span class="caret"></span>
                                     
                                    </button> 
                                        <ul class="dropdown-menu dropdown-wg">
                                            <li>
                                            
                                            @component('admin.buttons._show_button',[
                                                       'id' => $part->id,
                                                       'route'=>'admin:parts.show'
                                                        ])
                                        @endcomponent

                
                                            </li>
                                            <li>
                                                

                                            @component('admin.buttons._edit_button',[
                                                    'id' => $part->id,
                                                    'route'=>'admin:parts.edit'
                                                     ])
                                        @endcomponent
                
                                            </li>

                                            <li class="btn-style-drop">
                                            @component('admin.buttons._delete_button',[
                                                    'id'=>$part->id,
                                                    'route' => 'admin:parts.destroy',
                                                    'tooltip' => __('Delete '.$part['name']),
                                                     ])
                                        @endcomponent
                                            </li>

                                            <li>
                                            <a data-toggle="modal" data-target="#boostrapModal-2"
                                           onclick="getLibraryPartId('{{$part->id}}')"
                                           title="Part Library" class="btn btn-warning" style="margin-bottom:5px">
                                            <i class="fa fa-plus"> </i> {{__('Library')}}
                                        </a>

                                            </li>

                                            
                                            <li>
                                            <a data-toggle="modal" data-target="#part_taxes_{{$part->id}}"
                                           onclick="partTaxable('{{$part->id}}')"
                                           title="Part taxes" class="btn btn-info">
                                            <i class="fa fa-money"> </i> {{__('Taxes')}}
                                        </a>

                                            </li>

                                        </ul>
                                    </div>

                                        <!-- @component('admin.buttons._show_button',[
                                                       'id' => $part->id,
                                                       'route'=>'admin:parts.show'
                                                        ])
                                        @endcomponent

                                        @component('admin.buttons._edit_button',[
                                                    'id' => $part->id,
                                                    'route'=>'admin:parts.edit'
                                                     ])
                                        @endcomponent

                                        @component('admin.buttons._delete_button',[
                                                    'id'=>$part->id,
                                                    'route' => 'admin:parts.destroy',
                                                    'tooltip' => __('Delete '.$part['name']),
                                                     ])
                                        @endcomponent

                                        <a data-toggle="modal" data-target="#boostrapModal-2"
                                           onclick="getLibraryPartId('{{$part->id}}')"
                                           title="Part Library" class="btn btn-warning" style="margin-bottom:5px">
                                            <i class="fa fa-plus"> </i> {{__('Library')}}
                                        </a>

                                        <a data-toggle="modal" data-target="#part_taxes_{{$part->id}}"
                                           onclick="partTaxable('{{$part->id}}')"
                                           title="Part taxes" class="btn btn-info">
                                            <i class="fa fa-money"> </i> {{__('Taxes')}}
                                        </a> -->

                                    </td>
                                    <td>
                                        @component('admin.buttons._delete_selected',[
                                                         'id' => $part->id,
                                                          'route' => 'admin:parts.deleteSelected',
                                                          ])
                                        @endcomponent
                                    </td>

                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $parts->links() }}
                    </div>
                </div>

                <input type="hidden" id="part_id" >

            </div>
        </div>
    </div>

@endsection

@section('accounting-module-modal-area')
    @include($view_path . '.column-visible')
@endsection

@section('modals')

    @include('admin.parts.quantity_modal')
    @include('admin.parts.taxes_modal')

    <div class="modal fade modal-bg-wg" id="boostrapModal-2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel-1">{{__('Part Library')}}</h4>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <form action="{{route('admin:suppliers.upload.upload_library')}}" method="post"
                              enctype="multipart/form-data">
                            @csrf

                            <div class="form-group col-md-10">
                                <label>{{__('files')}}</label>
                                <input type="file" name="files[]" class="form-control" id="files" multiple>
                                <input type="hidden" name="part_id" value="" id="library_part_id">
                            </div>

                            <div class="form-group col-md-1">
                                <button type="button" class="btn btn-primary" onclick="uploadPartFiles()"
                                        style="margin-top: 28px;">{{__('save')}}</button>
                            </div>

                            <div class="form-group col-md-1" id="upload_loader" style="display: none;">
                                <img src="{{asset('default-images/loading.gif')}}" title="loader"
                                     style="width: 34px;height: 39px;margin-top: 27px;">
                            </div>
                        </form>
                    </div>

                    <hr>

                    <div id="part_files_area" class="row" style="text-align: center">


                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm waves-effect waves-light" data-dismiss="modal">
                        {{__('Close')}}
                    </button>
                </div>

            </div>
        </div>
    </div>

@endsection

@section('js')

    <script type="application/javascript">

        $("#selectAll").click(function () {

            $(".to_checked").prop("checked", $(this).prop("checked"));
        });

        $(".to_checked").click(function () {
            if (!$(this).prop("checked")) {
                $("#selectAll").prop("checked", false);
            }
        });

        function getLibraryPartId(id) {

            $("#library_part_id").val(id);

            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            $.ajax({

                type: 'post',
                url: '{{route('admin:parts.upload_library')}}',
                data: {
                    _token: CSRF_TOKEN,
                    id: id,
                },

                beforeSend: function () {
                    // $("#loader_get_test_video").show();
                },

                success: function (data) {

                    $("#part_files_area").html(data.view);
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
                        url: '{{route('admin:parts.upload_library.file.delete')}}',
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

        function uploadPartFiles() {

            var form_data = new FormData();

            var part_id = $("#library_part_id").val();

            var totalfiles = document.getElementById('files').files.length;

            for (var index = 0; index < totalfiles; index++) {
                form_data.append("files[]", document.getElementById('files').files[index]);
            }

            form_data.append("part_id", part_id);

            $.ajax({
                url: "{{route('admin:parts.upload.upload_library')}}",
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

                    $("#part_files_area").prepend(data.view);

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

        function partTaxable (part_id) {

            if ($('#taxable-' + part_id).is(':checked')) {

                $('.part_taxable').prop('disabled', false);

            }else {

                $('.part_taxable').prop('disabled', true);
            }
        }


    </script>

    <script type="application/javascript">
        // invoke_datatable($('#currencies'))
    </script>

    <script type="application/javascript" src="{{ asset('accounting-module/options-for-dt.js') }}"></script>
    @include('opening-balance.common-script')
@endsection
