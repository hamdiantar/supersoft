<div class="row">

    <div class=" form-group col-md-12">
        <div class="row">

            <div class="form-group  col-md-4">
                <label for="invoice_number" class="control-label">{{__('Invoice Number')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-file-text-o"></li></span>
                    <input type="text" name="invoice_number" class="form-control" id="invoice_number"
                           placeholder="{{__('Invoice Number')}}" value="{{$card_invoice->inv_number}}" disabled>
                </div>

                {{input_error($errors,'invoice_number')}}

                <input type="hidden" name="work_card_id" value="{{$work_card->id}}">
            </div>

            <div class="form-group col-md-4">
                <label for="date" class="control-label">{{__('Date')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-clock-o"></li></span>
                    <input type="date" name="date" class="form-control"
                           value="{{ $card_invoice->date? $card_invoice->date : now()->format('Y-m-d')}}" id="date"
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
                               value="{{$card_invoice->time? $card_invoice->time : now()->format('h:i')}}"
                               id="time" placeholder="{{__('Time')}}"
                        >
                    </div>
                    {{input_error($errors,'time')}}
                </div>
            </div>

            <div class="form-group col-md-4">
                <div class="form-group">
                    <button class="btn hvr-rectangle-in closeAdd-wg-btn  maintenance_type_active_maintenance_form"
                            type="button"
                            data-toggle="modal" data-target="#winch_info_data" title="Cars info">
                        <i class="ico ico-left fa fa-gear"></i>
                        {{__('Winch')}}
                    </button>
                </div>
            </div>

            {{--            <div class="col-md-3">--}}
            {{--                <div class="form-group has-feedback">--}}
            {{--                    <label for="inputSymbolAR" class="control-label">{{__('Invoice Type')}}</label>--}}

            {{--                    <div class="radio primary">--}}
            {{--                        <input type="radio" name="type" id="type_cash" value="cash"--}}
            {{--                                {{$card_invoice->type == 'cash'? 'checked':''}}>--}}
            {{--                        <label for="type_cash">{{__('Cash')}}</label>--}}
            {{--                    </div>--}}

            {{--                    <div class="radio primary">--}}
            {{--                        <input type="radio" name="type" id="type_credit" value="credit"--}}
            {{--                                {{$card_invoice->type == 'credit'? 'checked':''}}>--}}
            {{--                        <label for="type_credit">{{__('Credit')}}</label>--}}
            {{--                    </div>--}}
            {{--                </div>--}}
            {{--            </div>--}}

        </div>
    </div>


    <div class="col-md-12">

        <div class="form-group ">
            <label for="date" class="control-label">{{__('Maintenance Types')}}</label>

            <div class="widget-content widget-content-area icon-accordion-content ">
                <div id="toggleAccordion" style="padding: 10px">
                    @php
                        $types_count = 0;
                    @endphp
                    @foreach($maintenance_types as $index_one => $maintenance_type)
                        <div class="card mb-1">

                            @php
                                $maintenance_type_pivot = $card_invoice->maintenanceDetectionTypes()
                                ->wherePivot('maintenance_type_id',$maintenance_type->id)->first();
                            @endphp

                            <div class="card-header" id="headingOne_{{$index_one}}">
                                <h5 class="mb-0 mt-0">
                                    <span role="menu" class="" data-toggle="collapse"
                                          data-target="#iconChangeAccordionOne_{{$index_one}}"
                                          aria-expanded="true" aria-controls="iconChangeAccordionOne">

                                      <i class="flaticon-bell-18"></i> #{{$index_one+1}} {{$maintenance_type->name}}
                                        {{--                                        <i class="fa fa-home float-right"></i>--}}
                                        @if(in_array($maintenance_type->id, $card_invoice_type_ids))
                                            <span style="float: left;" class="fa fa-check float-right">({{__('Checked')}})</span>
                                        @endif

                                    </span>
                                </h5>
                            </div>

                            <div id="iconChangeAccordionOne_{{$index_one}}" class="collapse"
                                 aria-labelledby="headingOne4"
                                 data-parent="#toggleAccordion">
                                <div class="card-body type_body_{{$maintenance_type->id}}" style="padding: 10px">

                                    {{-- TYPES CHECKBOX--}}
                                    <div class="checkbox ">
                                        <input type="checkbox" id="checkbox-{{$maintenance_type->id}}-type"
                                               {{in_array($maintenance_type->id, $card_invoice_type_ids)? 'checked':''}}
                                               name="maintenance_types[]" value="{{$maintenance_type->id}}"
                                               onchange="activeMaintenance({{$maintenance_type->id}})">
                                        <label for="checkbox-{{$maintenance_type->id}}-type">
                                            {{$maintenance_type->name}}
                                        </label>

                                    </div>
                                    <hr>
                                    <div class=" row">
                                        @php
                                            $maintenance_count = 0;
                                        @endphp

                                        @foreach($maintenance_type->maintenance as $index=>$maintenance)

                                            <div class="col-md-12">

                                                @php
                                                    $part_pivot = $card_invoice->maintenanceDetections()
                                                    ->wherePivot('maintenance_detection_id',$maintenance->id)->first();
                                                @endphp

                                                {{-- PARTS CHECKBOX--}}
                                                <div class="checkbox col-md-2">
                                                    <input type="checkbox" id="checkbox-{{$maintenance->id}}-part"
                                                           name="type-{{$maintenance_type->id}}[maintenance_type_parts][]"
                                                           value="{{$maintenance->id}}"
                                                           onclick="activeMaintenanceForm({{$maintenance->id}})"

                                                           {{in_array($maintenance_type->id, $card_invoice_type_ids)? '':'disabled' }}
                                                           {{in_array($maintenance->id, $card_invoice_parts_ids)? 'checked':'' }}

                                                           class="active_maintenance_{{$maintenance_type->id}}_check_box">
                                                    <label for="checkbox-{{$maintenance->id}}-part">
                                                        {{$maintenance->name}}
                                                    </label>
                                                </div>

                                                {{-- NOTES TEXTAREA--}}
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="date" class="control-label">{{__('Notes')}}</label>
                                                        <textarea
                                                            class="form-control maintenance_type_{{$maintenance_type->id}}
                                                                active_maintenance_{{$maintenance->id}}_form"
                                                            {{in_array($maintenance->id, $card_invoice_parts_ids)? '':'disabled' }}
                                                            name="notes_{{$maintenance->id}}"
                                                        >{{$part_pivot ? $part_pivot->pivot->notes:''}}</textarea>
                                                    </div>
                                                </div>

                                                {{-- IMAGE INPUT--}}
                                                <div class="col-md-2">
                                                    <div class="form-group">

                                                        <label class="control-label">
                                                            {{__('Issue Images')}}

                                                            <a href="#" data-toggle="modal"
                                                               data-target="#maintenance_images" title="Cars info"
                                                               onclick="showImages('{{$maintenance->id}}', '{{$card_invoice->id}}')">
                                                                <span class="fa fa-image"></span>
                                                            </a>

                                                        </label>

                                                        <input type="file" name="image_{{$maintenance->id}}[]" multiple
                                                               {{in_array($maintenance->id, $card_invoice_parts_ids)? '':'disabled' }}
                                                               class="form-control  maintenance_type_{{$maintenance_type->id}}
                                                                   active_maintenance_{{$maintenance->id}}_form">
                                                    </div>
                                                </div>

                                                {{-- DEGREE RADIO BUTTONS--}}
                                                <div class="col-md-4">
                                                    <label for="date" class="control-label">{{__('Degree')}}</label>
                                                    <ul class="list-inline">

                                                        <li>
                                                            <div class="radio success">
                                                                <input type="radio" name="degree_{{$maintenance->id}}"
                                                                       id="radio-{{$maintenance->id}}-1" value="1"
                                                                       {{in_array($maintenance->id, $card_invoice_parts_ids)? '':'checked disabled' }}
                                                                       {{$part_pivot && $part_pivot->pivot->degree == 1 ? 'checked':'' }}
                                                                       class="active_maintenance_{{$maintenance->id}}_form
                                                                       maintenance_type_{{$maintenance_type->id}} ">
                                                                <label
                                                                    for="radio-{{$maintenance->id}}-1">{{__('Low')}}</label>
                                                            </div>
                                                            <!-- /.radio -->
                                                        </li>
                                                        <li>
                                                            <div class="radio warning">
                                                                <input type="radio" name="degree_{{$maintenance->id}}"
                                                                       id="radio-{{$maintenance->id}}-2" value="2"
                                                                       {{in_array($maintenance->id, $card_invoice_parts_ids)? '':'disabled' }}
                                                                       {{$part_pivot && $part_pivot->pivot->degree == 2 ? 'checked':'' }}
                                                                       class="active_maintenance_{{$maintenance->id}}_form
                                                                       maintenance_type_{{$maintenance_type->id}} ">
                                                                <label
                                                                    for="radio-{{$maintenance->id}}-2">{{__('Average')}}</label>
                                                            </div>
                                                            <!-- /.radio -->
                                                        </li>
                                                        <li>
                                                            <div class="radio danger">
                                                                <input type="radio" name="degree_{{$maintenance->id}}"
                                                                       id="radio-{{$maintenance->id}}-3" value="3"
                                                                       {{in_array($maintenance->id, $card_invoice_parts_ids)? '':'disabled' }}
                                                                       {{$part_pivot && $part_pivot->pivot->degree == 3 ? 'checked':'' }}
                                                                       class="active_maintenance_{{$maintenance->id}}_form
                                                                       maintenance_type_{{$maintenance_type->id}} ">
                                                                <label
                                                                    for="radio-{{$maintenance->id}}-3">{{__('High')}}</label>
                                                            </div>
                                                            <!-- /.radio -->
                                                        </li>
                                                    </ul>
                                                </div>

                                            </div>

                                            <div class="form-group col-md-12">
                                                {{-- SERVICES BUTTONS--}}
                                                <div>
                                                    <p>
                                                        <button
                                                            class="btn hvr-rectangle-in saveAdd-wg-btn   maintenance_type_{{$maintenance_type->id}}
                                                                active_maintenance_{{$maintenance->id}}_form"
                                                            data-toggle="collapse"
                                                            href="#collapseExample_{{$maintenance->id}}_services"
                                                            role="button"
                                                            {{in_array($maintenance->id, $card_invoice_parts_ids)? '':'disabled' }}
                                                            aria-expanded="false" aria-controls="collapseExample">
                                                            <i class="ico ico-left fa fa-gear"></i>
                                                            {{__('Services')}}
                                                        </button>
                                                        <button
                                                            class="btn hvr-rectangle-in resetAdd-wg-btn   maintenance_type_{{$maintenance_type->id}}
                                                                active_maintenance_{{$maintenance->id}}_form"
                                                            type="button" data-toggle="collapse"
                                                            data-target="#collapseExample_{{$maintenance->id}}_packages"
                                                            {{in_array($maintenance->id, $card_invoice_parts_ids)? '':'disabled' }}
                                                            aria-expanded="false"
                                                            aria-controls="collapseExample">
                                                            <i class="ico ico-left fa fa-gear"></i>
                                                            {{__('Packages')}}
                                                        </button>
                                                        <button
                                                            class="btn hvr-rectangle-in closeAdd-wg-btn   maintenance_type_{{$maintenance_type->id}}
                                                                active_maintenance_{{$maintenance->id}}_form"
                                                            type="button" data-toggle="collapse"
                                                            data-target="#collapseExample_{{$maintenance->id}}_parts"
                                                            {{in_array($maintenance->id, $card_invoice_parts_ids)? '':'disabled' }}
                                                            aria-expanded="false"
                                                            aria-controls="collapseExample">
                                                            <i class="ico ico-left fa fa-gear"></i>
                                                            {{__('Parts')}}
                                                        </button>
                                                    </p>

                                                    @php

                                                        $invoicePartType    = $card_invoice->types()
                                                        ->where('maintenance_detection_id',$maintenance->id)
                                                        ->where('type','Part')
                                                        ->first();

                                                        $invoiceServiceType = $card_invoice->types()
                                                        ->where('maintenance_detection_id',$maintenance->id)
                                                        ->where('type','Service')
                                                        ->first();

                                                        $invoicePackageType = $card_invoice->types()
                                                        ->where('maintenance_detection_id',$maintenance->id)
                                                        ->where('type','Package')
                                                        ->first();

                                                        // TOTAL OF MAINTENANCE
                                                        $invoice_service_items_total = $invoiceServiceType ?
                                                        $invoiceServiceType->items->sum('total_after_discount'):0;

                                                        $invoice_part_items_total = $invoicePartType ?
                                                        $invoicePartType->items->sum('total_after_discount'):0;

                                                        $invoice_package_items_total = $invoicePackageType ?
                                                        $invoicePackageType->items->sum('total_after_discount'):0;

                                                        $maintenance_total = $invoice_service_items_total +
                                                        $invoice_part_items_total + $invoice_package_items_total;
                                                        //END TOTAL OF MAINTENANCE
                                                    @endphp

                                                    {{-- SERVICES BUTTONS--}}
                                                    <div class="collapse"
                                                         id="collapseExample_{{$maintenance->id}}_services">
                                                        <div class="card card-body " style="padding: 10px;">
                                                            @include('admin.work_cards.invoices.services.services')
                                                        </div>
                                                    </div>

                                                    {{-- PACKAGES BUTTONS--}}
                                                    <div class="collapse"
                                                         id="collapseExample_{{$maintenance->id}}_packages">
                                                        <div class="card card-body " style="padding: 10px;">
                                                            @include('admin.work_cards.invoices.packages.packages')
                                                        </div>
                                                    </div>

                                                    {{-- PARTS BUTTONS--}}
                                                    <div class="collapse"
                                                         id="collapseExample_{{$maintenance->id}}_parts">
                                                        <div class="card card-body " style="padding: 10px;">
                                                            @include('admin.work_cards.invoices.parts.spareParts')
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <hr>
                                            </div>

                                            <input type="hidden" id="maintenance_{{$maintenance->id}}_total_cost"
                                                   class="type_{{$maintenance_type->id}}_maintenance_{{$index}}"
                                                   value="{{$maintenance_total}}">

                                            @php
                                                $maintenance_count++;
                                            @endphp
                                        @endforeach

                                        @include('admin.work_cards.invoices.total_maintenance_detection')
                                    </div>

                                    <input type="hidden" id="type_{{$maintenance_type->id}}_maintenance_count"
                                           value="{{$maintenance_count}}">
                                </div>
                            </div>
                        </div>
                        @php
                            $types_count++;
                        @endphp
                    @endforeach

                    <input type="hidden" id="maintenance_types_count" value="{{$types_count}}">
                </div>
            </div>


            <div class="col-lg-12">
                <div class="">


                </div>
            </div>

        </div>

    </div>


    @include('admin.work_cards.invoices.total_invoice_details')
    <div class="col-md-12">

        <div class="form-group col-sm-12">

            <input type="hidden" name="save_type" id="save_type">

            <button id="btnsave" type="submit" class="btn hvr-rectangle-in saveAdd-wg-btn"
                    onclick="saveAndPrint('save'); validation(); updateWinchRequest()">
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
                    onclick="saveAndPrint('save_and_print'); validation(); updateWinchRequest()">
                <i class="ico ico-left fa fa-print"></i>
                {{__('Save and print invoice')}}
            </button>
        </div>
    </div>
</div>


