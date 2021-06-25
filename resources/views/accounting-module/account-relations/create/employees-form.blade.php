<form id="account-relations-employees-form" method="post" action="{{ $form_route }}">
    @csrf
    <input type="hidden" name="branch_id" value="{{ auth()->user()->branch_id }}"/>
    <input type="hidden" name="action_for" value="employees"/>

    <div class="form-group col-md-8">
        <label> {{ __('accounting-module.related_as') }} </label>
        <div class="form-check">
            <input class="form-check-input" type="radio" checked name="related_as" id="global" value="global">
            <label class="form-check-label" for="global">
                {{ __('accounting-module.global') }}
            </label>
            <input class="form-check-input" type="radio" name="related_as" id="actor_group" value="actor_group">
            <label class="form-check-label" for="actor_group">
                {{ __('accounting-module.actor_group') }}
            </label>
            <input class="form-check-input" type="radio" name="related_as" id="actor_id" value="actor_id">
            <label class="form-check-label" for="actor_id">
                {{ __('accounting-module.actor_id') }}
            </label>
        </div>
    </div>
    <div class="form-group col-md-4" id="select-actor_group" style="display: none">
        <label> {{ __('accounting-module.actor_group') }} </label>
        <select name="group_id" class="form-control select2" style="width:100%">
            <option value=""> {{ __('accounting-module.select-one') }} </option>
            @foreach($groups as $group)
                <option value="{{$group->id}}"> {{ $group->name }} </option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-md-4" id="select-actor_id" style="display: none">
        <label> {{ __('accounting-module.employee') }} </label>
        <select name="employee_id" class="form-control select2" style="width:100%">
            <option value=""> {{ __('accounting-module.select-one') }} </option>
            @foreach($employees as $employee)
                <option value="{{$employee->id}}"> {{ $employee->name }} </option>
            @endforeach
        </select>
    </div>
    <div class="clearfix"></div>
    <div class="form-group col-md-6" id="accounts-tree-root-id">
        <label> {{ __('accounting-module.account-root') }} </label>
        <select name="accounts_tree_root_id" class="form-control select2" style="width:100%">
            <option value=""> {{ __('accounting-module.select-one') }} </option>
            @foreach($root_accounts_tree as $root_acc)
                <option value="{{ $root_acc->id }}">
                    {{ $lang == 'ar' ? $root_acc->name_ar : $root_acc->name_en }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-md-6" id="accounts-tree-id">
        <label> {{ __('accounting-module.account-branch') }} </label>
        <select name="accounts_tree_id" class="form-control select2" style="width:100%">
            <option value=""> {{ __('accounting-module.select-one') }} </option>
        </select>
    </div>
    <div class="form-group col-md-12" id="save-btn">
        <button id="form-save-btn" type="button" class="btn hvr-rectangle-in saveAdd-wg-btn"><i class="ico ico-left fa fa-save"></i> {{ __('words.save') }} </button>
        <button id="btn-clear-2" type="button" onclick="accountingModuleClearFrom(event)"
        class="btn hvr-rectangle-in resetAdd-wg-btn"><i class="ico ico-left fa fa-trash"></i> {{ __('words.clear') }} </button>
        <button id="btn-cancel-2" type="button" onclick="accountingModuleCancelForm('{{ route('account-relations.index') }}')"
        class="btn hvr-rectangle-in closeAdd-wg-btn"><i class="ico ico-left fa fa-close"></i> {{ __('words.cancel') }} </button>
    </div>
</form>