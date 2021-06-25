<tr data-id="{{$invoice_package_item->model_id}}" id="remove_package_{{$invoice_package_item->model_id}}_{{$maintenance->id}}" class="package_tr_{{$invoice_package_item->model_id}}">

    <input type="hidden" name="package_ids_{{$maintenance->id}}[]" value='{{$invoice_package_item->model_id}}'>

    <td>{{$invoice_package_item->package->name}}</td>

    <td>
        <input type="number" class="form-control" value="{{$invoice_package_item->qty}}" min="0"
               name="package_{{$invoice_package_item->model_id}}_maintenance_{{$maintenance->id}}[qty]"
               onkeyup="calculatePackageValues('{{$invoice_package_item->model_id}}','{{$maintenance->id}}',{{$maintenance_type->id}})"
               onchange="calculatePackageValues('{{$invoice_package_item->model_id}}','{{$maintenance->id}}',{{$maintenance_type->id}})"
               id="package-qty-{{$invoice_package_item->model_id}}-{{$maintenance->id}}"
        >
    </td>

    <td>
        <input type="number" class="form-control" value="{{optional($invoice_package_item->package)->total_after_discount}}" min="0"
               name="package_{{$invoice_package_item->model_id}}_maintenance_{{$maintenance->id}}[price]"
               id="package-price-{{$invoice_package_item->model_id}}-{{$maintenance->id}}" readonly
               onkeyup="calculatePackageValues('{{$invoice_package_item->model_id}}','{{$maintenance->id}}',{{$maintenance_type->id}})"
               onchange="calculatePackageValues('{{$invoice_package_item->model_id}}','{{$maintenance->id}}',{{$maintenance_type->id}})"
        >
    </td>

    <td>

        <div class="radio primary">
            <input type="radio"
                   name="package_{{$invoice_package_item->model_id}}_maintenance_{{$maintenance->id}}[discount_type]"
                   id="package-discount-type-amount-{{$invoice_package_item->model_id}}-{{$maintenance->id}}" value="amount"
                   {{$invoice_package_item->discount_type == 'amount'? 'checked':''}}
                   onclick="calculatePackageValues('{{$invoice_package_item->model_id}}','{{$maintenance->id}}',{{$maintenance_type->id}})"
            >
            <label for="package-discount-type-amount-{{$invoice_package_item->model_id}}-{{$maintenance->id}}">{{__('Amount')}}</label>
        </div>

        <div class="radio primary">
            <input type="radio"
                   name="package_{{$invoice_package_item->model_id}}_maintenance_{{$maintenance->id}}[discount_type]"
                   {{$invoice_package_item->discount_type == 'percent'? 'checked':''}}
                   id="package-discount-type-percent-{{$invoice_package_item->model_id}}-{{$maintenance->id}}" value="percent"
                   onclick="calculatePackageValues('{{$invoice_package_item->model_id}}','{{$maintenance->id}}',{{$maintenance_type->id}})"
            >
            <label for="package-discount-type-percent-{{$invoice_package_item->model_id}}-{{$maintenance->id}}">{{__('Percent')}}</label>
        </div>

        {{--        <select name="packages_discounts_types[]" class="js-example-basic-single"--}}
        {{--                id="package-discount-type-{{$invoice_package_item->model_id}}"--}}
        {{--                onchange="calculatePackageValues({{$invoice_package_item->model_id}})"--}}
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
        <input type="number" class="form-control" value="{{$invoice_package_item->discount}}" min="0"
               name="package_{{$invoice_package_item->model_id}}_maintenance_{{$maintenance->id}}[discount]"
               onkeyup="calculatePackageValues('{{$invoice_package_item->model_id}}','{{$maintenance->id}}',{{$maintenance_type->id}})"
               onchange="calculatePackageValues('{{$invoice_package_item->model_id}}','{{$maintenance->id}}',{{$maintenance_type->id}})"
               id="package-discount-{{$invoice_package_item->model_id}}-{{$maintenance->id}}"
        >
    </td>

    <td>
        <input type="number" class="form-control" readonly value="{{$invoice_package_item->sub_total}}"
               id="package-total-before-discount-{{$invoice_package_item->model_id}}-{{$maintenance->id}}"
        >
    </td>

    <td>
        <input type="number" class="form-control" readonly value="{{$invoice_package_item->total_after_discount}}"
               id="package-total-after-discount-{{$invoice_package_item->model_id}}-{{$maintenance->id}}"
        >
    </td>

    <td>
        <i class="fa fa-trash fa-2x"
           onclick="removePackageFromTable('{{$invoice_package_item->model_id}}', '{{$maintenance->id}}', {{$maintenance_type->id}})"
           style="color:#F44336; cursor: pointer">
        </i>

        <a style="cursor:pointer" data-toggle="modal" onclick="getPackageInfo({{$invoice_package_item->model_id}})"
           data-target="#package_info_data" title="{{__('Package details')}}">
            <i class="fa fa-eye fa-2x " style="color:#F44336"></i>
        </a>
    </td>
</tr>



{{--EMPLOYEES--}}
<tr id="remove_employees_package_{{$invoice_package_item->model_id}}_{{$maintenance->id}}">
    <td colspan="8">
        <div class="input-group">
            <span class="input-group-addon fa fa-users"></span>
            <select class="form-control js-example-basic-single" style="width:100%" multiple="multiple"
                    name="package_{{$invoice_package_item->model_id}}_maintenance_{{$maintenance->id}}[employees][]">

                @foreach($employees as $employee)
                    <option value="{{$employee->id}}"
                            {{in_array($employee->id, $invoice_package_item->employees->pluck('id')->toArray())?'selected':''}}>
                        {{$employee->name }} - {{optional($employee->employeeSetting)->card_work_percent}}
                    </option>
                @endforeach

            </select>
        </div>
    </td>
</tr>

{{--<input type="hidden"  id="packages_items_count_{{$maintenance->id}}" value="{{$items_count}}">--}}
{{--<input type="hidden"  value="{{$invoice_package_item->model_id}}" id="package-{{$items_count}}-{{$maintenance->id}}">--}}


