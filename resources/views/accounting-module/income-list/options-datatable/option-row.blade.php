
<div class="buttons-wg">
    <button class="dt-button buttons-collection buttons-colvis" onclick="invoke('print' ,event)"
        {{ user_can_access_accounting_module(NULL ,'income-list' ,'print') ? '' : 'disabled' }}>
        <i class="fa fa-print"></i> {{ __('accounting-module.printer') }}
    </button>
    <button class="dt-button buttons-collection buttons-colvis" onclick="invoke('excel' ,event)"
        {{ user_can_access_accounting_module(NULL ,'income-list' ,'print') ? '' : 'disabled' }}>
        <i class="fa fa-file-excel-o"></i> {{ __('accounting-module.export-excel') }}
    </button>
</div>