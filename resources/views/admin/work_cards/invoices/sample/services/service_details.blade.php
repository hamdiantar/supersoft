<tr data-id="{{$service->id}}" id="remove_service_{{$service->id}}" class="service_tr_{{$service->id}}">

    <input type="hidden" name="services[{{$items_count}}][id]" value='{{$service->id}}'>

    <td>{{$service->name}}</td>

    <td>
        <input type="number" class="form-control" min="0" name="services[{{$items_count}}][qty]"
               onkeyup="calculateServiceValues('{{$service->id}}')"
               onchange="calculateServiceValues('{{$service->id}}')"
               id="service-qty-{{$service->id}}">
    </td>

    <td>
        <input type="number" class="form-control" value="{{$service->price}}" min="0"
               name="services[{{$items_count}}][price]"
               id="service-price-{{$service->id}}"
               onkeyup="calculateServiceValues('{{$service->id}}')"
               onchange="calculateServiceValues('{{$service->id}}')"
        >
    </td>

    <td>

        <div class="radio primary">
            <input type="radio"
                   name="services[{{$items_count}}][discount_type]"
                   id="service-discount-type-amount-{{$service->id}}" value="amount" checked
                   onclick="calculateServiceValues('{{$service->id}}')">
            <label for="service-discount-type-amount-{{$service->id}}">{{__('Amount')}}</label>
        </div>

        <div class="radio primary">
            <input type="radio"
                   name="services[{{$items_count}}][discount_type]"
                   id="service-discount-type-percent-{{$service->id}}" value="percent"
                   onclick="calculateServiceValues('{{$service->id}}')">
            <label for="service-discount-type-percent-{{$service->id}}">{{__('Percent')}}</label>
        </div>

    </td>

    <td>
        <input type="number" class="form-control" value="0" min="0"
               name="services[{{$items_count}}][discount]"
               onkeyup="calculateServiceValues('{{$service->id}}')"
               onchange="calculateServiceValues('{{$service->id}}')"
               id="service-discount-{{$service->id}}"
        >
    </td>

    <td>
        <input type="number" class="form-control" readonly value="0" id="service-total-{{$service->id}}">
    </td>

    <td>
        <input type="number" class="form-control" readonly value="0" id="service-total-after-discount-{{$service->id}}"
        >
    </td>
    <td>
        <i class="fa fa-trash fa-2x"
           onclick="removeServiceFromTable({{$service->id}})"
           style="color:#F44336; cursor: pointer">
        </i>
    </td>
</tr>

{{--EMPLOYEES--}}
<tr id="remove_employees_service_{{$service->id}}">
    <td colspan="8">
        <div class="input-group">
            <span class="input-group-addon fa fa-users"></span>
            <select class="form-control js-example-basic-single"
                     multiple="multiple"  name="services[{{$items_count}}][employees][]">
                @foreach($employees as $employee)
                    <option value="{{$employee->id}}">
                        {{$employee->name }} - {{optional($employee->employeeSetting)->card_work_percent}}
                    </option>
                @endforeach
            </select>
        </div>
    </td>
</tr>

<input type="hidden" id="services_items_count" value="{{$items_count}}">
<input type="hidden"  value="{{$service->id}}" id="service-{{$items_count}}">
