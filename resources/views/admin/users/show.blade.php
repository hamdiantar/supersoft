
@extends('admin.layouts.app')
@section('title')
<title>{{__('User Activities')}}</title>
@endsection
@section('content')

<div class="row small-spacing">


        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
            <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin:users.index')}}"> {{__('Users')}}</a></li>
                <li class="breadcrumb-item active"> {{__('User Activities')}}</li>
            </ol>
        </nav>


        <div class="col-xs-12 search-tb">
        <div class="box-content card bordered-all js__card top-search">
					<h4 class="box-title with-control">
                    <i class="fa fa-user"></i>{{__('User Info')}}
						<span class="controls">
							<button type="button" class="control fa fa-minus js__card_minus"></button>
							<button type="button" class="control fa fa-times js__card_remove"></button>
						</span>
						<!-- /.controls -->
					</h4>
					<!-- /.box-title -->
					<div class="card-content js__card_content">

                <div class="card-content">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                            <tbody>
                               <th>{{__('Name')}}</th>
                               <td>{{$user->name}}</td>
                            </tbody>
                            </table>
                            <!-- /.row -->
                        </div>
                        <!-- /.col-md-6 -->
                        <div class="col-md-6">
                        <table class="table table-bordered">
                            <tbody>
                               <th>{{__('Email')}}</th>
                               <td>{{$user->email}}</td>
                            </tbody>
                            </table>

                        </div>
                        <!-- /.col-md-6 -->
                        <div class="col-md-6">
                        <table class="table table-bordered">
                            <tbody>
                               <th>{{__('Phone')}}</th>
                               <td>{{$user->phone}}</td>
                            </tbody>
                            </table>

                        </div>

                        <div class="col-md-6">

                        <table class="table table-bordered">
                            <tbody>
                               <th>{{__('Branch')}}</th>
                               <td>{{optional($user->branch)->name}}</td>
                            </tbody>
                            </table>
                        </div>
                        <!-- /.col-md-6 -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.card-content -->
            </div>
            <!-- /.box-content card -->
        </div>
        </div>

        <div class="clearfix"></div>

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
					<div class="card-content js__card_content">
                    <form action="{{route('admin:users.show',$user->id)}}" method="get">

                        <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                            <label> {{__('Model name')}} </label>
                                <input type="text" name="model" class="form-control" id="exampleInputEmail2"
                                       placeholder="{{__('Model name')}}">
                            </div>
                            </div>
                            <div class="col-md-4">
                            <div class="form-group">
                                <label> {{__('Date From')}} </label>
                                <input type="date" name="date_from" class="form-control" id="exampleInputEmail2">
                            </div>
                            </div>
                            <div class="col-md-4">
                            <div class="form-group">
                                <label> {{__('Date To')}} </label>
                                <input type="date" name="date_to" class="form-control" id="exampleInputEmail2">
                            </div>
                            </div>
                            <div class="col-md-4">
                            <div class="form-group">

                                <button type="submit"
                                class="btn sr4-wg-btn   waves-effect waves-light hvr-rectangle-out"><i
                                    class=" fa fa-search "></i> {{__('Search')}} </button>
                        <a href="{{route('admin:users.show',$user->id)}}"
                           class="btn bc-wg-btn   waves-effect waves-light hvr-rectangle-out"><i class=" fa fa-reply"></i> {{__('Back')}}
                        </a>

                            </div>
                            </div>
                            </div>
                    </form>
                </div>
                <!-- /.card-content -->
            </div>
            <!-- /.box-content -->
        </div>

        <div class="clearfix"></div>

        <div class="col-xs-12">
            <div class="box-content card bordered-all success">
            <span class="ribbon-bottom ribbon-b-r">
                <span><i class="fa fa-user"></i> {{__('User Activity')}}</span>
                 </span>

                <span class="ribbon-bottom ribbon-b-r">
                <span><i class="fa fa-user"></i> {{__('User Activity')}}</span>
                 </span>
                <ul class="list-inline pull-left top-margin-wg">
                    <li class="list-inline-item">
                        <button style="margin-left: 12px !important;" type="button" class="btn btn-wg-delete hvr-radial-out"  onclick="confirmDelete('{{$user->id}}')">
                            <i class="fa fa-trash"></i>  {{__('Delete All Activities')}}
                        </button>
                        <form style="display: none" method="POST" id="confirmDelete{{$user->id}}" action={{route('admin:users.deleteActivities',[ 'id' => $user->id])}}>
                            @method('DELETE')
                            @csrf
                        </form>
                    </li>


                </ul>
                 <br><br><br><br>
                <div class="box-content">
                    <table id="activity" class="table table-bordered wg-table-print table-hover" style="width:100%">
                        <thead>
                        <tr>
                            <th> {{__('#')}}</th>
                            @if(authIsSuperAdmin())
                            <th>{!! __('Branch') !!}</th>
                             @endif
                            <th> {{__('Author')}}</th>
                            <th> {{__('Description')}}</th>
                            <th> {{__('Model')}}</th>
                            <th> {{__('New Data')}}</th>
                            <th> {{__('Old Data')}}</th>
                            <th> {{__('Date')}}</th>
                            <th> {{__('Action')}}</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th> {{__('#')}}</th>
                            @if(authIsSuperAdmin())
                            <th>{!! __('Branch') !!}</th>
                             @endif
                            <th> {{__('Author')}}</th>
                            <th> {{__('Description')}}</th>
                            <th> {{__('Model')}}</th>
                            <th> {{__('New Data')}}</th>
                            <th> {{__('Old Data')}}</th>
                            <th> {{__('Date')}}</th>
                            <th> {{__('Action')}}</th>
                        </tr>
                        </tfoot>
                        <tbody>
                        @foreach($loggedActivity as $log)
                            <tr>
                                <td>{{$log->id}}</td>
                                @if(authIsSuperAdmin())
                                    <td class="text-danger">{!! optional($user->branch)->name !!}</td>
                                    @endif
                                <td>
                                    @if($log->causer)
                                        {{$log->causer->name}}
                                    @else
                                        <span> {{__('No Data')}} </span>
                                    @endif
                                </td>
                                <td>{{$log->description}}</td>
                                <td>{{class_basename($log->subject_type)}}</td>
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
        invoke_datatable($('#activity'))
    </script>
@endsection
