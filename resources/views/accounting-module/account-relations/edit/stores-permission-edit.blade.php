<form id="account-relations-stores-permissions-form" method="post" action="{{ $form_route }}">
    @csrf
    <input type="hidden" name="action_for" value="stores-permissions"/>
    <input type="hidden" name="old_id" value="{{ $model->id }}"/>

    <div class="form-group col-md-12">
        <label> {{ __('accounting-module.permission-nature') }} </label>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="permission_nature" id="exchange" value="exchange"
                {{ $model->related_stores_permission->permission_nature == 'exchange' ? 'checked' : '' }}>
            <label class="form-check-label" for="exchange">
                {{ __('accounting-module.exchange') }}
            </label>
            <input class="form-check-input" type="radio" name="permission_nature" id="receive" value="receive"
                {{ $model->related_stores_permission->permission_nature == 'receive' ? 'checked' : '' }}>
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