<!-- <td>
    <input type="text" disabled class="form-control" value="{{$branch_lat}}" id="branch_lat">
</td>

<td>
    <input type="text" disabled class="form-control" value="{{$branch_long}}" id="branch_long">
</td> -->

<td>
    <input type="text" class="form-control" disabled id="branch_distance"
           value="{{$winchType ? $winchType->winchRequest->distance :''}}">
</td>

<td>
    <input type="text" class="form-control" disabled  id="branch_price"
           value="{{$winchType ? $winchType->winchRequest->price : $kilo_meter_price}}">
</td>

<td>
    <div class="radio primary">
        <input type="radio" name="winch_discount_type" id="winch-discount-type-amount" value="amount"
               onclick="winchDiscount()"
            {{$winchType && $winchType->winchRequest->discount_type == 'amount' ? 'checked':'' }}
            {{!$winchType ? 'checked':'' }}
        >
        <label for="winch-discount-type-amount"> {{__('Amount')}}</label>
    </div>

    <div class="radio primary">
        <input type="radio" name="winch_discount_type" id="winch-discount-type-percent" value="percent" onclick="winchDiscount()"
            {{$winchType && $winchType->winchRequest->discount_type == 'percent' ? 'checked':'' }}>
        <label for="winch-discount-type-percent">{{__('Percent')}}</label>
    </div>
</td>

<td>
    <input  type="number" class="form-control" name="winch_discount" id="winch-discount"
            onkeyup="winchDiscount()" onchange="winchDiscount()"
            value="{{$winchType ? $winchType->winchRequest->discount : 0 }}"
    >
</td>

<td>
    <input  type="number" class="form-control" readonly  value="{{$winchType ? $winchType->winchRequest->sub_total : 0 }}"
            name="winch_total_before_discount" id="winch-total">
</td>

<td>
    <input type="number" class="form-control" readonly value="{{$winchType ? $winchType->winchRequest->total : 0 }}"
           name=winch_total_after_discount" id="winch-total-after-discount">
</td>
