<div class="col-xs-6">
    <div class="box-content card bordered-all blue-1 js__card">
        <h4 class="box-title bg-blue-1 with-control">
            {{__('Cars List')}}
        </h4>
        <div class="card-content js__card_content" style="">
            <table id="cars" class="table table-bordered" style="width:100%">
                <thead>
                <tr>
                    <th>#</th>
                    <th>{{__('Car Type')}}</th>
                    <th>{{__('Plate Number')}}</th>
                    <th>{{__('Chassis Number')}}</th>
                    <th>{{__('Options')}}</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
@section('js-car')
    <script type="application/javascript">
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var table = $('#cars').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin:cars', ['id' => isset($customer)? $customer->id:''])}}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'type', name: 'type'},
                    {data: 'plate_number', name: 'plate_number'},
                    {data: 'Chassis_number', name: 'Chassis_number'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                "language": {
                    "url": "{{app()->isLocale('ar')  ? "//cdn.datatables.net/plug-ins/1.10.20/i18n/Arabic.json" :
                                             "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/English.json"}}",
                }
            });

            $('#addCar, #addCarAndBack').on('click', function (e) {
                e.preventDefault();
                var returnBack = this.id;
                $(this).html("{{__('Sending..')}}");
                $.ajax({
                    data: new FormData($("#carForm")[0]),
                    processData: false,
                    contentType: false,
                    url: "{{ route('admin:addCar') }}",
                    type: "POST",
                    success: function (data) {
                        console.log(2);
                        swal("{{__('successfully')}}",  "{{__('Cars has been Added Successfully')}}", "success",{

                            buttons: {
                                confirm: {
                                    text: "{{__('Ok')}}",
                                },
                            }
                        });

                        $(".clear_data").val("");
                        $('#addCar').html("{{__('Save')}}");
                        $('#carsCount').html(data.carsCount);
                        var output = document.getElementById('output_image');
                        output.src = '';
                        table.draw();
                        if(returnBack === "addCarAndBack") {
                            $('#addSupplierForm').modal('hide');
                        }
                    },
                    error: function (data) {

                        // $('#add_customer_form').hide();
                        // $('#CustomerCarFrom').show();
                        $('#addCar').html("{{__('Save')}}");
                        $('#addCarAndBack').html("{{__('Save And Back')}}");

                        // if(returnBack !== "addCarAndBack") {

                            swal("{{__('Error')}}",  data.responseJSON, "error",{

                                buttons: {
                                    confirm: {
                                        text: "{{__('Ok')}}",
                                    },
                                }
                            });
                        // }

                        // if(returnBack === "addCarAndBack") {
                        //     $('#addSupplierForm').modal('hide');
                        // }
                    }
                });
            });

            $(document).on('click', '#removeCar', function () {
                var car_id = $(this).data("id");
                swal({
                    title: "{{__('Delete Car')}}",
                    text: "{{__('Are you sure want to Delete Car ?')}}",
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
                        $.ajax({
                            type: "DELETE",
                            url: "{{url('admin/customer/delete/car/')}}" + '/' + car_id,
                            success: function (data) {

                                swal("{{__('successfully')}}",  "{{__('Car has been Deleted Successfully')}}", "success",{

                                    buttons: {
                                        confirm: {
                                            text: "{{__('Ok')}}",
                                        },
                                    }
                                });

                                swal("{{__('successfully')}}", "{{__('Car has been Deleted Successfully')}}", "success");
                                $('#carsCount').html(data.carsCount);
                                table.draw();
                            },
                            error: function (data) {

                                swal("{{__('Error')}}", "{{__('Some Thing went Wrong')}}", "error",{

                                    buttons: {
                                        confirm: {
                                            text: "{{__('Ok')}}",
                                        },
                                    }
                                });
                            }
                        });
                    }
                });
            });

            $(document).on('click', '#editCar', function (e) {
                e.preventDefault();
                $("#output_image").show();
                $('#carTitle').html("{{__('Edit Car')}}");
                $('#addCar').html("{{__('Edit')}}");
                $('#addCarAndBack').html("{{__('Edit And Back')}}");
                var carData = $(this).data('info').split(',');
                $(".print_number_barcode").show();
                fillCarForm(carData);
            });

            function fillCarForm(carData)
            {
                $('#car_id').val(carData[0]);
                $('#cartype').val(carData[1]);
                $('#model').val(carData[2]);
                $('#plate_number').val(carData[3]);
                $('#Chassis_number').val(carData[4]);
                $('#speedometer').val(carData[5]);
                $('#barcode').val(carData[6]);
                $('#color').val(carData[7]);
                // $('#image').val(carData[8]);
               $("#output_image").attr("src", "{{asset('storage/images/cars/')}}"+"/"+carData[8]);
            }

            $(document).on('click', '#resetCarData', function () {
                swal({
                    title: "{{__('Reset Form')}}",
                    text: "{{__('Are you sure want to reset form ?')}}",
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

                        swal("{{__('successfully')}}",  "{{__('form has been reset')}}", "success",{

                            buttons: {
                                confirm: {
                                    text: "{{__('Ok')}}",
                                },
                            }
                        });

                        $('#carTitle').html("{{__('Add Car')}}");
                        $('#addCar').html("{{__('Save')}}");
                        $('#addCarAndBack').html("{{__('Save And Back')}}");
                        $(".form-control").val("");
                        $('#car_id').val('');
                        let image = $("#output_image");
                        image.attr("src", "");
                        image.hide();
                    }
                });
            });

            $("#getModelsByCompany").on( 'change',function () {
                $.ajax({
                    url: "{{ route('admin:companies.models') }}?company_id=" + $(this).val(),
                    method: 'GET',
                    success: function (data) {
                        $('#setModelsByCompany').html(data.models);
                    }
                });
            });

    </script>
@endsection
