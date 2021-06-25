<tr data-id="{{$package->id}}" id="remove_package_{{$package->id}}" class="package_tr_{{$package->id}}">

    <input type="hidden" name="package_ids[]" value='{{$package->id}}'>

    <td>{{$package->name}}</td>

    <td>
        <input type="number" class="form-control" value="{{$package_item->qty}}" name="packages_qty[]"
               onkeyup="calculatePackageValues({{$package->id}})"
               onchange="calculatePackageValues({{$package->id}})"
               id="package-qty-{{$package->id}}"
        >
    </td>

    <td>
        <input type="number" class="form-control" value="{{$package_item->price}}"
               name="packages_prices[]" id="package-price-{{$package->id}}" readonly
               onkeyup="calculatePackageValues({{$package->id}})"
               onchange="calculatePackageValues({{$package->id}})"
        >
    </td>

    <td>

        <div class="radio primary">
            <input type="radio" name="package_discount_type_{{$package->id}}"
                   id="package-discount-type-amount-{{$package->id}}" value="amount"
                   {{$package_item->discount_type == 'amount'? "checked":""}}
                   onclick="calculatePackageValues({{$package->id}})">
            <label for="package-discount-type-amount-{{$package->id}}">{{__('Amount')}}</label>
        </div>

        <div class="radio primary">
            <input type="radio" name="package_discount_type_{{$package->id}}"
                   id="package-discount-type-percent-{{$package->id}}" value="percent"
                   {{$package_item->discount_type == 'percent'? "checked":""}}
                   onclick="calculatePackageValues({{$package->id}})">
            <label for="package-discount-type-percent-{{$package->id}}">{{__('Percent')}}</label>
        </div>

{{--        <select name="packages_discounts_types[]" class="js-example-basic-single"--}}
{{--                id="package-discount-type-{{$package->id}}"--}}
{{--                onchange="calculatePackageValues({{$package->id}})"--}}
{{--        >--}}
{{--            <option value="amount" {{$package_item->discount_type == 'amount'? "selected":""}}>--}}
{{--                {{__('Amount')}}--}}
{{--            </option>--}}
{{--            <option value="percent" {{$package_item->discount_type == 'percent'? "selected":""}}>--}}
{{--                {{__('Percent')}}--}}
{{--            </option>--}}
{{--        </select>--}}
    </td>

    <td>
        <input type="number" class="form-control" value="{{$package_item->discount}}" name="packages_discounts[]"
               onkeyup="calculatePackageValues({{$package->id}})"
               onchange="calculatePackageValues({{$package->id}})"
               id="package-discount-{{$package->id}}"
        >
    </td>

    <td>
        <input type="number" class="form-control" readonly
               value="{{$package_item->sub_total}}"
               name="packages_total_before_discount[]"
               id="package-total-before-discount-{{$package->id}}"
        >
    </td>

    <td>
        <input type="number" class="form-control" readonly value="{{$package_item->total_after_discount}}"
               name="packages_total_after_discount[]"
               id="package-total-after-discount-{{$package->id}}"
        >
    </td>
    <td>
        <i class="fa fa-trash fa-2x" onclick="removePackageFromTable({{$package->id}})"
           style="color:#F44336; cursor: pointer">
        </i>

        <a style="cursor:pointer" data-toggle="modal" onclick="getPackageInfo({{$package->id}})"
           data-target="#package_info_data" title="{{__('Package details')}}">
            <i class="fa fa-eye fa-2x " style="color:#F44336"></i>
        </a>
    </td>
</tr>

{{--<input type="hidden" name="packages_items_count" id="packages_items_count" value="{{$items_count}}">--}}
{{--<input type="hidden" name="package_id" value="{{$package->id}}" id="package-{{$items_count}}">--}}
