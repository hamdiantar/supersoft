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
                <form id="filtration-form">
                    <input type="hidden" name="rows" value="{{ isset($_GET['rows']) ? $_GET['rows'] : '' }}"/>
                    <input type="hidden" name="key" value="{{ isset($_GET['key']) ? $_GET['key'] : '' }}"/>
                    <input type="hidden" name="sort_method"
                           value="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : '' }}"/>
                    <input type="hidden" name="sort_by" value="{{ isset($_GET['sort_by']) ? $_GET['sort_by'] : '' }}"/>
                    <input type="hidden" name="invoker"/>

                    <div class="row">

                        @if(authIsSuperAdmin())
                            <div class="form-group col-md-12">
                                <label> {{ __('Branches') }} </label>

                                <div class="input-group">
                                    <span class="input-group-addon fa fa-file"></span>

                                    <select name="branch_id" class="form-control js-example-basic-single" id="branchId">
                                        <option value="">{{ __('Select Branch') }}</option>
                                        @foreach(\App\Models\Branch::all() as $branch)
                                            <option
                                                {{ isset($_GET['branch_id']) && $_GET['branch_id'] == $branch->id ? 'selected' : '' }}
                                                value="{{ $branch->id }}"> {{ $branch->name }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif

                        <div class="form-group col-md-4">
                            <label> {{ __('words.store-from') }} </label>

                            <div class="input-group">
                                <span class="input-group-addon fa fa-building-o"></span>

                                {!! drawSelect2ByAjax('store_from','Store','name_'.app()->getLocale(),'name_'.app()->getLocale(),__('Store Name'), request()->store_from) !!}
                            </div>
                        </div>

                        <div class="form-group col-md-4">
                            <label> {{ __('words.store-to') }} </label>

                            <div class="input-group">
                                <span class="input-group-addon fa fa-building-o"></span>

                                {!! drawSelect2ByAjax('store_to','Store','name_'.app()->getLocale(),'name_'.app()->getLocale(),__('Store Name'),  request()->store_to) !!}

                            </div>
                        </div>

                        <div class="form-group col-md-4">
                            <label> {{ __('opening-balance.serial-number') }} </label>
                            <div class="input-group">
                                <span class="input-group-addon fa fa-bars"></span>
                                {!! drawSelect2ByAjax('transfer_number','StoreTransfer','transfer_number', 'transfer_number',__('opening-balance.serial-number'),  request()->transfer_number) !!}
                            </div>
                        </div>

                        <div class="form-group col-md-4">
                            <label> {{ __('parts') }} </label>
                            <div class="input-group">
                                <span class="input-group-addon fa fa-gear"></span>
                                {!! drawSelect2ByAjax('part_id','Part', 'name_'.app()->getLocale(),'name_'.app()->getLocale(),  __('opening-balance.select-one'),request()->part_name) !!}
                            </div>
                        </div>

                        <div class="form-group col-md-4">
                            <label>{{ __('Barcode') }} </label>
                            <div class="input-group">
                                <span class="input-group-addon fa fa-barcode"></span>
                                <input type="text" name="barcode" class="form-control" value=" {{ $_GET['barcode'] ?? null  }}">
                            </div>
                        </div>

                        <div class="form-group col-md-4">
                            <label> {{ __('Supplier Barcode') }} </label>
                            <div class="input-group">
                                <span class="input-group-addon fa fa-barcode"></span>
                                <input type="text" name="supplier_barcode" class="form-control" value=" {{ $_GET['supplier_barcode'] ?? null  }}">
                            </div>
                        </div>

                        <div class="form-group col-md-4">
                            <label> {{ __('words.date-from') }} </label>
                            <div class="input-group">
                                <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                                <input name="date_from"
                                       value="{{ isset($_GET['date_from']) ? $_GET['date_from'] : '' }}"
                                       class="form-control date js-example-basic-single" type="date"/>
                            </div>
                        </div>

                        <div class="form-group col-md-4">
                            <label> {{ __('words.date-to') }} </label>
                            <div class="input-group">
                                <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                                <input name="date_from"
                                       value="{{ isset($_GET['date_from']) ? $_GET['date_from'] : '' }}"
                                       class="form-control date js-example-basic-single" type="date"/>
                            </div>
                        </div>

                        <div class="form-group col-md-4">
                            <label> {{ __('Type') }} </label>
                            <div class="input-group">
                                <span class="input-group-addon fa fa-cubes"></span>
                                <select class="form-control select2" name="partId" id="loadAllParts">
                                    {!! loadPartTypeSelectAsTree() !!}
                                </select>
                            </div>
                        </div>

                            <div class="form-group col-md-4">
                                <label> {{ __('Concession Status') }} </label>
                                <div class="input-group">
                                    <span class="input-group-addon fa fa-cubes"></span>
                                    <select class="form-control js-example-basic-single" name="concession_status">
                                        <option value="">{{__('Select Status')}}</option>
                                        <option value="pending"  {{ isset($_GET['concession_status']) && $_GET['concession_status'] == 'pending' ? 'selected' : '' }}>{{__('Pending')}}</option>
                                        <option value="accepted" {{ isset($_GET['concession_status']) && $_GET['concession_status'] == 'accepted' ? 'selected' : '' }}>{{__('Accepted')}}</option>
                                        <option value="rejected" {{ isset($_GET['concession_status']) && $_GET['concession_status'] == 'rejected' ? 'selected' : '' }}>{{__('Rejected')}}</option>
                                        <option value="rejected" {{ isset($_GET['concession_status']) && $_GET['concession_status'] == 'not_found' ? 'selected' : '' }}>{{__('Not Found')}}</option>
                                    </select>
                                </div>
                            </div>

                    </div>

                    <button class="btn sr4-wg-btn waves-effect waves-light hvr-rectangle-out">
                        <i class=" fa fa-search "></i>
                        {{__('Search')}}
                    </button>
                    <a href="{{route('admin:stores-transfers.index')}}"
                       class="btn bc-wg-btn waves-effect waves-light hvr-rectangle-out"><i
                            class=" fa fa-reply"></i> {{__('Back')}}
                    </a>


                </form>

            </div>
        </div>
    </div>
@endif
