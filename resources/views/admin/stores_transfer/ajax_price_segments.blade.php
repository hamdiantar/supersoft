<select class="form-control js-example-basic-single" id="price_segments_part_{{$index}}"
        name="items[{{$index}}][part_price_segment_id]"  onchange=" getPurchasePrice('price_segments_part_','{{$index}}'); calculateItem('{{$index}}');">

    <option value="">{{__('Select')}}</option>

    @foreach($priceSegments as $priceSegment)
        <option value="{{$priceSegment->id}}"  data-purchase_price="{{$priceSegment->purchase_price}}">
            {{$priceSegment->name}}
        </option>
    @endforeach
</select>
