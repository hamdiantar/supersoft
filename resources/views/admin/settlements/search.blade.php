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
                <form action="{{route('admin:settlements.index')}}" method="get" id="filtration-form">
                    <input type="hidden" name="rows" value="{{ isset($_GET['rows']) ? $_GET['rows'] : '' }}"/>
                    <input type="hidden" name="key" value="{{ isset($_GET['key']) ? $_GET['key'] : '' }}"/>
                    <input type="hidden" name="sort_method" value="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'asc' }}"/>
                    <input type="hidden" name="sort_by" value="{{ isset($_GET['sort_by']) ? $_GET['sort_by'] : '' }}"/>
                    <input type="hidden" name="invoker"/>
                    <ul class="list-inline margin-bottom-0 row">
                        @if (authIsSuperAdmin())
                            <li class="form-group col-md-12">
                                <label> {{ __('Branches') }} </label>
                                <select class="form-control select2" name="branch_id" id="branchId">
                                    <option value=""> {{ __('Select Branch') }} </option>
                                    @foreach(\App\Models\Branch::all() as $branch)
                                        <option
                                            {{ isset($_GET['branch']) && $_GET['branch'] == $branch->id ? 'selected' : '' }}
                                            value="{{ $branch->id }}"> {{ $branch->name }} </option>
                                    @endforeach
                                </select>
                            </li>
                        @endif

                        <li class="form-group col-md-4">
                            <label> {{ __('Store Name') }} </label>
                            {!! drawSelect2ByAjax('store_id','Store','name_'.app()->getLocale(),'name_'.app()->getLocale(),__('Store Name'), request()->store_id) !!}
                        </li>

                        <li class="form-group col-md-4">
                            <label> {{ __('opening-balance.serial-number') }}</label>
                            {!! drawSelect2ByAjax('number','Settlement','number', 'number', __('opening-balance.serial-number'),  request()->number) !!}
                        </li>


                        <li class="form-group col-md-4">
                            <label>{{__('Date From')}}</label>
                            <input type="date" name="dateFrom" class="form-control">
                        </li>
                        <li class="form-group col-md-4">
                            <label>{{__('Date To')}}</label>
                            <input type="date" name="dateTo" class="form-control">
                        </li>


                        <li class="form-group col-md-4">
                            <label>{{__('spart')}}</label>
                            {!! drawSelect2ByAjax('part_name','Part', 'name_'.app()->getLocale(),'name_'.app()->getLocale(),  __('opening-balance.select-one'), request()->part_name) !!}
                        </li>

                        <li class="form-group col-md-4">
                            <label>{{ __('Barcode') }} </label>
                            <input type="text" name="barcode" class="form-control"  {{ $_GET['barcode'] ?? null }}>
                        </li>

                        <li class="form-group col-md-4">
                            <label> {{ __('Supplier Barcode') }} </label>
                            <input type="text" name="supplier_barcode" class="form-control"  {{ $_GET['supplier_barcode'] ?? null }}>
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

                        <li class="form-group col-md-4">
                            <label> {{ __('Concession Status') }} </label>
                            <div class="input-group">
                                <span class="input-group-addon fa fa-cubes"></span>
                                <select class="form-control js-example-basic-single" name="concession_status">
                                    <option value="">{{__('Select Status')}}</option>
                                    <option value="pending"  {{ isset($_GET['concession_status']) && $_GET['concession_status'] == 'pending' ? 'selected' : '' }}>
                                        {{__('Pending')}}
                                    </option>
                                    <option value="accepted" {{ isset($_GET['concession_status']) && $_GET['concession_status'] == 'accepted' ? 'selected' : '' }}>{{__('Accepted')}}</option>
                                    <option value="rejected" {{ isset($_GET['concession_status']) && $_GET['concession_status'] == 'rejected' ? 'selected' : '' }}>{{__('Rejected')}}</option>
                                    <option value="rejected" {{ isset($_GET['concession_status']) && $_GET['concession_status'] == 'not_found' ? 'selected' : '' }}>{{__('Not Found')}}</option>
                                </select>
                            </div>
                        </li>

                        <li class="form-group col-md-12">
                            <div class="radio primary col-md-3" style="margin-top: 37px;">
                                <input type="radio" name="settlement_type" value="positive" id="positive"  {{ isset($_GET['settlement_type']) && $_GET['settlement_type'] == 'positive' ? 'checked' : '' }}>
                                <label for="positive">{{__('Positive')}}</label>
                            </div>

                            <div class="radio primary col-md-3" style="margin-top: 37px;margin-right: 118px;">
                                <input type="radio" name="settlement_type" id="negative" value="negative"  {{ isset($_GET['settlement_type']) && $_GET['settlement_type'] == 'negative' ? 'checked' : '' }}>
                                <label for="negative">{{__('Negative')}}</label>
                            </div>

                            <div class="radio primary col-md-3" style="margin-top: 37px;margin-right: 118px;">
                                <input type="radio" name="settlement_type" id="both" value="both"  {{ isset($_GET['settlement_type']) && $_GET['settlement_type'] == 'both' ? 'checked' : '' }}>
                                <label for="both">{{__('Positive and Negative')}}</label>
                            </div>
                        </li>

                    </ul>
                    <button type="submit"
                            class="btn sr4-wg-btn waves-effect waves-light hvr-rectangle-out"><i
                            class=" fa fa-search "></i> {{__('Search')}} </button>
                    <a href="{{route('admin:settlements.index')}}"
                       class="btn bc-wg-btn waves-effect waves-light hvr-rectangle-out"><i
                            class=" fa fa-reply"></i> {{__('Back')}}
                    </a>
                </form>

            </div>
        </div>
    </div>
@endif
