@extends('admin.layouts.app')
@section('title')
    <title>{{ __('Super Car') }} - {{ __('Add customers and cars') }} </title>
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

                    <a class="btn hvr-rectangle-in saveAdd-wg-btn  " style="margin: auto"
                       href="{{url('admin/customers/edit/'.$customer->id)}}"><i
                            class="fa fa-user"></i> {{__('Show Customer Data')}}</a>
                    <br><br>
                    <div class="">
                        @include('admin.customers.parts.customer-data')
                        @include('admin.customers.parts.customer_points')
                        @include('admin.customers.cars.form')
{{--                        @include('admin.customers.parts.list_cars')--}}
                    </div>
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

    </script>

    {!! JsValidator::formRequest('App\Http\Requests\Admin\Cars\AddCarRequest', '.form'); !!}
    @include('admin.partial.sweet_alert_messages')
@endsection
