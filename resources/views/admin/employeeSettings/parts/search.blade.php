@if(filterSetting())
<div class="col-xs-12">
    <div class="box-content card bordered-all js__card top-search">
        <h4 class="box-title with-control">
            <i class="fa fa-search"></i>{{__('Search filters')}}
            <span class="controls">
							<button type="button" class="control fa fa-minus js__card_minus"></button>
							<button type="button" class="control fa fa-times js__card_remove"></button>
						</span>
        </h4>
        <div class="card-content js__card_content">
            <form id="filtration-form">
                <input type="hidden" name="rows" value="{{ isset($_GET['rows']) ? $_GET['rows'] : '' }}"/>
                <input type="hidden" name="key" value="{{ isset($_GET['key']) ? $_GET['key'] : '' }}"/>
                <input type="hidden" name="sort_method" value="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : '' }}"/>
                <input type="hidden" name="sort_by" value="{{ isset($_GET['sort_by']) ? $_GET['sort_by'] : '' }}"/>
                <input type="hidden" name="invoker"/>
                <ul class="list-inline margin-bottom-0">
                    <div class="row">
                        @if(authIsSuperAdmin())
                            <li class="form-group col-lg-4">
                            <label> {{ __('Branch') }} </label>
                                <select name="branch_id" class="form-control js-example-basic-single">
                                    <option value="">{{__('Select Branch')}}</option>
                                    @foreach(\App\Models\Branch::all() as $branch)
                                        <option value="{{$branch->id}}">{{$branch->name}}</option>
                                    @endforeach
                                </select>
                            </li>
                        @endif

                            <li class="form-group col-lg-4">
                            <label> {{ __('Select Shift') }} </label>
                                <select name="shift_id" class="form-control js-example-basic-single">
                                    <option value="">{{__('Select Shift')}}</option>
                                    @foreach(\App\Models\Shift::all() as $shift)
                                        <option value="{{$shift->id}}">{{$shift->name}}</option>
                                    @endforeach
                                </select>
                            </li>

                            <li class="form-group col-lg-4">
                            <label> {{ __('Employee Category Name') }} </label>
                                <select name="name" class="form-control js-example-basic-single" id="shift_id">
                                    <option value="">{{__('Select Employee Category Name')}}</option>
                                    @foreach(\App\Models\EmployeeSetting::all() as $emp)
                                        <option value="{{$emp->name}}">{{$emp->name}}</option>
                                    @endforeach
                                </select>
                            </li>

                            <li class="form-group col-lg-4">
                                <label> {{__('Employee Category Type')}} </label>
                                <ul class="list-inline">
                                    <li>
                                        <div class="radio info">
                                            <input type="radio" name="type_account" value="work_card"  id="radio-10"><label for="radio-10">{{__('Percent Card Work')}}</label></div>
                                    </li>
                                    <li>
                                        <div class="radio info">
                                            <input type="radio" name="type_account" value="days"  id="radio-11"><label for="radio-11">{{__('Days')}}</label></div>
                                    </li>
                                    <li>
                                        <div class="radio pink">
                                            <input type="radio" name="type_account" value="month" id="radio-12"><label for="radio-12">{{__('Monthly')}}</label></div>
                                    </li>
                                </ul>
                            </li>

                            <li class="form-group col-lg-4">
                                <label> {{__('Activation')}} </label>
                                <ul class="list-inline">
                                    <li>
                                        <div class="radio info">
                                            <input type="radio" name="status" value="1"  id="radio-33"><label for="radio-33">{{__('Active')}}</label></div>
                                    </li>
                                    <li>
                                        <div class="radio info">
                                            <input type="radio" name="status" value="0"  id="radio-44"><label for="radio-44">{{__('inActive')}}</label></div>
                                    </li>
                                </ul>
                            </li>


                    </div>
                </ul>

                <button type="submit"
                                class="btn sr4-wg-btn   waves-effect waves-light hvr-rectangle-out"><i
                                    class=" fa fa-search "></i> {{__('Search')}} </button>
                        <a href="{{route('admin:employee_settings.index')}}"
                           class="btn bc-wg-btn   waves-effect waves-light hvr-rectangle-out"><i class=" fa fa-reply"></i> {{__('Back')}}
                        </a>


            </form>
        </div>
    </div>
</div>
@endif