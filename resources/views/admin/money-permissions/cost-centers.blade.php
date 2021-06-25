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