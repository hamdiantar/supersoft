<div class="form-group has-feedback col-sm-12" style="display: {{isset($quotation) && $quotation ? '':'none'}}"
     id="quotation_total_details">

    <table class="table table-bordered" style="width:100%">
        <thead>
        <tr>
            <th scope="col">{!! __('Total') !!}</th>
            <th scope="col">{!! __('Discount Type') !!}</th>
            <th scope="col">{!! __('Discount') !!}</th>
            <th scope="col">{!! __('Total After Discount') !!}</th>
            <th scope="col">
{{--                <a style="cursor:pointer" onclick="implementInvoiceDiscount()" title="Refresh taxes">--}}
{{--                    <i class="fa fa-refresh" style="color:#00bf4f"></i>--}}
{{--                </a>--}}
{{--                <span> - </span>--}}
                <a style="cursor:pointer" data-remodal-target="m-2" onclick="implementInvoiceDiscount()" title="get taxes">
                    <i class="fa fa-eye " style="background-color:#F44336;color:white;padding:3px;border-radius:5px"></i>
                </a>

                {!! __('Taxes') !!}
            </th>
            <th scope="col">{!! __('Final Total') !!}</th>
        </tr>
        </thead>
        <tbody>
            
        <tr>

            <td>
                <input type="text" class="form-control" readonly name="total_before_discount"
                       id="total_before_discount" value="{{isset($quotation) ? $quotation->sub_total : 0}}">
            </td>
            <td>
                <div class="radio primary">
                    <input type="radio" name="discount_type" id="discount_type_amount" value="amount" {{isset($quotation) ? '':'checked'}}
                           onclick="implementInvoiceDiscount()"
                            {{isset($quotation) && $quotation->discount_type == 'amount'? "checked" : ""}}>
                    <label for="discount_type_amount"> {{__('Amount')}}</label>
                </div>
                <div class="radio primary">
                    <input type="radio" name="discount_type" id="discount_type_percent" value="percent"
                           onclick="implementInvoiceDiscount()" {{isset($quotation) && $quotation->discount_type == 'percent'? "checked" : ""}}>
                    <label for="discount_type_percent">{{__('Percent')}}</label>
                </div>
                <!--
{{--                <select name="discount_type" id="invoice_discount_type" onchange="implementInvoiceDiscount()"--}}
{{--                        class="form-control js-example-basic-single">--}}
{{--                    <option value="amount" {{isset($quotation) && $quotation->discount_type == 'amount'? "selected" : ""}}>--}}
{{--                        {{__('Amount')}}--}}
{{--                    </option>--}}
{{--                    <option value="percent" {{isset($quotation) && $quotation->discount_type == 'percent'? "selected" : ""}}>--}}
{{--                        {{__('Percent')}}--}}
{{--                    </option>--}}
{{--                </select>--}} -->
            </td>
            <td>
                <input type="number" class="form-control" value="{{isset($quotation) ? $quotation->discount : 0}}"
                       id="discount"
                       onchange="implementInvoiceDiscount()"
                       onkeyup="implementInvoiceDiscount()"
                       name="discount" min="0">
            </td>
            <td>
                <input type="text" class="form-control" readonly
                       value="{{isset($quotation) ? $quotation->total_after_discount : 0}}"
                       name="total_after_discount" id="total_after_discount">
            </td>
            <td>
                <input type="text" class="form-control" readonly
                       name="invoice_tax"
                       id="invoice_tax"
                       value="{{isset($quotation) ? $quotation->tax : 0}}">
            </td>
            <td>
                <input type="text" class="form-control" readonly
                       value="{{isset($quotation) ? $quotation->total : 0}}"
                       name="final_total" id="final_total">
            </td>
        </tr>
        </tbody>
    </table>
</div>