@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('Customers Categories') }} </title>
@endsection

@section('content')
    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Customers Categories')}}</li>
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
                    <form action="{{route('admin:customers-categories.index')}}" method="get">
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

                            <div class="form-group col-md-4">
                                <label> {{ __('Customer Category Name') }} </label>
                                <select name="name" class="form-control js-example-basic-single">
                                    <option value="">{{__('Select Name')}}</option>
                                    @foreach($customers_categories as $k=>$v)
                                        <option value="{{$k}}">{{$v}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="switch primary col-md-4">
                                <input type="checkbox" id="switch-slam" name="active">
                                <label for="switch-slam">{{__('Active')}}</label>
                            </div>
                            <div class="switch primary col-md-4">
                                <input type="checkbox" id="switch-ali" name="inactive">
                                <label for="switch-ali">{{__('inActive')}}</label>
                            </div>

                        </div>

                        <button type="submit"
                                class="btn sr4-wg-btn   waves-effect waves-light hvr-rectangle-out"><i
                                    class=" fa fa-search "></i> {{__('Search')}} </button>
                        <a href="{{route('admin:customers-categories.index')}}"
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
                <i class="fa fa-user"></i>  {{__('Customers Categories')}}
                 </h4>

                <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">

                        <li class="list-inline-item">
                            @include('admin.buttons.add-new', [
                       'route' => 'admin:customers-categories.create',
                           'new' => '',
                          ])
                        </li>

                            <li class="list-inline-item">
                                @component('admin.buttons._confirm_delete_selected',[
                                      'route' => 'admin:customers-categories.deleteSelected',
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
                                <th scope="col">{!! __('Customer Category Name') !!}</th>
                                <!-- <th scope="col">{!! __('Branch') !!}</th> -->
                                <th scope="col">{!! __('Status') !!}</th>
                                <th scope="col">{!! __('Sales discount type') !!}</th>
                                <th scope="col">{!! __('Sales discount') !!}</th>
                                <th scope="col">{!! __('Service discount type') !!}</th>
                                <th scope="col">{!! __('Service discount') !!}</th>
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
                                <th scope="col">{!! __('Customer Category Name') !!}</th>
                                <!-- <th scope="col">{!! __('Branch') !!}</th> -->
                                <th scope="col">{!! __('Status') !!}</th>
                                <th scope="col">{!! __('Sales discount type') !!}</th>
                                <th scope="col">{!! __('Sales discount') !!}</th>
                                <th scope="col">{!! __('Service discount type') !!}</th>
                                <th scope="col">{!! __('Service discount') !!}</th>
                               
                                <th scope="col">{!! __('created at') !!}</th>
                                <th scope="col">{!! __('Updated at') !!}</th>
                                <th scope="col">{!! __('Options') !!}</th>
                                <th scope="col">{!! __('Select') !!}</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($categories as $index=>$category)
                                <tr>
                                    <td>{!! $index +1 !!}</td>
                                    <td>{!! $category->name !!}</td>
                                    <!-- <td>{!! optional($category->branch)->name !!}</td> -->

                                    @if ($category->status == 1)
                                    <td>
                                        <div class="switch success">
                                            <input
                                                    disabled
                                                    type="checkbox"
                                                    {{$category->status == 1 ? 'checked' : ''}}
                                                    id="switch-{{ $category->id }}">
                                            <label for="category-{{ $category->id }}"></label>
                                        </div>

                                        </td>
                                    @else
                                    <td>
                                        <div class="switch success">
                                            <input
                                                    disabled
                                                    type="checkbox"
                                                    {{$category->status == 1 ? 'checked' : ''}}
                                                    id="switch-{{ $category->id }}">
                                            <label for="category-{{ $category->id }}"></label>
                                        </div>

                                        </td>
                                    @endif

                                    <td>
                                    <span class="label label-primary wg-label">   
                                        {!! __($category->sales_discount_type) !!}
                                   </span>
                                    </td>
                                    <td>{!! $category->sales_discount !!}</td>
                                    <td>
                                    <span class="label label-primary wg-label">   
                                        {!! __($category->services_discount_type) !!}
                                        </span>
                                    </td>
                                    <td>{!! $category->services_discount!!}</td>
                                    <td>{!! $category->created_at->format('y-m-d h:i:s A') !!}</td>
                                    <td>{!! $category->updated_at->format('y-m-d h:i:s A') !!}</td>

                                    <td>


                                        @component('admin.buttons._edit_button',[
                                                    'id' => $category->id,
                                                    'route'=>'admin:customers-categories.edit'
                                                     ])
                                        @endcomponent

                                        @component('admin.buttons._delete_button',[
                                                    'id'=>$category->id,
                                                    'route' => 'admin:customers-categories.destroy',
                                                    'tooltip' => __('Delete '.$category['name']),
                                                     ])
                                        @endcomponent
                                    </td>
                                    <td>
                                        @component('admin.buttons._delete_selected',[
                                               'id' => $category->id,
                                                'route' => 'admin:customers-categories.deleteSelected',
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
