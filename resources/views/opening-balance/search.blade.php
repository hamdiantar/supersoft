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
                <form action="{{route('opening-balance.index')}}" method="get">
                    <ul class="list-inline margin-bottom-0 row">
                        @if (authIsSuperAdmin())
                            <li class="form-group col-md-12">
                                <div class="form-group">
                                    <label> {{ __('Branches') }} </label>
                                    <select class="form-control select2" name="branch_id" id="branchId">
                                        <option value=""> {{ __('Select Branch') }} </option>
                                        @foreach(\App\Models\Branch::all() as $branch)
                                            <option
                                                {{ isset($_GET['branch_id']) && $_GET['branch_id'] == $branch->id ? 'selected' : '' }}
                                                value="{{ $branch->id }}"> {{ $branch->name }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </li>
                        @endif

                        <li class="form-group col-md-4">
                            <label> {{ __('Store Name') }} </label>
                            <div class="input-group">
                                <span class="input-group-addon fa fa-building-o"></span>
                                {!! drawSelect2ByAjax('store_id','Store','name_'.app()->getLocale(),'name_'.app()->getLocale(),__('Store Name'),request()->store_id) !!}
                            </div>
                        </li>

                        <li class="form-group col-md-4">
                            <label>{{__('parts')}}</label>
                            <div class="input-group">
                                <span class="input-group-addon fa fa-gear"></span>
                                {!! drawSelect2ByAjax('part_name','Part', 'name_'.app()->getLocale(),'name_'.app()->getLocale(),  __('parts'),request()->part_name) !!}
                            </div>
                        </li>

                        <li class="form-group col-md-4">
                            <label> {{ __('opening-balance.serial-number') }}</label>
                            <div class="input-group">
                                <span class="input-group-addon fa fa-bars"></span>
                                {!! drawSelect2ByAjax('serial_number','OpeningBalance','serial_number', __('opening-balance.serial-number'),request()->serial_number) !!}
                            </div>
                        </li>


                        <li class="form-group col-md-4">
                            <label>{{__('Date From')}}</label>
                            <div class="input-group">
                                <span class="input-group-addon fa fa-calendar"></span>
                                <input type="date" name="dateFrom" class="form-control">
                            </div>
                        </li>


                        <li class="form-group col-md-4">
                            <label>{{__('Date To')}}</label>
                            <div class="input-group">
                                <span class="input-group-addon fa fa-calendar"></span>
                                <input type="date" name="dateTo" class="form-control">
                            </div>
                        </li>

                        {{--                            <li class="form-group col-md-4">--}}
                        {{--                                <label> {{ __('Type') }} </label>--}}
                        {{--                                <select name="type" class="form-control js-example-basic-single">--}}
                        {{--                                    <option value="">{{__('Select Type')}}</option>--}}
                        {{--                                    {!! loadPartTypeSelectAsTree() !!}--}}
                        {{--                                </select>--}}
                        {{--                            </li>--}}


                        <li class="form-group col-md-4">
                            <label>{{ __('Barcode') }} </label>

                            <div class="input-group">
                                <span class="input-group-addon fa fa-barcode"></span>
                                <input type="text" name="barcode" class="form-control">
                            </div>
                        </li>

                        <li class="form-group col-md-4">
                            <label> {{ __('Supplier Barcode') }} </label>
                            <div class="input-group">
                                <span class="input-group-addon fa fa-barcode"></span>
                                <input type="text" name="supplier_barcode" class="form-control">
                            </div>
                        </li>
                        <li class="form-group col-md-4">
                            <label> {{ __('Type') }} </label>
                            <div class="input-group">
                                <span class="input-group-addon fa fa-cubes"></span>
                                <select class="form-control select2" name="partId" id="loadAllParts">
                                    {!! loadPartTypeSelectAsTree() !!}
                                </select>
                            </div>
                        </li>
                    </ul>

                    <button type="submit"
                            class="btn sr4-wg-btn waves-effect waves-light hvr-rectangle-out"><i
                            class=" fa fa-search "></i> {{__('Search')}} </button>
                    <a href="{{route('opening-balance.index')}}"
                       class="btn bc-wg-btn waves-effect waves-light hvr-rectangle-out"><i
                            class=" fa fa-reply"></i> {{__('Back')}}
                    </a>
                </form>

            </div>
        </div>
    </div>
@endif
