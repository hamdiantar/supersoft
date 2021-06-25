<div class="row">


    <div class="col-md-12">

        <div class="col-xs-12">

            @if(authIsSuperAdmin())
                <div class="form-group has-feedback">
                    <label for="inputPhone" class="control-label">{{__('Branch')}}</label>
                    <div class="input-group">
                        <span class="input-group-addon fa fa-file"></span>
                        <select class="form-control js-example-basic-single" name="branch_id">
                            <option value="">{{__('Select Branch')}}</option>
                            @foreach($branches as $k => $v)
                                <option
                                    value="{{$k}}" {{isset($pointsRule) && $pointsRule->branch_id == $k? 'selected':''}}>
                                    {{$v}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    {{input_error($errors,'branch_id')}}
                </div>
            @endif
        </div>
    </div>

    <div class="col-md-12">

        <div class="col-md-6">
            <div class="form-group">
                <label for="inputName" class="control-label">{{__('Text Arabic')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-file"></li></span>
                    <input type="text" name="text_ar" class="form-control" id="inputName"
                           placeholder="{{__('Text Arabic')}}"
                           value="{{old('text_ar', isset($pointsRule)? $pointsRule->text_ar:'')}}">
                </div>
                {{input_error($errors,'text_ar')}}
            </div>
        </div>


        <div class="col-md-6">
            <div class="form-group">
                <label for="inputName" class="control-label">{{__('Text English')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-file"></li></span>
                    <input type="text" name="text_en" class="form-control" id="inputName"
                           placeholder="{{__('Text English')}}"
                           value="{{old('text_en', isset($pointsRule)? $pointsRule->text_en:'')}}">
                </div>
                {{input_error($errors,'text_en')}}
            </div>
        </div>

    </div>


    <div class="col-md-12">

        <div class="col-md-6">
            <div class="form-group has-feedback">
                <label for="inputEmail" class="control-label">{{__('Points')}}</label>
                <div class="input-group">
                    <span class="input-group-addon fa fa-mail-forward"></span>
                    <input type="number" name="points" class="form-control" id="inputEmail"
                           placeholder="{{__('Points')}}"
                           value="{{old('points', isset($pointsRule)? $pointsRule->points:'')}}">
                </div>
                {{input_error($errors,'points')}}
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="inputName" class="control-label">{{__('Amount money')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-usd"></li></span>
                    <input type="text" name="amount" class="form-control" id="inputName"
                           placeholder="{{__('Amount money')}}"
                           value="{{old('amount', isset($pointsRule)? $pointsRule->amount:'')}}">
                </div>
                {{input_error($errors,'amount')}}
            </div>
        </div>

    </div>


    <div class="col-md-12">

        <div class="col-md-4">
            <div class="form-group has-feedback">
                <label for="inputPhone" class="control-label">{{__('Status')}}</label>
                <div class="switch primary">
                    <input type="checkbox" id="switch-1" name="status" {{!isset($pointsRule)?'checked':''}}
                        {{isset($pointsRule) && $pointsRule->status? 'checked':''}}
                    >
                    <label for="switch-1">{{__('Active')}}</label>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">

        <div class="col-md-6">
            <div class="form-group">
                @include('admin.buttons._save_buttons')
            </div>
        </div>

        <div class="col-md-6">

            {{--<div class="form-group">--}}
            {{--    <button type="submit" class="btn btn-primary waves-effect waves-light">{{__('Save')}}</button>--}}
            {{--</div>--}}

        </div>

    </div>

</div>
