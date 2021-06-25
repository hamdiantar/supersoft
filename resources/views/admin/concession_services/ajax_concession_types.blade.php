{{--<span class="input-group-addon fa fa-file"></span>--}}
<select class="form-control js-example-basic-single" name="concession_type_id" id="concession_type_id" onchange="getConcessionItems()">

    <option value="">{{__('Select Type')}}</option>

    @foreach($concessionTypes as $item)
        <option value="{{$item->id}}"
            {{isset($concession) && $concession->concession_type_id == $item->id? 'selected':''}}>
            {{$item->name}}
        </option>
    @endforeach
</select>
