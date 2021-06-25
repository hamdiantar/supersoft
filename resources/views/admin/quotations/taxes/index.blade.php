<div class="remodal" data-remodal-id="m-2" role="dialog"
           aria-labelledby="modal1Title" aria-describedby="modal1Desc">
           <div class="card box-content-wg-new bordered-all primary">
     <div class="modal-header">
    <button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>

    <div class="remodal-content">
        <h2 class="box-title with-control" id="modal1Title">{{__('Invoice Taxes')}}</h2>

        <div class="form-group has-feedback col-sm-12">
            <table class="table table-bordered" style="width:100%">
                <thead>
                <tr>
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