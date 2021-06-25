@extends('admin.layouts.app')
@section('title')
    <title>{{ __('Concession Types') }} </title>
@endsection
@section('content')
    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Concession Types')}}</li>
            </ol>
        </nav>

        @include('admin.concession_types.search_form')

        <div class="col-xs-12">
            <div class="box-content card bordered-all js__card">
                <h4 class="box-title bg-secondary with-control">
                    <i class="fa fa-bell-o"></i>  {{__('Concession Types')}}
                </h4>

                <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">

                        <li class="list-inline-item">
                            @include('admin.buttons.add-new', [  'route' => 'admin:concession-types.create',  'new' => '',])
                        </li>

                        <li class="list-inline-item">
                            @component('admin.buttons._confirm_delete_selected',['route' => 'admin:concession-types.deleteSelected',])
                            @endcomponent
                        </li>

                    </ul>
                    <div class="clearfix"></div>
                    <div class="table-responsive">
                        <table id="cities" class="table table-bordered wg-table-print table-hover" style="width:100%">
                            <thead>
                            <tr>
                                <th scope="col">{!! __('#') !!}</th>
                                <th scope="col">{!! __('Name') !!}</th>
                                <th scope="col">{!! __('Type') !!}</th>
                                <th scope="col">{!! __('Concession Item') !!}</th>
                                <th scope="col">{!! __('Created Date') !!}</th>
                                <th scope="col">{!! __('Updated Date') !!}</th>
                                <th scope="col">{!! __('Options') !!}</th>

                                <th scope="col">
                                    <div class="checkbox danger">
                                        <input type="checkbox"  id="select-all">
                                        <label for="select-all"></label>
                                    </div>{!! __('Select') !!}
                                </th>

                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th scope="col">{!! __('#') !!}</th>
                                <th scope="col">{!! __('Name') !!}</th>
                                <th scope="col">{!! __('Type') !!}</th>
                                <th scope="col">{!! __('Concession Item') !!}</th>
                                <th scope="col">{!! __('Created Date') !!}</th>
                                <th scope="col">{!! __('Updated Date') !!}</th>
                                <th scope="col">{!! __('Options') !!}</th>
                                <th scope="col">{!! __('Select') !!}</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($types as $index=>$type)
                                <tr>
                                    <td>{!! $index +1 !!}</td>
                                    <td>{!! $type->name !!}</td> 
                                    <td>
                                    @if($type->type == 'add' )
                                    <span class="label label-primary wg-label">  {{ __($type->type . ' concession') }} </span>
                                    @else
                                    <span class="label label-info wg-label"> {{ __($type->type . ' concession') }} </span>
                                    @endif
                                    </td>
                                    
                                    <td class="text-danger" style="background:#FBFAD4 !important">{{ $type->concessionItem ? __($type->concessionItem->name) : '---' }}</td>
                                    <td>{{ $type->created_at }}</td>
                                    <td>{{ $type->updated_at }}</td>
                                    <td>
                                    <div class="btn-group margin-top-10">
                                        
                                        <button type="button" class="btn btn-options dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="ico fa fa-bars"></i>
                                        {{__('Options')}} <span class="caret"></span>
                                     
                                    </button> 
                                        <ul class="dropdown-menu dropdown-wg">
                                            <li>
                                            @component('admin.buttons._edit_button',[
                                                    'id'=>$type->id,
                                                    'route' => 'admin:concession-types.edit',
                                                     ])
                                        @endcomponent
                                            </li>
                                            <li class="btn-style-drop">
                                                
   
                                            @component('admin.buttons._delete_button',[
                                                    'id'=> $type->id,
                                                    'route' => 'admin:concession-types.destroy',
                                                     ])
                                        @endcomponent
                                            </li>

                                        </ul>
                                    </div>
                                        <!-- @component('admin.buttons._edit_button',[
                                                    'id'=>$type->id,
                                                    'route' => 'admin:concession-types.edit',
                                                     ])
                                        @endcomponent

                                        @component('admin.buttons._delete_button',[
                                                    'id'=> $type->id,
                                                    'route' => 'admin:concession-types.destroy',
                                                     ])
                                        @endcomponent -->
                                    </td>
                                    <td>
                                        @component('admin.buttons._delete_selected',[
                                                   'id' => $type->id,
                                                    'route' => 'admin:concession-types.deleteSelected',
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
        invoke_datatable($('#cities'))
    </script>
@endsection
