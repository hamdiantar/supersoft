@extends('admin.layouts.app')
@section('title')
    <title>{{ __('Super Car') }} - {{ __('Lockers') }} </title>
@endsection
@section('content')
    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item">  {{__('Lockers')}}</li>
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
                <div class="card-content js__card_content">
                    <form action="{{route('admin:lockers.index')}}" method="get">
                        <div class="list-inline margin-bottom-0 row">

                            @if(authIsSuperAdmin())
                                <div class="form-group col-md-12">
                                    <label> {{ __('Branch') }} </label>
                                    <select name="branch_id" id="branch_id"
                                            class="form-control js-example-basic-single" onchange="getByBranch()">
                                        <option value="">{{__('Select Branch')}}</option>
                                        @foreach($branches as $k=>$v)
                                            <option value="{{$k}}">{{$v}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <div class="form-group col-md-4" id="data_by_branch">
                                <label> {{ __('Locker name') }} </label>
                                <select name="name" class="form-control js-example-basic-single">
                                    <option value="">{{__('Select Name')}}</option>
                                    @foreach($lockers_search as $k=>$v)
                                        <option value="{{$k}}">{{$v}}</option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="switch primary col-md-4">

                                <input type="checkbox" id="switch-2" name="active">
                                <label for="switch-2">{{__('Active')}}</label>
                            </div>
                            <div class="switch primary col-md-4">
                                <input type="checkbox" id="switch-3" name="inactive">
                                <label for="switch-3">{{__('inActive')}}</label>
                            </div>

                        </div>
                        <button type="submit"
                                class="btn sr4-wg-btn   waves-effect waves-light hvr-rectangle-out"><i
                                    class=" fa fa-search "></i> {{__('Search')}} </button>
                        <a href="{{route('admin:lockers.index')}}"
                           class="btn bc-wg-btn   waves-effect waves-light hvr-rectangle-out"><i
                                    class=" fa fa-reply"></i> {{__('Back')}}
                        </a>
                    </form>
                </div>
                <!-- /.card-content -->
            </div>
            <!-- /.box-content -->
        </div>
        @endif
        
        <div class="col-xs-12">
            <div class="box-content card bordered-all js__card">
                <h4 class="box-title bg-secondary with-control">
                <i class="fa fa-folder-open-o"></i>  {{__('Lockers')}}
                 </h4>

                <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">
                        <li class="list-inline-item">
                            @include('admin.buttons.add-new', [
                            'route' => 'admin:lockers.create',
                           'new' => '',
                          ])

                        </li>
              
                            <li class="list-inline-item">

                                @component('admin.buttons._confirm_delete_selected',[
                             'route' => 'admin:lockers.deleteSelected',
                              ])
                                @endcomponent
                            </li>
               
                    </ul>
                    <div class="clearfix"></div>
                    <div class="table-responsive">
                        <table id="currencies" class="table table-bordered" style="width:100%">
                            <thead>
                            <tr>
                                <th scope="col">{!! __('#') !!}</th>
                                <th scope="col">{!! __('Locker name') !!}</th>
                            <!-- <th scope="col">{!! __('Branch') !!}</th> -->

                                <th scope="col">{!! __('Locker balance') !!}</th>
                                <th scope="col">{!! __('Status') !!}</th>
                                <th scope="col">{!! __('created at') !!}</th>
                                <th scope="col">{!! __('Updated at') !!}</th>
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
                                <th scope="col">{!! __('Locker name') !!}</th>
                            <!-- <th scope="col">{!! __('Branch') !!}</th> -->

                                <th scope="col">{!! __('Locker balance') !!}</th>
                                <th scope="col">{!! __('Status') !!}</th>
                                <th scope="col">{!! __('created at') !!}</th>
                                <th scope="col">{!! __('Updated at') !!}</th>
                                <th scope="col">{!! __('Options') !!}</th>
                                <th scope="col">{!! __('Select') !!}</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($lockers as $index=>$locker)
                                <tr>
                                    <td>{!! $index +1 !!}</td>
                                    <td>{!! $locker->name !!}</td>
                                <!-- <td>{!! optional($locker->branch)->name !!}</td> -->
                                    <td class="text-danger">{!! $locker->balance !!}</td>
                                    @if ($locker->status == 1)
                                        <td>
                                            <div class="switch success">
                                                <input
                                                        disabled
                                                        type="checkbox"
                                                        {{$locker->status == 1 ? 'checked' : ''}}
                                                        id="switch-{{ $locker->id }}">
                                                <label for="locker-{{ $locker->id }}"></label>
                                            </div>
                                        </td>

                                    @else
                                        <td>
                                            <div class="switch success">
                                                <input
                                                        disabled
                                                        type="checkbox"
                                                        {{$locker->status == 1 ? 'checked' : ''}}
                                                        id="switch-{{ $locker->id }}">
                                                <label for="locker-{{ $locker->id }}"></label>
                                            </div>
                                        </td>

                                    @endif


                                    <td>{!! $locker->created_at->format('y-m-d h:i:s A') !!}</td>
                                    <td>{!! $locker->updated_at->format('y-m-d h:i:s A') !!}</td>

                                    <td>

                                        @component('admin.buttons._edit_button',[
                                                    'id' => $locker->id,
                                                    'route'=>'admin:lockers.edit'
                                                     ])
                                        @endcomponent


                                        @if(!$locker->revenueReceipts || !$locker->expensesReceipts)
                                            @component('admin.buttons._delete_button',[
                                                        'id'=>$locker->id,
                                                        'route' => 'admin:lockers.destroy',
                                                        'tooltip' => __('Delete '.$locker['name']),
                                                         ])
                                            @endcomponent
                                        @endif

                                    </td>
                                    <td>
                                        @component('admin.buttons._delete_selected',[
                                                      'id' => $locker->id,
                                                       'route' => 'admin:lockers.deleteSelected',
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

        function getByBranch() {

            var id = $("#branch_id").val();

            $.ajax({
                url: '{{route('admin:get.data.by.branch')}}',
                type: "get",
                data: {id: id},
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function (data) {

                    $("#data_by_branch").html(data);
                    $('.js-example-basic-single').select2();

                }, error: function (jqXhr, json, errorThrown) {
                    swal("sorry!", 'sorry please try later', "error");
                },
            });
        }

        invoke_datatable($('#currencies'))
    </script>

    <script type="application/javascript">
        $(document).ready(function () {
            $('.select_2').select2();
        });
    </script>


@endsection
