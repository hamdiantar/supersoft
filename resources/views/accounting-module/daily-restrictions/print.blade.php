<div id="daily-restriction-printer" style="padding: 15px;" class="text-center">
    @include("admin.layouts.datatable-print")
    <div class="col-xs-4 col-sm-4 col-md-4">
        <table class="table table-bordered snd-table" style="margin:10px 0 !important;width:100%">
            <tbody>
                <tr>
                    <th scope="row"> {{ __('accounting-module.restriction-number') }} </th>
                </tr>
                <tr>
                    <td> {{ $model->restriction_number }} </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-xs-4 col-sm-4 col-md-4">
        <table class="table table-bordered snd-table" style="margin:10px 0 !important;width:100%">
            <tbody>
                <tr>
                    <th scope="row"> {{ __('accounting-module.operation-date') }} </th>
                </tr>
                <tr>
                    <td> {{ $model->operation_date }} </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-xs-4 col-sm-4 col-md-4">
        <table class="table table-bordered snd-table" style="margin:10px 0 !important;width:100%">
            <tbody>
                <tr>
                    <th scope="row"> {{ __('accounting-module.debit-amount') }} </th>
                </tr>
                <tr>
                    <td> {{ $model->debit_amount }} </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="clearfix"></div>
    <div class="col-xs-4 col-sm-4 col-md-4">
        <table class="table table-bordered snd-table" style="margin:10px 0 !important;width:100%">
            <tbody>   
                <tr>
                    <th scope="row">{{ __('accounting-module.operation-number') }}</th>
                </tr>
                <tr>
                    <td>{{ $model->operation_number }} </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-xs-4 col-sm-4 col-md-4">
        <table class="table table-bordered snd-table" style="margin:10px 0 !important;width:100%">
            <tbody>
                <tr>
                    <th scope="row">{{ __('accounting-module.records-number') }}</th>
                </tr>
                <tr>
                    <td>{{ $model->records_number }} </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-xs-4 col-sm-4 col-md-4">
        <table class="table table-bordered snd-table" style="margin:10px 0 !important;width:100%">
            <tbody>
                <tr>
                    <th scope="row">{{ __('accounting-module.credit-amount') }}</th>
                </tr>
                <tr>
                    <td>{{ $model->credit_amount }} </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-md-12 col-xs-12 col-sm-12">
        <table class="table table-striped table-bordered" style="margin:10px 0 !important;width:100%">
            <thead>
                <tr>
                    <th> {{ __('accounting-module.account-code') }} </th>
                    <th> {{ __('accounting-module.account-name') }} </th>
                    <th> {{ __('accounting-module.root-cost-center') }} </th>
                    <th> {{ __('accounting-module.cost-center') }} </th>
                    <th> {{ __('accounting-module.debit') }} </th>
                    <th> {{ __('accounting-module.credit') }} </th>
                    <th> {{ __('accounting-module.notes') }} </th>
                </tr>
            </thead>
            <tbody>
                @foreach($model->my_table as $row)
                    <tr>
                        <td>
                            {{ $row->account_tree_code }}
                        </td>
                        <td>
                            {{ $lang == 'ar' ? optional($row->account_tree)->name_ar : optional($row->account_tree)->name_en }}
                        </td>
                        <td>
                            @if($row->cost_center && !$row->cost_center_root && $row->cost_center->my_parent_cost)
                                {{ $lang == 'ar' ? $row->cost_center->my_parent_cost->name_ar : $row->cost_center->my_parent_cost->name_en }}
                            @elseif($row->cost_center_root)
                                {{ $lang == 'ar' ? optional($row->cost_center_root)->name_ar : optional($row->cost_center)->name_en }}
                            @else
                                {{ $row->cost_center_code.' '.($lang == 'ar' ? optional($row->cost_center)->name_ar : optional($row->cost_center)->name_en) }}
                            @endif
                        </td>
                        <td>
                            @if(optional($row->cost_center)->tree_level == 0)
                            @else
                                {{ $row->cost_center_code.' '.($lang == 'ar' ? optional($row->cost_center)->name_ar : optional($row->cost_center)->name_en) }}
                            @endif
                        </td>
                        <td>
                            {{ $row->debit_amount }}
                        </td>
                        <td>
                            {{ $row->credit_amount }}
                        </td>
                        <td>
                            {{ $row->notes }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="clearfix"></div>
<div class="modal-footer">
    <button type="button" onclick="modal_printer('daily-restriction-printer')"
        class="btn btn-primary"> {{ __('accounting-module.print') }} </button>
</div>