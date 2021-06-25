<tr data-id="{{$items_count}}" id="remove_part_{{$items_count}}_{{$maintenance_id}}" class="tr_{{$items_count}}_{{$maintenance_id}}">

    <input type="hidden" name="part_ids_{{$maintenance_id}}[]" value='{{$part->id}}'>
    <input type="hidden" name="part_index_{{$maintenance_id}}[]" value='{{$items_count}}'>

    <input type="hidden" id="percent-discount-{{$items_count}}_{{$maintenance_id}}" value='{{$part->biggest_percent_discount}}'>
    <input type="hidden" id="amount-discount-{{$items_count}}_{{$maintenance_id}}" value='{{$part->biggest_amount_discount}}'>
    <input type="hidden" id="maximum-sale-amount-{{$items_count}}_{{$maintenance_id}}" value='{{$part->maximum_sale_amount}}'>

    <td>{{$part->name}}</td>

    <td>
        <select class="js-example-basic-single" style="width: 100%"
                name="part_{{$part->id}}_maintenance_{{$maintenance_id}}_index_{{$items_count}}[purchase_invoice_id]"
                id="purchase-invoice-{{$items_count}}-{{$maintenance_id}}"
                onchange="purchaseInvoiceData('{{$part->id}}','{{$maintenance_id}}',{{$maintenance_type_id}}, {{$items_count}})"
        >
            <option value="">{{__('select')}}</option>
            @foreach($part->items as $item)
                @if($item && $item->invoice)
                    @if($item && $item->invoice && $item->purchase_qty > 0)
                        <option value="{{$item->invoice->id}}">
                            {{$item->invoice->invoice_number}}
                        </option>
                    @endif
                @endif
            @endforeach
        </select>
    </td>

    <td>
        <input type="number" class="form-control" readonly value="{{$part->quantity}}"
                id="available-qy-{{$items_count}}-{{$maintenance_id}}">
    </td>

    <td>
        <input type="number" class="form-control" min="0"
               name="part_{{$part->id}}_maintenance_{{$maintenance_id}}_index_{{$items_count}}[qty]"
               onkeyup="setServiceValues('{{$items_count}}','{{$maintenance_id}}', {{$maintenance_type_id}})"
               onchange="setServiceValues('{{$items_count}}','{{$maintenance_id}}', {{$maintenance_type_id}})"
               id="sold-qy-{{$items_count}}-{{$maintenance_id}}">
    </td>


    <input type="hidden" class="form-control" readonly
           value="{{$part->last_selling_price}}"
           id="last_purchase_price-{{$items_count}}-{{$maintenance_id}}"
    >

    <td>
        <input type="number" class="form-control" value="{{$part->service_selling_price}}" min="0"
               name="part_{{$part->id}}_maintenance_{{$maintenance_id}}_index_{{$items_count}}[price]"
               id="selling-price-{{$items_count}}-{{$maintenance_id}}"
               onkeyup="setServiceValues('{{$items_count}}','{{$maintenance_id}}', {{$maintenance_type_id}})"
               onchange="setServiceValues('{{$items_count}}','{{$maintenance_id}}', {{$maintenance_type_id}})"
        >
    </td>

    <td>
        <div class="radio primary">
            <input type="radio"
                   name="part_{{$part->id}}_maintenance_{{$maintenance_id}}_index_{{$items_count}}[discount_type]"
                   id="discount-type-amount-{{$items_count}}-{{$maintenance_id}}" value="amount" checked
                   onclick="setServiceValues('{{$items_count}}','{{$maintenance_id}}', {{$maintenance_type_id}})"
            >
            <label for="discount-type-amount-{{$items_count}}-{{$maintenance_id}}">{{__('Amount')}}</label>
        </div>
        <div class="radio primary">
            <input type="radio"
                   name="part_{{$part->id}}_maintenance_{{$maintenance_id}}_index_{{$items_count}}[discount_type]"
                   id="discount-type-percent-{{$items_count}}-{{$maintenance_id}}" value="percent"
                   onclick="setServiceValues('{{$items_count}}','{{$maintenance_id}}', {{$maintenance_type_id}})"
            >
            <label for="discount-type-percent-{{$items_count}}-{{$maintenance_id}}">{{__('Percent')}}</label>
        </div>

    </td>
    <td>
        <input type="number" class="form-control" value="0" min="0"
               name="part_{{$part->id}}_maintenance_{{$maintenance_id}}_index_{{$items_count}}[discount]"
               onkeyup="setServiceValues('{{$items_count}}','{{$maintenance_id}}', {{$maintenance_type_id}})"
               onchange="setServiceValues('{{$items_count}}','{{$maintenance_id}}', {{$maintenance_type_id}})"
               id="discount-{{$items_count}}-{{$maintenance_id}}">
    </td>

    <td>
        <input type="number" class="form-control" readonly value="0"
               id="total-{{$items_count}}-{{$maintenance_id}}">
    </td>

    <td>
        <input type="number" class="form-control " readonly value="0"
               id="total-after-discount-{{$items_count}}-{{$maintenance_id}}">
    </td>

    <td>
        <i class="fa fa-trash fa-2x"
           onclick="removePartsFromTable('{{$items_count}}', '{{$maintenance_id}}', {{$maintenance_type_id}})"
           style="color:#F44336; cursor: pointer">
        </i>
    </td>
</tr>

<input type="hidden" id="parts_items_count_{{$maintenance_id}}" value="{{$items_count}}">
<input type="hidden"  value="{{$items_count}}" id="part-{{$items_count}}-{{$maintenance_id}}">
