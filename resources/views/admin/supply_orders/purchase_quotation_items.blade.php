
@php
$index = 0;
@endphp

@foreach($purchaseQuotations as $purchaseQuotation)

    @foreach($purchaseQuotation->items as $item)
        @php
            $part = $item->part;
            $index +=1;
        @endphp

        @include('admin.supply_orders.part_raw')
    @endforeach
@endforeach
