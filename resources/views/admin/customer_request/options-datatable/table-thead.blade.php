<thead>
    <tr>
        <th class="text-center column-name" 
            onclick="appling_sort(event ,'name')"
            data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}"
            scope="col">
            {!! __('Name') !!}
            <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('name') }}"></i>
        </th>
        <th class="text-center column-phone" 
            onclick="appling_sort(event ,'phone')"
            data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}"
            scope="col">
            {!! __('Phone') !!}
            <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('phone') }}"></i>
        </th>
        <th class="text-center column-username" 
            onclick="appling_sort(event ,'username')"
            data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}"
            scope="col">
            {!! __('username') !!}
            <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('username') }}"></i>
        </th>
        <th class="text-center column-status" 
            onclick="appling_sort(event ,'status')"
            data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}"
            scope="col">
            {!! __('Status') !!}
            <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('status') }}"></i>
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
        <th scope="col">{!! __('Options') !!}</th>
        <th scope="col">
            <div class="checkbox danger">
                <input type="checkbox" id="select-all">
                <label for="select-all"></label>
            </div>
            {!! __('Select') !!}
        </th>
    </tr>
</thead>