

    <div id="winch_section">
        <div id="ajax_winch_box" >
            <div class="row">
                <div class="row"  id="winch_map">
                    <div class="col-xs-12">

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 products-details-wg">
                            <div class="widget-content widget-content-area h-100 br-4 p-0">
                                <div class="top-exchange-rate">

                                    <div class="top-exchange-rate-title">
                                        <h6>{{__('Select Location')}}</h6>
                                    </div>

                                    <div class="top-exchange-rate-body">

                                        <div class="modal-body" id="map" style="height: 400px;" onclick="getWinchDistance()">

                                        </div>

                                    </div>
                                </div>

                            </div>

                            <div class="row">

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>{{__('Longitude')}}</label>
                                        <input type="text" name="request_long" required id="lng" class=" form-control" readonly
                                        value="{{isset($data['cardInvoiceWinchType']) && $data['cardInvoiceWinchType']->cardInvoiceWinchRequest ? $data['cardInvoiceWinchType']->cardInvoiceWinchRequest->request_long:''}}">
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>{{__('Latitude')}}</label>
                                        <input type="text" name="request_lat" required id="lat" class=" form-control" readonly
                                               value="{{isset($data['cardInvoiceWinchType']) && $data['cardInvoiceWinchType']->cardInvoiceWinchRequest ? $data['cardInvoiceWinchType']->cardInvoiceWinchRequest->request_lat:''}}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 products-details-wg">
                            <div class="form-group has-feedback">
                                <div class="anyClass-wg table-responsive">
                                    <table class="table table-bordered" style="width:100%">

                                        <thead style="background-color: #ada3a361">
                                        <tr>
                                            <!-- <th scope="col">{!! __('Branch lat') !!}</th>
                                            <th scope="col">{!! __('Branch long') !!}</th> -->
                                            <th scope="col">{!! __('Active') !!}</th>
                                            <th scope="col">{!! __('Distance (km)') !!}</th>
                                            <th scope="col">{!! __('price (km)') !!}</th>
                                            <th scope="col">{!! __('Discount Type') !!}</th>
                                            <th scope="col">{!! __('Discount') !!}</th>
                                            <th scope="col">{!! __('Total') !!}</th>
                                            <th scope="col">{!! __('Total After Discount') !!}</th>
{{--                                            <th scope="col">{!! __('Action') !!}</th>--}}
                                        </tr>
                                        </thead>
                                        <tbody >

                                        <tr>
                                          @include('admin.work_cards.invoices.sample.winch.winch_row')
                                        </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>

