@foreach($purchaseQuotations as $purchaseQuotation)

    <tr>
        <td>
            <input type="checkbox" name="purchase_quotations[]" value="{{$purchaseQuotation->id}}"
                   id="real_purchase_quotation_box_{{$purchaseQuotation->id}}"
                  {{isset($supply_order_quotations) && in_array($purchaseQuotation->id, $supply_order_quotations) ? 'checked':''}}
            >
        </td>
        <td>
            <span>{{$purchaseQuotation->number}}</span>
        </td>
    </tr>

@endforeach
