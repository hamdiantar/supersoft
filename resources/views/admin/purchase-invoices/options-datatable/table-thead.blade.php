<thead>
    <tr>
        @foreach(\App\Http\Controllers\DataExportCore\Invoices\Purchase::get_my_view_columns() as $key => $value)
            <th class="text-center column-{{ $key }}"
                onclick="appling_sort(event ,'{{ $key }}')"
                data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}"
                scope="col">
                {{ $value }}
                <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort($key) }}"></i>
            </th>
        @endforeach

            <th class="text-center column-execution-status"
                scope="col">
                {!! __('Execution Status') !!}
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
        <th scope="col">{!! __('Expenses') !!}</th>
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
