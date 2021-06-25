<tr>
    <td>
        <input value="{{ $account->account_code }}" type="hidden" name="table_data[{{ $count }}][account_tree_code]"/>
        <input value="{{ $account->account_id }}" type="hidden" name="table_data[{{ $count }}][accounts_tree_id]"/>
        <input value="{{ $account->account_code }}" class="form-control" disabled/>
    </td>
    <td>
        <input value="{{ $account->account_name }}" class="form-control" disabled/>
    </td>
    <td>
        <input type="hidden" name="table_data[{{ $count }}][debit_amount]" value="{{ $account->credit_balance }}"/>
        <input disabled class="form-control" value="{{ $account->credit_balance }}">
    </td>
    <td>
        <input type="hidden" name="table_data[{{ $count }}][credit_amount]" value="{{ $account->debit_balance }}"/>
        <input disabled class="form-control" value="{{ $account->debit_balance }}">
    </td>
    <td>
        <select class="form-control"
            name="table_data[{{ $count }}][cost_center_root_id]"
            onchange="load_cost_branches(event)">
            {!! \App\AccountingModule\Controllers\CostCenterCont::build_root_centers_options(NULL ,auth()->user()->branch_id) !!}
        </select>
    </td>
    <td>
        <select class="form-control cost-centers-select" name="table_data[{{ $count }}][cost_center_id]">
            {!! \App\AccountingModule\Controllers\CostCenterCont::build_centers_options(NULL ,NULL ,1 ,false ,auth()->user()->branch_id) !!}
        </select>
    </td>
</tr>