
                    <div class="row">

                    <div class="col-xs-12">
                    <div class="col-md-12">
                        @if(authIsSuperAdmin())
<div class="form-group has-feedback">
    <label for="inputPhone" class="control-label">{{__('Branches')}}</label>
    <div class="input-group">
        <span class="input-group-addon fa fa-file"></span>
        <select class="form-control js-example-basic-single" id="branch_id" name="branch_id" onchange="getUsers()">
            <option value="">{{__('Select Branches')}}</option>
            @foreach($branches as $k => $v)
                <option value="{{$k}}" {{isset($locker) && $locker->branch_id == $k? 'selected':''}}>{{$v}}</option>
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
    <label for="inputNameAr" class="control-label">{{__('Locker Name Ar')}}</label>
    <div class="input-group">
        <span class="input-group-addon"><li class="fa fa-user"></li></span>
        <input type="text" name="name_ar" class="form-control" id="inputNameEn"
               placeholder="{{__('NameAr')}}" value="{{old('name_ar', isset($locker)? $locker->name_ar:'')}}">
    </div>
    {{input_error($errors,'name_ar')}}
</div>

                        </div>


                        <div class="col-md-4">

                        <div class="form-group">
    <label for="inputName" class="control-label">{{__('Locker Name En')}}</label>
    <div class="input-group">
        <span class="input-group-addon"><li class="fa fa-user"></li></span>
        <input type="text" name="name_en" class="form-control" id="inputNameEn"
               placeholder="{{__('NameEn')}}" value="{{old('name_en', isset($locker)? $locker->name_en:'')}}">
    </div>
    {{input_error($errors,'name_en')}}
</div>
                        </div>      
                        <div class="col-md-4">
                        <div class="form-group">
    <label for="inputNameAr" class="control-label">{{__('Locker balance')}}</label>
    <div class="input-group">
        <span class="input-group-addon"><li class="fa fa-money"></li></span>
        <input type="text" name="balance" class="form-control" id="inputNameEn"
               {{isset($locker) && $locker->revenueReceipts->count() || isset($locker) &&
               $locker->expensesReceipts->count() ? 'disabled':''}}
               placeholder="{{__('Locker balance')}}" value="{{old('balance', isset($locker)? $locker->balance:'')}}">
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
        <input type="checkbox" id="switch-1" name="status"{{!isset($locker)?'checked':''}}
                {{isset($locker) && $locker->status? 'checked':''}}
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
                {{isset($locker) && $locker->special? 'checked':''}} onclick="showUsers()"
        >
        <label for="switch-2">{{__('Active')}}</label>
    </div>
</div>
                        </div>

                        <div class="col-md-4">
                        <div class="form-group has-feedback" id="allowed_users"  style="display: {{isset($locker) && $locker->special? '':'none'}}">
    <label for="inputPhone" class="control-label">{{__('Users')}}</label>
    <div class="input-group">
        <span class="input-group-addon fa fa-file"></span>
        <select class="form-control js-example-basic-single" multiple="multiple"  name="users[]" style="width: 100%">
{{--            <option value="">{{__('Select Users')}}</option>--}}
            @foreach($users as $k => $v)
                <option value="{{$k}}"
                        {{ isset($locker) && in_array($k,$locker->users->pluck('id')->toArray()) ? 'selected':''}}>
                    {{$v}}
                </option>
            @endforeach
        </select>
    </div>
    {{input_error($errors,'users')}}
</div>

                        </div>        

                                        

                    </div>                    
                    

                  </div>  







<div class="form-group  col-sm-12">
    <label for="inputDescription" class="control-label">{{__('Description')}}</label>
    <div class="input-group">
        <textarea name="description" class="form-control" rows="4" cols="150"
        >{{old('description', isset($locker)? $locker->description :'')}}</textarea>
    </div>
    {{input_error($errors,'description')}}
</div>



<div class="form-group col-sm-12">
    @include('admin.buttons._save_buttons')
</div>
