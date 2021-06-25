<input type="hidden" name="part_ids[]" value='{{$part->id}}'>
<input type="hidden" name="index[]" value='{{$index}}'>

<input type="hidden" id="percent-discount-{{$index}}" value='{{$part->biggest_percent_discount}}'>
<input type="hidden" id="amount-discount-{{$index}}" value='{{$part->biggest_amount_discount}}'>
<input type="hidden" id="maximum-sale-amount-{{$index}}" value='{{$part->maximum_sale_amount}}'>

<td>{{$part->name}}</td>

<td>
    <select name="purchase_invoice_id[]" class="js-example-basic-single" style="width: 100%"
            id="purchase-invoice-{{$index}}"
            {{--            onchange="setServiceValues({{$part->id}})"--}}
            onchange="purchaseInvoiceData({{$part->id}}, {{$index}})"
    >
        <option value="">{{__('Select')}}</option>
        @foreach($part->items as $item)
            @if($item && $item->invoice && $item->purchase_qty > 0)
                <option value="{{$item->invoice->id}}" {{$invoice->id == $item->invoice->id ? 'selected':''}}>
                    {{$item->invoice->invoice_number}}
                </option>
            @endif
        @endforeach
    </select>
</td>

<td>
    <input type="number" class="form-control" readonly value="{{$invoice_item->purchase_qty}}"
           name="available_qy[]" id="available-qy-{{$index}}">
</td>

<td>
    <select name="sold_qty[]" class="js-example-basic-single" style="width: 100%"
            id="sold-qy-{{$index}}" onchange="setServiceValues({{$index}})"
    >
        <option value="">{{__('Select')}}</option>
        @for($i=1; $i<=$invoice_item->purchase_qty; $i++)
            <option value="{{$i}}">{{$i}}</option>
        @endfor
    </select>
</td>
<td>
<input  type="number" class="form-control" readonly
       value="{{$part->last_selling_price}}" name="last_selling_price[]"
       id="last_purchase_price-{{$index}}"
>
</td>

<td>
    <input style="width:100px" type="number" class="form-control" value="{{$part->selling_price}}"
           name="selling_price[]" id="selling-price-{{$index}}"
           onkeyup="setServiceValues({{$index}})" onchange="setServiceValues({{$index}})"
    >
</td>

<td>

    <div class="radio primary">
        <input type="radio" name="item_discount_type_{{$index}}"
               id="item-discount-type-amount-{{$index}}" value="amount" checked
               onclick="setServiceValues({{$index}})">
        <label for="item-discount-type-amount-{{$index}}"> {{__('Amount')}}</label>
    </div>

    <div class="radio primary">
        <input type="radio" name="item_discount_type_{{$index}}"
               id="item-discount-type-percent-{{$index}}" value="percent"
               onclick="setServiceValues({{$index}})">
        <label for="item-discount-type-percent-{{$index}}">{{__('Percent')}}</label>
    </div>

</td>
<td>
    <input style="width:100px" type="number" class="form-control" value="0" name="item_discount[]"
           onkeyup="setServiceValues({{$index}})" onchange="setServiceValues({{$index}})"
           id="discount-{{$index}}">
</td>

<td>
    <input style="width:100px" type="number" class="form-control" readonly
           value="0" name="item_total_before_discount[]" id="total-{{$index}}">
</td>

<td><input style="width:100px" type="number" class="form-control" readonly value="0"
           name="item_total_after_discount[]"
           id="total-after-discount-{{$index}}">
</td>
<td>
    <i class="fa fa-trash fa-2x" onclick="removeServiceFromTable({{$index}})"
       style="color:#F44336; cursor: pointer">
    </i>
</td>
