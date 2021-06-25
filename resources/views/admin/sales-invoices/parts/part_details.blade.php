<tr data-id="{{$items_count}}" data-barcode="{{$part->barcode ?? '#' }}"  id="{{$items_count}}" class="tr_{{$items_count}}">

    <input type="hidden" name="part_ids[]" value='{{$part->id}}'>
    <input type="hidden" name="index[]" value='{{$items_count}}'>
    <input type="hidden" id="percent-discount-{{$items_count}}" value='{{$part->biggest_percent_discount}}'>
    <input type="hidden" id="amount-discount-{{$items_count}}" value='{{$part->biggest_amount_discount}}'>
    <input type="hidden" id="maximum-sale-amount-{{$items_count}}" value='{{$part->maximum_sale_amount}}'>

    <td>{{$part->name}}</td>

    <td>
        <select style="width:100px" name="purchase_invoice_id[]" class="js-example-basic-single" style="width: 100%"
                id="purchase-invoice-{{$items_count}}"
                onchange="purchaseInvoiceData({{$part->id}} ,{{$items_count}})"
        >
            <option value="">{{__('Select')}}</option>
            @foreach($part->items as $item)
                @if($item && $item->invoice && $item->purchase_qty > 0)
                    <option value="{{$item->invoice->id}}">
                        {{$item->invoice->invoice_number}}
                    </option>
                @endif
            @endforeach
        </select>
    </td>

    <td>
        <input style="width:100px" type="number" class="form-control" readonly value="{{$part->quantity}}"
               name="available_qy[]" id="available-qy-{{$items_count}}">
    </td>

    <td>
        <input style="width:100px" type="number" class="form-control first_qty" name="sold_qty[]"
               onkeyup="setServiceValues({{$items_count}})"  onchange="setServiceValues({{$items_count}})" id="sold-qy-{{$items_count}}">
    </td>

    <td>
    <input style="width:100px" type="number" class="form-control" readonly
           value="{{$part->last_selling_price}}" name="last_selling_price[]"
           id="last_purchase_price-{{$items_count}}"
    >
    </td>

    <td>
        <input style="width:100px" type="number" class="form-control" value="{{$part->selling_price}}"
               name="selling_price[]" id="selling-price-{{$items_count}}"
               onkeyup="setServiceValues({{$items_count}})"  onchange="setServiceValues({{$items_count}})"
        >
    </td>

    <td>

        <div class="radio primary">
            <input type="radio" name="item_discount_type_{{$items_count}}"
                   id="item-discount-type-amount-{{$items_count}}" value="amount" checked
                   onclick="setServiceValues({{$items_count}})">
            <label for="item-discount-type-amount-{{$items_count}}">{{__('amount')}}</label>
        </div>

        <div class="radio primary">
            <input type="radio" name="item_discount_type_{{$items_count}}"
                   id="item-discount-type-percent-{{$items_count}}" value="percent"
                   onclick="setServiceValues({{$items_count}})">
            <label for="item-discount-type-percent-{{$items_count}}">{{__('Percent')}}</label>
        </div>
    </td>
    <td>
        <input style="width:100px" type="number" class="form-control" value="0" name="item_discount[]"
               onkeyup="setServiceValues({{$items_count}})" onchange="setServiceValues({{$items_count}})"
               id="discount-{{$items_count}}">
    </td>

    <td>
        <input style="width:100px" type="number" class="form-control" readonly value="0"
               name="item_total_before_discount[]" id="total-{{$items_count}}">
    </td>

    <td><input style="width:100px" type="number" class="form-control" readonly value="0"
               name="item_total_after_discount[]" id="total-after-discount-{{$items_count}}">
    </td>
    <td>
        <i class="fa fa-trash fa-2x" onclick="removeServiceFromTable({{$items_count}})"
           style="color:#F44336; cursor: pointer">
        </i>
    </td>
</tr>

<input type="hidden" name="part_id" value="{{$items_count}}" id="part-{{$items_count}}">
<input type="hidden" name="items_count" id="invoice_items_count" value="{{$items_count}}">
<input type="hidden" name="parts_count" id="parts_count" value="{{$parts_count}}">
