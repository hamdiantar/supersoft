<table class="table table-responsive table-bordered table-striped">
    <thead>
        <tr>
            <th width="13%"> {{ __('opening-balance.part') }} </th>
            <th width="5%"> {{ __('opening-balance.default-quantity') }} </th>
            <th width="12%"> {{ __('opening-balance.units') }} </th>
            <th width="13%"> {{ __('opening-balance.price-segment') }} </th>
            <th width="13%"> {{ __('opening-balance.quantity') }} </th>
            <th width="13%"> {{ __('opening-balance.buy-price') }} </th>
            <th width="13%"> {{ __('opening-balance.total') }} </th>
            <th width="13%"> {{ __('opening-balance.store') }} </th>
            <th width="5%"> {{ __('opening-balance.clear') }} </th>
        </tr>
    </thead>
    <tbody id="opening-balance-items">
        @if(isset($balance_items))
            @foreach ($balance_items as $item)
                @include('opening-balance.edit-row')
            @endforeach
        @endif
    </tbody>
    <tfoot>
        <tr>
            <th> {{ __('opening-balance.part') }} </th>
            <th> {{ __('opening-balance.default-quantity') }} </th>
            <th> {{ __('opening-balance.units') }} </th>
            <th> {{ __('opening-balance.price-segment') }} </th>
            <th> {{ __('opening-balance.quantity') }} </th>
            <th> {{ __('opening-balance.buy-price') }} </th>
            <th> {{ __('opening-balance.total') }} </th>
            <th> {{ __('opening-balance.store') }} </th>
            <th> {{ __('opening-balance.clear') }} </th>
        </tr>
    </tfoot>
</table>