
    <select name="locker_id" class="form-control js-example-basic-single">
        <option value="">{{__('Select Locker')}}</option>
        @foreach($lockers as $k=>$v)
            <option value="{{$k}}">{{$v}}</option>
        @endforeach
    </select>


    <select name="account_id" class="form-control js-example-basic-single">
        <option value="">{{__('Select Account')}}</option>
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
