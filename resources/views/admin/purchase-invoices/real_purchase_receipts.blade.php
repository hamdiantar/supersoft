@foreach($purchaseReceipts as $purchaseReceipt)

    <tr>
        <td>
            <input type="checkbox" name="purchase_receipts[]" value="{{$purchaseReceipt->id}}"
                   class="real_purchase_quotation_box_{{$purchaseReceipt->id}}"
                {{isset($purchase_invoice_receipts) && in_array($purchaseReceipt->id, $purchase_invoice_receipts) ? 'checked':''}}
            >
        </td>
        <td>
            <span>{{$purchaseReceipt->number}}</span>
        </td>
        <td>
            <span>{{optional($purchaseReceipt->supplier)->name}}</span>
        </td>
    </tr>

@endforeach
