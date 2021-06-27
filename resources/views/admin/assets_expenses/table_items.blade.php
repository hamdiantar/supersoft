<div class="col-md-12">
    <div class="table-responsive scroll-table">
        <table class="table table-responsive table-hover table-bordered">
            <thead>
            <tr>
                <th width="2%"> # </th>
                <th width="16%"> {{ __('Assets Groups') }} </th>
                <th width="10%"> {{ __('Assets') }} </th>
                <th width="10%"> {{ __('Expenses Types') }} </th>
                <th width="10%"> {{ __('Expenses Items') }} </th>
                <th width="12%"> {{ __('Expense Cost') }} </th>
                <th width="5%"> {{ __('Action') }} </th>
            </tr>
            </thead>
            <tbody id="items_data">
            @if(isset($settlement))

                @foreach ($settlement->items as $index => $item)
                    @php
                        $index +=1;
                        $part = $item->part;
                        $max = 0;

                         $store = $part->stores()->where('store_id', $item->store_id)->first();

                        if ($store) {
                            $max += $store->pivot->quantity;
                        }

                    @endphp
                    @include('admin.settlements.part_raw')
                @endforeach
            @endif
            </tbody>
            <tfoot>
            <tr>
                <th width="2%"> # </th>
                <th width="16%"> {{ __('Assets Groups') }} </th>
                <th width="10%"> {{ __('Assets') }} </th>
                <th width="10%"> {{ __('Expenses Types') }} </th>
                <th width="10%"> {{ __('Expenses Items') }} </th>
                <th width="12%"> {{ __('Expense Cost') }} </th>
                <th width="5%"> {{ __('Action') }} </th>
            </tr>
            </tfoot>
            <input type="hidden" name="index" id="items_count" value="{{isset($settlement) ? $settlement->items->count() : 0}}">
        </table>
    </div>
</div>
