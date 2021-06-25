<div class="row">

    <div class="col-xs-12">
        <div class="col-md-12">

            @if(authIsSuperAdmin())
                <div class="form-group has-feedback">
                    <label for="inputPhone" class="control-label">{{__('Branch')}}</label>
                    <div class="input-group">
                        <span class="input-group-addon fa fa-file"></span>
                        <select class="form-control js-example-basic-single" name="branch_id" id="branch_id"
                                onchange="getGroupsByBranch()">
                            <option value="">{{__('Select Branches')}}</option>
                            @foreach($branches as $k => $v)
                                <option
                                    value="{{$k}}" {{isset($supplier) && $supplier->branch_id == $k? 'selected':''}}>{{$v}}</option>
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
                <label for="inputNameAr" class="control-label">{{__('Supplier Name Ar')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-user"></li></span>
                    <input type="text" name="name_ar" class="form-control" id="inputNameEn"
                           placeholder="{{__('Supplier Name Ar')}}"
                           value="{{old('name_ar', isset($supplier)? $supplier->name_ar:'')}}">
                </div>
                {{input_error($errors,'name_ar')}}
            </div>

        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="inputName" class="control-label">{{__('Supplier Name En')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-user"></li></span>
                    <input type="text" name="name_en" class="form-control" id="inputNameEn"
                           placeholder="{{__('Supplier Name En')}}"
                           value="{{old('name_en', isset($supplier)? $supplier->name_en:'')}}">
                </div>
                {{input_error($errors,'name_en')}}
            </div>
        </div>


        <div class="col-md-6">
            <div class="form-group has-feedback">
                <label for="group_id" class="control-label">{{__('Main Supplier Group')}}</label>
                <div class="input-group">
                    <span class="input-group-addon fa fa-file"></span>

                    <select name="group_id" id="main_group_id" onchange="getSubGroups()">

                        <option value="">{{__('Select')}}</option>

                        @foreach($mainGroups as $index=>$mainGroup)
                            <option value="{{$mainGroup->id}}" data-order="{{$index+1}}"
                                {{isset($supplier) && $supplier->group_id == $mainGroup->id ? 'selected':''}}>
                                {{$index+1}} . {{$mainGroup->name}}
                            </option>
                        @endforeach
                    </select>
                </div>
                {{input_error($errors,'group_id')}}
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group has-feedback">
                <label for="group_id" class="control-label">{{__('Sub Supplier Group')}}</label>
                <div class="input-group" id="sub_groups">
                    <span class="input-group-addon fa fa-file"></span>
                    <select name="sub_group_id" id="sub_group_id" class="form-control js-example-basic-single">

                        <option value="">{{__('Select')}}</option>

                        @foreach($subGroups as $key=>$value)
                            <option value="{{$key}}" {{isset($supplier) && $supplier->sub_group_id == $key ? 'selected':'' }}>
                                {{$value}}
                            </option>
                        @endforeach
                    </select>
                </div>
                {{input_error($errors,'sub_group_id')}}
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="col-md-4">
            <div class="form-group has-feedback">
                <label for="country" class="control-label">{{__('Select Country')}}</label>
                <div class="input-group">
                    <span class="input-group-addon fa fa-globe"></span>
                    <select name="country_id" class="form-control   js-example-basic-single" id="country">
                        <option value="">{{__('Select Country')}}</option>
                        @foreach(\App\Models\Country::all() as $country)
                            <option
                                value="{{$country->id}}" {{isset($supplier) && $supplier->country_id == $country->id? 'selected':''}}>
                                {{$country->name}}
                            </option>
                        @endforeach
                    </select>
                    {{input_error($errors,'country_id')}}
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group has-feedback">
                <label for="city" class="control-label">{{__('Select City')}}</label>
                <div class="input-group">
                    <span class="input-group-addon fa fa-globe"></span>
                    <select name="city_id" class="form-control  js-example-basic-single" id="city">
                        @foreach(\App\Models\City::all() as $city)
                            <option
                                value="{{$city->id}}" {{isset($supplier) && $supplier->city_id == $city->id? 'selected':''}}>
                                {{$city->name}}
                            </option>
                        @endforeach
                    </select>
                    {{input_error($errors,'city_id')}}
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group has-feedback">
                <label for="area" class="control-label">{{__('Select Area')}}</label>
                <div class="input-group">
                    <span class="input-group-addon fa fa-globe"></span>
                    <select name="area_id" class="form-control  js-example-basic-single select2" id="area">
                        @foreach(\App\Models\Area::all() as $area)
                            <option
                                value="{{$area->id}}" {{isset($supplier) && $supplier->area_id == $area->id? 'selected':''}}>
                                {{$area->name}}
                            </option>
                        @endforeach
                    </select>
                    {{input_error($errors,'area_id')}}
                </div>
            </div>
        </div>
    </div>


    <div class="col-md-12">
        <div class="col-md-4">
            <div class="form-group">
                <label for="inputNameAr" class="control-label">{{__('Email')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-envelope"></li></span>
                    <input type="email" name="email" class="form-control" id="inputNameEn"
                           placeholder="{{__('Email')}}"
                           value="{{old('email', isset($supplier)? $supplier->email:'')}}">
                </div>
                {{input_error($errors,'email')}}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="inputNameAr" class="control-label">{{__('Phone 1')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-phone"></li></span>
                    <input type="text" name="phone_1" class="form-control" id="phone_1"
                           placeholder="{{__('Phone 1')}}"
                           value="{{old('phone_1', isset($supplier)? $supplier->phone_1:'')}}">
                </div>
                {{input_error($errors,'phone_1')}}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="inputNameAr" class="control-label">{{__('Phone 2')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-phone"></li></span>
                    <input type="text" name="phone_2" class="form-control" id="phone_2"
                           placeholder="{{__('Phone 2')}}"
                           value="{{old('phone_2', isset($supplier)? $supplier->phone_2:'')}}">
                </div>
                {{input_error($errors,'phone_2')}}
            </div>
        </div>
    </div>


    <div class="col-xs-12">
        <div class="col-md-4">
            <div class="col-md-2" style="width: 100px">
                <div class="form-group has-feedback">
                    <label for="inputPhone" class="control-label">{{__('Supplier Type')}}</label>
                    <div class="radio primary">
                        <input type="radio" id="switch-3434" name="type"
                               value="person" {{!isset($supplier)?'checked':''}}
                               {{isset($supplier) && $supplier->type === 'person' ? 'checked':''}}
                               onclick="getCompanyData('person')">
                        <label for="switch-3434">{{__('Person')}}</label>
                    </div>
                </div>
            </div>
            <div class="col-md-2" style="width: 100px">
                <div class="form-group has-feedback">
                    <label for="inputPhone" class="control-label">{{__('Supplier Type')}}</label>
                    <div class="radio primary">
                        <input type="radio" id="switch-34343434" name="type"
                               value="company"
                               {{isset($supplier) && $supplier->type === 'company'? 'checked':''}}
                               onclick="getCompanyData('company')">
                        <label for="switch-34343434">{{__('Company')}}</label>
                    </div>
                    {{input_error($errors,'type')}}
                </div>
            </div>
        </div>
        <div class="form-group company_data col-md-4"
             style="display: {{isset($supplier) && $supplier->type == 'company'? '':'none' }} ;">
            <label for="inputNameAr" class="control-label">{{__('Fax Number')}}</label>
            <div class="input-group">
                <span class="input-group-addon"><li class="fa fa-envelope"></li></span>
                <input type="text" name="fax" class="form-control" id="phone_2"
                       placeholder="{{__('Fax')}}" value="{{old('fax', isset($supplier)? $supplier->fax:'')}}">
            </div>
            {{input_error($errors,'fax')}}
        </div>
        <div class="form-group company_data col-md-4"
             style="display: {{isset($supplier) && $supplier->type == 'company'? '':'none' }};">
            <label for="inputNameAr" class="control-label">{{__('Commercial Number')}}</label>
            <div class="input-group">
                <span class="input-group-addon"><li class="fa fa-cart-arrow-down"></li></span>
                <input type="text" name="commercial_number" class="form-control" id="commercial_number"
                       placeholder="{{__('Commercial Number')}}"
                       value="{{old('commercial_number', isset($supplier)? $supplier->commercial_number:'')}}">
            </div>
            {{input_error($errors,'commercial_number')}}
        </div>
    </div>

    <div class="col-xs-12">
        <div class="col-md-4">
            <div class="form-group  ">
                <label for="address" class="control-label">{{__('Address')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-cart-arrow-down"></li></span>
                    <input type="text" name="address" class="form-control" id="address"
                           placeholder="{{__('Address')}}"
                           value="{{old('address', isset($supplier)? $supplier->address:'')}}">
                </div>
                {{input_error($errors,'address')}}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group  ">
                <label for="address" class="control-label">{{__('Tax Number')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-cart-arrow-down"></li></span>
                    <input type="text" name="tax_number" class="form-control" id="tax_number"
                           placeholder="{{__('Tax Number')}}"
                           value="{{old('tax_number', isset($supplier)? $supplier->tax_number:'')}}">
                </div>
                {{input_error($errors,'tax_number')}}
            </div>
        </div>
        <div class="col-md-4">
            <label for="address" class="control-label" style="visibility: hidden;">
                {{__('Location')}}
            </label><br>
            <a data-toggle="modal" data-target="#boostrapModal-2" title="Cars info"
               class="btn btn-primary"
               style="margin-top:1px;cursor:pointer;font-size:12px;padding:3px 15px">
                <i class="fa fa-plus"> </i> {{__('Location')}}
            </a>
        </div>
    </div>

    <div class="col-xs-12">
        <div class="col-md-4">
            <div class="form-group  ">
                <label for="address" class="control-label">{{__('Lat')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-map"></li></span>
                    <input type="text" name="lat" class="form-control" id="lat"
                           placeholder="{{__('Lat')}}" value="{{old('lat', isset($supplier)? $supplier->lat:'')}}">
                </div>
                {{input_error($errors,'lat')}}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group  ">
                <label for="address" class="control-label">{{__('Long')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-map"></li></span>
                    <input type="text" name="long" class="form-control" id="lng"
                           placeholder="{{__('Long')}}" value="{{old('long', isset($supplier)? $supplier->long:'')}}">
                </div>
                {{input_error($errors,'long')}}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group  ">
                <label for="address" class="control-label">{{__('Maximum Supplier Funds')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-usd"></li></span>
                    <input type="text" name="maximum_fund_on" class="form-control" id="maximum_fund_on"
                           placeholder="{{__('Cost')}}"
                           value="{{old('maximum_fund_on', isset($supplier)? $supplier->maximum_fund_on: 0)}}">
                </div>
                {{input_error($errors,'maximum_fund_on')}}
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="col-md-3">
            <div class="form-group has-feedback">
                <label for="inputPhone" class="control-label">{{__('Status')}}</label>
                <div class="switch primary">
                    <input type="checkbox" id="switch-1" name="status"{{!isset($supplier)?'checked':''}}
                        {{isset($supplier) && $supplier->status? 'checked':''}}
                    >
                    <label for="switch-1">{{__('Active')}}</label>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group has-feedback">
                <label for="inputPhone" class="control-label">{{__('Supplier Type')}}</label>
                <div class="radio primary">
                    <input type="radio" id="switch-2" name="supplier_type"
                           value="supplier" {{!isset($supplier)?'checked':''}}
                        {{isset($supplier) && $supplier->supplier_type === __('supplier')? 'checked':''}}
                    >
                    <label for="switch-2">{{__('Supplier')}}</label>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group has-feedback">
                <label for="inputPhone" class="control-label">{{__('Supplier Type')}}</label>
                <div class="radio primary">
                    <input type="radio" id="switch-3" name="supplier_type"
                           value="contractor" {{!isset($supplier)?'checked':''}}
                        {{isset($supplier) && $supplier->supplier_type === __('contractor')? 'checked':''}}
                    >
                    <label for="switch-3">{{__('contractor')}}</label>
                </div>
                {{input_error($errors,'supplier_type')}}
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group has-feedback">
                <label for="inputPhone" class="control-label">{{__('Supplier Type')}}</label>
                <div class="radio primary">
                    <input type="radio" id="switch-4" name="supplier_type"
                           value="both_together" {{!isset($supplier)?'checked':''}}
                        {{isset($supplier) && $supplier->supplier_type === __('both_together')? 'checked':''}}
                    >
                    <label for="switch-4">{{__('Both Together')}}</label>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="col-md-12">
    @if (isset($supplier))
        @php
            $balance_details = $supplier->get_my_balance();
        @endphp
        <div class="col-md-12">
            <div class="form-group">
                <label> {{ $balance_details['balance_title'] }} </label>
                <input disabled value="{{ $balance_details['balance'] }}"
                       class="form-control"/>
            </div>
        </div>
    @endif

</div>

<div class="form-group  col-sm-12">
    <div class="col-md-4">
        <div class="form-group  ">
            <label for="address" class="control-label">{{__('Identity Number')}}</label>
            <div class="input-group">
                <span class="input-group-addon"><li class="fa fa-usd"></li></span>
                <input type="text" name="identity_number" class="form-control"
                       id="identity_number"
                       placeholder="{{__('Identity Number')}}"
                       value="{{old('identity_number', isset($supplier)? $supplier->identity_number :'')}}">
            </div>
            {{input_error($errors,'identity_number')}}
        </div>
    </div>

    <div class="col-md-8">
        <div class="form-group  ">
            <label for="inputDescription" class="control-label">{{__('Description')}}</label>
            <div class="input-group">
        <textarea name="description" class="form-control" rows="4" cols="150"
        >{{old('description', isset($supplier)? $supplier->description :'')}}</textarea>
            </div>
            {{input_error($errors,'description')}}
        </div>
    </div>
</div>

<div class="col-xs-12">
    @include('admin.suppliers.contacts.form')
</div>

<div class="col-xs-12">
    @include('admin.suppliers.bank_accounts.form')
</div>

<div class="form-group col-sm-12">
    @include('admin.buttons._save_buttons')
</div>
