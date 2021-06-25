<div class="row">

<div class="col-xs-12">
@if(authIsSuperAdmin())
            <div class="col-md-12">
                <div class="form-group has-feedback">
                    <label for="inputPhone" class="control-label">{{__('Branch')}}</label>
                    <div class="input-group">
                        <span class="input-group-addon fa fa-file"></span>
                        <select class="form-control js-example-basic-single" name="branch_id">
                            <option value="">{{__('Select Branches')}}</option>
                            @foreach($branches as $k => $v)
                                <option value="{{$k}}" {{isset($customers_category) && $customers_category->branch_id == $k? 'selected':''}}>{{$v}}</option>
                            @endforeach
                        </select>
                    </div>
                    {{input_error($errors,'branch_id')}}
                </div>
            </div>
        @endif
</div>

    <div class="col-md-12">

    

        <div class="col-md-4">
            <div class="form-group">
                <label for="inputNameAr" class="control-label">{{__('Customer Category Ar')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-user"></li></span>
                    <input type="text" name="name_ar" class="form-control" id="inputNameEn"
                           placeholder="{{__('Customer Category Ar')}}"
                           value="{{old('name_ar', isset($customers_category)? $customers_category->name_ar:'')}}">
                </div>
                {{input_error($errors,'name_ar')}}
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="inputName" class="control-label">{{__('Customer Category En')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-user"></li></span>
                    <input type="text" name="name_en" class="form-control" id="inputNameEn"
                           placeholder="{{__('Customer Category En')}}"
                           value="{{old('name_en', isset($customers_category)? $customers_category->name_en:'')}}">
                </div>
                {{input_error($errors,'name_en')}}
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="inputType" class="control-label">{{__('Sales discount type')}}</label>
                <div class="input-group">
                    <span class="input-group-addon fa fa-file"></span>
                    <select class="form-control js-example-basic-single" name="sales_discount_type">
                        <option value="">{{__('Select Type')}}</option>
                        <option value="amount" {{isset($customers_category) && $customers_category->sales_discount_type == 'amount'? 'selected':''}}>
                            {{__('Amount')}}
                        </option>
                        <option value="percent" {{isset($customers_category) && $customers_category->sales_discount_type == 'percent'? 'selected':''}}>
                            {{__('Percent')}}
                        </option>
                    </select>
                </div>
                {{input_error($errors,'sales_discount_type')}}
            </div>
        </div>        

    </div>


    <div class="col-md-12">


 


        <div class="col-md-4">

            <div class="form-group">
                <label for="inputQuantity" class="control-label">{{__('Sales discount')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-money"></li></span>
                    <input type="text" name="sales_discount" class="form-control"
                           placeholder="{{__('Sales discount')}}"
                           value="{{old('sales_discount', isset($customers_category)? $customers_category->sales_discount :'')}}">
                </div>
                {{input_error($errors,'sales_discount')}}
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="inputType" class="control-label">{{__('Service discount type')}}</label>
                <div class="input-group">
                    <span class="input-group-addon fa fa-file"></span>
                    <select class="form-control js-example-basic-single" name="services_discount_type">
                        <option value="">{{__('Select Type')}}</option>
                        <option value="amount" {{isset($customers_category) && $customers_category->services_discount_type == 'amount'? 'selected':''}}>
                            {{__('Amount')}}
                        </option>
                        <option value="percent" {{isset($customers_category) && $customers_category->services_discount_type == 'percent'? 'selected':''}}>
                            {{__('Percent')}}
                        </option>
                    </select>
                </div>
                {{input_error($errors,'services_discount_type')}}
            </div>
        </div>
        <div class="col-md-4">

            <div class="form-group">
                <label for="inputQuantity" class="control-label">{{__('Service discount')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-money"></li></span>
                    <input type="text" name="services_discount" class="form-control"
                           placeholder="{{__('Service discount')}}"
                           value="{{old('services_discount', isset($customers_category)? $customers_category->services_discount :'')}}">
                </div>
                {{input_error($errors,'services_discount')}}
            </div>
        </div>

    </div>


    

</div>


<div class="form-group  col-sm-12">
    <label for="inputDescription" class="control-label">{{__('Description')}}</label>
    <div class="input-group">
        <textarea name="description" class="form-control" rows="4" cols="150"
        >{{old('description', isset($customers_category)? $customers_category->description :'')}}</textarea>
    </div>
    {{input_error($errors,'description')}}
</div>

<div class="col-md-12">



        <div class="col-md-12">
            <div class="form-group has-feedback">
                <label for="inputPhone" class="control-label">{{__('Status')}}</label>
                <div class="switch primary">
                    <input type="checkbox" id="switch-1" name="status"{{!isset($customers_category)?'checked':''}}
                            {{isset($customers_category) && $customers_category->status? 'checked':''}}
                    >
                    <label for="switch-1">{{__('Active')}}</label>
                </div>
            </div>
        </div>

    </div>
<div class="form-group col-sm-12">
    @include('admin.buttons._save_buttons')
</div>
