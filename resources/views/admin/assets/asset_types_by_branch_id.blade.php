<option>{{__('Select Type')}}</option>
@foreach($assets_types as $assets_type)
    <option value="{{$assets_type->id}}">{{$assets_type->name}}</option>
@endforeach

