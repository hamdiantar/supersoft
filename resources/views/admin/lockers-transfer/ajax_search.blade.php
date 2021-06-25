<select name="locker_from_id" class="form-control js-example-basic-single">
    <option value="">{{__('Select Locker From')}}</option>
    @foreach($lockers as $k=>$v)
        <option value="{{$k}}">{{$v}}</option>
    @endforeach
</select>


<select name="locker_to_id" class="form-control js-example-basic-single">
    <option value="">{{__('Select Locker To')}}</option>
    @foreach($lockers as $k=>$v)
        <option value="{{$k}}">{{$v}}</option>
    @endforeach
</select>


<select name="created_by" class="form-control js-example-basic-single">
    <option value="">{{__('Select User')}}</option>
    @foreach($users as $k=>$v)
        <option value="{{$k}}">{{$v}}</option>
    @endforeach
</select>