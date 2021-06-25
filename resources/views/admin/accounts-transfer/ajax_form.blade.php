<div class="form-group has-feedback col-sm-4">
    <label for="inputPhone" class="control-label">{{__('Account From')}}</label>
    <div class="input-group">
        <span class="input-group-addon fa fa-envelope"></span>
        <select class="form-control js-example-basic-single"
                onchange="getAccountBalance('from'); hideAccountFromOfAccountTo();"
                id="account_from_id" name="account_from_id">
            <option value="">{{__('Select Account')}}</option>
            @foreach($accounts as $k => $v)
                <option value="{{$k}}"
                        {{isset($accounts_transfer) && $accounts_transfer->account_from_id == $k? 'selected':''}}>
                    {{$v}}
                </option>
            @endforeach
        </select>
    </div>
    {{input_error($errors,'account_from_id')}}
</div>

<div class="form-group has-feedback col-sm-4">
    <label for="inputPhone" class="control-label">{{__('Account To')}}</label>
    <div class="input-group">
        <span class="input-group-addon fa fa-envelope"></span>
        <select class="form-control js-example-basic-single" onchange="getAccountBalance('to')"
                id="account_to_id" name="account_to_id">
            <option value="">{{__('Select Account')}}</option>
            @foreach($accounts as $k => $v)
                <option value="{{$k}}" class="account_to_options" id="account_to_{{$k}}"
                        {{isset($accounts_transfer) && $accounts_transfer->account_to_id == $k? 'selected':''}}>
                    {{$v}}
                </option>
            @endforeach
        </select>
    </div>
    {{input_error($errors,'account_to_id')}}
</div>