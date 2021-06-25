@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{__('Create Card Invoice')}} </title>
@endsection

@section('style')
    <!-- Jquery UI -->
    <link rel="stylesheet" href="{{asset('assets/plugin/jquery-ui/jquery-ui.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugin/jquery-ui/jquery-ui.structure.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugin/jquery-ui/jquery-ui.theme.min.css')}}">

@endsection
@section('content')
    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin:work-cards.index')}}"> {{__('Work Cards')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Create Card Invoice')}}</li>
            </ol>
        </nav>

        <div class="col-xs-12">
            <div class=" card box-content-wg-new bordered-all primary">
                <h1 class="box-title bg-info" style="text-align: initial"><i class="fa fa-file-text-o"></i>
                    {{__('Create Card Invoice')}}
                    <span class="controls hidden-sm hidden-xs pull-left">
                    <button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Save')}}<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f1.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Reset')}}<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f2.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px"> {{__('Back')}} <img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f3.png')}}"></button>

						</span>
                </h1>
                <div class="box-content">

                    <form method="post" action="{{route('admin:work-cards-invoices.store')}}" id="card_invoice"
                          enctype="multipart/form-data" class="form">
                        @csrf
                        @method('post')

                        @include('admin.work_cards.invoices.create_form')

                    </form>
                </div>

            </div>
            <!-- /.box-content -->
        </div>
        <!-- /.col-xs-12 -->
    </div>
    <!-- /.row small-spacing -->

    {{--    @include('admin.work_cards.customers.form')--}}
@endsection

@section('modals')
    {{--    @include('admin.work_cards.cars_table')--}}
@endsection

@section('js-validation')
    {!! JsValidator::formRequest('App\Http\Requests\Admin\WorkCards\CreateWorkCardRequest', '.form'); !!}
    @include('admin.partial.sweet_alert_messages')
@endsection

@section('js')

    <script type="application/javascript" src="{{asset('assets/plugin/jquery-ui/jquery-ui.min.js')}}"></script>
    <script type="application/javascript" src="{{asset('assets/plugin/jquery-ui/jquery.ui.touch-punch.min.js')}}"></script>

    <script type="application/javascript" src="{{asset('assets/ckeditor/ckeditor.js')}}"></script>
    <script type="application/javascript" src="{{asset('assets/ckeditor/samples/js/sample.js')}}"></script>

    <script type="application/javascript">
        CKEDITOR.replace('editor1', {});
    </script>

    <script type="application/javascript">

        function saveAndPrint(type) {

            $("#save_type").val(type);
        }

        //  GET CUSTOMERS CARS
        function getCustomersCars() {

            $(".removeOldCustomerCars").remove();
            var customer_id = $("#customers_options").val();

            if (customer_id == '') {
                $("#boostrapModal-2").modal('hide');
                swal("{{__('please select customer')}}", "", "error");
                return false;
            }

                    @if(authIsSuperAdmin())
            var branch_id = $("#branch_id").val();

            if (branch_id == '') {
                $("#boostrapModal-2").modal('hide');
                swal("{{__('please select branch')}}", "", "error");
                return false;
            }

            @endif

            $.ajax({
                url: "{{ route('admin:work.cards.get.customer.cars')}}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },

                data: {customer_id: customer_id},

                beforeSend: function () {
                    $("#customer_cars_loading").show();
                },

                success: function (data) {
                    $("#customer_cars_loading").hide();
                    $("#customer_cars").html(data);
                },

                error: function (data) {
                    $("#customer_cars_loading").hide();
                    swal("Error!", "{{__('Some Thing Went Wrong')}}", "error");
                }
            });
        }

        function activeMaintenance(id) {

            $(".active_maintenance_" + id + "_check_box").prop('disabled', true);
            $(".maintenance_type_" + id).prop('disabled', true);

            if ($('#checkbox-' + id + '-type').is(':checked')) {
                $(".active_maintenance_" + id + "_check_box").prop('disabled', false);
            }

            $(".active_maintenance_" + id + "_check_box").prop('checked', false);
        }

        function activeMaintenanceForm(id) {

            $(".active_maintenance_" + id + "_form").prop('disabled', true);

            if ($('#checkbox-' + id + '-part').is(':checked')) {
                $(".active_maintenance_" + id + "_form").prop('disabled', false);
            }
        }

        function validation() {
            event.preventDefault();
            $.ajax({
                url: "{{ route('admin:work-cards-invoices.validation')}}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: $("#card_invoice").serialize(),

                beforeSend: function () {
                    $("#card_invoice_loading").show();
                },

                success: function (data) {
                    $("#card_invoice_loading").hide();
                    // toastr.success('customer saved successfully');
                    $("#card_invoice").submit();
                },
                error: function (jqXhr, json, errorThrown) {
                    $("#card_invoice_loading").hide();
                    var errors = jqXhr.responseJSON;

                    swal({
                        text: errors,
                        icon: "error",
                    });

                    // toastr.error(errors);
                },
            });
        }

    </script>

@endsection


