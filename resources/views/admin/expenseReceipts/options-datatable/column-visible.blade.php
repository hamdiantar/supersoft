
<div class="remodal" data-remodal-id="colum-visible-modal" style="max-width: 190px;padding:0" role="dialog" aria-labelledby="modal1Title" aria-describedby="modal1Desc">
    <button data-remodal-action="close" class="remodal-close  new-modal-wg" aria-label="Close"></button>
    <div class="remodal-content" style="box-shadow:0 0 3px 1px grey;border-radius:5px;padding:10px;overflow:hidden">
        <button class="col-md-12 columns-btns btn btn-default" style="margin-bottom: 5px"
            onclick="hide_column(event ,'expense-no')">
            {!! __('Expense No') !!}
        </button>
        <button class="col-md-12 columns-btns btn btn-default" style="margin-bottom: 5px"
            onclick="hide_column(event ,'receiver')">
            {!! __('Receiver') !!}
        </button>
        <button class="col-md-12 columns-btns btn btn-default" style="margin-bottom: 5px"
            onclick="hide_column(event ,'expense-item')">
            {!! __('Expense Item') !!}
        </button>
        <button class="col-md-12 columns-btns btn btn-default" style="margin-bottom: 5px"
            onclick="hide_column(event ,'cost')">
            {!! __('Cost') !!}
        </button>
        <button class="col-md-12 columns-btns btn btn-default" style="margin-bottom: 5px"
            onclick="hide_column(event ,'deportation-method')">
            {!! __('Deportation Method') !!}
        </button>
        <button class="col-md-12 columns-btns btn btn-default" style="margin-bottom: 5px"
            onclick="hide_column(event ,'deportation')">
            {!! __('Deportation') !!}
        </button>
        <button class="col-md-12 columns-btns btn btn-default" style="margin-bottom: 5px"
            onclick="hide_column(event ,'payment-type')">
            {!! __('Payment Type') !!}
        </button>
        <button class="col-md-12 columns-btns btn btn-default" style="margin-bottom: 5px"
            onclick="hide_column(event ,'created-at')">
            {!! __('Created At') !!}
        </button>
        <button class="col-md-12 columns-btns btn btn-default" style="margin-bottom: 5px"
            onclick="hide_column(event ,'updated-at')">
            {!! __('Updated At') !!}
        </button>
    </div>
</div>