
@if(authIsSuperAdmin())
    <div class="form-group has-feedback col-sm-12">
        <label for="inputPhone" class="control-label">{{__('Branch')}}</label>
        <div class="input-group">
            <span class="input-group-addon fa fa-file"></span>
            <select class="form-control js-example-basic-single" name="branch_id" id="branch_id" onchange="getByBranch()">
                <option value="">{{__('Select Branch')}}</option>
                @foreach($branches as $k => $v)
                    <option value="{{$k}}" {{isset($accounts_transfer) && $accounts_transfer->branch_id == $k? 'selected':''}}>{{$v}}</option>
                @endforeach
            </select>
        </div>
        {{input_error($errors,'branch_id')}}
    </div>
@endif


<div id="data_by_branch">
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
                <option value="" id="default_option">{{__('Select Account')}}</option>
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
</div>


<div class="col-md-4">

    <div class="form-group">
        <label for="inputName" class="control-label">{{__('the Amount')}}</label>
        <div class="input-group">
            <span class="input-group-addon"><li class="fa fa-money"></li></span>
            <input type="text" name="amount" class="form-control" id="inputNameEn"
                   placeholder="{{__('')}}" value="{{old('amount', isset($accounts_transfer)? $accounts_transfer->amount:0)}}">
        </div>
        {{input_error($errors,'amount')}}
    </div>
</div>
	
<div class="col-md-4">
    <div class="form-group">
    <label for="inputName" class="control-label">{{__('Account From Balance')}}</label>
    <div class="input-group">
        <span class="input-group-addon"><li class="fa fa-money"></li></span>
        <input type="text" name="account_balance" readonly class="form-control"
               id="account_from_balance" placeholder="{{__('')}}" {{isset($lockers_transaction) ? 'disabled' : ''}}
               value="{{old('account_balance', isset($accounts_transfer)? optional($accounts_transfer->accountFrom)->balance:'')}}">
    </div>
    {{input_error($errors,'account_balance')}}
    </div>
</div>

 <div class="col-md-4">
     <div class="form-group">
    <label for="inputName" class="control-label">{{__('Account To Balance')}}</label>
    <div class="input-group">
        <span class="input-group-addon"><li class="fa fa-money"></li></span>
        <input type="text" name="account_balance" readonly class="form-control"
               id="account_to_balance" placeholder="{{__('')}}" {{isset($lockers_transaction) ? 'disabled' : ''}}
               value="{{old('account_balance', isset($accounts_transfer)? optional($accounts_transfer->accountTo)->balance:'')}}">
    </div>
    {{input_error($errors,'account_balance')}}
     </div>
 </div>

<div class="col-md-4">
    <div class="form-group">
    <label for="inputNameAr" class="control-label">{{__('Date')}}</label>
    <div class="input-group">
        <span class="input-group-addon"><li class="fa fa-user"></li></span>
        <input type="date" name="date" class="form-control"
               value="{{isset($accounts_transfer)? \Carbon\Carbon::createFromDate($accounts_transfer->date)->format('Y-m-d')
                 : \Carbon\Carbon::now()->format('Y-m-d')}}"
        >
    </div>
    {{input_error($errors,'date')}}
    </div>
</div>


<div class="form-group col-sm-8">
    <label for="inputDescription" class="control-label">{{__('Description')}}</label>
    <div class="input-group">
        <textarea name="description" class="form-control" rows="4" cols="150"
        >{{old('description', isset($accounts_transfer)? $accounts_transfer->description :'')}}</textarea>
    </div>
    {{input_error($errors,'description')}}
</div>


<div class="form-group col-sm-12">
    @include('admin.buttons._save_buttons')
</div>
