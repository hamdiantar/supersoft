@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('words.create-asset') }} </title>
@endsection


@section('content')
    <div class="row small-spacing">
        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin:assets.index') }}"> {{__('words.assets')}}</a></li>
                <li class="breadcrumb-item active"> {{__('words.create-asset')}}</li>
            </ol>
        </nav>
        <div class="col-xs-12">
            <div class=" card box-content-wg-new bordered-all primary">
                <h4 class="box-title with-control" style="text-align: initial"><i
                        class="fa fa-cubes"></i> {{__('words.create-asset')}}
                    <span class="controls hidden-sm hidden-xs pull-left">
            <button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Save')}}<img
                    class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px"
                    src="{{asset('assets/images/f1.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Reset')}}<img
                                    class="img-fluid"
                                    style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px"
                                    src="{{asset('assets/images/f2.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px"> {{__('Back')}} <img
                                    class="img-fluid"
                                    style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px"
                                    src="{{asset('assets/images/f3.png')}}"></button>
						</span>
                </h4>
                <div class="box-content">
                    <form method="post" action="{{ route('admin:assets.store') }}">
                        @csrf

                        <div class="row">
                            @if (authIsSuperAdmin())
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label> {{ __('Branch') }} </label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                                            <select class="form-control select2" name="branch_id" id="branch_id">
                                                <option value=""> {{ __('Select Branch') }} </option>
                                                @foreach($branches as $branch)
                                                    <option {{ old('branch_id') == $branch->id ? 'selected' : '' }}
                                                            value="{{ $branch->id }}"> {{ $branch->name }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <input type="hidden" name="branch_id" value="{{ auth()->user()->branch_id }}"/>
                            @endif
                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> {{ __('Asset Group') }} </label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                                            <select class="form-control select2" id="asset_group_id"
                                                    name="asset_group_id">
                                                <option value=""> {{ __('Select Group') }} </option>
{{--                                                @foreach($assetsGroups as $assetGroup)--}}
{{--                                                    <option--}}
{{--                                                        {{ old('assetsGroups_id') == $assetGroup->id ? 'selected' : '' }}--}}
{{--                                                        value="{{ $assetGroup->id }}"--}}
{{--                                                        rate="{{ $assetGroup->annual_consumtion_rate }}"> {{ $assetGroup->name_ar }} </option>--}}
{{--                                                @endforeach--}}
                                            </select>
                                        </div>
                                        {{input_error($errors,'asset_group_id')}}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> {{ __('Asset Type') }} </label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                                            <select class="form-control select2" name="asset_type_id"
                                                    id="asset_type_id">
                                                <option value=""> {{ __('Select Type') }} </option>
{{--                                                @foreach($assetsTypes as $assetType)--}}
{{--                                                    <option--}}
{{--                                                        {{ old('asset_type_id') == $assetType->id ? 'selected' : '' }}--}}
{{--                                                        value="{{ $assetType->id }}"> {{ $assetType->name_ar }} </option>--}}
{{--                                                @endforeach--}}
                                            </select>
                                        </div>
                                        {{input_error($errors,'asset_type_id')}}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> {{ __('Asset Status') }} </label>
                                        <div class="input-group">
                                            <ul class="list-inline">
                                                <li>
                                                    <div class="radio info">
                                                        <input type="radio" id="radio_status_1" name="asset_status"
                                                               value="1" checked>
                                                        <label for="radio_status_1">{{ __('continues') }}</label>
                                                    </div>
                                                </li>

                                                <li>
                                                    <div class="radio info">
                                                        <input id="radio_status_2" disabled type="radio" name="asset_status"
                                                               value="2">
                                                        <label for="radio_status_2">{{ __('sell') }}</label>
                                                    </div>
                                                </li>

                                                <li>
                                                    <div class="radio info">
                                                        <input type="radio" disabled id="radio_status_3" name="asset_status"
                                                               value="3">
                                                        <label for="radio_status_3">{{ __('ignore') }}</label>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label> {{ __('words.asset-name-ar') }} </label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-cube"></i></span>
                                        <input class="form-control" name="name_ar"/>
                                    </div>
                                    {{input_error($errors,'name_ar')}}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label> {{ __('words.asset-name-en') }} </label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-cube"></i></span>
                                        <input class="form-control" name="name_en"/>
                                    </div>
                                    {{input_error($errors,'name_en')}}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label> {{ __('annual_consumtion_rate') }} </label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                        <input class="form-control"  value="0" name="annual_consumtion_rate"
                                               id="annual_consumtion_rate"/>
                                    </div>
                                    {{input_error($errors,'annual_consumetion_rate')}}
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label> {{ __('Notes') }} </label>
                                    <textarea class="form-control" name="asset_details"
                                              placeholder="{{ __('Notes') }}">{{ old('notes') }}</textarea>
                                </div>
                                {{input_error($errors,'asset_details')}}
                            </div>


                        <!-- <div class="col-md-4">
                            <div class="form-group">
                                <label> {{ __('asset age') }} </label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input class="form-control " disabled type="text" name="asset_age"/>
                                </div>
                                {{input_error($errors,'asset_age')}}
                            </div>
                        </div> -->

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label> {{ __('purchase date') }} </label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        <input class="form-control datepicker" type="date" name="purchase_date"/>
                                    </div>
                                    {{input_error($errors,'purchase_date')}}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label> {{ __('date of work') }} </label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        <input class="form-control datepicker" type="date" name="date_of_work"/>
                                    </div>
                                    {{input_error($errors,'date_of_work')}}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label> {{ __('cost of purchase') }} </label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                                        <input class="form-control " type="text" value="0" name="purchase_cost"/>
                                    </div>
                                    {{input_error($errors,'purchase_cost')}}
                                </div>
                            </div>

                        <!-- <div class="col-md-4">
                            <div class="form-group">
                                <label> {{ __('previous consumption') }} </label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-hashed">#</i></span>
                                    <input class="form-control " type="text" name="past_consumtion"/>
                                </div>
                                {{input_error($errors,'past_consumtion')}}
                            </div>
                        </div> -->

                        <!-- <div class="col-md-4">
                            <div class="form-group">
                                <label> {{ __('current consumption') }} </label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-hashed">#</i></span>
                                    <input class="form-control " type="text" name="current_consumtion"/>
                                </div>
                                {{input_error($errors,'current_consumtion')}}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label> {{ __('total current consumption') }} </label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-hashed">#</i></span>
                                    <input class="form-control " type="text" name="total_current_consumtion"/>
                                </div>
                                {{input_error($errors,'total_current_consumtion')}}
                            </div>
                        </div> -->

                        <!-- <div class="col-md-4">
                            <div class="form-group">
                                <label> {{ __('book value') }} </label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-hashed">#</i></span>
                                    <input class="form-control " type="text" name="booko_value"/>
                                </div>
                                {{input_error($errors,'book_value')}}
                            </div>
                        </div> -->

                        </div>
                        <div class="col-md-12">
                            <div class="col-md-12">
                                <div class="form-group">
                                    @include('admin.buttons._save_buttons')
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    {!! JsValidator::formRequest('App\Http\Requests\Admin\Asset\AssetRequest'); !!}
    <script type="application/javascript">
        $(document).ready(function () {
            $(".select2").select2();
            $("#asset_group_id").on('change', function () {
                $("#annual_consumtion_rate").val($("#asset_group_id option:checked").attr('rate'))
            });
        });


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


        })
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


        })

        $('#asset_group_id').on('change', function () {
            const CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            const asset_group_id = $(this).val();
            $.ajax({
                type: 'post',
                url: "{{ route('admin:assets.getAssetsGroupsAnnualConsumtionRate')}}",
                data: {
                    asset_group_id: asset_group_id,
                    _token: CSRF_TOKEN,
                },
                success: function (data) {
                    $('#annual_consumtion_rate').val(data.annual_consumtion_rate);
                },
                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });


        })
    </script>
@stop
