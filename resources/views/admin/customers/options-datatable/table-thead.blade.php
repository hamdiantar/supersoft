<thead>
    <tr>
        <th class="text-center column-name" 
            onclick="appling_sort(event ,'name')"
            data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}"
            scope="col">
            {!! __('Name') !!}
            <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('name') }}"></i>
        </th>
        <th class="text-center column-customer-type" 
            onclick="appling_sort(event ,'customer-type')"
            data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}"
            scope="col">
            {!! __('Customer Type') !!}
            <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('customer-type') }}"></i>
        </th>
        <th class="text-center column-customer-category" 
            onclick="appling_sort(event ,'customer-category')"
            data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}"
            scope="col">
            {!! __('Customers Category') !!}
            <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('customer-category') }}"></i>
        </th>
        <th class="text-center column-status" 
            onclick="appling_sort(event ,'status')"
            data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}"
            scope="col">
            {!! __('Status') !!}
            <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('status') }}"></i>
        </th>
        <th class="text-center column-cars-number" 
            onclick="appling_sort(event ,'cars-number')"
            data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}"
            scope="col">
            {!! __('Cars Number') !!}
            <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('cars-number') }}"></i>
        </th>
        <th class="text-center column-balance-for" 
            onclick="appling_sort(event ,'balance-for')"
            data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}"
            scope="col">
            {!! __('Balance For') !!}
            <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('balance-for') }}"></i>
        </th>
        <th class="text-center column-balance-to" 
            onclick="appling_sort(event ,'balance-to')"
            data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}"
            scope="col">
            {!! __('Balance To') !!}
            <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('balance-to') }}"></i>
        </th>
        <th class="text-center column-created-at" 
            onclick="appling_sort(event ,'created-at')"
            data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}"
            scope="col">
            {!! __('Created At') !!}
            <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('created-at') }}"></i>
        </th>
        <th class="text-center column-updated-at" 
            onclick="appling_sort(event ,'updated-at')"
            data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}"
            scope="col">
            {!! __('Updated At') !!}
            <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('updated-at') }}"></i>
        </th>
        <th scope="col">{!! __('Options') !!}</th>
        <th scope="col">
            <div class="checkbox danger">
                <input type="checkbox"  id="select-all">
                <label for="select-all"></label>
            </div>
            {!! __('Select') !!}
        </th>
    </tr>
</thead>