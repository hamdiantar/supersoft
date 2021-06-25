@extends('admin.layouts.app')
@section('title')
    <title>{{ __('Super Car') }} - {{__('Lockers Transactions')}} </title>
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('accounting-module/daily-restriction.css') }}"/>
@endsection
@section('content')
    <div class="row small-spacing">
        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item">  {{__('Lockers Transactions')}}</li>
            </ol>
        </nav>

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
                    <form action="{{route('admin:lockers-transactions.index')}}" method="get" id="filtration-form">
                        <input type="hidden" name="rows" value="{{ isset($_GET['rows']) ? $_GET['rows'] : '' }}"/>
                        <input type="hidden" name="key" value="{{ isset($_GET['key']) ? $_GET['key'] : '' }}"/>
                        <input type="hidden" name="sort_method" value="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : '' }}"/>
                        <input type="hidden" name="sort_by" value="{{ isset($_GET['sort_by']) ? $_GET['sort_by'] : '' }}"/>
                        <input type="hidden" name="invoker"/>
                        <div class="list-inline margin-bottom-0 row">

                            @if(authIsSuperAdmin())
                                <div class="form-group col-md-12">
                                    <label> {{ __('Branch') }} </label>
                                    <select name="branch_id" id="branch_id" class="form-control js-example-basic-single"
                                            onchange="getByBranch()">
                                        <option value="">{{__('Select Branch')}}</option>
                                        @foreach($branches as $k=>$v)
                                            <option value="{{$k}}">{{$v}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <div class="form-group col-md-4" id="data_by_branch">
                                <label> {{ __('Locker name') }} </label>
                                <select name="locker_id" class="form-control js-example-basic-single">
                                    <option value="">{{__('Select Locker')}}</option>
                                    @foreach($lockers as $k=>$v)
                                        <option value="{{$k}}">{{$v}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label> {{ __('Account name') }} </label>

                                <select name="account_id" class="form-control js-example-basic-single">
                                    <option value="">{{__('Select Account')}}</option>
                                    @foreach($accounts as $k=>$v)
                                        <option value="{{$k}}">{{$v}}</option>
                                    @endforeach
                                </select>

                            </div>
                            <div class="form-group col-md-4">
                                <label> {{ __('Username') }} </label>
                                <select name="created_by" class="form-control js-example-basic-single">
                                    <option value="">{{__('Select User')}}</option>
                                    @foreach($users as $k=>$v)
                                        <option value="{{$k}}">{{$v}}</option>
                                    @endforeach
                                </select>

                            </div>

                            <div class="form-group col-md-4">
                                <label> {{ __('Operation Type') }} </label>
                                <select name="type" class="form-control js-example-basic-single">
                                    <option value="">{{__('Select Type')}}</option>
                                    <option value="deposit">{{__('Deposit')}}</option>
                                    <option value="withdrawal">{{__('Withdrawal')}}</option>
                                </select>
                            </div>

                            <div class="form-group col-md-4">
                                <label>{{__('Date From')}}</label>
                                <input type="date" name="date_from" class="form-control">
                            </div>

                            <div class="form-group col-md-4">
                                <label>{{__('Date To')}}</label>
                                <input type="date" name="date_to" class="form-control">
                            </div>

                        </div>

                        <button type="submit"
                                class="btn sr4-wg-btn   waves-effect waves-light hvr-rectangle-out"><i
                                    class=" fa fa-search "></i> {{__('Search')}} </button>
                        <a href="{{route('admin:lockers-transactions.index')}}"
                           class="btn bc-wg-btn   waves-effect waves-light hvr-rectangle-out"><i
                                    class=" fa fa-reply"></i> {{__('Back')}}
                        </a>

                    </form>
                </div>
                <!-- /.card-content -->
            </div>
            <!-- /.box-content -->
        </div>
        @endif
        
        <div class="col-xs-12">
            <div class="box-content card bordered-all js__card">
                <h4 class="box-title bg-secondary with-control">
                <i class="fa fa-money"></i>  {{__('Lockers Transactions')}}
                 </h4>

                <div class="card-content js__card_content" style="">
                    {{-- <ul class="list-inline pull-left top-margin-wg">
                        <li class="list-inline-item">
                            @include('admin.buttons.add-new', [
                       'route' => 'admin:lockers-transactions.create',
                           'new' => '',
                          ])
                        </li>

                            <li class="list-inline-item">

                                @component('admin.buttons._confirm_delete_selected',[
                             'route' => 'admin:lockers.transactions.deleteSelected',
                              ])
                                @endcomponent
                            </li>
            
                    </ul> --}}
                    <div class="clearfix"></div>
                    <div class="table-responsive">
                        @php
                            $view_path = 'admin.lockers-transactions.options-datatable';
                        @endphp
                        @include($view_path . '.option-row')
                        <div class="clearfix"></div>
                        <table id="currencies" class="table table-bordered" style="width:100%;margin-top:15px">
                            @include($view_path . '.table-thead')
                            <tfoot>
                            <tr>
                                <th class="text-center column-id" scope="col">{!! __('#') !!}</th>
                                <th class="text-center column-locker-name" scope="col">{!! __('Locker name') !!}</th>
                                <th class="text-center column-account-name" scope="col">{!! __('Account name') !!}</th>
                                <th class="text-center column-operation-type" scope="col">{!! __('Operation Type') !!}</th>
                                <th class="text-center column-the-cost" scope="col">{!! __('The Cost') !!}</th>
                                <th class="text-center column-created-by" scope="col">{!! __('Created By') !!}</th>
                                <th class="text-center column-created-at" scope="col">{!! __('created at') !!}</th>
                                <th class="text-center column-updated-at" scope="col">{!! __('Updated at') !!}</th>
                                <th scope="col">{!! __('Options') !!}</th>
                                {{-- <th scope="col">{!! __('Select') !!}</th> --}}
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($lockerTransactions as $index=>$lockerTransaction)
                                <tr>
                                    <td class="text-center column-id">{!! $index +1 !!}</td>
                                    <td class="text-center column-locker-name">{!! optional($lockerTransaction->locker)->name !!}</td>
                                    <td class="text-center column-account-name">{!! optional($lockerTransaction->account)->name !!}</td>
                                    <td class="text-center column-operation-type">
                                    @if($lockerTransaction->type === 'deposit' )
                                    <span class="label label-primary wg-label">   {{__('Deposit')}} </span>
                                    @else
                                    <span class="label label-danger wg-label">  {{__('Withdrawal')}} </span>
                                    @endif
                                    </td>
                                    <td class="text-danger text-center column-the-cost">{!! $lockerTransaction->amount !!}</td>
                                    <td class="text-center column-created-by">{!! optional($lockerTransaction->createdBy)->name !!}</td>
                                    <td class="text-center column-created-at">{!! $lockerTransaction->created_at->format('y-m-d h:i:s A') !!}</td>
                                    <td class="text-center column-updated-at">{!! $lockerTransaction->updated_at->format('y-m-d h:i:s A') !!}</td>

                                    <td>
                                        <button class="btn btn-info" type="button"
                                            onclick="show_transfer_print('{{ route('admin:lockers-transactions.show' ,['id' => $lockerTransaction->id]) }}')">
                                            <i class="fa fa-print"></i>
                                        </button>

                                        {{-- @component('admin.buttons._edit_button',[
                                                    'id' => $lockerTransaction->id,
                                                    'route'=>'admin:lockers-transactions.edit'
                                                     ])
                                        @endcomponent

                                        @component('admin.buttons._delete_button',[
                                                    'id'=>$lockerTransaction->id,
                                                    'route' => 'admin:lockers-transactions.destroy',
                                                    'tooltip' => __('Delete '.$lockerTransaction['name']),
                                                     ])
                                        @endcomponent --}}
                                    </td>
                                    {{-- <td>
                                        @component('admin.buttons._delete_selected',[
                                                      'id' => $lockerTransaction->id,
                                                        'route' => 'admin:lockers.transactions.deleteSelected',
                                                       ])
                                        @endcomponent
                                    </td> --}}
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $lockerTransactions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('accounting-module-modal-area')
    <div class="remodal" data-remodal-id="show_data" role="dialog" aria-labelledby="modal1Title"
        aria-describedby="modal1Desc">
        <button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>
        <div class="remodal-content" id="printThis">
            <div id="DataToPrint">
            </div>
        </div>

        <button  class="btn btn-primary waves-effect waves-light" onclick="print_data()">
        <i class='fa fa-print'></i>
        {{__('Print')}}</button>

        <button data-remodal-action="cancel" class="btn btn-danger waves-effect waves-light">
        <i class='fa fa-close'></i>
        {{__('Close')}}</button>
    </div>
    @include($view_path . '.column-visible')
@endsection

@section('js')
    <script type="application/javascript">

        function getByBranch() {

            var id = $("#branch_id").val();

            $.ajax({
                url: '{{route('admin:get.transactions.by.branch')}}',
                type: "get",
                data: {id: id},
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function (data) {

                    $("#data_by_branch").html(data);
                    $('.js-example-basic-single').select2();

                }, error: function (jqXhr, json, errorThrown) {
                    swal("sorry!", 'sorry please try later', "error");
                },
            });
        }

        function print_data() {
            var page_title = document.title
            print_element('printThis' ,page_title)
        }

        function show_transfer_print(url) {
            $("#DataToPrint").html("{{ __('words.data-loading') }}")
            $('[data-remodal-id=show_data]').remodal().open()
            $.ajax({
                url: url,
                method: 'GET',
                success: function (data) {
                    $("#DataToPrint").html(data.code)
                }
            });
        }

        // invoke_datatable($('#currencies'))
    </script>
    <script type="application/javascript" src="{{ asset('accounting-module/options-for-dt.js') }}"></script>

@endsection
