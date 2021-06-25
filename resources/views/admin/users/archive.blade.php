@extends('admin.layouts.app')
@section('title')
    <title>{{ __('Users Archives') }} </title>
@endsection
@section('content')
    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin:users.index')}}"> {{__('Users')}}</a> </li>
                <li class="breadcrumb-item active"> {{__('Archive')}}</li>
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
                                <li class="form-group col-md-4">

                                    <label> {{ __('UserName') }} </label>

                                    {!! drawSelect2ByAjax('name','User','name','name',__('Select Name'),request()->name) !!}

                                </li>
                                <li class="form-group col-md-4">
                                    <label> {{ __('Email') }} </label>

                                    {!! drawSelect2ByAjax('email','User','email','email',__('Select Email'),request()->email) !!}

                                </li>
                                <li class="form-group col-md-4">
                                    <label> {{ __('Phone') }} </label>
                                    {!! drawSelect2ByAjax('phone','User','phone','phone',__('Select Phone'),request()->phone) !!}

                                </li>
                                @if(authIsSuperAdmin())
                                    <li class="form-group col-md-4">
                                        <label> {{ __('Branches') }} </label>
                                        {!! drawSelect2ByAjax('branch_id','Branch','name_'.app()->getLocale(),'name_'.app()->getLocale(),__('Select Branch'),request()->branch_id) !!}

                                    </li>
                                @endif

                                @if (checkIfShiftActive())
                                    <li class="form-group col-md-4">
                                        <label> {{ __('Shifts') }} </label>

                                        {!! drawSelect2ByAjax('shift_id','Shift','name_'.app()->getLocale(),'name_'.app()->getLocale(),__('Select Shift'),request()->shift_id) !!}

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
                            <a href="{{route('admin:users.archive')}}"
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
                    <i class="fa fa-user"></i> {{__('Users Archives')}}
                </h4>

                <!-- /.box-title -->
                <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">
                        <li class="list-inline-item">
                            @component('admin.buttons._confirm_force_delete_selected',[
                          'route' => 'admin:users.deleteSelected',
                           ])
                            @endcomponent
                        </li>

                        <li class="list-inline-item">
                            @component('admin.buttons._confirm_restore_selected',[
                          'route' => 'admin:users.restoreSelected',
                           ])
                            @endcomponent
                        </li>

                    </ul>
                    <div class="clearfix"></div>
                    <div class="table-responsive">
                        <table id="currencies" class="table table-bordered table-responsive" style="width:100%">

                            <thead>
                            <tr>
                                <th scope="col">{!! __('#') !!}</th>
                                <th scope="col">{!! __('Name') !!}</th>
                                <th scope="col">{!! __('UserName') !!}</th>
                                <th scope="col">{!! __('Email') !!}</th>
                                <th scope="col">{!! __('phone') !!}</th>
                                <th scope="col">{!! __('Status') !!}</th>
                            <!-- <th scope="col">{!! __('Branch') !!}</th> -->
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
                                <th scope="col">{!! __('Name') !!}</th>
                                <th scope="col">{!! __('UserName') !!}</th>
                                <th scope="col">{!! __('Email') !!}</th>
                                <th scope="col">{!! __('phone') !!}</th>
                                <th scope="col">{!! __('Status') !!}</th>
                            <!-- <th scope="col">{!! __('Branch') !!}</th> -->
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
                                    <td>{!! $user->name !!}</td>
                                    <td>{!! $user->username !!}</td>
                                    <td>{!! $user->email !!}</td>
                                    <td>{!! $user->phone !!}</td>
                                    <td>
                                        <div class="switch success">
                                            <input
                                                disabled
                                                type="checkbox"
                                                {{$user->status == 1 ? 'checked' : ''}}
                                                id="switch-{{ $user->id }}">
                                            <label for="user-{{ $user->id }}"></label>
                                        </div>
                                    </td>

                                <!-- <td>{!! optional($user->branch)->name !!}</td> -->
                                    <td>{!! $user->created_at->format('y-m-d h:i:s A') !!}</td>
                                    <td>{!! $user->updated_at->format('y-m-d h:i:s A') !!}</td>
                                    <td>
                                        <ul class="list-inline ul-wg">
                                            <li class="list-inline-item">
                                                @component('admin.buttons._force_delete',[
                                                             'id' => $user->id,
                                                             'route'=>'admin:users.force_delete'
                                                              ])
                                                @endcomponent
                                            </li>
                                            <li class="list-inline-item">

                                                @component('admin.buttons._restore_delete',[
                                                             'id'=>$user->id,
                                                             'route' => 'admin:users.restore_delete',
                                                             'tooltip' => __('Force Delete '.$user['name']),
                                                              ])
                                                @endcomponent
                                            </li>
                                        </ul>
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
