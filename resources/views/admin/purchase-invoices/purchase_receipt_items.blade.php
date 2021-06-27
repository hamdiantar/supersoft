@php
    $index = 0;
@endphp

@foreach($purchaseReceipts as $purchaseReceipt)

    @foreach($purchaseReceipt->items as $item)

        @php
            $part = $item->part;
            $index +=1;
        @endphp

        @include('admin.purchase-invoices.part_raw')
    @endforeach
@endforeach
