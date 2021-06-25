<div class="col-md-12">
    <hr>
    <span style="color: #000;font-size: 17px;"> {{__('Follow up')}} </span>
    <hr>
</div>


<div class="container">
    <div class="follow-up-form">

        <input type="hidden" value="{{$workCard->followUps->count() ? $workCard->followUps->count() -1 : 0}}"
               name="div_count" id="div-count">

        @if($workCard->followUps->count() != 0)
            @foreach($workCard->followUps as $index=>$follow_up)

                <div class="row price-{{$index}}">

                    <input type="hidden" value="{{$index}}" name="followUpsItems[]">

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="exampleInputEmail1">{{__('Name')}}</label>
                            <span class="asterisk"> * </span>
                            <input type="text" name="name_{{$index}}"
                                   value="{{ old('name_'.$index, $follow_up->name ) }}" required
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
                                   value="{{ old('kilo_number_'.$index , $follow_up->kilo_number) }}" required
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
                                   value="{{ old('date_'.$index, $follow_up->date) }}" required
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

                            <textarea name="notes_{{$index}}"
                                      class="form-control {{ $errors->has('notes_'.$index) ? 'is-invalid' : '' }}"
                                      style=" height: 45px;">{{ old('notes_'.$index, $follow_up->notes) }}</textarea>

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
                            <input type="checkbox" name="status_{{$index}}" class="form-control"
                            {{$follow_up->status == 1 ? 'checked':''}}>

                        </div>
                    </div>

                    @if($index != 0)
                        <div class="col-md-2">
                            <div class="form-group">
                                <button class="btn btn-sm btn-danger" type="button"
                                        onclick="deleteDivPrice({{$index}})"
                                        id="delete-div-{{$index}}" style="margin-top: 40px;">
                                    <li class="fa fa-trash"></li>
                                </button>
                            </div>
                        </div>
                    @endif

                </div>
            @endforeach


        @else
            <input type="hidden" value="0" name="followUpsItems[]">

            <div class="row price-0">

                <div class="col-md-2">
                    <div class="form-group">
                        <label for="exampleInputEmail1">{{__('Name')}}</label>
                        <span class="asterisk"> * </span>
                        <input type="text" name="name_0"
                               value="{{ old('name_0') }}" required
                               class="form-control {{ $errors->has('name_0') ? 'is-invalid' : '' }}">

                        @if ($errors->has('name_0'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('name_0') }}</strong>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label for="exampleInputEmail1">{{__('Kilos numbers')}} :</label>
                        <span class="asterisk"> * </span>
                        <input type="number" min="0" name="kilo_number_0" id="kilo_number_0"
                               value="{{ old('kilo_number_0') }}" required
                               class="form-control {{ $errors->has('kilo_number_0') ? 'is-invalid' : '' }}">

                        @if ($errors->has('kilo_number_0'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('kilo_number_0') }}</strong>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label for="exampleInputPassword1"> {{__('Date')}} : </label>
                        <span class="asterisk"> * </span>
                        <input type="date" min="0" name="date_0"
                               value="{{ old('date_0') }}" required
                               class="form-control {{ $errors->has('date_0') ? 'is-invalid' : '' }}">

                        @if ($errors->has('date_0'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('date_0') }}</strong>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label for="exampleInputPassword1"> {{__('Notes')}} : </label>
                        <span class="asterisk"> * </span>

                        <textarea name="notes_0"
                                  class="form-control {{ $errors->has('notes_0') ? 'is-invalid' : '' }}"
                                  style=" height: 45px;">{{ old('notes_0') }}</textarea>

                        @if ($errors->has('notes_0'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('notes_0') }}</strong>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label for="exampleInputPassword1"> {{__('Status')}} : </label>
                        <span class="asterisk"> * </span>
                        <input type="checkbox" name="status_0"
                               class="form-control">

                    </div>
                </div>

            </div>
        @endif
    </div>


    <div>
        <button type="button" title="new price" onclick="addPrice()"
                class="btn btn-sm btn-primary">
            <li class="fa fa-plus"></li>
        </button>
    </div>
</div>


<div class="col-md-12">
    <hr>
</div>
