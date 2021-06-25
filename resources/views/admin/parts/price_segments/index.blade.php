<table class="table table-bordered" style="width:100%">
    <thead style="background-color: #ada3a361">
    <tr>
        <th scope="col">{!! __('Check') !!}</th>
        <th scope="col">{!! __('Price Name') !!}</th>
        <th scope="col">{!! __('Purchase Price') !!}</th>
        <th scope="col">{!! __('Sales Price') !!}</th>
        <th scope="col">{!! __('Maintenance Price') !!}</th>
        <th scope="col">{!! __('Action') !!}</th>
    </tr>
    </thead>
    <tbody id="part_price_{{$index}}_segments">
        @if(isset($price))
            @foreach($price->partPriceSegments as $key => $priceSegment)

                @php
                $key+=1;
                @endphp

                @include('admin.parts.price_segments.ajax_price_segment')
            @endforeach
        @endif
    </tbody>

</table>

<input type="hidden" id="part_price_{{$index}}_segments_count" value="{{isset($price) ? $price->partPriceSegments->count() : 0 }}">

<button type="button" title="new price" onclick="newPartPriceSegment('{{$index}}')" class="btn btn-sm btn-info">
    <li class="fa fa-plus"></li> {{__('New price')}}
</button>
