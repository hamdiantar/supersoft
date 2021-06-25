<div class="row price-{{$index}}">

    <input type="hidden" value="{{$index}}" name="followUpsItems[]" >

    <div class="col-md-2">
        <div class="form-group">
            <label for="exampleInputEmail1">{{__('Name')}}</label>
            <span class="asterisk"> * </span>
            <input type="text" name="name_{{$index}}"
                   value="{{ old('name_'.$index) }}" required
                   class="form-control {{ $errors->has('name_'.$index) ? 'is-invalid' : '' }}">

            @if ($errors->has('name_'.$index))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('name_'.$index) }}</strong>
                </div>
            @endif
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <label for="exampleInputEmail1">{{__('Kilos numbers')}} :</label>
            <span class="asterisk"> * </span>
            <input type="number" min="0" name="kilo_number_{{$index}}" id="kilo_number_{{$index}}"
                   value="{{ old('kilo_number_'.$index) }}" required
                   class="form-control {{ $errors->has('kilo_number_'.$index) ? 'is-invalid' : '' }}">

            @if ($errors->has('kilo_number_'.$index))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('kilo_number_'.$index) }}</strong>
                </div>
            @endif
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <label for="exampleInputPassword1"> {{__('Date')}} : </label>
            <span class="asterisk"> * </span>
            <input type="date" min="0" name="date_{{$index}}"
                   value="{{ old('date_'.$index) }}" required
                   class="form-control {{ $errors->has('date_'.$index) ? 'is-invalid' : '' }}">

            @if ($errors->has('date_'.$index))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('date_'.$index) }}</strong>
                </div>
            @endif
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <label for="exampleInputPassword1"> {{__('Notes')}} : </label>
            <span class="asterisk"> * </span>

            <textarea name="notes_{{$index}}"   class="form-control {{ $errors->has('notes_'.$index) ? 'is-invalid' : '' }}"
                      style=" height: 45px;" >{{ old('notes_'.$index) }}</textarea>

            @if ($errors->has('notes_'.$index))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('notes_'.$index) }}</strong>
                </div>
            @endif
        </div>
    </div>

    <div class="col-md-1">
        <div class="form-group">
            <label for="exampleInputPassword1"> {{__('Status')}} : </label>
            <span class="asterisk"> * </span>
            <input type="checkbox"  name="status_{{$index}}" class="form-control">

        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <button class="btn btn-sm btn-danger" type="button"
                    onclick="deleteDivPrice({{$index}})"
                    id="delete-div-{{$index}}" style="margin-top: 40px;">
                <li class="fa fa-trash"></li>
            </button>
        </div>
    </div>

</div>