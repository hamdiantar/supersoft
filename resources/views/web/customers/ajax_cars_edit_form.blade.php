<form class="form" action="{{route('web:cars.update', $car->id)}}" method="post" enctype="multipart/form-data">
    @csrf


    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">{{__('Edit Car')}}</h4>
    </div>
    <div class="modal-body">

        <div class="row">

            <div class="col-md-6">
                <div class="form-group has-feedback">
                    <label for="inputSymbolAR" class="control-label">{{__('Car Type')}}</label>
                    <div class="input-group">
                        <span class="input-group-addon fa fa-globe"></span>
                        <select name="type_id" class="form-control  js-example-basic-single" id="type_id">
                            <option value="">{{__('Select Car Type')}}</option>
                            @foreach($carsTypes as $k=>$v)
                                <option value="{{$k}}" {{$car->type_id == $k? 'selected':''}}>{{$v}}</option>
                            @endforeach
                        </select>

                        {{input_error($errors,'type_id')}}
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group has-feedback">
                    <label for="inputSymbolAR" class="control-label">{{__('Car Company')}}</label>
                    <div class="input-group">
                        <span class="input-group-addon fa fa-globe"></span>
                        <select name="company_id" class="form-control  js-example-basic-single company_id_edit" onchange="carModels('edit')">
                            <option value="">{{__('Select Car Company')}}</option>
                            @foreach($companies as $k=>$v)
                                <option value="{{$k}}" {{$car->company_id == $k? 'selected':''}}>{{$v}}</option>
                            @endforeach
                        </select>
                        {{input_error($errors,'company_id')}}
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group has-feedback">
                    <label for="inputSymbolAR" class="control-label">{{__('Car Model')}}</label>
                    <div class="input-group">
                        <span class="input-group-addon fa fa-globe"></span>
                        <select name="model_id" class="form-control  js-example-basic-single model_id_edit">
                            <option value="" class="removeToNewData_edit">{{__('Select Car Model')}}</option>
                            @foreach($carsModels as $k=>$v)
                                <option value="{{$k}}" {{$car->model_id == $k? 'selected':''}} class="removeToNewData_edit">{{$v}}</option>
                            @endforeach
                        </select>
                        {{input_error($errors,'model_id')}}
                    </div>
                </div>
            </div>



            <div class="form-group  col-md-6 col-sm-6">
                <label for="plate_number" class="control-label">{{__('Plate Number')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-car"></i></span>
                    <input type="text" name="plate_number" class="form-control" id="plate_number" value="{{$car->plate_number}}"
                           placeholder="{{__('Plate Number')}}" required>
                </div>
                {{input_error($errors,'plate_number')}}
            </div>

            <div class="form-group  col-md-6 col-sm-6">
                <label for="Chassis_number" class="control-label">{{__('Chassis Number')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-car"></i></span>
                    <input type="text" name="Chassis_number" class="form-control" id="Chassis_number" value="{{$car->Chassis_number}}"
                           placeholder="{{__('Chassis Number')}}">
                </div>
                {{input_error($errors,'Chassis_number')}}
            </div>

            <div class="form-group  col-md-6 col-sm-6">
                <label for="speedometer" class="control-label">{{__('Speedometer')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-car"></i></span>
                    <input type="text" name="speedometer" class="form-control" id="speedometer" value="{{$car->speedometer}}"
                           placeholder="{{__('Speedometer')}}">
                </div>
                {{input_error($errors,'speedometer')}}
            </div>

            <div class="form-group  col-md-6 col-sm-6">
                <label for="barcode" class="control-label">{{__('Barcode')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-code"></i></span>
                    <input type="text" name="barcode" class="form-control" id="barcode" value="{{$car->barcode}}"
                           placeholder="{{__('Barcode')}}">
                </div>
                {{input_error($errors,'barcode')}}
            </div>

            <div class="form-group  col-md-6 col-sm-6">
                <label for="motor_number" class="control-label">{{__('Motor Number')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-code"></i></span>
                    <input type="text" name="motor_number" class="form-control" id="motor_number" value="{{$car->motor_number}}"
                           placeholder="{{__('Motor Number')}}">
                </div>
                {{input_error($errors,'motor_number')}}
            </div>

            <div class="form-group  col-md-6 col-sm-6">
                <label for="color" class="control-label">{{__('Car Color')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-car"></i></span>
                    <input type="color" name="color" class="form-control" id="color" value="{{$car->color}}"
                           placeholder="{{__('Car Color')}}">
                </div>
                {{input_error($errors,'color')}}
            </div>

            <div class="form-group  col-md-6 col-sm-6">
                <label for="image" class="control-label">{{__('Car Image')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-car"></i></span>
                    <input type="file" name="image" class="form-control" id="image"
                           onchange="readURL(this);" placeholder="{{__('Car Image')}}">
                </div>


                <div class="image-container small-wg handing wg-i ani image-format-hd wg-edit-img"
                     style="width: 300px;margin-top:50px !important">
                    <a href="{{$car->img}}" data-lightbox="roadtrip">

                        <!--<div class="image-container small-wg handing ani image-format-hd" style="width: 100%;">-->
                        <div class="image-container small-wg ani image-format-hd" style="width: 100%;">
                            <a id="openLargeImage" href="{{$car->img}}" data-lightbox="roadtrip">
                                <img style="width:300px; height: 150px !important"  src="{{$car->img}}" id="blah"/>
                            </a>
                            <div class="frame" ></div>
                        </div>
                        {{input_error($errors,'image')}}
                    </a>
                </div>

            </div>

        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-default btn-sm waves-effect waves-light" data-dismiss="modal">Close
        </button>
        <button type="submit" class="btn btn-primary btn-sm waves-effect waves-light">{{__('Save')}}</button>
    </div>

</form>
