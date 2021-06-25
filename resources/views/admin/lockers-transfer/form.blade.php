<div class="row">

    <div class="col-md-12">
        @if(authIsSuperAdmin())
            <div class="form-group has-feedback">
                <label for="inputPhone" class="control-label">{{__('Branch')}}</label>
                <div class="input-group">
                    <span class="input-group-addon fa fa-file"></span>
                    <select class="form-control js-example-basic-single" id="branch_id" name="branch_id"
                            onchange="getByBranch()">
                        <option value="">{{__('Select Branch')}}</option>
                        @foreach($branches as $k => $v)
                            <option value="{{$k}}" {{isset($lockers_transfer) && $lockers_transfer->branch_id == $k? 'selected':''}}>{{$v}}</option>
                        @endforeach
                    </select>
                </div>
                {{input_error($errors,'branch_id')}}
            </div>
        @endif
    </div>

    <div id="data_by_branch">

        <div class="col-md-4">
            <div class="form-group has-feedback">
                <label for="inputPhone" class="control-label">{{__('Locker From')}}</label>
                <div class="input-group">
                    <span class="input-group-addon fa fa-lock"></span>
                    <select class="form-control js-example-basic-single"
                            onchange=" hideLockerFromOfLockerTo(); getBalance('from');"
                            id="locker_from_id" name="locker_from_id">
                        <option value="">{{__('Select Locker')}}</option>
                        @foreach($lockers as $k => $v)
                            <option value="{{$k}}" id="locker_from_{{$k}}"
                                    {{isset($lockers_transfer) && $lockers_transfer->locker_from_id == $k? 'selected':''}}>
                                {{$v}}
                            </option>
                        @endforeach
                    </select>
                </div>
                {{input_error($errors,'locker_from_id')}}
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group has-feedback">
                <label for="inputPhone" class="control-label">{{__('Locker To')}}</label>
                <div class="input-group">
                    <span class="input-group-addon fa fa-lock"></span>
                    <select class="form-control js-example-basic-single" onchange="getBalance('to')"
                            id="locker_to_id" name="locker_to_id">
                        <option value="">{{__('Select Locker')}}</option>
                        @foreach($lockers as $k => $v)
                            <option value="{{$k}}" id="locker_to_{{$k}}" class="locker_to_options"
                                    {{isset($lockers_transfer) && $lockers_transfer->locker_to_id == $k? 'selected':''}}>
                                {{$v}}
                            </option>
                        @endforeach
                    </select>
                </div>
                {{input_error($errors,'locker_to_id')}}
            </div>
        </div>
    </div>

    <div class="form-group  col-sm-4">
        <label for="inputName" class="control-label">{{__('the Amount')}}</label>
        <div class="input-group">
            <span class="input-group-addon"><li class="fa fa-money"></li></span>
            <input type="text" name="amount" class="form-control" id="inputNameEn"
                   placeholder="{{__('')}}"
                   value="{{old('amount', isset($lockers_transfer)? $lockers_transfer->amount:0)}}">
        </div>
        {{input_error($errors,'amount')}}
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="inputName" class="control-label">{{__('Locker From Balance')}}</label>
            <div class="input-group">
                <span class="input-group-addon"><li class="fa fa-money"></li></span>
                <input type="text" name="locker_balance" readonly class="form-control"
                       id="locker_from_balance" placeholder="{{__('')}}"
                       {{isset($lockers_transaction) ? 'disabled' : ''}}
                       value="{{old('locker_balance', isset($lockers_transfer)? optional($lockers_transfer->lockerFrom)->balance:'')}}">
            </div>
            {{input_error($errors,'locker_balance')}}
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="inputName" class="control-label">{{__('Locker To Balance')}}</label>

            <div class="input-group">
                <span class="input-group-addon"><li class="fa fa-money"></li></span>
                <input type="text" name="locker_balance" readonly class="form-control"
                       id="locker_to_balance" placeholder="{{__('')}}" {{isset($lockers_transaction) ? 'disabled' : ''}}
                       value="{{old('locker_balance', isset($lockers_transfer)? optional($lockers_transfer->lockerTo)->balance:'')}}">
            </div>
            {{input_error($errors,'locker_balance')}}
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="inputNameAr" class="control-label">{{__('Date')}}</label>
            <div class="input-group">
                <span class="input-group-addon"><li class="fa fa-user"></li></span>
                <input type="date" name="date" class="form-control"
                       value="{{isset($lockers_transfer)? \Carbon\Carbon::createFromDate($lockers_transfer->date)->format('Y-m-d')
             : \Carbon\Carbon::now()->format('Y-m-d')}}"
                >
            </div>
            {{input_error($errors,'date')}}
        </div>
    </div>

    <div class="form-group  col-sm-12">
        <label for="inputDescription" class="control-label">{{__('Description')}}</label>
        <div class="input-group">
            <textarea name="description" class="form-control" rows="4" cols="150"
            >{{old('description', isset($lockers_transfer)? $lockers_transfer->description :'')}}</textarea>
        </div>
        {{input_error($errors,'description')}}
    </div>

    <div class="form-group col-sm-12">
        @include('admin.buttons._save_buttons')
    </div>

</div>

