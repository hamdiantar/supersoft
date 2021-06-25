<form action="{{ $action }}" method="post" id="accounts-tree-form" onsubmit="return account_tree_form_submit(event)">
    @csrf
    <input type="hidden" name="branch_id" value="{{ auth()->user()->branch_id }}"/>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"> {{ $title }} </h4>
    </div>
    <div class="modal-body">
        <input type="hidden" name="accounts_tree_id" value="{{ $account_tree_id }}"/>
        <input type="hidden" name="tree_level" value="{{ !$model ? $account->tree_level + 1 : $account->tree_level }}"/>
        @if($model)
            <input type="hidden" name="id" value="{{ $model->id }}"/>
        @endif
        @if(auth()->user()->can('accounts-tree-index_account_nature_edit'))
            <div class="form-group col-md-6">
                <label> {{ __('accounting-module.account-type-name') }} </label>
                <select name="custom_type" class="form-control" {{ $model && $model->is_type_editable() == false ? 'disabled' : '' }}>
                    <option value="1" {{ $model && $model->custom_type == 1 ? 'selected' : '' }}> {{ __('accounting-module.budget') }} </option>
                    <option value="2" {{ $model && $model->custom_type == 2 ? 'selected' : '' }}> {{ __('accounting-module.income-list') }} </option>
                    <option value="3" {{ $model && $model->custom_type == 3 ? 'selected' : '' }}> {{ __('accounting-module.trading-account') }} </option>
                </select>
            </div>
            <div class="form-group col-md-6">
                <label> {{ __('accounting-module.account-nature') }} </label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="account_nature" id="credit" value="credit"
                        {{ $model && $model->account_nature == 'credit' ? 'checked' : '' }}>
                    <label class="form-check-label" for="credit">
                        {{ __('accounting-module.credit') }}
                    </label>
                    <input class="form-check-input" type="radio" name="account_nature" id="debit" value="debit"
                        {{ $model && $model->account_nature == 'debit' ? 'checked' : '' }}>
                    <label class="form-check-label" for="debit">
                        {{ __('accounting-module.debit') }}
                    </label>
                </div>
            </div>
        @else
            <input type="hidden" name="account_nature" value="{{ $model ? $model->account_nature : $nature }}"/>
            <input type="hidden" name="custom_type" value="{{ $model ? $model->custom_type : $type }}"/>
        @endif
        <div class="clearfix"></div>
        <div class="form-group col-md-6">
            <label> {{ __('accounting-module.name-ar') }} </label>
            <input name="name_ar" class="form-control" value="{{ $model ? $model->name_ar : '' }}"/>
        </div>
        <div class="form-group col-md-6">
            <label> {{ __('accounting-module.name-en') }} </label>
            <input name="name_en" class="form-control" value="{{ $model ? $model->name_en : '' }}"/>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="modal-footer">
        <button class="btn btn-primary"> {{ __('accounting-module.save') }} </button>
        <button type="button" class="btn btn-default" data-dismiss="modal"> {{ __('accounting-module.close') }} </button>
    </div>
</form>