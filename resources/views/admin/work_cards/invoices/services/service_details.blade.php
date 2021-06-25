<tr data-id="{{$service->id}}" id="remove_service_{{$service->id}}_{{$maintenance_id}}" class="service_tr_{{$service->id}}">

    <input type="hidden" name="service_ids_{{$maintenance_id}}[]" value='{{$service->id}}'>

    <td>{{$service->name}}</td>

    <td>
        <input type="number" class="form-control" min="0"
               name="service_{{$service->id}}_maintenance_{{$maintenance_id}}[qty]"
               onkeyup="calculateServiceValues('{{$service->id}}','{{$maintenance_id}}',{{$maintenance_type_id}})"
               onchange="calculateServiceValues('{{$service->id}}','{{$maintenance_id}}',{{$maintenance_type_id}})"
               id="service-qty-{{$service->id}}-{{$maintenance_id}}">
    </td>


{{--    <input type="hidden" class="form-control" readonly--}}
{{--           value="{{$service->price}}" name="services_prices[]"--}}
{{--           id="service-price-{{$service->id}}"--}}
{{--    >--}}

    <td>
        <input type="number" class="form-control" value="{{$service->price}}" min="0"
               name="service_{{$service->id}}_maintenance_{{$maintenance_id}}[price]"
               id="service-price-{{$service->id}}-{{$maintenance_id}}"
               onkeyup="calculateServiceValues('{{$service->id}}','{{$maintenance_id}}',{{$maintenance_type_id}})"
               onchange="calculateServiceValues('{{$service->id}}','{{$maintenance_id}}',{{$maintenance_type_id}})"
        >
    </td>

    <td>

        <div class="radio primary">
            <input type="radio"
                   name="service_{{$service->id}}_maintenance_{{$maintenance_id}}[discount_type]"
                   id="service-discount-type-amount-{{$service->id}}-{{$maintenance_id}}" value="amount" checked
                   onclick="calculateServiceValues('{{$service->id}}', '{{$maintenance_id}}', {{$maintenance_type_id}})">
            <label for="service-discount-type-amount-{{$service->id}}-{{$maintenance_id}}">{{__('Amount')}}</label>
        </div>

        <div class="radio primary">
            <input type="radio"
                   name="service_{{$service->id}}_maintenance_{{$maintenance_id}}[discount_type]"
                   id="service-discount-type-percent-{{$service->id}}-{{$maintenance_id}}" value="percent"
                   onclick="calculateServiceValues('{{$service->id}}','{{$maintenance_id}}',{{$maintenance_type_id}})">
            <label for="service-discount-type-percent-{{$service->id}}-{{$maintenance_id}}">{{__('Percent')}}</label>
        </div>

    </td>

    <td>
        <input type="number" class="form-control" value="0" min="0"
               name="service_{{$service->id}}_maintenance_{{$maintenance_id}}[discount]"
               onkeyup="calculateServiceValues('{{$service->id}}','{{$maintenance_id}}', {{$maintenance_type_id}})"
               onchange="calculateServiceValues('{{$service->id}}','{{$maintenance_id}}', {{$maintenance_type_id}})"
               id="service-discount-{{$service->id}}-{{$maintenance_id}}"
        >
    </td>

    <td>
        <input type="number" class="form-control" readonly value="0"
               id="service-total-{{$service->id}}-{{$maintenance_id}}">
    </td>

    <td>
        <input type="number" class="form-control" readonly value="0"
               id="service-total-after-discount-{{$service->id}}-{{$maintenance_id}}"
        >
    </td>
    <td>
        <i class="fa fa-trash fa-2x"
           onclick="removeServiceFromTable('{{$service->id}}', '{{$maintenance_id}}', {{$maintenance_type_id}})"
           style="color:#F44336; cursor: pointer">
        </i>
    </td>
</tr>

{{--EMPLOYEES--}}
<tr id="remove_employees_service_{{$service->id}}_{{$maintenance_id}}">
    <td colspan="8">
        <div class="input-group">
            <span class="input-group-addon fa fa-users"></span>
            <select class="form-control js-example-basic-single"
                     multiple="multiple"  name="service_{{$service->id}}_maintenance_{{$maintenance_id}}[employees][]">
                {{--            <option value="">{{__('Select Branches')}}</option>--}}
                @foreach($employees as $employee)
                    <option value="{{$employee->id}}">
                        {{$employee->name }} - {{optional($employee->employeeSetting)->card_work_percent}}
                    </option>
                @endforeach
            </select>
        </div>
    </td>
</tr>

<input type="hidden" id="services_items_count_{{$maintenance_id}}" value="{{$items_count}}">
<input type="hidden"  value="{{$service->id}}" id="service-{{$items_count}}-{{$maintenance_id}}">
