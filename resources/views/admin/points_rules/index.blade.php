@extends('admin.layouts.app')
@section('title')
    <title>{{ __('Points Rules') }} </title>
@endsection
@section('content')
    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Points Rules')}}</li>
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

                        <form action="{{route('admin:points-rules.index')}}" method="get">
                            <ul class="list-inline margin-bottom-0 row">

                                @if(authIsSuperAdmin())
                                    <li class="form-group col-md-4">
                                        <label> {{ __('Branches') }} </label>
                                        {!! drawSelect2ByAjax('branch_id','Branch','name_'.app()->getLocale(),'name_'.app()->getLocale(),__('Select Branch'),request()->branch_id) !!}
                                    </li>
                                @endif

                                    <li class="form-group col-md-4">
                                        <label> {{ __('Search') }} </label>
                                        <input type="text" class="form-control" name="search" placeholder="{{__('amount, points, text')}}">
                                    </li>

                            </ul>
                            <button type="submit" class="btn sr4-wg-btn waves-effect waves-light hvr-rectangle-out">
                                <i class=" fa fa-search "></i> {{__('Search')}}
                            </button>

                            <a href="{{route('admin:points-rules.index')}}"
                               class="btn bc-wg-btn waves-effect waves-light hvr-rectangle-out">
                                <i class=" fa fa-reply"></i> {{__('Back')}}
                            </a>
                        </form>

                    </div>
                </div>
            </div>
        @endif


        <div class="col-xs-12">

            <div class="box-content card bordered-all js__card">
                <h4 class="box-title bg-secondary with-control">
                    <i class="fa fa-user"></i> {{__('Points Rules')}}
                </h4>

                <!-- /.box-title -->
                <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">
                        <li class="list-inline-item">
                            @include('admin.buttons.add-new', ['route' => 'admin:points-rules.create',
                           'new' => '',
                          ])
                        </li>

                        <li class="list-inline-item">
                            @component('admin.buttons._confirm_delete_selected',[
                          'route' => 'admin:points.rules.deleteSelected',
                           ])
                            @endcomponent
                        </li>

                    </ul>
                    <div class="clearfix"></div>

                    <div class="table-responsive">
                        <table id="currencies" class="table table-bordered table-responsive wg-table-print table-hover" style="width:100%">

                            <thead>
                            <tr>
                                <th scope="col">{!! __('#') !!}</th>
                                <th scope="col">{{__('Text Arabic')}}</th>
                                <th scope="col">{!! __('Points') !!}</th>
                                <th scope="col">{!! __('Amount money') !!}</th>
                                <th scope="col">{!! __('Status') !!}</th>
                                @if(authIsSuperAdmin())
                                <th scope="col">{!! __('Branch') !!}</th>
                                @endif
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
                                <th scope="col">{{__('Text Arabic')}}</th>
                                <th scope="col">{!! __('Points') !!}</th>
                                <th scope="col">{!! __('Amount money') !!}</th>
                                <th scope="col">{!! __('Status') !!}</th>
                                @if(authIsSuperAdmin())
                                <th scope="col">{!! __('Branch') !!}</th>
                                @endif
                                <th scope="col">{!! __('created at') !!}</th>
                                <th scope="col">{!! __('Updated at') !!}</th>
                                <th scope="col">{!! __('Options') !!}</th>
                                <th scope="col">{!! __('Select') !!}</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($rules as $index=>$rule)
                                <tr>
                                    <td>{!! $index +1 !!}</td>
                                    <td>{!! $rule->text !!} </td>
                                    <td class="text-danger">{!! $rule->points !!}</td>
                                    <td class="text-danger">{!! $rule->amount !!}</td>

                                    <td>
                                    @if($rule->status == 1 )
                                            <span class="label label-success wg-label"> {{ __('Active') }} </span>
                                        @else
                                        <span class="label label-ganger wg-label"> {{ __('inActive') }} </span>
                                        @endif
                                    </td>

                                    @if(authIsSuperAdmin())
                                    <td>{!! optional($rule->branch)->name !!}</td>
                                    @endif
                                    <td>{!! $rule->created_at->format('y-m-d h:i:s A') !!}</td>
                                    <td>{!! $rule->updated_at->format('y-m-d h:i:s A') !!}</td>
                                    <td>

                                    <div class="btn-group margin-top-10">
                                        
                                        <button type="button" class="btn btn-options dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="ico fa fa-bars"></i>
                                        {{__('Options')}} <span class="caret"></span>
                                     
                                    </button> 
                                    <ul class="dropdown-menu dropdown-wg">
                                            <li>
                                            
                                            @component('admin.buttons._edit_button',[
                                                             'id' => $rule->id,
                                                             'route'=>'admin:points-rules.edit'
                                                              ])
                                                @endcomponent
                                            </li>
                                            <li class="btn-style-drop">
                                            @component('admin.buttons._delete_button',[
                                                             'id'=>$rule->id,
                                                             'route' => 'admin:points-rules.destroy',
                                                             'tooltip' => __('Delete '.$rule['name']),
                                                              ])
                                                @endcomponent
                
                                            </li>

                                        </ul>
                                    </div>

                                        <!-- <ul class="list-inline ul-wg">

                                            <li class="list-inline-item">
                                                @component('admin.buttons._edit_button',[
                                                             'id' => $rule->id,
                                                             'route'=>'admin:points-rules.edit'
                                                              ])
                                                @endcomponent
                                            </li>
                                            <li class="list-inline-item">

                                                @component('admin.buttons._delete_button',[
                                                             'id'=>$rule->id,
                                                             'route' => 'admin:points-rules.destroy',
                                                             'tooltip' => __('Delete '.$rule['name']),
                                                              ])
                                                @endcomponent
                                            </li>
                                        </ul> -->


                                    </td>
                                    <td>

                                        @component('admin.buttons._delete_selected',[
                                                      'id' => $rule->id,
                                                      'route' => 'admin:points.rules.deleteSelected',
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
