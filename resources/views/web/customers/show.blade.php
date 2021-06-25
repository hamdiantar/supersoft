@extends('web.layout.app')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('Add customers and cars') }} </title>
@endsection

@section('content')
    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('web:dashboard')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Customers cars')}}</li>
            </ol>
        </nav>


        <div class="col-xs-12">
            <div class="card box-content-wg-new bordered-all primary ">

                <h4 class="box-title with-control"><i class="fa fa-car"></i>{{__('Customers cars')}}</h4>

                <a class="btn hvr-rectangle-in saveAdd-wg-btn  " style="margin: auto"
                   href="{{route('web:customer.edit')}}">
                    <i class="fa fa-user"></i>
                    {{__('Show Customer Data')}}
                </a>
                <br><br>

                @include('web.customers.customer_data')

                @include('web.customers.car_form')
                @include('web.customers.cars_list')
                @include('web.customers.contacts.show')
                @include('web.customers.bank_accounts.show')

            </div>
            <!-- /.box-content card -->
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

                url: "{{ route('web:cars.edit')}}",

                method: 'POST',

                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },

                data: {car_id: car_id},

                success: function (data) {
                    $("#cars_edit_form").html(data.view);
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

        function carModels(type) {

            let company_id = $(".company_id_" + type).val();

            $.ajax({

                url: "{{ route('web:cars.models')}}",

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

    </script>

    {!! JsValidator::formRequest('App\Http\Requests\Web\Customers\AddCarRequest', '.form'); !!}
    @include('admin.partial.sweet_alert_messages')
@endsection
