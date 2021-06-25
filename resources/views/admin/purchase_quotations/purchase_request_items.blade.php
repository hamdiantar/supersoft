
@foreach($purchaseRequest->items as $index=>$item)

    @php
        $part = $item->part;
        $index +=1;
    @endphp

    @include('admin.purchase_quotations.part_raw')

    @endforeach
