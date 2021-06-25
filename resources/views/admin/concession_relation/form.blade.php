<div class="row">

    <div class="col-md-12">

        <div class="radio primary col-md-1" style="margin-top: 37px;">
            <input type="radio" name="type" value="add"
                   {{isset($type) && $type->type == 'add'? 'checked':'' }}
                   id="add" checked onchange="showSelectedType()">
            <label for="add">{{__('Add')}}</label>
        </div>

        <div class="radio primary col-md-11" style="margin-top: 37px;">
            <input type="radio" name="type" id="withdrawal"
                   {{isset($type) && $type->type == 'withdrawal'? 'checked':'' }}
                   value="withdrawal" onchange="showSelectedType()">
            <label for="withdrawal">{{__('Withdrawal')}}</label>
        </div>

        <div class="col-md-6" id="concession_type_add" style="{{isset($type) && $type->type == 'add'? '':'display:none'}}" >
            <div class="form-group has-feedback">
                <label for="inputStore" class="control-label">{{__('Concession Types')}}</label>
                <div class="input-group">
                    <span class="input-group-addon fa fa-file"></span>
                    <select class="form-control js-example-basic-single" name="concession_type_id" id="select_type_add">

                        <option value="">{{__('Select Type')}}</option>

                        @foreach($types as $item)
                            @if($item->type == 'add')
                                <option value="{{$item->id}}" {{isset($type) && $type->id ==  $item->id? 'selected':''}}>
                                    {{$item->name}}
                                </option>
                            @endif
                        @endforeach

                    </select>
                </div>
                {{input_error($errors,'concession_type_id')}}
            </div>
        </div>

        <div class="col-md-6" id="concession_type_withdrawal"
             style="{{isset($type) && $type->type == 'withdrawal'? '':'display:none'}}" >
            <div class="form-group has-feedback">
                <label for="inputStore" class="control-label">{{__('Concession Types')}}</label>
                <div class="input-group">
                    <span class="input-group-addon fa fa-file"></span>
                    <select class="form-control js-example-basic-single" name="concession_type_id" disabled id="select_type_withdrawal">

                        <option value="">{{__('Select Type')}}</option>

                        @foreach($types as $item)
                            @if($item->type == 'withdrawal')
                                <option value="{{$item->id}}" {{isset($type) && $type->id == $item->id? 'selected':''}}>
                                    {{$item->name}}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>
                {{input_error($errors,'concession_type_id')}}
            </div>
        </div>

        <div class="col-md-6" id="concession_item_add" style="{{isset($type) && $type->type == 'add'? '':'display:none'}}">
            <div class="form-group has-feedback">
                <label for="inputStore" class="control-label">{{__('Concession Item')}}</label>
                <div class="input-group">
                    <span class="input-group-addon fa fa-file"></span>
                    <select class="form-control js-example-basic-single" name="concession_item_id" id="select_item_add">

                        <option value="">{{__('Select Item')}}</option>

                        @foreach($concessionItems as $item)
                            @if($item->type == 'add')
                                <option value="{{$item->id}}" {{isset($type) && $type->concession_type_item_id == $item->id? 'selected':''}}>
                                    {{$item->name}}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>
                {{input_error($errors,'concession_item_id')}}
            </div>
        </div>

        <div class="col-md-6" id="concession_item_withdrawal" style="{{isset($type) && $type->type == 'withdrawal'? '':'display:none'}}">
            <div class="form-group has-feedback">
                <label for="inputStore" class="control-label">{{__('Concession Types')}}</label>
                <div class="input-group">
                    <span class="input-group-addon fa fa-file"></span>
                    <select class="form-control js-example-basic-single" name="concession_item_id" id="select_item_withdrawal" disabled>

                        <option value="">{{__('Select Item')}}</option>

                        @foreach($concessionItems as $item)
                            @if($item->type == 'withdrawal')
                                <option value="{{$item->id}}" {{isset($type) && $type->concession_type_item_id == $item->id? 'selected':''}}>
                                    {{$item->name}}
                                </option>
                            @endif
                        @endforeach

                    </select>
                </div>
                {{input_error($errors,'concession_item_id')}}
            </div>
        </div>

    </div>
</div>
