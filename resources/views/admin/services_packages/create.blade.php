@extends('admin.layouts.app')
@section('title')
<title>{{ __('Super Car') }} - {{ __('Create Services Package') }} </title>
@endsection
@section('content')
    <div class="row small-spacing">
    <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
            <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin:services_packages.index')}}"> {{__('Service Package')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Create Services Package')}}</li>
            </ol>
        </nav>

        <div class="col-xs-12">
        <div class=" card box-content-wg-new bordered-all primary">
                    <h1 class="box-title bg-info" style="text-align: initial"><i class="fa fa-gear"></i>{{__('Create Services Package')}}
                    <span class="controls hidden-sm hidden-xs pull-left">
                    <button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Save')}}<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f1.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Reset')}}<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f2.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px"> {{__('Back')}} <img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f3.png')}}"></button>
						</span>
                </h1>
                <div class="box-content">
                    <form method="post" action="{{route('admin:services_packages.store')}}" class="form">
                        @csrf
                        @method('post')

                        @if (authIsSuperAdmin())
                        <div class="form-group has-feedback col-sm-12">
                            <label for="inputSymbolAR" class="control-label">{{__('Select Branch')}}</label>
                            <div class="input-group">
                             <span class="input-group-addon fa fa-file"></span>
                            <select name="branch_id" class="form-control js-example-basic-single" onchange="getServiceByBRanch(event)">
                                <option value="">{{__('Select Branch')}}</option>
                                @foreach(\App\Models\Branch::all() as $branch)
                                    <option value="{{$branch->id}}">{{$branch->name}}</option>
                                @endforeach
                            </select>
                            {{input_error($errors,'branch_id')}}
                        </div>
                        </div>
                        @endif

                        <div class="form-group  col-sm-6">
                            <label for="name_ar" class="control-label">{{__('Name in Arabic')}}</label>
                            <div class="input-group">
                                <span class="input-group-addon"><li class="fa fa-file-text-o"></li></span>
                                <input type="text" name="name_ar" class="form-control" id="name_ar"
                                       placeholder="{{__('Name in Arabic')}}">
                            </div>
                            {{input_error($errors,'name_ar')}}
                        </div>
                        <div class="form-group has-feedback col-sm-6">
                            <label for="name_en" class="control-label">{{__('Name in English')}}</label>
                            <div class="input-group">
                                <span class="input-group-addon"><li class="fa fa-file-text-o"></li></span>
                                <input type="text" name="name_en" class="form-control" id="name_en"
                                       placeholder="{{__('Name in English')}}">
                            </div>
                            {{input_error($errors,'name_en')}}
                        </div>
                        <div class="clearfix"></div>


            <div class="row products-details-wg">
<div class="col-lg-5 col-md-6 col-sm-12 col-xs-12">

<div class="form-group col-sm-12 widget-content widget-content-area" style="margin-top:30px">
              <div class="top-exchange-rate-title">
                            <h6 style="font-weight:bold" class="text-dark">{{__('Service Type')}}</h6>
              </div>
                            <ul class="nav nav-pills nav-stacked right-list-wg anyClass list-inline">
                                <div class="nav-item">
                                    <input class="form-control" type="text" placeholder="{{__('Search')}}" id="searchInServiceType" style="width: 100% !important">
                                </div>

                                <li class="nav-item">
                                    <a class="nav-link active bg-danger text-white" href="#" onclick="getServiceItemsById('all')" id="service_type"
                                       >{{__('All Services')}}</a>
                                </li>
                                <div class="searchResultServiceType" id="appendTypesHere">
                                    @if (authIsSuperAdmin())
                                        @foreach(\App\Models\ServiceType::where('status', 1)->get() as $service)
                                        <li class="nav-item  ">
                                                <a class="nav-link active bg-danger text-white" href="#" onclick="getServiceItemsById({{$service->id}})" id="service_type"
                                                >{{$service->name}}</a>
                                            </li>
                                        @endforeach
                                    @endif
                                    @if (false == authIsSuperAdmin())
                                        @foreach(\App\Models\ServiceType::where('status', 1)->where('branch_id', auth()->user()->branch_id )->get() as $service)
                                        <li class="nav-item  ">
                                                <a class="nav-link active bg-danger text-white" href="#" onclick="getServiceItemsById({{$service->id}})" id="service_type"
                                                >{{$service->name}}</a>
                                            </li>
                                        @endforeach
                                    @endif
                                </div>
                            </ul>
                        </div>


                        <div class="form-group col-sm-12 widget-content widget-content-area">
                            <div class="top-exchange-rate-title">
                            <h6 style="font-weight:bold" class="text-dark">{{__('Service Items')}}</h6>
                         </div>
                            <input class="form-control" type="text" id="searchInServiceItem" placeholder="{{__('Search')}}" style="width: 100%;">

                            <ul class="list-inline wg-list anyClass" id="add_services_names">

                                @if(authIsSuperAdmin())
                                @foreach(\App\Models\Service::where('status', 1)->get() as $service)
                                <li class="nav-item bg-blue text-white pakage-item  ">
                                    <a class="nav-link active" onclick="getServiceDetails({{$service->id}})"
                                       href="#"  id="service_details">
                                        {{ $service->name }}
                                    </a></li>
                            @endforeach
                            @endif
                            @if(!authIsSuperAdmin())
                                @foreach(\App\Models\Service::where('status', 1)->where('branch_id', auth()->user()->branch_id )->get() as $service)
                                <li class="nav-item bg-blue text-white pakage-item  ">
                                        <a class="nav-link active" onclick="getServiceDetails({{$service->id}})"
                                           href="#"  id="service_details">
                                            {{ $service->name }}
                                        </a></li>
                                @endforeach
                            @endif
                                </ul>
                        </div>

</div>

<div class="col-lg-7 col-md-6 col-sm-12 col-xs-12">

<div class="form-group has-feedback">

                      <h3 style="text-align: center;">{{__('Service Items Data')}}</h3>
                      <div class="anyClass1 table-responsive">
                      <table class="table table-bordered" style="width:100%">
                          <thead>
                          <tr>
                              <th scope="col">{!! __('Service Name') !!}</th>
                              <th scope="col">{!! __('Price') !!}</th>
                              <th scope="col">{!! __('Hours') !!}</th>
                              <th scope="col">{!! __('Minutes') !!}</th>
                              <th scope="col">{!! __('Quantity') !!}</th>
                              <th scope="col">{!! __('Total') !!}</th>
                              <th scope="col">{!! __('Action') !!}</th>
                          </tr>
                          </thead>
                          <tbody id="add_services">
                          </tbody>
                      </table>
                      </div>
                  </div>

</div>



                        </div>





                        <div class="form-group has-feedback col-sm-12">
                            <table class="table table-bordered" style="width:100%">
                                <thead>
                                <tr>
                                    <th scope="col">{!! __('Service Total Price') !!}</th>
                                    <th scope="col">{!! __('Total Hours') !!}</th>
                                    <th scope="col">{!! __('Total Minutes') !!}</th>
                                    <th scope="col">{!! __('Total Service Number') !!}</th>
                                    <th scope="col" style="width:20%">{!! __('Discount Type') !!}</th>
                                    <th scope="col">{!! __('Discount') !!}</th>
                                    <th scope="col">{!! __('Total After Discount') !!}</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><input type="text" class="form-control" readonly name="total_before_discount"
                                               id="total_before_discount" value="0"></td>
                                    <td><input type="text" class="form-control" readonly name="number_of_hours"
                                               value="0" id="serviceH"></td>
                                    <td><input type="text" class="form-control" readonly name="number_of_min"
                                               value="0" id="serviceM"></td>
                                    <td><input type="text" class="form-control" readonly name="services_number"
                                               value="0" id="serviceNumber"></td>
                                    <td>
                                        <select name="discount_type" id="discount_type"
                                                class="form-control js-example-basic-single">
                                            <option value="value">{{__('Value')}}</option>
                                            <option value="percent">{{__('Percent')}}</option>
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control" value="0" id="discount_value"
                                               name="discount_value"></td>
                                    <td><input type="text" class="form-control" readonly value="0"
                                               name="total_after_discount" id="total_after_discount"></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="form-group col-sm-12">
                            @include('admin.buttons._save_buttons')
                        </div>
                    </form>
                </div>
                <!-- /.box-content -->
            </div>
        </div>
        <!-- /.col-xs-12 -->
    </div>
    <!-- /.row small-spacing -->
@endsection
@section('js-validation')
    {!! JsValidator::formRequest('App\Http\Requests\Admin\ServicesPackage\ServicesPackageRequest', '.form'); !!}
    @include('admin.partial.sweet_alert_messages')
    <script type="application/javascript">
        function setServiceValues(id) {
            let q = document.getElementById('quantity-'+id);
            let price = document.getElementById('servicePrice-'+id);
            let total = document.getElementById('total-'+id);
            let total_before_discount = document.getElementById('total_before_discount');
            let total_after_discount = document.getElementById('total_after_discount');

            total_before_discount.value = (total_before_discount.value - total.value);
            total_after_discount.value = (total_after_discount.value - total.value);

            total.value = (q.value * price.textContent).toFixed(2);
            total_before_discount.value = ( q.value * price.textContent + +total_before_discount.value).toFixed(2);
            total_after_discount.value = (q.value * price.textContent + +total_after_discount.value).toFixed(2);

            let serviceNumberH = document.getElementById('serviceH-'+id);
            let serviceNumberM = document.getElementById('serviceM-'+id);
            let serviceHPackage = document.getElementById('serviceH');
            let serviceMPackage = document.getElementById('serviceM');
            let totalHValue = document.getElementById('totalH-'+id);
            let totalMValue = document.getElementById('totalM-'+id);


            serviceHPackage.value = ( serviceHPackage.value - totalHValue.value);
            serviceMPackage.value = (serviceMPackage.value - totalMValue.value);

            totalHValue.value = (q.value * serviceNumberH.textContent);
            totalMValue.value = (q.value * serviceNumberM.textContent);

            serviceHPackage.value = (q.value * serviceNumberH.textContent + +serviceHPackage.value);
            serviceMPackage.value = ( q.value * serviceNumberM.textContent + +serviceMPackage.value);
        }
function getServiceItemsById(id) {
    event.preventDefault();
    $.ajax({
        url: "{{ route('admin:services_packages.services') }}?service_id=" + id,
        method: 'GET',
        success: function (data) {
            $('#add_services').html(data.services);
            $('#add_services_names').html(data.servicesNames);
        }
    });
}

function getServiceByBRanch(event) {
    event.preventDefault();
    if (event.target.value) {
        $.ajax({
            url: "{{ route('admin:services_packages.services') }}?branch_id=" + event.target.value,
            method: 'GET',
            success: function (data) {
                $('#add_services').html(data.services);
                $('#add_services_names').html(data.servicesNames);
                $('#appendTypesHere').html(data.types);
            }
        });
    }
}
function getServiceDetails(id) {
    event.preventDefault();
    let isExist = true;
    $('#add_services tr').each(function(){
        if($(this).data('id') === id){
            isExist = false;
            swal({
                text: "{{__('You have chosen this Service before, you cannot choose it again!')}}",
                icon: "warning",
            })
        }
    });
    if(isExist) {
        $.ajax({
            url: "{{ route('admin:services_packages.services.details') }}?service_id=" + id,
            method: 'GET',
            async: false,
            success: function (data) {
                $('#add_services').append(data.services);
                let serviceNumber = document.getElementById('serviceNumber');
                serviceNumber.value++;
            }
        });
    }
}
        $('#discount_value').on('keyup', function () {
            let discountType = $('#discount_type').val();
           if (discountType === 'value') {
               let total_before_discount = document.getElementById('total_before_discount').value;
               let discount_value = $(this).val();
               document.getElementById('total_after_discount').value = (total_before_discount - discount_value).toFixed(2);
           }
            if (discountType === 'percent') {
                let total_before_discount = document.getElementById('total_before_discount').value;
                let discount_value = $(this).val();
                document.getElementById('total_after_discount').value = (total_before_discount - (total_before_discount *(discount_value/100))).toFixed(2);
            }

        });
        $('#discount_type').on('change', function () {
            document.getElementById('total_after_discount').value = document.getElementById('total_before_discount').value;
            document.getElementById('discount_value').value = 0;
        })
       function removeServiceFromTable(id) {
           swal({
               text: "{{__('Are you sure want to remove this service ?')}}",
               icon: "warning",
               buttons: {
                   confirm: {
                       text: "{{__('Ok')}}",
                   },
                   cancel: {
                       text: "{{__('Cancel')}}",
                       visible: true,
                   }
               }
           }).then(function (isConfirm) {
               if (isConfirm) {
                   let serviceNumber = document.getElementById('serviceNumber');
                   serviceNumber.value--;
                   let total = document.getElementById('total-'+id);
                   let total_before_discount = document.getElementById('total_before_discount');
                   let total_after_discount = document.getElementById('total_after_discount');
                   total_before_discount.value =  (total_before_discount.value - total.value).toFixed(2);
                   total_after_discount.value = (total_after_discount.value - total.value).toFixed(2);

                   let serviceHPackage = document.getElementById('serviceH');
                   let serviceMPackage = document.getElementById('serviceM');
                   let totalHValue = document.getElementById('totalH-'+id);
                   let totalMValue = document.getElementById('totalM-'+id);

                   serviceHPackage.value = (serviceHPackage.value - totalHValue.value);
                   serviceMPackage.value = (serviceMPackage.value - totalMValue.value);
                   $('#'+id).remove();
               }
           });
       }

       $('#searchInServiceType').on('keyup', function () {
           let value = $(this).val().toLowerCase();
           $(".searchResultServiceType li a").filter(function() {
               $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
           });
       });
        $('#searchInServiceItem').on('keyup', function () {
            let value = $(this).val().toLowerCase();
            $("#add_services_names li a").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        })
    </script>
@endsection
