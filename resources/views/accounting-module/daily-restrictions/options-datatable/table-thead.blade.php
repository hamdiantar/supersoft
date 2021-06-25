<thead>
    <tr>
        <th class="text-center column-restriction-number"
            onclick="appling_sort(event ,'restriction_number')"
            data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}">
            {{ __('accounting-module.restriction-number') }}
            <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('restriction_number') }}"></i>
        </th>
        <!-- <th class="text-center column-operation-number"
            onclick="appling_sort(event ,'operation_number')"
            data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}">
            {{ __('accounting-module.operation-number') }}
            <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('operation_number') }}"></i>
        </th> -->
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
        <th class="text-center column-records-number"
            onclick="appling_sort(event ,'records_number')"
            data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}">
            {{ __('accounting-module.records-number') }}
            <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('records_number') }}"></i>
        </th>
        <th class="text-center column-actions">
            {{ __('accounting-module.actions') }}
        </th>
    </tr>
</thead>