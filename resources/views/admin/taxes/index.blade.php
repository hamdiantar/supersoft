@extends('admin.layouts.app')
@section('title')
    <title>{{ __('Taxes And Fees') }} </title>
@endsection
@section('content')
    <div class="row small-spacing">
        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Taxes And Fees')}}</li>
            </ol>
        </nav>

        @include('admin.taxes.parts.search')
        <div class="col-xs-12">
            <div class="box-content card bordered-all js__card">
                <h4 class="box-title bg-secondary with-control">
                    <i class="fa fa-money"></i> {{__('Taxes And Fees')}}
                </h4>

                <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">
                        <li class="list-inline-item">
                            @include('admin.buttons.add-new', [
                       'route' => 'admin:taxes.create',
                           'new' => '',
                          ])
                        </li>

                        <li class="list-inline-item">
                            @component('admin.buttons._confirm_delete_selected',[
                                   'route' => 'admin:taxes.deleteSelected',
                                    ])
                            @endcomponent
                        </li>

                    </ul>
                    <div class="clearfix"></div>
                    <div class="table-responsive">

                        <table id="taxes" class="table table-bordered wg-table-print table-hover" style="width:100%">
                            <thead>
                            <tr>
                                <th scope="col">{!! __('#') !!}</th>
                                <th scope="col">{!! __('Name') !!}</th>
                                <th scope="col">{!! __('Tax /Fee Value') !!}</th>
                                <th scope="col">{!! __('Type') !!}</th>
                                <!-- <th scope="col">{!! __('Invoices') !!}</th>
                                <th scope="col">{!! __('Quotations') !!}</th>
                                <th scope="col">{!! __('Services') !!}</th>
                                <th scope="col">{!! __('Purchase Invoice') !!}</th>
                                <th scope="col">{!! __('For Parts') !!}</th>
                                <th scope="col">{!! __('Purchase Quotation') !!}</th>
                                <th scope="col">{!! __('Execution Time') !!}</th> -->
                                <th scope="col">{!! __('Created At') !!}</th>
                                <th scope="col">{!! __('Updated At') !!}</th>
                                <th scope="col">{!! __('Options') !!}</th>
                                <th scope="col">
                                    <div class="checkbox danger">
                                        <input type="checkbox" id="select-all">
                                        <label for="select-all"></label>
                                    </div>{!! __('Select') !!}</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th scope="col">{!! __('#') !!}</th>
                                <th scope="col">{!! __('Name') !!}</th>
                                <th scope="col">{!! __('Tax /Fee Value') !!}</th>
                                <th scope="col">{!! __('Type') !!}</th>
                                <!-- <th scope="col">{!! __('Invoices') !!}</th>
                                <th scope="col">{!! __('Quotations') !!}</th>
                                <th scope="col">{!! __('Services') !!}</th>
                                <th scope="col">{!! __('Purchase Invoice') !!}</th>
                                <th scope="col">{!! __('For Parts') !!}</th>
                                <th scope="col">{!! __('Purchase Quotation') !!}</th>
                                <th scope="col">{!! __('Execution Time') !!}</th> -->
                                <th scope="col">{!! __('Created At') !!}</th>
                                <th scope="col">{!! __('Updated At') !!}</th>
                                <th scope="col">{!! __('Options') !!}</th>
                                <th scope="col">{!! __('Select') !!}</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($taxes as $index=>$tax)
                                <tr>
                                    <td>{!! $index +1 !!}</td>
                                    <td>{!! $tax->name !!}</td>
                                    <td>{!! $tax->value !!}</td>
                                    <td>
                            @if($tax->tax_type === 'amount')
                                    <span class="label label-info wg-label"> {{ __('Amount') }} </span>
                                    @else
                                    <span class="label label-primary wg-label"> {{ __('Percentage') }} </span>
                            @endif
                            </td>
                            <!-- <td>
                                    @if($tax->active_invoices == 1 )
                                            <span class="label label-primary wg-label"> {{ __('Active') }} </span>
                                        @else
                                        <span class="label label-info wg-label"> {{ __('inActive') }} </span>
                                      @endif
                             </td>

                             <td>
                                    @if($tax->active_offers == 1 )
                                            <span class="label label-primary wg-label"> {{ __('Active') }} </span>
                                        @else
                                        <span class="label label-info wg-label"> {{ __('inActive') }} </span>
                                        @endif
                             </td>

                             <td>
                                    @if($tax->active_services == 1 )
                                            <span class="label label-primary wg-label"> {{ __('Active') }} </span>
                                        @else
                                        <span class="label label-info wg-label"> {{ __('inActive') }} </span>
                                        @endif
                             </td>

                             <td>
                                    @if($tax->active_purchase_invoice == 1 )
                                            <span class="label label-primary wg-label"> {{ __('Active') }} </span>
                                        @else
                                        <span class="label label-info wg-label"> {{ __('inActive') }} </span>
                                        @endif
                             </td>

                             <td>
                                    @if($tax->on_parts == 1 )
                                            <span class="label label-primary wg-label"> {{ __('Active') }} </span>
                                        @else
                                        <span class="label label-info wg-label"> {{ __('inActive') }} </span>
                                        @endif
                             </td>

                                    <td>
                                        @if($tax->purchase_quotation == 1 )
                                            <span class="label label-primary wg-label"> {{ __('Active') }} </span>
                                        @else
                                            <span class="label label-info wg-label"> {{ __('inActive') }} </span>
                                        @endif
                                    </td>

                                    <td>{!! __($tax->execution_time) !!}</td> -->
                                    <td>{!! $tax->created_at->format('y-m-d h:i:s A') !!}</td>
                                    <td>{!! $tax->updated_at->format('y-m-d h:i:s A') !!}</td>
                                    <td>

                                    <div class="btn-group margin-top-10">
                                        
                                        <button type="button" class="btn btn-options dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="ico fa fa-bars"></i>
                                        {{__('Options')}} <span class="caret"></span>
                                     
                                    </button> 
                                    <ul class="dropdown-menu dropdown-wg">
                                            <li>
                                            
                                            @component('admin.buttons._edit_button',[
                                                    'id'=>$tax->id,
                                                    'route' => 'admin:taxes.edit',
                                                     ])
                                        @endcomponent
                
                                            </li>
                                            <li class="btn-style-drop">
                                                

                                            @component('admin.buttons._delete_button',[
                                                    'id'=> $tax->id,
                                                    'route' => 'admin:taxes.destroy',
                                                     ])
                                        @endcomponent
                
                                            </li>

                                        </ul>
                                    </div>
<!-- 
                                        @component('admin.buttons._edit_button',[
                                                    'id'=>$tax->id,
                                                    'route' => 'admin:taxes.edit',
                                                     ])
                                        @endcomponent

                                        @component('admin.buttons._delete_button',[
                                                    'id'=> $tax->id,
                                                    'route' => 'admin:taxes.destroy',
                                                     ])
                                        @endcomponent -->
                                    </td>
                                    <td>
                                        @component('admin.buttons._delete_selected',[
                                                'id' => $tax->id,
                                                'route' => 'admin:taxes.deleteSelected',
                                                 ])
                                        @endcomponent
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script type="application/javascript">
        invoke_datatable($('#taxes'))
    </script>
@endsection
