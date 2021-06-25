<form id="account-relations-stores-form" method="post" action="{{ $form_route }}">
    @csrf
    <input type="hidden" name="old_id" value="{{ $model->id }}"/>
    <input type="hidden" name="action_for" value="stores"/>

    <div class="form-group col-md-6">
        <label> {{ __('accounting-module.account-nature') }} </label>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="related_as" id="store" value="store"
                {{ $model && $model->related_store && $model->related_store->related_as == 'store' ? 'checked' : '' }}>
            <label class="form-check-label" for="store">
                {{ __('accounting-module.related_as-store') }}
            </label>
            <input class="form-check-input" type="radio" name="related_as" id="branch-stores" value="branch-stores"
                {{ $model && $model->related_store && $model->related_store->related_as != 'store' ? 'checked' : '' }}>
            <label class="form-check-label" for="branch-stores">
                {{ __('accounting-module.related_as-branch-stores') }}
            </label>
        </div>
    </div>
    <div class="form-group col-md-6" id="related_id"
        style="display: {{ $model && $model->related_store && $model->related_store->related_as == 'store' ? 'block' : 'none' }}">
        <label> {{ __('accounting-module.related_as-store') }} </label>
        <select name="related_id" class="form-control select2" style="width:100%">
            <option value=""> {{ __('accounting-module.select-one') }} </option>
            @foreach($stores as $store)
                <option value="{{$store->id}}"
                    {{ $model && $model->related_store && $model->related_store->related_as == 'store' && $model->related_store->related_id == $store->id ? 'selected' : '' }}>
                    {{ $store->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="clearfix"></div>
    @include('accounting-module.account-relations.edit.common-tree')
    <div class="form-group col-md-12" id="save-btn">
        <button id="form-save-btn" type="button" class="btn hvr-rectangle-in saveAdd-wg-btn"><i class="ico ico-left fa fa-save"></i> {{ __('words.save') }} </button>
        <button id="btn-clear-2" type="button" onclick="accountingModuleClearFrom(event)"
        class="btn hvr-rectangle-in resetAdd-wg-btn"><i class="ico ico-left fa fa-trash"></i> {{ __('words.clear') }} </button>
        <button id="btn-cancel-2" type="button" onclick="accountingModuleCancelForm('{{ route('account-relations.index') }}')"
        class="btn hvr-rectangle-in closeAdd-wg-btn"><i class="ico ico-left fa fa-close"></i> {{ __('words.cancel') }} </button>
    </div>
</form>