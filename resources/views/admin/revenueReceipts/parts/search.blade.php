
@if(filterSetting())
<div class="col-xs-12">
<div class="box-content card bordered-all js__card top-search">
					<h4 class="box-title with-control">
                    <i class="fa fa-search"></i>{{__('Search filters')}}
						<span class="controls">
							<button type="button" class="control fa fa-minus js__card_minus"></button>
							<button type="button" class="control fa fa-times js__card_remove"></button>
						</span>
						<!-- /.controls -->
					</h4>
					<!-- /.box-title -->
					<div class="card-content js__card_content">
            <form id="filtration-form">
                <input type="hidden" name="rows" value="{{ isset($_GET['rows']) ? $_GET['rows'] : '' }}"/>
                <input type="hidden" name="key" value="{{ isset($_GET['key']) ? $_GET['key'] : '' }}"/>
                <input type="hidden" name="sort_method" value="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : '' }}"/>
                <input type="hidden" name="sort_by" value="{{ isset($_GET['sort_by']) ? $_GET['sort_by'] : '' }}"/>
                <input type="hidden" name="invoker"/>
                <div class="list-inline margin-bottom-0 row">
                    @if(authIsSuperAdmin())
                        <div class="form-group col-md-12">
                        <label> {{ __('Branch') }} </label>
                            <select name="branch_id" class="form-control js-example-basic-single" id="branch_id">
                                <option value="">{{__('Select Branch')}}</option>
                                @foreach(\App\Models\Branch::all() as $branch)
                                    <option value="{{$branch->id}}">{{$branch->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="form-group col-md-3">
                    <label> {{ __('Revenue Type') }} </label>
                        <select name="revenue_type_id" class="form-control js-example-basic-single" id="revenueType">
                            <option value="">{{__('Select Revenue Type')}}</option>
                            @foreach(\App\Models\RevenueType::all() as $type)
                                <option value="{{$type->id}}">{{$type->type}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-3">
                    <label> {{ __('Revenue Item') }} </label>
                        <select name="revenue_item_id" class="form-control js-example-basic-single" id="revenueItems">
                            <option value="">{{__('Select Revenue Item')}}</option>
                            @foreach(\App\Models\RevenueItem::all() as $item)
                                <option value="{{$item->id}}">{{$item->item}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-3">
                    <label> {{ __('Revenue No') }} </label>
                        <select name="revenue_no" class="form-control js-example-basic-single" id="revenuesNumbers">
                            <option value="">{{__('Select Revenue No')}}</option>
                            @foreach(\App\Models\RevenueReceipt::all() as $receipt)
                                <option value="{{$receipt->id}}">{{$receipt->id}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-3">
                    <label> {{ __('Receiver') }} </label>
                        <select name="receiver" class="form-control js-example-basic-single" id="receivers">
                            <option value="">{{__('Select Receiver')}}</option>
                            @foreach(\App\Models\RevenueReceipt::all() as $receipt)
                                <option value="{{$receipt->receiver}}">{{$receipt->receiver}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-3">
                    <label> {{ __('Method of deportation') }} </label>
                        <select name="deportation" class="form-control  js-example-basic-single" id="deportation">
                            <option value="">{{__('Select Method of deportation')}}</option>
                            <option value="safe">{{__('Safe')}}</option>
                            <option value="bank">{{__('Bank')}}</option>
                        </select>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="dateFrom" class="control-label">{{__('Date From')}}</label>
                        <div class="input-group">
                            <input type="date" name="dateFrom" class="form-control" value="" id="dateFrom" placeholder="{{__('Select Date')}}">
                        </div>
                      </div>

                      <div class="form-group col-md-3">
                        <label for="dateTo" class="control-label">{{__('Date To')}}</label>
                        <div class="input-group">
                            <input type="date" name="dateTo" class="form-control" value="" id="dateTo" placeholder="{{__('Select Date')}}">
                        </div>
                    </div>
                    @include('admin.partial.receipts-accounts' ,[
                        'type' => isset($_GET['user_account_type']) && $_GET['user_account_type'] != "" ? $_GET['user_account_type'] : NULL,
                        'id' => isset($_GET['user_account_id']) && $_GET['user_account_id'] != "" ? $_GET['user_account_id'] : NULL,
                    ])

                        <div class="col-md-3">
                            <div class="form-group">
                                <label> {{__('Payment Type')}} <i class="req-star" style="color: red">*</i>
                                </label>
                                <ul class="list-inline">
                                    <li>
                                        <div class="radio info">
                                            <input type="radio" name="payment_type" value="cash" checked
                                                   id="cashId"><label
                                                for="cashId">{{ __("words.cash") }}</label></div>
                                    </li>
                                    <li>
                                        <div class="radio info">
                                            <input type="radio" name="payment_type" value="network"
                                                   id="networkId"><label
                                                for="networkId">{{ __("words.network") }}</label></div>
                                    </li>
                                    <li>
                                        <div class="radio info">
                                            <input type="radio" name="payment_type" value="check"
                                                   id="checkId"><label
                                                for="checkId">{{ __("words.check") }}</label></div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-md-4" style="display: none" id="bankName">
                            <div class="form-group">
                                <label for="cost" class="control-label">{{__('Select Bank Name')}}</label>
                                <select name="bank_name" class="form-control js-example-basic-single">
                                    <option value="">{{__('Select Bank Name')}}</option>
                                    @foreach(\App\Models\RevenueReceipt::whereNotNull('bank_name') as $receipt)
                                        <option value="{{$receipt->bank_name}}">{{$receipt->bank_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4" style="display: none" id="checkNumber">
                            <div class="form-group">
                                <label for="cost" class="control-label">{{__('Select Check Number')}}</label>
                                <select name="check_number" class="form-control js-example-basic-single">
                                    <option value="">{{__('Select Bank Name')}}</option>
                                    @foreach(\App\Models\RevenueReceipt::whereNotNull('check_number')->get() as $receipt)
                                        <option value="{{$receipt->check_number}}">{{$receipt->check_number}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

</div>
                </ul>


                        <button type="submit"
                                class="btn sr4-wg-btn   waves-effect waves-light hvr-rectangle-out"><i
                                    class=" fa fa-search "></i> {{__('Search')}} </button>
                        <a href="{{route('admin:revenueReceipts.index')}}"
                           class="btn bc-wg-btn   waves-effect waves-light hvr-rectangle-out"><i class=" fa fa-reply"></i> {{__('Back')}}
                        </a>

            </form>
        </div>
        <!-- /.card-content -->
    </div>
    <!-- /.box-content -->
</div>
@endif

@section('js-ajax')
    <script type="application/javascript">
        $(".select2").select2()
        change_user_accounts()
        $('#branch_id').on('change', function () {
            $.ajax({
                url: "{{ route('admin:getRevenuesTypesByBranchID') }}?branch_id=" + $(this).val(),
                method: 'GET',
                success: function (data) {
                    $('#revenueType').html(data.types);
                }
            });
            $.ajax({
                url: "{{ route('admin:revenueTypes.revenuesNumbers') }}?branch_id=" + $(this).val(),
                method: 'GET',
                success: function (data) {
                    $('#revenuesNumbers').html(data.numbers);
                }
            });
            $.ajax({
                url: "{{ route('admin:revenueTypes.receivers') }}?branch_id=" + $(this).val(),
                method: 'GET',
                success: function (data) {
                    $('#receivers').html(data.receivers);
                }
            });
        });

        $("#revenueType").on( 'change',function () {
            $.ajax({
                url: "{{ route('admin:revenueTypes.items') }}?revenue_type_id=" + $(this).val(),
                method: 'GET',
                success: function (data) {
                    $('#revenueItems').html(data.items);
                }
            });
        });

        $('#checkId').on('click', function () {
            if (this.checked) {
                $("#bankName").show();
                $("#checkNumber").show();
            }
        });

        $('#cashId').on('click', function () {
            if (this.checked) {
                $("#bankName").hide();
                $("#checkNumber").hide();
            }
        });

        $('#networkId').on('click', function () {
            if (this.checked) {
                $("#bankName").hide();
                $("#checkNumber").hide();
            }
        });
    </script>
    @endsection
