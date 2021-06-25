@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('accounting-module.daily-restrictions-index') }} </title>
@endsection

@section('content')
<nav>
    <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
    <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
        <li class="breadcrumb-item active"><a href="{{ route('daily-restrictions.index')}}"> {{ __('accounting-module.daily-restrictions-index') }}</a></li>
        <li class="breadcrumb-item active"> {{__('accounting-module.daily-restrictions-update')}}</li>
    </ol>
</nav>
<div class="col-xs-12 ui-sortable-handle">
				<div class="box-content card bordered-all primary">
					<h4 class="box-title with-control"><i class="ico fa fa-money"></i>
                    {{__('accounting-module.daily-restrictions-update')}}

                    <span class="controls hidden-sm hidden-xs">
							<button class="control text-primary">{{ __('words.save') }}<img class="img-fluid" style="width:60px;height:60px;margin-top:-20px" src="{{ asset('assets/images/f1.png') }}"/></button>
							<button class="control text-info">{{ __('words.clear') }}<img class="img-fluid" style="width:60px;height:60px;margin-top:-20px" src="{{ asset('assets/images/f2.png') }}"/></button>
							<button class="control text-danger">{{ __('words.cancel') }} <img class="img-fluid" style="width:60px;height:60px;margin-top:-20px" src="{{ asset('assets/images/f3.png') }}"/></button>

						</span>
                    </h4>

                    <div class="card-content">
        <form method="post"
            action="{{ route('daily-restrictions.update' ,['daily_restriction' => $model->id ]) }}"
            onsubmit="return check_fiscal_year()">
            @csrf
            <input type="hidden" name="_method" value="PUT"/>
            <input type="hidden" name="branch_id" value="{{ auth()->user()->branch_id }}"/>

            <div class="form-group col-md-4">
                <label> {{ __('accounting-module.restriction-number') }} </label>
                <input disabled value="{{ $model->restriction_number }}" class="form-control"/>
            </div>
            <div class="form-group col-md-4">
                <label> {{ __('accounting-module.operation-number') }} </label>
                <input name="operation_number" value="{{ $model->operation_number }}"
                    class="form-control" onkeypress="return isNumber(event)"/>
            </div>
            <div class="form-group col-md-4">
                <label> {{ __('accounting-module.operation-date') }} </label>
                <input name="operation_date" value="{{ $model->operation_date }}"
                    type="date" class="form-control"/>
            </div>

            <div class="form-group col-md-4">
                <label> {{ __('accounting-module.debit-amount') }} </label>
                <input id="debit-amount" value="{{ $model->debit_amount }}"
                    onkeydown="return false" onkeyup="return false" onkeypress="return false"
                    class="form-control" name="debit_amount"/>
            </div>
            <div class="form-group col-md-4">
                <label> {{ __('accounting-module.credit-amount') }} </label>
                <input id="credit-amount" value="{{ $model->credit_amount }}"
                    onkeydown="return false" onkeyup="return false" onkeypress="return false"
                    class="form-control" name="credit_amount"/>
            </div>
            <div class="form-group col-md-4">
                <label> {{ __('accounting-module.records-number') }} </label>
                <input type="hidden" name="records_number" value="{{ $model->records_number }}"/>
                <input disabled id="records-number" value="{{ $model->records_number }}" class="form-control"/>
            </div>
            <div class="col-md-12">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th width="13%"> {{ __('accounting-module.account-code') }} </th>
                            <th width="13%"> {{ __('accounting-module.account-name') }} </th>
                            <th width="13%"> {{ __('accounting-module.debit') }} </th>
                            <th width="13%"> {{ __('accounting-module.credit') }} </th>
                            <th width="13%"> {{ __('accounting-module.root-cost-center') }} </th>
                            <th width="13%"> {{ __('accounting-module.cost-center') }} </th>
                            <th width="13%"> {{ __('accounting-module.notes') }} </th>
                            <th> {{ __('accounting-module.delete') }} </th>
                        </tr>
                    </thead>
                    <tbody id="table-body">
                        @if($model->my_table)
                            @php
                                $model->my_table()->orderBy('id' ,'asc')
                                ->chunk(1 ,function ($rows) use ($accounts_tree ,$view_path ,$model) {
                                    foreach($rows as $row) {
                                        $code = view($view_path . '.edit-table-row' ,[
                                            'row' => $row ,
                                            'accounts_tree' => $accounts_tree,
                                            'model' => $model
                                        ])->render();
                                        echo $code;
                                    }
                                });
                            @endphp
                        @else
                            @include($view_path . '.table-row')
                        @endif
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="8" class="text-left">
                                <button type="button" class="btn btn-default" onclick="addNewRow()">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="form-group col-md-12">
                <button id="btn-save" class="btn hvr-rectangle-in saveAdd-wg-btn"><i class="ico ico-left fa fa-save"></i> {{ __('words.save') }} </button>
                <button id="btn-clear" type="button" onclick="accountingModuleClearFrom(event)"
                class="btn hvr-rectangle-in resetAdd-wg-btn"><i class="ico ico-left fa fa-trash"></i> {{ __('words.clear') }} </button>
                <button id="btn-cancel" type="button" onclick="accountingModuleCancelForm('{{ route('daily-restrictions.index') }}')"
                class="btn hvr-rectangle-in closeAdd-wg-btn"><i class="ico ico-left fa fa-close"></i> {{ __('words.cancel') }} </button>
            </div>
        </form>
    </div>
    </div>
    </div>
@endsection

@section('accounting-scripts')
    {!! JsValidator::formRequest('App\Http\Requests\AccountingModule\DailyRestrictionsReq') !!}
    <script type="application/javascript" src="{{ asset('js/sweet-alert.js') }}"></script>

    <script type="application/javascript">
        function alert(message) {
            swal({
                title:"{{ __('accounting-module.warning') }}",
                text:message,
                icon:"warning",
                buttons:{
                    cancel: {
                        text: "{{ __('words.ok') }}",
                        className: "btn btn-primary",
                        value: null,
                        visible: true
                    }
                }
            })
        }
    </script>

    @include('accounting-module.form-actions')

    <script type="application/javascript">
        var row_html_code = `
            @include($view_path . '.table-row')
        `

        function account_name_n_code_equal(event ,hidden_in_same_level) {
            var selected_id = $(event.target).find('option:selected').data('selected-id')
            $(event.target)
                .parent()
                .siblings('td')
                .find('select')
                .find('option[data-selected-id="'+selected_id+'"]')
                    .prop('selected' ,'selected')
            $(event.target)
                .parent()
                .siblings('td')
                .find('select')
                .select2()
            var selected_code = $(event.target).find('option:selected').data('selected-code')
            if (hidden_in_same_level) {
                $(event.target).parent().find('input[data-for="tree-code"]')
                    .val(selected_code)
            } else {
                $(event.target).parent().siblings('td').find('input[data-for="tree-code"]')
                    .val(selected_code)
            }
            calculate_totals()
        }

        function debit_n_credit_equal(event) {
            calculate_totals()
            return;
            $(event.target)
                .parent()
                .siblings('td')
                .find('input[data-input-for="debit-credit"]')
                .val($(event.target).val())
            calculate_totals()
        }

        function removeThisRow(event) {
            swal({
                title:"{{ __('accounting-module.warning') }}",
                text:"{{ __('accounting-module.are-u-sure-to-delete-daily-restriction-row') }}",
                icon:"warning",
                reverseButtons: false,
                buttons:{
                    confirm: {
                        text: "{{ __('words.yes_delete') }}",
                        className: "btn btn-default",
                        value: true,
                        visible: true
                    },
                    cancel: {
                        text: "{{ __('words.no') }}",
                        className: "btn btn-default",
                        value: null,
                        visible: true
                    }
                }
            }).then(function(confirm_delete){
                if (confirm_delete) {
                    if (event.target.tagName == 'I') $(event.target).parent().parent().parent().remove()
                    else $(event.target).parent().parent().remove()
                    calculate_totals()
                } else {
                    alert("{{ __('accounting-module.daily-restriction-row-not-deleted') }}")
                }
            })
        }

        function addNewRow() {var randomId = Math.ceil(Math.random() * 100000)
            $('#table-body').append(row_html_code.replaceAll('table_data[]' ,'table_data['+randomId+']'))
            $(".select-2").select2()
            calculate_totals()
        }

        function isNumber(evt) {
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode == 46) {
                if (txt.value.indexOf('.') === -1)
                    return true;
                else
                    return false;
            } else {
                if (charCode > 31 && (charCode < 48 || charCode > 57) )
                    return false;
            }
            return true;
        }

        function calculate_totals() {
            $("#records-number").val($("#table-body tr").length)
            $("input[name='records_number']").val($("#table-body tr").length)
            var total_credit_amount = 0 ,total_debit_amount = 0
            $("#table-body tr").each(function () {
                let row_credit_amount = $(this).find('[data-input-for="credit"]').val()
                let row_debit_amount = $(this).find('[data-input-for="debit"]').val()
                total_credit_amount += row_credit_amount ? parseFloat(row_credit_amount) : 0
                total_debit_amount += row_debit_amount ? parseFloat(row_debit_amount) : 0
            })
            $("#debit-amount").val(parseFloat(total_debit_amount))
            $("#credit-amount").val(parseFloat(total_credit_amount))
        }

        function load_cost_branches(event ,selected) {
            var root_cost_center_id = $(event.target).find('option:selected').val() ,
                root_cost_center_count = $(event.target).find('option:selected').data('my-count'),
                url = '{{ route('cost-center-get-branches') }}?id=' +
                    root_cost_center_id + '&count=' + root_cost_center_count
            if (selected) url = url + '&selected=' + selected
            $.ajax({
                dataType:'json',
                type:'GET',
                url: url,
                success: function(response) {
                    $(event.target).parent().siblings('td').find('select.cost-centers-select').html(response.html_options)
                    $(event.target).parent().siblings('td').find('select.cost-centers-select').select2()
                    $(event.target).parent().siblings('td').find('input[data-for="cost-code"]').val('')
                },
                error: function(err) {
                    alert(err.responseJSON.message)
                }
            })
        }

        $(document).on('change' ,'select.cost-centers-select' ,function () {
            var option = $(this).find('option:selected'),
                cost_code = option.data('cost-center-code')
            $(this).parent().siblings('td').find('input[data-for="cost-code"]').val(cost_code)
        })

        function check_fiscal_year() {
            var complete_submit = true
            $.ajax({
                dataType: 'json',
                type: 'GET',
                async: false,
                url: '{{ route("fiscal-years.check-available") }}?date=' + $('input[name="operation_date"]').val(),
                success: function(response) {
                    if (response.status == 203) {
                        complete_submit = false
                        alert(response.message)
                    }
                },
                error: function (err) {
                    complete_submit = false
                    alert('server error')
                }
            })
            return complete_submit
        }

        $(document).ready(function() {
            $('select').select2()
        })
    </script>
@endsection