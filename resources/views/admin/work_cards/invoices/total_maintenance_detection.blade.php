<div class="form-group has-feedback col-sm-12"  id="quotation_total_details_{{$maintenance_type->id}}">
    <h3 style="text-align: center;">{{__('Total')}} {{$maintenance_type->name}}</h3>
    <table class="table table-bordered" style="width:100%">
        <thead>
        <tr>
            <th scope="col">{!! __('Total') !!}</th>
            <th scope="col">{!! __('Discount Type') !!}</th>
            <th scope="col">{!! __('Discount') !!}</th>
            <th scope="col">{!! __('Total After Discount') !!}</th>
{{--            <th scope="col">--}}
{{--                <a style="cursor:pointer" data-remodal-target="m-2" title="get taxes">--}}
{{--                    <i class="fa fa-eye " style="background-color:#F44336;color:white;padding:3px;border-radius:5px"></i>--}}
{{--                </a>--}}
{{--                {!! __('Taxes') !!}--}}
{{--            </th>--}}
{{--            <th scope="col">{!! __('Final Total') !!}</th>--}}
        </tr>
        </thead>
        <tbody>

        <tr>

            <td>
                <input type="text" class="form-control" readonly
                       name="maintenance_type_{{$maintenance_type->id}}[sub_total]"
                       id="total_before_discount_{{$maintenance_type->id}}_type"
                       value="{{$maintenance_type_pivot ? $maintenance_type_pivot->pivot->sub_total:0}}">
            </td>
            <td>

                <div class="radio primary">
                    <input type="radio" name="maintenance_type_{{$maintenance_type->id}}[discount_type]"
                           {{!$maintenance_type_pivot ? 'checked':''}}
                           {{$maintenance_type_pivot && $maintenance_type_pivot->pivot->discount_type == 'amount'?'checked':''}}
                           id="discount_type_amount_{{$maintenance_type->id}}_type" value="amount"
                           onclick="implementInvoiceDiscount({{$maintenance_type->id}})"
                    >
                    <label for="discount_type_amount_{{$maintenance_type->id}}_type">{{__('Amount')}}</label>
                </div>

                <div class="radio primary">
                    <input type="radio" name="maintenance_type_{{$maintenance_type->id}}[discount_type]"
                           {{$maintenance_type_pivot && $maintenance_type_pivot->pivot->discount_type == 'percent'?'checked':''}}
                           id="discount_type_percent_{{$maintenance_type->id}}_type" value="percent"
                           onclick="implementInvoiceDiscount({{$maintenance_type->id}})">
                    <label for="discount_type_percent_{{$maintenance_type->id}}_type">{{__('Percent')}}</label>
                </div>
            </td>
            <td>
                <input type="number" class="form-control"
                       value="{{$maintenance_type_pivot ? $maintenance_type_pivot->pivot->discount:0}}"
                       id="discount_{{$maintenance_type->id}}_type"
                       onchange="implementInvoiceDiscount({{$maintenance_type->id}})"
                       onkeyup="implementInvoiceDiscount({{$maintenance_type->id}})"
                       name="maintenance_type_{{$maintenance_type->id}}[discount]"
                       min="0">
            </td>
            <td>
                <input type="text" class="form-control maintenance_type_number_{{$index_one}}" readonly
                       value="{{$maintenance_type_pivot ? $maintenance_type_pivot->pivot->total_after_discount:0}}"
                       name="maintenance_type_{{$maintenance_type->id}}[total_after_discount]"
                       id="total_after_discount_{{$maintenance_type->id}}_type">
            </td>
{{--            <td>--}}
{{--                <input type="text" class="form-control" readonly--}}
{{--                       name="invoice_tax" id="invoice_tax_{{$maintenance_type->id}}_type"--}}
{{--                       value="0">--}}
{{--            </td>--}}
{{--            <td>--}}
{{--                <input type="text" class="form-control" readonly value="0"--}}
{{--                       name="final_total" id="final_total_{{$maintenance_type->id}}_type">--}}
{{--            </td>--}}
        </tr>
        </tbody>
    </table>
</div>
