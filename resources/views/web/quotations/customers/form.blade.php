<div class="modal fade" id="addSupplierForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class=" card box-content-wg-new bordered-all primary">

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="box-title with-control" style="text-align: initial"
                    id="myModalLabel-1">{{__('Add New Customer')}}
                    <span class="controls hidden-sm hidden-xs pull-left">
                <button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Save')}}<img
                            class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px"
                            src="{{asset('assets/images/f1.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Reset')}}
                                <img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f2.png')}}">
                            </button>
						</span>
                </h4>

                <form id="add_customer_form_e">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">

                                <input type="hidden" name="branch_id" id="setBranchId">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputNameAR" class="control-label">{{__('Name in Arabic')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-file-text-o"></li></span>
                                            <input type="text" name="name_ar" class="form-control" id="inputNameAR"
                                                   placeholder="{{__('Name in Arabic')}}">
                                        </div>
                                        {{input_error($errors,'name_ar')}}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group has-feedback">
                                        <label for="inputNameEN" class="control-label">{{__('Name in English')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-file-text-o"></li></span>
                                            <input type="text" name="name_en" class="form-control" id="inputNameEN"
                                                   placeholder="{{__('Name in English')}}">
                                        </div>
                                        {{input_error($errors,'name_en')}}
                                    </div>
                                </div>

                                <div class="col-md-4">

                                    <div class="form-group has-feedbac">
                                        <label for="inputSymbolAR"
                                               class="control-label">{{__('Select Customer Category')}}</label>
                                        <select name="customer_category_id" class="form-control js-example-basic-single"
                                                style="width: 100%" >
                                            @foreach( $customersGroups as $customersGroup)

                                                <option value="{{$customersGroup->id}}">{{$customersGroup->name}}</option>
                                            @endforeach
                                        </select>
                                        {{input_error($errors,'customer_category_id')}}
                                    </div>
                                </div>

                            </div>


                            <div class="col-md-12">

                                <div class="col-md-4">

                                    <div class="form-group has-feedback">
                                        <label for="inputSymbolAR"
                                               class="control-label">{{__('Select Country')}}</label>
                                        <select name="country_id" class="form-control js-example-basic-single"
                                                style="width: 100%" id="country">
                                            <option value="">{{__('Select Country')}}</option>
                                            @foreach(\App\Models\Country::all() as $country)
                                                <option value="{{$country->id}}">{{$country->name}}</option>
                                            @endforeach
                                        </select>
                                        {{input_error($errors,'country_id')}}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group has-feedback">
                                        <label for="inputSymbolAR" class="control-label">{{__('Select City')}}</label>
                                        <select name="city_id" class="form-control js-example-basic-single"
                                                style="width: 100%" id="city">
                                        </select>
                                    </div>
                                </div>


                                <div class="col-md-4">
                                    <div class="form-group has-feedback">
                                        <label for="area" class="control-label">{{__('Select Area')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon fa fa-globe"></span>
                                            <select name="area_id" class="form-control  js-example-basic-single select2"
                                                    id="area">

                                            </select>
                                            {{input_error($errors,'area_id')}}
                                        </div>
                                    </div>
                                </div>

                            </div>


                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <div class="form-group has-feedback" id="appendCompanyData">
                                        <label for="inputSymbolAR"
                                               class="control-label">{{__('Select Customer Type')}}</label>
                                        <select name="type" class="form-control js-example-basic-single"
                                                style="width: 100%" id="customer_type"
                                                onchange="getCompanyDataForQuotations()">
                                            <option value="">{{__('Select Customer Type')}}</option>
                                            <option value="person">{{__('Person')}}</option>
                                            <option value="company">{{__('Company')}}</option>
                                        </select>
                                        {{input_error($errors,'type')}}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group has-feedback">
                                        <label for="address" class="control-label">{{__('Address')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-home"></li></span>
                                            <input type="text" name="address" class="form-control" id="address"
                                                   placeholder="{{__('Address')}}">
                                        </div>
                                        {{input_error($errors,'address')}}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group has-feedback">
                                        <label for="phone1" class="control-label">{{__('Phone 1')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-phone"></li></span>
                                            <input type="text" name="phone1" class="form-control" id="phone1"
                                                   placeholder="{{__('Phone 1')}}">
                                        </div>
                                        {{input_error($errors,'phone1')}}
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-12">
                                <div class="form-group company_data col-md-3"
                                     style="display: {{isset($supplier) && $supplier->type == 'company'? '':'none' }};">
                                    <label for="inputNameAr" class="control-label">{{__('Tax Card')}}</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><li class="fa fa-cart-arrow-down"></li></span>
                                        <input type="text" name="tax_card" class="form-control" id="tax_card"
                                               placeholder="{{__('Tax Card')}}">
                                    </div>
                                </div>

                                <div class="form-group company_data col-md-3"
                                     style="display: {{isset($supplier) && $supplier->type == 'company'? '':'none' }} ;">
                                    <label for="inputNameAr" class="control-label">{{__('Fax Number')}}</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><li class="fa fa-envelope"></li></span>
                                        <input type="text" name="fax" class="form-control" id="phone_2"
                                               placeholder="{{__('Fax')}}">
                                    </div>
                                </div>

                                <div class="form-group company_data col-md-3"
                                     style="display: {{isset($supplier) && $supplier->type == 'company'? '':'none' }};">
                                    <label for="inputNameAr" class="control-label">{{__('Commercial Number')}}</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><li class="fa fa-cart-arrow-down"></li></span>
                                        <input type="text" name="commercial_number" class="form-control"
                                               id="commercial_number"
                                               placeholder="{{__('Commercial Number')}}"
                                        >
                                    </div>
                                </div>

                                <div class="form-group company_data col-sm-3" id="CompanyResponsible"
                                     style="display: {{isset($supplier) && $supplier->type == 'company'? '':'none' }};">
                                    <label for="responsible" class="control-label">{{__('Responsible Name')}}</label>
                                    <div class="input-group">
                            <span class="input-group-addon">
                                <li class="fa fa-fax"></li>
                            </span>
                                        <input type="text" name="responsible"
                                               class="form-control" id="responsible"
                                               placeholder="{{__('Responsible Name')}}">
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <div class="form-group has-feedback">
                                        <label for="phone2" class="control-label">{{__('Phone 2')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-phone"></li></span>
                                            <input type="text" name="phone2" class="form-control" id="phone2"
                                                   placeholder="{{__('Phone 2')}}">
                                        </div>
                                        {{input_error($errors,'phone2')}}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group has-feedback">
                                        <label for="email" class="control-label">{{__('Email')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">@</span>
                                            <input type="text" name="email" class="form-control" id="email"
                                                   placeholder="{{__('Email')}}">
                                        </div>
                                        {{input_error($errors,'email')}}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group has-feedback">
                                        <label for="cars_number" class="control-label">{{__('Cars Number')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-car"></li></span>
                                            <input type="text" name="cars_number" value="0" class="form-control"
                                                   id="cars_number"
                                                   placeholder="{{__('Cars Number')}}">
                                        </div>
                                        {{input_error($errors,'cars_number')}}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group has-feedback col-sm-12">
                                <div class="col-md-4">
                                    <label for="inputPhone" class="control-label">{{__('Status')}}</label>
                                    <div class="switch primary" style="margin-top: 15px">
                                        <input type="hidden" name="status" value="0">
                                        <input type="checkbox" id="switch-1" name="status" value="1" CHECKED>
                                        <label for="switch-1">{{__('Active')}}</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group has-feedback col-sm-12">
                                <div class="form-group">
                                    <label for="notes" class="control-label">{{__('Notes')}}</label>
                                    <div class="input-group">
                                        <textarea name="notes" class="form-control" rows="4" cols="200"></textarea>
                                    </div>
                                    {{input_error($errors,'notes')}}
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="form-group col-sm-12">
                                <button type="button" class="btn hvr-rectangle-in saveAdd-wg-btn" onclick="addCustomerQuotations()">
                                    <i class="ico ico-left fa fa-save"></i>
                                    {{__('Save')}}
                                </button>
                                <button id="reset" type="button" class="btn hvr-rectangle-in resetAdd-wg-btn  ">
                                    <i class="ico ico-left fa fa-trash"></i>
                                    {{__('Reset')}}
                                </button>
                                <button type="button" class="btn hvr-rectangle-in closeAdd-wg-btn  " data-dismiss="modal">
                                    <i class="ico ico-left fa fa-close"></i>
                                    {{__('Back')}}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                @include('web.quotations.cars.add_car')
            </div>
        </div>
    </div>
</div>

