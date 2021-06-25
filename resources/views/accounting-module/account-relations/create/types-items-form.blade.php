<form id="account-relations-types-items-form" method="post" action="{{ $form_route }}">
    @csrf
    <input type="hidden" name="branch_id" value="{{ auth()->user()->branch_id }}"/>
    <input type="hidden" name="action_for" value="types-items"/>

    <div class="form-group col-md-12">
        <label> {{ __('accounting-module.account-nature') }} </label>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="account_nature" id="credit" value="credit">
            <label class="form-check-label" for="credit">
                {{ __('accounting-module.credit') }}
            </label>
            <input class="form-check-input" type="radio" name="account_nature" id="debit" value="debit">
            <label class="form-check-label" for="debit">
                {{ __('accounting-module.debit') }}
            </label>
        </div>
    </div>
    <div class="form-group col-md-6" id="account-type-id">
        <label> {{ __('accounting-module.account-type') }} </label>
        <select name="account_type_id" class="form-control select2" style="width:100%">
        </select>
    </div>
    <div class="form-group col-md-6" id="account-item-id">
        <label> {{ __('accounting-module.account-item') }} </label>
        <select name="account_item_id" class="form-control select2" style="width:100%">
        </select>
    </div>
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
        <button id="btn-clear" type="button" onclick="accountingModuleClearFrom(event)"
        class="btn hvr-rectangle-in resetAdd-wg-btn"><i class="ico ico-left fa fa-trash"></i> {{ __('words.clear') }} </button>
        <button id="btn-cancel" type="button" onclick="accountingModuleCancelForm('{{ route('account-relations.index') }}')"
        class="btn hvr-rectangle-in closeAdd-wg-btn"><i class="ico ico-left fa fa-close"></i> {{ __('words.cancel') }} </button>
    </div>
</form>