@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('words.assets') }} </title>
@endsection

@section('content')
    <div class="row small-spacing">
        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"> {{ __('words.assets') }}</li>
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
                    <div class="card-content js__card_content" style="padding:30px">
                        <form action="" method="get">
                            <div class="list-inline margin-bottom-0 row">

                                    @if(authIsSuperAdmin())
                                        <div class="form-group col-md-12">
                                            <label> {{ __('Branches') }} </label>
                                            <div class="input-group">
                                                <span class="input-group-addon fa fa-file"></span>
                                                {!! drawSelect2ByAjax('branch_id','Branch','name_'.app()->getLocale(),'name_'.app()->getLocale(),__('Select Branch'),request()->branch_id) !!}
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label> {{ __('Asset Group') }} </label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                                                <select class="form-control select2" id="asset_group_id"
                                                        name="asset_group_id">
                                                    <option value="0"> {{ __('Select Group') }} </option>
                                                    @foreach($assetsGroups as $assetGroup)
                                                        <option
                                                            {{ old('assetsGroups_id') == $assetGroup->id ? 'selected' : '' }}
                                                            value="{{ $assetGroup->id }}"
                                                            rate="{{ $assetGroup->annual_consumtion_rate }}"> {{ $assetGroup->name_ar }} </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label> {{ __('Asset Type') }} </label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                                                <select class="form-control select2" name="asset_type_id"
                                                        id="asset_type_id">
                                                    <option value="0"> {{ __('Select Type') }} </option>
                                                    @foreach($assetsTypes as $assetType)
                                                        <option
                                                            {{ old('asset_type_id') == $assetType->id ? 'selected' : '' }}
                                                            value="{{ $assetType->id }}"> {{ $assetType->name_ar }} </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label> {{ __('Name') }} </label>
                                        {!! drawSelect2ByAjax('name','Asset','name_'.app()->getLocale(),'name_'.app()->getLocale(),__('Select Name'),request()->name) !!}
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label> {{ __('Asset Status') }} </label>
                                            <div class="input-group">
                                                <ul class="list-inline">
                                                    <li>
                                                        <div class="radio info">
                                                            <input type="radio" id="radio_status_1" name="asset_status"
                                                                   value="1">
                                                            <label for="radio_status_1">{{ __('continues') }}</label>
                                                        </div>
                                                    </li>

                                                    <li>
                                                        <div class="radio info">
                                                            <input id="radio_status_2" type="radio" name="asset_status"
                                                                   value="2">
                                                            <label for="radio_status_2">{{ __('sell') }}</label>
                                                        </div>
                                                    </li>

                                                    <li>
                                                        <div class="radio info">
                                                            <input type="radio" id="radio_status_3" name="asset_status"
                                                                   value="3">
                                                            <label for="radio_status_3">{{ __('ignore') }}</label>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label> {{ __('consumtion rate From') }}</label>
                                        <input type="text" class="form-control" name="annual_consumtion_rate1">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label> {{ __('consumtion rate To') }}</label>
                                        <input type="text" class="form-control" name="annual_consumtion_rate2">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label> {{ __('asset age From') }}</label>
                                        <input type="text" class="form-control" name="asset_age1">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label> {{ __('asset age To') }}</label>
                                        <input type="text" class="form-control" name="asset_age2">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label> {{ __('cost of purchase From') }}</label>
                                        <input type="text" class="form-control" name="purchase_cost1">
                                    </div>

                                        <div class="form-group col-md-4">
                                        <label> {{ __('cost of purchase To') }}</label>
                                        <input type="text" class="form-control" name="purchase_cost2">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label> {{ __('purchase date From') }}</label>
                                        <input type="date" class="form-control" name="purchase_date1">
                                    </div>
                                        <div class="form-group col-md-4">
                                        <label> {{ __('purchase date To') }}</label>
                                        <input type="date" class="form-control" name="purchase_date2">
                                    </div>

                                        <div class="form-group col-md-4">
                                            <label> {{ __('work date From') }}</label>
                                            <input type="date" class="form-control" name="date_of_work1">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label> {{ __('work date To') }}</label>
                                            <input type="date" class="form-control" name="date_of_work2">
                                        </div>


                                        <div class="form-group col-md-12">
                                            <label> {{ __('Employee Name') }} </label>
                                            {!! drawSelect2ByAjax('employee_id','EmployeeData', 'name_'.app()->getLocale(),'name_'.app()->getLocale(),  __('opening-balance.select-one'),request()->employee) !!}
                                        </div>

                            </div>

                            <button type="submit"
                                    class="btn sr4-wg-btn   waves-effect waves-light hvr-rectangle-out"><i
                                    class=" fa fa-search "></i> {{__('Search')}} </button>
                            <a href="{{route('admin:assets.index')}}"
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
                    <i class="fa fa-cubes"></i> {{ __('words.assets') }}
                </h4>

                <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">
                        <li class="list-inline-item">
                            @include('admin.buttons.add-new', [
                                'route' => 'admin:assets.create',
                                'new' => __(''),
                            ])
                        </li>
                        <li class="list-inline-item">

                            @component('admin.buttons._confirm_delete_selected',[
                                'route' => 'admin:assets.delete_selected',
                                ])
                            @endcomponent


                        </li>
                    </ul>
                    <div class="clearfix"></div>
                    <div class="table-responsive">
                        <table id="datatable-with-btns" class="table table-striped table-bordered display"
                               style="width:100%">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th scope="col"> {{ __('Branch') }} </th>
                                <th scope="col"> {{ __('Name') }} </th>
                            <!-- <th scope="col"> {{ __('type') }} </th> -->
                                <th scope="col"> {{ __('group') }} </th>
                                <th scope="col"> {{ __('status') }} </th>
                                <th scope="col"> {{ __('consumtion rate') }} </th>

                                <th scope="col"> {{ __('asset age') }} </th>

                                <th scope="col">{!! __('Created at') !!}</th>
                                <th scope="col">{!! __('Updated at') !!}</th>
                                <th scope="col">{!! __('Options') !!}</th>
                                <th scope="col">
                                    <div class="checkbox danger">
                                        <input type="checkbox" id="select-all">
                                        <label for="select-all"></label>
                                    </div>{!! __('Select') !!}
                                </th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>#</th>
                                <th scope="col"> {{ __('Branch') }} </th>
                                <th scope="col"> {{ __('Name') }} </th>
                            <!-- <th scope="col"> {{ __('type') }} </th> -->
                                <th scope="col"> {{ __('group') }} </th>
                                <th scope="col"> {{ __('status') }} </th>
                                <th scope="col"> {{ __('consumtion rate') }} </th>

                                <th scope="col"> {{ __('asset age') }} </th>

                                <th scope="col">{!! __('Created at') !!}</th>
                                <th scope="col">{!! __('Updated at') !!}</th>
                                <th scope="col">{!! __('Options') !!}</th>
                                <th scope="col">{!! __('Select') !!}</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($assets as $asset)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td> {{ $asset->branch->name }} </td>
                                    <td> {{ $asset->name }} </td>

                                <!-- <td > {{ $asset->type }} </td> -->
                                    <td> {{ $asset->group->name }} </td>

                                    <td>
                                        @if($asset->asset_status == 1)
                                            {{ __('continues') }}
                                        @elseif($asset->asset_status == 2)
                                            {{ __('sell') }}
                                        @else
                                            {{ __('ignore') }}
                                        @endif
                                    </td>
                                    <td> {{ $asset->annual_consumtion_rate }} </td>

                                    <td> {{ $asset->asset_age }} </td>

                                    <td> {{ $asset->created_at }} </td>
                                    <td> {{ $asset->updated_at }} </td>
                                    <td>
                                        <div class="btn-group margin-top-10">

                                            <button type="button" class="btn btn-options dropdown-toggle"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="ico fa fa-bars"></i>
                                                {{__('Options')}} <span class="caret"></span>

                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>

                                                    @component('admin.buttons._edit_button',[
                                                    'id'=>$asset->id,
                                                    'route' => 'admin:assets.edit',
                                                     ])
                                                    @endcomponent

                                                </li>
                                                <li>

                                                    @component('admin.buttons._delete_button',[
                                                    'id'=> $asset->id,
                                                    'route' => 'admin:assets.destroy',
                                                     ])
                                                    @endcomponent

                                                </li>

                                                <li>

                                                    @component('admin.buttons._show_with_text_button',[
                                                    'id'=> $asset->id,
                                                    'route' => 'admin:assetsEmployees.index',
                                                    'text' => __('employees'),
                                                     ])
                                                    @endcomponent

                                                </li>

                                                <li>

                                                    @component('admin.buttons._show_with_text_button',[
                                                    'id'=> $asset->id,
                                                    'route' => 'admin:assetsInsurances.index',
                                                    'text' => __('insurances'),
                                                     ])
                                                    @endcomponent

                                                </li>
                                                <li>

                                                    @component('admin.buttons._show_with_text_button',[
                                                    'id'=> $asset->id,
                                                    'route' => 'admin:assetsLicenses.index',
                                                    'text' => __('licenses'),
                                                     ])
                                                    @endcomponent

                                                </li>

                                                <li>

                                                    @component('admin.buttons._show_with_text_button',[
                                                    'id'=> $asset->id,
                                                    'route' => 'admin:assetsExaminations.index',
                                                    'text' => __('examinations'),
                                                     ])
                                                    @endcomponent

                                                </li>
                                                <li>

                                                    <a style="cursor:pointer" class="btn btn-print-wg text-white  "
                                                       data-toggle="modal"

                                                       onclick="getPrintData({{$asset->id}})"
                                                       data-target="#boostrapModal" title="{{__('print')}}">
                                                        <i class="fa fa-print"></i> {{__('Print')}}
                                                    </a>
                                                </li>

                                            </ul>
                                        </div>
                                    </td>
                                    <td>
                                        @component('admin.buttons._delete_selected',[
                                            'id' =>  $asset->id,
                                            'route' => 'admin:assets.deleteSelected',
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
    </div>
@stop

@section('js')
    <script type="application/javascript">
        $(document).ready(function () {
            invoke_datatable($('#datatable-with-btns'))
            $(".select2").select2()
        })

        function printAsset() {
            var element_id = 'asset_to_print', page_title = document.title
            print_element(element_id, page_title)
        }

        function getPrintData(id) {
            $.ajax({
                url: "{{ route('admin:assets.show') }}?asset_id=" + id,
                method: 'GET',
                success: function (data) {

                    $("#assetDatatoPrint").html(data.view)
                }
            });
        }

        $('#branch_id').on('change', function () {
            const CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            const branch_id = $(this).val();
            $.ajax({
                type: 'post',
                url: "{{ route('admin:assets.getAssetsGroupsByBranchId')}}",
                data: {
                    branch_id: branch_id,
                    _token: CSRF_TOKEN,
                },
                success: function (data) {
                    $('#asset_group_id').each(function () {
                        $(this).html(data.data)
                    });

                },
                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        });
        $('#branch_id').on('change', function () {
            const CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            const branch_id = $(this).val();
            $.ajax({
                type: 'post',
                url: "{{ route('admin:assets.getAssetsTypesByBranchId')}}",
                data: {
                    branch_id: branch_id,
                    _token: CSRF_TOKEN,
                },
                success: function (data) {
                    $('#asset_type_id').each(function () {
                        $(this).html(data.data)
                    });
                },
                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });


        });
    </script>


@stop

@section('modals')

    <div class="modal fade" id="boostrapModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span>
                    </button>
                <!-- <h4 class="modal-title" id="myModalLabel-1">{{__('Concession')}}</h4> -->
                </div>

                <div class="modal-body" id="assetDatatoPrint">


                </div>
                <div class="modal-footer" style="text-align:center">

                    <button type="button" class="btn btn-primary waves-effect waves-light"
                            onclick="printAsset()" id="print_sales_invoice">
                        <i class='fa fa-print'></i>
                        {{__('Print')}}
                    </button>

                    <button type="button" class="btn btn-danger waves-effect waves-light"
                            data-dismiss="modal"><i class='fa fa-close'></i>
                        {{__('Close')}}</button>

                </div>

            </div>
        </div>
    </div>
@endsection

