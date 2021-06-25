<span class="input-group-addon fa fa-file"></span>
<select class="form-control js-example-basic-single" name="concession_type_item_id">

    <option value="">{{__('Select Item')}}</option>

    @foreach($concessionItems as $item)
        <option value="{{$item->id}}">{{__($item->name)}}</option>
    @endforeach
</select>
