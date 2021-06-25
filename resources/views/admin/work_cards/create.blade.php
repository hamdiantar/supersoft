@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{__('Create Card')}} </title>
@endsection

@section('content')
    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin:work-cards.index')}}"> {{__('Work Cards')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Create Card')}}</li>
            </ol>
        </nav>

        <div class="col-xs-12">
            <div class=" card box-content-wg-new bordered-all primary">
                <h1 class="box-title bg-info" style="text-align: initial"><i class="fa fa-car"></i>
                    {{__('Create Card')}}
                    <span class="controls hidden-sm hidden-xs pull-left">
                    <button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Save')}}<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f1.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Reset')}}<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f2.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px"> {{__('Back')}} <img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f3.png')}}"></button>

						</span>
                </h1>
                <div class="box-content">

                    <form method="post" action="{{route('admin:work-cards.store')}}" class="form">
                        @csrf
                        @method('post')

                        @include('admin.work_cards.form')

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
    @include('admin.work_cards.cars_table')

    @include('admin.work_cards.customers.form')
@endsection

@section('js-validation')
    {!! JsValidator::formRequest('App\Http\Requests\Admin\WorkCards\CreateWorkCardRequest', '.form'); !!}
    @include('admin.partial.sweet_alert_messages')
@endsection

@section('js')

    <script type="application/javascript" src="{{asset('assets/ckeditor/ckeditor.js')}}"></script>
    <script type="application/javascript" src="{{asset('assets/ckeditor/samples/js/sample.js')}}"></script>

    <script type="application/javascript">

        CKEDITOR.replace('editor1', {});

        function saveWithInvoice() {
            $("#save_with_invoice").prop('disabled', false);
        }

        function getCustomersByBranch() {

            var branch_id = $("#branch_id").val();

            $.ajax({
                url: "{{ route('admin:work.cards.customers.by.branch')}}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },

                data: {branch_id: branch_id},

                success: function (data) {

                    $('.js-example-basic-single').select2();

                    $(".removeToNewData").remove();

                    $.each(data.customers, function (key, modelName) {

                        let phone = modelName.phone1 != null ? modelName.phone1 : '';

                        var option = new Option(modelName, modelName);

                        option.text = modelName.name_en + '-' + phone;

                        @if(app()->getLocale() == 'ar')
                            option.text = modelName.name_ar + '-' + phone;
                        @endif

                            option.value = modelName.id;

                        $("#customers_options").append(option);

                        $("#customers_options option[value='" + modelName.id + "']")
                            .attr("data-customer-balance", `${JSON.stringify(modelName.balance_details)}`)

                        $('#customers_options option').addClass(function () {
                            return 'removeToNewData';
                        });
                    });

                    $(".removeGroupsToNewData").remove();

                    $.each(data.customerGroups, function (key, modelName) {

                        var option = new Option(modelName, modelName);

                        option.text = modelName.name_en;

                        @if(app()->getLocale() == 'ar')
                            option.text = modelName.name_ar ;
                        @endif

                        option.value = modelName.id;

                        $("#customer_group_options").append(option);

                        $('#customer_group_options option').addClass(function () {
                            return 'removeGroupsToNewData';
                        });
                    });


                    $("#card_car_id").val('');
                    $("#customer_car_id").val('');
                }
            });
        }

        {{-- add Customer js  --}}
        $("#country").change(function () {
            $.ajax({
                url: "{{ route('admin:country.cities') }}?country_id=" + $(this).val(),
                method: 'GET',
                success: function (data) {
                    $('#city').html(data.cities);
                    $('#currency').html(data.currency);
                }
            });
        });

        $("#city").change(function () {
            $.ajax({
                url: "{{ route('admin:city.areas') }}?city_id=" + $(this).val(),
                method: 'GET',
                success: function (data) {
                    $('#area').html(data.html);
                }
            });
        });

        function getCompanyDataForQuotations() {

            var type = $("#customer_type").val();

            if (type == 'person') {

                $(".company_data").hide();
            } else {
                $(".company_data").show();
            }
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

        function selectCustomerCar(id, type) {

            var customer_id = $("#customers_options").val();

            $.ajax({
                url: "{{ route('admin:work.cards.select.customer.car')}}",

                method: 'POST',

                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },

                data: {id: id, customer_id: customer_id, type: type},



                success: function (data) {


                    $("#customer_car_id").val(data.id);
                    $("#card_car_id").val( data.plate_number);
                    $("#boostrapModal-2").modal('hide');

                    // $("#balance").val(balance)
                    // $("#balance-title").text(balance_title)

                    $("#balance").val(data.balance.balance)
                    $("#balance-title").text(data.balance.balance_title)
                },

                error: function (data) {
                    $("#card_car_id").val('');
                    $("#customer_car_id").val('');
                }
            });
        }


        function customerBalance(){

            var balance = $("#customers_options option:selected").data("customer-balance")
            if (balance) {
                $("#balance").val(balance.balance)
                // $("#balance-title").text(balance.balance_title)
            }
        }

        // $("#customers_options").on('change', function () {
        //     var balance = $("#customers_options option:selected").data("customer-balance")
        //     if (balance) {
        //         $("#balance").val(balance.balance)
        //         $("#balance-title").text(balance.balance_title)
        //     }
        // })


        // NEW CUSTOMER

        function setBranchId() {
            let branchId = localStorage.getItem('branch_id');
            $('#setBranchId').val(branchId)
            $.ajax({
                url: "{{ route('admin:customers.customerCategory') }}?id=" + branchId,
                method: 'GET',
                success: function (data) {
                    $('#customerCategory').html(data.customerCategory);
                }
            });
        }

        function addCustomerQuotations() {
            event.preventDefault();
            $.ajax({
                url: "{{ route('admin:sales.invoices.add.customer')}}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: $("#add_customer_form_e").serialize() + '&branch_id=' + $('#branch_id').val(),
                success: function (data) {
                    var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?customerId=' + data.customerId;
                    window.history.pushState({path: newurl}, '', newurl);
                    $('.customerIDFromController').val(data.customerId)
                    $('#customerName').html(data.customerName)
                    $('#customerAddress').html(data.customerAddress)
                    $('#customerPhone').html(data.customerPhone)
                    $('#customerType').html(data.customerType)
                    $('#carsCount').html(data.carsCount)
                    $('#customer_data').html(data.customerDataViewForCardInvoice);
                    toastr.success('supplier saved successfully');
                    $('#add_customer_form_e').hide();
                    $('#modal1Title').text('Add Cars');
                    $('.remodal').css('max-width', '1090px');
                    $('#CustomerCarFrom').show();
                    $('.js-example-basic-single').select2();
                },
                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    toastr.error(errors);
                },
            });
        }
    </script>

@endsection


