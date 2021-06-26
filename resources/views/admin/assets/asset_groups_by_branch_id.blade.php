<option>{{__('Select Group')}}</option>
@foreach($assets_groups as $assets_group)
    <option value="{{$assets_group->id}}">{{$assets_group->name}}</option>
@endforeach

