<div class="form-group col-md-6" id="accounts-tree-root-id">
    <label> {{ __('accounting-module.account-root') }} </label>
    <select name="accounts_tree_root_id" class="form-control select2" style="width:100%">
        <option value=""> {{ __('accounting-module.select-one') }} </option>
        @foreach($root_accounts_tree as $root_acc)
            <option value="{{ $root_acc->id }}" {{ $model->accounts_tree_root_id == $root_acc->id ? 'selected' : '' }}>
                {{ $lang == 'ar' ? $root_acc->name_ar : $root_acc->name_en }}
            </option>
        @endforeach
    </select>
</div>
<div class="form-group col-md-6" id="accounts-tree-id">
    <label> {{ __('accounting-module.account-branch') }} </label>
    <select name="accounts_tree_id" class="form-control select2" style="width:100%">
        <option value=""> {{ __('accounting-module.select-one') }} </option>
        @foreach($accounts_tree as $acc)
            <option value="{{ $acc->id }}" {{ $model->accounts_tree_id == $acc->id ? 'selected' : '' }}>
                {{ $lang == 'ar' ? $acc->name_ar : $acc->name_en }}
            </option>
        @endforeach
    </select>
</div>