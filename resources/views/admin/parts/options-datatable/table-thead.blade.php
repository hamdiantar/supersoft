<thead>
    <tr>
        <th class="text-center column-id"
            onclick="appling_sort(event ,'id')"
            data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}"
            scope="col">
            {!! __('#') !!}
            <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('id') }}"></i>
        </th>
        <th class="text-center column-name"
            onclick="appling_sort(event ,'name')"
            data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}"
            scope="col">
            {!! __('Name') !!}
            <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('name') }}"></i>
        </th>
        <th class="text-center column-type"
            onclick="appling_sort(event ,'type')"
            data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}"
            scope="col">
            {!! __('Type') !!}
            <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('type') }}"></i>
        </th>
        <!-- <th scope="col">{!! __('Image') !!}</th> -->
        <th class="text-center column-quantity"
            onclick="appling_sort(event ,'quantity')"
            data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}"
            scope="col">
            {!! __('Quantity') !!}
            <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('quantity') }}"></i>
        </th>
{{--        <th class="text-center column-last-purchase-price" --}}
{{--            onclick="appling_sort(event ,'last-purchase-price')"--}}
{{--            data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}"--}}
{{--            scope="col">--}}
{{--            {!! __('Last purchase price') !!}--}}
{{--            <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('last-purchase-price') }}"></i>--}}
{{--        </th>--}}
{{--        <th class="text-center column-last-selling-price" --}}
{{--            onclick="appling_sort(event ,'last-selling-price')"--}}
{{--            data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}"--}}
{{--            scope="col">--}}
{{--            {!! __('Last selling price') !!}--}}
{{--            <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('last-selling-price') }}"></i>--}}
{{--        </th>--}}
        <th class="text-center column-status"
            onclick="appling_sort(event ,'status')"
            data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}"
            scope="col">
            {!! __('Status') !!}
            <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('status') }}"></i>
        </th>

        <th class="text-center column-status"
            onclick="appling_sort(event ,'reviewable')"
            data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}"
            scope="col">
            {!! __('Reviewable') !!}
            <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('reviewable') }}"></i>
        </th>

        <th class="text-center column-status"
            onclick="appling_sort(event ,'taxable')"
            data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}"
            scope="col">
            {!! __('Taxable') !!}
            <i class="fa fa-sort{{ \App\AccountingModule\Helper::iam_used_in_sort('taxable') }}"></i>
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
            </div>{!! __('Select') !!}
        </th>
    </tr>
</thead>
