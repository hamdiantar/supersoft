<li class="form-group" id="data_by_branch">
    <select name="name" class="form-control js-example-basic-single">
        <option value="">{{__('Select Name')}}</option>
        @foreach($accounts_search as $k=>$v)
            <option value="{{$k}}">{{$v}}</option>
        @endforeach
    </select>
</li>