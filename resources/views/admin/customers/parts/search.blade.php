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
            <form id="filtration-form">
                <input type="hidden" name="rows" value="{{ isset($_GET['rows']) ? $_GET['rows'] : '' }}"/>
                <input type="hidden" name="key" value="{{ isset($_GET['key']) ? $_GET['key'] : '' }}"/>
                <input type="hidden" name="sort_method" value="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : '' }}"/>
                <input type="hidden" name="sort_by" value="{{ isset($_GET['sort_by']) ? $_GET['sort_by'] : '' }}"/>
                <input type="hidden" name="invoker"/>
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
                    <div class="form-group col-md-3">
                    <label> {{ __('Customer Name') }} </label>
                        <select name="name" class="form-control js-example-basic-single">
                            <option value="">{{__('Select Customer Name')}}</option>
                            @foreach($customersSearch as $customer)
                                <option value="{{$customer->name_ar}}">{{$customer->name}}</option>
                            @endforeach
                        </select>
                    </div>

                        <div class="form-group col-md-3">
                        <label> {{ __('Customers Type') }} </label>
                        <select name="type" class="form-control js-example-basic-single">
                            <option value="">{{__('Select Customer Type')}}</option>
                                <option value="person">{{__('Person')}}</option>
                                <option value="company">{{__('Company')}}</option>
                        </select>
                    </div>

                        <div class="form-group col-md-3">
                        <label> {{ __('Responsible Name') }} </label>
                        <select name="responsible" class="form-control js-example-basic-single">
                            <option value="">{{__('Select Responsible Name')}}</option>
                            @foreach($responsiples as $customer)
                                <option value="{{$customer->responsible}}">{{$customer->responsible}}</option>
                            @endforeach
                        </select>
                    </div>

                        <div class="form-group col-md-3">
                        <label> {{ __('Phone') }} </label>
                        <select name="phone" class="form-control js-example-basic-single">
                            <option value="">{{__('Select Customer Phone')}}</option>
                            @foreach($customersSearch as $customer)
                                <option value="{{$customer->phone1 ?? $customer->phone2}}">{{$customer->phone1 ?? $customer->phone2}}</option>
                            @endforeach
                        </select>
                    </div>

                        <div class="form-group col-md-3">
                        <label> {{ __('Customer Category') }} </label>
                        <select name="customer_category_id" class="form-control js-example-basic-single">
                            <option value="">{{__('Select Customer Category')}}</option>
                            @foreach($customerCategories as $customer)
                                <option value="{{$customer->id}}">{{$customer->name}}</option>
                            @endforeach
                        </select>
                    </div>



                        <div class="form-group col-md-3">
                        <label> {{ __('Plate Number') }} </label>
                        <select name="plate_number" class="form-control js-example-basic-single">
                            <option value="">{{__('Select Plate Number')}}</option>
                            @foreach($cars as $car)
                                <option value="{{$car->plate_number}}">{{$car->plate_number}}</option>
                            @endforeach
                        </select>
                    </div>

                        <div class="form-group col-md-3">
                        <label> {{ __('Car Model') }} </label>
                        <select name="model" class="form-control js-example-basic-single">
                            <option value="">{{__('Select Model')}}</option>
                            @foreach($cars as $car)
                                <option value="{{$car->model}}">{{$car->model}}</option>
                            @endforeach
                        </select>
                    </div>

                        <div class="form-group col-md-3">
                        <label> {{ __('Chassis Number') }} </label>
                        <select name="Chassis_number" class="form-control js-example-basic-single">
                            <option value="">{{__('Select Chassis Number')}}</option>
                            @foreach($cars as $car)
                                <option value="{{$car->Chassis_number}}">{{$car->Chassis_number}}</option>
                            @endforeach
                        </select>
                    </div>


                        <div class="form-group col-md-3">
                        <label> {{ __('Car Type') }} </label>
                        <select name="car_type" class="form-control js-example-basic-single">
                            <option value="">{{__('Select Car Type')}}</option>
                            @foreach($cars as $car)
                                <option value="{{$car->type}}">{{$car->type}}</option>
                            @endforeach
                        </select>
                    </div>


                        <div class="form-group col-md-3">
                        <label> {{ __('Barcode') }} </label>
                        <select name="barcode" class="form-control js-example-basic-single">
                            <option value="">{{__('Select Barcode')}}</option>
                            @foreach($cars as $car)
                                @php
                                    if (!$car->barcode) continue;
                                @endphp
                                <option {{ isset($_GET['barcode']) ? $car->barcode == $_GET['barcode'] : '' }}
                                    value="{{$car->barcode}}">{{$car->barcode}}</option>
                            @endforeach
                        </select>
                    </div>
                


                </div>
                <div class="row">
<div class="col-md-12">
                        
                        <button type="submit"
                                class="btn sr4-wg-btn   waves-effect waves-light hvr-rectangle-out"><i
                                    class=" fa fa-search "></i> {{__('Search')}} </button>
                        <a href="{{route('admin:customers.index')}}"
                           class="btn bc-wg-btn   waves-effect waves-light hvr-rectangle-out"><i class=" fa fa-reply"></i> {{__('Back')}}
                        </a>
</div>
</div>
            </form>
        </div>
        <!-- /.card-content -->
    </div>
    <!-- /.box-content -->
</div>
@endif