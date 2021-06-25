@if(invoiceSetting())
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 products-details-wg">

        {{-- MAIN PARTS TYPES --}}
        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
            <div class="widget-content widget-content-area h-100">
                <div class="top-exchange-rate">

                    <div class="top-exchange-rate-title">
                        <h6>{{__('Main Spare Parts Type')}}</h6>
                    </div>

                    <input onkeyup="searchInMainPartsType()" type="text" placeholder="{{__('Search')}}"
                           id="searchInSparePartsType" class="form-control">

                    <div class="top-exchange-rate-body">
                        <div class="table-responsive top-exchange-rate-scroll searchResultSparePartsType">
                            <table class="table">
                                <tbody>
                                <tr>
                                    <td class="align-center col-xs-3">
                                        <div class="card">
                                            <div class="card__image-holder">
                                                <a class="example-image-link"
                                                   href="{{url('default-images/defualt.png')}}"
                                                   data-lightbox="example-1">
                                                    <img class="example-image" src="{{url('default-images/defualt.png')}}" id="output_image"/>

                                                    <div class="frame"></div>
                                                </a>
                                            </div>
                                            <a class="nav-link active" onclick="getSubPartsTypes('all', 'main_type')"
                                               id="spare_part_type" style="cursor: pointer">
                                                <div class="card-title">
                                                    <h2 class="text-center" style="font-size: 12px">
                                                        {{__('All')}}
                                                        <i class="fa fa-search"></i>
                                                    </h2>
                                                </div>
                                            </a>
                                        </div>
                                    </td>

                                    @foreach($partsTypes as $partsType)
                                        @if(!$partsType->spare_part_id)
                                            <td class="align-center col-xs-3">
                                                <div class="card">
                                                    <div class="card__image-holder">
                                                        <a class="example-image-link" href="{{$partsType->img}}"
                                                           data-lightbox="example-1">
                                                            <img class="example-image" src="{{$partsType->img}}" id="output_image"/>
                                                            <div class="frame"></div>
                                                        </a>
                                                    </div>
                                                    <a class="nav-link active" style="cursor: pointer"
                                                       onclick="getSubPartsTypes({{$partsType->id}}, 'main_type')"
                                                       id="spare_part_type">
                                                        <div class="card-title">
                                                            <h2 class="text-center" style="font-size: 12px">
                                                                {{$partsType->type}}
                                                                <i class="fa fa-search"></i>
                                                            </h2>
                                                        </div>
                                                    </a>
                                                </div>
                                            </td>
                                        @endif
                                    @endforeach
                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{--   SUB SPARE PARTS TYPES     --}}
        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
            <div class="widget-content widget-content-area h-100">
                <div class="top-exchange-rate">

                    <div class="top-exchange-rate-title">
                        <h6>{{__('Sub Spare Parts Type')}}</h6>
                    </div>

                    <input onkeyup="searchInSubPartsType()" type="text" placeholder="{{__('Search')}}"
                           id="searchInSubSparePartsType" class="form-control">

                    <div class="top-exchange-rate-body">
                        <div class="table-responsive top-exchange-rate-scroll searchResultSubSparePartsType">
                            <table class="table">
                                <tbody>
                                <tr id="sub_parts_types_area">
                                    @foreach($partsTypes as $partsType)
                                        @if($partsType->spare_part_id)
                                            <td class="align-center col-xs-3">
                                                <div class="card">
                                                    <div class="card__image-holder">
                                                        <a class="example-image-link" href="{{$partsType->img}}"
                                                           data-lightbox="example-1">
                                                            <img class="example-image" src="{{$partsType->img}}"
                                                                 id="output_image"/>
                                                            <div class="frame"></div>
                                                        </a>
                                                    </div>
                                                    <a class="nav-link active" style="cursor: pointer"
                                                       onclick="getSubPartsTypes({{$partsType->id}}, 'sub_type')"
                                                       id="spare_part_type">
                                                        <div class="card-title">
                                                            <h2 class="text-center" style="font-size: 12px">
                                                                {{$partsType->type}}
                                                                <i class="fa fa-search"></i>
                                                            </h2>
                                                        </div>
                                                    </a>
                                                </div>
                                            </td>
                                        @endif
                                    @endforeach
                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- PARTS   --}}
        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">


            <div class="widget-content widget-content-area h-100 br-4 p-0">
                <div class="top-exchange-rate">
                    <div class="top-exchange-rate-title">
                        <h6>{{__('Spare Parts')}}</h6>
                        <input onkeyup="partsSearch()" type="text" id="searchInParts"
                               placeholder="{{__('Search')}}"
                               class="form-control">

                    </div>
                    <div class="top-exchange-rate-body">
                        <div class="table-responsive top-exchange-rate-scroll searchResultSpareParts">
                            <table class="table">
                                <tbody>

                                <tr class="" id="parts_area">

                                    @foreach($parts as $part)

                                        <td class="align-center col-xs-3">
                                            <div class="card">
                                                <div class="card__image-holder">
                                                    <a class="example-image-link" href="{{$part->image}}"
                                                       data-lightbox="example-1">
                                                        <img class="example-image" src="{{$part->image}}"
                                                             id="output_image"/>
                                                        <div class="frame"></div>
                                                    </a>
                                                </div>
                                                <a class="nav-link active"
                                                   onclick="getPartsDetails('{{$part->id}}')" href="#" id="part_details">
                                                    <div class="card-title">
                                                        <h2 class="text-center h2-inv"
                                                            style="font-size: 12px !important">
                                                            {{$part->name}} <p
                                                                style="display: none">{{$part->barcode}}</p>
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
@endif
