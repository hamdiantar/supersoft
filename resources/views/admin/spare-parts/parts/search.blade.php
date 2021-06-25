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
                <div class="row margin-bottom-0">
                    @if(authIsSuperAdmin())
                    @if (\App\Models\Branch::all()->count() > 1)
                        <div class="form-group col-md-12">
                            <label for="Branch" class="control-label">{{__('Select Branch')}}</label>
                            <select name="branch_id" class="form-control js-example-basic-single">
                                <option value="">{{__('Select Branch')}}</option>
                                @foreach(\App\Models\Branch::all() as $branch)
                                    <option value="{{$branch->id}}">{{$branch->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    @endif


                    <div class="form-group col-md-6">
                        <label for="Type" class="control-label">{{__('Select Main Type')}}</label>
                        <select name="type" class="form-control js-example-basic-single">
                            <option value="">{{__('Select Main Type')}}</option>
                            @foreach($sparePart as $part)
                                <option value="{{$part->type}}">{{$part->type}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <div class="switch primary col-md-6" style="margin-top: 15px">
                            <input type="checkbox" id="switch-1" name="status" value="1">
                            <label for="switch-1">{{__('Active')}}</label>
                        </div>

                    </div>

                </div>

                        <button type="submit"
                                class="btn sr4-wg-btn   waves-effect waves-light hvr-rectangle-out"><i
                                    class=" fa fa-search "></i> {{__('Search')}} </button>
                        <a href="{{route('admin:spare-parts.index')}}"
                           class="btn bc-wg-btn   waves-effect waves-light hvr-rectangle-out"><i class=" fa fa-reply"></i> {{__('Back')}}
                        </a>
            </form>
        </div>
        <!-- /.card-content -->
    </div>
    <!-- /.box-content -->
</div>
@endif
