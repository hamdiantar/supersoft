<thead style="cursor: pointer">
    <tr>
        <th class="text-center column-id"
            onclick="appling_sort(event ,'id')"
            data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}"
            scope="col">
            {!! __('#') !!}
            <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('id') }}"></i>
        </th>

        @if(authIsSuperAdmin())
            <th class="text-center column-name"
                onclick="appling_sort(event ,'branch')"
                data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}"
                scope="col">
                {{ __('opening-balance.branch') }}
                <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('branch') }}"></i>
            </th>
        @endif

        <th class="text-center column-operation_date"
            onclick="appling_sort(event ,'operation_date')"
            data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}"
            scope="col">
            {{ __('opening-balance.operation-date') }}
            <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('operation_date') }}"></i>
        </th>

        <th class="text-center column-serial_number"
            onclick="appling_sort(event ,'serial_number')"
            data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}"
            scope="col">
            {{ __('opening-balance.serial-number') }}
            <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('serial_number') }}"></i>
        </th>

        <th class="text-center column-total_money"
            onclick="appling_sort(event ,'total_money')"
            data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}"
            scope="col">
            {{ __('opening-balance.total') }}
            <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('total_money') }}"></i>
        </th>

        <th class="text-center column-status"
{{--            onclick="appling_sort(event ,'total_money')"--}}
{{--            data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}"--}}
            scope="col">
            {{ __('opening-balance.status') }}
        </th>

        <th class="text-center column-created_at"
            onclick="appling_sort(event ,'created-at')"
            data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}"
            scope="col">
            {!! __('Created At') !!}
            <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('created-at') }}"></i>
        </th>

        <th class="text-center column-updated_at"
            onclick="appling_sort(event ,'updated-at')"
            data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}"
            scope="col">
            {!! __('Updated At') !!}
            <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('updated-at') }}"></i>
        </th>

        <th scope="col">{!! __('Options') !!}</th>
        <th scope="col">
            <div class="checkbox danger">
                <input type="checkbox" id="select-all">
                <label for="select-all"></label>
            </div>{!! __('Select') !!}
        </th>
    </tr>
</thead>
