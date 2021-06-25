<form id="account-relations-discounts-form" method="post" action="{{ $form_route }}">
    @csrf
    <input type="hidden" name="old_id" value="{{ $model->id }}"/>
    <input type="hidden" name="action_for" value="discounts"/>

    <div class="form-group col-md-12">
        <label> {{ __('accounting-module.discount-type') }} </label>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="discount_type" id="earned" value="earned"
                {{ $model->related_discounts->discount_type == 'earned' ? 'checked' : '' }}>
            <label class="form-check-label" for="earned">
                {{ __('accounting-module.discount-earned') }}
            </label>
            <input class="form-check-input" type="radio" name="discount_type" id="permitted" value="permitted"
                {{ $model->related_discounts->discount_type == 'permitted' ? 'checked' : '' }}>
            <label class="form-check-label" for="permitted">
                {{ __('accounting-module.discount-permitted') }}
            </label>
            <input class="form-check-input" type="radio" name="discount_type" id="points" value="points"
                {{ $model->related_discounts->discount_type == 'points' ? 'checked' : '' }}>
            <label class="form-check-label" for="points">
                {{ __('accounting-module.discount-points') }}
            </label>
        </div>
    </div>
    @include('accounting-module.account-relations.edit.common-tree')
    <div class="form-group col-md-12" id="save-btn">
        <button id="form-save-btn" type="button" class="btn hvr-rectangle-in saveAdd-wg-btn"><i class="ico ico-left fa fa-save"></i> {{ __('words.save') }} </button>
        <button id="btn-clear-2" type="button" onclick="accountingModuleClearFrom(event)"
        class="btn hvr-rectangle-in resetAdd-wg-btn"><i class="ico ico-left fa fa-trash"></i> {{ __('words.clear') }} </button>
        <button id="btn-cancel-2" type="button" onclick="accountingModuleCancelForm('{{ route('account-relations.index') }}')"
        class="btn hvr-rectangle-in closeAdd-wg-btn"><i class="ico ico-left fa fa-close"></i> {{ __('words.cancel') }} </button>
    </div>
</form>