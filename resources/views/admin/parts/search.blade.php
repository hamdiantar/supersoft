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
            <form action="{{route('admin:parts.index')}}" method="get" id="filtration-form">
                <input type="hidden" name="rows" value="{{ isset($_GET['rows']) ? $_GET['rows'] : '' }}"/>
                <input type="hidden" name="key" value="{{ isset($_GET['key']) ? $_GET['key'] : '' }}"/>
                <input type="hidden" name="sort_method" value="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'asc' }}"/>
                <input type="hidden" name="sort_by" value="{{ isset($_GET['sort_by']) ? $_GET['sort_by'] : '' }}"/>
                <input type="hidden" name="invoker" value="search"/>

                <div class="list-inline margin-bottom-0 row">

                    <div class="col-md-12">

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

                        <div class="form-group col-md-4">
                            <label> {{ __('spart') }} </label>
                            <div class="input-group">
                                <span class="input-group-addon fa fa-file-o"></span>
                                {!! drawSelect2ByAjax('part_name','Part', 'name_'.app()->getLocale(),'name_'.app()->getLocale(),  __('parts'),request()->part_name) !!}

                            </div>
                        </div>

                        <div class="form-group col-md-4">
                            <label> {{ __('Store') }} </label>
                            <div class="input-group">
                                <span class="input-group-addon fa fa-building-o"></span>
                                {!! drawSelect2ByAjax('store_id','Store','name_'.app()->getLocale(),'name_'.app()->getLocale(),__('Store Name'), request()->store_id) !!}

                            </div>
                        </div>

                        <div class="form-group col-md-4">
                            <label> {{ __('Type') }} </label>
                            <div class="input-group">
                                <span class="input-group-addon fa fa-check"></span>
                                <select class="form-control select2" name="partId" id="loadAllParts">
                                    {!! loadPartTypeSelectAsTree() !!}
                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-12">

                        <div class="form-group col-md-4">
                            <label> {{ __('supplier') }} </label>
                            <div class="input-group">
                                <span class="input-group-addon fa fa-user"></span>
                                {!! drawSelect2ByAjax('supplier_id','Supplier','name_'.app()->getLocale(),'name_'.app()->getLocale(),__('Select supplier'), request()->supplier_id) !!}

                                {{--                                <select name="supplier_id" class="form-control js-example-basic-single">--}}
{{--                                    <option value="">{{__('Select supplier')}}</option>--}}
{{--                                    @foreach($suppliers as $k=>$v)--}}
{{--                                        <option value="{{$k}}">{{$v}}</option>--}}
{{--                                    @endforeach--}}
{{--                                </select>--}}
                            </div>
                        </div>


                        <div class="switch primary form-group col-md-2">
                            <input type="checkbox" id="switch-2" name="active"  {{ isset($_GET['active']) && $_GET['active'] == "on" ? 'checked' : ''}}>
                            <label for="switch-2">{{__('Active')}}</label>
                        </div>

                        <div class="switch primary form-group col-md-2">
                            <input type="checkbox" id="switch-3" name="inactive"  {{ isset($_GET['inactive']) && $_GET['inactive'] == "on" ? 'checked' : ''}}>
                            <label for="switch-3">{{__('inActive')}}</label>
                        </div>

                    </div>

                    <div class="col-md-12">

                        <div class="form-group col-md-4">
                            <label> {{ __('Barcode') }} </label>
                            <div class="input-group">
                                <span class="input-group-addon fa fa-barcode"></span>
                                <input type="text" name="barcode" class="form-control" value="{{$_GET['barcode'] ?? null}}">
                            </div>
                        </div>

                        <div class="form-group col-md-4">
                            <label> {{ __('Supplier Barcode') }} </label>
                            <div class="input-group">
                                <span class="input-group-addon fa fa-barcode"></span>
                                <input type="text" name="supplier_barcode" class="form-control"  value="{{$_GET['barcode'] ?? null}}">
                            </div>
                        </div>

                    </div>

                </div>

                <button type="submit"
                        class="btn sr4-wg-btn   waves-effect waves-light hvr-rectangle-out"><i
                        class=" fa fa-search "></i> {{__('Search')}} </button>
                <a href="{{route('admin:parts.index')}}"
                   class="btn bc-wg-btn   waves-effect waves-light hvr-rectangle-out"><i
                        class=" fa fa-reply"></i> {{__('Back')}}
                </a>

            </form>
        </div>
        <!-- /.card-content -->
    </div>
    <!-- /.box-content -->
</div>
