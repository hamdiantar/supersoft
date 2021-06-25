<div class="form-group has-feedback col-md-3">
    <label for="inputSymbolAR" class="control-label">{{__('Customer Name')}}</label>
    <input type="text" name="customer_id" readonly="readonly" class="form-control"
           value="{{optional($salesInvoice->customer)->name}}">

    {{input_error($errors,'customer_id')}}
</div>

<div class="form-group has-feedback col-md-3">
    <label for="inputSymbolAR" class="control-label">{{__('Customer Discount')}}</label>
    <input type="text" id="customer_group_discount" readonly="readonly" class="form-control"
           value="{{$salesInvoice->customer_discount}}">
    {{input_error($errors,'customer_discount')}}
</div>

<div class="form-group has-feedback col-md-3">
    <label for="inputSymbolAR" class="control-label">{{__('Discount Type')}}</label>
    <input type="text" id="customer_group_discount" readonly="readonly" class="form-control"
           value="{{__($salesInvoice->customer_discount_type)}}">
    {{input_error($errors,'customer_discount_type')}}
</div>

{{--    Invoice Items      --}}
<div class="form-group has-feedback col-sm-12">
    <div class="box-content-wg box-content card bordered-all js__card">
        <h4 class="box-title bg-blue-1 with-control">
            {{__('Invoice Items Details')}}
            <span class="controls">
                <button type="button" class="control fa fa-minus js__card_minus"></button>
                <button type="button" class="control fa fa-times js__card_remove"></button>
            </span>
        </h4>

        <div class="card-content js__card_content" style="">
        <!-- <h3 style="text-align: center;">{{__('Invoice Items Details')}}</h3> -->
            <div class="">
                <table id="invoiceItemsDetails" class="table table-bordered" style="width:100%">
                    <thead style="background-color: #ada3a361">
                    <tr>
                        <th scope="col">{!! __('Spare Part Name') !!}</th>
                        <th scope="col">{!! __('Invoices') !!}</th>
                        <th scope="col">{!! __('Available quantity') !!}</th>
                        <th scope="col">{!! __('Return quantity') !!}</th>
                        {{--     <th scope="col">{!! __('last purchase price') !!}</th>--}}
                        <th scope="col">{!! __('selling price') !!}</th>
                        <th scope="col">{!! __('Discount Type') !!}</th>
                        <th scope="col">{!! __('Discount') !!}</th>
                        <th scope="col">{!! __('Total') !!}</th>
                        <th scope="col">{!! __('Total After Discount') !!}</th>
                        <th scope="col">
                            <input type="checkbox" id="selectAllItems" checked onclick="select_all()">
                        </th>
                    </tr>
                    </thead>
                    <tbody id="add_parts_details">

                    @foreach($salesInvoice->items as $index=>$sales_invoice_item)
                        @include('admin.sales_invoice_return.items')
                    @endforeach

                    </tbody>

                </table>

                <input type="hidden" name="items_count" id="invoice_items_count"
                       value="{{$salesInvoice->number_of_items}}">
                <input type="hidden" name="parts_count" id="parts_count" value="{{$salesInvoice->number_of_items}}">
            </div>
        </div>
    </div>
</div>
<hr>

{{--    Invoice total      --}}
<div class="form-group has-feedback col-sm-12">

    <table class="table table-bordered" style="width:100%">
        <thead>
        <tr>

            <th scope="col">{!! __('Invoice Type') !!}</th>
            <th scope="col">{!! __('Total') !!}</th>
            <th scope="col">{!! __('Parts Number') !!}</th>
            <th scope="col">{!! __('Discount Type') !!}</th>
            <th scope="col">{!! __('Discount') !!}</th>
            <th scope="col">{!! __('Customer Discount') !!}</th>
            <th scope="col">{!! __('Points Discount') !!}</th>
            <th scope="col">{!! __('Total After Discount') !!}</th>
            <th scope="col">
                <a style="cursor:pointer" data-remodal-target="m-2" title="get taxes">
                    <i class="fa fa-eye " style="color:#F44336"></i>
                </a>
                {!! __('Taxes') !!}
            </th>
            <th scope="col">{!! __('Final Total') !!}</th>
        </tr>
        </thead>
        <tbody>
        <tr>

            <td>
                <div class="radio primary">
                    <input type="radio" name="type" id="type_cash" value="cash" checked>
                    <label for="type_cash">{{__('Cash')}}</label>
                </div>

                <div class="radio primary">
                    <input type="radio" name="type" id="type_credit" value="credit">
                    <label for="type_credit">{{__('Credit')}}</label>
                </div>
            </td>

            <td>
                <input type="text" class="form-control" readonly name="total_before_discount"
                       id="total_before_discount" value="{{$salesInvoice->sub_total}}">
            </td>
            <td>
                <input type="text" class="form-control" readonly name="number_of_items"
                       value="{{$salesInvoice->number_of_items}}" id="number_of_items">
            </td>
            <td>
                <div class="radio primary">
                    <input type="radio" name="discount_type" id="discount_type_amount"
                           value="amount" onclick="implementInvoiceDiscount()"
                        {{$salesInvoice->discount_type == 'amount'? 'checked':''}}
                    >
                    <label for="discount_type_amount">{{__('Amount')}}</label>
                </div>
                <div class="radio primary">
                    <input type="radio" name="discount_type"
                           id="discount_type_percent" value="percent"
                           onclick="implementInvoiceDiscount()"
                        {{$salesInvoice->discount_type == 'percent'? 'checked':''}}
                    >
                    <label for="discount_type_percent">{{__('Percent')}}</label>
                </div>

            </td>
            <td>
                <input type="number" class="form-control" value="{{$salesInvoice->discount}}" id="discount"
                       onchange="implementInvoiceDiscount()"
                       onkeyup="implementInvoiceDiscount()"
                       name="discount" min="0">
            </td>


            {{--  CUSTOMER DISCOUNT --}}
            <td>
                <div class="row">
                    <div class="col-md-2" style="margin-top: 10px;">
                        <div class="form-group has-feedback">
                            <input type="checkbox" id="customer_discount_check"
                                   name="customer_discount_check"
                                   {{$salesInvoice->customer_discount_status ? 'checked':''}}
                                   onclick="implementInvoiceDiscount()">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <input type="text" class="form-control" style="width:42px;"
                               value="{{$salesInvoice->customer_discount_type == 'amount'? '$':'%' }}"
                               id="customer_discount_type"
                               name="customer_discount_type" min="0" disabled
                        >
                        <input type="hidden" value="{{$salesInvoice->customer_discount_type}}"
                               id="customer_discount_type_value">
                    </div>

                    <div class="col-md-7">
                        <input type="number" class="form-control customer_group_discount"
                               value="{{$salesInvoice->customer_discount}}" id="customer_group_discount"
                               name="customer_group_discount" min="0" disabled="">
                    </div>

                </div>
            </td>

            <td>

                <input type="hidden" name="points_rule_id" value="{{$salesInvoice->points_rule_id}}" class="rule_id">

                <input type="hidden" name="customer_points" id="customer_points" value="0">

                <input type="text" class="form-control" readonly value="{{$salesInvoice->points_discount}}"
                       name="points_discount" id="points_discount">
            </td>

            <td>
                <input type="text" class="form-control" readonly value="{{$salesInvoice->total_after_discount}}"
                       name="total_after_discount" id="total_after_discount">
            </td>
            <td>
                <input type="text" class="form-control" readonly name="invoice_tax"
                       id="invoice_tax" value="{{$salesInvoice->tax}}">
            </td>
            <td>
                <input type="text" class="form-control" readonly value="{{$salesInvoice->total}}"
                       name="final_total" id="final_total">
            </td>
        </tr>
        </tbody>

    </table>
</div>

