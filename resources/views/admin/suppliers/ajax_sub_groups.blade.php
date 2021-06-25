<span class="input-group-addon fa fa-file"></span>
<select name="sub_group_id" id="sub_group_id" class="form-control js-example-basic-single">

    <option value="">{{__('Select')}}</option>

    @foreach($subGroups as $key=>$value)
        <option value="{{$key}}" {{isset($supplier) && $supplier->sub_group_id == $key ? 'selected':''}}>
            {{$value}}
        </option>
    @endforeach
</select>
