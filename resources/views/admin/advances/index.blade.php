@extends('admin.layouts.app')
@include('admin.employees_data.parts.rating')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('Employees Advances') }} </title>
@endsection


@section('content')
    <link rel="stylesheet" href="{{ asset('accounting-module/daily-restriction.css') }}"/>
    <div class="row small-spacing">
        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"> {{ __('Employees Advances') }}</li>
            </ol>
        </nav>
        @include('admin.advances.parts.search')

        <div class="col-xs-12">
        <div class="box-content card bordered-all js__card">
                <h4 class="box-title bg-secondary with-control">
                <i class="fa fa-money"></i>   {{ __('Employees Advances') }}
                 </h4>

                <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">
                        <li class="list-inline-item">
                            @include('admin.buttons.add-new', [
                           'route' => 'admin:advances.create',
                           'new' => '',
                          ])
                        </li>
                   
                        <li class="list-inline-item">
                            @component('admin.buttons._confirm_delete_selected',[
                            'route' => 'admin:advances.deleteSelected',
                             ])
                            @endcomponent
                        </li>
                     
                    </ul>
                    <div class="clearfix"></div>
                    <div class="table-responsive">
                        @php
                            $view_path = 'admin.advances.options-datatable';
                        @endphp
                        @include($view_path . '.option-row')
                        <div class="clearfix"></div>
                        <table id="employeeData" class="table table-bordered" style="width:100%;margin-top:15px">
                            @include($view_path . '.table-thead')
                            <tfoot>
                            <tr>
                                <th class="text-center column-name" scope="col">{!! __('Employee Name') !!}</th>
                                <th class="text-center column-type" scope="col">{!! __('Operation') !!}</th>
                                <th class="text-center column-cost" scope="col">{!! __('Amount') !!}</th>
                                <!-- <th scope="col">{!! __('Date') !!}</th> -->
                                <th class="text-center column-created-at" scope="col">{!! __('Created at') !!}</th>
                                <th class="text-center column-updated-at" scope="col">{!! __('Updated at') !!}</th>
                                <th scope="col">{!! __('Options') !!}</th>
                                <th scope="col">{!! __('Select') !!}</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($advances as $adv)
                                <tr>
                                    <td class="text-center column-name">{{optional($adv->employee)->name}}</td>
                                    <td class="text-center column-type">
                                    @if($adv->operation === __('deposit') )
                                        <span class="label label-primary wg-label"> {{ __('Deposit') }} </span>
                                    @else
                                    <span class="label label-danger wg-label"> {{ __('Withdrawal') }} </span>
                                    @endif
                                    </td>
                                    <td class="text-danger text-center column-cost">{{number_format($adv->amount, 2)}}</td>
                                        <!-- <td>{!! $adv->date !!}</td> -->
                                    <td class="text-center column-created-at">{!! $adv->created_at->format('y-m-d h:i:s A') !!}</td>
                                    <td class="text-center column-updated-at">{!! $adv->updated_at->format('y-m-d h:i:s A') !!}</td>
                                    <td>
                                        @component('admin.advances.parts.print', [
                                            'id' => $adv->id,
                                        ])
                                        @endcomponent
                                        @component('admin.buttons._edit_button',[
                                            'id'=> $adv->id,
                                            'route' => 'admin:advances.edit',
                                        ])
                                        @endcomponent
                                        @component('admin.buttons._delete_button',[
                                            'id'=> $adv->id,
                                            'route' => 'admin:advances.destroy',
                                        ])
                                        @endcomponent
                                    </td>
                                    <td>
                                        @component('admin.buttons._delete_selected',[
                                        'id' =>  $adv->id,
                                            'route' => 'admin:advances.deleteSelected',
                                        ])
                                        @endcomponent
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $advances->links() }}
                    </div>
                </div>
            </div>
        </div>
    
        <div class="remodal" data-remodal-id="showAdvance" role="dialog" aria-labelledby="modal1Title"
             aria-describedby="modal1Desc">
            <button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>
            <div class="remodal-content" id="printThis">
                <div id="DataToPrint">
                </div>
            </div>

            <button  class="btn btn-primary waves-effect waves-light" onclick="printDownPayment()">
            <i class='fa fa-print'></i>
            {{__('Print')}}</button>

            <button data-remodal-action="cancel" class="btn btn-danger waves-effect waves-light">
            <i class='fa fa-close'></i>
            {{__('Close')}}</button>
        </div>
    </div>

@endsection

@section('modals')
    @include($view_path . '.column-visible')
@endsection

@section('js')
    <script type="application/javascript">

        @if(request()->query('invoice'))

        $( document ).ready(function() {

            var id = '{{request()->query('invoice')}}'

            getPrintData(id);
        });

        @endif

        // invoke_datatable($('#employeeData'))

        function printDownPayment() {
            var element_id = "advance_print" ,page_title = document.title
            print_element(element_id ,page_title)
        }

        function getPrintData(id) {
            $("#DataToPrint").html("")
            $.ajax({
                url: "{{ url('admin/printAdvances') }}?advance_id=" + id,
                method: 'GET',
                success: function (data) {
                    $("#DataToPrint").html(data.print)
                }
            });
        }
    </script>
    <script type="application/javascript" src="{{ asset('accounting-module/options-for-dt.js') }}"></script>
@endsection
