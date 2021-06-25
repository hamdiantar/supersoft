<select class="form-control js-example-basic-single" id="price_segments_part_{{$index}}"
        name="items[{{$index}}][part_price_segment_id]" onchange="getPurchasePrice('{{$index}}'); calculateItem('{{$index}}')">

    <option value="">{{__('Select Segment')}}</option>

    @foreach($priceSegments as $priceSegment)
        <option value="{{$priceSegment->id}}" data-purchase_price="{{$priceSegment->purchase_price}}">
            {{$priceSegment->name}}
        </option>
    @endforeach
</select>
