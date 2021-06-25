
<div class="row small-spacing" style="display: none" id="CustomerCarFrom">
    <div class="col-xs-12">
        <!-- <div class="box-content card bordered-all success"> -->
            <!-- <h1  class="box-title bg-info"><i class="fa fa-car"></i>{{__('Car')}}</h1> -->
            <div class="card-content">
                <div class="box-content">
                        @include('admin.quotations.cars.customer-data')
                        @include('admin.quotations.cars.car_from')
                        @include('admin.quotations.cars.list_cars')
                </div>
            </div>
        </div>
    </div>
<!-- </div> -->
{{--@section('js-validation')--}}
{{--    @include('admin.partial.sweet_alert_messages')--}}
{{--@endsection--}}
