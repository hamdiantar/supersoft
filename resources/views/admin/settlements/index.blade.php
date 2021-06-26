@extends('admin.layouts.app')
@section('title')
    <title>{{ __('Settlements') }} </title>
@endsection

@section('modals')
    @include('admin.settlements.optional-datatable.column-visible')
@endsection
@section('content')
    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Settlements')}}</li>
            </ol>
        </nav>

        @include('admin.settlements.search')

        <div class="col-xs-12">
            <div class="box-content card bordered-all js__card">
                <h4 class="box-title bg-secondary with-control">
                    <i class="fa fa-cube"></i>  {{__('Settlements')}}
                </h4>

                <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">

                        <li class="list-inline-item">
                            @include('admin.buttons.add-new', [  'route' => 'admin:settlements.create',  'new' => '',])
                        </li>

                        <li class="list-inline-item">
                            @component('admin.buttons._confirm_delete_selected',['route' => 'admin:settlements.deleteSelected',])
                            @endcomponent
                        </li>

                    </ul>

                    <div class="clearfix"></div>

                    <div class="table-responsive">
                        @include('admin.settlements.optional-datatable.option-row')
                        <table id="cities" class="table table-bordered wg-table-print table-hover" style="width:100%">
                            @include('admin.settlements.optional-datatable.table-thead')
                            <tfoot>
                            <tr>
                                <th scope="col" class="column-id">{!! __('#') !!}</th>
                                @if(authIsSuperAdmin())
                                <th scope="col" class="column-branch">{!! __('Branch') !!}</th>
                                @endif
                                <th scope="col"  class="column-date">{!! __('Date') !!}</th>
                                <th scope="col" class="column-number">{!! __('Number') !!}</th>
                                <th scope="col" class="column-number">{{__('settlement type')}}</th>
                                <th scope="col" class="column-total">{!! __('Total') !!}</th>
                                <th scope="col" class="column-status">{!! __('Concession Status') !!}</th>
                                <th scope="col" class="column-created_at">{!! __('Created Date') !!}</th>
                                <th scope="col" class="column-updated_at">{!! __('Updated Date') !!}</th>
                                <th scope="col">{!! __('Options') !!}</th>
                                <th scope="col">{!! __('Select') !!}</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($data as $index=>$item)
                                <tr>
                                    <td  class="column-id">{!! $index +1 !!}</td>
                                    @if(authIsSuperAdmin())
                                    <td class="text-danger column-branch">{!! optional($item->branch)->name !!}</td>
                                    @endif

                                    <td class="text-danger column-date">{{ $item->date }}</td>
                                    <td class="column-number">{{ $item->number }}</td>
                                    <td class="column-number">
                                        
                                        @if($item->type == 'positive' )
                                            <span class="label label-primary wg-label"> {{__('Positive')}} </span>
                                            @else
                                            <span class="label label-danger wg-label"> {{__('Negative')}} </span>
                                        @endif
    
                                        </td>
                                    <td class="column-total" style="background:#FBFAD4 !important">{{ $item->total }}</td>
                                    <td>
                                    
                                    @if( $item->concession )                 

@if( $item->concession->status == 'pending' )
<span class="label label-info wg-label"> {{__('Pending')}}</span>
@elseif( $item->concession->status == 'accepted' )
<span class="label label-success wg-label"> {{__('Accepted')}} </span>
@elseif( $item->concession->status == 'rejected' )
<span class="label label-danger wg-label"> {{__('Rejected')}} </span>
@endif

@else
<span class="label label-warning wg-label">  {{__('Not determined')}} </span>
@endif

                                     </td>
                                    <td class="column-created_at">{{ $item->created_at }}</td>
                                    <td class="column-updated_at">{{ $item->updated_at }}</td>
                                    <td>

                                    <div class="btn-group margin-top-10">

                                        <button type="button" class="btn btn-options dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="ico fa fa-bars"></i>
                                        {{__('Options')}} <span class="caret"></span>

                                    </button>
                                        <ul class="dropdown-menu dropdown-wg">
                                            <li>

                                            @component('admin.buttons._show_button',[
                                                        'id' => $item->id,
                                                        'route'=>'admin:settlements.show'
                                                         ])
                                            @endcomponent

                                            </li>
                                            <li>

                                            @component('admin.buttons._edit_button',[
                                                    'id'=>$item->id,
                                                    'route' => 'admin:settlements.edit',
                                                     ])
                                        @endcomponent

                                            </li>
                                            <li class="btn-style-drop">
                                            @component('admin.buttons._delete_button',[
                                                    'id'=> $item->id,
                                                    'route' => 'admin:settlements.destroy',
                                                     ])
                                        @endcomponent
                                            </li>

                                        </ul>
                                    </div>

                                    <!-- @component('admin.buttons._show_button',[
                                                        'id' => $item->id,
                                                        'route'=>'admin:settlements.show'
                                                         ])
                                            @endcomponent
                                        @component('admin.buttons._edit_button',[
                                                    'id'=>$item->id,
                                                    'route' => 'admin:settlements.edit',
                                                     ])
                                        @endcomponent

                                        @component('admin.buttons._delete_button',[
                                                    'id'=> $item->id,
                                                    'route' => 'admin:settlements.destroy',
                                                     ])
                                        @endcomponent -->

                                    </td>
                                    <td>
                                        @component('admin.buttons._delete_selected',[
                                                   'id' => $item->id,
                                                    'route' => 'admin:settlements.deleteSelected',
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
    @include('opening-balance.common-script')
    <script type="application/javascript" src="{{ asset('accounting-module/options-for-dt.js') }}"></script>
@endsection
