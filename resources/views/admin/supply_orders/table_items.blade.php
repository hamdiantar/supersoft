<div class="col-md-12">
    <div class="table-responsive scroll-table">
    <table class="table table-responsive table-bordered table-striped">
        <thead>
        <tr>
            <th width="2%"> # </th>
            <th width="9%"> {{ __('Name') }} </th>
            <th width="8%"> {{ __('Unit') }} </th>
            <th width="8%"> {{ __('Price Segments') }} </th>
            <th width="8%"> {{ __('Price') }} </th>
            <th width="7%"> {{ __('Quantity') }} </th>
            <th width="4%"> {{ __('Discount Type') }} </th>
            <th width="5%"> {{ __('Discount') }} </th>
            <th width="8%"> {{ __('Total Before Discount') }} </th>
            <th width="8%"> {{ __('Total After Discount') }} </th>
            <th width="8%"> {{ __('Taxes') }} </th>
            <th width="8%"> {{ __('Total') }} </th>
            <th width="5%"> {{ __('Action') }} </th>
        </tr>
        </thead>
        <tbody id="parts_data">

        @if(isset($supplyOrder))

            @foreach ($supplyOrder->items as $index => $update_item)
                @php
                    $index +=1;
                    $part = $update_item->part;
                @endphp
                @include('admin.supply_orders.part_raw')
            @endforeach
        @endif


        </tbody>
        <tfoot>
        <tr>
            <th width="2%"> # </th>
            <th width="9%"> {{ __('Name') }} </th>
            <th width="8%"> {{ __('Unit') }} </th>
            <th width="8%"> {{ __('Price Segments') }} </th>
            <th width="8%"> {{ __('Price') }} </th>
            <th width="7%"> {{ __('Quantity') }} </th>
            <th width="4%"> {{ __('Discount Type') }} </th>
            <th width="5%"> {{ __('Discount') }} </th>
            <th width="8%"> {{ __('Total Before Discount') }} </th>
            <th width="8%"> {{ __('Total After Discount') }} </th>
            <th width="8%"> {{ __('Taxes') }} </th>
            <th width="8%"> {{ __('Total') }} </th>
            <th width="5%"> {{ __('Action') }} </th>
        </tr>
        </tfoot>

        <input type="hidden" name="index" id="items_count" value="{{isset($supplyOrder) ? $supplyOrder->items->count() : 0}}">
    </table>
</div>
</div>
