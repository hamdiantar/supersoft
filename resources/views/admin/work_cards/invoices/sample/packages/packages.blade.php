
<div id="ajax_package_box">

    <div class="row">

        <div class="col-md-12">
            @if(invoiceSetting())
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 products-details-wg">
                    <div class="widget-content widget-content-area h-100 br-4 p-0">
                        <div class="top-exchange-rate">

                            <div class="top-exchange-rate-title">

                                <h6>{{__('Packages')}}</h6>

                                <input type="text" id="searchInPackagesData" onkeyup="searchInPackageData()"
                                       placeholder="{{__('Search')}}" class="form-control"
                                >

                            </div>
                            <div class="top-exchange-rate-body">

                                <ul class="nav nav-pills nav-stacked right-list-wg anyClass d-flex-wg-all wg-img-bg list-inline"
                                    id="packages_data" class="form-control">

                                    <div style="text-align: center; display: none; margin-top: 66px; " id="packages_by_type_loading">
                                        <img src="{{asset('default-images/loading.gif')}}" style="height: 45px;width: 45px;">
                                    </div>

                                    <div>
                                        @foreach($packages as $package)

                                            <li style="font-size: 12px;padding:10px; margin: 5px;"
                                                class="bg-danger nav-item hvr-shrink col-lg-3  remove_ajax_services">

                                                <a style="font-size: 12px;padding:0" class="nav-link active"
                                                   onclick="getPackageDetails('{{$package->id}}')" href="#" id="part_details">
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
                        <table id="invoiceItemsDetails" class="table table-bordered"
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
                            <tbody id="add_package_details">
                            @if(isset($data['cardInvoicePackageType']))
                                @foreach($data['cardInvoicePackageType']->items as  $invoicePackageIndex => $invoice_package_item)

                                    @php
                                        $invoicePackageIndex +=1;
                                    @endphp

                                    @include('admin.work_cards.invoices.sample.packages.edit_package_details')

                                    <input type="hidden" value="{{$invoice_package_item->model_id}}"
                                           id="package-{{$invoicePackageIndex}}">

                                @endforeach
                            @endif
                            </tbody>
                            @include('admin.work_cards.invoices.sample.packages.table-footer')
                        </table>

                        <input type="hidden" id="packages_items_count"
                               value="{{isset($data['cardInvoicePackageType']) ? $data['cardInvoicePackageType']->items->count() : 0}}">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
