<div id="printThis{{ $exchange->id }}">
    <div style="border: #AAA solid 1px;border-top:20px solid grey;border-radius:15px 15px 5px 5px;min-height:170px">
        @if($exchange->branch)
            @php
                $branch = $exchange->branch;
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
                                <h5><i class="fa fa-home"></i> {{optional($exchange->branch)->name_ar}}</h5>
                                <h5 ><i class="fa fa-phone"></i> {{optional($exchange->branch)->phone1}} </h5>
                                <h5 ><i class="fa fa-globe"></i> {{optional($exchange->branch)->address}} </h5>
                                <h5 ><i class="fa fa-fax"></i> {{optional($exchange->branch)->fax}}</h5>
                                <h5 ><i class="fa fa-adjust"></i> {{optional($exchange->branch)->tax_card}}</h5>
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <img class="img-fluid services-img center-block text-center"
                                style=" width:100px;height:100px;margin:25px auto 0 !important;text-align:center"
                                src="{{url('storage/images/branches/'.$branch->logo)}}"/>
                        </div>
                        <div class="col-xs-4">
                            <div style="text-align: left" class="my-1">
                                <h5 >{{optional($exchange->branch)->name_en}} <i class="fa fa-home"></i></h5>
                                <h5 >{{optional($exchange->branch)->phone1}} <i class="fa fa-phone"></i></h5>
                                <h5 >{{optional($exchange->branch)->address}} <i class="fa fa-globe"></i></h5>
                                <h5 >{{optional($exchange->branch)->fax}} <i class="fa fa-fax"></i></h5>
                                <h5 >{{optional($exchange->branch)->tax_card}} <i class="fa fa-adjust"></i></h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        @endif
        <div class="num-w">
            <div style="border: #AAA solid 1px;border-radius:5px;padding:10px;margin-top:10px">
                <h3 class="text-center" style="font-weight:bold ">{{ __('words.bank-exchange') }}</h3>
                <div class="row wg-tb-snd">
                    <div class="col-xs-6">
                        <table class="table table-bordered">
                            <tbody>
                                <th width="40%" style="background-color:#EEE !important;color:black !important">{{__('words.permission-number')}}</th>
                                <td>{{$exchange->permission_number}}</td>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-xs-6">
                        <table class="table table-bordered">
                            <tbody>
                                <th width="40%" style="background-color:#EEE !important;color:black !important">{{__('the Amount')}}</th>
                                <td>{{ $exchange->amount }}</td>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-xs-6">
                        <table class="table table-bordered">
                            <tbody>
                                <th width="40%" style="background-color:#EEE !important;color:black !important">{{__('words.bank-from')}}</th>
                                <td>{{ optional($exchange->fromBank)->name }}</td>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-xs-6">
                        <table class="table table-bordered">
                            <tbody>
                                @if($exchange->destination_type == 'locker')
                                    <th width="40%" style="background-color:#EEE !important;color:black !important">{{__('Locker To')}}</th>
                                    <td>{{ optional($exchange->toLocker)->name }}</td>
                                @else
                                    <th width="40%" style="background-color:#EEE !important;color:black !important">{{__('words.bank-to')}}</th>
                                    <td>{{ optional($exchange->toBank)->name }}</td>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="col-xs-6">
                        <table class="table table-bordered">
                            <tbody>
                                <th width="40%" style="background-color:#EEE !important;color:black !important">{{__('Date')}}</th>
                                <td>{{ $exchange->operation_date }}</td>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-xs-6">
                        <table class="table table-bordered">
                            <tbody>
                                <th width="40%" style="background-color:#EEE !important;color:black !important">{{__('words.permission-status')}}</th>
                                <td>{{ __('words.'.$exchange->status) }}</td>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-xs-6">
                        <table class="table table-bordered">
                            <tbody>
                                <th width="40%" style="background-color:#EEE !important;color:black !important">{{__('words.money-receiver')}}</th>
                                <td>{{ optional($exchange->employee)->name }}</td>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-xs-12">
                        <table class="table table-bordered">
                            <tbody>
                                <th width="40%" style="background-color:#EEE !important;color:black !important">{{__('words.permission-note')}}</th>
                                <td>{{ $exchange->note }}</td>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div>
    <div style="margin-top:13px">
        @if($exchange->status == 'pending')
            <a href="{{ route('admin:bank-exchanges.approve' ,['id' => $exchange->id]) }}" class="btn btn-success">
                <i class="fa fa-check"></i> {{ __('words.approve-permission') }}
            </a>
            <a href="{{ route('admin:bank-exchanges.reject' ,['id' => $exchange->id]) }}" class="btn btn-warning">
                <i class="fa fa-ban"></i> {{ __('words.reject-permission') }}
            </a>
        @endif
        <button onclick="printExchange('{{ $exchange->id }}')" class="btn btn-primary waves-effect waves-light">
            <i class='fa fa-print'></i>
            {{ __('Print') }}
        </button>
        <button data-remodal-action="cancel" class="btn btn-danger waves-effect waves-light">
            <i class='fa fa-close'></i>
            {{ __('Close') }}
        </button>
    </div>
</div>