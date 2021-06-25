<div class="form-group has-feedback col-sm-12">
    <h3 style="text-align: center;">{{__('Total Invoice')}}</h3>
    <table class="table table-bordered" style="width:100%">
        <thead>
        <tr>
            <th scope="col">{!! __('Invoice Type') !!} </th>
            <th scope="col">{!! __('Total') !!}</th>
            <th scope="col">{!! __('Discount Type') !!}</th>
            <th scope="col">{!! __('Discount') !!}</th>
            <th scope="col">{!! __('Customer Discount') !!}</th>

            <th scope="col">

                <a style="cursor:pointer" data-toggle="modal"
                   data-target="#pointsDiscount" title="{{__('Points Rules')}}">
                    <i class="fa fa-eye "
                       style="background-color:#F44336;color:white;padding:3px;border-radius:5px"></i>
                </a>

                {!! __('Points Discount') !!}
            </th>

            <th scope="col">{!! __('Total After Discount') !!}</th>
            <th scope="col">
                <a href="#" data-toggle="modal" onclick="implementInvoiceDiscount()" style="color: red;"
                   data-target="#invoice_taxes" title="Cars info">
                    <span class="fa fa-eye" style="background-color:#F44336;color:white;padding:3px;border-radius:5px"></span>
                </a>
                {!! __('Taxes') !!}
            </th>
            <th scope="col">{!! __('Final Total') !!}</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <div class="radio primary">
                    <input type="radio" name="type" id="type_cash" value="cash"
                        {{$work_card->cardInvoice ? '':'checked'}}
                        {{$work_card->cardInvoice && $work_card->cardInvoice->type == 'cash'? 'checked' : ''}}>
                    <label for="type_cash">{{__('Cash')}}</label>
                </div>

                <div class="radio primary">
                    <input type="radio" name="type" id="type_credit" value="credit"
                        {{$work_card->cardInvoice && $work_card->cardInvoice->type == 'credit'? 'checked' : ''}}
                    >
                    <label for="type_credit">{{__('Credit')}}</label>
                </div>
            </td>

            <td>
                <input type="text" class="form-control" readonly name="total_before_discount"
                       id="total_before_discount"
                       value="{{$work_card->cardInvoice ? $work_card->cardInvoice->sub_total : 0}}">
            </td>

            <td>
                <div class="radio primary">
                    <input type="radio" name="discount_type" checked
                           id="discount_type_amount" value="amount"
                           onclick="invoiceDiscount()"
                        {{$work_card->cardInvoice? '' : 'checked'}}
                        {{$work_card->cardInvoice && $work_card->cardInvoice->discount_type == 'amount' ? 'checked':''}}
                        >
                    <label for="discount_type_amount">{{__('Amount')}}</label>
                </div>

                <div class="radio primary">
                    <input type="radio" name="discount_type"
                           id="discount_type_percent" value="percent"
                           onclick="invoiceDiscount()"
                        {{$work_card->cardInvoice && $work_card->cardInvoice->discount_type == 'percent' ? 'checked':''}}
                    >
                    <label for="discount_type_percent">{{__('Percent')}}</label>
                </div>
            </td>

            <td>
                <input type="number" class="form-control" id="discount"
                       value="{{$work_card->cardInvoice ? $work_card->cardInvoice->discount : 0}}"
                       name="discount" min="0" onkeyup="invoiceDiscount()" onchange="invoiceDiscount()"
                >
            </td>

            <td>
                <div class="row">
                    <div class="col-md-1" style="margin-top: 10px;">
                        <input type="checkbox" class="form-control" id="checkbox_customer_discount"
                               onclick="invoiceDiscount()" name="customer_discount_check"
                            {{$work_card->cardInvoice && $work_card->cardInvoice->customer_discount_status ? 'checked' : ''}}
                        >
                        <label for="checkbox_customer_discount"></label>
                    </div>

                    <div class="col-md-4">

                        <input type="hidden" id="customer_discount_type" value="{{optional($work_card->customer->customerCategory)->services_discount_type}}">

                        <input type="text" class="form-control"
                               value="{{__(optional($work_card->customer->customerCategory)->services_discount_type)}}"
                               name="customer_discount" min="0" disabled
                        >
                    </div>

                    <div class="col-md-6">
                        <input type="number" class="form-control"
                               value="{{optional($work_card->customer->customerCategory)->services_discount}}"
                               id="customer_discount"
                               name="customer_discount" min="0" disabled
                        >
                    </div>
                </div>
            </td>

            <td>

                <input type="hidden" name="points_rule_id" value="{{$work_card->cardInvoice ? $work_card->cardInvoice->points_rule_id : ''}}" class="rule_id">

                <input type="hidden" name="customer_points" id="customer_points" value="0">

                <input type="text" class="form-control" readonly
                       value="{{$work_card->cardInvoice ? $work_card->cardInvoice->points_discount : 0}}"
                       name="points_discount" id="points_discount">
            </td>

            <td>
                <input type="text" class="form-control" readonly
                       value="{{$work_card->cardInvoice ? $work_card->cardInvoice->total_after_discount : 0  }}"
                       name="total_after_discount" id="total_after_discount"
                >
            </td>

            <td>
                <input type="text" class="form-control" readonly
                       name="invoice_tax" id="invoice_tax"
                       value="{{$work_card->cardInvoice ? $work_card->cardInvoice->tax : 0  }}">
            </td>

            <td>
                <input type="text" class="form-control" readonly value="{{$work_card->cardInvoice ? $work_card->cardInvoice->total : 0  }}"
                       name="final_total" id="final_total">
            </td>
        </tr>
        </tbody>
    </table>
</div>

<div class="col-md-12">
    <div class="form-group col-md-12">
        <div class="form-group">
            <label for="date" class="control-label">{{__('Terms And Conditions')}}</label>
            <textarea id="editor1" name="terms" cols="5" rows="5"
            >{{old('terms', $work_card->cardInvoice ? $work_card->cardInvoice->terms : '')}}</textarea>
        </div>
    </div>
</div>
