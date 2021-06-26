@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Edit Supply Orders') }} </title>
@endsection

@section('style')

@endsection

@section('content')

    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"><a
                        href="{{route('admin:supply-orders.index')}}"> {{__('Supply Orders')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Edit Supply Orders')}}</li>
            </ol>
        </nav>

        <div class="col-xs-12">

            {{--  box-content-wg-new  --}}

            <div class=" card box-content-wg-new bordered-all primary">

                <h4 class="box-title with-control" style="text-align: initial"><i class="fa fa-file-text-o"></i>
                    {{__('Edit Supply Orders')}}
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
                    <form method="post" action="{{route('admin:supply-orders.update', $supplyOrder->id)}}" class="form"
                          enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        @include('admin.supply_orders.form')

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

    <div class="modal fade" id="purchase_quotations" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                    <h4 class="modal-title" id="myModalLabel-1">{{__('Purchase Quotations')}}</h4>
                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-12 margin-bottom-20">
                            <table id="purchase_quotations_table" class="table table-bordered" style="width:100%">
                                <thead>
                                <tr>
                                    <th scope="col">{!! __('Check') !!}</th>
                                    <th scope="col">{!! __('Purchase quotations') !!}</th>
                                    <th scope="col">{!! __('Supplier') !!}</th>
                                </tr>
                                </thead>

                                <form id="purchase_quotation_form" method="post">
                                    @csrf

                                    <tbody id="purchase_quotation_data">

                                        @if(isset($supplyOrder))
                                            @include('admin.supply_orders.purchase_quotations', ['purchaseQuotations'=> $data['purchaseQuotations'],
                                            'supply_order_quotations' => $supplyOrder->purchaseQuotations->pluck('id')->toArray()])
                                        @endif

                                    </tbody>

                                </form>
                            </table>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-danger btn-sm waves-effect waves-light"
                            data-dismiss="modal">
                        {{__('Close')}}
                    </button>

                    <button type="button" class="btn btn-primary btn-sm waves-effect waves-light"
                            onclick="addSelectedPurchaseQuotations()">
                        {{__('Add Item')}}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="modals_part_types">

    </div>

@endsection

@section('js-validation')

    {!! JsValidator::formRequest('App\Http\Requests\Admin\SupplyOrders\CreateRequest', '.form'); !!}
    @include('admin.partial.sweet_alert_messages')

@endsection

@section('js')

    <script src="{{asset('js/supply_orders/index.js')}}"></script>

    <script type="application/javascript">

        function changeBranch() {
            let branch_id = $('#branch_id').find(":selected").val();
            window.location.href = "{{route('admin:supply-orders.create')}}" + "?branch_id=" + branch_id;
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
                    order: order
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

            $.ajax({

                type: 'post',

                url: '{{route('admin:supply.orders.select.part')}}',

                data: {
                    _token: CSRF_TOKEN,
                    part_id: part_id,
                    index: index,
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

        function priceSegments(index) {

            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            let price_id = $('#prices_part_' + index).val();

            $.ajax({

                type: 'post',

                url: '{{route('admin:supply.orders.price.segments')}}',

                data: {
                    _token: CSRF_TOKEN,
                    price_id: price_id,
                    index: index
                },

                success: function (data) {

                    $("#price_segments_part_" + index).html(data.view);
                    $('.js-example-basic-single').select2();
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
                    calculateItem(index);
                    reorderItems();
                }
            });

        }

        function getPurchaseQuotations() {

            if (!checkBranchValidation()) {
                swal({text: '{{__('sorry, please select branch first')}}', icon: "error"});
                return false;
            }

            $("#purchase_quotations_table").dataTable().fnDestroy()

            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            let purchase_request_id = $('#purchase_request_id').find(":selected").val();

            let data = {
                _token: CSRF_TOKEN,
                purchase_request_id: purchase_request_id
            }

            let supplier = $('#supplier_id').val();

            if (supplier.length) {
                data.supplier_id = supplier
            }

            $.ajax({

                type: 'post',
                url: '{{route('admin:supply.orders.purchase-quotations')}}',
                data: data,

                success: function (data) {

                    $("#purchase_quotation_data").html(data.view);

                    $("#purchase_quotations_selected").html(data.real_purchase_quotations);

                    $("#purchase_quotations").modal('show');

                    $('.js-example-basic-single').select2();

                    invoke_datatable_quotations($('#purchase_quotations_table'));
                },

                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        }

        function addSelectedPurchaseQuotations() {

            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            var selected = [];

            $('#purchase_quotation_data input:checked').each(function() {
                selected.push($(this).attr('value'));
            });
            $.ajax({

                type: 'post',

                url: '{{route('admin:supply.orders.add.purchase-quotations')}}',

                data: {_token:CSRF_TOKEN, purchase_quotations:selected} ,

                success: function (data) {

                    $("#supplier_id").val(data.supplierId).change();

                    $("#parts_data").html(data.view);

                    $("#items_count").val(data.index);

                    $('.js-example-basic-single').select2();

                    executeAllItems();
                },

                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        }

        function reorderItems() {

            let items_count = $('#items_count').val();

            let index = 1;

            for (let i = 1; i <= items_count; i++) {

                if ($('#price_' + i).length) {
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

        function invoke_datatable_quotations (selector ,load_at_end_selector ,last_child_allowed) {
            var selector_id = selector.attr("id")
            var page_title = $("title").text()
            $("#" + selector_id).DataTable({
                "language": {
                    "url": "{{app()->isLocale('ar')  ? "//cdn.datatables.net/plug-ins/1.10.20/i18n/Arabic.json" :
                                             "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/English.json"}}",
                },
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'colvis',
                        text: '<i class="fa fa-list"></i> {{__('Columns visibility')}}',
                    },
                ],
            });
        }

        $('.dropdown-toggle').dropdown();

    </script>

@endsection
