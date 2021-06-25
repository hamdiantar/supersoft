
<div class="remodal new-modal-wg" data-remodal-id="colum-visible-modal" style="max-width: 190px;padding:0" role="dialog" aria-labelledby="modal1Title" aria-describedby="modal1Desc">
    <button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>
    <div class="remodal-content" style="box-shadow:0 0 3px 1px grey;border-radius:5px;padding:10px;overflow:hidden">
        @foreach(\App\Http\Controllers\DataExportCore\Invoices\SalesReturn::get_my_view_columns() as $key => $value)
            <button class="col-md-12 columns-btns btn btn-default" style="margin-bottom: 5px"
                onclick="hide_column(event ,'{{ $key }}')">
                {{ $value }}
            </button>
        @endforeach
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