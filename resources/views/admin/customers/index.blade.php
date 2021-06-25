@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('customers and cars') }} </title>
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('accounting-module/daily-restriction.css') }}"/>
@endsection


@section('content')
    <div class="row small-spacing">

    <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"> {{__('customers and cars')}}</li>
            </ol>
        </nav>

        @include('admin.customers.parts.search')


        <div class="col-xs-12">
        <div class="box-content card bordered-all js__card">
                <h4 class="box-title bg-secondary with-control">
                <i class="fa fa-car"></i>  {{__('customers and cars')}}
                 </h4>

                 <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">
                       <li class="list-inline-item">
                       @include('admin.buttons.add-new', [
                  'route' => 'admin:customers.create',
                      'new' => '',
                     ])
                       </li>

                            <li class="list-inline-item">
                                @component('admin.buttons._confirm_delete_selected',[
                                   'route' => 'admin:customers.deleteSelected',
                                    ])
                                @endcomponent
                            </li>

                    </ul>
                    <div class="clearfix"></div>
                    <div class="table-responsive">
                        @php
                            $view_path = 'admin.customers.options-datatable';
                        @endphp
                        @include($view_path . '.option-row')
                        <div class="clearfix"></div>
                        <table id="customers" class="table table-bordered" style="width:100%;margin-top: 15px">
                            @include($view_path . '.table-thead')
                            <tfoot>
                            <tr>
                                <th class="text-center column-name" scope="col">{!! __('Name') !!}</th>
                                <th class="text-center column-customer-type" scope="col">{!! __('Customer Type') !!}</th>
                                <th class="text-center column-customer-category" scope="col">{!! __('Customers Category') !!}</th>
                                <th class="text-center column-status" scope="col">{!! __('Status') !!}</th>
                                <th class="text-center column-cars-number" scope="col">{!! __('Cars Number') !!}</th>
                                <th class="text-center column-balance-for" scope="col">{!! __('Balance For') !!}</th>
                                <th class="text-center column-balance-to" scope="col">{!! __('Balance To') !!}</th>
                                <th class="text-center column-created-at" scope="col">{!! __('Created At') !!}</th>
                                <th class="text-center column-updated-at" scope="col">{!! __('Updated At') !!}</th>
                                <th scope="col">{!! __('Options') !!}</th>
                                <th scope="col">{!! __('Select') !!}</th>

                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($customers as $customer)
                                @php
                                    $customer_balance = $customer->direct_balance();
                                @endphp
                                <tr>
                                    <td class="text-center column-name">{!! $customer->name !!}</td>
                                    <td class="text-center column-customer-type">
                                    <span class="label label-danger wg-label">
                                    {{__($customer->type)}}
                                   </span>
                                    </td>
                                    <td class="text-center column-customer-category">
                                    <span class="label label-primary wg-label">
                                    {!! optional($customer->customerCategory)->name_ar !!}
                                    </span>
                                    </td>
                                    @if ($customer->status)
                                    <td class="text-center column-status">
                                        <div class="switch success">
                                            <input
                                                    disabled
                                                    type="checkbox"
                                                    {{$customer->status == 1 ? 'checked' : ''}}
                                                    id="switch-{{ $customer->id }}">
                                            <label for="customer-{{ $customer->id }}"></label>
                                        </div>

                                        </td>
                                        @else
                                        <td class="text-center column-status">
                                        <div class="switch success">
                                            <input
                                                    disabled
                                                    type="checkbox"
                                                    {{$customer->status == 1 ? 'checked' : ''}}
                                                    id="switch-{{ $customer->id }}">
                                            <label for="customer-{{ $customer->id }}"></label>
                                        </div>

                                        </td>
                                         @endif
                                    <td class="text-center column-cars-number">{!!optional($customer->cars)->count() !!}</td>
                                    <td class="text-danger text-center column-balance-for">{{ $customer_balance['debit'] }}</td>
                                    <td class="text-danger column-balance-to">{{ $customer_balance['credit'] }}</td>
                                    <td class="text-center column-created-at">{!! $customer->created_at->format('y-m-d h:i:s A') !!}</td>
                                    <td class="text-center column-updated-at">{!! $customer->updated_at->format('y-m-d h:i:s A') !!}</td>
                                    <td>
                                        @component('admin.buttons._edit_button',[
                                                    'id'=> $customer->id,
                                                    'route' => 'admin:customers.edit',
                                                     ])
                                        @endcomponent

                                        @component('admin.buttons._delete_button',[
                                                    'id'=> $customer->id,
                                                    'route' => 'admin:customers.destroy',
                                                     ])
                                        @endcomponent

                                            <a class="show-car-wg hvr-radial-out   text-white"
                                               href="{{route('admin:customers.show',['id' => $customer->id])}}">
                                                <i class="fa fa-eye"></i> {{__('Show')}}
                                            </a>

                                            <a data-toggle="modal" data-target="#boostrapModal-2"
                                               onclick="getLibraryCustomerId('{{$customer->id}}')"
                                               title="Supplier Library" class="btn btn-warning">
                                                <i class="fa fa-plus"> </i> {{__('Library')}}
                                            </a>
                                    </td>
                                    <td>
                                    @component('admin.buttons._delete_selected',[
                                       'id' =>  $customer->id,
                                        'route' => 'admin:customers.deleteSelected',
                                        ])
                                        @endcomponent
                                    </td>

                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $customers->links() }}
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
                    <h4 class="modal-title" id="myModalLabel-1">{{__('Customer Library')}}</h4>
                </div>
                <div class="modal-body">


                    <div class="row">
                        <form action="{{route('admin:customers.upload.upload_library')}}" method="post"
                              enctype="multipart/form-data">
                            @csrf

                            <div class="form-group col-md-10">
                                <label>{{__('files')}}</label>
                                <input type="file" name="files[]" class="form-control" multiple id="files">
                                <input type="hidden" name="customer_id" value="" id="library_customer_id">
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

                    <div id="customer_files_area" class="row" style="text-align: center">


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

@section('accounting-module-modal-area')
    @include($view_path . '.column-visible')
@endsection

@section('js')

    <script type="application/javascript">
        // invoke_datatable($('#customers'))
    </script>

    <script type="application/javascript" src="{{ asset('accounting-module/options-for-dt.js') }}"></script>

    <script>

        function getLibraryCustomerId(id) {

            $("#library_customer_id").val(id);

            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            $.ajax({

                type: 'post',
                url: '{{route('admin:customers.upload_library')}}',
                data: {
                    _token: CSRF_TOKEN,
                    id: id,
                },

                beforeSend: function () {
                    // $("#loader_get_test_video").show();
                },

                success: function (data) {

                    $("#customer_files_area").html(data.view);
                },

                error: function (jqXhr, json, errorThrown) {
                    // $("#loader_save_goals").hide();
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });

        }

        function removeFile (id) {

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
                        url: '{{route('admin:customers.upload_library.file.delete')}}',
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

            var customer_id = $("#library_customer_id").val();

            var totalfiles = document.getElementById('files').files.length;

            for (var index = 0; index < totalfiles; index++) {
                form_data.append("files[]", document.getElementById('files').files[index]);
            }

            form_data.append("customer_id", customer_id);

            $.ajax({
                url: "{{route('admin:customers.upload.upload_library')}}",
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

                    $("#customer_files_area").prepend(data.view);

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

@endsection
