<div class="row">

    <div class=" form-group col-md-12">
        <div class="row">

            {{--  INVOICE NUMBER  --}}
            <div class="form-group  col-md-4">
                <label for="invoice_number" class="control-label">{{__('Invoice Number')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-file-text-o"></li></span>
                    <input type="text" name="invoice_number" class="form-control" id="invoice_number"
                           placeholder="{{__('Invoice Number')}}"
                           value="{{$work_card->cardInvoice ? $work_card->cardInvoice->inv_number : ''}}" disabled>
                </div>

                {{input_error($errors,'invoice_number')}}

                <input type="hidden" name="work_card_id" value="{{$work_card->id}}">
            </div>

            {{--  DATE  --}}
            <div class="form-group col-md-4">
                <label for="date" class="control-label">{{__('Date')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-clock-o"></li></span>
                    <input type="date" name="date" class="form-control"
                           value="{{$work_card->cardInvoice ? $work_card->cardInvoice->date :  now()->format('Y-m-d')}}"
                           id="date" placeholder="{{__('Date')}}">
                </div>

                {{input_error($errors,'date')}}
            </div>

            {{--  TIME  --}}
            <div class="form-group col-md-4">
                <div class="form-group">
                    <label for="type_en" class="control-label">{{__('Time')}}</label>
                    <div class="input-group">
                        <span class="input-group-addon"><li class="fa fa-clock-o"></li></span>
                        <input type="time" name="time" class="form-control"
                               value="{{$work_card->cardInvoice ? $work_card->cardInvoice->time : now()->format('h:i')}}" id="time" placeholder="{{__('Time')}}"
                        >
                    </div>
                    {{input_error($errors,'time')}}
                </div>
            </div>

        </div>
    </div>


    <div class="col-md-12">

        <div class="form-group ">
            <label for="date" class="control-label">{{__('Maintenance Types')}}</label>

            {{-- SERVICES BUTTONS--}}
            <div class="form-group col-md-12">
                {{-- SERVICES BUTTONS--}}
                <div>
                    <p>
                        <button class="btn hvr-rectangle-in saveAdd-wg-btn maintenance_type_active_maintenance_form"
                                data-toggle="collapse"
                                href="#collapseExample_services"
                                role="button"
                                aria-expanded="false" aria-controls="collapseExample">
                            <i class="ico ico-left fa fa-gear"></i>
                            {{__('Services')}}
                        </button>

                        <button class="btn hvr-rectangle-in resetAdd-wg-btn  maintenance_type_active_maintenance_form"
                                type="button" data-toggle="collapse"
                                data-target="#collapseExample_packages"
                                aria-expanded="false"
                                aria-controls="collapseExample">
                            <i class="ico ico-left fa fa-gear"></i>
                            {{__('Packages')}}
                        </button>

                        <button class="btn hvr-rectangle-in closeAdd-wg-btn  maintenance_type_active_maintenance_form"
                                type="button" data-toggle="collapse"
                                data-target="#collapseExample_parts"
                                aria-expanded="false"
                                aria-controls="collapseExample">
                            <i class="ico ico-left fa fa-gear"></i>
                            {{__('Parts')}}
                        </button>

                        <button class="btn hvr-rectangle-in closeAdd-wg-btn  maintenance_type_active_maintenance_form" type="button"
                                data-toggle="modal" data-target="#winch_info_data" title="Cars info">
                            <i class="ico ico-left fa fa-gear"></i>
                            {{__('Winch')}}
                        </button>
                    </p>

                    {{-- SERVICES BUTTONS--}}
                    <div class="collapse"
                         id="collapseExample_services">
                        <div class="card card-body " style="padding: 10px;">
                            @include('admin.work_cards.invoices.sample.services.services')
                        </div>
                    </div>

                    {{-- PACKAGES BUTTONS--}}
                    <div class="collapse"
                         id="collapseExample_packages">
                        <div class="card card-body " style="padding: 10px;">
                            @include('admin.work_cards.invoices.sample.packages.packages')
                        </div>
                    </div>

                    {{-- PARTS BUTTONS--}}
                    <div class="collapse"
                         id="collapseExample_parts">
                        <div class="card card-body " style="padding: 10px;">
                            @include('admin.work_cards.invoices.sample.parts.spareParts')
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    @include('admin.work_cards.invoices.sample.total_invoice_details')

    {{-- FORM BUTTONS   --}}
    <div class="col-md-12">

        <div class="form-group col-sm-12">

            <input type="hidden" name="save_type" id="save_type">

            <button id="btnsave" type="submit" class="btn hvr-rectangle-in saveAdd-wg-btn" onclick="saveAndPrint('save'); updateWinchRequest()">
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

            <button type="submit" class="btn hvr-rectangle-in saveAdd-wg-btn" onclick="saveAndPrint('save_and_print'); updateWinchRequest()">
                <i class="ico ico-left fa fa-print"></i>
                {{__('Save and print')}}
            </button>
        </div>
    </div>
</div>


