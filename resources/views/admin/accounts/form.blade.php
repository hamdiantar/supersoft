<div class="row">


    <div class="col-xs-12">

        <div class="col-md-12">
            @if(authIsSuperAdmin())
                <div class="form-group has-feedback">
                    <label for="inputPhone" class="control-label">{{__('Branch')}}</label>
                    <div class="input-group">
                        <span class="input-group-addon fa fa-file"></span>
                        <select class="form-control js-example-basic-single" id="branch_id" name="branch_id"
                                onchange="getUsers()">
                            <option value="">{{__('Select Branches')}}</option>
                            @foreach($branches as $k => $v)
                                <option value="{{$k}}" {{isset($account) && $account->branch_id == $k? 'selected':''}}>{{$v}}</option>
                            @endforeach
                        </select>
                    </div>
                    {{input_error($errors,'branch_id')}}
                </div>
            @endif
        </div>
    </div>

    <div class="col-md-12">

        <div class="col-md-4">
            <div class="form-group">
                <label for="inputNameAr" class="control-label">{{__('Bank Name Ar')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-user"></li></span>
                    <input type="text" name="name_ar" class="form-control" id="inputNameEn"
                           placeholder="{{__('NameAr')}}"
                           value="{{old('name_ar', isset($account)? $account->name_ar:'')}}">
                </div>
                {{input_error($errors,'name_ar')}}
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="inputName" class="control-label">{{__('Bank Name En')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-user"></li></span>
                    <input type="text" name="name_en" class="form-control" id="inputNameEn"
                           placeholder="{{__('NameEn')}}"
                           value="{{old('name_en', isset($account)? $account->name_en:'')}}">
                </div>
                {{input_error($errors,'name_en')}}
            </div>
        </div>

        <div class="col-md-4">

            <div class="form-group">
                <label for="inputName" class="control-label">{{__('Bank Number')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-lock"></li></span>
                    <input type="text" name="number" class="form-control" id="inputNameEn"
                           placeholder="{{__('Bank Number')}}"
                           value="{{old('name_en', isset($account)? $account->number:'')}}">
                </div>
                {{input_error($errors,'number')}}
            </div>
        </div>

    </div>

    <div class="col-md-12">

        <div class="col-md-4">
            <div class="form-group">
                <label for="inputNameAr" class="control-label">{{__('Balance')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-money"></li></span>
                    <input type="text" name="balance" class="form-control" id="inputNameEn"
                           {{isset($account) && $account->revenueReceipts->count() || isset($account) &&
                            $account->expensesReceipts->count() ? 'disabled':''}}
                           placeholder="{{__('Balance')}}" value="{{old('balance', isset($account)? $account->balance:'')}}">
                </div>
                {{input_error($errors,'balance')}}
            </div>
        </div>
    </div>

    <div class="col-md-12">

        <div class="col-md-4">
            <div class="form-group has-feedback">
                <label for="inputPhone" class="control-label">{{__('Status')}}</label>
                <div class="switch primary">
                    <input type="checkbox" id="switch-1" name="status"{{!isset($account)?'checked':''}}
                            {{isset($account) && $account->status? 'checked':''}}
                    >
                    <label for="switch-1">{{__('Active')}}</label>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group has-feedback">
                <label for="inputPhone" class="control-label">{{__('Special users')}}</label>
                <div class="switch primary">
                    <input type="checkbox" id="switch-2" name="special"
                           {{isset($account) && $account->special? 'checked':''}} onclick="showUsers()"
                    >
                    <label for="switch-2">{{__('Active')}}</label>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group has-feedback" id="allowed_users"
                 style="display: {{isset($account) && $account->special? '':'none'}}">
                <label for="inputPhone" class="control-label">{{__('Users')}}</label>
                <div class="input-group">
                    <span class="input-group-addon fa fa-file"></span>
                    <select class="form-control js-example-basic-single" multiple="multiple" name="users[]"
                            style="width: 100%">
                        {{--            <option value="">{{__('Select Users')}}</option>--}}
                        @foreach($users as $k => $v)
                            <option value="{{$k}}"
                                    {{ isset($account) && in_array($k,$account->users->pluck('id')->toArray()) ? 'selected':''}}>
                                {{$v}}
                            </option>
                        @endforeach
                    </select>
                </div>
                {{input_error($errors,'users')}}
            </div>
        </div>

        <div class="col-md-4">

        </div>

    </div>

</div>


<div class="form-group  col-sm-12">
    <label for="inputDescription" class="control-label">{{__('Description')}}</label>
    <div class="input-group">
        <textarea name="description" class="form-control" rows="4" cols="150"
        >{{old('description', isset($account)? $account->description :'')}}</textarea>
    </div>
    {{input_error($errors,'description')}}
</div>


<div class="form-group col-sm-12">
    @include('admin.buttons._save_buttons')
</div>
