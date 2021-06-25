<div id="colum-visible-modal" class="modal fade new-modal-wg" role="dialog">
    <div class="modal-dialog" style="width:175px;padding-top:100px">
        <div class="modal-content" style="overflow:hidden">
            <button class="col-md-12 columns-btns btn btn-default"
                onclick="hide_column(event ,'id')">
                {!! __('#') !!}
            </button>
            <button class="col-md-12 columns-btns btn btn-default"
                onclick="hide_column(event ,'name')">
                {!! __('Name') !!}
            </button>
            <button class="col-md-12 columns-btns btn btn-default"
                onclick="hide_column(event ,'operation_date')">
                {{ __('opening-balance.operation-date') }}
            </button>
            <button class="col-md-12 columns-btns btn btn-default"
                onclick="hide_column(event ,'serial_number')">
                {{ __('opening-balance.serial-number') }}
            </button>
            <button class="col-md-12 columns-btns btn btn-default"
                onclick="hide_column(event ,'total_money')">
                {{ __('opening-balance.total') }}
            </button>

            <button class="col-md-12 columns-btns btn btn-default"
                    onclick="hide_column(event ,'status')">
                {{ __('opening-balance.concession.status') }}
            </button>

            <button class="col-md-12 columns-btns btn btn-default"
                onclick="hide_column(event ,'created_at')">
                {!! __('Created At') !!}
            </button>
            <button class="col-md-12 columns-btns btn btn-default"
                onclick="hide_column(event ,'updated_at')">
                {!! __('Updated At') !!}
            </button>
        </div>
    </div>
</div>
