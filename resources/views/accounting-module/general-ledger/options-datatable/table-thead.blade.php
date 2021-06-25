<thead>
    <tr>
        <th class="text-center column-restriction-number"
            onclick="appling_sort(event ,'restriction_number')"
            data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}">
            {{ __('accounting-module.restriction-number') }}
            <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('restriction_number') }}"></i>
        </th>
        <th class="text-center column-operation-date"
            onclick="appling_sort(event ,'operation_date')"
            data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}">
            {{ __('accounting-module.operation-date') }}
            <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('operation_date') }}"></i>
        </th>
        <th class="text-center column-debit-amount"
            onclick="appling_sort(event ,'debit_amount')"
            data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}">
            {{ __('accounting-module.debit-amount') }}
            <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('debit_amount') }}"></i>
        </th>
        <th class="text-center column-credit-amount"
            onclick="appling_sort(event ,'credit_amount')"
            data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}">
            {{ __('accounting-module.credit-amount') }}
            <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('credit_amount') }}"></i>
        </th>
        <th class="text-center column-balance"
            onclick="appling_sort(event ,'balance')"
            data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}">
            {{ __('accounting-module.balance') }}
            <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('balance') }}"></i>
        </th>
        <th class="text-center column-account-name"
            onclick="appling_sort(event ,'account_name')"
            data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}">
            {{ __('accounting-module.account-name') }}
            <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('account_name') }}"></i>
        </th>
        <th class="text-center column-account-code"
            onclick="appling_sort(event ,'account_code')"
            data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}">
            {{ __('accounting-module.account-code') }}
            <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('account_code') }}"></i>
        </th>
        @if($show_cost_center)
            <th class="text-center column-cost-center"
                onclick="appling_sort(event ,'cost_center')"
                data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}">
                {{ __('accounting-module.cost-center') }}
                <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('cost_center') }}"></i>
            </th>
        @endif
    </tr>
</thead>