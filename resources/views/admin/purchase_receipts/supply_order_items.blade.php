@foreach ($supplyOrder->items as $index => $item)
    @php
        $index +=1;
        $part = $item->part;
    @endphp
    @include('admin.purchase_receipts.part_raw')
@endforeach
