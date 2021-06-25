@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('Work Cards') }} </title>
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('accounting-module/daily-restriction.css') }}"/>
@endsection

@section('content')
    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Work Cards')}}</li>
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
                    <div class="card-content js__card_content" style="padding:20px">
                        <form action="{{route('admin:work-cards.index')}}" method="get" id="filtration-form">
                            <input type="hidden" name="rows" value="{{ isset($_GET['rows']) ? $_GET['rows'] : '' }}"/>
                            <input type="hidden" name="key" value="{{ isset($_GET['key']) ? $_GET['key'] : '' }}"/>
                            <input type="hidden" name="sort_method"
                                   value="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : '' }}"/>
                            <input type="hidden" name="sort_by"
                                   value="{{ isset($_GET['sort_by']) ? $_GET['sort_by'] : '' }}"/>
                            <input type="hidden" name="invoker"/>
                            <ul class="list-inline margin-bottom-0 row">

                                @if(authIsSuperAdmin())
                                    <li class="form-group col-md-12">
                                        <label> {{ __('Branches') }} </label>
                                        <select name="branch_id" class="form-control js-example-basic-single">
                                            <option value="">{{__('Select Branch')}}</option>
                                            @foreach($data['branches']  as $k=>$v)
                                                <option value="{{$k}}">{{$v}}</option>
                                            @endforeach
                                        </select>
                                    </li>
                                @endif

                                <li class="form-group col-md-3">
                                    <label> {{ __('UserName') }} </label>
                                    <select name="user_id" class="form-control js-example-basic-single">
                                        <option value="">{{__('Select Name')}}</option>
                                        @foreach($data['users']  as $k=>$v)
                                            <option value="{{$k}}">{{$v}}</option>
                                        @endforeach
                                    </select>
                                </li>
                                @if (checkIfShiftActive())
                                    <li class="form-group col-md-3">
                                        <label> {{ __('Shifts') }} </label>
                                        <select name="shift_id" class="form-control js-example-basic-single">
                                            <option value="">{{__('Select Shift')}}</option>
                                            @foreach($data['shifts'] as $k=>$v)
                                                <option value="{{$k}}">{{$v}}</option>
                                            @endforeach
                                        </select>
                                    </li>
                                @endif

                                <li class="form-group col-md-3">
                                    <label> {{ __('Customer Name') }} </label>
                                    <select name="customer_id" class="form-control js-example-basic-single">
                                        <option value="">{{__('Select Customer Name')}}</option>
                                        @foreach($data['customers'] as $customer)
                                            <option value="{{$customer->id}}">{{$customer->name}}</option>
                                        @endforeach
                                    </select>
                                </li>

                                <li class="form-group col-md-3">
                                    <label> {{ __('Customer Phone') }} </label>
                                    <select name="phone" class="form-control js-example-basic-single">
                                        <option value="">{{__('Select Customer Phone')}}</option>
                                        @foreach($data['customers'] as $customer)
                                            <option value="{{$customer->id}}">{{$customer->phone1}}</option>
                                        @endforeach
                                    </select>
                                </li>

                                <li class="form-group col-md-3">
                                    <label> {{ __('Car Number') }} </label>
                                    <select name="car_id" class="form-control js-example-basic-single">
                                        <option value="">{{__('Select Car Number')}}</option>
                                        @foreach($data['cars'] as $k=>$v)
                                            <option value="{{$k}}">{{$v}}</option>
                                        @endforeach
                                    </select>
                                </li>

                                <li class="form-group col-md-3">
                                    <label> {{ __('Card Number') }} </label>
                                    <select name="card_number" class="form-control js-example-basic-single">
                                        <option value="">{{__('Select Card Number')}}</option>
                                        @foreach($data['cards'] as $card)
                                            <option value="{{$card->id}}">{{$card->card_number}}</option>
                                        @endforeach
                                    </select>
                                </li>

                                <li class="form-group col-md-3">
                                    <label> {{ __('Card Status') }} </label>
                                    <select name="card_status" class="form-control js-example-basic-single">
                                        <option value="">{{__('Select Status')}}</option>
                                        <option value="pending">{{__('Pending')}}</option>
                                        <option value="processing">{{__('Processing')}}</option>
                                        <option value="finished">{{__('Finished')}}</option>
                                        <option value="scheduled">{{__('Scheduled')}}</option>
                                    </select>
                                </li>

                                <li class="form-group col-md-3">
                                    <label> {{ __('Service Invoice Number') }} </label>
                                    <select name="invoice_number" class="form-control js-example-basic-single">
                                        <option value="">{{__('Select Invoice Number')}}</option>
                                        @foreach($data['card_invoices'] as $k=>$v)
                                            <option value="{{$k}}">{{$v}}</option>
                                        @endforeach
                                    </select>
                                </li>

                                <li class="form-group col-md-3">
                                    <label> {{ __('Date From') }} </label>
                                    <input type="date" name="date_from" class="form-control"
                                           placeholder="{{ __('Date From') }}">
                                </li>


                                <li class="form-group col-md-3">
                                    <label> {{ __('Date To') }} </label>
                                    <input type="date" name="date_to" class="form-control"
                                           placeholder="{{ __('Date To') }}">
                                </li>

                                <li class="switch primary col-md-2">
                                    <input type="checkbox" id="switch-2" name="receive_car_status">
                                    <label for="switch-2">{{__('Received Cars')}}</label>
                                </li>

                            <!-- <li class="switch primary col-md-2">
                                <input type="checkbox" id="switch-4" name="not_receive_car_status">
                                <label for="switch-4">{{__('Not Received Cars')}}</label>
                            </li> -->

                                <li class="switch primary col-md-2">
                                    <input type="checkbox" id="switch-3" name="delivery_car_status">
                                    <label for="switch-3">{{__('Delivered Cars')}}</label>
                                </li>
                            <!--
                            <li class="switch primary col-md-2">
                                <input type="checkbox" id="switch-5" name="not_delivery_car_status">
                                <label for="switch-5">{{__('Not Delivered Cars')}}</label>
                            </li> -->

                                <li class="form-group col-md-4">

                                </li>
                            </ul>
                            <button type="submit"
                                    class="btn sr4-wg-btn   waves-effect waves-light hvr-rectangle-out"><i
                                    class=" fa fa-search "></i> {{__('Search')}} </button>
                            <a href="{{route('admin:work-cards.index')}}"
                               class="btn bc-wg-btn   waves-effect waves-light hvr-rectangle-out"><i
                                    class=" fa fa-reply"></i> {{__('Back')}}
                            </a>

                        </form>
                    </div>
                </div>
            </div>
        @endif

        <div class="col-xs-12">
            <div class="box-content card bordered-all js__card">
                <h4 class="box-title bg-secondary with-control">
                    <i class="fa fa-car"></i> {{__('Work Cards')}}
                </h4>

                <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">
                        <li class="list-inline-item">
                            @include('admin.buttons.add-new', [
                            'route' => 'admin:work-cards.create',
                           'new' => '',
                          ])
                        </li>

                        <li class="list-inline-item">
                            @component('admin.buttons._confirm_delete_selected',[
                          'route' => 'admin:work.cards.deleteSelected',
                           ])
                            @endcomponent
                        </li>

                    </ul>
                    <div class="clearfix"></div>


                    <div class="table-responsive">
                        @php
                            $view_path = 'admin.work_cards.options-datatable';
                        @endphp
                        @include($view_path . '.option-row')
                        <div class="clearfix"></div>
                        <table id="currencies" class="table table-bordered table-responsive"
                               style="width:100%;margin-top:15px">
                            @include($view_path . '.table-thead')
                            <tfoot>
                            <tr>
                                <th class="text-center column-card-number" scope="col">{!! __('#') !!}</th>
                                <th class="text-center column-name" scope="col">{!! __('Name') !!}</th>
                                <th class="text-center column-phone" scope="col">{!! __('Phone') !!}</th>
                                <th class="text-center column-status" scope="col">{!! __('Status') !!}</th>
                                <th class="text-center column-receive" scope="col">{!! __('Receive Status') !!}</th>
                                <th class="text-center column-delivery" scope="col">{!! __('Delivery Status') !!}</th>
                                <th class="text-center column-created-at" scope="col">{!! __('Created at') !!}</th>
                                <th class="text-center column-updated-at" scope="col">{!! __('Updated at') !!}</th>
                                <th scope="col">{!! __('Expenses') !!}</th>
                                <th scope="col">{!! __('Options') !!}</th>
                                <th scope="col">{!! __('Select') !!}</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($cards as $index=>$card)
                                <tr>
                                    <td class="text-center column-card-number">{!! $card->card_number !!}</td>
                                    <td class="text-center column-name">{!! optional($card->customer)->name !!}</td>
                                    <td class="text-center column-phone">{!! optional($card->customer)->phone1 !!}</td>
                                    <td class="text-center column-status">
                                        @if($card->status == 'pending')
                                            <span class="label label-danger wg-label">{{__($card->status)}}</span>
                                        @elseif($card->status == 'processing')
                                            <span class="label label-warning wg-label">{{__($card->status)}}</span>
                                        @elseif($card->status == 'finished')
                                            <span class="label label-success wg-label">{{__($card->status)}}</span>
                                        @else
                                            <span class="label label-info wg-label">{{__($card->status)}}</span>
                                        @endif
                                    </td>
                                    <td class="text-center column-delivery">
                                        <div class="switch success">
                                            <input disabled type="checkbox"
                                                   {{$card->receive_car_status == 1 ? 'checked' : ''}}
                                                   id="switch-{{ $card->id }}">
                                            <label for="user-{{ $card->id }}"></label>
                                        </div>
                                    </td>
                                    <td class="text-center column-receive">
                                        <div class="switch success">
                                            <input disabled type="checkbox"
                                                   {{$card->delivery_car_status == 1 ? 'checked' : ''}}
                                                   id="switch-{{ $card->id }}">
                                            <label for="delivery-{{ $card->id }}"></label>
                                        </div>
                                    </td>

                                    <td class="text-center column-created-at">{!! $card->created_at->format('y-m-d h:i:s A') !!}</td>
                                    <td class="text-center column-updated-at">{!! $card->updated_at->format('y-m-d h:i:s A') !!}</td>

                                    <td>
                                        @if($card->cardInvoice)
                                            <a href="{{route('admin:card.invoices.revenue.receipts',
                                             ['id' => $card->cardInvoice->id])}}"
                                               class="btn btn-info-wg hvr-radial-out  ">
                                                <i class="fa fa-money"></i> {{__('Payments')}}
                                        @endif
                                    </td>

                                    <td>

                                        @if(!$card->cardInvoice || $card->cardInvoice && $card->cardInvoice->maintenanceDetectionTypes->count())
                                            <a class="btn btn-wg-show hvr-radial-out  "
                                               href="{{route('admin:work.cards.invoice.create',$card->id)}}">
                                                <i class="fa fa-eye"></i> {{__('Show services')}}
                                            </a>
                                        @endif
                                        {{--                                            @component('admin.buttons._show_button',[--}}
                                        {{--                                                       'id' => $card->id,--}}
                                        {{--                                                       'route'=>'admin:users.show'--}}
                                        {{--                                                        ])--}}
                                        {{--                                            @endcomponent--}}

                                        @component('admin.buttons._edit_button',[
                                                    'id' => $card->id,
                                                    'route'=>'admin:work-cards.edit'
                                                     ])
                                        @endcomponent

                                        @component('admin.buttons._delete_button',[
                                                    'id'=>$card->id,
                                                    'route' => 'admin:work-cards.destroy',
                                                    'tooltip' => __('Delete '.$card['card_number']),
                                                     ])
                                        @endcomponent

                                        @component('admin.sales-invoices.parts.print',[
                                                'id'=> $card->id,
                                                'invoice'=> $card,
                                                 ])
                                        @endcomponent

                                        {{--                                        <a style="cursor:pointer" class="btn btn-print-wg text-white  "--}}
                                        {{--                                           data-toggle="modal" onclick="getPrintData({{$card->id}},'image')"--}}
                                        {{--                                           data-target="#imageOfInvoice" title="{{__('print invoice image')}}">--}}
                                        {{--                                            <i class="fa fa-print"></i> {{__('Invoice Image')}}--}}
                                        {{--                                        </a>--}}

                                        @if(!$card->cardInvoice || $card->cardInvoice && !$card->cardInvoice->maintenanceDetectionTypes->count())
                                            <a href="{{route('admin:work.cards.invoice.sample.create', $card->id)}}"
                                               style="cursor:pointer" class="btn btn-print-wg text-white"
                                               title="{{__('Sample Invoice')}}">
                                                <i class="fa fa-hand-o-left"></i> {{__('Fast Invoice')}}
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        @component('admin.buttons._delete_selected',[
                                                      'id' => $card->id,
                                                      'route' => 'admin:work.cards.deleteSelected',
                                                       ])
                                        @endcomponent
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{--                            <div>--}}
                        {{--                                {{$users->appends(request()->except('page'))->links()}}--}}
                        {{--                            </div>--}}
                        {{ $cards->links() }}
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('modals')
    <div class="modal fade" id="boostrapModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel-1">{{__('Work Card Invoice')}}</h4>
                </div>

                <div class="modal-body invoiceDatatoPrint">

                </div>

                <div class="modal-footer" style="text-align:center">

                    <button type="button" class="btn btn-primary waves-effect waves-light"
                            onclick="printDownPayment()">
                        <i class='fa fa-print'></i>
                        {{__('Print')}}
                    </button>

                    <button type="button" class="btn btn-danger waves-effect waves-light"
                            data-dismiss="modal">
                        <i class='fa fa-close'></i>
                        {{__('Close')}}</button>

                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="imageOfInvoice" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel-1">{{__('Work Card Invoice Image')}}</h4>
                </div>

                <div class="modal-body invoiceDatatoPrint">

                </div>
                <div class="modal-footer" style="text-align:center">

                    <button type="button" class="btn btn-primary waves-effect waves-light"
                            onclick="printDownPayment()">
                        <i class='fa fa-print'></i>
                        {{__('Print')}}
                    </button>

                    <button type="button" class="btn btn-danger waves-effect waves-light"
                            data-dismiss="modal">
                        <i class='fa fa-close'></i>
                        {{__('Close')}}</button>

                </div>

            </div>
        </div>
    </div>
    @include($view_path . '.column-visible')
@endsection

@section('js')
    <script type="application/javascript">

        @if(request()->query('print_type') && request()->query('invoice'))

        $(document).ready(function () {

            var id = '{{request()->query('invoice')}}'

            getPrintData(id);

            $('#boostrapModal').modal('show');
        });

        @endif

        @if(request()->query('print_type') && request()->query('work_card'))

        $(document).ready(function () {

            var id = '{{request()->query('work_card')}}'

            getPrintData(id);

            $('#boostrapModal').modal('show');
        });

        @endif

        // invoke_datatable($('#currencies'));


        function printDownPayment() {
            var element_id = 'card_invoice_print', page_title = document.title;
            print_element(element_id, page_title)
        }

        function getPrintData(id, type = 'invoice') {

            $.ajax({


                url: "{{ route('admin:card.invoices.show') }}?invoiceID=" + id + '&type=' + type,
                method: 'GET',
                success: function (data) {
                    $(".invoiceDatatoPrint").html(data.invoice);
                    let total = $("#totalInLetters").text();
                    $("#totalInLetters").html( new Tafgeet(total, '{{env('DEFAULT_CURRENCY')}}').parse())
                }
            });
        }

    </script>
    <script type="application/javascript" src="{{ asset('accounting-module/options-for-dt.js') }}"></script>
@endsection
