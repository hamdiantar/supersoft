<form id="account-relations-lockers-banks-form" method="post" action="{{ $form_route }}">
    @csrf
    <input type="hidden" name="branch_id" value="{{ auth()->user()->branch_id }}"/>
    <input type="hidden" name="action_for" value="lockers-banks"/>

    <div class="form-group col-md-6">
        <label> {{ __('accounting-module.account-nature') }} </label>
        <div class="form-check">
            <input class="form-check-input" type="radio" checked name="account_nature" id="locker" value="locker">
            <label class="form-check-label" for="locker">
                {{ __('accounting-module.locker') }}
            </label>
            <input class="form-check-input" type="radio" name="account_nature" id="bank_acc" value="bank_acc">
            <label class="form-check-label" for="bank_acc">
                {{ __('accounting-module.bank_acc') }}
            </label>
        </div>
    </div>
    <div class="form-group col-md-6" id="locker-id">
        <label> {{ __('accounting-module.locker') }} </label>
        <select name="locker_id" class="form-control select2" style="width:100%">
            <option value=""> {{ __('accounting-module.select-one') }} </option>
            @foreach(\App\Models\Locker::where('status',1)->where('branch_id', auth()->user()->branch_id)->get() as $locker)
                <option value="{{$locker->id}}"> {{ $locker->get_trans_name() }} </option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-md-6" id="bank-acc-id" style="display: none">
        <label> {{ __('accounting-module.bank_acc') }} </label>
        <select name="bank_id" class="form-control select2" style="width:100%">
            <option value=""> {{ __('accounting-module.select-one') }} </option>
            @foreach(\App\Models\Account::where('status',1)->where('branch_id', auth()->user()->branch_id)->get() as $account)
                <option value="{{$account->id}}"> {{ $account->get_trans_name() }} </option>
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