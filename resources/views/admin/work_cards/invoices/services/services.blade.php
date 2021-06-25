<!-- {{--<div class=" form-group col-md-12" >--}}
        <div class="box-content card bordered-all blue-1 js__card" id="services_section">

                <h4 class="box-title bg-blue-1 with-control">
{{__('Services Details')}}
        <span class="controls">
            <button type="button" class="control fa fa-minus js__card_minus"></button>
            <button type="button" class="control fa fa-times js__card_remove"></button>
        </span>
    </h4>
    <div class="card-content js__card_content" style="">

-->
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
                                   id="searchInServicesType_{{$maintenance->id}}"
                                   onkeyup="searchInServicesTypes({{$maintenance->id}})" class="form-control">
                        </div>
                        <div class="top-exchange-rate-body">
                            <div class="">

                                <ul class="nav nav-pills nav-stacked right-list-wg anyClass-3 wg-img-bg list-inline">
                                    <div class="searchResultServicesTypes" id="services_types_{{$maintenance->id}}">

                                        <li style="font-size: 12px;padding:10px" class="bg-danger nav-item hvr-shrink">
                                            <a class="nav-link" href="#"
                                               onclick="getServiceById('all',{{$maintenance->id}})"
                                               id="services_types_{{$maintenance->id}}">
                                                {{__('All')}}
                                                <i class="fa fa-search"></i>

                                            </a>
                                        </li>


                                        @foreach($servicesTypes as $serviceType)
                                            <li style="font-size: 12px;padding:10px"
                                                class="bg-danger nav-item hvr-shrink">
                                                <a class=" nav-link" href="#"
                                                   onclick="getServiceById('{{$serviceType->id}}',{{$maintenance->id}})"
                                                   id="service_type_value">

                                                    {{$serviceType->name}}
                                                    <i class="fa fa-search"></i>

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

                            <input type="text" id="searchInServicesData_{{$maintenance->id}}"
                                   onkeyup="searchInServicesData({{$maintenance->id}})"
                                   placeholder="{{__('Search')}}" class="form-control"
                            >
                        </div>

                        <div class="top-exchange-rate-body">
                            <div class="">

                                <ul class="nav nav-pills nav-stacked right-list-wg anyClass-3 d-flex-wg-left wg-list"
                                    id="services_data_{{$maintenance->id}}">

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
                                        <li class="nav-item bg-blue text-white pakage-item hvr-shrink remove_ajax_services_{{$maintenance->id}}">

                                            <a style="font-size: 12px" class=" "
                                               onclick="getServiceDetails('{{$service->id}}','{{$maintenance->id}}',{{$maintenance_type->id}})"
                                               href="#" id="part_details_{{$maintenance->id}}">

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
                    <table id="invoiceItemsDetails_{{$maintenance->id}}" class="table table-bordered"
                           style="width:100%">
                        @include('admin.work_cards.invoices.services.table-footer')

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
                        <tbody id="add_service_details_{{$maintenance->id}}">
                        @if($invoiceServiceType)
                            @foreach($invoiceServiceType->items as  $invoiceServiceIndex => $invoice_service_item)

                                @include('admin.work_cards.invoices.services.edit_service_details')

                                <input type="hidden" value="{{$invoice_service_item->model_id}}"
                                       id="service-{{$invoiceServiceIndex+1}}-{{$maintenance->id}}">

                            @endforeach
                        @endif
                        </tbody>

                    </table>

                    <input type="hidden" id="services_items_count_{{$maintenance->id}}"
                           value="{{$invoiceServiceType ? $invoiceServiceType->items->count() : 0}}">
                </div>
            </div>
        </div>
    </div>
</div>

{{--</div>--}}
