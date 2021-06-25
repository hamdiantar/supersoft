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
            <form>
                <div class="list-inline margin-bottom-0 row">
                    @if (authIsSuperAdmin())
                        <div class="form-group col-md-12">
                        <label> {{ __('Branch') }} </label>
                            <select name="branch_id" class="form-control js-example-basic-single">
                                <option value="">{{__('Select Branch')}}</option>
                                @foreach(\App\Models\Branch::all() as $branch)
                                    <option value="{{$branch->id}}">{{$branch->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif


                    <div class="form-group col-md-12">
                    <label> {{ __('Services Package') }} </label>
                        <select name="name" class="form-control js-example-basic-single">
                            <option value="">{{__('Select Package Name')}}</option>
                            @foreach($servicesPackages as $package)
                                <option value="{{$package->name}}">{{$package->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                        <button type="submit"
                                class="btn sr4-wg-btn   waves-effect waves-light hvr-rectangle-out"><i
                                    class=" fa fa-search "></i> {{__('Search')}} </button>
                        <a href="{{route('admin:services_packages.index')}}"
                           class="btn bc-wg-btn   waves-effect waves-light hvr-rectangle-out"><i class=" fa fa-reply"></i> {{__('Back')}}
                        </a>
            </form>
        </div>
        <!-- /.card-content -->
    </div>
    <!-- /.box-content -->
</div>
@endif