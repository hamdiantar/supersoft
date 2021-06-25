<tr data-id="{{$part->id}}" data-barcode="{{$part->barcode ?? '#' }}" id="{{$part->id}}" class="tr_{{$part->id}}">

    <input type="hidden" name="items[{{$part->id}}][id]" value='{{$part->id}}'>

    <input type="hidden" name="part_id" value='{{$part->id}}' id="part-{{$items_count}}">

    <td>{{$part->name}}</td>

    <td>
        <input style="width:100px" type="number" class="form-control" readonly value="{{$part->quantity}}"
               name="available_qy[]" id="available-qy-{{$part->id}}">
    </td>

    <td>
        <input type="number" class="form-control" min="0" max="{{$part->quantity}}" value="0"
               name="items[{{$part->id}}][purchase_qty]"
               onkeyup="setServiceValues({{$part->id}})"
               onchange="setServiceValues({{$part->id}})"
               id="purchased-qy-{{$part->id}}">
    </td>

    <input type="hidden" class="form-control" readonly value="{{$part->last_purchase_price}}"
           name="items[{{$part->id}}][last_purchase_price]"
           id="last_purchase_price-{{$part->id}}">

    <td>
        <select name="items[{{$part->id}}][part_price_id]" class="js-example-basic-single"
                id="part_prices_{{$part->id}}" onchange="unitPrices('{{$part->id}}')">
            <option value="">{{__('Select')}}</option>
            @foreach($part->prices as $price)
                <option value="{{$price->id}}"
                        data-sales-price="{{$price->selling_price}}"
                        data-purchase-price="{{$price->purchase_price}}"
                >
                    {{optional($price->unit)->unit}}
                </option>
            @endforeach
        </select>
    </td>

    <td id="td_unit_prices_{{$part->id}}">
        <select name="items[{{$part->id}}][part_price_segment_id]" class="js-example-basic-single"
                id="unit_prices_{{$part->id}}">
            <option value="">{{__('Select')}}</option>
        </select>
    </td>

    <td>
        <input type="text" class="form-control" value="{{$part->purchase_price}}"
               name="items[{{$part->id}}][purchase_price]"
               id="purchased-price-{{$part->id}}" onkeyup="setServiceValues({{$part->id}})">
    </td>


    {{--    <td>--}}
    {{--        <input type="text"  class="form-control" readonly value="{{$part->last_purchase_price}}">--}}
    {{--    </td>--}}

    <td>
        <select name="items[{{$part->id}}][store_id]" class="js-example-basic-single">
            @foreach($stores as $k=>$v)
                <option value="{{$k}}" {{  $part->store_id == $k ? 'selected':'' }}>
                    {{$v}}
                </option>
            @endforeach
        </select>
    </td>

    <td>
        <ul class="list-inline">
            <li>
                <div class="radio info">
                    <input type="radio" name="items[{{$part->id}}][discount_type]" value="amount" checked
                           id="radio-10-discount-amount-{{$part->id}}" onclick="setServiceValues({{$part->id}})"
                    >
                    <label for="radio-10-discount-amount-{{$part->id}}">
                        {{__('Amount')}}
                    </label>
                </div>
            </li>

            <li>
                <div class="radio pink">
                    <input type="radio" name="items[{{$part->id}}][discount_type]" value="percent"
                           onclick="setServiceValues({{$part->id}})"
                           id="radio-12-discount-percent-{{$part->id}}">

                    <label for="radio-12-discount-percent-{{$part->id}}">
                        {{__('Percent')}}
                    </label>
                </div>
            </li>

        </ul>
    </td>

    <td>
        <input type="number" class="form-control" value="0" name="items[{{$part->id}}][discount]"
               onkeyup="setServiceValues({{$part->id}})" onchange="setServiceValues({{$part->id}})"
               id="discount-{{$part->id}}">
    </td>

    <td>
        <input type="number" class="form-control" readonly value="0"
               name="items[{{$part->id}}][subtotal]" id="subtotal-{{$part->id}}">
    </td>

    <td>
        <input type="number" class="form-control" readonly value="0"
               name="items[{{$part->id}}][total_after_discount]"
               id="total-after-discount-{{$part->id}}"
        >
    </td>

    <td>

        <input type="hidden" id="part_taxes_count_{{$part->id}}" value="{{$part->taxes->count()}}">

        @if($part->taxes->count())
            <ul class="list-inline">
                @foreach($part->taxes as $key=>$tax)
                    <li>
                        <div>
                            <input type="checkbox" id="part_tax_check_{{$part->id}}_{{$key}}"

                                   {{!auth()->user()->can('purchase_invoices_active_tax') ? 'disabled':''}}

                                   name="items[{{$part->id}}][taxes][{{$key}}]"
                                   value="{{$tax->id}}" data-tax-type="{{$tax->tax_type}}"
                                   data-tax-value="{{$tax->value}}"
                                   onclick="setServiceValues('{{$part->id}}')" checked>
                            <label>
                                {{$tax->value}} {{$tax->tax_type == 'amount'? '$':'%'}}
                            </label>
                        </div>

                        @if(!auth()->user()->can('purchase_invoices_active_tax'))

                            <input type="checkbox" name="items[{{$part->id}}][taxes][{{$key}}]" value="{{$tax->id}}"
                                   checked style="display: none">
                        @endif
                    </li>
                @endforeach
            </ul>
        @else
            <span>{{__('no tax')}}</span>
        @endif
    </td>

    <td>
        <input type="number" class="form-control" readonly value="0" name="items[{{$part->id}}][total]"
               id="total-part-{{$part->id}}">
    </td>


    <td>
        <i class="fa fa-trash fa-2x" onclick="removeServiceFromTable({{$part->id}})"
           style="color:#F44336; cursor: pointer">
        </i>
    </td>
</tr>
