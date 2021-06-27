<tr class="text-center-inputs" id="item_{{$index}}">

    <td>
        <span>{{$index}}</span>
    </td>


    <td>
        <span style="width: 150px !important;display:block">{{$assetGroup->name}}</span>
    </td>

    <td class="inline-flex-span">
        <span>{{$asset->name}}</span>
        <input type="hidden" class="assetExist" value="{{$asset->id}}" name="items[{{$index}}][asset_id]">
    </td>

    <td>
        <div class="input-group">
            <select style="width: 150px !important;" class="form-control js-example-basic-single">
                @foreach($assetExpensesTypes as $type)
                    <option value="{{$type->id}}">{{$type->name}}</option>
                @endforeach
            </select>
        </div>
    </td>

    <td>
        <div class="input-group">
            <select style="width: 150px !important;" class="form-control js-example-basic-single"
                    name="items[{{$index}}][asset_expense_item_id]">
                @foreach($assetExpensesItems as $item)
                    <option value="{{$item->id}}">{{$item->item}}</option>
                @endforeach
            </select>
        </div>
    </td>

    <td>
        <input type="number" class="priceItem" name="items[{{$index}}][price]" onkeyup="addPriceToTotal('{{$index}}')">
    </td>
    <td>
        <div class="input-group" id="stores">
            <button type="button" class="btn btn-danger fa fa-trash" onclick="removeItem('{{$index}}')"></button>
        </div>
    </td>
</tr>


