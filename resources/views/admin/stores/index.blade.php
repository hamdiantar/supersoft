@extends('admin.layouts.app')
@section('title')
<title>{{ __('Stores') }} </title>
@endsection
@section('content')
    <div class="row small-spacing">
        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
            <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Stores')}}</li>
            </ol>
        </nav>

        @include('admin.stores.parts.search')
        <div class="col-xs-12">
        <div class="box-content card bordered-all js__card">
                <h4 class="box-title bg-secondary with-control">
                <i class="fa fa-home"></i>  {{__('Stores')}}
                 </h4>

                 <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">
                       <li class="list-inline-item">
                       @include('admin.buttons.add-new', [
                  'route' => 'admin:stores.create',
                      'new' => '',
                     ])
                       </li>

                            <li class="list-inline-item">
                                @component('admin.buttons._confirm_delete_selected',[
                                      'route' => 'admin:stores.deleteSelected',
                                       ])
                                @endcomponent
                            </li>

                    </ul>
                       <div class="clearfix"></div>

<div class="table-responsive">
                        <table id="stores" class="table table-bordered wg-table-print table-hover" style="width:100%">
                            <thead>
                            <tr>
                                <th scope="col">{!! __('#') !!}</th>
                                @if(authIsSuperAdmin())
                                    <th scope="col">{!! __('Branch') !!}</th>
                                @endif
                                <th scope="col">{!! __('Store Name') !!}</th>
                                <th scope="col">{!! __('Store Creator') !!}</th>
                                <!-- <th scope="col">{!! __('Store Phone') !!}</th> -->
                                <!-- <th scope="col">{!! __('Address') !!}</th> -->
                                <th scope="col">{!! __('Created At') !!}</th>
                                <th scope="col">{!! __('Updated At') !!}</th>
                                <th scope="col">{!! __('Options') !!}</th>
                                <th scope="col">
                                <div class="checkbox danger">
                                        <input type="checkbox"  id="select-all">
                                        <label for="select-all"></label>
                                    </div>{!! __('Select') !!}</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th scope="col">{!! __('#') !!}</th>
                                @if(authIsSuperAdmin())
                                    <th scope="col">{!! __('Branch') !!}</th>
                                @endif
                                <th scope="col">{!! __('Store Name') !!}</th>
                                <th scope="col">{!! __('Store Creator') !!}</th>
                                <!-- <th scope="col">{!! __('Store Phone') !!}</th> -->
                                <!-- <th scope="col">{!! __('Address') !!}</th> -->
                                <th scope="col">{!! __('Created At') !!}</th>
                                <th scope="col">{!! __('Updated At') !!}</th>
                                <th scope="col">{!! __('Options') !!}</th>
                                <th scope="col">{!! __('Select') !!}</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($stores as $index=>$store)
                                <tr>
                                    <td>{!! $index +1 !!}</td>
                                    @if(authIsSuperAdmin())
                                        <td class="text-danger">{!! optional($store->branch)->name !!}</td>
                                    @endif
                                    <td>{!! $store->name !!}</td> 
                                    <td>
                                        @foreach($store->storeEmployeeHistories as $storeEmployeeHistory)
                                            <p class="employeeData">{{optional($storeEmployeeHistory->employee)->name}}
                                                ({{optional($storeEmployeeHistory->employee)->phone1}} / {{optional($storeEmployeeHistory->employee)->phone2}} )</p>
                                        @endforeach
                                    </td>
                                    <!-- <td>{!! $store->store_phone !!}</td> -->
                                    <!-- <td>{!! $store->store_address !!}</td> -->
                                    <td>{!! $store->created_at->format('y-m-d h:i:s A') !!}</td>
                                    <td>{!! $store->updated_at->format('y-m-d h:i:s A') !!}</td>
                                    <td>

                                    <div class="btn-group margin-top-10">
                                        
                                        <button type="button" class="btn btn-options dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="ico fa fa-bars"></i>
                                        {{__('Options')}} <span class="caret"></span>
                                     
                                    </button> 
                                        <ul class="dropdown-menu dropdown-wg">
                                            <li>
                                            

                                            <a class="btn btn-wg-show hvr-radial-out" onclick="loadDataWithModal('{{$store->id}}')" data-id="{{$store->id}}">
                                                <i class="fa fa-eye"></i> {{__('Show')}}
                                            </a>
                
                                            </li>
                                            <li>
                                                
                                            @component('admin.buttons._edit_button',[
                                                    'id'=>$store->id,
                                                    'route' => 'admin:stores.edit',
                                                     ])
                                        @endcomponent
                                            </li>

                                            <li class="btn-style-drop">
                                            @component('admin.buttons._delete_button',[
                                                    'id'=> $store->id,
                                                    'route' => 'admin:stores.destroy',
                                                     ])
                                        @endcomponent
                                            </li>
                                            
                                        </ul>
                                    </div>
<!-- 
                                    <a class="btn btn-wg-show hvr-radial-out" onclick="loadDataWithModal('{{$store->id}}')" data-id="{{$store->id}}">
                                                <i class="fa fa-eye"></i> {{__('Show')}}
                                            </a>

                                        @component('admin.buttons._edit_button',[
                                                    'id'=>$store->id,
                                                    'route' => 'admin:stores.edit',
                                                     ])
                                        @endcomponent

                                        @component('admin.buttons._delete_button',[
                                                    'id'=> $store->id,
                                                    'route' => 'admin:stores.destroy',
                                                     ])
                                        @endcomponent -->

                                    </td>
                                    <td>
                                    @component('admin.buttons._delete_selected',[
                                         'id' => $store->id,
                                          'route' => 'admin:stores.deleteSelected',
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

@section('modals')
    <div class="modal fade wg-content" id="empModal" role="dialog">
        <div class="modal-dialog" style="width:800px;">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-body">
                    <div class="box-loader">
                        <p>{{__('Loading')}}</p>
                        <div class="loader-31"></div>
                    </div>
                    <div id="storeData">

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script type="application/javascript">
        invoke_datatable($('#stores'))

        function loadDataWithModal(storeId) {
            event.preventDefault();
            $.ajax({
                url: '{{route('admin:stores.show')}}',
                type: 'post',
                data: {
                    _token: '{{csrf_token()}}',
                    storeId: storeId
                },
                success: function (response) {
                    $('#empModal').modal('show');
                    setTimeout( () => {
                        $('.box-loader').hide();
                        $('#storeData').html(response.data);
                        },3000)
                }
            });
        }

        $('#empModal').on('hidden.bs.modal', function () {
            $('.box-loader').show();
            $('#storeData').html('');
        })
    </script>

@endsection

