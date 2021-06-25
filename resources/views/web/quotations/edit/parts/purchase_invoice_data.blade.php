
<input type="hidden" name="part_ids[]" value='{{$part->id}}'>
<input type="hidden" name="index[]" value='{{$index}}'>

<td>{{$part->name}}</td>

<td>
    <select name="purchase_invoice_id[]" class="js-example-basic-single" style="width: 100%"
            id="purchase-invoice-{{$index}}"
            onchange="purchaseInvoiceData({{$part->id}}, '{{$index}}')"
    >
        <option value="">{{__('Select')}}</option>
        @foreach($part->items as $item)
            @if($item && $item->invoice && $item->purchase_qty > 0)
                <option value="{{$item->invoice->id}}" {{$purchaseInvoice->id == $item->invoice->id ? 'selected':''}}>
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
            <option value="{{$i}}" {{$type_item->qty == $i ? "selected" : ""}}>
                {{$i}}
            </option>
        @endfor
    </select>
</td>

<input type="hidden" class="form-control" readonly
       value="{{$part->last_selling_price}}"
       name="last_selling_price[]"
       id="last_purchase_price-{{$index}}"
>

<td>
    <input type="number" class="form-control" value="{{$type_item->price}}"
           name="selling_price[]" id="selling-price-{{$index}}"
           onkeyup="setServiceValues({{$index}})" onchange="setServiceValues({{$index}})"
    >
</td>

<td>


    <div class="radio primary">
        <input type="radio" name="item_discount_type_{{$index}}"
               {{$type_item->discount_type == "amount" ? "checked":""}}
               id="discount-type-amount-{{$index}}" value="amount" checked
               onclick="setServiceValues({{$index}})">
        <label for="discount-type-amount-{{$index}}">{{__('Amount')}}</label>
    </div>
    <div class="radio primary">
        <input type="radio"  name="item_discount_type_{{$index}}"
               {{$type_item->discount_type == "percent" ? "checked":""}}
               id="discount-type-percent-{{$index}}" value="percent"
               onclick="setServiceValues({{$index}})">
        <label for="discount-type-percent-{{$index}}">{{__('Percent')}}</label>
    </div>
</td>

<td>
    <input type="number" class="form-control" value="{{$type_item->discount}}" name="item_discount[]"
           onkeyup="setServiceValues({{$index}})"
           onchange="setServiceValues({{$index}})"
           id="discount-{{$index}}">
</td>

<td>
    <input type="number" class="form-control" readonly
           value="{{$type_item->sub_total}}" name="item_total_before_discount[]" id="total-{{$index}}">
</td>

<td><input type="number" class="form-control"
           readonly value="{{$type_item->total_after_discount}}"
           name="item_total_after_discount[]"
           id="total-after-discount-{{$index}}">
</td>
<td>
    <i class="fa fa-trash fa-2x" onclick="removePartsFromTable({{$index}})"
       style="color:#F44336; cursor: pointer">
    </i>
</td>
