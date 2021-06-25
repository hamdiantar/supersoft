<div class="row">

    <div class="col-md-12">
    <div class="col-xs-12">

            @if(authIsSuperAdmin())
                <div class="form-group has-feedback">
                <label for="inputPhone" class="control-label">{{__('Branch')}}</label>
                <div class="input-group">
                    <span class="input-group-addon fa fa-file"></span>
                    <select class="form-control js-example-basic-single" id="branch_id" {{isset($lockers_transaction) ? 'disabled' : ''}}
                            name="branch_id" onchange="getByBranch()">
                        <option value="">{{__('Select Branch')}}</option>
                        @foreach($branches as $k => $v)
                            <option value="{{$k}}" {{isset($lockers_transaction) && $lockers_transaction->branch_id == $k? 'selected':''}}>{{$v}}</option>
                        @endforeach
                    </select>
                </div>
                {{input_error($errors,'branch_id')}}
                </div>
            @endif

    </div>
    </div>
</div>

<div id="data_by_branch">

    <div class="col-md-4">
        <div class="form-group has-feedback">
            <label for="inputPhone" class="control-label">{{__('Locker name')}}</label>
            <div class="input-group">
                <span class="input-group-addon fa fa-lock"></span>
                <select class="form-control js-example-basic-single" id="locker_id" {{isset($lockers_transaction) ? 'disabled' : ''}}
                        name="locker_id" onchange="getBalance()">
                    <option value="">{{__('Select Locker')}}</option>
                    @foreach($lockers as $k => $v)
                        <option value="{{$k}}" {{isset($lockers_transaction) && $lockers_transaction->locker_id == $k? 'selected':''}}>{{$v}}</option>
                    @endforeach
                </select>
            </div>
            {{input_error($errors,'locker_id')}}
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="inputName" class="control-label">{{__('Account name')}}</label>

            <div class="input-group">
                <span class="input-group-addon fa fa-envelope"></span>
                <select class="form-control js-example-basic-single" id="account_id" {{isset($lockers_transaction) ? 'disabled' : ''}}
                name="account_id" onchange="getAccountBalance()">
                    <option value="">{{__('Select Account')}}</option>
                    @foreach($accounts as $k => $v)
                        <option value="{{$k}}" {{isset($lockers_transaction) && $lockers_transaction->account_id == $k? 'selected':''}}>{{$v}}</option>
                    @endforeach
                </select>
            </div>
            {{input_error($errors,'account_id')}}
        </div>
    </div>

</div>





<div class="col-md-4">
    <div class="form-group has-feedback">

    <label for="inputPhone" class="control-label">{{__('Operation Type')}}</label>
    <div class="input-group">
        <span class="input-group-addon fa fa-envelope"></span>
        <select class="form-control js-example-basic-single" name="type" {{isset($lockers_transaction) ? 'disabled' : ''}}>
            <option value="">{{__('Select Type')}}</option>
            <option value="deposit" {{isset($lockers_transaction) && $lockers_transaction->type == 'deposit'? 'selected':''}}>
                {{__('Deposit')}}
            </option>
            <option value="withdrawal" {{isset($lockers_transaction) && $lockers_transaction->type == 'withdrawal'? 'selected':''}}>
                {{__('Withdrawal')}}
            </option>
        </select>
    </div>
    {{input_error($errors,'type')}}
</div>
</div>


<div class="col-md-4">
    <div class="form-group has-feedback">
    <label for="inputPhone" class="control-label">{{__('Locker balance')}}</label>
    <div class="input-group">
        <span class="input-group-addon"><li class="fa fa-money"></li></span>
        <input type="text" name="locker_balance" readonly class="form-control"
               id="locker_balance" placeholder="{{__('')}}" {{isset($lockers_transaction) ? 'disabled' : ''}}
               value="{{old('locker_balance', isset($lockers_transaction)? optional($lockers_transaction->locker)->balance:'')}}">
    </div>
    {{input_error($errors,'locker_balance')}}
</div>
</div>


	
<div class="col-md-4">
    <div class="form-group">
    <label for="inputName" class="control-label">{{__('Account balance')}}</label>
    <div class="input-group">
        <span class="input-group-addon"><li class="fa fa-money"></li></span>
        <input type="text" name="account_balance" readonly class="form-control"
               id="account_balance" placeholder="{{__('')}}" {{isset($lockers_transaction) ? 'disabled' : ''}}
               value="{{old('account_balance', isset($lockers_transaction)? optional($lockers_transaction->account)->balance:'')}}">
    </div>
    {{input_error($errors,'account_balance')}}
</div>

</div>

<div class="col-md-4">
    <div class="form-group">
    <label for="inputName" class="control-label">{{__('the Amount')}}</label>
    <div class="input-group">
        <span class="input-group-addon"><li class="fa fa-money"></li></span>
        <input type="text" name="amount" class="form-control" id="inputNameEn"
               placeholder="{{__('')}}" value="{{old('amount', isset($lockers_transaction)? $lockers_transaction->amount:0)}}">
    </div>
    {{input_error($errors,'amount')}}
</div>
</div>

<div class="col-md-4">
    <div class="form-group">
        <label for="inputNameAr" class="control-label">{{__('Date')}}</label>
        <div class="input-group">
            <span class="input-group-addon"><li class="fa fa-user"></li></span>
            <input type="date" name="date" class="form-control"
                    value="{{isset($lockers_transaction)? \Carbon\Carbon::createFromDate($lockers_transaction->date)->format('Y-m-d')
                     : \Carbon\Carbon::now()->format('Y-m-d')}}"
            >
        </div>
        {{input_error($errors,'date')}}
    </div>
</div>


<div class="form-group col-sm-12">
    @include('admin.buttons._save_buttons')
</div>
