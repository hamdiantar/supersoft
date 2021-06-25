<div class="row">

    <div class=" form-group col-md-12">
        <div class="row">

            <div class="form-group  col-md-4">
                <label for="invoice_number" class="control-label">{{__('Invoice Number')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-file-text-o"></li></span>
                    <input type="text" name="invoice_number" class="form-control" id="invoice_number"
                           placeholder="{{__('Invoice Number')}}" value="####" disabled>
                </div>

                {{input_error($errors,'invoice_number')}}

                <input type="hidden" name="work_card_id" value="{{$work_card->id}}">
            </div>

            <div class="form-group col-md-4">
                <label for="date" class="control-label">{{__('Date')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-clock-o"></li></span>
                    <input type="date" name="date" class="form-control"
                           value="{{now()->format('Y-m-d')}}" id="date"
                           placeholder="{{__('Date')}}">
                </div>
                {{input_error($errors,'date')}}
            </div>

            <div class="form-group col-md-4">
                <div class="form-group">
                    <label for="type_en" class="control-label">{{__('Time')}}</label>
                    <div class="input-group">
                        <span class="input-group-addon"><li class="fa fa-clock-o"></li></span>
                        <input type="time" name="time" class="form-control"
                               value="{{now()->format('h:i')}}"
                               id="time" placeholder="{{__('Time')}}"
                        >
                    </div>
                    {{input_error($errors,'time')}}
                </div>
            </div>

            {{--            <div class="col-md-3">--}}
            {{--                <div class="form-group has-feedback">--}}
            {{--                    <label for="inputSymbolAR" class="control-label">{{__('Invoice Type')}}</label>--}}

            {{--                    <div class="radio primary">--}}
            {{--                        <input type="radio" name="type" id="type_cash" value="cash" checked>--}}
            {{--                        <label for="type_cash">{{__('Cash')}}</label>--}}
            {{--                    </div>--}}

            {{--                    <div class="radio primary">--}}
            {{--                        <input type="radio" name="type" id="type_credit" value="credit">--}}
            {{--                        <label for="type_credit">{{__('Credit')}}</label>--}}
            {{--                    </div>--}}
            {{--                </div>--}}
            {{--            </div>--}}

        </div>
    </div>

<!-- <div class="col-md-12">
        <div class="form-group col-md-12">
            <div class="form-group">
                <label for="date" class="control-label">{{__('Terms And Conditions')}}</label>
                <textarea id="editor1" name="terms" cols="5" rows="5"
                >{{old('terms')}}</textarea>
            </div>
        </div>
    </div> -->

    <div class="col-md-12">
        <div class="form-group col-md-12">
            <div class="form-group">
                <label for="date" class="control-label">{{__('Maintenance Types')}}</label>
                <div id="accordion" class="js__ui_accordion">

                    @foreach($maintenance_types as $index_one => $maintenance_type)
                        <h3 class="accordion-title">
                            {{$maintenance_type->name}}
                        </h3>
                        <div class="accordion-content">

                            <div class="checkbox ">
                                <input type="checkbox" id="checkbox-{{$maintenance_type->id}}-type"
                                       name="maintenance_types[]" value="{{$maintenance_type->id}}"
                                       onchange="activeMaintenance({{$maintenance_type->id}})">
                                <label for="checkbox-{{$maintenance_type->id}}-type">
                                <!-- <b>
                                   {{__('Check')}}
                                    </b> -->
                                    {{$maintenance_type->name}}
                                </label>

                            </div>
                            <hr>

                            @if($maintenance_type->maintenance->count() != 0 )
                                @foreach($maintenance_type->maintenance as $index=>$maintenance)

                                    <div class=" row">
                                        <div class="col-md-12">

                                            <div class="checkbox col-md-2">
                                                <input type="checkbox" id="checkbox-{{$maintenance->id}}-part"
                                                       name="type-{{$maintenance_type->id}}[maintenance_type_parts][]"
                                                       value="{{$maintenance->id}}"
                                                       onclick="activeMaintenanceForm({{$maintenance->id}})"
                                                       disabled
                                                       class="active_maintenance_{{$maintenance_type->id}}_check_box">
                                                <label for="checkbox-{{$maintenance->id}}-part">
                                                    {{$maintenance->name}}
                                                </label>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="date" class="control-label">{{__('Notes')}}</label>
                                                    <textarea
                                                        class="form-control maintenance_type_{{$maintenance_type->id}}
                                                            active_maintenance_{{$maintenance->id}}_form"
                                                        disabled name="notes_{{$maintenance->id}}"></textarea>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="date"
                                                           class="control-label">{{__('Issue Images')}}</label>
                                                    <input type="file" disabled name="image_{{$maintenance->id}}[]"
                                                           multiple
                                                           class="form-control maintenance_type_{{$maintenance_type->id}}
                                                               active_maintenance_{{$maintenance->id}}_form">
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <label for="date" class="control-label">{{__('Degree')}}</label>
                                                <ul class="list-inline">

                                                    <li>
                                                        <div class="radio success">
                                                            <input type="radio" checked
                                                                   name="degree_{{$maintenance->id}}"
                                                                   id="radio-{{$maintenance->id}}-1" value="1"
                                                                   disabled
                                                                   class=" maintenance_type_{{$maintenance_type->id}}
                                                                       active_maintenance_{{$maintenance->id}}_form">
                                                            <label
                                                                for="radio-{{$maintenance->id}}-1">{{__('Low')}}</label>
                                                        </div>
                                                        <!-- /.radio -->
                                                    </li>
                                                    <li>
                                                        <div class="radio warning">
                                                            <input type="radio" name="degree_{{$maintenance->id}}"
                                                                   id="radio-{{$maintenance->id}}-2" value="2"
                                                                   disabled
                                                                   class="maintenance_type_{{$maintenance_type->id}}
                                                                       active_maintenance_{{$maintenance->id}}_form">
                                                            <label
                                                                for="radio-{{$maintenance->id}}-2">{{__('Average')}}</label>
                                                        </div>
                                                        <!-- /.radio -->
                                                    </li>
                                                    <li>
                                                        <div class="radio danger">
                                                            <input type="radio" name="degree_{{$maintenance->id}}"
                                                                   id="radio-{{$maintenance->id}}-3" value="3"
                                                                   disabled
                                                                   class="maintenance_type_{{$maintenance_type->id}}
                                                                       active_maintenance_{{$maintenance->id}}_form">
                                                            <label
                                                                for="radio-{{$maintenance->id}}-3">{{__('High')}}</label>
                                                        </div>
                                                        <!-- /.radio -->
                                                    </li>
                                                </ul>
                                            </div>

                                        </div>

                                        <div class="col-md-12">
                                            <hr>
                                        </div>
                                    </div>

                                @endforeach
                            @else
                                <div class=" row">
                                    <div class="col-md-12" style="text-align: center;">
                                        <span> {{__('No Maintenance Detection')}} </span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>

    {{--    <div class="col-md-12">--}}

    {{--        <div class="col-md-6">--}}
    {{--            <div class="form-group">--}}
    {{--                @include('admin.buttons._save_buttons')--}}
    {{--            </div>--}}
    {{--        </div>--}}

    {{--    </div>--}}

    <div class="col-md-12">

        <div class="form-group col-sm-12">

            <input type="hidden" name="save_type" id="save_type">

            <button id="btnsave" type="submit" class="btn hvr-rectangle-in saveAdd-wg-btn" onclick="saveAndPrint('save')">
                <i class="ico ico-left fa fa-save"></i>
                {{__('Save')}}
            </button>

            <img src="{{asset('default-images/loading.gif')}}"
                 style="width: 41px; height: auto; display: none;" id="card_invoice_loading">


            <button id="reset" type="button" class="btn hvr-rectangle-in resetAdd-wg-btn  ">
                <i class="ico ico-left fa fa-trash"></i>
                {{__('Reset')}}
            </button>

            <button id="back" type="button" class="btn hvr-rectangle-in closeAdd-wg-btn  ">
                <i class="ico ico-left fa fa-close"></i>
                {{__('Back')}}
            </button>

            <button type="submit" class="btn hvr-rectangle-in saveAdd-wg-btn"
                    onclick="saveAndPrint('save_and_print')">
                <i class="ico ico-left fa fa-print"></i>
                {{__('Save and print')}}
            </button>


        </div>
    </div>
</div>


