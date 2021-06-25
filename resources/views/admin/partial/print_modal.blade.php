<div class="modal fade" id="boostrapModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel-1">{{$title}}</h4>
            </div>

            <div class="modal-body" id="data_to_print">


            </div>

            <div class="modal-footer" style="text-align:center">

                <button type="button" class="btn btn-primary waves-effect waves-light" onclick="printDownPayment()"
                        id="print_sales_invoice">
                    <i class='fa fa-print'></i>
                    {{__('Print')}}
                </button>

                <button type="button" class="btn btn-danger waves-effect waves-light" data-dismiss="modal">
                    <i class='fa fa-close'></i>
                    {{__('Close')}}</button>
            </div>

        </div>
    </div>
</div>
