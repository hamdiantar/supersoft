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