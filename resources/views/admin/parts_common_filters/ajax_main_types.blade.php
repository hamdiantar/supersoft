<span class="input-group-addon fa fa-file"></span>

<select class="form-control js-example-basic-single" id="main_types_select" onchange="dataByMainType()">

    <option value="">{{__('Select Type')}}</option>

    @foreach($mainTypes as $key=>$type)
        <option value="{{$type->id}}">
            {{$key+1}} . {{$type->type}}
        </option>
    @endforeach
</select>
