<div class="col-md-4">
    <div class="form-group">
        <label> {{ __('User Account Type') }} </label>
        <select class="form-control select2" name="user_account_type" onchange="change_user_accounts()">
            <option value="" data-url=""> {{ __("Select One") }} </option>
            <option value="customers" {{ isset($type) && $type == "customers" ? "selected" : "" }}
                data-url="{{ route('admin:get-customers-select') }}"> {{ __("Customers") }} </option>
            <option value="suppliers" {{ isset($type) && $type == "suppliers" ? "selected" : "" }}
                data-url="{{ route('admin:get-suppliers-select') }}"> {{ __("Suppliers") }} </option>
            <option value="employees" {{ isset($type) && $type == "employees" ? "selected" : "" }}
                data-url="{{ route('admin:get-employees-select') }}"> {{ __("Employees") }} </option>
        </select>
    </div>
</div>
<div class="col-md-4">
    <div class="form-group">
        <label> {{ __('User Account Name') }} </label>
        <select class="form-control select2" name="user_account_id">
            <option value=""> {{ __("Select One") }} </option>
            @if(isset($id))
                <option value="{{ $id }}" selected></option>
            @endif
        </select>
    </div>
</div>
<div class="col-md-4">
    <div class="form-group">
        <label> {{ __('accounting-module.cost-center') }} </label>
        <select name="cost_center_id" class="form-control select2">
            {!!
                \App\AccountingModule\Controllers\CostCenterCont::build_centers_options(
                    isset($selected_id) ? $selected_id : NULL ,
                    NULL ,
                    1 ,
                    true ,
                    isset($branch_id) ? $branch_id : NULL
                )
            !!}
        </select>
    </div>
</div>