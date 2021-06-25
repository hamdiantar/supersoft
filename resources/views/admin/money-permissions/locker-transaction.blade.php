<div style="border: #AAA solid 1px;border-top:20px solid grey;border-radius:15px 15px 5px 5px;min-height:170px">
    @if($locker_transaction->branch)
        @php
            $branch = $locker_transaction->branch;
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
            <h3 class="text-center" style="font-weight:bold ">
                {{ $locker_transaction->type == 'deposit' ? __('words.transfer-from-bank-to-locker') : __('words.transfer-from-locker-to-bank') }}
            </h3>
            <div class="row wg-tb-snd">
                <div class="col-xs-6">
                    <table class="table table-bordered">
                        <tbody>
                            <th width="40%" style="background-color:#EEE !important;color:black !important">{{__('words.bank')}}</th>
                            <td>{{ optional($locker_transaction->account)->name }}</td>
                        </tbody>
                    </table>
                </div>
                <div class="col-xs-6">
                    <table class="table table-bordered">
                        <tbody>
                            <th width="40%" style="background-color:#EEE !important;color:black !important">{{__('words.locker')}}</th>
                            <td>{{ optional($locker_transaction->locker)->name }}</td>
                        </tbody>
                    </table>
                </div>
                <div class="col-xs-6">
                    <table class="table table-bordered">
                        <tbody>
                            <th width="40%" style="background-color:#EEE !important;color:black !important">{{__('Date')}}</th>
                            <td>{{ $locker_transaction->date }}</td>
                        </tbody>
                    </table>
                </div>
                <div class="col-xs-6">
                    <table class="table table-bordered">
                        <tbody>
                            <th width="40%" style="background-color:#EEE !important;color:black !important">{{__('the Amount')}}</th>
                            <td>{{ $locker_transaction->amount }}</td>
                        </tbody>
                    </table>
                </div>
                <div class="col-xs-6">
                    <table class="table table-bordered">
                        <tbody>
                            <th width="40%" style="background-color:#EEE !important;color:black !important">{{__('Created By')}}</th>
                            <td>{{ optional($locker_transaction->createdBy)->name }}</td>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>