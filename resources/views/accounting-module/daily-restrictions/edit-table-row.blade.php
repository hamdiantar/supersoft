<tr>
    <td>
        <input data-for="tree-code" type="hidden" name="table_data[{{ $row->id }}][account_tree_code]"
            value="{{ $row->account_tree_code }}"/>
        <input data-for="cost-code" type="hidden" name="table_data[{{ $row->id }}][cost_center_code]"
            value="{{ $row->cost_center_code }}"/>
        <select style="width:100%" name="table_data[{{ $row->id }}][accounts_tree_id]"
            class="form-control select-2" onchange="account_name_n_code_equal(event ,true)">
            <option value=""> {{ __('accounting-module.select-one') }} </option>
            @foreach($accounts_tree as $acc)
                <option value="{{ $acc->id }}" {{ $row->accounts_tree_id == $acc->id ? 'selected' : '' }}
                    data-selected-id="{{ $acc->id }}" data-selected-code="{{ $acc->code }}">
                    {{ $acc->code }}
                </option>
            @endforeach
        </select>
    </td>
    <td>
        <select style="width:100%" class="form-control select-2"
            onchange="account_name_n_code_equal(event)">
            <option value=""> {{ __('accounting-module.select-one') }} </option>
            @foreach($accounts_tree as $acc)
                <option value="{{ $acc->id }}" {{ $row->accounts_tree_id == $acc->id ? 'selected' : '' }}
                    data-selected-id="{{ $acc->id }}" data-selected-code="{{ $acc->code }}">
                    {{ $acc->name }}
                </option>
            @endforeach
        </select>
    </td>
    <td>
        <input data-input-for="debit" class="form-control"
            onkeypress="return isNumber(event)" value="{{ $row->debit_amount ?: 0 }}"
            name="table_data[{{ $row->id }}][debit_amount]" onchange="debit_n_credit_equal(event)">
    </td>
    <td>
        <input data-input-for="credit" class="form-control"
            onkeypress="return isNumber(event)" value="{{ $row->credit_amount ?: 0 }}"
            name="table_data[{{ $row->id }}][credit_amount]" onchange="debit_n_credit_equal(event)">
    </td>
    <td>
        <select class="form-control"
            name="table_data[{{ $row->id }}][cost_center_root_id]"
            onchange="load_cost_branches(event ,{{ $row->cost_center_root_id }})">
            {!! \App\AccountingModule\Controllers\CostCenterCont::build_root_centers_options($row->cost_center_root_id) !!}
        </select>
    </td>
    <td>
        <select class="form-control cost-centers-select" name="table_data[{{ $row->id }}][cost_center_id]">
            {!! \App\AccountingModule\Controllers\CostCenterCont::build_centers_options($row->cost_center_id ,$row->cost_center_root_id) !!}
        </select>
    </td>
    <td>
        <input class="form-control" name="table_data[{{ $row->id }}][notes]" value="{{ $row->notes }}">
    </td>
    <td>
        <button type="button" class="btn btn-default" onclick="removeThisRow(event)">
            <i class="fa fa-times"></i>
        </button>
    </td>
</tr>