<span class="input-group-addon fa fa-file"></span>

<select class="form-control js-example-basic-single" id="sub_types_select" onchange="dataBySubType()">

    <option value="">{{__('Select Type')}}</option>

    @foreach($subTypes as $id=>$type)
        <option value="{{$id}}">
            {{$type}}
        </option>
    @endforeach
</select>
