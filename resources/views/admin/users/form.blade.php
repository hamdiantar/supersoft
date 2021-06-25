<div class="row">
<div class="col-xs-12">

<div class="row top-data-wg" style="box-shadow: 0 0 7px 1px #DDD;margin:5px 5px 10px;padding-top:20px">

            @if(authIsSuperAdmin())
            <div class="col-md-12">
                <div class="form-group has-feedback">
                    <label for="inputPhone" class="control-label">{{__('Branch')}}</label>
                    <div class="input-group">
                        <span class="input-group-addon fa fa-file"></span>
                        <select class="form-control js-example-basic-single" name="branch_id">
                            <option value="">{{__('Select Branch')}}</option>
                            @foreach($branches as $k => $v)
                                <option value="{{$k}}" {{isset($user) && $user->branch_id == $k? 'selected':''}}>{{$v}}</option>
                            @endforeach
                        </select>
                    </div>
                    {{input_error($errors,'branch_id')}}
                </div>
                </div>
            @endif



    <div class="col-md-12">


        <div class="col-md-4">
            <div class="form-group">
                <label for="inputName" class="control-label">{{__('Name')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-user"></li></span>
                    <input type="text" name="name" class="form-control" id="inputName"
                           placeholder="{{__('Name')}}" value="{{old('name', isset($user)? $user->name:'')}}">
                </div>
                {{input_error($errors,'name')}}
            </div>
        </div>


        <div class="col-md-4">
            <div class="form-group has-feedback">
                <label for="inputEmail" class="control-label">{{__('Email')}}</label>
                <div class="input-group">
                    <span class="input-group-addon fa fa-mail-forward"></span>
                    <input type="email" name="email" class="form-control" id="inputEmail" placeholder="{{__('Email')}}"
                           value="{{old('email', isset($user)? $user->email:'')}}">
                </div>
                {{input_error($errors,'email')}}
            </div>
        </div>


        <div class="col-md-4">
            <div class="form-group">
                <label for="inputUserName" class="control-label">{{__('UserName')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-user"></li></span>
                    <input type="text" name="username" class="form-control" id="inputName"
                           placeholder="{{__('UserName')}}"
                           value="{{old('username', isset($user)? $user->username:'')}}">
                </div>
                {{input_error($errors,'username')}}
            </div>
        </div>


    </div>

    <div class="col-md-12">


        <div class="col-md-4">
            <div class="form-group has-feedback">
                <label for="inputPassword" class="control-label">{{__('Password')}}</label>
                <div class="input-group">
                    <span class="input-group-addon fa fa-check"></span>
                    <input type="password" name="password" class="form-control" id="inputPassword"
                           placeholder="{{__('Password')}}">
                </div>
                {{input_error($errors,'password')}}
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group has-feedback">
                <label for="inputPhone" class="control-label">{{__('Phone')}}</label>
                <div class="input-group">
                    <span class="input-group-addon fa fa-phone"></span>
                    <input type="text" name="phone" class="form-control" id="inputPhone"
                           placeholder="{{__('Phone')}}" value="{{old('phone', isset($user)? $user->phone:'')}}"
                    >
                </div>
                {{input_error($errors,'phone')}}
            </div>
        </div>


        <div class="col-md-4">
            @if (checkIfShiftActive())
                <div class="form-group has-feedback">
                    <label for="inputPhone" class="control-label">{{__('Shifts')}}</label>
                    <div class="input-group">
                        <span class="input-group-addon fa fa-file"></span>
                        <select class="form-control js-example-basic-single" multiple="multiple" name="shifts[]">
                            {{--            <option value="">{{__('Select shifts')}}</option>--}}
                            @foreach($shifts as $k => $v)
                                <option value="{{$k}}" {{isset($user) && in_array($k, $user->shifts->pluck('id')->toArray()) ? 'selected':''}}>{{$v}}</option>
                            @endforeach
                        </select>
                    </div>
                    {{input_error($errors,'shifts')}}
                </div>
            @endif
        </div>

    </div>
    </div>
    </div>
    </div>

    <div class="row center-data-wg" style="box-shadow: 0 0 7px 1px #DDD;margin:5px 5px 10px;padding-top:20px">

    <div class="col-md-12">


        @if(authIsSuperAdmin())
            <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="inputPhone" class="control-label">{{__('Super Admin')}}</label>
                    <div class="switch primary">
                        <input type="checkbox" id="switch-2" name="super_admin"
                                {{isset($user) && $user->super_admin? 'checked':''}}
                        >
                        <label for="switch-2">{{__('Super Admin')}}</label>
                    </div>
                </div>
            </div>
        @endif

            <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="is_admin_branch" class="control-label">{{__('Branch Admin')}}</label>
                    <div class="switch primary">
                        <input type="checkbox" id="is_admin_branch-33" name="is_admin_branch" value="1"
                            {{isset($user) && $user->is_admin_branch? 'checked':''}}
                        >
                        <label for="is_admin_branch-33">{{__('Branch Admin')}}</label>
                    </div>
                </div>
            </div>

        <div class="col-md-4">
            <div class="form-group has-feedback">
                <label for="inputPhone" class="control-label">{{__('Roles')}}</label>
                <div class="input-group">
                    @foreach($roles as $role)
                        <div class="radio">
                            <input type="radio" id="checkbox-{{$role->id}}" name="roles[]"
                                   value="{{$role->id}}" {{isset($user) && $user->hasRole($role->name) ? 'checked':''}}>
                            <label for="checkbox-{{$role->id}}"> {{explode('_', $role->name)[0]}} </label>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group has-feedback">
                <label for="inputPhone" class="control-label">{{__('Status')}}</label>
                <div class="switch primary">
                    <input type="checkbox" id="switch-1" name="status"{{!isset($user)?'checked':''}}
                            {{isset($user) && $user->status? 'checked':''}}
                    >
                    <label for="switch-1">{{__('Active')}}</label>
                </div>
            </div>
        </div>


    </div>
    </div>

                @include('admin.buttons._save_buttons')
        
    </div>
 


