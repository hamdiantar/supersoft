<tr>
    <td>
    <span>{{$item->part->name}}</span> 
        <input type="hidden" name="table_data[{{ $item->id }}][part]" value="{{ $item->part->id }}"/>
        <!-- <input class="form-control" readonly value="{{ $item->part->name }}"/> -->
    </td>
    <td>
        <input class="form-control" data-row-input="defualt-qnty"
            readonly name="table_data[{{ $item->id }}][default_quantity]" value="{{ $item->default_unit_quantity }}"/>
            <span class="part-unit-span"> {{ $item->load('part')->part && $item->load('part')->part->sparePartsUnit ? $item->load('part')->part->sparePartsUnit->unit :'' }}  </span>
    </td>
    <td>
        <select name="table_data[{{ $item->id }}][unit_id]" class="form-control select2-updated" data-row-input="unit">
            <option value=""> {{ __('opening-balance.select-one') }} </option>
            @foreach($item->part->prices as $unit_price)
                <option value="{{ $unit_price->id }}" {{ $item->part_price_id == $unit_price->id ? 'selected' : '' }}
                    data-quantity="{{ $unit_price->quantity }}"
                    data-purchase-price="{{ $unit_price->purchase_price }}"
                    data-price-segment-json="{{ $unit_price->partPriceSegments->toJson() }}">
                    {{ optional($unit_price->unit)->unit }}
                </option>
            @endforeach
        </select>
    </td>
    @php
        $buy_price = $item->buy_price;
    @endphp
    <td>

        <select name="table_data[{{ $item->id }}][price_segment_id]" class="form-control select2-updated" data-row-input="price-segment">
            <option value="" data-purchase-price="0"> {{ __('opening-balance.select-one') }} </option>
            @foreach(\App\OpeningStockBalance\Services\CommonFunctionsService::fetch_price_segments($item->part_price_id) as $price_segment)
                @php
                    $buy_price = $price_segment->id == $item->part_price_price_segment_id ? $price_segment->purchase_price : $buy_price;
                @endphp
                <option value="{{ $price_segment->id }}"
                    {{ $price_segment->id == $item->part_price_price_segment_id ? 'selected' : '' }}
                    data-purchase-price="{{ $price_segment->purchase_price }}">
                    {{ $price_segment->name }}
                </option>
            @endforeach
        </select>
    </td>
    <td>
        <input class="form-control" data-row-input="qnty" name="table_data[{{ $item->id }}][quantity]" value="{{ $item->quantity }}"/>
    </td>
    <td>
        <input class="form-control" data-row-input="buy-price" name="table_data[{{ $item->id }}][buy_price]" value="{{ $buy_price }}"/>
    </td>
    <td>
        <input class="form-control" data-row-input="total" name="table_data[{{ $item->id }}][total]" readonly
            value="{{ $buy_price * $item->quantity }}"/>
    </td>
    <td>
        <select name="table_data[{{ $item->id }}][store_id]" class="form-control select2-updated">
            <option value=""> {{ __('opening-balance.select-one') }} </option>
            @foreach($stores as $store)
                <option {{ $item->store_id == $store->id ? 'selected' : '' }} value="{{ $store->id }}"> {{ $store->name }} </option>
            @endforeach
        </select>
    </td>
    <td>
        <button class="btn btn-danger" type="button" onclick="remove_my_row_parent(event ,'{{ $item->id }}')">
            <i class="fa fa-trash"></i>
        </button>
    </td>
</tr>
