<option>{{__('Select Store Creator')}}</option>
@foreach($employees as $employee)
    <option value="{{$employee->id}}">{{$employee->name}}</option>
@endforeach
