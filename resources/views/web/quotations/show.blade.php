<div class=" small-spacing" id="quotation">
    <div class=" print-wg-fatora">
        <div class="row">
            <div class="col-xs-4">

                <div style="text-align: right ">
                    <h5><i class="fa fa-home"></i> {{optional($quotation->branch)->name_ar}}</h5>
                    <h5><i class="fa fa-phone"></i> {{optional($quotation->branch)->phone1}} </h5>
                    <h5><i class="fa fa-globe"></i> {{optional($quotation->branch)->address}} </h5>
                    <h5><i class="fa fa-fax"></i> {{optional($quotation->branch)->fax}}</h5>
                    <h5><i class="fa fa-adjust"></i> {{optional($quotation->branch)->tax_card}}</h5>
                </div>
            </div>
            <div class="col-xs-4">

                <img class="text-center center-block" style="width: 100px; height: 100px;margin-top:20px"
                     src="{{isset($quotation->branch->logo) ? asset('storage/images/branches/'.$branchToPrint->logo) : env('DEFAULT_IMAGE_PRINT')}}">
            </div>
            <div class="col-xs-4">

                <div style="text-align: left" class="my-1">
                    <h5>{{optional($quotation->branch)->name_en}} <i class="fa fa-home"></i></h5>
                    <h5>{{optional($quotation->branch)->phone1}} <i class="fa fa-phone"></i></h5>
                    <h5>{{optional($quotation->branch)->address}} <i class="fa fa-globe"></i></h5>
                    <h5>{{optional($quotation->branch)->fax}} <i class="fa fa-fax"></i></h5>
                    <h5>{{optional($quotation->branch)->tax_card}} <i class="fa fa-adjust"></i></h5>
                </div>
            </div>
        </div>
    </div>


    <h4 class="text-center">{{__('Quotation')}}</h4>

    <div class="wg-tb-snd" style="border:1px solid #AAA;margin:5px 20px 20px;padding:10px;border-radius:5px">

        <div class="row">
            <div class="col-xs-4">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Quotation Number')}}</th>
                        <td>{{$quotation->quotation_number}}</td>
                    </tr>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Date')}}</th>
                        <td>{{$quotation->date}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-xs-4">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Time')}}</th>
                        <td>{{$quotation->time}}</td>
                    </tr>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('User Name')}}</th>
                        <td>{{auth()->guard('customer')->user()->name}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-xs-4">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Customer Name')}}</th>
                        <td>{{optional($quotation->customer)->name}}</td>
                    </tr>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Customer Phone')}}</th>
                        <td>{{optional($quotation->customer)->phone1 ?? optional($quotation->customer)->phone2}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@if($partType)
    <div class="col-xs-12 wg-tb-snd">
        <div style="margin:10px 15px">
        <!-- <h2 style="text-align: center">{{__('sparts')}}</h2> -->
            <table class="table table-bordered">
                <thead>
                <tr class="heading">
                    <th style="background:#CCC !important;color:black">{{__('Unit')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Type')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('spart')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Sold Quantity')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Selling Price')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Discount Type')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Discount')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Total')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Total After Discount')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($partType->items as $item)

                    <tr class="item">
                        <td>{{optional($item->part->sparePartsUnit)->unit}}</td>
                        <td>{{optional($item->part->sparePartsType)->type}}</td>
                        <td>{{optional($item->part)->name}}</td>
                        <td>{{$item->qty}}</td>
                        <td>{{$item->price}}</td>
                        <td>{{__($item->discount_type)}}</td>
                        <td>{{$item->discount}}</td>
                        <td>{{number_format($item->sub_total, 2)}}</td>
                        <td>{{number_format($item->total_after_discount, 2)}}</td>
                    </tr>
                @endforeach
                </tbody>

            </table>
        </div>
    </div>
@endif

@if($quotationServiceType)
    <div class="col-xs-12 wg-tb-snd">
        <div style="margin:10px 15px">
        <!-- <h2 style="text-align: center">{{__('Services')}}</h2> -->
            <table class="table table-bordered">
                <thead>
                <tr class="heading">
                    <th style="background:#CCC !important;color:black">{{__('Service Name')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Quantity')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Price')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Discount Type')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Discount')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Total')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Total After Discount')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($quotationServiceType->items as $index=>$service_item)
                    @php
                        $service = $service_item->service;
                    @endphp
                    <tr class="item">
                        <td>{{$service->name}}</td>
                        <td>{{$service_item->price}}</td>
                        <td>{{$service_item->qty}}</td>
                        <td>{{__($service_item->discount_type)}}</td>
                        <td>{{$service_item->discount}}</td>
                        <td>{{number_format($service_item->sub_total, 2)}}</td>
                        <td>{{number_format($service_item->total_after_discount, 2)}}</td>
                    </tr>
                @endforeach
                </tbody>

            </table>
        </div>
    </div>
@endif

@if($packageType)
    <div class="col-xs-12 wg-tb-snd">
        <div style="margin:10px 15px">
        <!-- <h2 style="text-align: center">{{__('Packages')}}</h2> -->
            <table class="table table-bordered">
                <thead>
                <tr class="heading">
                    <th style="background:#CCC !important;color:black">{{__('Package Name')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Quantity')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Price')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Discount Type')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Discount')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Total')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Total After Discount')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($packageType->items as $index=>$package_item)
                    @php
                        $package = $package_item->package;
                    @endphp
                    <tr class="item">
                        <td>{{$package->name}}</td>
                        <td>{{$package_item->price}}</td>
                        <td>{{$package_item->qty}}</td>
                        <td>{{__($package_item->discount_type)}}</td>
                        <td>{{$package_item->discount}}</td>
                        <td>{{number_format($package_item->sub_total, 2)}}</td>
                        <td>{{number_format($package_item->total_after_discount, 2)}}</td>
                    </tr>
                @endforeach
                </tbody>

            </table>
        </div>
    </div>
@endif

@if($quotationWinchType && $quotationWinchType->winchRequest)
    <div class="col-xs-12 wg-tb-snd">
        <div style="margin:10px 15px">
        <!-- <h2 style="text-align: center">{{__('Services')}}</h2> -->
            <table class="table table-bordered">
                <thead>
                <tr class="heading">
                    <th style="background:#CCC !important;color:black">{{__('Branch lat')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Branch long')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Request lat')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Request long')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Distance')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Price')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('SubTotal')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Discount Type')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Discount')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Total')}}</th>
                </tr>
                </thead>
                <tbody>
                    <tr class="item">
                        <td>{{$quotationWinchType->winchRequest->branch_lat}}</td>
                        <td>{{$quotationWinchType->winchRequest->branch_long}}</td>
                        <td>{{$quotationWinchType->winchRequest->request_lat}}</td>
                        <td>{{$quotationWinchType->winchRequest->request_long}}</td>
                        <td>{{$quotationWinchType->winchRequest->distance}}</td>
                        <td>{{$quotationWinchType->winchRequest->price}}</td>
                        <td>{{$quotationWinchType->winchRequest->sub_total}}</td>
                        <td>{{__($quotationWinchType->winchRequest->discount_type)}}</td>
                        <td>{{$quotationWinchType->winchRequest->discount}}</td>
                        <td>{{number_format($quotationWinchType->winchRequest->total, 2)}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endif

<div class="col-xs-12 wg-tb-snd">
    <div style="margin:10px 15px">
    <!-- <h2 style="text-align: center">{{__('Taxes')}}</h2> -->
        <table class="table table-bordered">
            <thead>
            <tr class="heading">
                <th style="background:#CCC !important;color:black">{{__('Tax Name')}}</th>
                <th style="background:#CCC !important;color:black">{{__('Tax Type')}}</th>
                <th style="background:#CCC !important;color:black">{{__('Tax Value')}}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($taxes as $tax)
                <tr class="item">
                    <td>{{$tax->name}}</td>
                    <td>{{__($tax->tax_type)}}</td>
                    <td>{{$tax->value}}</td>
                </tr>
            @endforeach
            <tr class="item">
                <th style="background:#CCC !important;color:black" colspan="2">{{__('Total Tax')}}</th>
                <td>{{$totalTax}}</td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="wg-tb-snd" style="border:1px solid #AAA;margin:5px 20px 20px;padding:10px;border-radius:5px">

<!-- <h4 class="text-center" style="font-weight:bold;margin-bottom:15px">{{__('Final Deatils')}}</h4> -->
    <div class="row">

        <div class="col-xs-4">
            <table class="table table-bordered">
                <tbody>
                <tr>
                    <th style="background:#CCC !important;color:black" scope="row">{{__('Discount Type')}}</th>
                    <td>{{__($quotation->discount_type)}}</td>
                </tr>

                <tr>
                    <th style="background:#CCC !important;color:black" scope="row">{{__('Discount')}} </th>
                    <td>{{$quotation->discount}}</td>
                </tr>

                </tbody>
            </table>
        </div>

        <div class="col-xs-8">
            <table class="table table-bordered">
                <tbody>
                <tr>
                    <th style="background:#CCC !important;color:black" scope="row">{{__('Total in Numbers')}}</th>
                    <td>{{$quotation->total}}</td>
                </tr>
                <tr>
                <!-- <th style="background:#CCC !important;color:black" scope="row">{{__('Total in letters')}}</th> -->
                    <td id="totalInLetters">{{$quotation->total}}</td>
                </tr>

                </tbody>
            </table>
        </div>
    </div>

    {{-- Seetings   --}}

    <div>
        @if($setting)
            <hr>
            {!! $setting->quotationTerms !!}
        @endif
    </div>
</div>

