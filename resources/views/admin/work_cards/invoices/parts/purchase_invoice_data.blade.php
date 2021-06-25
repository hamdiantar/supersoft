
<input type="hidden" name="part_ids_{{$maintenance_id}}[]" value='{{$part->id}}'>
<input type="hidden" name="part_index_{{$maintenance_id}}[{{$part->id}}]" value='{{$index}}'>

<input type="hidden" id="percent-discount-{{$index}}_{{$maintenance_id}}" value='{{$part->biggest_percent_discount}}'>
<input type="hidden" id="amount-discount-{{$index}}_{{$maintenance_id}}" value='{{$part->biggest_amount_discount}}'>
<input type="hidden" id="maximum-sale-amount-{{$index}}_{{$maintenance_id}}" value='{{$part->maximum_sale_amount}}'>

<td>{{$part->name}}</td>

<td>
    <select  class="js-example-basic-single" style="width: 100%"
            name="part_{{$part->id}}_maintenance_{{$maintenance_id}}_index_{{$index}}[purchase_invoice_id]"
            id="purchase-invoice-{{$index}}-{{$maintenance_id}}"
             onchange="purchaseInvoiceData('{{$part->id}}',{{$maintenance_id}}, {{$maintenance_type_id}}, {{$index}})"
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
           id="available-qy-{{$index}}-{{$maintenance_id}}">
</td>

<td>
    <select class="js-example-basic-single" style="width: 100%"
            name="part_{{$part->id}}_maintenance_{{$maintenance_id}}_index_{{$index}}[qty]"
            id="sold-qy-{{$index}}-{{$maintenance_id}}"
            onchange="setServiceValues('{{$index}}','{{$maintenance_id}}', {{$maintenance_type_id}})"
    >
        <option value="">{{__('select')}}</option>
        @for($i=1; $i<=$invoice_item->purchase_qty; $i++)
            <option value="{{$i}}">{{$i}}</option>
        @endfor
    </select>
</td>

<input type="hidden" class="form-control" readonly
       value="{{$part->last_selling_price}}"
       id="last_purchase_price-{{$index}}-{{$maintenance_id}}"
>

<td>
    <input type="number" class="form-control" value="{{$part->service_selling_price}}" min="0"
           name="part_{{$part->id}}_maintenance_{{$maintenance_id}}_index_{{$index}}[price]"
           id="selling-price-{{$index}}-{{$maintenance_id}}"
           onkeyup="setServiceValues('{{$index}}','{{$maintenance_id}}', {{$maintenance_type_id}})"
           onchange="setServiceValues('{{$index}}','{{$maintenance_id}}', {{$maintenance_type_id}})"
    >
</td>

<td>

    <div class="radio primary">
        <input type="radio"
               name="part_{{$index}}_maintenance_{{$maintenance_id}}_index_{{$index}}[discount_type]"
               id="discount-type-amount-{{$index}}-{{$maintenance_id}}" value="amount" checked
               onclick="setServiceValues('{{$index}}','{{$maintenance_id}}', {{$maintenance_type_id}})"
        >
        <label for="discount-type-amount-{{$index}}-{{$maintenance_id}}">{{__('Amount')}}</label>
    </div>
    <div class="radio primary">
        <input type="radio"
               name="part_{{$index}}_maintenance_{{$maintenance_id}}_index_{{$index}}[discount_type]"
               id="discount-type-percent-{{$index}}-{{$maintenance_id}}" value="percent"
               onclick="setServiceValues('{{$index}}','{{$maintenance_id}}', {{$maintenance_type_id}})"
        >
        <label for="discount-type-percent-{{$index}}-{{$maintenance_id}}">{{__('Percent')}}</label>
    </div>

</td>
<td>
    <input type="number" class="form-control" value="0" min="0"
           name="part_{{$part->id}}_maintenance_{{$maintenance_id}}_index_{{$index}}[discount]"
           onkeyup="setServiceValues('{{$index}}','{{$maintenance_id}}', {{$maintenance_type_id}})"
           onchange="setServiceValues('{{$index}}','{{$maintenance_id}}', {{$maintenance_type_id}})"
           id="discount-{{$index}}-{{$maintenance_id}}">
</td>

<td>
    <input type="number" class="form-control" readonly value="0"
           id="total-{{$index}}-{{$maintenance_id}}">
</td>

<td>
    <input type="number" class="form-control" readonly value="0"
           id="total-after-discount-{{$index}}-{{$maintenance_id}}">
</td>
<td>
    <i class="fa fa-trash fa-2x"
       onclick="removePartsFromTable('{{$index}}', '{{$maintenance_id}}', {{$maintenance_type_id}})"
       style="color:#F44336; cursor: pointer">
    </i>
</td>
