<div class="col-xs-12">
    <div class="box-content card bordered-all blue-1 box-content-wg js__card">
        <h4 id="carTitle" class="box-title bg-blue-1 with-control">
            {{__('Add Car')}}
            <span class="controls">
							<button type="button" class="control fa fa-minus js__card_minus"></button>
							<button type="button" class="control fa fa-times js__card_remove"></button>
						</span>
        </h4>
        <div class="card-content js__card_content" style="">
            <form  method="post"  class="form"  id="carForm" enctype="multipart/form-data">
                <input type="hidden" name="customer_id" value="{{$customer->id}}" id="customer_id" >
                <input type="hidden" name="car_id" value="" id="car_id" >
{{--                <div class="form-group col-md-3  col-sm-6">--}}
{{--                    <label for="cartype" class="control-label">{{__('Car Type')}}</label>--}}
{{--                    <div class="input-group">--}}
{{--                        <span class="input-group-addon"><i class="fa fa-car"></i></span>--}}
{{--                        <input type="text" name="type" class="form-control" id="cartype" placeholder="{{__('Car Type')}}" required>--}}
{{--                    </div>--}}
{{--                </div>--}}

                <div class="col-md-4">
                    <div class="form-group has-feedback">
                        <label for="inputSymbolAR" class="control-label">{{__('Car Type')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon fa fa-globe"></span>
                            <select name="type_id" class="form-control  js-example-basic-single" id="setCarType">
                                <option value="">{{__('Select Car Type')}}</option>
                                @foreach(\App\Models\CarType::all() as $type)
                                    <option value="{{$type->id}}">{{$type->type}}</option>
                                @endforeach
                            </select>
                            {{input_error($errors,'type_id')}}
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group has-feedback">
                        <label for="inputSymbolAR" class="control-label">{{__('Car Company')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon fa fa-globe"></span>
                            <select name="company_id" class="form-control  js-example-basic-single" id="getModelsByCompany">
                                <option value="">{{__('Select Car Company')}}</option>
                                @foreach(\App\Models\Company::all() as $company)
                                    <option value="{{$company->id}}">{{$company->name}}</option>
                                @endforeach
                            </select>
                            {{input_error($errors,'company_id')}}
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group has-feedback">
                        <label for="inputSymbolAR" class="control-label">{{__('Car Model')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon fa fa-globe"></span>
                            <select name="model_id" class="form-control  js-example-basic-single" id="setModelsByCompany">
                                <option value="">{{__('Select Car Model')}}</option>
                                @foreach(\App\Models\CarModel::all() as $carModel)
                                    <option value="{{$carModel->id}}">{{$carModel->name}}</option>
                                @endforeach
                            </select>
                            {{input_error($errors,'model_id')}}
                        </div>
                    </div>
                </div>

                <div class="form-group  col-md-3 col-sm-6">
                    <label for="plate_number" class="control-label">{{__('Plate Number')}}</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-car"></i></span>
                        <input type="text" name="plate_number" class="form-control" id="plate_number" placeholder="{{__('Plate Number')}}" required >
                    </div>
                    {{input_error($errors,'plate_number')}}
                </div>

{{--                <div class="form-group  col-md-3 col-sm-6">--}}
{{--                    <label for="model" class="control-label">{{__('Car Model')}}</label>--}}
{{--                    <div class="input-group">--}}
{{--                        <span class="input-group-addon"><i class="fa fa-car"></i></span>--}}
{{--                        <input type="text" name="model" class="form-control" id="model" placeholder="{{__('Car Model')}}">--}}
{{--                    </div>--}}
{{--                    {{input_error($errors,'model')}}--}}
{{--                </div>--}}

                <div class="form-group  col-md-3 col-sm-6">
                    <label for="Chassis_number" class="control-label">{{__('Chassis Number')}}</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-car"></i></span>
                        <input type="text" name="Chassis_number" class="form-control" id="Chassis_number" placeholder="{{__('Chassis Number')}}">
                    </div>
                    {{input_error($errors,'Chassis_number')}}
                </div>

                <div class="form-group  col-md-3 col-sm-6">
                    <label for="speedometer" class="control-label">{{__('Speedometer')}}</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-car"></i></span>
                        <input type="text" name="speedometer" class="form-control" id="speedometer" placeholder="{{__('Speedometer')}}">
                    </div>
                    {{input_error($errors,'speedometer')}}
                </div>

                <div class="form-group  col-md-3 col-sm-6">
                    <label for="barcode" class="control-label">{{__('Barcode')}}</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-code"></i></span>
                        <input type="text" name="barcode" class="form-control" id="barcode" placeholder="{{__('Barcode')}}">
                    </div>
                    {{input_error($errors,'barcode')}}
                </div>

                <div class="form-group  col-md-3 col-sm-6">
                    <label for="motor_number" class="control-label">{{__('Motor Number')}}</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-code"></i></span>
                        <input type="text" name="motor_number" class="form-control" id="motor_number" placeholder="{{__('Motor Number')}}">
                    </div>
                    {{input_error($errors,'motor_number')}}
                </div>

                <div class="form-group  col-md-3 col-sm-6">
                    <label for="color" class="control-label">{{__('Car Color')}}</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-car"></i></span>
                        <input type="color" name="color" class="form-control" id="color" placeholder="{{__('Car Color')}}">
                    </div>
                    {{input_error($errors,'color')}}
                </div>



        <div class="form-group col-md-3 col-sm-6 print_number_barcode"  style="display: none;">
                    <label for="plate_number" class="control-label">{{__('print Barcode')}}</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-barcode"></i></span>
                        <input type="number" name="print_number" class="form-control" id="barcode_qty">
                    </div>
                    {{input_error($errors,'print_number')}}
                </div>

                <div class="form-group  col-md-6 col-sm-6">
                    <label for="image" class="control-label">{{__('Car Image')}}</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-car"></i></span>
                        <input type="file" name="image" class="form-control" id="image"
                               onchange="preview_image(event)" placeholder="{{__('Car Image')}}">
                    </div>


        <div class="image-container small-wg handing wg-i ani image-format-hd wg-edit-img" style="width: 300px;margin-top:50px !important">
                <a href="{{__('Car Image')}}" data-lightbox="roadtrip">


                    <!--<div class="image-container small-wg handing ani image-format-hd" style="width: 100%;">-->
                    <div class="image-container small-wg ani image-format-hd" style="width: 100%;">
                        <a id="openLargeImage" href="" data-lightbox="roadtrip">
                            <img style="width:300px; height: 150px !important"  id="output_image"/>
                        </a>
                        <div class="frame"></div>
                    </div>
                {{input_error($errors,'image')}}
                </a>
        </div>

                </div>



                <div class="form-group col-sm-12" id="addButtonForUpdate">
                    <button type="submit" id="addCar" name="button_1"  class="btn btn-primary   waves-effect waves-light updateCar">
                    <i class="ico ico-left fa fa-save"></i>
                    {{__('Save')}}</button>

                    <button type="submit" id="addCarAndBack" name="button_1" class="btn btn-primary   waves-effect waves-light">
                    <i class="ico ico-left fa fa-save"></i>
                    {{__('Save And Back')}}</button>
                    <button id="resetCarData"  type="button" class="btn btn-danger   mr-1 mb-1 waves-effect waves-light">
                    <i class="ico ico-left fa fa-trash"></i>
                    {{__('Reset')}}</button>
                    <button style="display: none;"  type="button"
                            class="btn btn-danger   mr-1 mb-1 waves-effect waves-light print_number_barcode"
                    onclick="openWin()">
                    <i class="ico ico-left fa fa-print"></i>
                        {{__('Print barcode')}}
                    </button>



                </div>
            </form>
        </div>
    </div>
    <!-- /.box-content -->
</div>
