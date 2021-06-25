@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('Create Supplier') }} </title>
@endsection

@section('content')
    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin:suppliers.index')}}"> {{__('Suppliers')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Create Supplier')}}</li>
            </ol>
        </nav>

        <div class="col-xs-12">
            <div class=" card box-content-wg-new bordered-all primary">
                <h4 class="box-title with-control" style="text-align: initial"><i
                        class="fa fa-user ico"></i>{{__('Create Supplier')}}
                    <span class="controls hidden-sm hidden-xs pull-left">
                <button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Save')}}<img
                        class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px"
                        src="{{asset('assets/images/f1.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Reset')}}<img
                                    class="img-fluid"
                                    style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px"
                                    src="{{asset('assets/images/f2.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px"> {{__('Back')}} <img
                                    class="img-fluid"
                                    style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px"
                                    src="{{asset('assets/images/f3.png')}}"></button>
						</span>
                </h4>


                <div class="box-content">

                    <form method="post" action="{{route('admin:suppliers.store')}}" class="form"
                          enctype="multipart/form-data">
                        @csrf
                        @method('post')
                        @include('admin.suppliers.form')
                    </form>
                </div>

            </div>
            <!-- /.box-content -->
        </div>
        <!-- /.col-xs-12 -->
    </div>
    <!-- /.row small-spacing -->
@endsection

@section('modals')

    <div class="modal fade" id="boostrapModal-2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-1">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel-1">{{__('Supplier Locations')}}</h4>
                </div>
                <div class="modal-body">
                    <div class="modal-body" id="map" style="height: 400px;" >

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

    {!! JsValidator::formRequest('App\Http\Requests\Admin\Suppliers\CreateSuppliersRequest', '.form'); !!}

    @include('admin.partial.sweet_alert_messages')


    <script type="application/javascript">

        $("#country").change(function () {
            $.ajax({
                url: "{{ route('admin:country.cities') }}?country_id=" + $(this).val(),
                method: 'GET',
                success: function (data) {
                    $('#city').html(data.cities);
                    $('#currency').html(data.currency);
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

        function getCompanyData(type) {
            if (type == 'person') {
                $(".company_data").hide();
            } else {
                $(".company_data").show();
            }
        }

        function getGroupsByBranch() {

            var branch_id = $("#branch_id").val();

            $.ajax({
                url: "{{ route('admin:suppliers.groups.by.branch.by.branch')}}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {branch_id: branch_id},

                success: function (response) {
                    $('.js-example-basic-single').select2();
                    $(".removeToNewData").remove();
                    var option = new Option('', '{{__('Select Group')}}');
                    option.text = '{{__("Select Group")}}';
                    option.value = '';
                    $("#supplier_groups_options").html(response.mainGroup);
                    $("#sub_groups_id").html(response.subGroup);
                }
            });
        }

        function newContact () {

            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            let contacts_count = $("#contacts_count").val();

            $.ajax({

                type: 'post',
                url: '{{route('admin:suppliers.new.contact')}}',
                data: {
                    _token: CSRF_TOKEN,
                    contacts_count:contacts_count
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

        function newBankAccount () {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            let bank_account_count = $("#bank_account_count").val();
            $.ajax({
                type: 'post',
                url: '{{route('admin:suppliers.new.bank-account')}}',
                data: {
                    _token: CSRF_TOKEN,
                    bank_account_count:bank_account_count
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

        function getSubGroups() {

            let mainGroupId = $("#main_group_id").val();
            let order = $('#main_group_id').find(":selected").data('order');

            $.ajax({
                url: "{{ route('admin:suppliers.getSubGroupsByMainIds')}}",

                method: 'post',

                data: {
                    _token: '{{csrf_token()}}',
                    group_id: mainGroupId,
                    order:order
                },
                success: function (response) {
                    $("#sub_groups").html(response.view)
                    $(".js-example-basic-single").select2();
                }
            })
        }

    </script>

    <script type="application/javascript" defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD4YXeD4XTeNieaKWam43diRHXjsGg7aVY&callback=initMap"></script>
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

@endsection
