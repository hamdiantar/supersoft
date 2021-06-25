<form id="account-relations-money-permissions-form" method="post" action="{{ $form_route }}">
    @csrf
    <input type="hidden" name="action_for" value="money-permissions"/>
    <input type="hidden" name="old_id" value="{{ $model->id }}"/>

    <div class="form-group col-md-6">
        <label> {{ __('accounting-module.account-nature') }} </label>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="account_nature" id="permission_locker" value="permission_locker"
                {{ $model->related_money_permission->money_gateway == 'locker' ? 'checked' : '' }}>
            <label class="form-check-label" for="permission_locker">
                {{ __('accounting-module.locker') }}
            </label>
            <input class="form-check-input" type="radio" name="account_nature" id="permission_bank" value="permission_bank"
                {{ $model->related_money_permission->money_gateway == 'bank' ? 'checked' : '' }}>
            <label class="form-check-label" for="permission_bank">
                {{ __('accounting-module.bank_acc') }}
            </label>
        </div>
    </div>
    <div class="form-group col-md-6">
        <label> {{ __('accounting-module.permission-nature') }} </label>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="permission_nature" id="exchange" value="exchange"
                {{ $model->related_money_permission->act_as == 'exchange' ? 'checked' : '' }}>
            <label class="form-check-label" for="exchange">
                {{ __('accounting-module.exchange') }}
            </label>
            <input class="form-check-input" type="radio" name="permission_nature" id="receive" value="receive"
                {{ $model->related_money_permission->act_as == 'receive' ? 'checked' : '' }}>
            <label class="form-check-label" for="receive">
                {{ __('accounting-module.receive') }}
            </label>
        </div>
    </div>
    @include('accounting-module.account-relations.edit.common-tree')
    <div class="form-group col-md-12" id="save-btn">
        <button id="form-save-btn" type="button" class="btn hvr-rectangle-in saveAdd-wg-btn"><i class="ico ico-left fa fa-save"></i> {{ __('words.save') }} </button>
        <button id="btn-clear" type="button" onclick="accountingModuleClearFrom(event)"
        class="btn hvr-rectangle-in resetAdd-wg-btn"><i class="ico ico-left fa fa-trash"></i> {{ __('words.clear') }} </button>
        <button id="btn-cancel" type="button" onclick="accountingModuleCancelForm('{{ route('account-relations.index') }}')"
        class="btn hvr-rectangle-in closeAdd-wg-btn"><i class="ico ico-left fa fa-close"></i> {{ __('words.cancel') }} </button>
    </div>
</form>