@extends('web.layout.app')

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
                <li class="breadcrumb-item"><a href="{{route('web:dashboard')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active">  {{__('Quotations')}}</li>
            </ol>
        </nav>

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

                        <form id="filtration-form">
                            <input type="hidden" name="rows" value="{{ isset($_GET['rows']) ? $_GET['rows'] : '' }}"/>
                            <input type="hidden" name="key" value="{{ isset($_GET['key']) ? $_GET['key'] : '' }}"/>
                            <input type="hidden" name="sort_method" value="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : '' }}"/>
                            <input type="hidden" name="sort_by" value="{{ isset($_GET['sort_by']) ? $_GET['sort_by'] : '' }}"/>
                            <input type="hidden" name="invoker"/>
                            <ul class="list-inline margin-bottom-0 row">

                                <li class="form-group col-md-3">
                                    <label> {{ __('Quotation Number') }} </label>
                                    <select name="quotation_number" class="form-control js-example-basic-single">
                                        <option value="">{{__('Select Quotation Number')}}</option>
                                        @foreach($quotations_data as $item)
                                            <option value="{{$item->id}}">{{$item->quo_number}}</option>
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
                                        <option value="Wensh">{{__('Wensh')}}</option>

                                    </select>
                                </li>

                                <li class="form-group col-md-2">
                                    <label> {{__('Date From')}} </label>
                                    <input type="date" name="date_from" class="form-control" placeholder=" {{ __('Date From') }}">
                                </li>

                                <li class="form-group col-md-2">
                                    <label> {{__('Date To')}} </label>
                                    <input type="date" name="date_to" class="form-control" placeholder=" {{ __('Date To') }}">
                                </li>

                            </ul>

                            <button type="submit" class="btn sr4-wg-btn waves-effect waves-light hvr-rectangle-out">
                                <i class=" fa fa-search "></i>
                                {{__('Search')}}
                            </button>
                            <a href="{{route('admin:quotations.index')}}" class="btn bc-wg-btn waves-effect waves-light hvr-rectangle-out">
                                <i class=" fa fa-reply"></i>
                                {{__('Back')}}
                            </a>
                        </form>
                    </div>
                </div>
            </div>

        <div class="col-xs-12">
            <div class="box-content card bordered-all js__card">

                <h4 class="box-title">
                    <span> <i class="fa fa-file-o"></i> {{__('Quotations')}}</span>
                </h4>

                <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">
                        <li class="list-inline-item">
                            @include('admin.buttons.add-new', [
                           'route' => 'web:quotations.create',
                           'new' => '',
                          ])
                        </li>

                        @can('delete_quotations')
                            <li class="list-inline-item">
                                @component('admin.buttons._confirm_delete_selected',[
                              'route' => 'web:quotations.deleteSelected',
                               ])
                                @endcomponent
                            </li>
                        @endcan
                    </ul>

                    <div class="clearfix"></div>
                    <div class="table-responsive">
                        @php
                            $view_path = 'web.quotations.options-datatable';
                        @endphp
                        @include($view_path . '.option-row')
                        <div class="clearfix"></div>
                        <table id="quotations" class="table table-bordered" style="width:100%;margin-top:15px">
                            @include($view_path . '.table-thead')
                            <tfoot>
                            <tr>
                                <th class="text-center column-quotation-number" scope="col">{!! __('Number') !!}</th>
                                <th class="text-center column-customer-name" scope="col">{!! __('Customer') !!}</th>
                                <th class="text-center column-customer-phone" scope="col">{!! __('Customer Phone') !!}</th>
                                <th class="text-center column-status" scope="col">{!! __('Status') !!}</th>
                                <th class="text-center column-created-at" scope="col">{!! __('created at') !!}</th>
                                <th class="text-center column-updated-at" scope="col">{!! __('Updated at') !!}</th>
                                <th scope="col">{!! __('Options') !!}
                                </th>
{{--                                <th scope="col">{!! __('Select') !!}</th>--}}
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($quotations as $quotation)
                                <tr>
                                    <td class="text-center column-quotation-number">{{$quotation->quo_number}}</td>
                                    <td class="text-center column-customer-name">{{optional($quotation->customer)->name}}</td>
                                    <td class="text-center column-customer-phone">{{optional($quotation->customer)->phone1}}</td>
                                    <td class="text-center column-status">{{$quotation->status}}</td>
                                    <td class="text-center column-created-at">{{$quotation->created_at->format('Y-m-d')}}</td>
                                    <td class="text-center column-updated-at">{{$quotation->updated_at->format('Y-m-d')}}</td>
                                    <td>


{{--                                        @component('admin.buttons._edit_button',[--}}
{{--                                                    'id' => $quotation->id,--}}
{{--                                                    'route'=>'web:quotations.edit'--}}
{{--                                                     ])--}}
{{--                                        @endcomponent--}}

{{--                                        @component('admin.buttons._delete_button',[--}}
{{--                                                    'id'=>$quotation->id,--}}
{{--                                                    'route' => 'web:quotations.destroy',--}}
{{--                                                    'tooltip' => __('Delete '.$quotation['quotation_number']),--}}
{{--                                                     ])--}}
{{--                                        @endcomponent--}}

                                        @component('admin.quotations.parts.print',[
                                             'id'=> $quotation->id,
                                             'quotation'=> $quotation,
                                            ])
                                        @endcomponent
                                    </td>

{{--                                    <td>--}}
{{--                                        @component('admin.buttons._delete_selected',[--}}
{{--                                                      'id' => $quotation->id,--}}
{{--                                                       'route' => 'web:quotations.deleteSelected',--}}
{{--                                                       ])--}}
{{--                                        @endcomponent--}}
{{--                                    </td>--}}
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

        // invoke_datatable($('#quotations'))

        function printDownPayment() {
            var element_id = 'quotation', page_title = document.title
            print_element(element_id, page_title)
        }

        function getPrintData(id) {
            $.ajax({
                url: "{{ route('web:quotations.show') }}?quotationId=" + id,
                method: 'GET',
                success: function (data) {
                    $("#quotation").html(data.quotation)
                    let total = $("#totalInLetters").text()
                    $("#totalInLetters").html(new Tafgeet(total, '{{env('DEFAULT_CURRENCY')}}').parse())
                }
            });
        }
    </script>
    <script type="application/javascript" src="{{ asset('accounting-module/options-for-dt.js') }}"></script>
@endsection
