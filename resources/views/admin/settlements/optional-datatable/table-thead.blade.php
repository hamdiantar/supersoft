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
            <th class="text-center column-branch"
                onclick="appling_sort(event ,'branch')"
                data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}"
                scope="col">
                {{ __('opening-balance.branch') }}
                <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('branch') }}"></i>
            </th>
        @endif




        <th class="text-center column-date"
            onclick="appling_sort(event ,'date')"
            data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}"
            scope="col">
            {!! __('Date') !!}
            <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('date') }}"></i>
        </th>

        <th class="text-center column-number"
            onclick="appling_sort(event ,'number')"
            data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}"
            scope="col">
            {!! __('Number') !!}
            <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('number') }}"></i>
        </th>

        <th class="text-center column-total"
            onclick="appling_sort(event ,'total')"
            data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}"
            scope="col">
            {!! __('Total') !!}
            <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('total') }}"></i>
        </th>

        <th class="text-center column-status" scope="col">
            {{ __('Concession Status') }}
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
