<div id="colum-visible-modal" class="modal fade new-modal-wg" role="dialog">
    <div class="modal-dialog" style="width:175px;padding-top:100px">
        <div class="modal-content" style="overflow:hidden">
            <button class="col-md-12 columns-btns btn btn-default"
                onclick="hide_column(event ,'restriction-number')">
                {{ __('accounting-module.restriction-number') }}
            </button>
            <button class="col-md-12 columns-btns btn btn-default"
                onclick="hide_column(event ,'operation-date')">
                {{ __('accounting-module.operation-date') }}
            </button>
            <button class="col-md-12 columns-btns btn btn-default"
                onclick="hide_column(event ,'debit-amount')">
                {{ __('accounting-module.debit-amount') }}
            </button>
            <button class="col-md-12 columns-btns btn btn-default"
                onclick="hide_column(event ,'credit-amount')">
                {{ __('accounting-module.credit-amount') }}
            </button>
            <button class="col-md-12 columns-btns btn btn-default"
                onclick="hide_column(event ,'balance')">
                {{ __('accounting-module.balance') }}
            </button>
            <button class="col-md-12 columns-btns btn btn-default"
                onclick="hide_column(event ,'account-name')">
                {{ __('accounting-module.account-name') }}
            </button>
            <button class="col-md-12 columns-btns btn btn-default"
                onclick="hide_column(event ,'account-code')">
                {{ __('accounting-module.account-code') }}
            </button>
            @if($show_cost_center)
                <button class="col-md-12 columns-btns btn btn-default"
                    onclick="hide_column(event ,'cost-center')">
                    {{ __('accounting-module.cost-center') }}
                </button>
            @endif
        </div>
    </div>
</div>