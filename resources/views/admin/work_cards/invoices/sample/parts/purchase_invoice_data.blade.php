
<input type="hidden" name="parts[{{$index}}][id]" value='{{$part->id}}'>

<input type="hidden" id="percent-discount-{{$index}}" value='{{$part->biggest_percent_discount}}'>
<input type="hidden" id="amount-discount-{{$index}}" value='{{$part->biggest_amount_discount}}'>
<input type="hidden" id="maximum-sale-amount-{{$index}}" value='{{$part->maximum_sale_amount}}'>

<td>{{$part->name}}</td>

<td>
    <select  class="js-example-basic-single" style="width: 100%"
            name="parts[{{$index}}][purchase_invoice_id]"
            id="purchase-invoice-{{$index}}"
             onchange="purchaseInvoiceData('{{$part->id}}', {{$index}})"
    >
        <option value="">{{__('select')}}</option>
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
           id="available-qy-{{$index}}">
</td>

<td>
    <select class="js-example-basic-single" style="width: 100%"
            name="parts[{{$index}}][qty]" id="sold-qy-{{$index}}"
            onchange="setServiceValues('{{$index}}')"
    >
        <option value="">{{__('select')}}</option>
        @for($i=1; $i<=$invoice_item->purchase_qty; $i++)
            <option value="{{$i}}">{{$i}}</option>
        @endfor
    </select>
</td>

<input type="hidden" class="form-control" readonly value="{{$part->last_selling_price}}" id="last_purchase_price-{{$index}}">

<td>
    <input type="number" class="form-control" value="{{$part->service_selling_price}}" min="0"
           name="parts[{{$index}}][price]"
           id="selling-price-{{$index}}"
           onkeyup="setServiceValues('{{$index}}')"
           onchange="setServiceValues('{{$index}}')"
    >
</td>

<td>

    <div class="radio primary">
        <input type="radio"
               name="parts[{{$index}}][discount_type]"
               id="discount-type-amount-{{$index}}" value="amount" checked
               onclick="setServiceValues('{{$index}}')"
        >
        <label for="discount-type-amount-{{$index}}">{{__('Amount')}}</label>
    </div>

    <div class="radio primary">
        <input type="radio"
               name="parts[{{$index}}][discount_type]"
               id="discount-type-percent-{{$index}}" value="percent"
               onclick="setServiceValues('{{$index}}')"
        >
        <label for="discount-type-percent-{{$index}}">{{__('Percent')}}</label>
    </div>

</td>
<td>
    <input type="number" class="form-control" value="0" min="0"
           name="parts[{{$index}}][discount]"
           onkeyup="setServiceValues('{{$index}}')"
           onchange="setServiceValues('{{$index}}')"
           id="discount-{{$index}}">
</td>

<td>
    <input type="number" class="form-control" readonly value="0" id="total-{{$index}}">
</td>

<td>
    <input type="number" class="form-control" readonly value="0" id="total-after-discount-{{$index}}">
</td>
<td>
    <i class="fa fa-trash fa-2x"
       onclick="removePartsFromTable('{{$index}}')"
       style="color:#F44336; cursor: pointer">
    </i>
</td>
