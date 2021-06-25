<tr data-id="{{$invoice_service_item->model_id}}" id="remove_service_{{$invoice_service_item->model_id}}"
    class="service_tr_{{$invoice_service_item->model_id}}">

    <input type="hidden" name="services[{{$invoiceServiceIndex}}][id]" value='{{$invoice_service_item->model_id}}'>

    <td>{{$invoice_service_item->service->name}}</td>

    <td>
        <input type="number" class="form-control" min="0" value="{{$invoice_service_item->qty}}"
               name="services[{{$invoiceServiceIndex}}][qty]"
               onkeyup="calculateServiceValues('{{$invoice_service_item->model_id}}')"
               onchange="calculateServiceValues('{{$invoice_service_item->model_id}}')"
               id="service-qty-{{$invoice_service_item->model_id}}">
    </td>

    <td>
        <input type="number" class="form-control" value="{{$invoice_service_item->price}}" min="0"
               name="services[{{$invoiceServiceIndex}}][price]"
               id="service-price-{{$invoice_service_item->model_id}}"
               onkeyup="calculateServiceValues('{{$invoice_service_item->model_id}}')"
               onchange="calculateServiceValues('{{$invoice_service_item->model_id}}')"
        >
    </td>

    <td>

        <div class="radio primary">
            <input type="radio"
                   name="services[{{$invoiceServiceIndex}}][discount_type]"
                   id="service-discount-type-amount-{{$invoice_service_item->model_id}}"
                   value="amount" {{$invoice_service_item->discount_type == 'amount'? 'checked':''}}
                   onclick="calculateServiceValues('{{$invoice_service_item->model_id}}')">
            <label for="service-discount-type-amount-{{$invoice_service_item->model_id}}">{{__('Amount')}}</label>
        </div>

        <div class="radio primary">
            <input type="radio"
                   name="services[{{$invoiceServiceIndex}}][discount_type]"
                   id="service-discount-type-percent-{{$invoice_service_item->model_id}}"
                   value="percent" {{$invoice_service_item->discount_type == 'percent'? 'checked':''}}
                   onclick="calculateServiceValues('{{$invoice_service_item->model_id}}')">
            <label for="service-discount-type-percent-{{$invoice_service_item->model_id}}">{{__('Percent')}}</label>
        </div>

    </td>

    <td>
        <input type="number" class="form-control" value="{{$invoice_service_item->discount}}" min="0"
               name="services[{{$invoiceServiceIndex}}][discount]"
               onkeyup="calculateServiceValues('{{$invoice_service_item->model_id}}')"
               onchange="calculateServiceValues('{{$invoice_service_item->model_id}}')"
               id="service-discount-{{$invoice_service_item->model_id}}"
        >
    </td>

    <td>
        <input type="number" class="form-control" readonly value="{{$invoice_service_item->sub_total}}"
               id="service-total-{{$invoice_service_item->model_id}}">
    </td>

    <td>
        <input type="number" class="form-control" readonly value="{{$invoice_service_item->total_after_discount}}"
               id="service-total-after-discount-{{$invoice_service_item->model_id}}"
        >
    </td>
    <td>
        <i class="fa fa-trash fa-2x"
           onclick="removeServiceFromTable('{{$invoice_service_item->model_id}}')"
           style="color:#F44336; cursor: pointer">
        </i>
    </td>
</tr>

{{--EMPLOYEES--}}
<tr id="remove_employees_service_{{$invoice_service_item->model_id}}">
    <td colspan="8">
        <div class="input-group">
            <span class="input-group-addon fa fa-users"></span>
            <select class="form-control js-example-basic-single" style="width:100%" multiple="multiple"
                    name="services[{{$invoiceServiceIndex}}][employees][]">
                @foreach($employees as $employee)
                    <option value="{{$employee->id}}"
                            {{in_array($employee->id, $invoice_service_item->employees->pluck('id')->toArray())?'selected':''}}>
                        {{$employee->name }} - {{optional($employee->employeeSetting)->card_work_percent}}
                    </option>
                @endforeach
            </select>
        </div>
    </td>
</tr>


{{--<input type="hidden" id="services_items_count_{{$maintenance->id}}" value="{{$items_count}}">--}}
{{--<input type="hidden"  value="{{$invoice_service_item->model_id}}" id="service-{{$items_count}}-{{$maintenance->id}}">--}}
