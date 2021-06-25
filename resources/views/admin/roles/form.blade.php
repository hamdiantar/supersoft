@if(authIsSuperAdmin())
    <div class="form-group has-feedback col-sm-12">
        <label for="inputPhone" class="control-label">{{__('Branches')}}</label>
        <div class="input-group">
            <span class="input-group-addon fa fa-file"></span>
            <select class="form-control js-example-basic-single" name="branch_id">
                <option value="">{{__('Select Branches')}}</option>
                @foreach($branches as $k => $v)
                    <option value="{{$k}}" {{isset($role) && $k == $branch_id? 'selected':''}}>{{$v}}</option>
                @endforeach
            </select>
        </div>
        {{input_error($errors,'branch_id')}}
    </div>
@endif


<div class="form-group has-feedback col-sm-12">
    <label for="inputPhone" class="control-label">{{__('Role Name')}}</label>
    <div class="input-group">
        <span class="input-group-addon fa fa-check"></span>
        <input type="text" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}"
               name="name" placeholder="{{__('Role Name')}}"
               value="{{old('name',isset($role) ? explode('_', $role->name)[0]:'')}}"
        >
    </div>
    {{input_error($errors,'name')}}
</div>


<div class="form-group has-feedback col-sm-12">
    {{--    <label for="inputPhone" class="control-label">{{__('Roles')}}</label>--}}
    <div class="input-group">
    <div class="col-xs-6">
{{--        <div class="switch primary">--}}
{{--            <input type="checkbox" id="switch-1" name="permissions[]" value="{{$loginPermission->id}}"--}}
{{--                    {{isset($role) && $role->hasPermissionTo($loginPermission->name)? 'checked':''}}>--}}
{{--            <label for="switch-1">{{__($loginPermission->name)}}</label>--}}
{{--        </div>--}}
        </div>
        <div class="col-xs-6">
        <div class="checkbox primary">
            <input type="checkbox" id="select-all">
            <label for="select-all">{{__('Select All')}}</label>
        </div>
</div>

    </div>
</div>



@foreach($modules as $module)
    <div class="col-lg-4 col-md-6 col-xs-12">
        <div class="box-content card bordered-all primary js__card">

            <h4 class="">

            </h4>
<span class="ribbon1 ribbon1-r ribbon-wg-new">
                                                    <span>
                                                    <div class="checkbox primary sheckbox-wg">
                    <input type="checkbox" id="select-{{$module->name}}-all"
                           onclick="checkThisModule('{{$module->name}}')">
                    <label for="select-{{$module->name}}-all">{{__($module->name)}}</label>
                </div>
                                                    </span>
                                                </span>
            <!-- /.box-title -->
            @foreach($module->permissions as $permission)
                <div class="switch primary" style=" margin: 17px;">
                    <input type="checkbox" class="checked_{{$module->name}}"
                           id="switch-{{$permission->id}}" name="permissions[]" value="{{$permission->id}}"
                            {{isset($role) && $role->hasPermissionTo($permission->name)? 'checked':''}}>
                    <label for="switch-{{$permission->id}}">{{ __($permission->name)}}</label>
                </div>
            @endforeach

        </div>
        <!-- /.box-content -->
    </div>
@endforeach

<div class="form-group col-sm-12">
    @include('admin.buttons._save_buttons')
</div>

{{--<div class="form-group col-sm-6">--}}
{{--    <button type="submit" class="btn btn-primary waves-effect waves-light">{{__('Save')}}</button>--}}
{{--</div>--}}
