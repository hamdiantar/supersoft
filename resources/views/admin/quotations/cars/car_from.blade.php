<div class="col-xs-12">
<div class="box-content box-content-wg card bordered-all blue-1 js__card">
        <h4 id="carTitle" class="box-title bg-blue-1 with-control">
            {{__('Add Car')}}
            <span class="controls">
							<button type="button" class="control fa fa-minus js__card_minus"></button>
							<button type="button" class="control fa fa-times js__card_remove"></button>
						</span>
        </h4>
        <div class="card-content js__card_content" style="">
            <form  method="post"  class="form"  id="carForm" enctype="multipart/form-data">
                <input type="hidden" name="customer_id" value="" id="customer_id" class="customerIDFromController">
                <input type="hidden" name="car_id" value="" id="car_id" >
{{--                <div class="form-group  col-sm-4">--}}
{{--                    <label for="cartype" class="control-label">{{__('Car Type')}}</label>--}}
{{--                    <div class="input-group">--}}
{{--                        <span class="input-group-addon"><i class="fa fa-car"></i></span>--}}
{{--                        <input type="text" name="type" class="form-control" id="cartype" placeholder="{{__('Car Type')}}" required>--}}
{{--                    </div>--}}
{{--                </div>--}}

                <div class="col-md-4">
                    <div class="form-group has-feedback">
                        <label for="inputSymbolAR" class="control-label">{{__('Select Car Type')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon fa fa-globe"></span>
                            <select name="type_id" class="form-control clear_data  js-example-basic-single" id="setCarType">
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
                        <label for="inputSymbolAR" class="control-label">{{__('Select Company')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon fa fa-globe"></span>
                            <select name="company_id" class="form-control clear_data  js-example-basic-single" id="getModelsByCompany">
                                <option value="">{{__('Select Select Company')}}</option>
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
                        <label for="inputSymbolAR" class="control-label">{{__('Select Car Model')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon fa fa-globe"></span>
                            <select name="model_id" class="form-control  clear_data js-example-basic-single" id="setModelsByCompany">
                                <option value="">{{__('Select Car Model')}}</option>
                                @foreach(\App\Models\CarModel::all() as $carModel)
                                    <option value="{{$carModel->id}}">{{$carModel->name}}</option>
                                @endforeach
                            </select>
                            {{input_error($errors,'model_id')}}
                        </div>
                    </div>
                </div>


                <div class="form-group  col-sm-4">
                    <label for="plate_number" class="control-label">{{__('Plate Number')}}</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-car"></i></span>
                        <input type="text" name="plate_number" class="form-control clear_data" id="plate_number"
                               placeholder="{{__('Plate Number')}}" required >
                    </div>
                    {{input_error($errors,'plate_number')}}
                </div>

{{--                <div class="form-group  col-sm-4">--}}
{{--                    <label for="model" class="control-label">{{__('Car Model')}}</label>--}}
{{--                    <div class="input-group">--}}
{{--                        <span class="input-group-addon"><i class="fa fa-car"></i></span>--}}
{{--                        <input type="text" name="model" class="form-control" id="model" placeholder="{{__('Car Model')}}">--}}
{{--                    </div>--}}
{{--                    {{input_error($errors,'model')}}--}}
{{--                </div>--}}

                <div class="form-group  col-sm-4">
                    <label for="Chassis_number" class="control-label">{{__('Chassis Number')}}</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-car"></i></span>
                        <input type="text" name="Chassis_number" class="form-control clear_data" id="Chassis_number" placeholder="{{__('Chassis Number')}}">
                    </div>
                    {{input_error($errors,'Chassis_number')}}
                </div>

                <div class="form-group  col-sm-4">
                    <label for="speedometer" class="control-label">{{__('Speedometer')}}</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-car"></i></span>
                        <input type="text" name="speedometer" class="form-control clear_data"
                               id="speedometer" placeholder="{{__('Speedometer')}}">
                    </div>
                    {{input_error($errors,'speedometer')}}
                </div>

                <div class="form-group  col-sm-4">
                    <label for="barcode" class="control-label">{{__('Barcode')}}</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-code"></i></span>
                        <input type="text" name="barcode" class="form-control clear_data" id="barcode" placeholder="{{__('Barcode')}}">
                    </div>
                    {{input_error($errors,'barcode')}}
                </div>

                <div class="form-group  col-md-3 col-sm-4">
                    <label for="motor_number" class="control-label">{{__('Motor Number')}}</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-code"></i></span>
                        <input type="text" name="motor_number" class="form-control clear_data"
                               id="motor_number" placeholder="{{__('Motor Number')}}">
                    </div>
                    {{input_error($errors,'motor_number')}}
                </div>

                <div class="form-group  col-sm-4">
                    <label for="color" class="control-label">{{__('Car Color')}}</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-car"></i></span>
                        <input type="color" name="color" class="form-control clear_data" id="color" placeholder="{{__('Car Color')}}">
                    </div>
                    {{input_error($errors,'color')}}
                </div>

                <div class="form-group  col-sm-4">
                    <label for="image" class="control-label">{{__('Car Image')}}</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-car"></i></span>
                        <input type="file" name="image" class="form-control clear_data" id="image"
                               onchange="preview_image(event)" placeholder="{{__('Car Image')}}">
                    </div>
                    <img style="max-width:300px; max-height: 130px"  id="output_image"/>
                    {{input_error($errors,'image')}}
                </div>

                <div class="form-group  col-sm-12 print_number_barcode"  style="display: none;">
                    <label for="plate_number" class="control-label">{{__('print Barcode')}}</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-barcode"></i></span>
                        <input type="number" name="print_number" class="form-control clear_data" id="barcode_qty">
                    </div>
                    {{input_error($errors,'print_number')}}
                </div>

                <div class="form-group col-sm-12" id="addButtonForUpdate">
                    <button type="submit" id="addCar" name="button_1"  class="btn btn-primary waves-effect waves-light updateCar">{{__('Save')}}</button>
                    <button type="submit" id="addCarAndBack" name="button_1" class="btn btn-primary waves-effect waves-light">{{__('Save And Back')}}</button>
                    <button id="resetCarData"  type="button" class="btn btn-danger mr-1 mb-1 waves-effect waves-light">{{__('Reset')}}</button>
                </div>
            </form>
        </div>
    </div>
    <!-- /.box-content -->
</div>
