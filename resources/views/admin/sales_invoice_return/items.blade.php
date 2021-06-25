@php
    $part = $sales_invoice_item->part;
    $index +=1;
@endphp


<tr data-id="{{$index}}" id="{{$index}}" class="tr_{{$index}}">

    <input type="hidden" name="part_id_{{$index}}" value='{{$index}}'>
    <input type="hidden" name="sales_invoice_items_id_{{$index}}" value='{{$sales_invoice_item->id}}'>
    <input type="hidden" id="part-{{$index}}" value='{{$index}}'>

    <td>{{$part->name}}</td>

    <td>
        <input style="width:100px" type="text" name="purchase_invoice_id_{{$index}}" readonly
               value="{{optional($sales_invoice_item->purchaseInvoice)->invoice_number}}"
               class="form-control"
        >
    </td>

    <td>
        <input style="width:100px" type="number" class="form-control" readonly
               value="{{$sales_invoice_item->available_qty}}"
               name="available_qy_{{$index}}" id="available-qy-{{$index}}">
    </td>

    <td>
        <select style="width:100px" name="return_qty_{{$index}}" class="js-example-basic-single" style="width: 100%"
                id="sold-qy-{{$index}}" onchange="setServiceValues({{$index}})"
        >
            <option value="">{{__('select')}}</option>
            @for($i=1; $i<=$sales_invoice_item->sold_qty; $i++)
                <option value="{{$i}}" {{$i == $sales_invoice_item->sold_qty ? "selected":""}}>
                    {{$i}}
                </option>
            @endfor
        </select>
    </td>


    <input style="width:100px" type="hidden" class="form-control"
           value="{{$sales_invoice_item->last_selling_price}}" name="last_selling_price_{{$index}}"
           id="last_purchase_price-{{$index}}"
    >

    <td>
        <input style="width:100px" type="number" class="form-control"
               value="{{$sales_invoice_item->selling_price}}"
               name="selling_price_{{$index}}" id="selling-price-{{$index}}"
               onkeyup="setServiceValues({{$index}})"
               onchange="setServiceValues({{$index}})"
        >
    </td>

    <td>

        <div class="radio primary">
            <input type="radio" name="item_discount_type_{{$index}}"
                   id="item-discount-type-amount-{{$index}}" value="amount" checked
                   onclick="setServiceValues({{$index}})">
            <label for="item-discount-type-amount-{{$index}}">{{__('Amount')}}</label>
        </div>

        <div class="radio primary">
            <input type="radio" name="item_discount_type_{{$index}}"
                   id="item-discount-type-percent-{{$index}}" value="percent"
                   onclick="setServiceValues({{$index}})">
            <label for="item-discount-type-percent-{{$index}}">{{__('Percent')}}</label>
        </div>

    </td>

    <td>
        <input type="number" class="form-control"
               value="{{$sales_invoice_item->discount}}" name="item_discount_{{$index}}"
               onkeyup="setServiceValues({{$index}})" onchange="setServiceValues({{$index}})"
               id="discount-{{$index}}">
    </td>

    <td>
        <input style="width:100px" type="number" class="form-control"
               readonly value="{{$sales_invoice_item->sub_total}}"
               name="item_total_before_discount_{{$index}}" id="total-{{$index}}">
    </td>

    <td>
        <input style="width:100px" type="number" class="form-control"
               readonly value="{{$sales_invoice_item->total_after_discount}}"
               name="item_total_after_discount_{{$index}}" id="total-after-discount-{{$index}}">
    </td>

    <td>
        <input style="width:100px" type="checkbox" id="return_part_id_{{$index}}" class="sales_invoice_checkbox"
               name="return_part_ids[{{$index}}]"
               onclick="setServiceValues({{$index}}); disabledUnselected({{$index}})" checked
               value="{{$part->id}}">
    </td>
</tr>
