
<div class="col-md-8 col-sm-8 col-xs-12 buttons-wg wg-style text-right-wg">


<button class="dt-button buttons-collection buttons-colvis" onclick="invoke('print' ,event)">
            <i class="fa fa-print"></i> {{ __('accounting-module.printer') }}
        </button>
        <button class="dt-button buttons-collection buttons-colvis" onclick="invoke('excel' ,event)">
            <i class="fa fa-file-excel-o"></i> {{ __('accounting-module.export-excel') }}
        </button>
        <button class="dt-button buttons-collection buttons-colvis" data-remodal-target="colum-visible-modal">
            <i class="fa fa-tasks"></i> {{ __('accounting-module.column-visibility') }}
        </button>

عرض
        <select onchange="change_rows(event)" class="wg-select">>
            <option {{ isset($_GET['rows']) && $_GET['rows'] == 1 ? 'selected' : '' }} value="10">
                10
            </option>
            <option {{ isset($_GET['rows']) && $_GET['rows'] == 25 ? 'selected' : '' }} value="25">
                25
            </option>
            <option {{ isset($_GET['rows']) && $_GET['rows'] == 50 ? 'selected' : '' }} value="50">
                50
            </option>
            <option {{ isset($_GET['rows']) && $_GET['rows'] == 100 ? 'selected' : '' }} value="100">
                100
            </option>
        </select>
        قيم كل صفحه


    
</div>

<div class="col-md-4 col-sm-4 col-xs-12 text-left-wg" style="padding-right: 0;margin-top:10px">

     <div class="input-group  input-group-wg">
     <span class="input-group-btn">
            <button class="btn btn-default" style="height: 35px;padding: -7px -4px" type="button" onclick="free_search('#free-search' ,'key')">
                <i class="fa fa-search"></i>
            </button>
        </span>
       <input id="free-search" type="text" class="search-btn-wg"
        placeholder="{{ __('accounting-module.search') }} : {!! __('words.permission-number') !!},{!! __('words.operation-date') !!}"
            value="{{ isset($_GET['key']) ? $_GET['key'] : '' }}">

    </div>
</div>