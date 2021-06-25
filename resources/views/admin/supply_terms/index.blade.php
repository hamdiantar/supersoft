@extends('admin.layouts.app')
@section('title')
    <title>{{ __('Supply & payments') }} </title>
@endsection
@section('content')
    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Supply & payments')}}</li>
            </ol>
        </nav>

        {{--        @include('admin.damaged_stock.search_form')--}}

        <div class="col-xs-12">
            <div class="box-content card bordered-all js__card">
                <h4 class="box-title bg-secondary with-control">
                    <i class="fa fa-file-o"></i> {{__('Supply & payments')}}
                </h4>

                <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">

                        <li class="list-inline-item">
                            @include('admin.buttons.add-new', [  'route' => 'admin:supply-terms.create',  'new' => '',])
                        </li>

                        {{--                        <li class="list-inline-item">--}}
                        {{--                            @component('admin.buttons._confirm_delete_selected',['route' => 'admin:damaged-stock.create.deleteSelected',])--}}
                        {{--                            @endcomponent--}}
                        {{--                        </li>--}}

                    </ul>

                    <div class="clearfix"></div>

                    <div class="table-responsive">
                        <table id="cities" class="table table-bordered wg-table-print table-hover" style="width:100%">
                            <thead>
                            <tr>
                                <th scope="col">{!! __('#') !!}</th>
                                @if(authIsSuperAdmin())
                                    <th scope="col">{!! __('Branch') !!}</th>
                                @endif
                                <th scope="col">{!! __('Type') !!}</th>
                                <th scope="col">{!! __('Term') !!}</th>

                                <th scope="col">{!! __('Purchase Quotation') !!}</th>
                                <th scope="col">{!! __('Status') !!}</th>
                               

                                <th scope="col">{!! __('Created Date') !!}</th>
                                <th scope="col">{!! __('Updated Date') !!}</th>
                                <th scope="col">{!! __('Options') !!}</th>

                                <th scope="col">
                                    <div class="checkbox danger">
                                        <input type="checkbox" id="select-all">
                                        <label for="select-all"></label>
                                    </div>{!! __('Select') !!}
                                </th>

                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th scope="col">{!! __('#') !!}</th>
                                @if(authIsSuperAdmin())
                                    <th scope="col">{!! __('Branch') !!}</th>
                                @endif
                                <th scope="col">{!! __('Type') !!}</th>
                                <th scope="col">{!! __('Term') !!}</th>

                                <th scope="col">{!! __('Purchase Quotation') !!}</th>
                                <th scope="col">{!! __('Status') !!}</th>

                                <th scope="col">{!! __('Created Date') !!}</th>
                                <th scope="col">{!! __('Updated Date') !!}</th>
                                <th scope="col">{!! __('Options') !!}</th>
                                <th scope="col">{!! __('Select') !!}</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($data as $index => $item)
                                <tr>
                                    <td>{!! $index +1 !!}</td>
                                    @if(authIsSuperAdmin())
                                        <td class="text-danger">{!! optional($item->branch)->name !!}</td>
                                    @endif
                                    <td>
                                        @if($item->type == 'supply')
                                            <span class="label label-primary wg-label"> {{__('Supply')}} </span>
                                        @else
                                            <span class="label label-warning wg-label"> {{__('Payment')}} </span>
                                        @endif
                                    </td>

                                    <td>{{ \Illuminate\Support\Str::limit( $item->term, 30)}}</td>
                                    <td class="text-danger">

                                        @if($item->for_purchase_quotation)
                                            <span class="label label-success wg-label"> {{ __('Active') }} </span>
                                        @else
                                            <span class="label label-danger wg-label"> {{ __('inActive') }} </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->status)
                                            <span class="label label-success wg-label"> {{ __('Active') }} </span>
                                        @else
                                            <span class="label label-danger wg-label"> {{ __('inActive') }} </span>
                                        @endif
                                    </td>

                                  

                                    <td>{{ $item->created_at }}</td>
                                    <td>{{ $item->updated_at }}</td>


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
                                                    'route'=>'admin:supply-terms.show'
                                                     ])
                                        @endcomponent
                
                                            </li>
                                            <li>
                                                
                                            @component('admin.buttons._edit_button',[
                                                    'id'=>$item->id,
                                                    'route' => 'admin:supply-terms.edit',
                                                     ])
                                        @endcomponent
                
                                            </li>
                                            <li class="btn-style-drop">
                                            @component('admin.buttons._delete_button',[
                                                    'id'=> $item->id,
                                                    'route' => 'admin:supply-terms.destroy',
                                                     ])
                                        @endcomponent
                                            </li>

                                        </ul>
                                    </div>

                                    <!-- @component('admin.buttons._show_button',[
                                                    'id' => $item->id,
                                                    'route'=>'admin:supply-terms.show'
                                                     ])
                                        @endcomponent
                                        @component('admin.buttons._edit_button',[
                                                    'id'=>$item->id,
                                                    'route' => 'admin:supply-terms.edit',
                                                     ])
                                        @endcomponent

                                        @component('admin.buttons._delete_button',[
                                                    'id'=> $item->id,
                                                    'route' => 'admin:supply-terms.destroy',
                                                     ])
                                        @endcomponent -->



                                    </td>
                                    <td>
                                        {{--                                        @component('admin.buttons._delete_selected',[--}}
                                        {{--                                                   'id' => $type->id,--}}
                                        {{--                                                    'route' => 'admin:concession-types.deleteSelected',--}}
                                        {{--                                                    ])--}}
                                        {{--                                        @endcomponent--}}
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

@section('modals')

@endsection

@section('js-validation')

@endsection

@section('js')
    <script type="application/javascript">
        invoke_datatable($('#cities'))
    </script>
@endsection
