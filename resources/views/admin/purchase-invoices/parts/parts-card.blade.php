<div class="row" style="margin-top:50px">

    @include('admin.invoices_parts_filter.index')


    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group has-feedback table-wg-inv">
            <div class="anyClass-wg">
                <div class="table-responsive">
                    <table id="invoiceItemsDetails" class="table table-bordered" style="">

                        @include('admin.invoices_parts_filter.footer')

                        <thead style="background-color: #ada3a361">
                        <tr>
                            <th style="" scope="col">{!! __('Spare Part Name') !!}</th>
                            <th style="width:5%" scope="col">{!! __('Available quantity') !!}</th>
                            <th style="width:5%" scope="col">{!! __('Purchased quantity') !!}</th>
                            <th style="width:10%" scope="col">{!! __('Units') !!}</th>
                            <th style="width:10%" scope="col">{!! __('Prices') !!}</th>
                            <th style="width:10%" scope="col">{!! __('purchase price') !!}</th>
{{--                            <th style="width:15%" scope="col">{!! __('last purchase price') !!}</th>--}}
                            <th style="" scope="col">{!! __('Store') !!}</th>
                            <th style="" scope="col">{!! __('Discount Type') !!}</th>
                            <th style="width:8%" scope="col">{!! __('Discount') !!}</th>
                            <th style="width:12%" scope="col">{!! __('Total') !!}</th>
                            <th style="width:10%" scope="col">{!! __('Total After Discount') !!}</th>
                            <th style="width:10%" scope="col">{!! __('Taxes') !!}</th>
                            <th style="width:12%" scope="col">{!! __('Total') !!}</th>
                            <th style="width:15%" scope="col">{!! __('Action') !!}</th>
                        </tr>
                        </thead>

                        <tbody id="add_parts_details">

                        @if(isset($purchase_invoice))
                            @foreach($purchase_invoice->items as $index=>$item)
                                @php
                                    $part = $item->part;
                                @endphp

                                @include('admin.purchase-invoices.parts.edit_part_details')

                            @endforeach
                        @endif

                        </tbody>
                    </table>
                    <input type="hidden" name="items_count" id="invoice_items_count"
                           value="{{isset($purchase_invoice) ? $purchase_invoice->number_of_items : 0 }}">

                    <input type="hidden" name="parts_count" id="parts_count"
                           value="{{isset($purchase_invoice) ? $purchase_invoice->number_of_items : 0 }}">
                </div>
            </div>
        </div>
    </div>
</div>


