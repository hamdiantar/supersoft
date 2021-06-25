<div class="form-group has-feedback col-sm-4">
    <label for="inputPhone" class="control-label">{{__('Lockers')}}</label>
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

<div class="form-group has-feedback col-sm-4">
    <label for="inputPhone" class="control-label">{{__('Accounts')}}</label>
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