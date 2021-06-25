
<div id="ajax_service_box">
    <div class="row">

        @if(invoiceSetting())

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 products-details-wg">


            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                <div class="widget-content widget-content-area h-100 br-4 p-0">
                    <div class="top-exchange-rate">

                        <div class="top-exchange-rate-title">

                            <h6>{{__('Services Types')}}</h6>

                            <input type="text" placeholder="{{__('Search')}}"
                                   id="searchInServicesType" onkeyup="searchInServicesTypes()" class="form-control">
                        </div>
                        <div class="top-exchange-rate-body">
                            <div class="">

                                <ul class="nav nav-pills nav-stacked right-list-wg anyClass-3 wg-img-bg list-inline">
                                    <div class="searchResultServicesTypes" id="services_types">

                                        <li style="font-size: 12px;padding:10px" class="bg-danger nav-item hvr-shrink">
                                            <a class="nav-link" href="#"
                                               onclick="getServiceById('all')" id="services_types">
                                                {{__('All')}} <i class="fa fa-search"></i>
                                            </a>
                                        </li>


                                        @foreach($servicesTypes as $serviceType)
                                            <li style="font-size: 12px;padding:10px" class="bg-danger nav-item hvr-shrink">
                                                <a class=" nav-link" href="#"
                                                   onclick="getServiceById('{{$serviceType->id}}')" id="service_type_value">
                                                    {{$serviceType->name}} <i class="fa fa-search"></i>
                                                </a>
                                            </li>
                                        @endforeach
                                    </div>
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>

            </div>


            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">


                <div class="widget-content widget-content-area h-100 br-4 p-0">
                    <div class="top-exchange-rate">

                        <div class="top-exchange-rate-title">
                            <h6>{{__('Services')}}</h6>

                            <input type="text" id="searchInServicesData" onkeyup="searchInServiceData()"
                                   placeholder="{{__('Search')}}" class="form-control">
                        </div>

                        <div class="top-exchange-rate-body">
                            <div class="">

                                <ul class="nav nav-pills nav-stacked right-list-wg anyClass-3 d-flex-wg-left wg-list" id="services_data">

                                    <div style="text-align: center; display: none; margin-top: 66px; "
                                         id="services_by_type_loading">

                                        <a class="example-image-link" href="{{asset('default-images/loading.gif')}}"
                                           data-lightbox="example-1">

                                            <img class="example-image"
                                                 src="{{asset('default-images/loading.gif')}}" id="output_image"/>

                                            <div class="frame"></div>
                                        </a>

                                    </div>


                                    @foreach($services as $service)
                                        <li class="nav-item bg-blue text-white pakage-item hvr-shrink remove_ajax_services">

                                            <a style="font-size: 12px"
                                               onclick="getServiceDetails('{{$service->id}}')" href="#" id="part_details">
                                                {{$service->name}}<i class="fa fa-plus"></i>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

        </div>
        @endif

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 products-details-wg">
            <div class="form-group has-feedback">
                <div class="anyClass-wg table-responsive">
                    <table id="invoiceItemsDetails" class="table table-bordered" style="width:100%">
                        @include('admin.work_cards.invoices.sample.services.table-footer')

                        <thead style="background-color: #ada3a361">
                        <tr>
                            <th scope="col">{!! __('Name') !!}</th>
                            <th scope="col">{!! __('quantity') !!}</th>
                            <th scope="col">{!! __('Price') !!}</th>
                            <th scope="col">{!! __('Discount Type') !!}</th>
                            <th scope="col">{!! __('Discount') !!}</th>
                            <th scope="col">{!! __('Total') !!}</th>
                            <th scope="col">{!! __('Total After Discount') !!}</th>
                            <th scope="col">{!! __('Action') !!}</th>
                        </tr>
                        </thead>
                        <tbody id="add_service_details">

                        @if(isset($data['cardInvoiceServiceType']))

                            @foreach($data['cardInvoiceServiceType']->items as $invoiceServiceIndex => $invoice_service_item)

                                @php
                                    $invoiceServiceIndex +=1;
                                @endphp

                                @include('admin.work_cards.invoices.sample.services.edit_service_details')

                                <input type="hidden" value="{{$invoice_service_item->model_id}}" id="service-{{$invoiceServiceIndex}}">

                            @endforeach
                        @endif
                        </tbody>

                    </table>

                    <input type="hidden" id="services_items_count"
                           value="{{isset($data['cardInvoiceServiceType']) ? $data['cardInvoiceServiceType']->items->count() : 0}}">
                </div>
            </div>
        </div>
    </div>
</div>

{{--</div>--}}
