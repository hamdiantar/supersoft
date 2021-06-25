
<thead>
    <tr>
        <th class="text-center column-id" 
            onclick="appling_sort(event ,'id')"
            data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}"
            scope="col">
            {!! __('#') !!}
            <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('id') }}"></i>
        </th>
        <th class="text-center column-account-from" 
            onclick="appling_sort(event ,'account-from')"
            data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}"
            scope="col">
            {!! __('Account From') !!}
            <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('account-from') }}"></i>
        </th>
        <th class="text-center column-account-to" 
            onclick="appling_sort(event ,'account-to')"
            data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}"
            scope="col">
            {!! __('Account To') !!}
            <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('account-to') }}"></i>
        </th>
        <th class="text-center column-the-cost" 
            onclick="appling_sort(event ,'the-cost')"
            data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}"
            scope="col">
            {!! __('The Cost') !!}
            <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('the-cost') }}"></i>
        </th>
        <th class="text-center column-created-by" 
            onclick="appling_sort(event ,'created-by')"
            data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}"
            scope="col">
            {!! __('Created By') !!}
            <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('created-by') }}"></i>
        </th>
        <th class="text-center column-created-at" 
            onclick="appling_sort(event ,'created-at')"
            data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}"
            scope="col">
            {!! __('created at') !!}
            <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('created-at') }}"></i>
        </th>
        <th class="text-center column-updated-at" 
            onclick="appling_sort(event ,'updated-at')"
            data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}"
            scope="col">
            {!! __('Updated at') !!}
            <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('updated-at') }}"></i>
        </th>
        <th scope="col">
            {!! __('Options') !!}
        </th>
        {{-- <th scope="col">
            <div class="checkbox danger">
                <input type="checkbox" id="select-all">
                <label for="select-all"></label>
            </div>{!! __('Select') !!}</th> --}}
    </tr>
</thead>