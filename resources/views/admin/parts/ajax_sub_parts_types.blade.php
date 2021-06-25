<span class="input-group-addon fa fa-file"></span>

<select class="form-control js-example-basic-single data_by_branch"
        name="spare_part_type_ids[]" id="sub_parts_types_options" multiple>
    @foreach($subTypes as $key => $value)

        <option value="{{$key}}"
                {{isset($part) && $part && in_array($key, $part->spareParts()->pluck('spare_part_type_id')->toArray())? 'selected':''}}
                class="removeToNewData">

            {{$value}}
        </option>

    @endforeach
</select>
