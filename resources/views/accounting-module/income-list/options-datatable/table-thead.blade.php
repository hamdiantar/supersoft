<thead>
    <tr>
        @foreach($sort_by_columns as $col)
            @php
                $column_name = implode("-" ,explode("_" ,$col));
            @endphp
            <th class="text-center">
                {{ __('accounting-module.'.$column_name) }}
            </th>
        @endforeach
    </tr>
</thead>