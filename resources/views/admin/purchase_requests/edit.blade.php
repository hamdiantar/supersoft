@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Edit Purchase Request') }} </title>
@endsection

@section('content')

    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"><a
                        href="{{route('admin:purchase-requests.index')}}"> {{__('Purchase Requests')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Edit Purchase Request')}}</li>
            </ol>
        </nav>

        <div class="col-xs-12">

            {{--  box-content-wg-new  --}}

            <div class=" card box-content-wg-new bordered-all primary">

                <h4 class="box-title with-control" style="text-align: initial"><i class="fa fa-file-text-o"></i>
                    {{__('Edit Purchase Request')}}
                    <span class="controls hidden-sm hidden-xs pull-left">
                      <button class="control text-white"
                              style="background:none;border:none;font-size:14px;font-weight:normal !important;">{{__('Save')}}
                      <img class="img-fluid" style="width:40px;height:40px;margin-top:-15px;margin-bottom:-13px"
                           src="{{asset('assets/images/f1.png')}}">
                  </button>
                        <button class="control text-white"    style="background:none;border:none;font-size:14px;font-weight:normal !important;">
                            {{__('Reset')}}
                            <img class="img-fluid" style="width:40px;height:40px;margin-top:-15px;margin-bottom:-13px"
                                 src="{{asset('assets/images/f2.png')}}"></button>
							<button class="control text-white"    style="background:none;border:none;font-size:14px;font-weight:normal !important;"> {{__('Back')}} <img
                                    class="img-fluid"
                                    style="width:40px;height:40px;margin-top:-15px;margin-bottom:-13px"
                                    src="{{asset('assets/images/f3.png')}}"></button>
						</span>
                </h4>

                <div class="box-content">
                    <form method="post" class="form" enctype="multipart/form-data"
                          action="{{ isset($request_type) && $request_type == 'approval' ? route('admin:purchase.requests.approval', $purchaseRequest->id) : route('admin:purchase-requests.update', $purchaseRequest->id) }}">

                        @csrf
                        @method('PATCH')

                        @include('admin.purchase_requests.form')

                        <div class="form-group col-sm-12">
                            @include('admin.buttons._save_buttons')
                        </div>

                    </form>
                </div>

            </div>
            <!-- /.box-content -->
        </div>
        <!-- /.col-xs-12 -->
    </div>

    <!-- /.row small-spacing -->
@endsection

@section('modals')

    @if(isset($request_type) && $request_type == 'approval')

        @foreach ($purchaseRequest->items as $index => $item)
            @php
                $index +=1;
                $part = $item->part;
            @endphp

            <div class="modal fade" id="part_quantity_{{$index}}" tabindex="-1" role="dialog"
                 aria-labelledby="myModalLabel-1">
                <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content wg-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel-1">{{__('Part Quantity')}}</h4>
                        </div>
                        <div class="modal-body">
                            <table id="" class="table table-striped table-bordered display" style="width:100%">
                                <thead>
                                <tr>
                                    <th scope="col">{!! __('Store') !!}</th>
                                    <th scope="col">{!! __('Quantity') !!}</th>
                                    <th scope="col">{!! __('Recession date') !!}</th>
                                </tr>
                                </thead>
                                <tfoot>
                                <tr>
                                    <th scope="col">{!! __('Store') !!}</th>
                                    <th scope="col">{!! __('Quantity') !!}</th>
                                    <th scope="col">{!! __('Recession date') !!}</th>
                                </tr>
                                </tfoot>
                                <tbody>

                                @foreach($part->stores as $store)
                                    <tr>
                                        <td>{!! $store->name !!}</td>
                                        <td>{!! $store->pivot->quantity !!}</td>
                                        <td>{!! $store->pivot->updated_at !!}</td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary btn-sm waves-effect waves-light"
                                    data-dismiss="modal">
                                {{__('Close')}}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        @endforeach
    @endif


    <div id="modals_part_types">

        @foreach ($purchaseRequest->items as $index => $item)

            @php
                $index +=1;
                $part = $item->part;
                $partTypes = partTypes($part);
            @endphp

            @include('admin.purchase_requests.part_types')

        @endforeach

    </div>

@endsection



@section('js-validation')

    {!! JsValidator::formRequest('App\Http\Requests\Admin\PurchaseRequest\CreateRequest', '.form'); !!}

    @include('admin.partial.sweet_alert_messages')

    <script type="application/javascript">

        function dataByBranch() {

            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            let branch_id = $('#branch_id').find(":selected").val();

            $.ajax({

                type: 'post',

                url: '{{route('admin:parts.common.filter.data.by.branch')}}',

                data: {
                    _token: CSRF_TOKEN,
                    branch_id: branch_id
                },

                success: function (data) {

                    $("#main_types").html(data.main_types);

                    $("#sub_types").html(data.sub_types);

                    $("#parts").html(data.parts);

                    $(".remove_on_change_branch").remove();

                    $('.js-example-basic-single').select2();
                },

                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        }

        function dataByMainType() {

            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            let branch_id = $('#branch_id').find(":selected").val();
            let main_type_id = $('#main_types_select').find(":selected").val();
            let order = $('#main_types_select').find(":selected").data('order');

            $.ajax({

                type: 'post',

                url: '{{route('admin:parts.common.filter.data.by.main.type')}}',

                data: {
                    _token: CSRF_TOKEN,
                    branch_id: branch_id,
                    main_type_id: main_type_id,
                    order: order,
                },

                success: function (data) {

                    $("#sub_types").html(data.sub_types);
                    $("#parts").html(data.parts);

                    $('.js-example-basic-single').select2();
                },

                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        }

        function dataBySubType() {

            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            let branch_id = $('#branch_id').find(":selected").val();
            let sub_type_id = $('#sub_types_select').find(":selected").val();

            $.ajax({

                type: 'post',

                url: '{{route('admin:parts.common.filter.data.by.sub.type')}}',

                data: {
                    _token: CSRF_TOKEN,
                    branch_id: branch_id,
                    sub_type_id: sub_type_id
                },

                success: function (data) {

                    $("#parts").html(data.parts);
                    $('.js-example-basic-single').select2();
                },

                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        }

        function selectPart() {

            if (!checkBranchValidation()) {
                swal({text: '{{__('sorry, please select branch first')}}', icon: "error"});
                return false;
            }

            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            let part_id = $('#parts_select').find(":selected").val();

            let index = $('#items_count').val();

            let branch_id = $('#branch_id').find(":selected").val();

            $.ajax({

                type: 'post',

                url: '{{route('admin:purchase.requests.select.part')}}',

                data: {
                    _token: CSRF_TOKEN,
                    part_id: part_id,
                    index: index,
                    branch_id: branch_id
                },

                success: function (data) {

                    $("#parts_data").append(data.parts);

                    $("#items_count").val(data.index);

                    $('.js-example-basic-single').select2();

                    reorderItems();
                },

                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        }

        function removeItem(index) {

            swal({

                title: "Delete Item",
                text: "Are you sure want to delete this item ?",
                icon: "warning",
                buttons: true,
                dangerMode: true,

            }).then((willDelete) => {

                if (willDelete) {

                    $('#tr_part_' + index).remove();
                    $('#part_types_' + index).remove();
                    reorderItems();
                }
            });
        }

        function selectItemType(index, key) {

            if ($('#item_type_checkbox_' + index + '_' + key).is(':checked')) {

                $('#item_type_real_checkbox_' + index + '_' + key).prop('checked', true);

            } else {

                $('#item_type_real_checkbox_' + index + '_' + key).prop('checked', false);
            }
        }

        function reorderItems() {

            let items_count = $('#items_count').val();

            let index = 1;

            for (let i = 1; i <= items_count; i++) {

                if ($('#prices_part_' + i).length) {
                    $('#item_number_' + i).text(index);

                }else {
                    continue;
                }

                index++;
            }
        }

        function checkBranchValidation() {

            let branch_id = $('#branch_id').find(":selected").val();

            let isSuperAdmin = '{{authIsSuperAdmin()}}';

            if (!isSuperAdmin) {
                return true;
            }

            if (branch_id) {
                return true;
            }

            return false;
        }

    </script>

@endsection
