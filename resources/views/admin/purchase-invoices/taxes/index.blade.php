<div class="modal fade" id="taxes-data" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class=" card box-content-wg-new bordered-all primary">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="box-title with-control" style="text-align: initial"
                        id="myModalLabel-1">{{__('Taxes Values')}}
                    </h4>
                </div>
                <div class="modal-body">
                <table class="table table-bordered" style="width:100%">
                    <thead>
                    <tr>
                        <th scope="col">{!! __('Check') !!}</th>
                        <th scope="col">{!! __('Name') !!}</th>
                        <th scope="col">{!! __('Type') !!}</th>
                        <th scope="col">{!! __('value') !!}</th>
                        <th scope="col">{!! __('value of total after discount') !!}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($taxes as $index=>$tax)
                        <tr>
                            <td>

                                <input type="checkbox" id="tax_check_{{$index+1}}" name="taxes[{{$tax->id}}][value]" value="{{$tax->id}}"
                                       onclick="selectTaxes('{{$index+1}}')" checked
                                    {{!auth()->user()->can('purchase_invoices_active_tax') ? 'disabled':''}}>
                            </td>

                            <td>
                                <input type="text" class="form-control" disabled
                                       id="tax_name_{{$index+1}}" value="{{$tax->name}}">
                            </td>
                            <td>
                                <input type="text" class="form-control" disabled value="{{__($tax->tax_type)}}">
                                <input type="hidden" class="form-control" disabled
                                       id="tax_type_{{$index+1}}" value="{{$tax->tax_type}}">
                            </td>
                            <td>
                                <input type="text" class="form-control" disabled
                                       id="tax_value_{{$index+1}}" value="{{$tax->value}}">
                            </td>
                            <td>
                                <input type="text" class="form-control" disabled
                                       id="tax_value_after_discount_{{$index+1}}" value="0">
                            </td>
                        </tr>
                    @endforeach
                    <input type="hidden" name="tax_count" id="tax_count" value="{{$taxes->count()}}">
                    </tbody>
                </table>
                </div>

            </div>
        </div>
    </div>
</div>
