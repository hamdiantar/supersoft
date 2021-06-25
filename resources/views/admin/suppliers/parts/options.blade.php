@foreach($subSupplierGroups as $subSupplierGroup)
    <option value="{{$subSupplierGroup->id}}" class="removeToNewData">
        {{$subSupplierGroup->name}}
    </option>
@endforeach
