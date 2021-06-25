

<a class="btn btn-wg-show hvr-radial-out  " data-remodal-target="show-{{$id}}">
    <i class="fa fa-eye"></i> {{__('words.Show')}}
</a>



<div class="remodal" data-remodal-id="show-{{$id}}" role="dialog" aria-labelledby="modal1Title" aria-describedby="modal1Desc">
    <button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>
    <div class="remodal-content">
        <div class="row">
        @if (authIsSuperAdmin())
                    <div class="col-md-12">
                        <div class="form-group">
                            <label> {{__('words.Branch')}}</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                                <select disabled name="branch_id" class="form-control">
                                    @foreach(\App\Models\Branch::all() as $branch)
                                        <option value="{{$branch->id}}"
                                            {{$employeeSetting->branch_id === $branch->id ? 'selected' : ''}}>
                                            {{$branch->name}}</option>
                                    @endforeach
                                </select>
                                {{input_error($errors,'branch_id')}}
                            </div>
                        </div>
                    </div>
                @endif
            <div class="col-md-12">

                <div class="col-md-4">
                    <div class="form-group">
                        <label> {{__('words.Shift')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                            <select disabled name="shift_id" class="form-control">
                                @foreach($shifts as $shift)
                                    <option value="{{$shift->id}}"
                                        {{$employeeSetting->shift_id === $shift->id ? 'selected' : ''}}
                                    >{{$shift->name}}</option>
                                @endforeach
                            </select>
                            {{input_error($errors,'shift_id')}}
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>{{__('words.Employee Category Arabic Name')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                            <input readonly class="form-control" name="name_ar"  value="{{$employeeSetting->name_ar}}" placeholder="{{__('words.Employee Category Arabic Name')}}">
                            {{input_error($errors,'name_ar')}}
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label> {{__('words.Employee Category English Name')}} </label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                            <input readonly class="form-control" name="name_en" value="{{$employeeSetting->name_en}}" placeholder="{{__('words.Employee Category English Name')}}">
                            {{input_error($errors,'name_en')}}
                        </div>
                    </div>
                </div>

                </div>

                <div class="col-md-12">
                <div class="col-md-6">
                    <div class="form-group">
                        <label> {{__('words.Employee Category Type')}} </label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                         <input readonly type="text" name="type_account"  class="form-control"
                         value="{{$employeeSetting->type_account}}">
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label> {{__('words.Account Amount')}} </label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                            <input readonly type="text" class="form-control time" name="amount_account" value="{{$employeeSetting->amount_account}}" placeholder="{{__('words.Account Amount')}}">
                        </div>
                    </div>
                </div>
                </div>

                <div class="col-md-12">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{__('words.Max Advance')}} </label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                            <input readonly type="text" class="form-control" name="max_advance" value="{{$employeeSetting->max_advance}}" placeholder="{{__('words.Max Advance')}}">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label> {{__('Percent Card Value')}} </label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                            <input class="form-control" readonly value="{{$employeeSetting->card_work_percent}}"
                                placeholder="{{__('Percentage')}}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="col-md-3">
                    <div class="form-group">
                        <label> {{__('words.Attendance Time')}} </label>
                        <input readonly type="time" class="form-control time" name="time_attend" value="{{$employeeSetting->time_attend}}" placeholder="{{__('words.Attendance Time')}}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label> {{__('words.Leaving Time')}} </label>
                        <input readonly type="time" class="form-control time" name="time_leave" value="{{$employeeSetting->time_leave}}" placeholder="{{__('words.Leaving Time')}}">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>{{__('words.Yearly Vacations Days')}} </label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar-times-o"></i></span>
                            <input readonly type="number" class="form-control" name="annual_vocation_days"
                                   value="{{$employeeSetting->annual_vocation_days}}"
                                   placeholder="{{__('words.Yearly Vacations Days')}}">
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>{{__('words.Daily Work Hours')}} </label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                            <input readonly type="number" class="form-control" name="daily_working_hours" value="{{$employeeSetting->daily_working_hours}}" placeholder="{{__('words.Daily Work Hours')}}">
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-md-12">
            <div class="col-md-6">
                    <div class="form-group">
                        <label>{{__('words.Absence Day Equal To')}}</label>
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                        <input readonly type="text" class="form-control" name="type_absence_equal" value="{{$employeeSetting->type_absence_equal}}">
                            </div>
                    </div>
                </div>
            </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{__('words.Absence Type')}}</label>
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                            <input readonly type="text" name="type_absence" class="form-control" value="{{__('words.'.$employeeSetting->type_absence)}}">
                        </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-md-12">
            <div class="col-md-6">
                    <div class="form-group">
                        <label>{{__('words.Delay Hour Equal To')}}</label>
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                                <input readonly type="text" class="form-control" name="hourly_delay_equal" value="{{$employeeSetting->hourly_delay_equal}}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{__('words.Delay Hour')}}</label>
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                                <input readonly type="text" name="hourly_delay" class="form-control" value="{{__('words.'.$employeeSetting->hourly_delay)}}">
                            </div>
                        </div>
                    </div>
                </div>

            </div>


            <div class="col-md-12">
            <div class="col-md-6">
                    <div class="form-group">
                        <label>{{__('words.Additional Hour Equal To')}}</label>
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                                <input readonly type="text" class="form-control" name="hourly_extra" value="{{$employeeSetting->hourly_extra_equal}}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{__('words.Additional Hour')}}</label>
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                                <input readonly type="text" name="type_absence" class="form-control" value="{{__('words.'.$employeeSetting->hourly_extra)}}">
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-md-12">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>{{__('words.Weekly Official Vacations')}}</label>
                        <ul class="list-inline">
                            <li>
                                <div class="checkbox circled info">
                                    <input disabled readonly type="checkbox" id="checkbox-circled-sat" name="saturday" value="1"
                                        {{$employeeSetting->saturday === 1 ? 'checked' : ''}}>
                                    <label for="checkbox-circled-sat">{{__('words.Saturday')}}</label>
                                </div>
                            </li>
                            <li>
                                <div class="checkbox circled info">
                                    <input disabled readonly type="checkbox" id="checkbox-circled-sun" name="sunday" value="1"
                                        {{$employeeSetting->sunday === 1 ? 'checked' : ''}}>
                                    <label for="checkbox-circled-sun">{{__('words.Sunday')}}</label>
                                </div>
                            </li>
                            <li>
                                <div class="checkbox circled info">
                                    <input disabled readonly type="checkbox" id="checkbox-circled-mon" name="monday" value="1"
                                        {{$employeeSetting->monday === 1 ? 'checked' : ''}}>
                                    <label for="checkbox-circled-mon">{{__('words.Monday')}}</label>
                                </div>
                            </li>
                            <li>
                                <div class="checkbox circled info">
                                    <input disabled readonly type="checkbox" id="checkbox-circled-tue" name="tuesday" value="1"
                                        {{$employeeSetting->tuesday === 1 ? 'checked' : ''}}>
                                    <label for="checkbox-circled-tue">{{__('words.Tuesday')}}</label>
                                </div>
                            </li>
                            <li>
                                <div class="checkbox circled info">
                                    <input disabled readonly type="checkbox" id="checkbox-circled-wed" name="wednesday" value="1"
                                        {{$employeeSetting->wednesday === 1 ? 'checked' : ''}}>
                                    <label for="checkbox-circled-wed">{{__('words.Wednesday')}}</label>
                                </div>
                            </li>
                            <li>
                                <div class="checkbox circled info">
                                    <input disabled readonly type="checkbox" id="checkbox-circled-thu" name="thursday" value="1"
                                        {{$employeeSetting->thursday === 1 ? 'checked' : ''}}>
                                    <label for="checkbox-circled-thu">{{__('words.Thursday')}}</label>
                                </div>
                            </li>
                            <li>
                                <div class="checkbox circled info">
                                    <input disabled type="checkbox" id="checkbox-circled-fri" name="friday" value="1"
                                        {{$employeeSetting->friday === 1 ? 'checked' : ''}}>
                                    <label for="checkbox-circled-fri">{{__('words.Friday')}}</label>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

    </div>
        <button data-remodal-action="cancel" class="remodal-cancel">{{__('words.Close')}}</button>
</div>
