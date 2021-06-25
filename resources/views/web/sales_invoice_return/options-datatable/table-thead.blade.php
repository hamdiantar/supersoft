<thead>
    <tr>
        @foreach(\App\Http\Controllers\DataExportCore\CustomerPanel\SalesReturn::get_my_view_columns() as $key => $value)
            @if ($key == 'payment' || $key == 'paid' || $key == 'remaining')
            <th class="text-center column-{{ $key }}" scope="col"> {{ $value }} </th>
            @else
                <th class="text-center column-{{ $key }}" 
                    onclick="appling_sort(event ,'{{ $key }}')"
                    data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}"
                    scope="col">
                    {{ $value }}
                    <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort($key) }}"></i>
                </th>
            @endif
        @endforeach
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
    </tr>
</thead>