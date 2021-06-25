<div class="row">

<div class="col-xs-12">
<div class="col-md-12">
            @if(authIsSuperAdmin())
                <div class="form-group has-feedback">
                    <label for="inputPhone" class="control-label">{{__('Branch')}}</label>
                    <div class="input-group">
                        <span class="input-group-addon fa fa-file"></span>
                        <select class="form-control js-example-basic-single" name="branch_id">
                            <option value="">{{__('Select Branches')}}</option>
                            @foreach($branches as $k => $v)
                                <option value="{{$k}}" {{isset($suppliers_group) && $suppliers_group->branch_id == $k? 'selected':''}}>{{$v}}</option>
                            @endforeach
                        </select>
                    </div>
                    {{input_error($errors,'branch_id')}}
                </div>
            @endif
        </div>
</div>

    <div class="col-md-12">

        <div class="col-md-4">
            <div class="form-group">
                <label for="inputNameAr" class="control-label">{{__('Supplier Group Name Ar')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-user"></li></span>
                    <input type="text" name="name_ar" class="form-control" id="inputNameEn"
                           placeholder="{{__('Supplier Group Name Ar')}}"
                           value="{{old('name_ar', isset($suppliers_group)? $suppliers_group->name_ar:'')}}">
                </div>
                {{input_error($errors,'name_ar')}}
            </div>
        </div>

        <div class="col-md-4">

            <div class="form-group">
                <label for="inputName" class="control-label">{{__('Supplier Group Name En')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-user"></li></span>
                    <input type="text" name="name_en" class="form-control" id="inputNameEn"
                           placeholder="{{__('Supplier Group Name En')}}"
                           value="{{old('name_en', isset($suppliers_group)? $suppliers_group->name_en:'')}}">
                </div>
                {{input_error($errors,'name_en')}}
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="inputType" class="control-label">{{__('Discount Type')}}</label>
                <div class="input-group">
                    <span class="input-group-addon fa fa-file"></span>
                    <select class="form-control js-example-basic-single" name="discount_type">
                        <option value="">{{__('Select Discount Type')}}</option>
                        <option value="amount" {{isset($suppliers_group) && $suppliers_group->discount_type == 'amount'? 'selected':''}}>
                            {{__('Amount')}}
                        </option>
                        <option value="percent" {{isset($suppliers_group) && $suppliers_group->discount_type == 'percent'? 'selected':''}}>
                            {{__('Percent')}}
                        </option>
                    </select>
                </div>
                {{input_error($errors,'discount_type')}}
            </div>
        </div>        

    </div>

    <div class="col-md-12">



        <div class="col-md-4">

            <div class="form-group">
                <label for="inputQuantity" class="control-label">{{__('Discount')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-money"></li></span>
                    <input type="text" name="discount" class="form-control"
                           placeholder="{{__('discount')}}"
                           value="{{old('discount', isset($suppliers_group)? $suppliers_group->discount :'')}}">
                </div>
                {{input_error($errors,'discount')}}
            </div>
        </div>

        <div class="col-md-4">

            <div class="form-group has-feedback">
                <label for="inputPhone" class="control-label">{{__('Status')}}</label>
                <div class="switch primary">
                    <input type="checkbox" id="switch-1" name="status"{{!isset($suppliers_group)?'checked':''}}
                            {{isset($suppliers_group) && $suppliers_group->status? 'checked':''}}
                    >
                    <label for="switch-1">{{__('Active')}}</label>
                </div>
            </div>
        </div>


    </div>

</div>


<div class="col-md-12">
    <div class="form-group">
        <label for="inputDescription" class="control-label">{{__('Description')}}</label>
        <div class="input-group">
        <textarea name="description" class="form-control" rows="4" cols="150"
        >{{old('description', isset($suppliers_group)? $suppliers_group->description :'')}}</textarea>
        </div>
        {{input_error($errors,'description')}}
    </div>
</div>



<div class="col-md-12">
    <div class="form-group">
        @include('admin.buttons._save_buttons')
    </div>
</div>









