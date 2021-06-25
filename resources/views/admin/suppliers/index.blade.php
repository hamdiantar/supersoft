@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('Suppliers') }} </title>
@endsection

@section('style')
    <link rel="stylesheet" href="{{asset('assets/plugin/iCheck/skins/square/blue.css')}}">
    <link rel="stylesheet" href="{{ asset('accounting-module/daily-restriction.css') }}"/>
@endsection
@section('content')
    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Suppliers')}}</li>
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
                        <!-- /.controls -->
                    </h4>
                    <!-- /.box-title -->
                    <div class="card-content js__card_content">
                        <form id="filtration-form" action="{{route('admin:suppliers.index')}}" method="get">
                            <input type="hidden" name="rows" value="{{ isset($_GET['rows']) ? $_GET['rows'] : '' }}"/>
                            <input type="hidden" name="key" value="{{ isset($_GET['key']) ? $_GET['key'] : '' }}"/>
                            <input type="hidden" name="sort_method"
                                   value="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : '' }}"/>
                            <input type="hidden" name="sort_by"
                                   value="{{ isset($_GET['sort_by']) ? $_GET['sort_by'] : '' }}"/>
                            <input type="hidden" name="invoker"/>

                            <div class="list-inline margin-bottom-0 row">

                                @if(authIsSuperAdmin())
                                    <div class="form-group col-md-12">
                                        <label> {{ __('Branch') }} </label>
                                        <select name="branch_id" class="form-control js-example-basic-single">
                                            <option value="">{{__('Select Branch')}}</option>
                                            @foreach($branches as $k=>$v)
                                                <option value="{{$k}}">{{$v}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                                <div class="form-group col-md-4">
                                    <label> {{ __('Supplier Name') }} </label>
                                    <select name="name" class="form-control js-example-basic-single">
                                        <option value="">{{__('Select Name')}}</option>
                                        @foreach($suppliers_search as $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label> {{ __('Supplier Group Name') }} </label>
                                    <select name="group_id[]" class="form-control js-example-basic-single" multiple>
                                        {!! loadAllSuppliersTypesAsTree() !!}
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label> {{ __('Country') }} </label>
                                    <select name="country_id" class="form-control js-example-basic-single">
                                        <option value="">{{__('Select Country')}}</option>
                                        @foreach($countries as $k=>$v)
                                            <option value="{{$k}}">{{$v}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label> {{ __('Supplier Type') }} </label>
                                    <select name="type" class="form-control js-example-basic-single">
                                        <option value="">{{__('Select Type')}}</option>
                                        <option value="person">{{__('Person')}}</option>
                                        <option value="company">{{__('Company')}}</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label> {{ __('Phone') }} </label>
                                    <select name="phone_1" class="form-control js-example-basic-single">
                                        <option value="">{{__('Select Phone')}}</option>
                                        @foreach($suppliers_search as $item)
                                            @if($item->phone_1 != null)
                                                <option value="{{$item->phone_1}}">{{$item->phone_1}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label> {{ __('Commercial Register') }} </label>
                                    <select name="commercial_number" class="form-control js-example-basic-single">
                                        <option value="">{{__('Select commercial number')}}</option>
                                        @foreach($suppliers_search as $item)
                                            @if($item->commercial_number)
                                                <option
                                                    value="{{$item->commercial_number}}">{{$item->commercial_number}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                <div class="switch primary col-md-6">
                                    <ul class="list-inline">
                                        <li>
                                            <input type="checkbox" id="switch-2" name="active">
                                            <label for="switch-2">{{__('Active')}}</label>
                                        </li>
                                        <li>
                                            <input type="checkbox" id="switch-3" name="inactive">
                                            <label for="switch-3">{{__('inActive')}}</label>
                                        </li>
                                    </ul>
                                </div>

                                    <div class="radio primary col-md-6" style="margin-top: 40px !important;">
                                        <ul class="list-inline">
                                            <li>
                                                <input type="radio" id="supplier_type1" name="supplier_type"
                                                       value="supplier">
                                                <label for="supplier_type1">{{__('Supplier')}}</label>
                                            </li>
                                            <li>
                                                <input type="radio" id="supplier_type2" name="supplier_type"
                                                       value="contractor">
                                                <label for="supplier_type2">{{__('contractor')}}</label>
                                            </li>
                                            <li>
                                                <input type="radio" id="supplier_type3" name="supplier_type"
                                                       value="both_together">
                                                <label for="supplier_type3">{{__('Both Together')}}</label>
                                            </li>

                                            <li>
                                                <input type="radio" id="supplier_type4" name="supplier_type"
                                                       value="all">
                                                <label for="supplier_type4">{{__('All')}}</label>
                                            </li>
                                        </ul>
                                    </div>

                            </div>

                            <button type="submit"
                                    class="btn sr4-wg-btn   waves-effect waves-light hvr-rectangle-out"><i
                                    class=" fa fa-search "></i> {{__('Search')}} </button>
                            <a href="{{route('admin:suppliers.index')}}"
                               class="btn bc-wg-btn   waves-effect waves-light hvr-rectangle-out"><i
                                    class=" fa fa-reply"></i> {{__('Back')}}
                            </a>

                        </form>
                    </div>
                    <!-- /.card-content -->
                </div>
                <!-- /.box-content -->
            </div>
        @endif

        <div class="col-xs-12">
            <div class="box-content card bordered-all js__card">
                <h4 class="box-title bg-secondary with-control">
                    <i class="fa fa-check-square-o"></i> {{__('Suppliers')}}
                </h4>

                <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">
                        <li class="list-inline-item">
                            @include('admin.buttons.add-new', [
                       'route' => 'admin:suppliers.create',
                           'new' => '',
                          ])

                        </li>

                        <li class="list-inline-item">

                            @component('admin.buttons._confirm_delete_selected',[
                          'route' => 'admin:suppliers.deleteSelected',
                           ])
                            @endcomponent
                        </li>

                    </ul>
                    <div class="clearfix"></div>
                    <div class="table-responsive">
                        @php
                            $view_path = 'admin.suppliers.options-datatable';
                        @endphp
                        @include($view_path . '.option-row')
                        <div class="clearfix"></div>
                        <table id="currencies" class="table table-bordered" style="width:100%;margin-top:15px">
                            @include($view_path . '.table-thead')
                            <tfoot>
                            <tr>
                                <th class="text-center column-id" scope="col">#</th>
                                <th class="text-center column-supplier-name"
                                    scope="col">{!! __('Supplier Name') !!}</th>
                                <th class="text-center column-supplier-group"
                                    scope="col">{!! __('Supplier Type') !!}</th>
                                <th class="text-center column-supplier-type"
                                    scope="col">{!! __('Supplier Type') !!}</th>
                                <th class="text-center column-funds-for" scope="col">{!! __('Funds For') !!}</th>
                                <th class="text-center column-funds-on" scope="col">{!! __('Funds On') !!}</th>
                                <th class="text-center column-status" scope="col">{!! __('Status') !!}</th>
                                <th class="text-center column-created-at" scope="col">{!! __('created at') !!}</th>
                                <th class="text-center column-updated-at" scope="col">{!! __('Updated at') !!}</th>
                                <th scope="col">{{__('Options')}}</th>
                                <th scope="col">{{__('Select')}}</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($suppliers as $index=>$supplier)
                                @php
                                    $supplier_balance = $supplier->direct_balance();
                                @endphp
                                <tr>
                                    <td class="text-center column-id">{!! $index +1 !!}</td>
                                    <td class="text-center column-supplier-name">{!! $supplier->name !!}</td>
                                    <td class="text-center column-supplier-group">
                                        @if($supplier->supplier_type)
                                            <span
                                                class="btn btn-wg-show hvr-radial-out">{{$supplier->supplier_type}}</span>
                                        @endif
                                    </td>
                                    <td class="text-center column-supplier-type">
                                        @if($supplier->type == 'person')
                                            {{__('Person')}}
                                        @else
                                            {{__('Company')}}
                                        @endif
                                    </td>
                                    <td class="text-danger text-center  column-funds-for">{{ $supplier_balance['debit'] }}</td>
                                    <td class="text-danger text-center  column-funds-on">{{ $supplier_balance['credit'] }}</td>
                                    @if ($supplier->status)
                                        <td class="text-center column-status">
                                            <div class="switch success">
                                                <input
                                                    disabled
                                                    type="checkbox"
                                                    {{$supplier->status == 1 ? 'checked' : ''}}
                                                    id="switch-{{ $supplier->id }}">
                                                <label for="supplier-{{ $supplier->id }}"></label>
                                            </div>

                                        </td>
                                    @else
                                        <td class="text-center column-status">
                                            <div class="switch success">
                                                <input
                                                    disabled
                                                    type="checkbox"
                                                    {{$supplier->status == 1 ? 'checked' : ''}}
                                                    id="switch-{{ $supplier->id }}">
                                                <label for="supplier-{{ $supplier->id }}"></label>
                                            </div>

                                        </td>
                                    @endif

                                    <td class="text-center column-created-at">{!! $supplier->created_at->format('y-m-d h:i:s A') !!}</td>
                                    <td class="text-center column-updated-at">{!! $supplier->updated_at->format('y-m-d h:i:s A') !!}</td>

                                    <td>


                                        @component('admin.buttons._show_button',[
                                                       'id' => $supplier->id,
                                                       'route'=>'admin:suppliers.show'
                                                        ])
                                        @endcomponent

                                        @component('admin.buttons._edit_button',[
                                                    'id' => $supplier->id,
                                                    'route'=>'admin:suppliers.edit'
                                                     ])
                                        @endcomponent

                                        @component('admin.buttons._delete_button',[
                                                    'id'=>$supplier->id,
                                                    'route' => 'admin:suppliers.destroy',
                                                    'tooltip' => __('Delete '.$supplier['name']),
                                                     ])
                                        @endcomponent

                                        <a data-toggle="modal" data-target="#boostrapModal-2"
                                           onclick="getLibrarySupplierId('{{$supplier->id}}')"
                                           title="Supplier Library" class="btn btn-warning">
                                            <i class="fa fa-plus"> </i> {{__('Library')}}
                                        </a>
                                    </td>
                                    <td>
                                        @component('admin.buttons._delete_selected',[
                                                'id' => $supplier->id,
                                                'route' => 'admin:suppliers.deleteSelected',
                                                 ])
                                        @endcomponent
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $suppliers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('accounting-module-modal-area')
    @include($view_path . '.column-visible')
@endsection

@section('modals')

    <div class="modal fade" id="boostrapModal-2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel-1">{{__('Supplier Library')}}</h4>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <form action="{{route('admin:suppliers.upload.upload_library')}}" method="post"
                              enctype="multipart/form-data">
                            @csrf

                            <div class="form-group col-md-10">
                                <label>{{__('files')}}</label>
                                <input type="file" name="files[]" class="form-control" id="files" multiple>
                                <input type="hidden" name="supplier_id" value="" id="library_supplier_id">
                            </div>

                            <div class="form-group col-md-1">
                                <button type="button" class="btn btn-danger" onclick="uploadSupplierFiles()"
                                        style="margin-top: 28px;">{{__('save')}}</button>
                            </div>

                            <div class="form-group col-md-1" id="upload_loader" style="display: none;">
                                <img src="{{asset('default-images/loading.gif')}}" title="loader"
                                     style="width: 34px;height: 39px;margin-top: 27px;">
                            </div>
                        </form>
                    </div>

                    <hr>

                    <div id="supplier_files_area" class="row" style="text-align: center">


                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-sm waves-effect waves-light" data-dismiss="modal">
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

        function getLibrarySupplierId(id) {

            $("#library_supplier_id").val(id);

            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            $.ajax({

                type: 'post',
                url: '{{route('admin:suppliers.upload_library')}}',
                data: {
                    _token: CSRF_TOKEN,
                    id: id,
                },

                beforeSend: function () {
                    // $("#loader_get_test_video").show();
                },

                success: function (data) {

                    $("#supplier_files_area").html(data.view);
                },

                error: function (jqXhr, json, errorThrown) {
                    // $("#loader_save_goals").hide();
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });

        }

        function getSupplierFiles(id) {

            $.ajax({

                type: 'post',
                url: '{{route('admin:suppliers.upload_library')}}',
                data: {
                    _token: CSRF_TOKEN,
                    id: id,
                },

                beforeSend: function () {
                    // $("#loader_get_test_video").show();
                },

                success: function (data) {

                    $("#supplier_files_area").html(data.view);
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
                        url: '{{route('admin:suppliers.upload_library.file.delete')}}',
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

        function uploadSupplierFiles() {

            var form_data = new FormData();

            var supplier_id = $("#library_supplier_id").val();

            var totalfiles = document.getElementById('files').files.length;

            for (var index = 0; index < totalfiles; index++) {
                form_data.append("files[]", document.getElementById('files').files[index]);
            }

            form_data.append("supplier_id", supplier_id);

            $.ajax({
                url: "{{route('admin:suppliers.upload.upload_library')}}",
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

                    $("#supplier_files_area").prepend(data.view);

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

    <script type="application/javascript" src="{{ asset('accounting-module/options-for-dt.js') }}"></script>

@endsection
