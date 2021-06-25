<div class="row">
    <div class="col-md-12">
        <div class="col-md-6">
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
        <div class="col-md-6">
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
        <div class="col-md-6">
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
        <div class="col-md-6">
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
        <div class="col-md-6">
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
        <div class="col-md-6">
            <div class="form-group">
                <label for="inputimage" class="control-label">{{__('Image')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-user"></li></span>
                    <input type="file" name="image" class="form-control" onchange="preview_image(event)">
                </div>
                {{input_error($errors,'image')}}
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="col-md-6">
            <div class="form-group">
                @include('admin.buttons._save_buttons')
            </div>
        </div>
    </div>
</div>
