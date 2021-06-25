@extends('admin.layouts.app')
@section('title')
<title>{{ __('branches') }} </title>
@endsection
@section('content')
    <div class="row small-spacing">
        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
            <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"> {{__('branches')}}</li>
            </ol>
        </nav>

        <div class="col-xs-12">
        <div class="box-content card bordered-all js__card">
                <h4 class="box-title bg-secondary with-control">
                <i class="fa fa-building-o"></i>   {{__('branches')}}
                 </h4>

            <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">
                       <li class="list-inline-item">
                       @include('admin.buttons.add-new', [
                  'route' => 'admin:branches.create',
                      'new' => '',
                     ])
                       </li>
                  
                       <li class="list-inline-item">
                       @component('admin.buttons._confirm_delete_selected',[
                        'route' => 'admin:branches.deleteSelected',
                         ])
                      @endcomponent
               
                       </li>
                    </ul>
                    <div class="clearfix"></div>
                    <div class="table-responsive">
                <table id="branches" class="table table-bordered wg-table-print table-hover" style="width:100%">
                    <thead>
                    <tr>
                        <th scope="col">{{__('#') }}</th>
                        <th scope="col">{{__('Name')}}</th>
                        <th scope="col">{{__('Commercial Register')}}</th>
                        <th scope="col">{{__('Phone')}}</th>
                        <th scope="col">{{__('Shifts')}}</th>
                        <th scope="col">{!! __('Options') !!}</th>
                        <th scope="col">
                        <div class="checkbox danger">
                                <input type="checkbox"  id="select-all">
                                <label for="select-all"></label>
                            </div>{{__('Select')}}</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th scope="col">{{__('#') }}</th>
                        <th scope="col">{{__('Name')}}</th>
                        <th scope="col">{{__('Commercial Register')}}</th>
                        <th scope="col">{{__('Phone')}}</th>
                        <th scope="col">{{__('Shifts')}}</th>
                        <th scope="col">{{__('Options')}}</th>
                        <th scope="col">{{__('Select')}}</th>
                    </tr>
                    </tfoot>
                    <tbody>
                    @foreach($branches as $index=>$branch)
                        <tr>
                            <td>{!! $index +1 !!}</td>
                            <td>{!! $branch->name !!}</td>
                            <td>{!! $branch->mailbox_number !!}</td>
                            <td>{!! $branch->phone1 ?? $branch->phone2 !!}</td>
                            <td>
                                    @if($branch->shift_is_active == 1 )
                                            <span class="label label-success wg-label"> {{ __('Active') }} </span>
                                        @else
                                        <span class="label label-danger wg-label"> {{ __('inActive') }} </span>
                                        @endif
                                    </td>

                            <td>

                            <div class="btn-group margin-top-10">
                                        
                                        <button type="button" class="btn btn-options dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="ico fa fa-bars"></i>
                                        {{__('Options')}} <span class="caret"></span>
                                     
                                    </button> 
                                    <ul class="dropdown-menu dropdown-wg">
                                            <li>
                                            
    
                                            @component('admin.buttons._edit_button',[
                                            'id'=>$branch->id,
                                            'route' => 'admin:branches.edit',
                                             ])
                                @endcomponent
                
                                            </li>

                                            <li class="btn-style-drop">
                                            @component('admin.buttons._delete_button',[
                                            'id'=> $branch->id,
                                            'route' => 'admin:branches.destroy',
                                             ])
                                @endcomponent
                
                                            </li>

                                        </ul>
                                    </div>


                                <!-- @component('admin.buttons._edit_button',[
                                            'id'=>$branch->id,
                                            'route' => 'admin:branches.edit',
                                             ])
                                @endcomponent

                                @component('admin.buttons._delete_button',[
                                            'id'=> $branch->id,
                                            'route' => 'admin:branches.destroy',
                                             ])
                                @endcomponent -->
                            </td>
                            <td>
                            @component('admin.buttons._delete_selected',[
                                     'id' => $branch->id,
                                      'route' => 'admin:branches.deleteSelected',
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
        invoke_datatable($('#branches'))
    </script>
@endsection
