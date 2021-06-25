<tr id="price_{{$index}}_segment_{{$key}}">
    <td>
        <div class="form-group">
            <div class="input-group">
                <div class="checkbox">
                    <input type="checkbox" checked
                           id="price_segment_checkbox_{{$index}}_{{$key}}"
                           value="{{isset($priceSegment) ? $priceSegment->id : ''}}"
                           name="{{isset($priceSegment) ? 'units['.$index.'][prices]['.$key.'][id]' : ''}}"
                           onclick="openPriceSegment('{{$key}}', '{{$index}}')">
                    <label for="price_segment_checkbox_{{$index}}_{{$key}}"></label>
                </div>
            </div>
        </div>
    </td>

    <td style="color: #0c0c0c">
        <input type="text" class="form-control"
{{--               {{isset($priceSegment) ? '':'disabled'}}--}}
               value="{{isset($priceSegment) ? $priceSegment->name : ''}}"
               id="segment_{{$index}}_{{$key}}"
               name="units[{{$index}}][prices][{{$key}}][name]"
        >
    </td>

    <td>
        <div class="input-group">
            <input type="text" class="form-control"
{{--                   {{isset($priceSegment) ? '':'disabled'}}--}}
                   value="{{isset($priceSegment) ? $priceSegment->purchase_price : 0}}"
                   id="price_segment_{{$index}}_{{$key}}"
                   name="units[{{$index}}][prices][{{$key}}][purchase_price]"
            >
        </div>
    </td>

    <td>
        <div class="input-group">
            <input type="text" class="form-control"
{{--                   {{isset($priceSegment) ? '':'disabled'}}--}}
                   value="{{isset($priceSegment) ? $priceSegment->sales_price : 0}}"
                   id="sales_price_segment_{{$index}}_{{$key}}"
                   name="units[{{$index}}][prices][{{$key}}][sales_price]"
            >
        </div>
    </td>

    <td>
        <div class="input-group">
            <input type="text" class="form-control"
{{--                   {{isset($priceSegment) ? '':'disabled'}}--}}
                   value="{{isset($priceSegment) ? $priceSegment->maintenance_price : 0}}"
                   id="maintenance_price_segment_{{$index}}_{{$key}}"
                   name="units[{{$index}}][prices][{{$key}}][maintenance_price]"
            >
        </div>
    </td>

    <td>
        <div class="input-group">

            @if(isset($priceSegment))

                <button type="button" title="remove price segment"
                        onclick="deleteOldPartPriceSegment('{{$index}}', '{{$key}}', '{{$priceSegment->id}}')"
                        class="btn btn-sm btn-danger">
                    <li class="fa fa-trash"></li>
                </button>
            @else
                <button type="button" title="remove price segment"
                        onclick="removePartPriceSegment('{{$index}}', '{{$key}}')"
                        class="btn btn-sm btn-danger">
                    <li class="fa fa-trash"></li>
                </button>
            @endif
        </div>
    </td>

</tr>
