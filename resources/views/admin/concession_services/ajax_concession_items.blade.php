
<select class="form-control js-example-basic-single" name="item_id" onchange="{{$search ? '': 'getConcessionPartsData()'}}" id="concession_item_id">
    <option value="">{{__('Select Item')}}</option>
    @foreach($data as $item)

        <option value="{{isset($item->id) ? $item->id : ''}}" class="remove_items">
            {{$item->number}}
        </option>

        @endforeach
</select>
