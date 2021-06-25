<tr id="tr_part_{{$index}}" class="remove_on_change_branch">

    <td>
        <span id="item_number_{{$index}}">{{$index}}</span>
        <input type="hidden" name="items[{{isset($update_item) ? $update_item->supply_order_item_id : $item->id}}][supply_order_item_id]"
               value="{{isset($update_item) ? $update_item->supply_order_item_id : $item->id}}">
    </td>

    <td>
        <span>{{$part->name}}</span>
    </td>

    <td>
        <div class="input-group">
            @if(isset($update_item))
                <span>{{isset($update_item) && $update_item->partPrice && $update_item->partPrice->unit ? $update_item->partPrice->unit->unit : '---'}}</span>
            @else
                <span>{{isset($item) && $item->partPrice && $item->partPrice->unit ? $item->partPrice->unit->unit : '---'}}</span>
            @endif
        </div>
    </td>

    <td>
        <div class="input-group">
            @if(isset($update_item))
                <span>{{isset($update_item) ? $update_item->price : '---'}}</span>
            @else
                <span>{{isset($item) ? $item->price : '---'}}</span>
            @endif
        </div>
    </td>

    <td>
        <input style="width: 150px !important;" type="number" class="form-control" id="total_quantity_{{$index}}"
               value="{{isset($update_item) ? $update_item->total_quantity : $item->quantity}}" disabled
               name="items[{{isset($update_item) ? $update_item->supply_order_item_id : $item->id}}][total_quantity]">
    </td>

    <td>
        <input style="width: 150px !important;" type="number" class="form-control" id="old_accepted_quantity_{{$index}}"
               value="{{ isset($update_item) ? $update_item->old_accepted_quantity : $item->accepted_quantity}}" disabled>
    </td>

    <td>
        <input style="width: 150px !important;" type="number" class="form-control" id="remaining_quantity_{{$index}}"
               value="{{isset($update_item) ? $update_item->remaining_quantity : $item->remaining_quantity_for_accept}}" disabled>
    </td>

    <td>
        <input style="width: 150px !important;" type="number" class="form-control" id="refused_quantity_{{$index}}"
               value="{{isset($update_item) ? $update_item->remaining_quantity - $update_item->accepted_quantity  : 0}}" min="0"
               name="items[{{ isset($update_item) ? $update_item->supply_order_item_id : $item->id}}][refused_quantity]"
               onchange="calculateRefusedQuantity('{{$index}}')" onkeyup="calculateRefusedQuantity('{{$index}}')">

    </td>

    <td>
        <input style="width: 150px !important;" type="number" class="form-control" id="accepted_quantity_{{$index}}"
               value="{{ isset($update_item) ? $update_item->accepted_quantity : $item->remaining_quantity_for_accept}}" min="0"
               name="items[{{isset($update_item) ? $update_item->supply_order_item_id : $item->id}}][accepted_quantity]"
               onchange="calculateAcceptedQuantity('{{$index}}')" onkeyup="calculateAcceptedQuantity('{{$index}}')">

    </td>

    <td>
        <span id="defect_percent_{{$index}}">{{isset($update_item) ? ' % ' . $update_item->calculate_defected_percent : ' % 0'}}</span>
    </td>

    <td>
        <div class="input-group">
            <select style="width: 150px !important;" class="form-control js-example-basic-single"
                    name="items[{{isset($update_item) ? $update_item->supply_order_item_id : $item->id}}][store_id]" id="store_part_{{$index}}">

                @foreach($part->stores as $store)
                    <option value="{{$store->id}}"
                        {{isset($update_item) && $update_item->store_id == $store->id ? 'selected':'' }}>
                        {{$store->name}}
                    </option>
                @endforeach

            </select>
        </div>
    </td>
</tr>




