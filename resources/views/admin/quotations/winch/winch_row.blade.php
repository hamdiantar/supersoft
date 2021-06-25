<!-- <td>
    <input type="text" disabled class="form-control" value="{{$branch_lat}}" id="branch_lat">
</td>

<td>
    <input type="text" disabled class="form-control" value="{{$branch_long}}" id="branch_long">
</td> -->

<td>
    <input type="text" class="form-control" disabled value="0" id="branch_distance">
</td>

<td>
    <input type="text" class="form-control" disabled value="{{$kilo_meter_price}}" id="branch_price">
</td>

<td>
    <div class="radio primary">
        <input type="radio" name="winch_discount_type" id="winch-discount-type-amount" value="amount" checked
               onclick="winchDiscount()">
        <label for="winch-discount-type-amount"> {{__('Amount')}}</label>
    </div>

    <div class="radio primary">
        <input type="radio" name="winch_discount_type" id="winch-discount-type-percent" value="percent" onclick="winchDiscount()">
        <label for="winch-discount-type-percent">{{__('Percent')}}</label>
    </div>
</td>

<td>
    <input  type="number" class="form-control" value="0" name="winch_discount" id="winch-discount"
            onkeyup="winchDiscount()" onchange="winchDiscount()"
    >
</td>

<td>
    <input  type="number" class="form-control" readonly value="0" name="winch_total_before_discount" id="winch-total">
</td>

<td>
    <input type="number" class="form-control" readonly value="0" name=winch_total_after_discount" id="winch-total-after-discount">
</td>
