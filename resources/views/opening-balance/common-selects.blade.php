<div class="row">
    <div class="form-group col-md-4">
        <label> {{ __('opening-balance.main-type') }} </label>
        <div class="input-group">
        <span class="input-group-addon fa fa-cubes"></span>
        <select id="main-type-selector" class="form-control select2">
            {!! mainPartTypeSelectAsTree() !!}
        </select>
    </div>
    </div>

    <div class="form-group col-md-4">
        <label> {{ __('opening-balance.sub-type') }} </label>
        <div class="input-group">
        <span class="input-group-addon fa fa-cube"></span>
        <select id="sub-type-selector" class="form-control select2">
            {!! subPartTypeSelectAsTree() !!}
        </select>
    </div>
    </div>

    <div class="form-group col-md-4">
        <label> {{ __('opening-balance.part') }} </label>
        <div class="input-group">
        <span class="input-group-addon fa fa-cube"></span>
        <select id="part-selector" class="form-control select2">
            <option value=""> {{ __('opening-balance.select-one') }} </option>
            @foreach($parts as $part)
                <option value="{{ $part->id }}"> {{ $part->prices->first() ? $part->prices->first()->barcode : ''}} - {{ $part->name}} </option>
            @endforeach
        </select>
    </div>
    </div>
</div>
