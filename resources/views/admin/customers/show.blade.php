@extends('admin.layouts.app')
@section('title')
    <title>{{ __('Super Car') }} - {{ __('show customers and cars') }} </title>
@endsection

@section('content')
    <div class="row small-spacing">
        <nav>

            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a
                        href="{{route('admin:customers.index')}}"> {{__('customers and cars')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Customers cars')}}</li>
            </ol>
        </nav>


        <div class="col-xs-12">
            <div class=" card box-content-wg-new bordered-all primary">
                <h4 class="box-title with-control"><i class="fa fa-car"></i>{{__('Customers cars')}}</h4>

                <div class="box-content">

                    <a class="btn hvr-rectangle-in saveAdd-wg-btn " style="margin: auto"
                       href="{{url('admin/customers/edit/'.$customer->id)}}"><i
                            class="fa fa-user"></i> {{__('Show Customer Data')}}
                    </a>
                    <br><br>

                    <div class="">
                        @include('admin.customers.parts.customer-data')
                        @include('admin.customers.parts.customer_points')
                        @include('admin.customers.cars.form')
                        @include('admin.customers.cars.index')
                        {{--                        @include('admin.customers.parts.list_cars')--}}
                    </div>
                </div>


            </div>
        </div>

        <div class="col-xs-12">
            <div class="box-content box-content-wg card bordered-all blue-1 js__card">
                <h4 class="box-title bg-blue-1 with-control text-center">
                    {{__('Contacts')}}
                    <span style="float: right;"> </span>
                    <span class="controls">

                                    <button type="button" class="control fa fa-minus js__card_minus"></button>
                                    <button type="button" class="control fa fa-times js__card_remove"></button>
                                </span>
                </h4>

                <div class="card-content js__card_content" style="">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>{{__('Name')}}</th>
                            <th>{{__('Phone 1')}}</th>
                            <th>{{__('Phone 2')}}</th>
                            <th>{{__('address')}}</th>
                            <th>{{__('Created Date')}}</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($customer->contacts as $contact)
                            <tr>
                                <td>{{$contact->name}}</td>
                                <td>{{$contact->phone_1}}</td>
                                <td>{{$contact->phone_2}}</td>
                                <td>{{\Illuminate\Support\Str::limit($contact->address, 50)}}</td>
                                <td>{{$contact->created_at}}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
        <div class="col-xs-12">
            <div class="box-content box-content-wg card bordered-all blue-1 js__card">
                <h4 class="box-title bg-blue-1 with-control text-center">
                    {{__('Bank Accounts')}}
                    <span style="float: right;"> </span>
                    <span class="controls">

                                    <button type="button" class="control fa fa-minus js__card_minus"></button>
                                    <button type="button" class="control fa fa-times js__card_remove"></button>
                                </span>
                </h4>

                <div class="card-content js__card_content" style="">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>{{__('Bank Name')}}</th>
                            <th>{{__('Account Name')}}</th>
                            <th>{{__('Branch Name')}}</th>
                            <th>{{__('Account Number')}}</th>
                            <th>{{__('IBAN')}}</th>
                            <th>{{__('Swift Code')}}</th>
                            <th>{{__('Created Date')}}</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($customer->bankAccounts as $bankAccount)
                            <tr>
                                <td>{{$bankAccount->bank_name}}</td>
                                <td>{{$bankAccount->account_name}}</td>
                                <td>{{$bankAccount->branch}}</td>
                                <td>{{$bankAccount->account_number}}</td>
                                <td>{{$bankAccount->iban}}</td>
                                <td>{{$bankAccount->swift_code}}</td>
                                <td>{{$bankAccount->created_at}}</td>
                            </tr>
                        @endforeach

                    </table>
                </div>
            </div>

        </div>

    </div>
@endsection


@section('modals')

    <div class="modal fade" id="boostrapModal-2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" id="cars_edit_form">


            </div>
        </div>
    </div>

@endsection

@section('js-validation')

    <script type="application/javascript">

        invoke_datatable($('#cars'));

        function carsEditForm(car_id) {

            $.ajax({

                url: "{{ route('admin:cars.edit')}}",

                method: 'POST',

                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },

                data: {car_id: car_id},

                success: function (data) {

                    $("#cars_edit_form").html(data.view);

                    $('.js-example-basic-single').select2();
                },

                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    toastr.info("{{__('something went wrong')}}", '', options);
                },
            });
        }

        function carModels(type) {

            let company_id = $(".company_id_" + type).val();

            $.ajax({

                url: "{{ route('admin:cars.models')}}",

                method: 'POST',

                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },

                data: {company_id: company_id},

                success: function (data) {

                    $('.js-example-basic-single').select2();

                    $(".removeToNewData_" + type).remove();

                    var option = new Option('', '');
                    option.text = '{{__('Select Car Model')}}';
                    option.value = "";

                    $(".model_id_" + type).append(option);

                    $.each(data.models, function (key, modelName) {


                        var option = new Option(modelName, modelName);
                        @if(\Illuminate\Support\Facades\App::getLocale() == 'ar')
                            option.text = modelName.name_ar;
                        @else
                            option.text = modelName.name_en;
                        @endif


                            option.value = modelName.id;

                        $(".model_id_" + type).append(option);

                        $('.model_id_' + type + ' option').addClass(function () {
                            return 'removeToNewData_' + type;
                        });
                    });


                    // $("#cars_edit_form").html(data.view);
                },

                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    toastr.info("{{__('something went wrong')}}", '', options);
                },
            });
        }

        function readURL(input) {

            if (input.files && input.files[0]) {

                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah')
                        .attr('src', e.target.result)
                };

                reader.readAsDataURL(input.files[0]);
            }
        }

        function openWin(id) {

            var qty = $("#barcode_qty").val();

            $.ajax({
                type: 'get',
                data: {id: id, qty: qty},
                url: "{{route('admin:cars.print.barcode')}}",
                success: function (data) {
                    var myWindow = window.open('', '', 'width=700,height=500');
                    myWindow.document.write(data);

                    myWindow.document.close();
//                    myWindow.focus();
//                    myWindow.print();
//                    myWindow.close();
//                    $(".msg").html(data);
                },
                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        }

    </script>

    {!! JsValidator::formRequest('App\Http\Requests\Admin\Cars\AddCarRequest', '.form'); !!}
    @include('admin.partial.sweet_alert_messages')
@endsection
