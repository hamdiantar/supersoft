<div id="ajax_parts_box">
    <div class="row">

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 products-details-wg">

                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                    <div class="widget-content widget-content-area h-100 br-4 p-0">
                        <div class="top-exchange-rate">

                            <div class="top-exchange-rate-title">
                                <h6>{{__('Spare Parts Type')}}</h6>
                                <input type="text" placeholder="{{__('Search')}}" id="searchInSparePartsType"
                                       onkeyup="searchInPartsTypesItems()" class="form-control">
                            </div>


                            <div class="top-exchange-rate-body">
                                <div class="table-responsive top-exchange-rate-scroll searchResultSparePartsType" >

                                    <table class="table">
                                        <tbody>
                                        <tr class="">

                                            <td class="align-center col-xs-3">

                                                <div class="card">

                                                    <div class="card__image-holder">

                                                        <a class="example-image-link"
                                                           href="{{url('default-images/defualt.png')}}"
                                                           data-lightbox="example-1">

                                                            <img class="example-image"
                                                                 src="{{url('default-images/defualt.png')}}"
                                                                 id="output_image"/>

                                                            <div class="frame"></div>
                                                        </a>

                                                    </div>

                                                    <a class="nav-link active" href="#" onclick="getSparePartsById('all')"
                                                       id="spare_part_type">
                                                        <div class="card-title">
                                                            <h2 class="text-center" style="font-size: 12px">
                                                                {{__('All')}}
                                                                <i class="fa fa-search"></i>
                                                            </h2>
                                                        </div>
                                                    </a>
                                                </div>

                                            </td>
                                            @foreach($sparPartsTypes as $part)
                                                @php
                                                    $imageUrl = $part->image ? url('storage/images/spare-parts/'.$part->image) : url('default-images/defualt.png');
                                                @endphp
                                                <td class="col-xs-3">

                                                    <div class="card">
                                                        <div class="card__image-holder">
                                                            <a class="example-image-link" href="{{$imageUrl}}"
                                                               data-lightbox="example-1">
                                                                <img class="example-image"
                                                                     src="{{$imageUrl}}" id="output_image"/>

                                                                <div class="frame"></div>
                                                            </a>
                                                        </div>

                                                        <a class="nav-link active" href="#"
                                                           onclick="getSparePartsById({{$part->id}})" id="spare_part_type">
                                                            <div class="card-title">
                                                                <h2 class="text-center" style="font-size: 12px">
                                                                    {{$part->type}}
                                                                    <i class="fa fa-search"></i>
                                                                </h2>
                                                            </div>
                                                        </a>
                                                    </div>

                                                </td>
                                            @endforeach
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>


                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">


                    <div class="widget-content widget-content-area h-100 br-4 p-0">
                        <div class="top-exchange-rate">

                            <div class="top-exchange-rate-title">
                                <h6>{{__('Spare Parts')}}</h6>
                                <input type="text" id="searchInParts" onkeyup="searchInPartsItems()" placeholder="{{__('Search')}}"
                                       class="form-control">

                            </div>
                            <div class="top-exchange-rate-body">
                                <div class="table-responsive top-exchange-rate-scroll">
                                    <table class="table search_in_parts">
                                        <tbody>

                                        <tr class="" id="add_parts">
                                            @foreach($parts as $part)
                                                @php
                                                    $imageUrl = $part->img ? url('storage/images/parts/'.$part->img) : url('default-images/defualt.png');
                                                @endphp
                                                <td class="align-center col-xs-3">
                                                    <div class="card">
                                                        <div class="card__image-holder">
                                                            <a class="example-image-link" href="{{$imageUrl}}"
                                                               data-lightbox="example-1">
                                                                <img class="example-image"
                                                                     src="{{$imageUrl}}" id="output_image"/>

                                                                <div class="frame"></div>
                                                            </a>

                                                        </div>
                                                        <a class="nav-link active"
                                                           onclick="getPartsDetails('{{$part->id}}')"
                                                           href="#" id="part_details">
                                                            <div class="card-title">
                                                                <h2 class="text-center" style="font-size: 12px">
                                                                    {{$part->name}}
                                                                    <i class="fa fa-plus"></i>
                                                                </h2>
                                                                <h2 style="font-size: 20px; display: none;">{{$part->barcode}}
                                                                    <i class="fa fa-plus"></i>
                                                                </h2>
                                                            </div>
                                                        </a>
                                                    </div>

                                                </td>
                                            @endforeach

                                        </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

            </div>

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 products-details-wg">
            <div class="form-group has-feedback">
                <div class="anyClass-wg table-responsive">
                    <table id="invoiceItemsDetails" class="table table-bordered" style="width:100%">
                        @include('web.quotations.parts.table-footer')
                        <thead style="background-color: #ada3a361">
                        <tr>
                            <th scope="col">{!! __('Name') !!}</th>
                            <th scope="col">{!! __('Invoices') !!}</th>
{{--                            <th scope="col">{!! __('Available quantity') !!}</th>--}}
                            <th scope="col">{!! __('Sold quantity') !!}</th>
                            {{--                                        <th scope="col">{!! __('last purchase price') !!}</th>--}}
                            <th scope="col">{!! __('selling price') !!}</th>
                            <th scope="col">{!! __('Discount Type') !!}</th>
                            <th scope="col">{!! __('Discount') !!}</th>
                            <th scope="col">{!! __('Total') !!}</th>
                            <th scope="col">{!! __('Total After Discount') !!}</th>
                            <th scope="col">{!! __('Action') !!}</th>
                        </tr>
                        </thead>
                        <tbody id="add_parts_details">
                        </tbody>

                    </table>
                </div>
                <input type="hidden" name="parts_items_count" id="parts_items_count" value="0">
            </div>
        </div>

    </div>
</div>
