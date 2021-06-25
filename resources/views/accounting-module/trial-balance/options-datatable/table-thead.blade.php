<thead>
    <tr>
        @foreach($sort_by_columns as $col)
            @php
                $column_name = implode("-" ,explode("_" ,$col));
            @endphp
            <th class="text-center column-{{ $column_name }}"
                onclick="appling_sort(event ,'{{ $col }}')"
                data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}">
                {{ __('accounting-module.'.$column_name) }}
                <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort($col) }}"></i>
            </th>
        @endforeach
        @if($show_transactions)
            @foreach($transaction_column as $col)
                @php
                    $column_name = implode("-" ,explode("_" ,$col));
                @endphp
                <th class="text-center column-{{ $column_name }}"
                    onclick="appling_sort(event ,'{{ $col }}')"
                    data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}">
                    {{ __('accounting-module.'.$column_name) }}
                    <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort($col) }}"></i>
                </th>
            @endforeach
        @endif
    </tr>
</thead>