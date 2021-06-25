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
        <div class="card-content js__card_content" style="padding:20px">
            <form>
            <div class="list-inline margin-bottom-0 row">
                @if(authIsSuperAdmin())
                    @if (\App\Models\Branch::all()->count() > 1)
                    <li class="form-group col-md-12">
                        <label> {{ __('Select Branch') }} </label>
                            <select name="branch_id" class="form-control js-example-basic-single">
                                <option value="">{{__('Select Branch')}}</option>
                                @foreach(\App\Models\Branch::all() as $branch)
                                    <option value="{{$branch->id}}">{{$branch->name}}</option>
                                @endforeach
                            </select>
                        </li>
                    @endif
                @endif

                    <li class="form-group col-md-12">
                            <label> {{ __('Select Shift Name') }} </label>
                        <select name="name" class="form-control js-example-basic-single">
                            <option value="">{{__('Select Shift Name')}}</option>
                            @foreach($shifts as $shift)
                                <option value="{{$shift->name}}">{{$shift->name}}</option>
                            @endforeach
                        </select>
                    </li>

                        <li class="form-group col-md-12">
                        <label>{{__('Choose Days')}}</label>
                                <ul class="list-inline">
                                    <li>
                                        <input type="hidden"  name="Saturday" VALUE="0">
                                        <div class="switch primary" style="margin-top: 15px">
                                            <input type="checkbox" id="switch-1" name="Saturday" VALUE="1">
                                            <label for="switch-1">{{__('Saturday')}}</label>
                                        </div>
                                    </li>

                                    <li>
                                        <input type="hidden"  name="sunday" VALUE="0">
                                        <div class="switch primary" style="margin-top: 15px">
                                            <input type="checkbox" id="switch-2" name="sunday" VALUE="1">
                                            <label for="switch-2">{{__('Sunday')}}</label>
                                        </div>
                                    </li>

                                    <li>
                                        <input type="hidden"  name="monday" VALUE="0">
                                        <div class="switch primary" style="margin-top: 15px">
                                            <input type="checkbox" id="switch-3" name="monday" VALUE="1">
                                            <label for="switch-3">{{__('Monday')}}</label>
                                        </div>
                                    </li>

                                    <li>
                                        <input type="hidden"  name="tuesday" VALUE="0">
                                        <div class="switch primary" style="margin-top: 15px">
                                            <input type="checkbox" id="switch-4" name="tuesday" VALUE="1">
                                            <label for="switch-4">{{__('Tuesday')}}</label>
                                        </div>
                                    </li>

                                    <li>
                                        <input type="hidden"  name="wednesday" VALUE="0">
                                        <div class="switch primary" style="margin-top: 15px">
                                            <input type="checkbox" id="switch-5" name="wednesday" VALUE="1">
                                            <label for="switch-5">{{__('Wednesday')}}</label>
                                        </div>
                                    </li>

                                    <li>
                                        <input type="hidden"  name="thursday" VALUE="0">
                                        <div class="switch primary" style="margin-top: 15px">
                                            <input type="checkbox" id="switch-6" name="thursday" VALUE="1">
                                            <label for="switch-6">{{__('Thursday')}}</label>
                                        </div>
                                    </li>

                                    <li>
                                        <input type="hidden"  name="friday" VALUE="0">
                                        <div class="switch primary" style="margin-top: 15px">
                                            <input type="checkbox" id="switch-7" name="friday" VALUE="1">
                                            <label for="switch-7">{{__('Friday')}}</label>
                                        </div>
                                    </li>
                                </ul>
                        </li>

                </ul>
                <div class="col-md-12">
                                <button type="submit"
                                class="btn sr4-wg-btn   waves-effect waves-light hvr-rectangle-out"><i
                                    class=" fa fa-search "></i> {{__('Search')}} </button>
                        <a href="{{route('admin:shifts.index')}}"
                           class="btn bc-wg-btn   waves-effect waves-light hvr-rectangle-out"><i class=" fa fa-reply"></i> {{__('Back')}}
                        </a>

                </div>

            </form>
        </div>
        <!-- /.card-content -->
    </div>
    <!-- /.box-content -->
</div>
</div>
@endif