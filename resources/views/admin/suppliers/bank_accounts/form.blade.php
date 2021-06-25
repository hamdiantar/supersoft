<div class="col-md-12">
    <hr>
    <span
        style="color: white;font-size: 14px;background:#2980B9;padding:5px 10px;border-radius:3px"> {{__('Bank Accounts')}} </span>
    <hr>
</div>

<div class="container">

    <div class="form_new_bank_account">

        @if(isset($supplier) && $supplier->bankAccounts )
            @foreach($supplier->bankAccounts as $bankAccount)
                @php
                    $index = $bankAccount->id;
                @endphp
                <div class="row " id="bank_account_{{$bankAccount->id}}">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="exampleInputEmail1">{{__('Bank Name')}}</label>
                                <span class="asterisk" style="color: #ff1d47"> * </span>
                                <input type="text" id="bank_name_{{$bankAccount->id}}"
                                       name="bankAccountUpdate[{{$bankAccount->id}}][bank_name]"
                                       value="{{$bankAccount->bank_name}}" class="form-control">
                            </div>
                            {{input_error($errors,'bankAccountUpdate.'.$bankAccount->id.'.bank_name')}}
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="exampleInputEmail1">{{__('Account Name')}}</label>
                                <span class="asterisk" style="color: #ff1d47"> * </span>
                                <input type="text" id="account_name_{{$bankAccount->id}}"
                                       name="bankAccountUpdate[{{$bankAccount->id}}][account_name]"
                                       value="{{$bankAccount->account_name}}" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="exampleInputEmail1">{{__('Branch Name')}} :</label>
                                <span class="asterisk" style="color: #ff1d47"> * </span>
                                <input type="text" id="branch_{{$bankAccount->id}}"
                                       name="bankAccountUpdate[{{$bankAccount->id}}][branch]"
                                       value="{{$bankAccount->branch}}" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="exampleInputPassword1"> {{__('Account Number')}} : </label>
                                <span class="asterisk" style="color: #ff1d47"> * </span>
                                <input ype="text" id="account_number_{{$bankAccount->id}}"
                                       name="bankAccountUpdate[{{$bankAccount->id}}][account_number]"
                                       value="{{$bankAccount->account_number}}" class="form-control"
                                       style=" height: 45px;">
                            </div>
                            {{input_error($errors,'bankAccountUpdate.'.$bankAccount->id.'.account_number')}}
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="exampleInputEmail1">{{__('IBAN')}} :</label>
                                <span class="asterisk" style="color: #ff1d47"> * </span>
                                <input type="text" id="iban_{{$bankAccount->id}}"
                                       name="bankAccountUpdate[{{$bankAccount->id}}][iban]"
                                       value="{{$bankAccount->iban}}" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="exampleInputEmail1">{{__('Swift Code')}} :</label>
                                <span class="asterisk" style="color: #ff1d47"> * </span>
                                <input type="text" id="swift_code_{{$bankAccount->id}}"
                                       name="bankAccountUpdate[{{$bankAccount->id}}][swift_code]"
                                       value="{{$bankAccount->swift_code}}" class="form-control">
                            </div>
                            <input type="hidden" name="bankAccountUpdate[{{$bankAccount->id}}][bankAccountId]"
                                   value="{{$bankAccount->id}}">

                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                {{--                                <button class="btn btn-sm btn-success" type="button" onclick="updateBankAccount('{{$bankAccount->id}}')"--}}
                                {{--                                        id="delete-div-" style="margin-top: 31px;" title="{{__('Update Bank Account')}}">--}}
                                {{--                                    <li class="fa fa-check"></li>--}}
                                {{--                                </button>--}}

                                <button class="btn btn-sm btn-danger" type="button"
                                        onclick="destroyBankAccount('{{$bankAccount->id}}')"
                                        id="delete-div-" style="margin-top: 31px;"
                                        title="{{__('Delete Bank Account')}}">
                                    <li class="fa fa-trash"></li>
                                </button>

                            </div>
                        </div>
                    </div>
                </div>
                <hr>
            @endforeach
        @endif

        @if($errors->has('bankAccount.*.bank_name') || $errors->has('bankAccount.*.account_number'))
            @include('admin.suppliers.bank_accounts.ajax_form_new_contact',['index' => $index ?? 12323])
        @endif
        <input type="hidden" value="0" id="bank_account_count">

    </div>


</div>


<div class="col-md-12">
    <button type="button" title="new price" onclick="newBankAccount()"
            class="btn btn-sm btn-primary">
        <li class="fa fa-plus"></li>
    </button>
    <hr>
</div>
