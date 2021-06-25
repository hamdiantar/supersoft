@extends('admin.layouts.app')


@section('title')
    <title>{{ __('Super Car') }} - {{ __('Quotations') }} </title>
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('accounting-module/daily-restriction.css') }}"/>
@endsection

@section('content')
    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active">  {{__('Quotations')}}</li>
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

                        <form action="{{route('admin:quotations.index')}}" method="get" id="filtration-form">
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
                                            @foreach($branches as $k=>$v)
                                                <option value="{{$k}}">{{$v}}</option>
                                            @endforeach
                                        </select>
                                    </li>
                                @endif

                                <li class="form-group col-md-3">
                                    <label> {{ __('Customer Name') }} </label>
                                    <select name="customer_id" class="form-control js-example-basic-single">
                                        <option value="">{{__('Select Customer Name')}}</option>
                                        @foreach($customers as $customer)
                                            <option value="{{$customer->id}}">{{$customer->name}}</option>
                                        @endforeach
                                    </select>
                                </li>

                                <li class="form-group col-md-3">
                                    <label> {{ __('Customer phone') }} </label>
                                    <select name="customer_phone" class="form-control js-example-basic-single">
                                        <option value="">{{__('Select Customer phone')}}</option>
                                        @foreach($customers as $customer)
                                            <option value="{{$customer->id}}">{{$customer->phone1}}</option>
                                        @endforeach
                                    </select>
                                </li>

                                <li class="form-group col-md-3">
                                    <label> {{ __('Quotation Number') }} </label>
                                    <select name="quotation_number" class="form-control js-example-basic-single">
                                        <option value="">{{__('Select Quotation Number')}}</option>
                                        @foreach($quotations_data as $item)
                                            <option value="{{$item->id}}">{{$item->quo_number}}</option>
                                        @endforeach
                                    </select>
                                </li>

                                <li class="form-group col-md-3">
                                    <label> {{ __('User Name') }} </label>
                                    <select name="created_by" class="form-control js-example-basic-single">
                                        <option value="">{{__('Select User Name')}}</option>
                                        @foreach($users as $k=>$v)
                                            <option value="{{$k}}">{{$v}}</option>
                                        @endforeach
                                    </select>
                                </li>

                            <!-- <li class="form-group col-md-3">
                            <label> {{ __('Chassis number') }} </label>
                                <select name="Chassis_number" class="form-control js-example-basic-single">
                                    <option value="">{{__('Select Chassis number')}}</option>
                                    @foreach($cars as $car)
                                @if($car->Chassis_number)
                                    <option value="{{$car->customer_id}}">{{$car->Chassis_number}}</option>
                                        @endif
                            @endforeach
                                </select>
                            </li> -->

                                <li class="form-group col-md-3">
                                    <label> {{ __('plate number') }} </label>
                                    <select name="plate_number" class="form-control js-example-basic-single">
                                        <option value="">{{__('Select plate number')}}</option>
                                        @foreach($cars as $car)
                                            <option value="{{$car->customer_id}}">{{$car->plate_number}}</option>
                                        @endforeach
                                    </select>
                                </li>

                                <li class="form-group col-md-3">
                                    <label> {{ __('Shift') }} </label>
                                    <select name="shift" class="form-control js-example-basic-single">
                                        <option value="">{{__('Select shift')}}</option>
                                        @foreach($shifts as $k=>$v)
                                            <option value="{{$k}}">{{$v}}</option>
                                        @endforeach
                                    </select>
                                </li>

                                <li class="form-group col-md-2">
                                    <label> {{ __('Quotation type') }} </label>
                                    <select name="type" class="form-control js-example-basic-single">
                                        <option value="">{{__('Select Quotation type')}}</option>

                                        <option value="Part">{{__('Part')}}</option>
                                        <option value="Service">{{__('Service')}}</option>
                                        <option value="Package">{{__('Package')}}</option>

                                    </select>
                                </li>

                                <li class="form-group col-md-2">
                                    <label> {{__('Date From')}} </label>
                                    <input type="date" name="date_from" class="form-control"
                                           placeholder=" {{ __('Date From') }}">
                                </li>

                                <li class="form-group col-md-2">
                                    <label> {{__('Date To')}} </label>
                                    <input type="date" name="date_to" class="form-control"
                                           placeholder=" {{ __('Date To') }}">
                                </li>

                            </ul>
                            </ul>

                            <button type="submit"
                                    class="btn sr4-wg-btn   waves-effect waves-light hvr-rectangle-out"><i
                                    class=" fa fa-search "></i> {{__('Search')}} </button>
                            <a href="{{route('admin:quotations.index')}}"
                               class="btn bc-wg-btn   waves-effect waves-light hvr-rectangle-out"><i
                                    class=" fa fa-reply"></i> {{__('Back')}}
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        {{--        @include('admin.services_packages.parts.search')--}}
        <div class="col-xs-12">
            <div class="box-content card bordered-all js__card">
                <h4 class="box-title bg-secondary with-control">
                    <i class="fa fa-file-o"></i> {{__('Quotations')}}
                </h4>

                <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">
                        <li class="list-inline-item">
                            @include('admin.buttons.add-new', [
                           'route' => 'admin:quotations.create',
                           'new' => '',
                          ])
                        </li>

                        <li class="list-inline-item">
                            @component('admin.buttons._confirm_delete_selected',[
                          'route' => 'admin:quotations.deleteSelected',
                           ])
                            @endcomponent
                        </li>

                    </ul>

                    <div class="clearfix"></div>
                    <div class="table-responsive">
                        @php
                            $view_path = 'admin.quotations.options-datatable';
                        @endphp
                        @include($view_path . '.option-row')
                        <div class="clearfix"></div>
                        <table id="quotations" class="table table-bordered" style="width:100%;margin-top:15px">
                            @include($view_path . '.table-thead')
                            <tfoot>
                            <tr>
                                <th class="text-center column-quotation-number"
                                    scope="col">{!! __('Quotation Number') !!}</th>
                                <th class="text-center column-customer-name"
                                    scope="col">{!! __('Customer Name') !!}</th>
                                <th class="text-center column-customer-phone"
                                    scope="col">{!! __('Customer Phone') !!}</th>
                            <!-- <th scope="col">{!! __('User') !!}</th> -->
                                <th class="text-center column-created-at" scope="col">{!! __('created at') !!}</th>
                                <th class="text-center column-updated-at" scope="col">{!! __('Updated at') !!}</th>
                                <th scope="col">{!! __('Options') !!}
                                </th>
                                <th scope="col">{!! __('Select') !!}</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($quotations as $quotation)
                                <tr>
                                    <td class="text-center column-quotation-number">{{$quotation->quo_number}}</td>
                                    <td class="text-center column-customer-name">{{optional($quotation->customer)->name}}</td>
                                    <td class="text-center column-customer-phone">{{optional($quotation->customer)->phone1}}</td>
                                <!-- <td>{{optional($quotation->user)->name}}</td> -->
                                    <td class="text-center column-created-at">{{$quotation->created_at->format('Y-m-d')}}</td>
                                    <td class="text-center column-updated-at">{{$quotation->updated_at->format('Y-m-d')}}</td>
                                    <td>


                                        @component('admin.buttons._edit_button',[
                                                    'id' => $quotation->id,
                                                    'route'=>'admin:quotations.edit'
                                                     ])
                                        @endcomponent

                                        @component('admin.buttons._delete_button',[
                                                    'id'=>$quotation->id,
                                                    'route' => 'admin:quotations.destroy',
                                                    'tooltip' => __('Delete '.$quotation['quotation_number']),
                                                     ])
                                        @endcomponent
                                        @component('admin.quotations.parts.print',[
                                             'id'=> $quotation->id,
                                             'quotation'=> $quotation,
                                            ])
                                        @endcomponent

                                        <button type="button"
                                                class="btn btn-primary margin-bottom-10 waves-effect waves-light"
                                                data-toggle="modal" data-target="#boostrapModal-1"
                                                onclick="quotationToSales({{$quotation->id}})">
                                            {{__('To Sales Invoice')}}
                                        </button>

                                        <button type="button"
                                                class="btn btn-primary margin-bottom-10 waves-effect waves-light"
                                                data-toggle="modal" data-target="#quotationToWorkCard"
                                                onclick="quotationToWorkCard({{$quotation->id}}); customerCars({{$quotation->id}})">
                                            {{__('To Work Card')}}
                                        </button>
                                    </td>

                                    <td>
                                        @component('admin.buttons._delete_selected',[
                                                      'id' => $quotation->id,
                                                       'route' => 'admin:quotations.deleteSelected',
                                                       ])
                                        @endcomponent
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $quotations->links() }}
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
                    <h4 class="modal-title" id="myModalLabel-1">{{__('Quotation')}}</h4>
                </div>

                <div class="modal-body" id="quotation">
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

    <div class="modal fade" id="boostrapModal-1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">{{__('To Sales Invoice')}}</h4>
                </div>
                <form action="{{route('admin:quotations.to.sales')}}" method="post">
                    @csrf
                    <div class="modal-body">

                        <p>{{__('Are You Want To Change This Quotation To Sales Invoice')}}</p>
                        <input type="hidden" name="quotation_id" id="quotation_to_sales">

                    </div>
                    <div class="modal-footer">
                    <button type="submit"
                                class="btn btn-primary btn-sm waves-effect waves-light">{{__('Change')}}</button>
                        <button type="button" class="btn btn-danger btn-sm waves-effect waves-light"
                                data-dismiss="modal">{{__('Close')}}</button>

                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="quotationToWorkCard" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">{{__('To Work Card')}}</h4>
                </div>
                <form action="{{route('admin:quotations.to.work.card')}}" method="post">
                    @csrf
                    <div class="modal-body">
                        <p>{{__('Are You Want To Change This Quotation To Work Card')}}</p>
                        <input type="hidden" name="quotation_id" id="quotation_to_work_card">

                        <div class="form-group">
                            <label>{{__('Please Select Car')}}</label>
                            <select name="car_id" class="form-control" id="customer_cars">
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer">
                    <button type="submit"
                                class="btn btn-primary btn-sm waves-effect waves-light">{{__('Change')}}</button>
                        <button type="button" class="btn btn-danger btn-sm waves-effect waves-light"
                                data-dismiss="modal">{{__('Close')}}</button>

                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script type="application/javascript">

        @if(request()->query('print_type'))

        $(document).ready(function () {

            var id = '{{request()->query('quotation')}}'

            getPrintData(id);

            $('#boostrapModal').modal('show');
        });

        @endif

        function printDownPayment() {
            var element_id = 'quotation', page_title = document.title
            print_element(element_id, page_title)
        }

        function getPrintData(id) {
            $.ajax({
                url: "{{ route('admin:quotations.show') }}?quotationId=" + id,
                method: 'GET',
                success: function (data) {
                    $("#quotation").html(data.quotation)
                    let total = $("#totalInLetters").text()
                    $("#totalInLetters").html( new Tafgeet(total, '{{env('DEFAULT_CURRENCY')}}').parse())
                }
            });
        }

        function quotationToSales(quotation_id) {

            $("#quotation_to_sales").val(quotation_id);
        }

        function quotationToWorkCard(quotation_id) {

            $("#quotation_to_work_card").val(quotation_id);
        }

        function customerCars(quotation_id) {

            $.ajax({
                url: "{{ route('admin:quotations.customer.cars')}}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },

                data: {quotation_id: quotation_id},

                success: function (data) {

                    $('.js-example-basic-single').select2();

                    $(".removeToNewData").remove();

                    $(".removeGroupsToNewData").remove();

                    $.each(data.cars, function (key, modelName) {

                        var option = new Option(modelName, modelName);

                        option.text = modelName.plate_number;

                        option.value = modelName.id;

                        $("#customer_cars").append(option);

                        $('#customer_cars option').addClass(function () {
                            return 'removeToNewData';
                        });
                    });
                },

                error: function (data) {

                    swal("Error!", "{{__('Some Thing Went Wrong')}}", "error");
                }
            });

        }

    </script>
    <script type="application/javascript" src="{{ asset('accounting-module/options-for-dt.js') }}"></script>
@endsection
