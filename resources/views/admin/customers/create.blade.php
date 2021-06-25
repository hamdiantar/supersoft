@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('Add customers and cars') }} </title>
@endsection

@section('content')
    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a
                        href="{{route('admin:customers.index')}}"> {{__('customers and cars')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Add customers and cars')}}</li>
            </ol>
        </nav>

        <div class="col-xs-12">
            <div class=" card box-content-wg-new bordered-all primary">
                <h4 class="box-title with-control" style="text-align: initial"><i
                        class="fa fa-user"></i> {{__('Add customers data')}}
                    <span class="controls hidden-sm hidden-xs pull-left">
                    <button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Save')}}<img
                            class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px"
                            src="{{asset('assets/images/f1.png')}}"></button>
					<button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Reset')}}<img
                            class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px"
                            src="{{asset('assets/images/f2.png')}}"></button>
					<button class="control text-white" style="background:none;border:none;font-size:12px"> {{__('Back')}} <img
                            class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px"
                            src="{{asset('assets/images/f3.png')}}"></button>
					</span>
                </h4>
                <div class="box-content">

                    <form method="post" action="{{route('admin:customers.store')}}" class="form">
                        @csrf
                        @method('post')

                        <div class="row">

                            <div class="col-md-12">
                                @if (authIsSuperAdmin())

                                    <div class="form-group has-feedback">
                                        <label for="inputSymbolAR" class="control-label">{{__('Select Branch')}}</label>
                                        <select name="branch_id" class="form-control js-example-basic-single"
                                                onchange="getCustomerCate(event)">
                                            <option value="">{{__('Select Branch')}}</option>
                                            @foreach(\App\Models\Branch::all() as $branch)
                                                <option value="{{$branch->id}}"
                                                    {{old('branch_id') == $branch->id ? 'selected' : ''}}>{{$branch->name}}</option>
                                            @endforeach
                                        </select>
                                        {{input_error($errors,'branch_id')}}
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputNameAR"
                                               class="control-label">{{__('Client Name in Arabic')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-file-text-o"></li></span>
                                            <input type="text" name="name_ar" class="form-control" id="inputNameAR"
                                                   value="{{old('name_ar')}}"
                                                   placeholder="{{__('Client Name in Arabic')}}">
                                        </div>
                                        {{input_error($errors,'name_ar')}}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group has-feedback">
                                        <label for="inputNameEN"
                                               class="control-label">{{__('Client Name in English')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-file-text-o"></li></span>
                                            <input type="text" name="name_en" class="form-control" id="inputNameEN"
                                                   value="{{old('name_en')}}"
                                                   placeholder="{{__('Client Name in English')}}">
                                        </div>
                                        {{input_error($errors,'name_en')}}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group has-feedbac">
                                        <label for="inputSymbolAR"
                                               class="control-label">{{__('Customers Category')}}</label>
                                        <select name="customer_category_id" class="form-control js-example-basic-single"
                                                id="customerCategory">
                                            <option value="">{{__('Select Customer Category')}}</option>
                                            @foreach($customerCategories as $category)
                                                <option
                                                    value="{{$category->id}}" {{old('customerCategory') === $category->id ? 'selected' : ''}}>{{$category->name}}</option>
                                            @endforeach
                                        </select>
                                        {{input_error($errors,'customer_category_id')}}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="col-md-4">

                                    <div class="form-group has-feedback">
                                        <label for="inputSymbolAR"
                                               class="control-label">{{__('Select Country')}}</label>
                                        <select name="country_id" class="form-control js-example-basic-single"
                                                id="country">
                                            <option value="">{{__('Select Country')}}</option>
                                            @foreach(\App\Models\Country::all() as $country)
                                                <option value="{{$country->id}}"
                                                    {{old('country_id') == $country->id ? 'selected' : ''}}>{{$country->name}}</option>
                                            @endforeach
                                        </select>
                                        {{input_error($errors,'country_id')}}
                                    </div>
                                </div>

                                <div class="col-md-4">

                                    <div class="form-group has-feedback">
                                        <label for="inputSymbolAR" class="control-label">{{__('Select City')}}</label>
                                        <select name="city_id" class="form-control js-example-basic-single" id="city">
                                        </select>
                                    </div>

                                </div>

                                <div class="col-md-4">
                                    <div class="form-group has-feedback">
                                        <label for="area" class="control-label">{{__('Select Area')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon fa fa-globe"></span>
                                            <select name="area_id" class="form-control  js-example-basic-single select2"
                                                    id="area">

                                            </select>
                                            {{input_error($errors,'area_id')}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <div class="col-md-2" style="width: 100px">
                                        <div class="form-group has-feedback">
                                            <label for="inputPhone"
                                                   class="control-label">{{__('Supplier Type')}}</label>
                                            <div class="radio primary">
                                                <input type="radio" id="switch-3434" name="type"
                                                       value="person" {{!isset($supplier)?'checked':''}}
                                                       {{isset($supplier) && $supplier->type === 'person' ? 'checked':''}}
                                                       onclick="getCompanyData('person')">
                                                <label for="switch-3434">{{__('Person')}}</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2" style="width: 100px">
                                        <div class="form-group has-feedback">
                                            <label for="inputPhone"
                                                   class="control-label">{{__('Supplier Type')}}</label>
                                            <div class="radio primary">
                                                <input type="radio" id="switch-34343434" name="type"
                                                       value="company"
                                                       {{isset($supplier) && $supplier->type === 'company'? 'checked':''}}
                                                       onclick="getCompanyData('company')">
                                                <label for="switch-34343434">{{__('Company')}}</label>
                                            </div>
                                            {{input_error($errors,'type')}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 search-tb company_data"
                                 style="display: none;
                                     border: 2px solid #2f70a5;
                                           border-radius: 10px;
                                           margin-bottom: 25px;
                                           margin-top: 20px;
                                           padding: 10px;">
                                <div class="compnayData">
                                    <h4 class="text-center" style="margin-bottom: 15px">{{__('Company Data')}}</h4>
                                    <div class="col-md-3">
                                        <div class="form-group" id="faxCompany">
                                            <label for="fax" class="control-label">{{__('Fax')}}</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><li class="fa fa-fax"></li></span>
                                                <input type="text" name="fax" class="form-control" id="fax"
                                                       value="{{old('fax')}}" placeholder="{{__('Fax')}}">
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group" id="CompanyCommercialRegister">
                                            <label for="commercial_register"
                                                   class="control-label">{{__('Commercial Register')}}</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><li class="fa fa-fax"></li></span>
                                                <input type="text" name="commercial_register" class="form-control"
                                                       value="{{old('commercial_register')}}"
                                                       id="commercial_register"
                                                       placeholder="{{__('Commercial Register')}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">

                                        <div class="form-group" id="CompanyResponsible">
                                            <label for="responsible"
                                                   class="control-label">{{__('Responsible Name')}}</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><li class="fa fa-fax"></li></span>
                                                <input type="text" name="responsible" class="form-control"
                                                       id="responsible"
                                                       value="{{old('responsible')}}"
                                                       placeholder="{{__('Responsible Name')}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group" id="CompanyTaxCard">
                                            <label for="tax_card" class="control-label">{{__('Tax Card')}}</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><li class="fa fa-fax"></li></span>
                                                <input type="text" name="tax_card" class="form-control" id="tax_card"
                                                       value="{{old('tax_card')}}"
                                                       placeholder="{{__('Responsible Name')}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="col-md-12">

                            <div class="col-md-4">
                                <div class="form-group has-feedback">
                                    <label for="address" class="control-label">{{__('Address')}}</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><li class="fa fa-home"></li></span>
                                        <input type="text" name="address" class="form-control" id="address"
                                               value="{{old('address')}}"
                                               placeholder="{{__('Address')}}">
                                    </div>
                                    {{input_error($errors,'address')}}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group has-feedback">
                                    <label for="phone1" class="control-label">{{__('Phone 1')}}</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><li class="fa fa-phone"></li></span>
                                        <input type="text" name="phone1" class="form-control" id="phone1"
                                               value="{{old('phone1')}}"
                                               placeholder="{{__('Phone 1')}}">
                                    </div>
                                    {{input_error($errors,'phone1')}}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group has-feedback">
                                    <label for="phone2" class="control-label">{{__('Phone 2')}}</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><li class="fa fa-phone"></li></span>
                                        <input type="text" name="phone2" class="form-control" id="phone2"
                                               value="{{old('phone2')}}"
                                               placeholder="{{__('Phone 2')}}">
                                    </div>
                                    {{input_error($errors,'phone2')}}
                                </div>
                            </div>

                            <div class="col-md-4">

                                <div class="form-group has-feedback">
                                    <label for="email" class="control-label">{{__('Email')}}</label>
                                    <div class="input-group">
                                        <span class="input-group-addon">@</span>
                                        <input type="text" name="email" class="form-control" id="email"
                                               value="{{old('email')}}"
                                               placeholder="{{__('Email')}}">
                                    </div>
                                    {{input_error($errors,'email')}}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group has-feedback">
                                    <label for="cars_number" class="control-label">{{__('Cars Number')}}</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><li class="fa fa-car"></li></span>
                                        <input type="text" name="cars_number" value="0" class="form-control"
                                               id="cars_number"
                                               placeholder="{{__('Cars Number')}}">
                                    </div>
                                    {{input_error($errors,'cars_number')}}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="col-md-4">
                                <div class="form-group has-feedback">
                                    <label for="email" class="control-label">{{__('Username')}}</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><li class="fa fa-user"></li></span>
                                        <input type="text" name="username" class="form-control" id="username"
                                               value="{{old('username')}}"
                                               placeholder="{{__('Username')}}">
                                    </div>
                                    {{input_error($errors,'username')}}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group has-feedback">
                                    <label for="email" class="control-label">{{__('Password')}}</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><li class="fa fa-check"></li></span>
                                        <input type="password" name="password" class="form-control" id="password"
                                               placeholder="{{__('Password')}}">
                                    </div>
                                    {{input_error($errors,'password')}}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group  ">
                                    <label for="address" class="control-label">{{__('Maximum Funds On')}}</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><li class="fa fa-usd"></li></span>
                                        <input type="text" name="maximum_fund_on" class="form-control"
                                               id="maximum_fund_on"
                                               placeholder="{{__('Cost')}}"
                                               value="{{old('maximum_fund_on') ?? 0}}">
                                    </div>
                                    {{input_error($errors,'maximum_fund_on')}}
                                </div>
                            </div>


                        </div>

                        <div class="col-md-12">


                            <div class="col-md-4">
                                <label style="visibility: hidden;">الموثع علي الخريطه</label><br>
                                <a data-toggle="modal" data-target="#boostrapModal-2" title="Cars info"
                                   class="btn btn-primary "
                                   style="margin-top:1px;cursor:pointer;font-size:12px;padding:3px 15px">
                                    <i class="fa fa-plus"> </i> {{__('Location')}}
                                </a>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group  ">
                                    <label for="address" class="control-label">
                                        {{__('Lat')}}

                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><li class="fa fa-map"></li></span>
                                        <input type="text" name="lat" class="form-control" id="lat"
                                               placeholder="{{__('Lat')}}" value="{{old('lat')}}">
                                    </div>
                                    {{input_error($errors,'lat')}}
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group  ">
                                    <label for="address" class="control-label">{{__('Long')}}</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><li class="fa fa-map"></li></span>
                                        <input type="text" name="long" class="form-control" id="lng"
                                               placeholder="{{__('Long')}}" value="{{old('long')}}">
                                    </div>
                                    {{input_error($errors,'long')}}
                                </div>
                            </div>

                        </div>

                        <div class="form-group has-feedback col-sm-12">

                            <div class="col-md-4">
                                <div class="form-group  ">
                                    <label for="address" class="control-label">{{__('Identity Number')}}</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><li class="fa fa-usd"></li></span>
                                        <input type="text" name="identity_number" class="form-control"
                                               id="identity_number"
                                               placeholder="{{__('Identity Number')}}"
                                               value="{{old('identity_number')}}">
                                    </div>
                                    {{input_error($errors,'identity_number')}}
                                </div>
                            </div>

                            <div class="col-md-8">
                            <div class="form-group">
                                <label for="notes" class="control-label">{{__('Notes')}}</label>
                                <div class="input-group">
                                  <textarea name="notes" class="form-control" rows="4" cols="200">{{old('notes')}}
                                  </textarea>
                                </div>
                                {{input_error($errors,'notes')}}
                            </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <label for="inputPhone" class="control-label">{{__('Status')}}</label>
                            <div class="switch primary">
                                <input type="hidden" name="status" value="0">
                                <input type="checkbox" id="switch-1" name="status" value="1" CHECKED>
                                <label for="switch-1">{{__('Active')}}</label>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <label for="inputPhone" class="control-label">{{__('Can Edit')}}</label>
                            <div class="switch primary">
                                <input type="checkbox" id="switch-2" name="can_edit" CHECKED>
                                <label for="switch-2">{{__('Active')}}</label>
                            </div>
                        </div>

                        <div class="col-xs-12">
                            @include('admin.customers.contacts.form')
                        </div>

                        <div class="col-xs-12">
                            @include('admin.customers.bank_accounts.form')
                        </div>


                        <div class="form-group col-sm-12">
                            @include('admin.buttons._save_buttons')

                        </div>
                </div>
                </form>
            </div>
            <!-- /.box-content -->
        </div>
        <!-- /.col-xs-12 -->
    </div>
    <!-- /.row small-spacing -->
    </div>
@endsection

@section('modals')

    <div class="modal fade" id="boostrapModal-2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-1">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel-1">{{__('Customer Locations')}}</h4>
                </div>
                <div class="modal-body">
                    <div class="modal-body" id="map" style="height: 400px;">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm waves-effect waves-light" data-dismiss="modal">
                        {{__('Close')}}
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js-validation')

    {!! JsValidator::formRequest('App\Http\Requests\Admin\CustomerCar\CustomerCarRequest', '.form'); !!}

    @include('admin.partial.sweet_alert_messages')

    <script type="application/javascript">

        function getCompanyData(type) {
            if (type == 'person') {
                $(".company_data").hide();
            } else {
                $(".company_data").show();
            }
        }

        $("#country").on('change', function () {
            $.ajax({
                url: "{{ route('admin:country.cities') }}?country_id=" + $(this).val(),
                method: 'GET',
                success: function (data) {
                    $('#city').html(data.cities);
                }
            });
        });

        $("#city").change(function () {
            $.ajax({
                url: "{{ route('admin:city.areas') }}?city_id=" + $(this).val(),
                method: 'GET',
                success: function (data) {
                    $('#area').html(data.html);
                }
            });
        });

        function getCustomerCate(event) {
            if (event.target.value) {
                $.ajax({
                    url: "{{ route('admin:customers.customerCategory') }}?id=" + event.target.value,
                    method: 'GET',
                    success: function (data) {
                        $('#customerCategory').html(data.customerCategory);
                    }
                });
            }
        }
    </script>

    <script type="application/javascript" defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD4YXeD4XTeNieaKWam43diRHXjsGg7aVY&callback=initMap"></script>

    <script type="text/javascript">


        //Set up some of our variables.
        var map; //Will contain map object.
        var marker = false; ////Has the user plotted their location marker?

        //Function called to initialize / create the map.
        //This is called when the page has loaded.
        function initMap() {

            //The center location of our map.
            var centerOfMap = new google.maps.LatLng(24.68731563631883, 46.719044971885445);


            //Map options.
            var options = {
                center: centerOfMap, //Set center.
                zoom: 7 //The zoom value.
            };

            //Create the map object.
            map = new google.maps.Map(document.getElementById('map'), options);

            //Listen for any clicks on the map.
            google.maps.event.addListener(map, 'click', function (event) {
                //Get the location that the user clicked.
                var clickedLocation = event.latLng;
                //If the marker hasn't been added.
                if (marker === false) {
                    //Create the marker.
                    marker = new google.maps.Marker({
                        position: clickedLocation,
                        map: map,
                        draggable: true //make it draggable
                    });
                    //Listen for drag events!
                    google.maps.event.addListener(marker, 'dragend', function (event) {
                        markerLocation();
                    });
                } else {
                    //Marker has already been added, so just change its location.
                    marker.setPosition(clickedLocation);
                }
                //Get the marker's location.
                markerLocation();
            });
        }

        //This function will get the marker's current location and then add the lat/long
        //values to our textfields so that we can save the location.
        function markerLocation() {
            //Get location.
            var currentLocation = marker.getPosition();
            //Add lat and lng values to a field that we can save.
            document.getElementById('lat').value = currentLocation.lat(); //latitude
            document.getElementById('lng').value = currentLocation.lng(); //longitude
        }


        //Load the map when the page has finished loading.
        google.maps.event.addDomListener(window, 'load', initMap);

    </script>

    <script>

        function newContact() {

            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            let contacts_count = $("#contacts_count").val();

            $.ajax({

                type: 'post',
                url: '{{route('admin:customers.new.contact')}}',
                data: {
                    _token: CSRF_TOKEN,
                    contacts_count: contacts_count
                },

                beforeSend: function () {
                    // $("#loader_get_test_video").show();
                },

                success: function (data) {

                    $("#contacts_count").val(data.index);
                    $(".form_new_contact").append(data.view);
                },

                error: function (jqXhr, json, errorThrown) {
                    // $("#loader_save_goals").hide();
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });

        }

        function deleteContact(index) {
            swal({
                title: "{{__('Delete')}}",
                text: "{{__('Are you sure want to Delete?')}}",
                type: "success",
                buttons: {
                    confirm: {
                        text: "{{__('Ok')}}",
                    },
                    cancel: {
                        text: "{{__('Cancel')}}",
                        visible: true,
                    }
                }
            }).then(function (isConfirm) {
                if (isConfirm) {
                    $(".contact-" + index).remove();
                }
            });
        }

        function newBankAccount() {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            let bank_account_count = $("#bank_account_count").val();
            $.ajax({
                type: 'post',
                url: '{{route('admin:suppliers.new.bank-account')}}',
                data: {
                    _token: CSRF_TOKEN,
                    bank_account_count: bank_account_count
                },
                success: function (data) {
                    $("#bank_account_count").val(data.index);
                    $(".form_new_bank_account").append(data.view);
                },
                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        }

        function deleteBankAccount(index) {
            swal({
                title: "{{__('Delete')}}",
                text: "{{__('Are you sure want to Delete?')}}",
                type: "success",
                buttons: {
                    confirm: {
                        text: "{{__('Ok')}}",
                    },
                    cancel: {
                        text: "{{__('Cancel')}}",
                        visible: true,
                    }
                }
            }).then(function (isConfirm) {
                if (isConfirm) {
                    $(".bank-account-" + index).remove();
                }
            });
        }

    </script>
@endsection
