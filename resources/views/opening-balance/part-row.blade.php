@php
    $row_number = rand(1000000 ,9999999)
@endphp
<tr>
    <td>
    <span>{{$part->name}}</span> 
        <input type="hidden" name="table_data[{{ $row_number }}][part]" value="{{ $part->id }}"/>
        <!-- <input class="form-control" readonly value="{{ $part->name }}"/> -->
    </td>
    <td>
        <input class="form-control" data-row-input="defualt-qnty"
            readonly name="table_data[{{ $row_number }}][default_quantity]" value="{{ $part->prices[0]->quantity }}"/>
            <span class="part-unit-span"> {{ $part->sparePartsUnit->unit }}  </span> 
    </td>
    <td>
        <select name="table_data[{{ $row_number }}][unit_id]" class="form-control select2-updated" data-row-input="unit">
            <!-- <option value=""> {{ __('Select') }} </option> -->
            @foreach($part->prices as $unit_price)
                <option value="{{ $unit_price->id }}" {{ $loop->index == 0 ? 'selected' : '' }}
                    data-quantity="{{ $unit_price->quantity }}"
                    data-purchase-price="{{ $unit_price->purchase_price }}"
                    data-price-segment-json="{{ $unit_price->partPriceSegments->toJson() }}"
                >
                    {{ optional($unit_price->unit)->unit }}
                </option>
            @endforeach
        </select>
    </td>
    <td>
        <select name="table_data[{{ $row_number }}][price_segment_id]" class="form-control select2-updated" data-row-input="price-segment">
            <option value="" data-purchase-price="0"> {{ __('Select') }} </option>
            @foreach($part->prices[0]->partPriceSegments as $price_segment)
                <option value="{{ $price_segment->id }}" data-purchase-price="{{ $price_segment->purchase_price }}">
                    {{ $price_segment->name }}
                </option>
            @endforeach
        </select>
    </td>
    <td>
        <input class="form-control" data-row-input="qnty" name="table_data[{{ $row_number }}][quantity]" value="0"/>
    </td>
    <td>
        <input class="form-control" data-row-input="buy-price" name="table_data[{{ $row_number }}][buy_price]" value="{{ $part->prices[0]->purchase_price }}"/>
    </td>
    <td>
        <input class="form-control" data-row-input="total" name="table_data[{{ $row_number }}][total]" readonly value="0"/>
    </td>
    <td>
        <select name="table_data[{{ $row_number }}][store_id]" class="form-control select2-updated">
            <!-- <option value=""> {{ __('opening-balance.select-one') }} </option> -->
            @foreach($stores as $store)
                <option value="{{ $store->id }}"> {{ $store->name }} </option>
            @endforeach
        </select>
    </td>
    <td>
        <button class="btn btn-danger" type="button" onclick="remove_my_row_parent(event)">
        <i class="fa fa-trash"></i>
        </button>
    </td>
</tr>
