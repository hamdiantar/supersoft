
<tr data-id="{{$invoicePartIndex}}" id="remove_part_{{$invoicePartIndex}}" class="tr_{{$invoicePartIndex}}">

    <input type="hidden" name="parts[{{$invoicePartIndex}}][id]" value='{{$invoice_part_item->model_id}}'>

    <input type="hidden" id="percent-discount-{{$invoicePartIndex}}" value='{{$invoice_part_item->part->biggest_percent_discount}}'>

    <input type="hidden" id="amount-discount-{{$invoicePartIndex}}" value='{{$invoice_part_item->part->biggest_amount_discount}}'>

    <input type="hidden" id="maximum-sale-amount-{{$invoicePartIndex}}" value='{{$invoice_part_item->part->maximum_sale_amount}}'>

    <td>{{$invoice_part_item->part->name}}</td>

    <td>
        <select class="js-example-basic-single" style="width: 100%"
                name="parts[{{$invoicePartIndex}}][purchase_invoice_id]" id="purchase-invoice-{{$invoicePartIndex}}"
                onchange="purchaseInvoiceData('{{$invoice_part_item->model_id}}', {{$invoicePartIndex}})"
        >
            <option value="">{{__('select')}}</option>
            @foreach($invoice_part_item->part->items as $item)
                @if($item && $item->invoice )
                    <option value="{{$item->invoice->id}}"
                            {{$invoice_part_item->purchase_invoice_id == $item->invoice->id ? 'selected':''}}>
                        {{$item->invoice->invoice_number}}
                    </option>
                @endif
            @endforeach
        </select>
    </td>

    <td>
        <input type="number" class="form-control" readonly value="{{$invoice_part_item->qty}}" id="available-qy-{{$invoicePartIndex}}">
    </td>

    <td>

        @php
        $purchaseInvoiceItem = $invoice_part_item->purchaseInvoice->items()->where('part_id', $invoice_part_item->model_id)->first();

        $itemQty = $purchaseInvoiceItem && $invoice_part_item->purchase_qty >= $invoice_part_item->qty? $purchaseInvoiceItem->purchase_qty:$invoice_part_item->qty;
        @endphp

        <select class="js-example-basic-single" style="width: 100%"
                name="parts[{{$invoicePartIndex}}][qty]" id="sold-qy-{{$invoicePartIndex}}"
                onchange="setServiceValues('{{$invoicePartIndex}}')"
        >
            <option value="">{{__('select')}}</option>
            @for($i=1; $i<=$itemQty; $i++)
                <option value="{{$i}}" {{$invoice_part_item->qty == $i ? 'selected':''}}>{{$i}}</option>
            @endfor
        </select>
    </td>

    <input type="hidden" class="form-control" readonly value="{{$invoice_part_item->part->last_selling_price}}"
           id="last_purchase_price-{{$invoicePartIndex}}"
    >

    <td>
        <input type="number" class="form-control" value="{{$invoice_part_item->price}}" min="0"
               name="parts[{{$invoicePartIndex}}][price]"
               id="selling-price-{{$invoicePartIndex}}"
               onkeyup="setServiceValues('{{$invoicePartIndex}}')"
               onchange="setServiceValues('{{$invoicePartIndex}}')"
        >
    </td>

    <td>

        <div class="radio primary">
            <input type="radio"
                   name="parts[{{$invoicePartIndex}}][discount_type]"
                   id="discount-type-amount-{{$invoicePartIndex}}" value="amount"
                   {{$invoice_part_item->discount_type == 'amount' ? 'checked':''}}
                   onclick="setServiceValues('{{$invoice_part_item->model_id}}', {{$invoicePartIndex}})"
            >
            <label for="discount-type-amount-{{$invoicePartIndex}}">{{__('Amount')}}</label>
        </div>

        <div class="radio primary">
            <input type="radio" name="parts[{{$invoicePartIndex}}][discount_type]"
                   id="discount-type-percent-{{$invoicePartIndex}}" value="percent"
                   {{$invoice_part_item->discount_type == 'percent' ? 'checked':''}}
                   onclick="setServiceValues('{{$invoicePartIndex}}')"
            >
            <label for="discount-type-percent-{{$invoicePartIndex}}">{{__('Percent')}}</label>
        </div>

    </td>

    <td>
        <input type="number" class="form-control" value="{{$invoice_part_item->discount}}" min="0"
               name="parts[{{$invoicePartIndex}}][discount]"
               onkeyup="setServiceValues('{{$invoicePartIndex}}')"
               onchange="setServiceValues('{{$invoicePartIndex}}')"
               id="discount-{{$invoicePartIndex}}">
    </td>

    <td>
        <input type="number" class="form-control" readonly value="{{$invoice_part_item->sub_total}}"
               id="total-{{$invoicePartIndex}}">
    </td>

    <td>
        <input type="number" class="form-control" readonly value="{{$invoice_part_item->total_after_discount}}"
               id="total-after-discount-{{$invoicePartIndex}}">
    </td>

    <td>
        <i class="fa fa-trash fa-2x"
           onclick="removePartsFromTable('{{$invoicePartIndex}}')"
           style="color:#F44336; cursor: pointer">
        </i>
    </td>
</tr>
