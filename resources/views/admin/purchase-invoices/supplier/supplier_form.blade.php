<div class="modal fade" id="boostrapModal-2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-1">

    <div class="modal-dialog modal-lg" role="document">

        <div class="modal-content">
            <div class=" card box-content-wg-new bordered-all primary">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="box-title with-control" style="text-align: initial"
                        id="myModalLabel-1">{{__('Add Supplier')}}

                        <span class="controls hidden-sm hidden-xs pull-left">
                <button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Save')}}<img
                        class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px"
                        src="{{asset('assets/images/f1.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Reset')}}<img
                                    class="img-fluid"
                                    style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px"
                                    src="{{asset('assets/images/f2.png')}}"></button>
							<!-- <button class="control text-white" style="background:none;border:none;font-size:12px"> {{__('Back')}} <img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f3.png')}}"></button> -->
						</span>
                    </h4>
                </div>


                <form id="add_supplier_form">
                    <div class="modal-body">
                        <div class="row">

                            <input type="hidden" name="branch_id" id="setBranchIdForSupplier">

                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">{{__('Name En')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-user"></li></span>
                                            <input type="text" name="name_en" class="form-control" id="inputNameEn"
                                                   placeholder="{{__('NameEn')}}">
                                        </div>
                                    </div>

                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputNameAr" class="control-label">{{__('Name Ar')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-user"></li></span>
                                            <input type="text" name="name_ar" class="form-control" id="inputNameEn"
                                                   placeholder="{{__('NameAr')}}">
                                        </div>
                                    </div>

                                </div>

                                <div class="col-md-4">
                                    <div class="form-group has-feedback">
                                        <label for="country" class="control-label">{{__('Select Country')}}</label>
                                        <select name="country_id" class="form-control js-example-basic-single"
                                                style="width: 100%" id="country">
                                            <option value="">{{__('Select Country')}}</option>
                                            @foreach(\App\Models\Country::all() as $country)
                                                <option value="{{$country->id}}">
                                                    {{$country->name}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-12">


                                <div class="col-md-4">
                                    <div class="form-group has-feedback">
                                        <label for="city" class="control-label">{{__('Select City')}}</label>
                                        <select name="city_id" class="form-control  js-example-basic-single"
                                                style="width: 100%" id="city">
                                            @foreach(\App\Models\City::all() as $city)
                                                <option value="{{$city->id}}">
                                                    {{$city->name}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group has-feedback">
                                        <label for="area" class="control-label">{{__('Select Area')}}</label>
                                        <select name="area_id" class="form-control js-example-basic-single"
                                                style="width: 100%" id="area">
                                            @foreach(\App\Models\Area::all() as $area)
                                                <option value="{{$area->id}}">
                                                    {{$area->name}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputNameAr" class="control-label">{{__('Email')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-envelope"></li></span>
                                            <input type="email" name="email" class="form-control" id="inputNameEn"
                                                   placeholder="{{__('Email')}}">
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-12">


                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputNameAr" class="control-label">{{__('Phone 1')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-phone"></li></span>
                                            <input type="text" name="phone_1" class="form-control" id="phone_1"
                                                   placeholder="{{__('Phone one')}}">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputNameAr" class="control-label">{{__('Phone 2')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-phone"></li></span>
                                            <input type="text" name="phone_2" class="form-control" id="phone_2"
                                                   placeholder="{{__('Phone two')}}">
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputType" class="control-label">{{__('Type')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon fa fa-file"></span>
                                            <select class="form-control js-example-basic-single" style="width: 100%"
                                                    id="supplier_type" name="type"
                                                    onchange="getCompanyData()">
                                                <option value="">{{__('Select Type')}}</option>
                                                <option value="person">{{__('Person')}}</option>
                                                <option value="company">{{__('Company')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">

                                <div class="form-group company_data col-md-4"
                                     style="display: {{isset($supplier) && $supplier->type == 'company'? '':'none' }};">
                                    <label for="inputNameAr" class="control-label">{{__('Tax Card')}}</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><li class="fa fa-cart-arrow-down"></li></span>
                                        <input type="text" name="tax_card" class="form-control" id="tax_card"
                                               placeholder="{{__('Tax Card')}}">
                                    </div>
                                </div>

                                <div class="form-group company_data col-md-4"
                                     style="display: {{isset($supplier) && $supplier->type == 'company'? '':'none' }} ;">
                                    <label for="inputNameAr" class="control-label">{{__('Fax Number')}}</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><li class="fa fa-envelope"></li></span>
                                        <input type="text" name="fax" class="form-control" id="phone_2"
                                               placeholder="{{__('Fax')}}">
                                    </div>
                                </div>

                                <div class="form-group company_data col-md-4"
                                     style="display: {{isset($supplier) && $supplier->type == 'company'? '':'none' }};">
                                    <label for="inputNameAr" class="control-label">{{__('Commercial Number')}}</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><li class="fa fa-cart-arrow-down"></li></span>
                                        <input type="text" name="commercial_number" class="form-control"
                                               id="commercial_number"
                                               placeholder="{{__('Commercial Number')}}">
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-12">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="address" class="control-label">{{__('Address')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li
                                                    class="fa fa-cart-arrow-down"></li></span>
                                            <input type="text" name="address" class="form-control" id="address"
                                                   placeholder="{{__('Address')}}"
                                            >
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group has-feedback">
                                        <label for="group_id" class="control-label">{{__('Suppliers Groups')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon fa fa-file"></span>
                                            <select class="form-control js-example-basic-single" style="width: 100%"
                                                    name="group_id">
                                                <option value="">{{__('Select Group')}}</option>
                                                @foreach(\App\Models\SupplierGroup::all() as $group)
                                                    <option value="{{$group->id}}">{{$group->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group has-feedback">
                                        <label for="inputPhone" class="control-label">{{__('Status')}}</label>
                                        <div class="switch primary">
                                            <input type="checkbox" id="switch-1"
                                                   name="status"{{!isset($supplier)?'checked':''}}
                                                {{isset($supplier) && $supplier->status? 'checked':''}}
                                            >
                                            <label for="switch-1">{{__('Active')}}</label>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-12">

                                <div class="col-md-4">
                                    <div class="form-group  ">
                                        <label for="address" class="control-label">
                                            <a data-toggle="modal" data-target="#boostrapModal-3" title="Cars info"
                                               class="btn btn-primary "
                                               style="margin-top:1px;cursor:pointer;font-size:12px;padding:3px 15px">
                                                <i class="fa fa-plus"> </i> {{__('Location')}}
                                            </a>
                                            {{__('Lat')}}

                                        </label>

                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-map"></li></span>
                                            <input type="text" name="lat" class="form-control" id="lat"
                                                   placeholder="{{__('Lat')}}">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group  ">
                                        <label for="address" class="control-label">{{__('Long')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-map"></li></span>
                                            <input type="text" name="long" class="form-control" id="lng"
                                                   placeholder="{{__('Long')}}">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group  ">
                                        <label for="address"
                                               class="control-label">{{__('Maximum Supplier Funds')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-usd"></li></span>
                                            <input type="text" name="maximum_fund_on" class="form-control"
                                                   id="maximum_fund_on" placeholder="{{__('Cost')}}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group  col-sm-12">
                                <div class="col-md-4">
                                    <div class="form-group  ">
                                        <label for="address" class="control-label">{{__('Tax Number')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li
                                                    class="fa fa-cart-arrow-down"></li></span>
                                            <input type="text" name="tax_number" class="form-control" id="tax_number"
                                                   placeholder="{{__('Tax Number')}}">
                                        </div>
                                        {{input_error($errors,'tax_number')}}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group  col-sm-12">
                                <label for="inputDescription" class="control-label">{{__('Description')}}</label>
                                <div class="input-group">
                                    <textarea name="description" class="form-control" rows="4" cols="150"></textarea>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="modal-footer">
                        <div class="form-group col-sm-12">
                            <button id="btnsave" type="button" onclick="addSupplier()"
                                    class="btn hvr-rectangle-in saveAdd-wg-btn  ">
                                <i class="ico ico-left fa fa-save"></i>
                                {{__('Save')}}
                            </button>

                            <button id="reset" type="button" class="btn hvr-rectangle-in resetAdd-wg-btn  ">
                                <i class="ico ico-left fa fa-trash"></i>
                                {{__('Reset')}}
                            </button>

                            <button data-dismiss="modal" type="button"
                                    class="btn hvr-rectangle-in closeAdd-wg-btn  ">
                                <i class="ico ico-left fa fa-close"></i>
                                {{__('Back')}}
                            </button>

                            <!-- <button type="button" class="btn btn-default btn-sm waves-effect waves-light" data-dismiss="modal">Close</button> -->
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
