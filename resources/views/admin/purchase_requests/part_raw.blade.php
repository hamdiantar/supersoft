<tr id="tr_part_{{$index}}" class="remove_on_change_branch text-center-inputs">

    <td>
        <span id="item_number_{{$index}}">{{$index}}</span>
    </td>

    <td class="text-center">
        <!-- <input type="text" disabled value="{{$part->name}}" class="form-control" style="text-align: center;"> -->
        <span>{{$part->name}}</span> 
        <input type="hidden" value="{{$part->id}}" name="items[{{$index}}][part_id]" class="form-control"
               style="text-align: center;">

        @if(isset($item) && isset($request_type) && $request_type == 'approval')
            <input type="hidden" value="{{$item->id}}" name="items[{{$index}}][item_id]" class="form-control"
                   style="text-align: center;">
        @endif
    </td>

    <td>
        <div class="input-group">

            <select style="width: 150px !important;" class="form-control js-example-basic-single" name="items[{{$index}}][part_price_id]"
                    id="prices_part_{{$index}}"
                {{isset($request_type) && $request_type == 'approval' ? 'disabled' : ''}}
            >

                @foreach($part->prices as $price)
                    <option
                        value="{{$price->id}}"{{isset($item) && $item->part_price_id == $price->id ? 'selected':''}}>
                        {{optional($price->unit)->unit}}
                    </option>
                @endforeach
            </select>
        </div>
        {{input_error($errors, 'items['.$index.'][part_price_id]')}}
    </td>

    <td>
        <input style="width: 130px !important;margin:0 auto;display:block" type="number" class="form-control" id="quantity_{{$index}}"
               value="{{isset($item) ? $item->quantity : 0}}" min="0"
               name="items[{{$index}}][quantity]" {{isset($request_type) && $request_type == 'approval' ? 'disabled' : ''}}>

        {{input_error($errors, 'items['.$index.'][quantity]')}}
    </td>

    @if(isset($request_type) && $request_type == 'approval')
        <td>
            <input style="width: 150px !important;" type="number" class="form-control" value="{{isset($item) ? $item->part->quantity : 0}}" disabled>
        </td>

        <td>
            <input style="width: 150px !important;" type="number" class="form-control" id="quantity_{{$index}}"
                   value="{{isset($item) ? $item->approval_quantity : 0}}"
                   min="0" name="items[{{$index}}][approval_quantity]">
        </td>
    @endif

    <td>
     
        <a data-toggle="modal" data-target="#part_types_{{$index}}" title="Part Types" class="btn btn-primary">
                <i class="fa fa-check-circle"> </i> {{__('Types')}}
            </a>

            @if(!isset($request_type) || ( isset($request_type) && $request_type != 'approval'))
                <button type="button" class="btn btn-danger fa fa-trash" onclick="removeItem('{{$index}}')"></button>
            @endif

            <div style="padding:5px !important;">
            @if(isset($request_type) && $request_type == 'approval')
                <a data-toggle="modal" data-target="#part_quantity_{{$index}}"
                   title="Part quantity" class="btn btn-info">
                    <li class="fa fa-cubes"></li> {{__('Stores Qty')}}
                </a>
            @endif
</div>


  
    </td>

    <td style="display: none;">

        @php
            $partTypes = isset($partTypes) ? $partTypes : partTypes($part);
        @endphp

        @foreach($partTypes as $key=>$value)
            <div class="checkbox">
                <input type="checkbox" id="item_type_real_checkbox_{{$index}}_{{$key}}" value="{{$key}}"
                       name="items[{{$index}}][item_types][]"
                    {{isset($item) && in_array($key, $item->spareParts->pluck('id')->toArray()) ? 'checked':''}}
                >

                <label for="item_type_real_checkbox_{{$index}}_{{$key}}"></label>
            </div>
        @endforeach

    </td>

</tr>




