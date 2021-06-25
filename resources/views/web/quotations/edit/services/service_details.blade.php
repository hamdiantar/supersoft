<tr data-id="{{$service->id}}" id="remove_service_{{$service->id}}" class="service_tr_{{$service->id}}">

    <input type="hidden" name="service_ids[]" value='{{$service->id}}'>

    <td>{{$service->name}}</td>

    <td>
        <input type="number" class="form-control" name="services_qty[]"
               onkeyup="calculateServiceValues({{$service->id}})"
               onchange="calculateServiceValues({{$service->id}})"
               id="service-qty-{{$service->id}}"
               value="{{$service_item->qty}}"
        >
    </td>


{{--    <input type="hidden" class="form-control" readonly--}}
{{--           value="{{$service->price}}" name="services_prices[]"--}}
{{--           id="service-price-{{$service->id}}"--}}
{{--    >--}}

    <td>
        <input type="number" class="form-control" value="{{$service_item->price}}"
               name="services_prices[]" id="service-price-{{$service->id}}"
               onkeyup="calculateServiceValues({{$service->id}})"
               onchange="calculateServiceValues({{$service->id}})"
        >
    </td>

    <td>

        <div class="radio primary">
            <input type="radio" name="service_discount_type_{{$service->id}}"
                   {{$service_item->discount_type == 'amount' ? "checked":""}}
                   id="service-discount-type-amount-{{$service->id}}" value="amount" checked
                   onclick="calculateServiceValues({{$service->id}})">
            <label for="service-discount-type-amount-{{$service->id}}">{{__('Amount')}}</label>
        </div>

        <div class="radio primary">
            <input type="radio" name="service_discount_type_{{$service->id}}"
                   {{$service_item->discount_type == 'percent' ? "checked":""}}
                   id="service-discount-type-percent-{{$service->id}}" value="percent"
                   onclick="calculateServiceValues({{$service->id}})">
            <label for="service-discount-type-percent-{{$service->id}}">{{__('Percent')}}</label>
        </div>


{{--        <select name="services_discounts_types[]" class="js-example-basic-single"--}}
{{--                id="service-discount-type-{{$service->id}}"--}}
{{--                onchange="calculateServiceValues({{$service->id}})"--}}
{{--        >--}}
{{--            <option value="amount" {{$service_item->discount_type == 'amount' ? "selected":""}}>--}}
{{--                {{__('Amount')}}--}}
{{--            </option>--}}
{{--            <option value="percent" {{$service_item->discount_type == 'percent' ? "selected":""}}>--}}
{{--                {{__('Percent')}}--}}
{{--            </option>--}}
{{--        </select>--}}
    </td>

    <td>
        <input type="number" class="form-control" value="{{$service_item->discount}}" name="services_discounts[]"
               onkeyup="calculateServiceValues({{$service->id}})"
               onchange="calculateServiceValues({{$service->id}})"
               id="service-discount-{{$service->id}}"
        >
    </td>

    <td>
        <input type="number" class="form-control" readonly value="{{$service_item->sub_total}}"
               name="service_total_before_discount[]" id="service-total-{{$service->id}}">
    </td>

    <td>
        <input type="number" class="form-control" readonly value="{{$service_item->total_after_discount}}"
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

{{--<input type="hidden" name="services_items_count" id="services_items_count" value="{{$items_count}}">--}}
{{--<input type="hidden" name="part_id" value="{{$service->id}}" id="service-{{$items_count}}">--}}
