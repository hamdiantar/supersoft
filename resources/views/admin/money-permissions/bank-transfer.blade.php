<div style="border: #AAA solid 1px;border-top:20px solid grey;border-radius:15px 15px 5px 5px;min-height:170px">
    @if($account_transfer->branch)
        @php
            $branch = $account_transfer->branch;
            $img = $branch->img;
            $country = optional($branch->country)->name;
            $city = optional($branch->city)->name;
        @endphp
        <div class="clearfix"></div>
        <div class=" srvices-items">
            <div class="col-xs-12">
                <div class="row">
                    <div class="col-xs-4">
                        <div style="text-align: right ">
                            <h5><i class="fa fa-home"></i> {{optional($branch)->name_ar}}</h5>
                            <h5 ><i class="fa fa-phone"></i> {{optional($branch)->phone1}} </h5>
                            <h5 ><i class="fa fa-globe"></i> {{optional($branch)->address}} </h5>
                            <h5 ><i class="fa fa-fax"></i> {{optional($branch)->fax}}</h5>
                            <h5 ><i class="fa fa-adjust"></i> {{optional($branch)->tax_card}}</h5>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <img class="img-fluid services-img center-block text-center"
                            style=" width:100px;height:100px;margin:25px auto 0 !important;text-align:center"
                            src="{{url('storage/images/branches/'.$branch->logo)}}"/>
                    </div>
                    <div class="col-xs-4">
                        <div style="text-align: left" class="my-1">
                            <h5 >{{optional($branch)->name_en}} <i class="fa fa-home"></i></h5>
                            <h5 >{{optional($branch)->phone1}} <i class="fa fa-phone"></i></h5>
                            <h5 >{{optional($branch)->address}} <i class="fa fa-globe"></i></h5>
                            <h5 >{{optional($branch)->fax}} <i class="fa fa-fax"></i></h5>
                            <h5 >{{optional($branch)->tax_card}} <i class="fa fa-adjust"></i></h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    @endif
    <div class="num-w">
        <div style="border: #AAA solid 1px;border-radius:5px;padding:10px;margin-top:10px">
            <h3 class="text-center" style="font-weight:bold ">{{ __('words.bank-transfer') }}</h3>
            <div class="row wg-tb-snd">
                <div class="col-xs-6">
                    <table class="table table-bordered">
                        <tbody>
                            <th width="40%" style="background-color:#EEE !important;color:black !important">{{__('words.exchange-number')}}</th>
                            <td>{{ optional($account_transfer->bank_transfer_pivot->bank_exchange_permission)->permission_number }}</td>
                        </tbody>
                    </table>
                </div>
                <div class="col-xs-6">
                    <table class="table table-bordered">
                        <tbody>
                            <th width="40%" style="background-color:#EEE !important;color:black !important">{{__('words.receive-number')}}</th>
                            <td>{{ optional($account_transfer->bank_transfer_pivot->bank_receive_permission)->permission_number }}</td>
                        </tbody>
                    </table>
                </div>
                <div class="col-xs-6">
                    <table class="table table-bordered">
                        <tbody>
                            <th width="40%" style="background-color:#EEE !important;color:black !important">{{__('Account From')}}</th>
                            <td>{{ optional($account_transfer->bank_transfer_pivot->bank_exchange_permission->fromBank)->name }}</td>
                        </tbody>
                    </table>
                </div>
                <div class="col-xs-6">
                    <table class="table table-bordered">
                        <tbody>
                            <th width="40%" style="background-color:#EEE !important;color:black !important">{{__('Account To')}}</th>
                            <td>{{ optional($account_transfer->bank_transfer_pivot->bank_exchange_permission->toBank)->name }}</td>
                        </tbody>
                    </table>
                </div>
                <div class="col-xs-6">
                    <table class="table table-bordered">
                        <tbody>
                            <th width="40%" style="background-color:#EEE !important;color:black !important">{{__('words.money-exchange-receiver')}}</th>
                            <td>{{ optional($account_transfer->bank_transfer_pivot->bank_exchange_permission->employee)->name }}</td>
                        </tbody>
                    </table>
                </div>
                <div class="col-xs-6">
                    <table class="table table-bordered">
                        <tbody>
                            <th width="40%" style="background-color:#EEE !important;color:black !important">{{__('words.money-receive-receiver')}}</th>
                            <td>{{ optional($account_transfer->bank_transfer_pivot->bank_receive_permission->employee)->name }}</td>
                        </tbody>
                    </table>
                </div>
                <div class="col-xs-6">
                    <table class="table table-bordered">
                        <tbody>
                            <th width="40%" style="background-color:#EEE !important;color:black !important">{{__('words.exchange-date')}}</th>
                            <td>{{ optional($account_transfer->bank_transfer_pivot->bank_exchange_permission)->operation_date }}</td>
                        </tbody>
                    </table>
                </div>
                <div class="col-xs-6">
                    <table class="table table-bordered">
                        <tbody>
                            <th width="40%" style="background-color:#EEE !important;color:black !important">{{__('words.receive-date')}}</th>
                            <td>{{ optional($account_transfer->bank_transfer_pivot->bank_receive_permission)->operation_date }}</td>
                        </tbody>
                    </table>
                </div>
                <div class="col-xs-6">
                    <table class="table table-bordered">
                        <tbody>
                            <th width="40%" style="background-color:#EEE !important;color:black !important">{{__('words.exchange-cost-center')}}</th>
                            <td>{{ optional($account_transfer->bank_transfer_pivot->bank_exchange_permission->cost_center)->name }}</td>
                        </tbody>
                    </table>
                </div>
                <div class="col-xs-6">
                    <table class="table table-bordered">
                        <tbody>
                            <th width="40%" style="background-color:#EEE !important;color:black !important">{{__('words.receive-cost-center')}}</th>
                            <td>{{ optional($account_transfer->bank_transfer_pivot->bank_receive_permission->cost_center)->name }}</td>
                        </tbody>
                    </table>
                </div>
                <div class="clearfix"></div>
                <div class="col-xs-6">
                    <table class="table table-bordered">
                        <tbody>
                            <th width="40%" style="background-color:#EEE !important;color:black !important">{{__('the Amount')}}</th>
                            <td>{{ optional($account_transfer->bank_transfer_pivot->bank_exchange_permission)->amount }}</td>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>