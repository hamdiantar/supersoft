<div class="form_new_unit">

    {{-- unit one (default)  --}}

    @if(isset($part) && $part->prices->count())
        @foreach($part->prices as $index => $price)
            @include('admin.parts.units.ajax_form_new_unit', ['index'=> $index + 1])
        @endforeach
    @else
        @include('admin.parts.units.ajax_form_new_unit', ['index'=>1])
    @endif


    <input type="hidden" value="{{isset($part) && $part->prices->count() ? $part->prices->count() : 1}}" id="units_count">

</div>


<div class="col-md-12">
    <button type="button" title="new price" onclick="newUnit()"
            class="btn btn-sm btn-primary">
        <li class="fa fa-plus"></li> {{__('Add unit')}}
    </button>
    <hr>
</div>
