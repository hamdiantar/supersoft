<thead>
    <tr>
        <th class="text-center column-revenue-no" 
            onclick="appling_sort(event ,'revenue-no')"
            data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}"
            scope="col">
            {!! __('Revenue No') !!}
            <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('revenue-no') }}"></i>
        </th>
        <th class="text-center column-receiver" 
            onclick="appling_sort(event ,'receiver')"
            data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}"
            scope="col">
            {!! __('Receiver') !!}
            <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('receiver') }}"></i>
        </th>
        <th class="text-center column-revenue-item" 
            onclick="appling_sort(event ,'revenue-item')"
            data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}"
            scope="col">
            {!! __('Revenue Item') !!}
            <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('revenue-item') }}"></i>
        </th>
        <th class="text-center column-cost" 
            onclick="appling_sort(event ,'cost')"
            data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}"
            scope="col">
            {!! __('Cost') !!}
            <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('cost') }}"></i>
        </th>
        <th class="text-center column-deportation-method" 
            onclick="appling_sort(event ,'deportation-method')"
            data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}"
            scope="col">
            {!! __('Deportation Method') !!}
            <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('deportation-method') }}"></i>
        </th>
        <th class="text-center column-deportation" 
            onclick="appling_sort(event ,'deportation')"
            data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}"
            scope="col">
            {!! __('Deportation') !!}
            <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('deportation') }}"></i>
        </th>
        <th class="text-center column-payment-type" 
            onclick="appling_sort(event ,'payment-type')"
            data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}"
            scope="col">
            {!! __('Payment Type') !!}
            <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('payment-type') }}"></i>
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
        <th scope="col">
            {!! __('Options') !!}</th>
        <th scope="col">
        <div class="checkbox danger">
                <input type="checkbox"  id="select-all">
                <label for="select-all"></label>
            </div>{!! __('Select') !!}</th>
    </tr>
</thead>