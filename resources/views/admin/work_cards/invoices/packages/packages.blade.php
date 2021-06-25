<!-- {{--<div class=" form-group col-md-12" >--}}
        <div class="box-content card bordered-all blue-1 js__card" id="packages_section"> -->
<!-- <h4 class="box-title bg-blue-1 with-control">
                {{__('packages Details')}}
        <span class="controls">
        <button type="button" class="control fa fa-minus js__card_minus"></button>
        <button type="button" class="control fa fa-times js__card_remove"></button>
    </span>
    </h4>
    <div class="card-content js__card_content" style=""> -->
<div id="ajax_package_box">

    <div class="row">
        {{--            <div class="col-md-6">--}}
        {{--                <h3>{{__('Services Types')}}</h3>--}}
        {{--                <input type="text" placeholder="{{__('Search')}}" id="searchInServicesType"--}}
        {{--                       onkeyup="searchInServicesTypes()" class="form-control">--}}
        {{--                <ul class="nav nav-pills nav-stacked anyClass form-control" style="text-align: center">--}}
        {{--                    <div class="searchResultServicesTypes">--}}
        {{--                        <li class="nav-item">--}}
        {{--                            <a class="nav-link active" href="#" onclick="getServiceById('all')"--}}
        {{--                               id="services_types">--}}
        {{--                                <div class="card">--}}
        {{--                                    <div class="card-title">--}}
        {{--                                        <h2 style="font-size: 20px">--}}
        {{--                                            {{__('All')}}--}}
        {{--                                            <i class="fa fa-search"></i>--}}
        {{--                                        </h2>--}}
        {{--                                    </div>--}}
        {{--                                </div>--}}
        {{--                            </a>--}}
        {{--                        </li>--}}
        {{--                        <hr>--}}
        {{--                        @foreach($servicesTypes as $serviceType)--}}
        {{--                            <li class="nav-item">--}}
        {{--                                <a class="nav-link active" href="#"--}}
        {{--                                   onclick="getServiceById({{$serviceType->id}})" id="service_type_value">--}}
        {{--                                    <div class="card">--}}
        {{--                                        <div class="card-title">--}}
        {{--                                            <h2 style="font-size: 20px">--}}
        {{--                                                {{$serviceType->name}}--}}
        {{--                                                <i class="fa fa-search"></i>--}}
        {{--                                            </h2>--}}
        {{--                                        </div>--}}
        {{--                                    </div>--}}
        {{--                                </a>--}}
        {{--                            </li>--}}
        {{--                            <hr>--}}
        {{--                        @endforeach--}}
        {{--                    </div>--}}
        {{--                </ul>--}}
        {{--            </div>--}}

        <div class="col-md-12">
            @if(invoiceSetting())
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 products-details-wg">
                    <div class="widget-content widget-content-area h-100 br-4 p-0">
                        <div class="top-exchange-rate">

                            <div class="top-exchange-rate-title">

                                <h6>{{__('Packages')}}</h6>

                                <input type="text" id="searchInPackagesData_{{$maintenance->id}}"
                                       onkeyup="searchInPackagesData({{$maintenance->id}})"
                                       placeholder="{{__('Search')}}" class="form-control"
                                >

                            </div>
                            <div class="top-exchange-rate-body">

                                <ul class="nav nav-pills nav-stacked right-list-wg anyClass d-flex-wg-all wg-img-bg list-inline"
                                    id="packages_data_{{$maintenance->id}}" class="form-control">

                                    <div style="text-align: center; display: none; margin-top: 66px; "
                                         id="packages_by_type_loading_{{$maintenance->id}}">
                                        <img src="{{asset('default-images/loading.gif')}}"
                                             style="height: 45px;width: 45px;">
                                    </div>

                                    <div>
                                        @foreach($packages as $package)

                                            <li style="font-size: 12px;padding:10px; margin: 5px;"
                                                class="bg-danger nav-item hvr-shrink col-lg-3  remove_ajax_services_{{$maintenance->id}}">

                                                <a style="font-size: 12px;padding:0" class="nav-link active"
                                                   onclick="getPackageDetails('{{$package->id}}', '{{$maintenance->id}}',{{$maintenance_type->id}})"
                                                   href="#" id="part_details_{{$maintenance->id}}">

                                                    {{$package->name}} <i class="fa fa-plus"></i>

                                                </a>
                                            </li>

                                        @endforeach
                                    </div>

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 products-details-wg">
                <div class="form-group has-feedback col-sm-12">


                <!-- <h3 style="text-align: center;">{{__('Packages Details')}}</h3> -->
                    <div class="table-responsive class-scroll-wg">
                        <table id="invoiceItemsDetails_{{$maintenance->id}}" class="table table-bordered"
                               style="width:100%">
                            <thead style="background-color: #ada3a361">
                            <tr>
                                <th scope="col">{!! __('Name') !!}</th>
                                <th scope="col">{!! __('Package quantity') !!}</th>
                                <th scope="col">{!! __('Package Price') !!}</th>
                                <th scope="col">{!! __('Discount Type') !!}</th>
                                <th scope="col">{!! __('Discount') !!}</th>
                                <th scope="col">{!! __('Total Before Discount') !!}</th>
                                <th scope="col">{!! __('Total After Discount') !!}</th>
                                <th scope="col">{!! __('Action') !!}</th>
                            </tr>
                            </thead>
                            <tbody id="add_package_details_{{$maintenance->id}}">
                            @if($invoicePackageType)
                                @foreach($invoicePackageType->items as  $invoicePackageIndex => $invoice_package_item)

                                    @include('admin.work_cards.invoices.packages.edit_package_details')

                                    <input type="hidden" value="{{$invoice_package_item->model_id}}"
                                           id="package-{{$invoicePackageIndex+1}}-{{$maintenance->id}}">

                                @endforeach
                            @endif
                            </tbody>
                            @include('admin.work_cards.invoices.packages.table-footer')
                        </table>

                        <input type="hidden" id="packages_items_count_{{$maintenance->id}}"
                               value="{{$invoicePackageType ? $invoicePackageType->items->count() : 0}}">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
