<div class="box-content box-content-wg card bordered-all blue-1 js__card">
    <h4 class="box-title bg-blue-1 with-control text-center">
        {{__('Bank Accounts')}}
        <span style="float: right;"> </span>
        <span class="controls">

                                    <button type="button" class="control fa fa-minus js__card_minus"></button>
                                    <button type="button" class="control fa fa-times js__card_remove"></button>
                                </span>
    </h4>

    <div class="card-content js__card_content" style="">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>{{__('Bank Name')}}</th>
                <th>{{__('Account Name')}}</th>
                <th>{{__('Branch Name')}}</th>
                <th>{{__('Account Number')}}</th>
                <th>{{__('IBAN')}}</th>
                <th>{{__('Swift Code')}}</th>
                <th>{{__('Created Date')}}</th>
            </tr>
            </thead>
            <tbody>

            @foreach($customer->bankAccounts as $bankAccount)
                <tr>
                    <td>{{$bankAccount->bank_name}}</td>
                    <td>{{$bankAccount->account_name}}</td>
                    <td>{{$bankAccount->branch}}</td>
                    <td>{{$bankAccount->account_number}}</td>
                    <td>{{$bankAccount->iban}}</td>
                    <td>{{$bankAccount->swift_code}}</td>
                    <td>{{$bankAccount->created_at}}</td>
                </tr>
            @endforeach

        </table>
    </div>
</div>

