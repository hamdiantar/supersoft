<div class="remodal" data-remodal-id="payment-2" role="dialog" aria-labelledby="modal1Title" aria-describedby="modal1Desc">

    <div class="card box-content-wg-new bordered-all primary">
        <div class="modal-header">
            <button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>

            <h2 class="box-title with-control" id="modal1Title">{{__('Payment Terms')}}</h2>

            <div class="form-group has-feedback col-sm-12">
                <table class="table table-bordered" style="width:100%">
                    <thead>
                    <tr>
                        <th scope="col">{!! __('Check') !!}</th>
                        <th scope="col">{!! __('Term') !!}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($paymentTerms as $term)
                        <tr>
                            <td>
                                <input type="checkbox" name="supply[]" value="{{$term->id}}">
                            </td>
                            <td>
                                <span>{{$term->term}}</span>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <input type="hidden" name="test" value="1">

            </div>
        </div>
    </div>
</div>
