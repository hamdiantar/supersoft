@extends('admin.layouts.app')

@section('content')

<div class="col-xs-12 ui-sortable-handle">
				<div class="box-content card bordered-all primary">
					<h4 class="box-title with-control"><i class="ico fa fa-money"></i>
              {{__('accounting-module.Adverse Restrictions Create')}}
                   

                    </h4>


    <div class="col-md-12" style="padding-top:10px">
        <form method="post" action="{{ route('adverse-restrictions-store') }}">
            @csrf

            <div class="form-group col-md-4">
                <label> {{ __('accounting-module.restriction-number') }} </label>
                <input type="hidden" name="restriction_number" value="{{ $restriction_number }}"/>
                <input type="hidden" name="fiscal_year_id" value="{{ $fiscal_year }}"/>
                <input type="hidden" name="date_from" value="{{ $date_from }}"/>
                <input type="hidden" name="date_to" value="{{ $date_to }}"/>
                <input type="hidden" name="for_account_id" value="{{ $account->id }}"/>
                <input disabled value="{{ $restriction_number }}" class="form-control"/>
            </div>
            <div class="form-group col-md-4">
                <label> {{ __('accounting-module.operation-number') }} </label>
                <input type="hidden" name="operation_number" value="{{ $operation_number }}"/>
                <input disabled class="form-control" value="{{ $operation_number }}"/>
            </div>
            <div class="form-group col-md-4">
                <label> {{ __('accounting-module.operation-date') }} </label>
                <input type="hidden" name="operation_date" value="{{ $operation_date }}"/>
                <input disabled type="date" class="form-control" value="{{ $operation_date }}"/>
            </div>

            <div class="form-group col-md-4">
                <label> {{ __('accounting-module.debit-amount') }} </label>
                <input onkeydown="return false" onkeyup="return false" onkeypress="return false"
                    name="debit_amount" class="form-control" value="{{ $balance < 0 ? abs($balance) + $debit_total : $debit_total }}"/>
            </div>
            <div class="form-group col-md-4">
                <label> {{ __('accounting-module.credit-amount') }} </label>
                <input onkeydown="return false" onkeyup="return false" onkeypress="return false"
                    name="credit_amount" class="form-control" value="{{ $balance > 0 ? abs($balance) + $credit_total : $credit_total }}"/>
            </div>
            <div class="form-group col-md-4">
                <label> {{ __('accounting-module.records-number') }} </label>
                <input type="hidden" name="records_number" value="{{ $rows }}"/>
                <input disabled id="records-number" class="form-control" value="{{ $rows }}"/>
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
                        </tr>
                    </thead>
                    <tbody id="table-body">
                        @php
                            $count = 1;
                            $total_debit = $total_credit = 0;
                            $collection->chunk(25 ,function ($accounts) use (&$count ,&$total_debit ,&$total_credit ,$view_path) {
                                foreach($accounts as $acc) {
                                    echo view($view_path.'.adverse-restriction-row' ,
                                        ['count' => $count ,'account' => $acc])->render();
                                    $count++;
                                }
                            });
                        @endphp
                        <tr>
                            <td>
                                <input value="{{ $account->code }}" type="hidden" name="table_data[{{ $count }}][account_tree_code]"/>
                                <input value="{{ $account->id }}" type="hidden" name="table_data[{{ $count }}][accounts_tree_id]"/>
                                <input value="{{ $account->code }}" class="form-control" disabled/>
                            </td>
                            <td>
                                <input value="{{ $lang == 'ar' ? $account->name_ar : $account->name_en }}" class="form-control" disabled/>
                            </td>
                            <td>
                                <input type="hidden" name="table_data[{{ $count }}][debit_amount]"
                                    value="{{ $balance < 0 ? abs($balance) : 0 }}"/>
                                <input disabled class="form-control" value="{{ $balance < 0 ? abs($balance) : 0 }}">
                            </td>
                            <td>
                                <input type="hidden" name="table_data[{{ $count }}][credit_amount]"
                                    value="{{ $balance > 0 ? $balance : 0 }}"/>
                                <input disabled class="form-control" value="{{ $balance > 0 ? $balance : 0 }}">
                            </td>
                            <td>
                                <select class="form-control"
                                    name="table_data[{{ $count }}][cost_center_root_id]"
                                    onchange="load_cost_branches(event)">
                                    {!! \App\AccountingModule\Controllers\CostCenterCont::build_root_centers_options(NULL ,auth()->user()->branch_id) !!}
                                </select>
                            </td>
                            <td>
                                <select class="form-control cost-centers-select" name="table_data[{{ $count }}][cost_center_id]">
                                    {!! \App\AccountingModule\Controllers\CostCenterCont::build_centers_options(NULL ,NULL ,1 ,false ,auth()->user()->branch_id) !!}
                                </select>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        {{-- <tr>
                            <td colspan="8" class="text-left">
                                <button type="button" class="btn btn-default" onclick="addNewRow()">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </td>
                        </tr> --}}
                    </tfoot>
                </table>
            </div>
            <div class="form-group col-md-12">
                <button class="btn btn-wg-show hvr-radial-out">
                <i class="fa fa-save"></i> {{ __('accounting-module.save') }}
                </button>
            </div>
        </form>
    </div>
    </div>
    </div>
@endsection

@section('accounting-scripts')
    {!! JsValidator::formRequest('App\Http\Requests\AccountingModule\AdverseRestrictionStoreRequest') !!}

    <script>
        function load_cost_branches(event) {
            var root_cost_center_id = $(event.target).find('option:selected').val() ,
                root_cost_center_count = $(event.target).find('option:selected').data('my-count')
            $.ajax({
                dataType:'json',
                type:'GET',
                url: '{{ route('cost-center-get-branches') }}?id=' + root_cost_center_id + '&count=' + root_cost_center_count,
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
    </script>
@endsection