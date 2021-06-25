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

                            @if (\App\Models\Branch::all()->count() > 1)
                                <div class="form-group col-md-12">
                                    <label> {{ __('Select Branch') }} </label>

                                    {!! drawSelect2ByAjax('branch_id','Branch','name_'.app()->getLocale(),'name_'.app()->getLocale(),__('Select Branch')) !!}

                                </div>
                            @endif
                        @endif


                        <div class="form-group col-md-6">
                            <label> {{ __('Select Tax Name') }} </label>
                            {!! drawSelect2ByAjax('id','TaxesFees','name_'.app()->getLocale(),'name_'.app()->getLocale(),__('Select Tax Name')) !!}

                        </div>

                        <div class="form-group col-md-6">
                            <div class="switch success">
                                <input type="checkbox" id="search-on-parts" name="on_parts">
                                <label for="search-on-parts">{{__('For Parts')}}</label>
                            </div>
                        </div>


                    <!-- <li class="form-group">
                        <button type="submit" class="btn btn-info waves-effect waves-light fa fa-search"></button>
                        <a href="{{route('admin:taxes.index')}}"
                           class="btn btn-danger waves-effect waves-light fa fa-refresh">
                        </a>
                    </li> -->
                    </div>
                    <button type="submit"
                            class="btn sr4-wg-btn   waves-effect waves-light hvr-rectangle-out"><i
                            class=" fa fa-search "></i> {{__('Search')}} </button>
                    <a href="{{route('admin:taxes.index')}}"
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
