
            <div class="col-xs-12">
    <div class="box-content card bordered-all js__card top-search">
        <h4 class="box-title with-control">
        <i class="fa fa-money"></i>   {{ __('Return Sales Invoice Payments') }}
        </h4>
 <div class="card-content js__card_conten">
 <table class="table table-responsive table-bordered table-striped">
                    <thead>
                    <tr>
                        <th scope="col">{!! __('Invoice Number') !!}</th>
                        <th scope="col">{!! __('Total') !!}</th>
                        <th scope="col">{!! __('Paid') !!}</th>
                        <th scope="col">{!! __('Remaining') !!}</th>
                        <th scope="col">{!! __('Invoice Type') !!}</th>
                    </tr>
                    </thead>
                    <tbody>

                    <tr style="color:black">
                        <td>{{$sales_invoice_return->inv_number}}</td>
                        <td class="text-danger">{{number_format($sales_invoice_return->total, 2)}}</td>
                        <td class="text-danger">{{number_format($sales_invoice_return->paid, 2)}}</td>
                        <td class="text-danger">{{number_format( $sales_invoice_return->remaining, 2)}}</td>
                        <td>
                        <span class="label label-warning wg-label">  
                        {{__($sales_invoice_return->type)}}
                        </span>
                        </td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
        <!-- /.card-content -->
    </div>
    <!-- /.box-content -->
</div>
