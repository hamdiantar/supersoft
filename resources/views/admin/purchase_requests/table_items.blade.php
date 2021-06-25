<div class="col-md-12">
    <div class="table-responsive scroll-table">
    <table class="table table-responsive table-bordered table-striped">
        <thead>
        <tr>
            <th width="2%"> # </th>
            <th width="13%"> {{ __('Name') }} </th>
            <th width="8%"> {{ __('Unit') }} </th>
            <th width="5%"> {{ __('Requested Qty') }} </th>
            @if(isset($request_type) && $request_type == 'approval')
                <th width="5%"> {{ __('Available Quantity') }} </th>
                <th width="5%"> {{ __('Approval Quantity') }} </th>
            @endif
            <th width="5%"> {{ __('Action') }} </th>
        </tr>
        </thead>
        <tbody id="parts_data">
        @if(isset($purchaseRequest))

            @foreach ($purchaseRequest->items as $index => $item)
                @php
                    $index +=1;
                    $part = $item->part;
                @endphp
                @include('admin.purchase_requests.part_raw')
            @endforeach
        @endif
        </tbody>
        <tfoot>
        <tr>
            <th width="2%"> # </th>
            <th width="13%"> {{ __('Name') }} </th>
            <th width="8%"> {{ __('Unit') }} </th>
            <th width="5%"> {{ __('Requested Qty') }} </th>
            @if(isset($request_type) && $request_type == 'approval')
                <th width="5%"> {{ __('Available Quantity') }} </th>
                <th width="5%"> {{ __('Approval Quantity') }} </th>
            @endif
            <th width="5%"> {{ __('Action') }} </th>
        </tr>
        </tfoot>

        <input type="hidden" name="index" id="items_count"
               value="{{isset($purchaseRequest) ? $purchaseRequest->items->count() : 0}}">
    </table>
</div>
</div>
