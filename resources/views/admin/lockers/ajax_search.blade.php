<li class="form-group">
    <select name="name" class="form-control js-example-basic-single">
        <option value="">{{__('Select Name')}}</option>
        @foreach($lockers_search as $k=>$v)
            <option value="{{$k}}">{{$v}}</option>
        @endforeach
    </select>
</li>