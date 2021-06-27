@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('Assets Licenses') }} </title>
@endsection
<!-- Modal -->
<div class="modal fade text-xs-left" id="add-employee-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="newAssetEmployee-form" method="post" action="{{ route('admin:assetsLicenses.store') }}">
                <div class="modal-body">

                    <div class="col-xs-12">
                        <div class="box-content card bordered-all js__card">

                            <div class="card-content js__card_content">

                                <div class="row">
                                    @csrf
                                    <input type="hidden" value="{{$asset->id}}" name="asset_id" >
                                    <input type="hidden" value="" name="asset_license_id" id="asset_license_id" >
                                    <div class="form-group col-md-12">
                                        <label>{{ __('details') }} </label>
                                        <div class="input-group">
                                            <span class="input-group-addon fa fa-barcode"></span>
                                            <input type="text" name="name" id="name" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group col-md-12">
                                            <label> {{ __('words.date-from') }} </label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                                                <input name="start_date" id="start_date"
                                                       class="form-control date js-example-basic-single" type="date"/>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-group col-md-12">
                                        <label> {{ __('words.date-to') }} </label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                                            <input name="end_date" id="end_date"
                                                   class="form-control date js-example-basic-single" type="date"/>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <label for="status" class="control-label">{{__('Status')}}</label>
                                    <div class="switch primary" style="margin-top: 15px">
                                        <input type="hidden"  name="status" value="0">
                                        <input type="checkbox" id="switch-1" name="status" value="1" CHECKED >
                                        <label for="switch-1">{{__('Active')}}</label>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary waves-effect waves-light" type="submit">
                        <i class="ico ico-left fa fa-save"></i>
                        {{__('save')}}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@section('content')
<div class="row small-spacing">
        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin:assets.index')}}"> {{__('words.assets')}}</a></li>

                <li class="breadcrumb-item active"> {{ __('asset Licenses') }}</li>
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
                    <form  method="get">
                        <div class="list-inline margin-bottom-0 row">
                            <div class="form-group col-md-4">
                                <label> {{ __('Name') }} </label>
                                {!! drawSelect2ByAjax('name','AssetLicenses','license_details',__('Select Name'),request()->name) !!}
                            </div>

                                                            <div class="form-group col-md-6">
                                                                <label> {{ __('words.date-from') }} </label>
                                                                <div class="input-group">
                                                                    <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                                                                    <input name="start_date" id="start_date"
                                                                           class="form-control date js-example-basic-single" type="date"/>
                                                                </div>
                                                            </div>

                                                            <div class="form-group col-md-4">
                                                                <label> {{ __('words.date-to') }} </label>
                                                                <div class="input-group">
                                                                    <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                                                                    <input name="end_date" id="end_date"
                                                                           class="form-control date js-example-basic-single" type="date"/>
                                                                </div>
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
                        <a href="{{\Illuminate\Support\Facades\URL::previous()}}"
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
                <i class="fa fa-cubes"></i>   {{ $asset->name. " " .__('insurance') }}
                 </h4>

                 <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">
                        <li class="list-inline-item">
                            <a style=" margin-bottom: 12px; border-radius: 5px"
                               type="button"
                               data-toggle="modal" data-target="#add-employee-modal"
                               class="btn btn-icon btn-icon-left btn-create-wg waves-effect waves-light hvr-bounce-to-left">
                                {{__('Create')}}
                                <i class="ico fa fa-plus"></i>

                            </a>
                        </li>
                        <li class="list-inline-item">

                                @component('admin.buttons._confirm_delete_selected',[
                                    'route' => 'admin:assetsLicenses.delete_selected',
                                    ])
                                @endcomponent



                        </li>
            </ul>
            <div class="clearfix"></div>
                    <div class="table-responsive">
                <table id="datatable-with-btns" class="table table-striped table-bordered display" style="width:100%">
                    <thead>
                        <tr>
                            <th scope="col"> {{ __('#') }} </th>
                            <th scope="col"> {{ __('status') }} </th>
                            <th scope="col"> {{ __('details') }} </th>
                            <th scope="col"> {{ __('start dte') }} </th>
                            <th scope="col"> {{ __('end date') }} </th>
                            <th scope="col">{!! __('Options') !!}</th>
                            <th scope="col">
                                <div class="checkbox danger">
                                    <input type="checkbox"  id="select-all">
                                    <label for="select-all"></label>
                                </div>{!! __('Select') !!}
                            </th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th scope="col"> {{ __('#') }} </th>
                            <th scope="col"> {{ __('status') }} </th>
                            <th scope="col"> {{ __('details') }} </th>
                            <th scope="col"> {{ __('start date') }} </th>
                            <th scope="col"> {{ __('end date') }} </th>
                            <th scope="col">{!! __('Options') !!}</th>
                            <th scope="col">{!! __('Select') !!}</th>
                        </tr>
                    </tfoot>
                    <tbody>
                    @if($assetsLicenses)
                        @foreach($assetsLicenses as $assetLicense)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$assetLicense->status?__('Active'):__('In-active')}}</td>
                                <td> {{ $assetLicense->license_details }} </td>
                                <td> {{ $assetLicense->start_date }} </td>
                                <td> {{ $assetLicense->end_date }} </td>
                                <td>
                                <div class="btn-group margin-top-10">

                                        <button type="button" class="btn btn-options dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="ico fa fa-bars"></i>
                                        {{__('Options')}} <span class="caret"></span>

                                    </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a style=" margin-bottom: 12px; border-radius: 5px"
                                                   type="button"
                                                   data-toggle="modal" data-target="#add-employee-modal"
                                                   data-license_id ="{{ $assetLicense->id }}"
                                                   data-name="{{ $assetLicense->license_details }}"
                                                   data-start_date="{{ $assetLicense->start_date }}"
                                                   data-end_date="{{ $assetLicense->end_date }}"
                                                   data-status="{{ $assetLicense->status }}"
                                                   class="btn btn-icon btn-icon-left btn-create-wg waves-effect waves-light hvr-bounce-to-left">
                                                    {{__('Edit')}}
                                                    <i class="ico fa fa-edit"></i>

                                                </a>

                                            </li>
                                            <li>

                                            @component('admin.buttons._delete_button',[
                                            'id'=> $assetLicense->id,
                                            'route' => 'admin:assetsLicenses.destroy',
                                             ])
                                @endcomponent

                                            </li>

                                        </ul>
                                    </div>
                                </td>
                                <td>
                                    @component('admin.buttons._delete_selected',[
                                        'id' =>  $assetLicense->id,
                                        'route' => 'admin:assetsLicenses.deleteSelected',
                                    ])
                                    @endcomponent
                                </td>
                            </tr>
                        @endforeach
                    @endif
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
{!! JsValidator::formRequest('App\Http\Requests\Admin\Asset\AssetInsuranceRequest')->selector('#newAssetEmployee-form'); !!}
    <script type="application/javascript">
        $(document).ready(function () {
            invoke_datatable($('#datatable-with-btns'))
            $(".select2").select2();
        });


        $('#add-employee-modal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var license_id = button.data('license_id');
            $('#asset_license_id').val(license_id);
            var name = button.data('name');
            $('#name').val(name);
            var start_date = button.data('start_date');
            $('#start_date').val(start_date);
            var end_date = button.data('end_date');
            $('#end_date').val(end_date);
            var status = button.data('status');
            $('.status').val(status);
        });


    </script>
@stop
