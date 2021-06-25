<thead>
<tr>
    <th class="text-center column-id"
        onclick="appling_sort(event ,'id')"
        data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}"
        scope="col">
        {!! __('#') !!}
        <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('id') }}"></i>
    </th>

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
        {!! __('Concession number') !!}
        <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('number') }}"></i>
    </th>

    <th class="text-center column-total_quantity"
        onclick="appling_sort(event ,'total_quantity')"
        data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}"
        scope="col">
        {!! __('Total') !!}
        <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('total_quantity') }}"></i>
    </th>

    <th class="text-center column-type"
        onclick="appling_sort(event ,'type')"
        data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}"
        scope="col">
        {!! __('Type') !!}
        <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('type') }}"></i>
    </th>

    <th class="text-center column-type" scope="col">
        {!! __('Item Number') !!}
    </th>

    <th class="text-center column-type">
        {!! __('Concession Type') !!}
    </th>

    <th class="text-center column-status"
        onclick="appling_sort(event ,'status')"
        data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}"
        scope="col">
        {!! __('Status') !!}
        <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('status') }}"></i>
    </th>


    <th class="text-center column-concession-type" scope="col">
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

    <th scope="col" class="text-center column-options">{!! __('Options') !!}</th>

    <th scope="col" class="text-center column-select-all">
        <div class="checkbox danger">
            <input type="checkbox" id="select-all">
            <label for="select-all"></label>
        </div>{!! __('Select') !!}
    </th>
</tr>
</thead>
