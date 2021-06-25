<div id="colum-visible-modal" class="modal fade new-modal-wg" role="dialog">
    <div class="modal-dialog" style="width:175px;padding-top:100px">
        <div class="modal-content" style="overflow:hidden">
            @foreach($sort_by_columns as $col)
                @php
                    $column_name = implode("-" ,explode("_" ,$col));
                @endphp
                <button class="col-md-12 columns-btns btn btn-default"
                    onclick="hide_column(event ,'{{ $column_name }}')">
                    {{ __('accounting-module.'.$column_name) }}
                </button>
            @endforeach
            @if($show_transactions)
                @foreach($transaction_column as $col)
                    @php
                        $column_name = implode("-" ,explode("_" ,$col));
                    @endphp
                    <button class="col-md-12 columns-btns btn btn-default"
                        onclick="hide_column(event ,'{{ $column_name }}')">
                        {{ __('accounting-module.'.$column_name) }}
                    </button>
                @endforeach
            @endif
        </div>
    </div>
</div>