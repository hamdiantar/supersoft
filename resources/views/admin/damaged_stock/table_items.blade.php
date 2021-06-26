<div class="col-md-12">
    <div class="table-responsive scroll-table">
    <table class="table table-responsive table-hover table-bordered">
        <thead>
        <tr>
            <th width="2%"> # </th>
            <th width="16%"> {{ __('Name') }} </th>
            <th width="10%"> {{ __('Unit Quantity') }} </th>
            <th width="12%"> {{ __('Unit') }} </th>
            <th width="13%"> {{ __('Price Segments') }} </th>
            <th width="5%"> {{ __('Store') }} </th>
            <th width="5%"> {{ __('Quantity') }} </th>
            <th width="5%"> {{ __('Price') }} </th>
            <th width="5%"> {{ __('Total') }} </th>
            <th width="5%"> {{ __('Action') }} </th>
        </tr>
        </thead>
        <tbody id="parts_data">
            @if(isset($damagedStock))

                @foreach ($damagedStock->items as $index => $item)
                    @php
                        $max = 0;
                        $index +=1;
                        $part = $item->part;

                         $store = $part->stores()->where('store_id', $item->store_id)->first();

                        if ($store) {
                            $max += $store->pivot->quantity;
                        }
                    @endphp
                    @include('admin.damaged_stock.part_raw')
                @endforeach
            @endif
        </tbody>
        <tfoot>
        <tr>
            <th width="2%"> # </th>
            <th width="16%"> {{ __('Name') }} </th>
            <th width="10%"> {{ __('Unit Quantity') }} </th>
            <th width="12%"> {{ __('Unit') }} </th>
            <th width="13%"> {{ __('Price Segments') }} </th>
            <th width="5%"> {{ __('Store') }} </th>
            <th width="5%"> {{ __('Quantity') }} </th>
            <th width="5%"> {{ __('Price') }} </th>
            <th width="5%"> {{ __('Total') }} </th>

            <th width="5%"> {{ __('Action') }} </th>
        </tr>
        </tfoot>

        <input type="hidden" name="index" id="items_count" value="{{isset($damagedStock) ? $damagedStock->items->count() : 0}}">
    </table>
    </div>
</div>
