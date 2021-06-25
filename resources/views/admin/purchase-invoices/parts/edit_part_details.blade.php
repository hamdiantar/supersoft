<tr data-id="{{$part->id}}" data-barcode="{{$part->barcode ?? '#' }}"  id="{{$part->id}}" class="tr_{{$part->id}}">

    <input type="hidden" name="items[{{$part->id}}][id]" value='{{$part->id}}'>
    <input type="hidden" name="items[{{$part->id}}][item_id]" value='{{$item->id}}'>

    <input type="hidden" name="part_id" value='{{$part->id}}' id="part-{{$index+1}}">

    <td>{{$part->name}}</td>

    <td>
        <input style="width:100px" type="number" class="form-control" readonly value="{{$item->available_qty}}"
               name="items[{{$part->id}}][available_qy]" id="available-qy-{{$part->id}}">
    </td>

    <td>
        <input type="number"  class="form-control" min="0" max="{{$part->quantity}}" value="{{$item->quantity}}"
               name="items[{{$part->id}}][purchase_qty]"
               onkeyup="setServiceValues({{$part->id}})"
               onchange="setServiceValues({{$part->id}})"
               id="purchased-qy-{{$part->id}}">
    </td>

    <input type="hidden" class="form-control" readonly value="{{$item->last_purchase_price}}"
           name="items[{{$part->id}}][last_purchase_price]"
           id="last_purchase_price-{{$part->id}}">

    <td>
        <input type="text" class="form-control" value="{{$item->purchase_price}}" name="items[{{$part->id}}][purchase_price]"
               id="purchased-price-{{$part->id}}" onkeyup="setServiceValues({{$part->id}})">
    </td>


    <td>
        <input type="text"  class="form-control" readonly value="{{$item->last_purchase_price}}">
    </td>

    <td>
        <select name="items[{{$part->id}}][store_id]" class="js-example-basic-single">
            @foreach($stores as $k=>$v)
                <option value="{{$k}}" {{  $item->store_id == $k ? 'selected':'' }}>
                    {{$v}}
                </option>
            @endforeach
        </select>
    </td>

    <td>
        <ul class="list-inline">
            <li>
                <div class="radio info">
                    <input type="radio" name="items[{{$part->id}}][discount_type]" value="amount"
                           id="radio-10-discount-amount-{{$part->id}}" onclick="setServiceValues({{$part->id}})"
                        {{$item->discount_type == 'amount'? 'checked':''}}
                    >
                    <label for="radio-10-discount-amount-{{$part->id}}">
                        {{__('Amount')}}
                    </label>
                </div>
            </li>

            <li>
                <div class="radio pink">
                    <input type="radio" name="items[{{$part->id}}][discount_type]" value="percent" onclick="setServiceValues({{$part->id}})"
                           id="radio-12-discount-percent-{{$part->id}}"
                        {{$item->discount_type == 'percent'? 'checked':''}}
                    >

                    <label for="radio-12-discount-percent-{{$part->id}}">
                        {{__('Percent')}}
                    </label>
                </div>
            </li>

        </ul>
    </td>

    <td>
        <input type="number"  class="form-control" value="{{$item->discount}}" name="items[{{$part->id}}][discount]"
               onkeyup="setServiceValues({{$part->id}})" onchange="setServiceValues({{$part->id}})"
               id="discount-{{$part->id}}">
    </td>

    <td>
        <input type="number"  class="form-control"  readonly value="{{$item->subtotal}}"
               name="items[{{$part->id}}][subtotal]" id="subtotal-{{$part->id}}">
    </td>

    <td>
        <input type="number"  class="form-control"  readonly value="{{$item->total_after_discount}}"
               name="items[{{$part->id}}][total_after_discount]"
               id="total-after-discount-{{$part->id}}"
        >
    </td>

    <td>
        <i class="fa fa-trash fa-2x" onclick="removeServiceFromTable({{$part->id}})"
           style="color:#F44336; cursor: pointer">
        </i>
    </td>
</tr>
