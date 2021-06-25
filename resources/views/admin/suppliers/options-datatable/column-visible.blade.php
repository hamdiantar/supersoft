<div id="colum-visible-modal" class="modal fade new-modal-wg" role="dialog">
    <div class="modal-dialog" style="width:175px;padding-top:100px">
        <div class="modal-content" style="overflow:hidden">
            <button class="col-md-12 columns-btns btn btn-default"
                onclick="hide_column(event ,'id')">
                #
            </button>
            <button class="col-md-12 columns-btns btn btn-default"
                onclick="hide_column(event ,'supplier-name')">
                {!! __('Supplier Name') !!}
            </button>
            <button class="col-md-12 columns-btns btn btn-default"
                onclick="hide_column(event ,'supplier-group')">
                {!! __('Supplier Group') !!}
            </button>
            <button class="col-md-12 columns-btns btn btn-default"
                onclick="hide_column(event ,'supplier-type')">
                {!! __('Supplier Type') !!}
            </button>
            <button class="col-md-12 columns-btns btn btn-default"
                onclick="hide_column(event ,'funds-for')">
                {!! __('Funds For') !!}
            </button>
            <button class="col-md-12 columns-btns btn btn-default"
                onclick="hide_column(event ,'funds-on')">
                {!! __('Funds On') !!}
            </button>
            <button class="col-md-12 columns-btns btn btn-default"
                onclick="hide_column(event ,'status')">
                {!! __('Status') !!}
            </button>
            <button class="col-md-12 columns-btns btn btn-default"
                onclick="hide_column(event ,'created-at')">
                {!! __('created at') !!}
            </button>
            <button class="col-md-12 columns-btns btn btn-default"
                onclick="hide_column(event ,'updated-at')">
                {!! __('updated at') !!}
            </button>
        </div>
    </div>
</div>