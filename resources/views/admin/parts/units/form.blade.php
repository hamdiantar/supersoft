<div class="col-md-2">
    <div class="form-group">

        <label for="inputQuantity" class="control-label">{{__('Quantity')}}</label>

        @if(isset($price))
            <input type="hidden" name="units[{{$index}}][part_price_id]" value="{{$price->id}}">
        @endif

        <div class="input-group">
            <span class="input-group-addon"><li class="fa fa-cube"></li></span>

            <input type="text"
                   name="units[{{$index}}][quantity]"
                   onkeyup="calculatePrice('{{$index}}')"
                   class="form-control" placeholder="{{__('Quantity')}}" id="qty_{{$index}}"
                   value="{{ isset($price) ? $price->quantity : 1 }}" {{$index == 1 ? 'readOnly':''}}>
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
            @if($index == 1)
                <input type="text" class="form-control default_unit" readonly
                       value="{{isset($price) && $price->unit? $price->unit->unit : '' }}">

                <input type="hidden" class="form-control" id="default_unit_val"
                       name="units[{{$index}}][unit_id]"
                       value="{{isset($price) ? $price->unit_id : '' }}">

            @else
                <select class="form-control js-example-basic-single" id="unit_{{$index}}"
                        name="units[{{$index}}][unit_id]" onchange="getUnitName('{{$index}}')">
                    <option value="">{{__('Select unit')}}</option>
                    @foreach($partUnits as $partsUnit)
                        <option
                            value="{{$partsUnit->id}}" {{isset($price) && $price->unit_id == $partsUnit->id? 'selected':''}}>
                            {{$partsUnit->unit}}
                        </option>
                    @endforeach
                </select>
            @endif
        </div>
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">

        <label for="inputQuantity" class="control-label">{{__('Selling Price')}}</label>

        <div class="input-group">
            <span class="input-group-addon"><li class="fa fa-money"></li></span>

            <input type="text" name="units[{{$index}}][selling_price]"
                   id="selling_price_{{$index}}"
                   class="form-control" placeholder="{{__('selling price')}}"
                   value="{{isset($price) ? $price->selling_price : 0}}">
        </div>
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label for="inputPurchasePrice" class="control-label">{{__('purchase price')}}</label>
        <div class="input-group">
            <span class="input-group-addon"><li class="fa fa-money"></li></span>
            <input type="text"
                   name="units[{{$index}}][purchase_price]"
                   id="purchase_price_{{$index}}"
                   class="form-control" placeholder="{{__('purchase price')}}"
                   value="{{isset($price) ? $price->purchase_price : 0}}">
        </div>
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label for="inputLess" class="control-label">{{__('Less Selling Price')}}</label>
        <div class="input-group">
            <span class="input-group-addon"><li class="fa fa-money"></li></span>
            <input type="text"
                   name="units[{{$index}}][less_selling_price]"
                   class="form-control" id="less_selling_price_{{$index}}"
                   placeholder="{{__('less selling price')}}"
                   value="{{isset($price) ? $price->less_selling_price : 0}}">
        </div>
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label for="inputServices" class="control-label">{{__('Service selling price')}}</label>
        <div class="input-group">
            <span class="input-group-addon"><li class="fa fa-money"></li></span>
            <input type="text"
                   name="units[{{$index}}][service_selling_price]"
                   class="form-control" id="service_selling_price_{{$index}}"
                   placeholder="{{__('Services selling price')}}"
                   value="{{isset($price) ? $price->service_selling_price : 0}}">
        </div>

    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label for="inputServices" class="control-label">{{__('Less Service selling price')}}</label>
        <div class="input-group">
            <span class="input-group-addon"><li class="fa fa-money"></li></span>
            <input type="text"
                   name="units[{{$index}}][less_service_selling_price]"
                   class="form-control"
                   id="less_service_selling_price_{{$index}}"
                   placeholder="{{__('Less services selling price')}}"
                   value="{{isset($price) ? $price->less_service_selling_price : 0}}">
        </div>
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label for="inputMaxQuantity" class="control-label">{{__('Maximum sale amount')}}</label>
        <div class="input-group">
            <span class="input-group-addon"><li class="fa fa-money"></li></span>
            <input type="number"
                   name="units[{{$index}}][maximum_sale_amount]"
                   class="form-control"
                   placeholder="{{__('maximum sale amount of quantity')}}" id="maximum_sale_amount_{{$index}}"
                   value="{{isset($price) ? $price->maximum_sale_amount : 0}}">
        </div>
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label for="inputMinimumQuantity" class="control-label">{{__('Minimum for order')}}</label>
        <div class="input-group">
            <span class="input-group-addon"><li class="fa fa-cube"></li></span>
            <input type="number" name="units[{{$index}}][minimum_for_order]"
                   class="form-control" id="minimum_for_order_{{$index}}"
                   placeholder="{{__('minimum for order')}}"
                   value="{{isset($price) ? $price->minimum_for_order : 0}}">
        </div>
    </div>

</div>

<div class="col-md-3">

    <div class="form-group">
        <label for="inputBigDiscount" class="control-label">{{__('Biggest percent discount')}}</label>
        <div class="input-group">
            <span class="input-group-addon"><li class="fa fa-money"></li></span>
            <input type="text"
                   name="units[{{$index}}][biggest_percent_discount]"
                   class="form-control"
                   placeholder="{{__('Biggest percent discount')}}" id="biggest_percent_discount_{{$index}}"
                   value="{{isset($price) ? $price->biggest_percent_discount : 0}}">
        </div>
    </div>
</div>

<div class="col-md-3">

    <div class="form-group">
        <label for="inputBigAmountDiscount" class="control-label">{{__('Biggest amount discount')}}</label>
        <div class="input-group">
            <span class="input-group-addon"><li class="fa fa-money"></li></span>
            <input type="text" name="units[{{$index}}][biggest_amount_discount]"
                   class="form-control"
                   placeholder="{{__('Biggest amount discount')}}" id="biggest_amount_discount_{{$index}}"
                   value="{{isset($price) ? $price->biggest_amount_discount : 0}}">
        </div>
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label for="inputLastSelling" class="control-label">{{__('Last selling price')}}</label>
        <div class="input-group">
            <span class="input-group-addon"><li class="fa fa-money"></li></span>
            <input type="text"
                   name="units[{{$index}}][last_selling_price]"
                   class="form-control"
                   placeholder="{{__('Last selling price')}}" disabled="disabled" id="last_selling_price_{{$index}}"
                   value="{{isset($price) ? $price->last_selling_price : 0}}">
        </div>
    </div>

</div>

<div class="col-md-3">
    <div class="form-group">
        <label for="inputLastPurchase" class="control-label">{{__('Last purchase price')}}</label>
        <div class="input-group">
            <span class="input-group-addon"><li class="fa fa-money"></li></span>
            <input type="text"
                   {{--                   name="{{isset($price) ? '' : 'units['.$index.'][last_purchase_price]'}}"--}}
                   name="units[{{$index}}][last_purchase_price]"
                   class="form-control"
                   placeholder="{{__('Last purchase price')}}" disabled="disabled" id="last_purchase_price_{{$index}}"
                   value="{{isset($price) ? $price->last_purchase_price : 0}}">
        </div>
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label for="inputBarcodeSupplier" class="control-label">{{__('Damage Price')}}</label>
        <div class="input-group">
            <span class="input-group-addon"><li class="fa fa-money"></li></span>
            <input type="text" class="form-control"
                   name="units[{{$index}}][damage_price]" id="damage_price_{{$index}}"
                   value="{{isset($price) ? $price->damage_price : 0}}">
        </div>
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label for="inputBarcode" class="control-label">{{__('Barcode')}}</label>
        <div class="input-group">
            <span class="input-group-addon"><li class="fa fa-barcode"></li></span>
            <input type="text" class="form-control"
                   {{--                   name="{{isset($price) ? '' : 'units['.$index.'][barcode]'}}" --}}
                   name="units[{{$index}}][barcode]"
                   placeholder="{{__('Barcode')}}" id="barcode_{{$index}}"
                   value="{{isset($price) ? $price->barcode : ''}}">
        </div>
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label for="inputBarcodeSupplier" class="control-label">{{__('Supplier Barcode')}}</label>
        <div class="input-group">
            <span class="input-group-addon"><li class="fa fa-barcode"></li></span>
            <input type="text" class="form-control"
                   name="units[{{$index}}][supplier_barcode]"
                   placeholder="{{__('Supplier Barcode')}}" id="supplier_barcode_{{$index}}"
                   value="{{isset($price) ? $price->supplier_barcode : ''}}">
        </div>
    </div>
</div>

<div class="col-md-9">
    <div class="form-group">
        <div class="radio primary col-md-3">
            <input type="radio"
                   name="default_purchase" id="radio-purchase-{{$index}}" value="{{$index}}"
                {{isset($price) && $price->default_purchase ? 'checked':''}}>
            <label for="radio-purchase-{{$index}}">{{__('Default Purchase')}}</label>
        </div>

        <div class="radio primary col-md-3" style="margin-top: 10px;">
            <input type="radio"
                   name="default_sales" {{isset($price) && $price->default_sales ? 'checked':''}} value="{{$index}}"
                   id="radio-sales-{{$index}}" >
            <label for="radio-sales-{{$index}}">{{__('Default Sales')}}</label>
        </div>

        <div class="radio primary col-md-3" style="margin-top: 10px;">
            <input type="radio"
                   name="default_maintenance" value="{{$index}}"
                   {{isset($price) && $price->default_maintenance ? 'checked':''}}
                   id="radio-maintenance-{{$index}}">
            <label for="radio-maintenance-{{$index}}">{{__('Default Maintenance')}}</label>
        </div>
    </div>
</div>

<div class="col-md-8">
    <div class="form-group">
    <!-- <label for="inputBarcode" class="control-label">{{__('Default')}}</label> -->
        <div class="input-group">

            <div class="row">


            </div>
        </div>
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
    <div class=" collapse {{isset($price) && $price->partPriceSegments->count() ? 'show in':''}}" id="collapseExample_{{$index}}">
        <div class="card card-body " style="padding: 10px;">

            <div id="ajax_service_box">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 products-details-wg">

                        @include('admin.parts.price_segments.index')

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

