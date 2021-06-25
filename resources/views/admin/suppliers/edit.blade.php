@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('Edit Supplier') }} </title>
@endsection

@section('content')
    <div class="row small-spacing">

    <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin:suppliers.index')}}"> {{__('Suppliers')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Edit Supplier')}}</li>
            </ol>
        </nav>

        <div class="col-xs-12">
        <div class=" card box-content-wg-new bordered-all primary">
                <h4 class="box-title with-control" style="text-align: initial"><i class="fa fa-user ico"></i>{{__('Edit Supplier')}}
                <span class="controls hidden-sm hidden-xs pull-left">
                <button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Save')}}<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f1.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Reset')}}<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f2.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px"> {{__('Back')}} <img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f3.png')}}"></button>
						</span>
            </h4>


                    <div class="box-content">

                    <form method="post" action="{{route('admin:suppliers.update',$supplier->id)}}" class="form"
                          enctype="multipart/form-data">
                        @method('PATCH')
                        @csrf
                        @include('admin.suppliers.form')
                    </form>
                </div>
            </div>
        </div>
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
{{--    {!! JsValidator::formRequest('App\Http\Requests\Admin\Suppliers\UpdateSuppliersRequest', '.form'); !!}--}}
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

        function destroyContact (contact_id) {

            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            swal({

                title: "Delete Contact",
                text: "Are you sure want to delete this contact ?",
                icon: "warning",
                buttons: {
                    confirm: {
                        text: "{{__('Ok')}}",
                    },
                    cancel: {
                        text: "{{__('Cancel')}}",
                        visible: true,
                    }
                },
                dangerMode: true,

            }).then((willDelete) => {

                if (willDelete) {

                    $.ajax({

                        type: 'post',
                        url: '{{route('admin:suppliers.contact.destroy')}}',
                        data: {
                            _token: CSRF_TOKEN,
                            contact_id:contact_id
                        },

                        beforeSend: function () {
                            // $("#loader_get_test_video").show();
                        },

                        success: function (data) {

                            $("#contact_" + data.id).fadeOut('slow');
                            $("#contact_" + data.id).remove();

                            swal({text: '{{__('Contact Deleted Successfully')}}}', icon: "success"})
                        },

                        error: function (jqXhr, json, errorThrown) {

                            var errors = jqXhr.responseJSON;
                            swal({text: errors, icon: "error"})
                        }
                    });
                }
            });

        }

        function updateContact (contact_id) {

            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            swal({

                title: "Edit Contact",
                text: "Are you sure want to Edit this contact ?",
                icon: "warning",
                buttons: {
                    confirm: {
                        text: "{{__('Ok')}}",
                    },
                    cancel: {
                        text: "{{__('Cancel')}}",
                        visible: true,
                    }
                },
                dangerMode: true,

            }).then((willDelete) => {

                if (willDelete) {

                    let phone_1 = $("#phone_1_" + contact_id).val();
                    let phone_2 = $("#phone_2_" + contact_id).val();
                    let address = $("#address_" + contact_id).val();
                    let name    = $("#name_" + contact_id).val();

                    $.ajax({

                        type: 'post',
                        url: '{{route('admin:suppliers.contact.update')}}',
                        data: {
                            _token: CSRF_TOKEN,
                            contact_id:contact_id,
                            phone_1:phone_1,
                            phone_2:phone_2,
                            address:address,
                            name:name,
                        },

                        success: function (data) {

                            swal({text: '{{__('Contact Updated Successfully')}}', icon: "success"})
                        },

                        error: function (jqXhr, json, errorThrown) {

                            var errors = jqXhr.responseJSON;
                            swal({text: errors, icon: "error"})
                        }
                    });
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
                    order:order,
                    supplier_id:'{{$supplier->id}}'
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

    function destroyBankAccount (bankAccountId)
    {
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        swal({
            title: "{{__('Delete Bank Account')}}",
            text: "{{__('Are you sure want to delete this Bank Account?')}}",
            icon: "warning",
            buttons: {
                confirm: {
                    text: "{{__('Ok')}}",
                },
                cancel: {
                    text: "{{__('Cancel')}}",
                    visible: true,
                }
            },
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    type: 'post',
                    url: '{{route('admin:suppliers.bank-account.destroy')}}',
                    data: {
                        _token: CSRF_TOKEN,
                        bankAccountId: bankAccountId
                    },
                    success: function (data) {
                        $("#bank_account_" + data.id).fadeOut('slow');
                        $("#bank_account_" + data.id).remove();
                        swal({text: '{{__('Bank Account Deleted Successfully')}}', icon: "success"})
                    },
                    error: function (jqXhr, json, errorThrown) {
                        var errors = jqXhr.responseJSON;
                        swal({text: errors, icon: "error"})
                    }
                });
            }
        });
    }

    function updateBankAccount (bankAccountId)
    {
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        swal({
            title: "{{__('Edit Bank Account')}}",
            text: "{{__('Are you sure want to Edit this Bank Account ?')}}",
            icon: "warning",
            buttons: {
                confirm: {
                    text: "{{__('Ok')}}",
                },
                cancel: {
                    text: "{{__('Cancel')}}",
                    visible: true,
                }
            },
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                let bank_name = $("#bank_name_" + bankAccountId).val();
                let account_name = $("#account_name_" + bankAccountId).val();
                let branch = $("#branch_" + bankAccountId).val();
                let account_number = $("#account_number_" + bankAccountId).val();
                let iban = $("#iban_" + bankAccountId).val();
                let swift_code = $("#swift_code_" + bankAccountId).val();
                $.ajax({
                    type: 'post',
                    url: '{{route('admin:suppliers.bank-account.update')}}',
                    data: {
                        _token : CSRF_TOKEN,
                        bankAccountId : bankAccountId,
                        bank_name : bank_name,
                        account_name : account_name,
                        branch : branch,
                        account_number : account_number,
                        iban : iban,
                        swift_code : swift_code,
                    },
                    success: function (data) {
                        swal({text: '{{__('Bank Account Updated Successfully')}}', icon: "success"})
                    },
                    error: function (jqXhr, json, errorThrown) {
                        var errors = jqXhr.responseJSON;
                        swal({text: errors, icon: "error"})
                    }
                });
            }
        });
    }
</script>

@endsection
