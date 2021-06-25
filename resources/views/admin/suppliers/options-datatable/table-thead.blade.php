<thead>
    <tr>
        <th class="text-center column-id"
            onclick="appling_sort(event ,'id')"
            data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}"
            scope="col">
            #
            <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('id') }}"></i>
        </th>
        <th class="text-center column-supplier-name"
            onclick="appling_sort(event ,'supplier_name')"
            data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}"
            scope="col">
            {!! __('Supplier Name') !!}
            <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('supplier_name') }}"></i>
        </th>
        <th class="text-center column-supplier-group"
            onclick="appling_sort(event ,'supplier_type')"
            data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}"
            scope="col">
            {!! __('Supplier Type') !!}
            <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('supplier_type') }}"></i>
        </th>
        <th class="text-center column-supplier-type"
            onclick="appling_sort(event ,'supplier_type')"
            data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}"
            scope="col">
            {!! __('Supplier Type') !!}
            <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('supplier_type') }}"></i>
        </th>
        <th class="text-center column-funds-for"
            onclick="appling_sort(event ,'funds_for')"
            data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}"
            scope="col">
            {!! __('Funds For') !!}
            <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('funds_for') }}"></i>
        </th>
        <th class="text-center column-funds-on"
            onclick="appling_sort(event ,'funds_on')"
            data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}"
            scope="col">
            {!! __('Funds On') !!}
            <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('funds_on') }}"></i>
        </th>
        <th class="text-center column-status"
            onclick="appling_sort(event ,'status')"
            data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}"
            scope="col">
            {!! __('Status') !!}
            <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('status') }}"></i>
        </th>
        <th class="text-center column-created-at"
            onclick="appling_sort(event ,'created_at')"
            data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}"
            scope="col">
            {!! __('created at') !!}
            <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('created_at') }}"></i>
        </th>
        <th class="text-center column-updated-at"
            onclick="appling_sort(event ,'updated_at')"
            data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}"
            scope="col">
            {!! __('Updated at') !!}
            <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('updated_at') }}"></i>
        </th>
        <th scope="col">{!! __('Options') !!}</th>
        <th scope="col">
            <div class="checkbox danger">
                <input type="checkbox" id="select-all">
                <label for="select-all"></label>
            </div>{{__('Select')}}
        </th>
    </tr>
</thead>
