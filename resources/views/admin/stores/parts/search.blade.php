@if(filterSetting())
    <div class="col-xs-12">
        <div class="box-content card bordered-all js__card top-search">
            <h4 class="box-title with-control"><i class="fa fa-search"></i>{{__('Search filters')}}
                <span class="controls">
							<button type="button" class="control fa fa-minus js__card_minus"></button>
							<button type="button" class="control fa fa-times js__card_remove"></button>
						</span>
            </h4>
            <!-- /.box-title -->
            <div class="card-content js__card_content">
                <form>
                    <div class="list-inline margin-bottom-0 row">
                        @if(authIsSuperAdmin())
                            <div class="form-group col-md-12">
                                <label> {{ __('Branch') }} </label>
                                <div class="input-group">
                                    <span class="input-group-addon fa fa-file"></span>
                                    <select name="branch_id" class="form-control js-example-basic-single" id="branchId">
                                        <option value="">{{__('Select Branch')}}</option>
                                        @foreach(\App\Models\Branch::all() as $branch)
                                            <option value="{{$branch->id}}">{{$branch->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif

                        <div class="form-group col-md-6">
                            <label> {{ __('Store Name') }} </label>
                            {!! drawSelect2ByAjax('store_id','Store','name_'.app()->getLocale(),'name_'.app()->getLocale(),__('Store Name')) !!}
                        </div>

                        <div class="form-group col-md-6">
                            <label> {{ __('Store Creator') }} </label>
                            <div class="input-group">
                                <span class="input-group-addon fa fa-file"></span>
                                {!! drawSelect2ByAjax('employee','EmployeeData', 'name_'.app()->getLocale(),'name_'.app()->getLocale(),  __('opening-balance.select-one'),request()->employee) !!}

                            </div>
                        </div>
                    </div>

                    <button type="submit"
                            class="btn sr4-wg-btn   waves-effect waves-light hvr-rectangle-out"><i
                            class=" fa fa-search "></i> {{__('Search')}} </button>
                    <a href="{{route('admin:stores.index')}}"
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
