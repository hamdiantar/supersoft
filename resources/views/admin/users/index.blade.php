@extends('admin.layouts.app')
@section('title')
    <title>{{ __('Users') }} </title>
@endsection
@section('content')
    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Users')}}</li>
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
                        <form action="{{route('admin:users.index')}}" method="get">
                            <ul class="list-inline margin-bottom-0 row">
                            @if(authIsSuperAdmin())

                                    <li class="form-group col-md-12">
                                        <label> {{ __('Branches') }} </label>
                                        <div class="input-group">
                        <span class="input-group-addon fa fa-file"></span>
                                        {!! drawSelect2ByAjax('branch_id','Branch','name_'.app()->getLocale(),'name_'.app()->getLocale(),__('Select Branch'),request()->branch_id) !!}
</div>
                                    </li>
                                @endif
                                <li class="form-group col-md-4">

                                    <label> {{ __('UserName') }} </label>
                                    <div class="input-group">
                                    <span class="input-group-addon fa fa-user"></span>
                                     {!! drawSelect2ByAjax('name','User','name','name',__('Select Name'),request()->name) !!}
                                     </div>
                                </li>
                                
                                <li class="form-group col-md-4">
                                    <label> {{ __('Email') }} </label>
                                    <div class="input-group">
                                    <span class="input-group-addon fa fa-mail-forward"></span>
                                    {!! drawSelect2ByAjax('email','User','email','email',__('Select Email'),request()->email) !!}
                                    </div>
                                </li>
                                <li class="form-group col-md-4">
                                    <label> {{ __('Phone') }} </label>
                                    <div class="input-group">
                                    <span class="input-group-addon fa fa-phone"></span>
                                    {!! drawSelect2ByAjax('phone','User','phone','phone',__('Select Phone'),request()->phone) !!}
                                    </div>
                                </li>


                                @if (checkIfShiftActive())
                                    <li class="form-group col-md-4">
                                        <label> {{ __('Shifts') }} </label>
                                        <div class="input-group">
                                        <span class="input-group-addon fa fa-file-o"></span>
                                        {!! drawSelect2ByAjax('shift_id','Shift','name_'.app()->getLocale(),'name_'.app()->getLocale(),__('Select Shift'),request()->shift_id) !!}
                                        </div>
                                    </li>
                                @endif

                                <li class="switch primary col-md-2">
                                    <input type="checkbox" id="switch-2" name="active">
                                    <label for="switch-2">{{__('Active')}}</label>
                                </li>
                                <li class="switch primary col-md-2">
                                    <input type="checkbox" id="switch-3" name="inactive">
                                    <label for="switch-3">{{__('inActive')}}</label>
                                </li>
                                <li class="form-group col-md-4">

                                </li>
                            </ul>
                            <button type="submit"
                                    class="btn sr4-wg-btn waves-effect waves-light hvr-rectangle-out"><i
                                    class=" fa fa-search "></i> {{__('Search')}} </button>
                            <a href="{{route('admin:users.index')}}"
                               class="btn bc-wg-btn waves-effect waves-light hvr-rectangle-out"><i
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
                <i class="fa fa-user"></i> {{__('Users')}}
                 </h4>

                <!-- /.box-title -->
                <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">
                        <li class="list-inline-item">
                            @include('admin.buttons.add-new', ['route' => 'admin:users.create','new' => ''])
                        </li>

                            <li class="list-inline-item">
                                @component('admin.buttons._confirm_force_delete_selected',[
                              'route' => 'admin:users.deleteSelected',
                               ])
                                @endcomponent
                            </li>


                    </ul>
                    <div class="clearfix"></div>

                    <div class="table-responsive">

                        <table id="currencies" class="table table-for-width table-bordered wg-table-print table-hover" style="width:100%">

                            <thead>
                            <tr>
                                <th scope="col">{!! __('#') !!}</th>
                                @if(authIsSuperAdmin())
                                <th scope="col">{!! __('Branch') !!}</th>
                                @endif
                                <th scope="col">{!! __('Name') !!}</th>
                                <th scope="col">{!! __('UserName') !!}</th>
                                <th scope="col">{!! __('Email') !!}</th>
                                <th scope="col">{!! __('phone') !!}</th>
                                <th scope="col">{!! __('Status') !!}</th>

                                <th scope="col">{!! __('created at') !!}</th>
                                <th scope="col">{!! __('Updated at') !!}</th>
                                <th scope="col">{!! __('Options') !!}</th>
                                <th scope="col">
                                    <div class="checkbox danger">
                                        <input type="checkbox" id="select-all">
                                        <label for="select-all"></label>
                                    </div>
                                    {!! __('Select') !!}</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th scope="col">{!! __('#') !!}</th>
                                @if(authIsSuperAdmin())
                                <th scope="col">{!! __('Branch') !!}</th>
                                @endif
                                <th scope="col">{!! __('Name') !!}</th>
                                <th scope="col">{!! __('UserName') !!}</th>
                                <th scope="col">{!! __('Email') !!}</th>
                                <th scope="col">{!! __('phone') !!}</th>
                                <th scope="col">{!! __('Status') !!}</th>

                                <th scope="col">{!! __('created at') !!}</th>
                                <th scope="col">{!! __('Updated at') !!}</th>
                                <th scope="col" class="option-wg">{!! __('Options') !!}</th>
                                <th scope="col">{!! __('Select') !!}</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($users as $index=>$user)
                                <tr>
                                    <td>{!! $index +1 !!}</td>
                                    @if(authIsSuperAdmin())
                                    <td class="text-danger">{!! optional($user->branch)->name !!}</td>
                                    @endif
                                    <td>{!! $user->name !!}</td>
                                    <td>{!! $user->username !!}</td>
                                    <td>{!! $user->email !!}</td>
                                    <td>{!! $user->phone !!}</td>
                                    <td>
                                    @if($user->status == 1 )
                                            <span class="label label-success wg-label"> {{ __('Active') }} </span>
                                        @else
                                        <span class="label label-danger wg-label"> {{ __('inActive') }} </span>
                                        @endif
                                    </td>

                                    <td>{!! $user->created_at->format('y-m-d h:i:s A') !!}</td>
                                    <td>{!! $user->updated_at->format('y-m-d h:i:s A') !!}</td>
                                    <td>

                                    <div class="btn-group margin-top-10"> 
                                        
						<button type="button" class="btn btn-options dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="ico fa fa-bars"></i>
                        {{__('Options')}} <span class="caret"></span>
                     
                    </button> 
						<ul class="dropdown-menu dropdown-wg">
							<li>
                            
                            @component('admin.buttons._show_button',[
                                                            'id' => $user->id,
                                                            'route'=>'admin:users.show'
                                                             ])
                                                @endcomponent

                            </li>
							<li>
                                
                            @component('admin.buttons._edit_button',[
                                                             'id' => $user->id,
                                                             'route'=>'admin:users.edit'
                                                              ])
                                                @endcomponent

                            </li>
                            @if(!$user->is_admin_branch)
							<li>

                            <a onclick="confirmForceDeleteUser({{$user->id}})" class="btn btn-wg-delete hvr-radial-out" href="{{route('admin:users.force_delete',['id' => $user->id])}}">
                                                        <i class="fa fa-trash"></i>  {{__('Force Delete')}}
                                                    </a>
                                                    <form style="display: none" method="POST" id="confirmForceDelete{{$user->id}}" action={{route('admin:users.force_delete',[ 'id' => $user->id])}}>
                                                        @method('DELETE')
                                                        @csrf
                                                    </form>

                            </li>
                            @endif
						</ul>
					</div>
                    
                                        <!-- <ul class="list-inline ul-wg">
                                            <li class="list-inline-item">
                                                @component('admin.buttons._show_button',[
                                                            'id' => $user->id,
                                                            'route'=>'admin:users.show'
                                                             ])
                                                @endcomponent
                                            </li>
                                            <li class="list-inline-item">
                                                @component('admin.buttons._edit_button',[
                                                             'id' => $user->id,
                                                             'route'=>'admin:users.edit'
                                                              ])
                                                @endcomponent
                                            </li>
                                            @if(!$user->is_admin_branch)
                                                <li class="list-inline-item">
                                                    <a onclick="confirmForceDeleteUser({{$user->id}})" class="btn btn-wg-delete hvr-radial-out" href="{{route('admin:users.force_delete',['id' => $user->id])}}">
                                                        <i class="fa fa-trash"></i>  {{__('Force Delete')}}
                                                    </a>
                                                    <form style="display: none" method="POST" id="confirmForceDelete{{$user->id}}" action={{route('admin:users.force_delete',[ 'id' => $user->id])}}>
                                                        @method('DELETE')
                                                        @csrf
                                                    </form>
                                                </li>
                                             @endif
                                        </ul>
 -->

                                    </td>
                                    <td>

                                        @component('admin.buttons._delete_selected',[
                                                      'id' => $user->id,
                                                      'route' => 'admin:users.deleteSelected',
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
        invoke_datatable($('#currencies'))
    </script>
@endsection
