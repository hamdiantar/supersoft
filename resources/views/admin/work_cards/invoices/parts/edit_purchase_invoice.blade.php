
<tr data-id="{{$invoicePartIndex}}" id="remove_part_{{$invoicePartIndex}}_{{$maintenance->id}}"
    class="tr_{{$invoicePartIndex}}_{{$maintenance->id}}">

    <input type="hidden" name="part_ids_{{$maintenance->id}}[]" value='{{$invoice_part_item->model_id}}'>
    <input type="hidden" name="part_index_{{$maintenance->id}}[]" value='{{$invoicePartIndex}}'>

    <input type="hidden" id="percent-discount-{{$invoicePartIndex}}_{{$maintenance->id}}"
           value='{{$invoice_part_item->part->biggest_percent_discount}}'>

    <input type="hidden" id="amount-discount-{{$invoicePartIndex}}_{{$maintenance->id}}"
           value='{{$invoice_part_item->part->biggest_amount_discount}}'>

    <input type="hidden" id="maximum-sale-amount-{{$invoicePartIndex}}_{{$maintenance->id}}"
           value='{{$invoice_part_item->part->maximum_sale_amount}}'>

    <td>{{$invoice_part_item->part->name}}</td>

    <td>
        <select class="js-example-basic-single" style="width: 100%"
                name="part_{{$invoice_part_item->model_id}}_maintenance_{{$maintenance->id}}_index_{{$invoicePartIndex}}[purchase_invoice_id]"
                id="purchase-invoice-{{$invoicePartIndex}}-{{$maintenance->id}}"
                onchange="purchaseInvoiceData('{{$invoice_part_item->model_id}}',{{$maintenance->id}}, {{$maintenance_type->id}}, {{$invoicePartIndex}})"
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
        <input type="number" class="form-control" readonly

               value="{{$invoice_part_item->qty}}"
               id="available-qy-{{$invoicePartIndex}}-{{$maintenance->id}}">
    </td>

    @php
        $purchaseInvoiceItem = $invoice_part_item->purchaseInvoice->items()->where('part_id', $invoice_part_item->model_id)->first();

        $itemQty = $purchaseInvoiceItem && $invoice_part_item->purchase_qty >= $invoice_part_item->qty? $purchaseInvoiceItem->purchase_qty:$invoice_part_item->qty;
    @endphp

    <td>
        <select class="js-example-basic-single" style="width: 100%"
                name="part_{{$invoice_part_item->model_id}}_maintenance_{{$maintenance->id}}_index_{{$invoicePartIndex}}[qty]"
                id="sold-qy-{{$invoicePartIndex}}-{{$maintenance->id}}"
                onchange="setServiceValues('{{$invoicePartIndex}}','{{$maintenance->id}}', {{$maintenance_type->id}})"
        >
            <option value="">{{__('select')}}</option>
            @for($i=1; $i<=$itemQty; $i++)
                <option value="{{$i}}" {{$invoice_part_item->qty == $i ? 'selected':''}}>{{$i}}</option>
            @endfor
        </select>
    </td>

    <input type="hidden" class="form-control" readonly
           value="{{$invoice_part_item->part->last_selling_price}}"
           id="last_purchase_price-{{$invoicePartIndex}}-{{$maintenance->id}}"
    >

    <td>
        <input type="number" class="form-control" value="{{$invoice_part_item->price}}" min="0"
               name="part_{{$invoice_part_item->model_id}}_maintenance_{{$maintenance->id}}_index_{{$invoicePartIndex}}[price]"
               id="selling-price-{{$invoicePartIndex}}-{{$maintenance->id}}"
               onkeyup="setServiceValues('{{$invoicePartIndex}}','{{$maintenance->id}}', {{$maintenance_type->id}})"
               onchange="setServiceValues('{{$invoicePartIndex}}','{{$maintenance->id}}', {{$maintenance_type->id}})"
        >
    </td>

    <td>

        <div class="radio primary">
            <input type="radio"
                   name="part_{{$invoice_part_item->model_id}}_maintenance_{{$maintenance->id}}_index_{{$invoicePartIndex}}[discount_type]"
                   id="discount-type-amount-{{$invoicePartIndex}}-{{$maintenance->id}}" value="amount"
                   {{$invoice_part_item->discount_type == 'amount' ? 'checked':''}}
                   onclick="setServiceValues('{{$invoice_part_item->model_id}}','{{$maintenance->id}}', {{$maintenance_type->id}}, {{$invoicePartIndex}})"
            >
            <label for="discount-type-amount-{{$invoicePartIndex}}-{{$maintenance->id}}">{{__('Amount')}}</label>
        </div>
        <div class="radio primary">
            <input type="radio"
                   name="part_{{$invoice_part_item->model_id}}_maintenance_{{$maintenance->id}}_index_{{$invoicePartIndex}}[discount_type]"
                   id="discount-type-percent-{{$invoicePartIndex}}-{{$maintenance->id}}" value="percent"
                   {{$invoice_part_item->discount_type == 'percent' ? 'checked':''}}
                   onclick="setServiceValues('{{$invoicePartIndex}}','{{$maintenance->id}}', {{$maintenance_type->id}})"
            >
            <label for="discount-type-percent-{{$invoicePartIndex}}-{{$maintenance->id}}">{{__('Percent')}}</label>
        </div>

    </td>
    <td>
        <input type="number" class="form-control" value="{{$invoice_part_item->discount}}" min="0"
               name="part_{{$invoice_part_item->model_id}}_maintenance_{{$maintenance->id}}_index_{{$invoicePartIndex}}[discount]"
               onkeyup="setServiceValues('{{$invoicePartIndex}}','{{$maintenance->id}}', {{$maintenance_type->id}})"
               onchange="setServiceValues('{{$invoicePartIndex}}','{{$maintenance->id}}', {{$maintenance_type->id}})"
               id="discount-{{$invoicePartIndex}}-{{$maintenance->id}}">
    </td>

    <td>
        <input type="number" class="form-control" readonly value="{{$invoice_part_item->sub_total}}"
               id="total-{{$invoicePartIndex}}-{{$maintenance->id}}">
    </td>

    <td>
        <input type="number" class="form-control" readonly value="{{$invoice_part_item->total_after_discount}}"
               id="total-after-discount-{{$invoicePartIndex}}-{{$maintenance->id}}">
    </td>

    <td>
        <i class="fa fa-trash fa-2x"
           onclick="removePartsFromTable('{{$invoicePartIndex}}', '{{$maintenance->id}}', {{$maintenance_type->id}})"
           style="color:#F44336; cursor: pointer">
        </i>
    </td>
</tr>
