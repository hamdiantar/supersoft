<tr data-id="{{$service->id}}" id="remove_service_{{$service->id}}" class="service_tr_{{$service->id}}">

    <input type="hidden" name="service_ids[]" value='{{$service->id}}'>

    <td>{{$service->name}}</td>

    <td>
        <input type="number" class="form-control" name="services_qty[]"
               onkeyup="calculateServiceValues({{$service->id}})"
               onchange="calculateServiceValues({{$service->id}})"
               id="service-qty-{{$service->id}}">
    </td>


{{--    <input style="width:100px" type="hidden" class="form-control" readonly--}}
{{--           value="{{$service->price}}" name="services_prices[]"--}}
{{--           id="service-price-{{$service->id}}"--}}
{{--    >--}}

    <td>
        <input type="number" class="form-control" value="{{$service->price}}"
               name="services_prices[]" id="service-price-{{$service->id}}"
               onkeyup="calculateServiceValues({{$service->id}})"
               onchange="calculateServiceValues({{$service->id}})"
        >
    </td>

    <td>

        <div class="radio primary">
            <input type="radio" name="service_discount_type_{{$service->id}}"
                   id="service-discount-type-amount-{{$service->id}}" value="amount" checked
                   onclick="calculateServiceValues({{$service->id}})">
            <label for="service-discount-type-amount-{{$service->id}}"> {{__('Amount')}}</label>
        </div>

        <div class="radio primary">
            <input type="radio" name="service_discount_type_{{$service->id}}"
                   id="service-discount-type-percent-{{$service->id}}" value="percent"
                   onclick="calculateServiceValues({{$service->id}})">
            <label for="service-discount-type-percent-{{$service->id}}">{{__('Percent')}}</label>
        </div>

{{--        <select style="width:100px" name="services_discounts_types[]" class="js-example-basic-single"--}}
{{--                id="service-discount-type-{{$service->id}}"--}}
{{--                onchange="calculateServiceValues({{$service->id}})"--}}
{{--        >--}}
{{--            <option value="amount">--}}
{{--                {{__('Amount')}}--}}
{{--            </option>--}}
{{--            <option value="percent">--}}
{{--                {{__('Percent')}}--}}
{{--            </option>--}}
{{--        </select>--}}
    </td>

    <td>
        <input  type="number" class="form-control" value="0" name="services_discounts[]"
               onkeyup="calculateServiceValues({{$service->id}})"
               onchange="calculateServiceValues({{$service->id}})"
               id="service-discount-{{$service->id}}"
        >
    </td>

    <td>
        <input  type="number" class="form-control" readonly value="0"
               name="service_total_before_discount[]" id="service-total-{{$service->id}}">
    </td>

    <td>
        <input type="number" class="form-control" readonly value="0"
               name="services_total_after_discount[]"
               id="service-total-after-discount-{{$service->id}}"
        >
    </td>
    <td>
        <i class="fa fa-trash fa-2x" onclick="removeServiceFromTable({{$service->id}})"
           style="color:#F44336; cursor: pointer">
        </i>
    </td>
</tr>

<input type="hidden" name="services_items_count" id="services_items_count" value="{{$items_count}}">
<input type="hidden" name="part_id" value="{{$service->id}}" id="service-{{$items_count}}">
