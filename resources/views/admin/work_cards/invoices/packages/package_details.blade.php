<tr data-id="{{$package->id}}" id="remove_package_{{$package->id}}_{{$maintenance_id}}" class="package_tr_{{$package->id}}">

    <input type="hidden" name="package_ids_{{$maintenance_id}}[]" value='{{$package->id}}'>

    <td>{{$package->name}}</td>

    <td>
        <input type="number" class="form-control" value="0" min="0"
               name="package_{{$package->id}}_maintenance_{{$maintenance_id}}[qty]"
               onkeyup="calculatePackageValues('{{$package->id}}','{{$maintenance_id}}',{{$maintenance_type_id}})"
               onchange="calculatePackageValues('{{$package->id}}','{{$maintenance_id}}',{{$maintenance_type_id}})"
               id="package-qty-{{$package->id}}-{{$maintenance_id}}"
        >
    </td>
    <td>
        <input type="number" class="form-control" value="{{$package->total_after_discount}}" min="0"
               name="package_{{$package->id}}_maintenance_{{$maintenance_id}}[price]"
               id="package-price-{{$package->id}}-{{$maintenance_id}}" readonly
               onkeyup="calculatePackageValues('{{$package->id}}','{{$maintenance_id}}',{{$maintenance_type_id}})"
               onchange="calculatePackageValues('{{$package->id}}','{{$maintenance_id}}',{{$maintenance_type_id}})"
        >
    </td>

    <td>

        <div class="radio primary">
            <input type="radio"
                   name="package_{{$package->id}}_maintenance_{{$maintenance_id}}[discount_type]"
                   id="package-discount-type-amount-{{$package->id}}-{{$maintenance_id}}" value="amount" checked
                   onclick="calculatePackageValues('{{$package->id}}','{{$maintenance_id}}',{{$maintenance_type_id}})"
            >
            <label for="package-discount-type-amount-{{$package->id}}-{{$maintenance_id}}">{{__('Amount')}}</label>
        </div>

        <div class="radio primary">
            <input type="radio"
                   name="package_{{$package->id}}_maintenance_{{$maintenance_id}}[discount_type]"
                   id="package-discount-type-percent-{{$package->id}}-{{$maintenance_id}}" value="percent"
                   onclick="calculatePackageValues('{{$package->id}}','{{$maintenance_id}}',{{$maintenance_type_id}})"
            >
            <label for="package-discount-type-percent-{{$package->id}}-{{$maintenance_id}}">{{__('Percent')}}</label>
        </div>

{{--        <select name="packages_discounts_types[]" class="js-example-basic-single"--}}
{{--                id="package-discount-type-{{$package->id}}"--}}
{{--                onchange="calculatePackageValues({{$package->id}})"--}}
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
        <input type="number" class="form-control" value="0" min="0"
               name="package_{{$package->id}}_maintenance_{{$maintenance_id}}[discount]"
               onkeyup="calculatePackageValues('{{$package->id}}','{{$maintenance_id}}',{{$maintenance_type_id}})"
               onchange="calculatePackageValues('{{$package->id}}','{{$maintenance_id}}',{{$maintenance_type_id}})"
               id="package-discount-{{$package->id}}-{{$maintenance_id}}"
        >
    </td>

    <td>
        <input type="number" class="form-control" readonly value="{{$package->total_after_discount}}"
               id="package-total-before-discount-{{$package->id}}-{{$maintenance_id}}"
        >
    </td>

    <td>
        <input type="number" class="form-control" readonly value="{{$package->total_after_discount}}"
               id="package-total-after-discount-{{$package->id}}-{{$maintenance_id}}"
        >
    </td>

    <td>
        <i class="fa fa-trash fa-2x"
           onclick="removePackageFromTable('{{$package->id}}', '{{$maintenance_id}}', {{$maintenance_type_id}})"
           style="color:#F44336; cursor: pointer">
        </i>

        <a style="cursor:pointer" data-toggle="modal" onclick="getPackageInfo({{$package->id}})"
           data-target="#package_info_data" title="{{__('Package details')}}">
            <i class="fa fa-eye fa-2x " style="color:#F44336"></i>
        </a>
    </td>
</tr>

{{--EMPLOYEES--}}
<tr id="remove_employees_package_{{$package->id}}_{{$maintenance_id}}">
    <td colspan="8">
        <div class="input-group">
            <span class="input-group-addon fa fa-users"></span>
            <select class="form-control js-example-basic-single"
                    multiple="multiple"  name="package_{{$package->id}}_maintenance_{{$maintenance_id}}[employees][]">
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

<input type="hidden"  id="packages_items_count_{{$maintenance_id}}" value="{{$items_count}}">
<input type="hidden"  value="{{$package->id}}" id="package-{{$items_count}}-{{$maintenance_id}}">


