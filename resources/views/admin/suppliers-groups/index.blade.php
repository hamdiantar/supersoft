@extends('admin.layouts.app')
@section('title')
    <title>{{ __('Super Car') }} - {{ __('Suppliers Groups') }} </title>
@endsection

@section('style')
    <link rel="stylesheet" href="{{asset('assets/plugin/iCheck/skins/square/blue.css')}}">
@endsection
@section('content')
    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Suppliers Groups')}}</li>
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
                    <form action="{{route('admin:suppliers-groups.index')}}" method="get">
                        <div class="list-inline margin-bottom-0 row">

                            @if(authIsSuperAdmin())
                                <div class="form-group col-md-12">
                                    <label> {{ __('Branch') }} </label>
                                    <select name="branch_id" class="form-control js-example-basic-single">
                                        <option value="">{{__('Select Branch')}}</option>
                                        @foreach($branches as $k=>$v)
                                            <option value="{{$k}}">{{$v}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <div class="form-group col-md-6">
                                <label> {{ __('Supplier Group Name') }} </label>
                                <select name="name" class="form-control js-example-basic-single">
                                    <option value="">{{__('Select Name')}}</option>
                                    @foreach($suppliers_groups as $k=>$v)
                                        <option value="{{$k}}">{{$v}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="switch primary col-md-6">
                            <ul class="list-inline">
                              <li>
                              <input type="checkbox" id="switch-2" name="active">
                                <label for="switch-2">{{__('Active')}}</label>
                              </li>
                              <li>
                              <input type="checkbox" id="switch-3" name="inactive">
                                <label for="switch-3">{{__('inActive')}}</label>
                              </li>
                            </ul>

                            </div>

                        </div>
                        <button type="submit"
                                class="btn sr4-wg-btn   waves-effect waves-light hvr-rectangle-out"><i
                                    class=" fa fa-search "></i> {{__('Search')}} </button>
                        <a href="{{route('admin:suppliers-groups.index')}}"
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
                <i class="fa fa-user"></i>  {{__('Suppliers Groups')}}
                 </h4>

                <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">

                        <li class="list-inline-item">
                            @include('admin.buttons.add-new', [
                       'route' => 'admin:suppliers-groups.create',
                           'new' => '',
                          ])
                        </li>
                            <li class="list-inline-item">
                                @component('admin.buttons._confirm_delete_selected',[
                             'route' => 'admin:suppliers.groups.deleteSelected',
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
                                <th scope="col">{!! __('Supplier Group Name') !!}</th>
                                <th scope="col">{!! __('Status') !!}</th>
                                <th scope="col">{!! __('Discount Type') !!}</th>
                                <th scope="col">{!! __('Discount') !!}</th>
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
                                <th scope="col">#</th>
                                <th scope="col">{!! __('Supplier Group Name') !!}</th>
                                <th scope="col">{!! __('Status') !!}</th>
                                <th scope="col">{!! __('Discount Type') !!}</th>
                                <th scope="col">{!! __('Discount') !!}</th>
                                <th scope="col">{!! __('created at') !!}</th>
                                <th scope="col">{!! __('Updated at') !!}</th>
                                <th scope="col">{!! __('Options') !!}</th>
                                <th scope="col">{!! __('Select') !!}</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($groups as $index=>$group)
                                <tr>
                                    <td>{!! $index +1 !!}</td>
                                    <td>{!! $group->name !!}</td>
                                    @if ($group->status)
                                    <td>
                                        <div class="switch success">
                                            <input
                                                    disabled
                                                    type="checkbox"
                                                    {{$group->status == 1 ? 'checked' : ''}}
                                                    id="switch-{{ $group->id }}">
                                            <label for="group-{{ $group->id }}"></label>
                                        </div>

                                        </td>
                                    @else
                                    <td>
                                        <div class="switch success">
                                            <input
                                                    disabled
                                                    type="checkbox"
                                                    {{$group->status == 1 ? 'checked' : ''}}
                                                    id="switch-{{ $group->id }}">
                                            <label for="group-{{ $group->id }}"></label>
                                        </div>

                                        </td>
                                    @endif
                                    <td>
                                    <span class="label label-primary wg-label">  
                                        {{__($group->discount_type )}}
                                        </span>
                                    </td>
                                    <td class="text-danger">{!! $group->discount !!}</td>

                                    <td>{!! $group->created_at->format('y-m-d h:i:s A') !!}</td>
                                    <td>{!! $group->updated_at->format('y-m-d h:i:s A') !!}</td>

                                    <td>

                                    <!--
                                @component('admin.buttons._show_button',[
                                               'id' => $group->id,
                                               'route'=>'admin:suppliers-groups.show'
                                                ])
                                    @endcomponent
                                            -->

                                        @component('admin.buttons._edit_button',[
                                                    'id' => $group->id,
                                                    'route'=>'admin:suppliers-groups.edit'
                                                     ])
                                        @endcomponent

                                        @component('admin.buttons._delete_button',[
                                                    'id'=>$group->id,
                                                    'route' => 'admin:suppliers-groups.destroy',
                                                    'tooltip' => __('Delete '.$group['name']),
                                                     ])
                                        @endcomponent
                                    </td>
                                    <td>
                                        @component('admin.buttons._delete_selected',[
                                               'id' => $group->id,
                                               'route' => 'admin:suppliers.groups.deleteSelected',
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

        $("#selectAll").click(function () {

            $(".to_checked").prop("checked", $(this).prop("checked"));
        });

        $(".to_checked").click(function () {
            if (!$(this).prop("checked")) {
                $("#selectAll").prop("checked", false);
            }
        });

    </script>

    <script type="application/javascript">
        invoke_datatable($('#currencies'))
    </script>

@endsection
