<a class="btn btn-wg-show hvr-radial-out hvr-shrink" data-remodal-target="show-{{$id}}">
    <i class="fa fa-eye"></i>
    @if(isset($showInStore))
        {{$employeeData->name}}
    @else
        {{__('Show')}}
    @endif
</a>


<div class="remodal" data-remodal-id="show-{{$id}}" role="dialog" aria-labelledby="modal1Title"
     aria-describedby="modal1Desc">
    <button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>
    <div class="remodal-content">
        <div class="row">
            @if (authIsSuperAdmin())
                <div class="col-md-12">
                    <div class="form-group">
                        <label> {{__('Branch')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                            <select disabled name="branch_id" class="form-control">
                                @foreach(\App\Models\Branch::all() as $branch)
                                    <option value="{{$branch->id}}"
                                        {{$employeeData->branch_id === $branch->id ? 'selected' : ''}}>
                                        {{$branch->name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            @endif
            <div class="col-md-12">

                <div class="col-md-4">
                    <div class="form-group">
                        <label> {{__('Employee Category')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                            <select disabled name="employee_setting_id"
                                    class="form-control">
                                <option value="">{{__('Select Employee Category')}}</option>
                                @foreach(\App\Models\EmployeeSetting::whereStatus(1)->get() as $emp)
                                    <option value="{{$emp->id}}"
                                        {{$employeeData->employee_setting_id === $emp->id ? 'selected' : ''}}>
                                        {{$emp->name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>{{__('Employee Name In Arabic')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                            <input readonly class="form-control" name="name_ar" value="{{$employeeData->name_ar}}"
                                   placeholder="{{__('Employee Name In Arabic')}}">
                        </div>
                    </div>
                </div>


                <div class="col-md-4">
                    <div class="form-group">
                        <label> {{__('Employee Name In English')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                            <input readonly class="form-control" name="name_en" value="{{$employeeData->name_en}}"
                                   placeholder="{{__('Employee Name In English')}}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="col-md-4">
                    <div class="form-group">
                        <label> {{__('Functional Class')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                            <input readonly class="form-control" name="Functional_class"
                                   value="{{$employeeData->Functional_class}}"
                                   placeholder="{{__('Functional Class')}}">
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label> {{__('Country')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-home"></i></span>
                            <select disabled name="country_id" class="form-control">
                                @foreach(\App\Models\Country::all() as $country)
                                    <option value="{{$country->id}}"
                                        {{$employeeData->country_id === $country->id ? 'selected' : ''}}>
                                        {{$country->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label> {{__('City')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-home"></i></span>
                            <select disabled name="city_id" class="form-control">
                                @foreach(\App\Models\City::all() as $city)
                                    <option value="{{$city->id}}"
                                        {{$employeeData->city_id === $city->id ? 'selected' : ''}}>
                                        {{$city->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="col-md-4">
                    <div class="form-group">
                        <label> {{__('Area')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-home"></i></span>
                            <select disabled name="area_id" class="form-control">
                                <option value="">{{__('Area')}}</option>
                                @foreach(\App\Models\Area::all() as $area)
                                    <option value="{{$area->id}}"
                                        {{$employeeData->area_id === $area->id ? 'selected' : ''}}>
                                        {{$area->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>


                <div class="col-md-4">
                    <div class="form-group">
                        <label>{{__('Address')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-home"></i></span>
                            <input readonly class="form-control" name="address" value="{{$employeeData->address}}"
                                   placeholder="{{__('Address')}}">
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>{{__('Phone1')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                            <input readonly class="form-control" name="phone1" value="{{$employeeData->phone1}}"
                                   placeholder="{{__('Phone1')}}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>{{__('Phone2')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                            <input readonly class="form-control" name="phone2" value="{{$employeeData->phone2}}"
                                   placeholder="{{__('Phone2')}}">
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label> {{__('Select Nationality')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-home"></i></span>
                            <select disabled name="national_id" class="form-control">
                                <option value="">{{__('Select Nationality')}}</option>
                                @foreach(\App\Models\Country::all() as $country)
                                    <option value="{{$country->id}}"
                                        {{$employeeData->national_id === $country->id ? 'selected' : ''}}>
                                        {{$country->name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>{{__('ID Number')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                            <input readonly class="form-control" name="id_number" placeholder="{{__('ID Number')}}"
                                   value="{{$employeeData->id_number}}">
                        </div>
                    </div>
                </div>


            </div>
            <div class="col-md-12">


                <div class="col-md-4">
                    <div class="form-group">
                        <label> {{__('End Date of ID Number')}}</label>
                        <input readonly type="date" class="form-control time" name="end_date_id_number"
                               value="{{$employeeData->end_date_id_number}}">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label> {{__('Date of hiring')}}</label>
                        <input readonly type="date" class="form-control time" name="start_date_assign"
                               value="{{$employeeData->start_date_assign}}">
                    </div>
                </div>


                <div class="col-md-4">
                    <div class="form-group">
                        <label> {{__('Starting date of stay')}}</label>
                        <input readonly type="date" class="form-control time" name="start_date_stay"
                               value="{{$employeeData->start_date_stay}}">
                    </div>
                </div>

            </div>
            <div class="col-md-12">


                <div class="col-md-4">
                    <div class="form-group">
                        <label> {{__('expiration date of stay')}}</label>
                        <input readonly type="date" class="form-control time" name="end_date_stay"
                               value="{{$employeeData->end_date_stay}}">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>{{__('Number Card Work')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-gear"></i></span>
                            <input readonly type="text" class="form-control" name="number_card_work"
                                   value="{{$employeeData->number_card_work}}" placeholder="{{__('Number Card Work')}}">
                        </div>
                    </div>
                </div>


                <div class="col-md-4">
                    <div class="form-group">
                        <label> {{__('expiration date of the health insurance')}}</label>
                        <input readonly type="date" class="form-control time" name="end_date_health"
                               value="{{$employeeData->end_date_health}}">
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>{{__('E-Mail')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-envelope-square"></i></span>
                            <input readonly type="text" class="form-control" name="email" placeholder="{{__('E-Mail')}}"
                                   value="{{$employeeData->email}}">
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>{{__('CV')}}</label>
                        <div class="input-group">

                            <button type="button" {{ $employeeData->cv ? '' : 'disabled' }}
                            onclick="viewCV(event, '{{asset('/employees/cv/').'/'.$employeeData->cv}}')"
                                    class="form-control btn btn-info">
                                {{__('view cv')}}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                @php
                    $balance_details = $employeeData->direct_balance();
                @endphp
                <div class="col-md-4">
                    <div class="form-group">
                        <label> {{ __("words.employee-debit") }} </label>
                        <input disabled value="{{ $balance_details['debit'] }}"
                               class="form-control"/>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label> {{ __("words.employee-credit") }} </label>
                        <input disabled value="{{ $balance_details['credit'] }}"
                               class="form-control"/>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label>{{__('Rating')}}</label>
                    <div class="rate">
                        <input disabled type="radio" id="star5" name="rating"
                               value="5" {{$employeeData->rating === 5 ? 'checked' : ''}}>
                        <label for="star5" title="text">5 stars</label>
                        <input disabled type="radio" id="star4" name="rating"
                               value="4" {{$employeeData->rating === 4 ? 'checked' : ''}}>
                        <label for="star4" title="text">4 stars</label>
                        <input disabled type="radio" id="star3" name="rating"
                               value="3" {{$employeeData->rating === 3 ? 'checked' : ''}}>
                        <label for="star3" title="text">3 stars</label>
                        <input disabled type="radio" id="star2" name="rating"
                               value="2" {{$employeeData->rating === 2 ? 'checked' : ''}}>
                        <label for="star2" title="text">2 stars</label>
                        <input disabled type="radio" id="star1" name="rating"
                               value="1" {{$employeeData->rating === 1 ? 'checked' : ''}}>
                        <label for="star1" title="text">1 star</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <button data-remodal-action="cancel" class="remodal-cancel">{{__('Close')}}</button>
</div>
@section('js-validation')
    <script type="application/javascript">
        function viewCV(event, cv) {
            event.preventDefault();
            if (window.navigator && window.navigator.msSaveOrOpenBlob) {
                window.navigator.msSaveOrOpenBlob(newBlob);
                return;
            }
            ;
            window.open(cv, 'resizable,scrollbars');
            return false;
        }
    </script>
@endsection
