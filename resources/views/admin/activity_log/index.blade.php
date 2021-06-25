@extends('admin.layouts.app')
@section('title')
<title>{{__('User Activities')}} </title>
@endsection


@section('content')
    <div class="row small-spacing">
    <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
            <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"> {{__('User Activities')}}</li>
            </ol>
        </nav>


        @if(filterSetting())
        <div class="col-xs-12">
        <div class="box-content card bordered-all js__card top-search">
					<h4 class="box-title with-control"><i class="fa fa-search"></i>{{__('Search filters')}}
                <span class="controls">
							<button type="button" class="control fa fa-minus js__card_minus"></button>
							<button type="button" class="control fa fa-times js__card_remove"></button>
						</span>
                </h4>
                <!-- /.box-title -->
                <div class="card-content js__card_content">
                    <form action="{{route('admin:activity.index')}}" method="get">
                        <div class="row">
                            <div class="col-md-6">
                                <label> {{ __('UserName') }} </label>
                                {!! drawSelect2ByAjax('user','User','name','name',__('Select User')) !!}
                            </div>
                            <div class="col-md-6">
                            <label> {{__('Model name')}} </label>
                            <input type="text" name="model" class="form-control" id="exampleInputEmail2"
                                       placeholder="{{__('Model name')}}">
                            </div>
                        </div>
                        <br>
                                <button type="submit"
                                class="btn sr4-wg-btn   waves-effect waves-light hvr-rectangle-out"><i
                                    class=" fa fa-search "></i> {{__('Search')}} </button>
                        <a href="{{route('admin:activity.index')}}"
                           class="btn bc-wg-btn   waves-effect waves-light hvr-rectangle-out"><i class=" fa fa-reply"></i> {{__('Back')}}
                        </a>

                    </form>
                </div>
                <!-- /.card-content -->
            </div>
            <!-- /.box-content -->
        </div>
        @endif
        <div class="col-xs-12">
            <div class="box-content card bordered-all success">
                <h1 class="box-title bg-info"><i class="fa fa-check-square-o"></i>{{__('Activity')}}</h1>
                <div class="box-content">
                    <ul class="list-inline pull-left top-margin-wg">
                        <li class="list-inline-item">
                            <button style="margin-bottom: 12px !important;" type="button" class="btn btn-wg-delete hvr-radial-out"  onclick="confirmDelete('deleteAll')">
                                <i class="fa fa-trash"></i>  {{__('Delete All Activities')}}
                            </button>
                            <form style="display: none" method="POST" id="confirmDeletedeleteAll" action={{route('admin:activity.delete-all')}}>
                                @method('DELETE')
                                @csrf
                            </form>
                        </li>

                        <li class="list-inline-item">
                            @component('admin.buttons._confirm_force_delete_selected',[
                          'route' => 'admin:services.deleteSelected',
                           ])
                            @endcomponent
                        </li>

                        <li class="list-inline-item">
                            @component('admin.buttons._confirm_archive_selected',[
                          'route' => 'admin:services.archiveSelected',
                           ])
                            @endcomponent
                        </li>
                        <li class="list-inline-item">
                            @include('admin.buttons._archive', [
                       'route' => 'admin:activity.archive',
                           'new' => '',
                          ])
                        </li>

                    </ul>
                    <table id="activity" class="table table-bordered wg-table-print table-hover" style="width:100%">
                        <thead>
                        <tr>
                            <th> {{__('#')}}</th>
                            <th> {{__('Author')}}</th>
                            <th> {{__('Description')}}</th>
                            <th> {{__('Model')}}</th>
                            <th> {{__('New Data')}}</th>
                            <th> {{__('Old Data')}}</th>
                            <th> {{__('Date')}}</th>
                            <th> {{__('Action')}}</th>
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
                            <th> {{__('#')}}</th>
                            <th> {{__('Author')}}</th>
                            <th> {{__('Description')}}</th>
                            <th> {{__('Model')}}</th>
                            <th> {{__('New Data')}}</th>
                            <th> {{__('Old Data')}}</th>
                            <th> {{__('Date')}}</th>
                            <th> {{__('Action')}}</th>
                            <th scope="col">{!! __('Select') !!}</th>
                            {{--                            <th> Actions</th>--}}
                        </tr>
                        </tfoot>
                        <tbody>

                        @foreach($loggedActivity as $log)

                            @if(!authIsSuperAdmin() && $log->causer && $log->causer->branch_id != auth()->user()->branch_id)
                                @continue
                            @endif

                            @if(!authIsSuperAdmin() && !$log->causer )
                                @continue
                            @endif

                            <tr>

                                <td>{{$log->id}}</td>
                                <td class="text-danger">

                                    @if($log->causer)
                                        {{$log->causer->name}}
                                    @else
                                        <span> {{__('No Data')}} </span>
                                    @endif
                                </td>
                                <td>{{__($log->description)}}</td>
                                <td class="text-primary">{{__(class_basename($log->subject_type))}}</td>
                                <td>
                                    @if($log->properties->last() && is_array($log->properties->last()))
                                        @foreach($log->properties->last() as $key=>$data)
                                            <span> {{$key}} : {{$data}} </span> <br>
                                        @endforeach
                                    @else
                                    <span> {{__('No Data')}} </span>
                                    @endif
                                </td>
                                <td>
                                    @if($log->properties->first() && is_array($log->properties->first()))
                                        @foreach($log->properties->first() as $key=>$data)
                                            <span> {{$key}} : {{$data}} </span>  <br>
                                        @endforeach
                                    @else
                                    <span> {{__('No Data')}} </span>
                                    @endif
                                </td>


                                <td>{{$log->created_at}}</td>
                                <td>

                                    @component('admin.buttons._delete_button',[
                                                       'id'=>$log->id,
                                                       'route' => 'admin:activity.delete',
                                                       'tooltip' => __('Delete '. optional($log->causer)->name),
                                                        ])
                                    @endcomponent

                                </td>

                                <td>

                                    @component('admin.buttons._delete_selected',[
                                                  'id' => $log->id,
                                                  'route' => 'admin:activity.deleteSelected',
                                                   ])
                                    @endcomponent

                                </td>

                                {{--                                <td>--}}
                                {{--                                    @php--}}
                                {{--                                        $model = strtolower(class_basename($log->subject_type));--}}
                                {{--                                    @endphp--}}

                                {{--                                    <a class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only--}}
                                {{--                                         m-btn--pill custom-delete-alert-button"--}}
                                {{--                                       title="{{'Delete '.class_basename($log->subject_type).' Log'}}"--}}
                                {{--                                       data-url="{{route('admin:activity.delete',$log->id)}}">--}}
                                {{--                                        <li style="padding-top: 8px;" class="la la-trash-o"></li>--}}
                                {{--                                    </a>--}}

                                {{--                                </td>--}}
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script type="application/javascript">
        invoke_datatable($('#activity'))
    </script>
@endsection
