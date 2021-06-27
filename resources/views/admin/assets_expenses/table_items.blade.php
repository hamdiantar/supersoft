<div class="col-md-12">
    <div class="table-responsive scroll-table">
        <table class="table table-responsive table-hover table-bordered">
            <thead>
            <tr>
                <th width="2%"> # </th>
                <th width="16%"> {{ __('Assets Groups') }} </th>
                <th width="10%"> {{ __('Assets') }} </th>
                <th width="10%"> {{ __('Expenses Types') }} </th>
                <th width="10%"> {{ __('Expenses Items') }} </th>
                <th width="12%"> {{ __('Expense Cost') }} </th>
                <th width="5%"> {{ __('Action') }} </th>
            </tr>
            </thead>
            <tbody id="items_data">
            @if(isset($assetExpense))
                @foreach ($assetExpense->assetExpensesItems as $index => $item)
                    <tr class="text-center-inputs" id="item_{{$index}}">
                        <input type="hidden" name="asset_expense_id" value="{{$assetExpense->id}}">

                        <td>
                            <span>{{$index}}</span>
                        </td>

                        <td>
                            <span style="width: 150px !important;display:block">{{optional($item->asset->group)->name}}</span>
                        </td>

                        <td class="inline-flex-span">
                            <span>{{optional($item->asset)->name}}</span>
                            <input type="hidden" class="assetExist" value="{{optional($item->asset)->id}}" name="items[{{$index}}][asset_id]">
                        </td>

                        <td>
                            <div class="input-group">
                                <select style="width: 150px !important;" class="form-control js-example-basic-single">
                                    @foreach($assetExpensesTypes as $type)
                                        <option value="{{$type->id}}"
                                            {{optional($item->assetExpenseItem)->assets_type_expenses_id === $type->id ? 'selected' : ''}}>{{$type->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </td>

                        <td>
                            <div class="input-group">
                                <select style="width: 150px !important;" class="form-control js-example-basic-single"
                                        name="items[{{$index}}][asset_expense_item_id]">
                                    @foreach($assetExpensesItems as $assetExpensesItem)
                                        <option value="{{$assetExpensesItem->id}}"
                                        {{$item->asset_expense_item_id === $assetExpensesItem->id ? 'selected' : ''}}>{{$assetExpensesItem->item}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </td>

                        <td>
                            <input type="number" class="priceItem" name="items[{{$index}}][price]" value="{{$item->price}}" onkeyup="addPriceToTotal('{{$index}}')">
                        </td>
                        <td>
                            <div class="input-group" id="stores">
                                <button type="button" class="btn btn-danger fa fa-trash" onclick="removeItem('{{$index}}')"></button>
                            </div>
                        </td>
                    </tr>



                @endforeach
            @endif
            </tbody>
            <tfoot>
            <tr>
                <th width="2%"> # </th>
                <th width="16%"> {{ __('Assets Groups') }} </th>
                <th width="10%"> {{ __('Assets') }} </th>
                <th width="10%"> {{ __('Expenses Types') }} </th>
                <th width="10%"> {{ __('Expenses Items') }} </th>
                <th width="12%"> {{ __('Expense Cost') }} </th>
                <th width="5%"> {{ __('Action') }} </th>
            </tr>
            </tfoot>
            <input type="hidden" name="index" id="items_count" value="{{isset($settlement) ? $settlement->items->count() : 0}}">
        </table>
    </div>
</div>
