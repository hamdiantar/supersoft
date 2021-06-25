<div class="col-md-2">
    <div class="form-group">

        <label for="inputQuantity" class="control-label">{{__('Quantity')}}</label>

        <div class="input-group">
            <span class="input-group-addon"><li class="fa fa-cube"></li></span>

            <input type="text" disabled class="form-control" value="{{$price->quantity}}" >
        </div>
    </div>
</div>

<div class="col-md-1" style="padding-top: 33px;">
    <span style="color: white; margin-top:-5px; margin-right:-10px;" class="default_unit_title part-unit-span"> {{isset($price) ? $price->unit->unit :''}} </span>
</div>
<div class="col-md-3">
    <div class="form-group has-feedback">
        <label for="inputUnits" class="control-label">{{__('Unit')}}</label>
        <div class="input-group">
            <span class="input-group-addon fa fa-clone"></span>
            <input type="text" disabled class="form-control" value="{{optional($price->unit)->unit}}" >
        </div>
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label for="inputQuantity" class="control-label">{{__('Selling Price')}}</label>
        <div class="input-group">
            <span class="input-group-addon"><li class="fa fa-money"></li></span>
            <input type="text"  value="{{$price->selling_price}}" class="form-control" disabled>
        </div>
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label for="inputPurchasePrice" class="control-label">{{__('purchase price')}}</label>
        <div class="input-group">
            <span class="input-group-addon"><li class="fa fa-money"></li></span>

            <input type="text"  value="{{$price->purchase_price}}" class="form-control" disabled>
        </div>
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label for="inputLess" class="control-label">{{__('Less Selling Price')}}</label>
        <div class="input-group">
            <span class="input-group-addon"><li class="fa fa-money"></li></span>

            <input type="text"  value="{{$price->less_selling_price }}" class="form-control" disabled>

        </div>
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label for="inputServices" class="control-label">{{__('Service selling price')}}</label>
        <div class="input-group">
            <span class="input-group-addon"><li class="fa fa-money"></li></span>

            <input type="text"  value="{{$price->service_selling_price }}" class="form-control" disabled>

        </div>

    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label for="inputServices" class="control-label">{{__('Less Service selling price')}}</label>
        <div class="input-group">
            <span class="input-group-addon"><li class="fa fa-money"></li></span>

            <input type="text"  value="{{$price->less_service_selling_price }}" class="form-control" disabled>

        </div>
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label for="inputMaxQuantity" class="control-label">{{__('Maximum sale amount')}}</label>
        <div class="input-group">
            <span class="input-group-addon"><li class="fa fa-money"></li></span>

            <input type="text"  value="{{$price->maximum_sale_amount }}" class="form-control" disabled>
        </div>
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label for="inputMinimumQuantity" class="control-label">{{__('Minimum for order')}}</label>
        <div class="input-group">
            <span class="input-group-addon"><li class="fa fa-cube"></li></span>

            <input type="text"  value="{{$price->minimum_for_order }}" class="form-control" disabled>

        </div>
    </div>

</div>

<div class="col-md-3">

    <div class="form-group">
        <label for="inputBigDiscount" class="control-label">{{__('Biggest percent discount')}}</label>
        <div class="input-group">
            <span class="input-group-addon"><li class="fa fa-money"></li></span>

            <input type="text"  value="{{$price->biggest_percent_discount }}" class="form-control" disabled>
        </div>
    </div>
</div>

<div class="col-md-3">

    <div class="form-group">
        <label for="inputBigAmountDiscount" class="control-label">{{__('Biggest amount discount')}}</label>
        <div class="input-group">
            <span class="input-group-addon"><li class="fa fa-money"></li></span>

            <input type="text"  value="{{$price->biggest_amount_discount }}" class="form-control" disabled>
        </div>
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label for="inputLastSelling" class="control-label">{{__('Last selling price')}}</label>
        <div class="input-group">
            <span class="input-group-addon"><li class="fa fa-money"></li></span>

            <input type="text"  value="{{$price->last_selling_price }}" class="form-control" disabled>
        </div>
    </div>

</div>

<div class="col-md-3">
    <div class="form-group">
        <label for="inputLastPurchase" class="control-label">{{__('Last purchase price')}}</label>
        <div class="input-group">
            <span class="input-group-addon"><li class="fa fa-money"></li></span>

            <input type="text"  value="{{$price->last_purchase_price }}" class="form-control" disabled>

        </div>
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label for="inputLastPurchase" class="control-label">{{__('Damage Price')}}</label>
        <div class="input-group">
            <span class="input-group-addon"><li class="fa fa-money"></li></span>

            <input type="text"  value="{{$price->damage_price }}" class="form-control" disabled>

        </div>
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label for="inputBarcode" class="control-label">{{__('Barcode')}}</label>
        <div class="input-group">
            <span class="input-group-addon"><li class="fa fa-barcode"></li></span>

            <input type="text"  value="{{$price->barcode }}" class="form-control" disabled>
        </div>
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label for="inputBarcode" class="control-label">{{__('Supplier Barcode')}}</label>
        <div class="form-group">
            <input class="form-control" disabled name="supplier_barcode" value="{{$price->supplier_barcode}}">
        </div>
    </div>
</div>


{{--PRINT BARECODE--}}
<div class="col-md-12">
<div class="col-md-2">
    <div class="form-group">
        <label for="inputBarcode" class="control-label">{{__('Barcode Num.')}}</label>
        <div class="form-group">
            <input type="number" id="barcode_qty" min="1" class="form-control" name="barcode_qty" value="1">
        </div>
    </div>
</div>

<div class="col-md-4" style="margin-top: 28px">
    <div class="form-group">
        <button class="btn btn-primary" onclick="openWin('{{$price->id}}')">
            {{__('Print Barcode')}}
        </button>
    </div>
</div>

<div class="radio primary col-md-2" style="margin-top: 29px;">
                    <input type="radio" disabled
                           id="radio-purchase-{{$index}}" {{isset($price) && $price->default_purchase ? 'checked':''}}>
                    <label for="radio-purchase-{{$index}}">{{__('Default Purchase')}}</label>
                </div>

                <div class="radio primary col-md-2" style="margin-top: 29px;">
                    <input type="radio" disabled
                           {{isset($price) && $price->default_sales ? 'checked':''}}
                           id="radio-sales-{{$index}}">
                    <label for="radio-sales-{{$index}}">{{__('Default Sales')}}</label>
                </div>

                <div class="radio primary col-md-2" style="margin-top: 29px;">
                    <input type="radio" disabled
                           {{isset($price) && $price->default_maintenance ? 'checked':''}}
                           id="radio-maintenance-{{$index}}">
                    <label for="radio-maintenance-{{$index}}">{{__('Default Maintenance')}}</label>
                </div>
</div>


<div class="col-md-12">
    <hr>
</div>

{{-- PRICES BUTTONS--}}
<div class="col-md-12">
    <div class="form-group">
        <p>
            <button class="btn hvr-rectangle-in resetAdd-wg-btn maintenance_type_active_maintenance_form"
                    data-toggle="collapse"
                    href="#collapseExample_{{$index}}"
                    role="button"
                    aria-expanded="false" aria-controls="collapseExample">
                <i class="ico ico-left fa fa-usd"></i>
                {{__('Prices')}}
            </button>
        </p>
    </div>

    {{-- PRICES BUTTONS--}}
    <div class="collapse show in" id="collapseExample_{{$index}}">
        <div class="card card-body " style="padding: 10px;">

            <div id="ajax_service_box">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 products-details-wg">

                        <table class="table table-bordered" style="width:100%">
                        <thead style="background-color: #ada3a361; text-align: center;">
                            <tr>
                                <th scope="col">{!! __('Check') !!}</th>
                                <th scope="col">{!! __('Price Name') !!}</th>
                                <th scope="col">{!! __('Purchase Price') !!}</th>
                                <th scope="col">{!! __('Sales Price') !!}</th>
                                <th scope="col">{!! __('Maintenance Price') !!}</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($price->partPriceSegments as $key => $priceSegment)

                                <tr>
                                    <td>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" disabled checked
                                                           id="price_segment_checkbox_{{$index}}_{{$key}}"
                                                           value="{{$key}}" name="units[{{$index}}][prices][{{$key}}][id]">
                                                    <label for="price_segment_checkbox_{{$index}}_{{$key}}"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td style="color: #0c0c0c">
                                        {{$priceSegment->name}}
                                    </td>

                                    <td>
                                        <div class="input-group">
                                            <input type="text" class="form-control" disabled
                                                   value="{{$priceSegment? $priceSegment->purchase_price : 0}}"
                                                   id="price_segment_{{$index}}_{{$key}}"
                                                   name="units[{{$index}}][prices][{{$key}}][price]"
                                            >
                                        </div>
                                    </td>

                                    <td>
                                        <div class="input-group">
                                            <input type="text" class="form-control" disabled
                                                   value="{{$priceSegment ? $priceSegment->sales_price : 0}}"
                                                   id="sales_price_segment_{{$index}}_{{$key}}"
                                                   name="units[{{$index}}][prices][{{$key}}][sales_price]"
                                            >
                                        </div>
                                    </td>

                                    <td>
                                        <div class="input-group">
                                            <input type="text" class="form-control" disabled
                                                   value="{{ $priceSegment ? $priceSegment->maintenance_price : 0}}"
                                                   id="maintenance_price_segment_{{$index}}_{{$key}}"
                                                   name="units[{{$index}}][prices][{{$key}}][maintenance_price]"
                                            >
                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

