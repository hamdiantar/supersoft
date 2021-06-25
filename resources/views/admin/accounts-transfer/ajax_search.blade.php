<select name="account_from_id" class="form-control js-example-basic-single">
    <option value="">{{__('Select Account From')}}</option>
    @foreach($accounts as $k=>$v)
        <option value="{{$k}}">{{$v}}</option>
    @endforeach
</select>

<select name="account_to_id" class="form-control js-example-basic-single">
    <option value="">{{__('Select Account To')}}</option>
    @foreach($accounts as $k=>$v)
        <option value="{{$k}}">{{$v}}</option>
    @endforeach
</select>

<select name="created_by" class="form-control js-example-basic-single">
    <option value="">{{__('Select User')}}</option>
    @foreach($users as $k=>$v)
        <option value="{{$k}}">{{$v}}</option>
    @endforeach
</select>