<tr data-id="{{$package->id}}" id="remove_package_{{$package->id}}" class="package_tr_{{$package->id}}">

    <input type="hidden" name="packages[{{$items_count}}][id]" value='{{$package->id}}'>

    <td>{{$package->name}}</td>

    <td>
        <input type="number" class="form-control" value="0" min="0"
               name="packages[{{$items_count}}][qty]"
               onkeyup="calculatePackageValues('{{$package->id}}')"
               onchange="calculatePackageValues('{{$package->id}}')"
               id="package-qty-{{$package->id}}"
        >
    </td>
    <td>
        <input type="number" class="form-control" value="{{$package->total_after_discount}}" min="0"
               name="packages[{{$items_count}}][price]"
               id="package-price-{{$package->id}}" readonly
               onkeyup="calculatePackageValues('{{$package->id}}')"
               onchange="calculatePackageValues('{{$package->id}}')"
        >
    </td>

    <td>

        <div class="radio primary">
            <input type="radio"
                   name="packages[{{$items_count}}][discount_type]"
                   id="package-discount-type-amount-{{$package->id}}" value="amount" checked
                   onclick="calculatePackageValues('{{$package->id}}')"
            >
            <label for="package-discount-type-amount-{{$package->id}}">{{__('Amount')}}</label>
        </div>

        <div class="radio primary">
            <input type="radio"
                   name="packages[{{$items_count}}][discount_type]"
                   id="package-discount-type-percent-{{$package->id}}" value="percent"
                   onclick="calculatePackageValues('{{$package->id}}')"
            >
            <label for="package-discount-type-percent-{{$package->id}}">{{__('Percent')}}</label>
        </div>
    </td>

    <td>
        <input type="number" class="form-control" value="0" min="0"
               name="packages[{{$items_count}}][discount]"
               onkeyup="calculatePackageValues('{{$package->id}}')"
               onchange="calculatePackageValues('{{$package->id}}')"
               id="package-discount-{{$package->id}}"
        >
    </td>

    <td>
        <input type="number" class="form-control" readonly value="{{$package->total_after_discount}}"
               id="package-total-before-discount-{{$package->id}}">
    </td>

    <td>
        <input type="number" class="form-control" readonly value="{{$package->total_after_discount}}"
               id="package-total-after-discount-{{$package->id}}"
        >
    </td>

    <td>
        <i class="fa fa-trash fa-2x"
           onclick="removePackageFromTable('{{$package->id}}')"
           style="color:#F44336; cursor: pointer">
        </i>

        <a style="cursor:pointer" data-toggle="modal" onclick="getPackageInfo({{$package->id}})"
           data-target="#package_info_data" title="{{__('Package details')}}">
            <i class="fa fa-eye fa-2x " style="color:#F44336"></i>
        </a>
    </td>
</tr>

{{--EMPLOYEES--}}
<tr id="remove_employees_package_{{$package->id}}">
    <td colspan="8">
        <div class="input-group">
            <span class="input-group-addon fa fa-users"></span>
            <select class="form-control js-example-basic-single"
                    multiple="multiple"  name="packages[{{$items_count}}][employees][]">
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

<input type="hidden"  id="packages_items_count" value="{{$items_count}}">
<input type="hidden"  value="{{$package->id}}" id="package-{{$items_count}}">


