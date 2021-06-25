@if(filterSetting())
<div class="col-xs-12">
    <div class="box-content card bordered-all js__card top-search">
        <h4 class="box-title with-control">
        <i class="fa fa-search"></i>  {{__('Search filters')}}
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
                            <label> {{__('Branch')}} </label>
                                <select name="branch_id" class="form-control js-example-basic-single">
                                    <option value="">{{__('Select Branch')}}</option>
                                    @foreach(\App\Models\Branch::all() as $branch)
                                        <option value="{{$branch->id}}">{{$branch->name}}</option>
                                    @endforeach
                                </select>
                            </li>
                        @endif

                        <li class="form-group col-lg-4">
                        <label> {{__('Employee Name')}} </label>
                            <select name="employee_data_id" class="form-control js-example-basic-single">
                                <option value="">{{__('Select Employee Name')}}</option>
                                @foreach(\App\Models\EmployeeData::all() as $emp)
                                    <option value="{{$emp->id}}">{{$emp->name}}</option>
                                @endforeach
                            </select>
                        </li>

                            <li class="form-group col-lg-4">
                                <label for="dateFrom" class="control-label">{{__('Date From')}}</label>
                                <div class="input-group">
                                    <input type="date" name="dateFrom" class="form-control" value="" id="dateFrom" placeholder="{{__('Select Date')}}">
                                </div>
                            </li>

                            <li class="form-group col-lg-4">
                                <label for="dateTo" class="control-label">{{__('Date To')}}</label>
                                <div class="input-group">
                                    <input type="date" name="dateTo" class="form-control" value="" id="dateTo" placeholder="{{__('Select Date')}}">
                                </div>
                            </li>

                            <li class="form-group col-lg-4">
                                <label> {{__('Operation Type')}} </label>
                                <ul class="list-inline">
                                    <li>
                                        <div class="radio info">
                                            <input type="radio" name="type" value="attendance"  id="radio-10"><label for="radio-10">{{__('Attendance')}}</label></div>
                                    </li>
                                    <li>
                                        <div class="radio pink">
                                            <input type="radio" name="type" value="departure" id="radio-12"><label for="radio-12">{{__('Departure')}}</label></div>
                                    </li>
                                </ul>
                            </li>

                    </div>
                </ul>

                <button type="submit"
                                class="btn sr4-wg-btn   waves-effect waves-light hvr-rectangle-out"><i
                                    class=" fa fa-search "></i> {{__('Search')}} </button>
                        <a href="{{route('admin:employees_attendance.index')}}"
                           class="btn bc-wg-btn   waves-effect waves-light hvr-rectangle-out"><i class=" fa fa-reply"></i> {{__('Back')}}
                        </a>
            </form>
        </div>
    </div>
</div>
@endif