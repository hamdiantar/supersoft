<!-- <td>
    <input type="text" disabled class="form-control" value="{{$branch_lat}}" id="branch_lat">
</td>

<td>
    <input type="text" disabled class="form-control" value="{{$branch_long}}" id="branch_long">
</td> -->

<td>
    <div class="radio primary">
        <input type="checkbox" name="active_winch" id="active_winch" onclick="invoiceTotal()"

        {{isset($data['cardInvoiceWinchType']) && $data['cardInvoiceWinchType']->cardInvoiceWinchRequest ? 'checked':''}}
        >
    </div>
</td>

<td>
    <input type="text" class="form-control" disabled id="branch_distance"
           value="{{isset($data['cardInvoiceWinchType']) && $data['cardInvoiceWinchType']->cardInvoiceWinchRequest ? $data['cardInvoiceWinchType']->cardInvoiceWinchRequest->distance:''}}">
</td>

<td>
    <input type="text" class="form-control" disabled value="{{$kilo_meter_price}}" id="branch_price">
</td>

<td>
    <div class="radio primary">
        <input type="radio" name="winch_discount_type" id="winch-discount-type-amount" value="amount" checked onclick="winchDiscount()"
               {{!isset($data['cardInvoiceWinchType']) ? 'checked': ''}}
            {{isset($data['cardInvoiceWinchType']) && $data['cardInvoiceWinchType']->cardInvoiceWinchRequest->discount_type == 'amount' ? 'checked':''}}
        >
        <label for="winch-discount-type-amount"> {{__('Amount')}}</label>
    </div>

    <div class="radio primary">
        <input type="radio" name="winch_discount_type" id="winch-discount-type-percent" value="percent" onclick="winchDiscount()"
            {{isset($data['cardInvoiceWinchType']) && $data['cardInvoiceWinchType']->cardInvoiceWinchRequest->discount_type == 'percent' ? 'checked':''}}>
        <label for="winch-discount-type-percent">{{__('Percent')}}</label>
    </div>
</td>

<td>
    <input  type="number" class="form-control" name="winch_discount" id="winch-discount"
            onkeyup="winchDiscount()" onchange="winchDiscount()"
            value="{{isset($data['cardInvoiceWinchType']) && $data['cardInvoiceWinchType']->cardInvoiceWinchRequest ? $data['cardInvoiceWinchType']->cardInvoiceWinchRequest->discount: 0}}"
    >
</td>

<td>
    <input  type="number" class="form-control" readonly name="winch_total_before_discount" id="winch-total"
            value="{{isset($data['cardInvoiceWinchType']) && $data['cardInvoiceWinchType']->cardInvoiceWinchRequest ? $data['cardInvoiceWinchType']->cardInvoiceWinchRequest->sub_total:0}}">
</td>

<td>
    <input type="number" class="form-control" readonly name=winch_total_after_discount" id="winch-total-after-discount"

           value="{{isset($data['cardInvoiceWinchType']) && $data['cardInvoiceWinchType']->cardInvoiceWinchRequest ? $data['cardInvoiceWinchType']->cardInvoiceWinchRequest->total:0}}"
    >
</td>
