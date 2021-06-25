<div class="row">

    @if(authIsSuperAdmin())

        <div class="col-md-12">
            <div class="col-md-12">
                <div class="form-group has-feedback">
                    <label for="inputStore" class="control-label">{{__('Branches')}}</label>
                    <div class="input-group">
                        <span class="input-group-addon fa fa-file"></span>
                        <select class="form-control js-example-basic-single" name="branch_id" id="branch_id">
                            <option value="">{{__('Select Branch')}}</option>

                            @foreach($branches as $branch)
                                <option
                                    value="{{$branch->id}}" {{isset($concessionType) && $concessionType->branch_id == $branch->id? 'selected':''}}>
                                    {{$branch->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    {{input_error($errors,'branch_id')}}
                </div>

            </div>

        </div>
    @endif

    <div class="col-md-12">

        <div class="col-md-4">
            <div class="form-group">
                <label for="inputNameAr" class="control-label">{{__('Name Ar')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-file-o"></li></span>
                    <input type="text" name="name_ar" class="form-control" id="inputNameEn"
                           placeholder="{{__('NameAr')}}"
                           value="{{old('name_ar', isset($concessionType)? $concessionType->name_ar:'')}}">
                </div>
                {{input_error($errors,'name_ar')}}
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="inputName" class="control-label">{{__('Name En')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-file-o"></li></span>
                    <input type="text" name="name_en" class="form-control" id="inputNameEn"
                           placeholder="{{__('NameEn')}}"
                           value="{{old('name_en', isset($concessionType)? $concessionType->name_en:'')}}">
                </div>
                {{input_error($errors,'name_en')}}
            </div>
        </div>



        <div class="col-md-4" >
            <div class="form-group has-feedback">
                <label for="inputStore" class="control-label">{{__('Concession Item')}}</label>
                <div class="input-group" id="concession_items_data">
                    <span class="input-group-addon fa fa-bell"></span>
                    <select class="form-control js-example-basic-single" name="concession_type_item_id">

                        <option value="">{{__('Select Item')}}</option>

                        @foreach($concessionItems as $item)
                            <option value="{{$item->id}}"
                                {{isset($concessionType) && $concessionType->concession_type_item_id == $item->id? 'selected':''}}>
                                {{__($item->name)}}
                            </option>
                        @endforeach
                    </select>
                </div>
                {{input_error($errors,'concession_type_item_id')}}
            </div>
        </div>

        <div class="col-md-12">

        <div class="radio primary col-md-2" style="margin-top: 37px;">
            <input type="radio" name="type" value="add" id="add" onclick="showSelectedTypes('add')"
                {{ !isset($concessionType) ? 'checked':'' }}
                {{isset($concessionType) && $concessionType->type == 'add' ? 'checked':''}} >
            <label for="add">{{__('Add Concession')}}</label>
        </div>

        <div class="radio primary col-md-2" style="margin-top: 37px;">
            <input type="radio" name="type" id="withdrawal" value="withdrawal" onclick="showSelectedTypes('withdrawal')"
                {{isset($concessionType) && $concessionType->type == 'withdrawal' ? 'checked':''}} >
            <label for="withdrawal">{{__('Withdrawal Concession')}}</label>
        </div>
        



        <div class="col-md-8">
          
                <label for="inputDescription" class="control-label">{{__('Description')}}</label>
                <div class="input-group">
                <textarea name="description" class="form-control" rows="4" cols="150"
                >{{old('description', isset($concessionType)? $concessionType->description :'')}}</textarea>
                </div>
                {{input_error($errors,'description')}}
        
        </div>
    </div>

</div>
</div>
