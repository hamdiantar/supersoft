@foreach($purchaseQuotations as $purchaseQuotation)

    <tr>
        <td>
            <input type="checkbox" name="purchase_quotations[]" value="{{$purchaseQuotation->id}}"
                   onclick="selectPurchaseQuotation('{{$purchaseQuotation->id}}')"
                   class="purchase_quotation_box_{{$purchaseQuotation->id}}"
                {{isset($supply_order_quotations) && in_array($purchaseQuotation->id, $supply_order_quotations) ? 'checked':''}}
            >
        </td>
        <td>
            <span>{{$purchaseQuotation->number}}</span>
        </td>
        <td>

            <span>{{optional($purchaseQuotation->supplier)->name}}</span>
        </td>
    </tr>

@endforeach
