

<div class="modal fade"  id="boostrapModal-2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-1">
        <div class="modal-dialog modal-lg" role="document" >
            <div class="modal-content" >
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel-1">{{__('Customer Cars')}}</h4>
                </div>
                <div class="modal-body">

                    <table id="" class="table table-striped table-bordered display" style="width:100%">
                        <thead>
                        <tr>
                            <th scope="col">{!! __('#') !!}</th>
                            <th scope="col">{!! __('Type') !!}</th>
                            <th scope="col">{!! __('Plate Number') !!}</th>
                            <th scope="col">{!! __('Chassis Number') !!}</th>
                            <th scope="col">{!! __('Barcode') !!}</th>
                            <th scope="col">{!! __('Color') !!}</th>
                            <th scope="col">{!! __('Image') !!}</th>
                            <th scope="col">{!! __('Select') !!}</th>
                        </tr>
                        </thead>

                        <tfoot>
                        <tr>
                            <th scope="col">{!! __('#') !!}</th>
                            <th scope="col">{!! __('Type') !!}</th>
                            <th scope="col">{!! __('Plate Number') !!}</th>
                            <th scope="col">{!! __('Chassis Number') !!}</th>
                            <th scope="col">{!! __('Barcode') !!}</th>
                            <th scope="col">{!! __('Color') !!}</th>
                            <th scope="col">{!! __('Image') !!}</th>
                            <th scope="col">{!! __('Select') !!}</th>
                        </tr>
                        </tfoot>

                        <tbody id="customer_cars">
                            <tr id="customer_cars_loading" style="display: none;">
                                <td colspan="8" style="background:white;">
                                    <img src="{{asset('default-images/loading.gif')}}">
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">{{__('Close')}}</button>
                </div>
            </div>
        </div>
    </div>