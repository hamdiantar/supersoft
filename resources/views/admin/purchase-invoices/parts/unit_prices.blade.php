<select name="items[{{$part_id}}][part_price_segment_id]" class="js-example-basic-single" id="unit_prices_{{$part_id}}"
        onchange="segmentPrices('{{$part_id}}')">

    <option value="">{{__('Select')}}</option>
    @foreach($prices as $price)
        <option value="{{$price->id}}"
                data-purchase-price="{{$price->pivot->price}}"
        >
            {{$price->name}}
        </option>
    @endforeach
</select>
