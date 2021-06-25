<div class="form-group has-feedback col-sm-12" style="margin-top:30px">
    <table class="table table-bordered" style="width:100%">
        <thead>
        <tr>
            <th scope="col">{!! __('Total') !!}</th>
            <th scope="col">{!! __('Discount Type') !!}</th>
            <th scope="col">{!! __('Discount') !!}</th>
            <th scope="col">{!! __('Supplier Discount') !!}</th>
            <th scope="col">{!! __('Total After Discount') !!}</th>
            <th scope="col">
                <div class="btn-group ">
                    <span type="button" class="fa fa-usd  dropdown-toggle" data-toggle="dropdown"
                          style="background-color: rgb(244, 67, 54); color: white; padding: 3px; border-radius: 5px; cursor: pointer"
                          aria-haspopup="true" aria-expanded="false"> </span>

                    <ul class="dropdown-menu" style="margin-top: 19px;">
                        @if($taxes->count())
                            @foreach($taxes as $tax_key => $tax)

                                @php
                                    $tax_key +=1;
                                @endphp

                                <li>
                                    <a>
                                        <input type="checkbox" id="checkbox_tax_{{$tax_key}}" name="taxes[]"
                                               onclick="calculateTax()"
                                               {{!isset($purchaseQuotation) ? 'checked':''}}
                                               {{isset($purchaseQuotation) && in_array($tax->id, $purchaseQuotation->taxes->pluck('id')->toArray())? 'checked':'' }}
                                               data-tax-value="{{$tax->value}}"
                                               data-tax-type="{{$tax->tax_type}}"
                                               data-tax-execution-time="{{$tax->execution_time}}"
                                               value="{{$tax->id}}"
                                        >
                                        <span>
                                            {{$tax->name}} - {{$tax->tax_type == 'amount' ? '$':'%'}} - {{ $tax->value }} -
                                             <span id="calculated_tax_value_{{$tax_key}}">
                                                  {{isset($purchaseQuotation) ? taxValueCalculated($purchaseQuotation->total_after_discount, $purchaseQuotation->sub_total, $tax) : 0}}
                                             </span>
                                        </span>
                                    </a>
                                </li>
                            @endforeach
                        @else
                            <li>
                                <a>
                                    <span>{{ __('No Taxes Founded') }}</span>
                                </a>
                            </li>

                        @endif
                    </ul>

                    <input type="hidden" id="invoice_tax_count" value="{{$taxes->count()}}">
                </div>

                {!! __('Taxes') !!}
            </th>

            <th scope="col">
                <div class="btn-group ">
                    <span type="button" class="fa fa-usd  dropdown-toggle" data-toggle="dropdown"
                          style="background-color: rgb(244, 67, 54); color: white; padding: 3px; border-radius: 5px; cursor: pointer"
                          aria-haspopup="true" aria-expanded="false"> </span>

                    <ul class="dropdown-menu" style="margin-top: 19px;">
                        @if($additionalPayments->count())
                            @foreach($additionalPayments as $additional_key => $additionalPayment)

                                @php
                                    $additional_key +=1;
                                @endphp

                                <li>
                                    <a>
                                        <input type="checkbox" id="checkbox_additional_{{$additional_key}}" name="additional_payments[]"
                                               onclick="calculateTotal()"
                                               {{!isset($purchaseQuotation) ? 'checked':''}}
                                               {{isset($purchaseQuotation) && in_array($additionalPayment->id, $purchaseQuotation->taxes->pluck('id')->toArray())? 'checked':'' }}
                                               data-additional-value="{{$additionalPayment->value}}"
                                               data-additional-type="{{$additionalPayment->tax_type}}"
                                               data-additional-execution-time="{{$additionalPayment->execution_time}}"
                                               value="{{$additionalPayment->id}}"
                                        >
                                        <span>
                                            {{$additionalPayment->name}} -
                                            {{$additionalPayment->tax_type == 'amount' ? '$':'%'}} -
                                            {{ $additionalPayment->value }} -
                                             <span id="calculated_additional_value_{{$additional_key}}">
                                                 {{isset($purchaseQuotation) ? taxValueCalculated($purchaseQuotation->total_after_discount, $purchaseQuotation->sub_total, $additionalPayment ) : 0}}
                                             </span>
                                        </span>
                                    </a>
                                </li>
                            @endforeach
                        @else
                            <li>
                                <a>
                                    <span>{{ __('No Additional Founded') }}</span>
                                </a>
                            </li>

                        @endif
                    </ul>

                    <input type="hidden" id="invoice_additional_count" value="{{$additionalPayments->count()}}">
                </div>

                {!! __('Additional Payments') !!}
            </th>

            <th scope="col">{!! __('Final Total') !!}</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <input type="text" class="form-control" readonly name="sub_total" id="sub_total"
                       value="{{isset($purchaseQuotation) ? $purchaseQuotation->sub_total : 0}}">
            </td>
            <td>
                <div class="radio primary">
                    <input type="radio" name="discount_type" id="discount_type_amount"
                           {{isset($purchaseQuotation) && $purchaseQuotation->discount_type == 'amount' ? 'checked': '' }}
                           value="amount"
                           {{!isset($purchaseQuotation) ? 'checked':''}} onclick="calculateInvoiceDiscount()">
                    <label for="discount_type_amount">{{__('amount')}}</label>
                </div>

                <div class="radio primary">
                    <input type="radio" name="discount_type"
                           {{isset($purchaseQuotation) && $purchaseQuotation->discount_type == 'percent' ? 'checked': '' }}
                           id="discount_type_percent" value="percent"
                           onclick="calculateInvoiceDiscount()">
                    <label for="discount_type_percent">{{__('Percent')}}</label>
                </div>

            </td>

            <td>
                <input type="number" class="form-control"
                       value="{{isset($purchaseQuotation) ? $purchaseQuotation->discount : 0}}"
                       id="discount"
                       onchange="calculateInvoiceDiscount()"
                       onkeyup="calculateInvoiceDiscount()"
                       name="discount" min="0">
            </td>

            <td>

                <div class="row">
                    <div class="col-md-2" style="margin-top: 4px;">
                        <div class="form-group has-feedback">
                            <input type="checkbox" id="supplier_discount_check" name="supplier_discount_active" onclick="calculateTotal()"
                                {{isset($purchaseQuotation) && $purchaseQuotation->supplier_discount_active ? 'checked' : ''}}>
                        </div>
                    </div>

                    <div class="col-md-3">

                        <input type="text" disabled="disabled" class="form-control supplier_discount_type"
                               value="{{isset($purchaseQuotation) && $purchaseQuotation->supplier_discount_type == 'percent' ? '%' : '$'}}"
                               style="width: 42px;">

                        <input type="hidden" name="supplier_discount_type"  class="supplier_discount_type_value"
                               value="{{isset($purchaseQuotation) ? $purchaseQuotation->supplier_discount_type : 'amount'}}"
                        >
                    </div>

                    <div class="col-md-7">
                        <input type="number" name="supplier_discount" min="0" readonly="readonly"
                               class="form-control supplier_discount"
                               value="{{isset($purchaseQuotation) ? $purchaseQuotation->supplier_discount : 0}}"
                        >
                    </div>
                </div>

            </td>

            <td>
                <input type="text" class="form-control" readonly
                       value="{{isset($purchaseQuotation) ? $purchaseQuotation->total_after_discount : 0}}"
                       name="total_after_discount" id="total_after_discount">
            </td>

            <td>
                <input type="text" class="form-control" readonly name="tax" id="tax"
                       value=" {{isset($purchaseQuotation) ? $purchaseQuotation->tax : 0}}"
                >
            </td>

            <td>
                <input type="text" class="form-control" readonly name="additional_payments_value" id="additional_payments"
                       value=" {{isset($purchaseQuotation) ? $purchaseQuotation->additional_payments : 0}}"
                >
            </td>

            <td>
                <input type="text" class="form-control" readonly name="total" id="total"
                       value="{{isset($purchaseQuotation) ? $purchaseQuotation->total : 0}}">
            </td>
        </tr>
        </tbody>
    </table>
</div>
