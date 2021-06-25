<span class="input-group-addon fa fa-file"></span>

<select class="form-control js-example-basic-single" id="parts_select" onchange="selectPart()">

    <option value="">{{__('Select Part')}}</option>

    @foreach($parts as $part)
        <option value="{{$part->id}}">
             {{$part->Prices->first() ? $part->Prices->first()->barcode . ' - ' : ''}} {{$part->name}}
        </option>
    @endforeach
</select>
