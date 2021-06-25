<div class="col-xs-12">
    <div class="box-content  box-content-wg card bordered-all blue-1 js__card">
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
@section('js')
    <script type="application/javascript">
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var table = $('#cars').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin:cars', ['id' => $customer->id])}}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'carType', name: 'carType.type_ar'},
                    {data: 'plate_number', name: 'plate_number'},
                    {data: 'Chassis_number', name: 'Chassis_number'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                "language": {
                    "url": "{{app()->isLocale('ar')  ? url('trans/ar.json') :  url('trans/en.json')}}",
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

                        swal("{{__('successfully')}}",  "{{__('Cars has been Added Successfully')}}", "success",{

                            buttons: {
                                confirm: {
                                    text: "{{__('Ok')}}",
                                },
                            }
                        });

                        $(".form-control").val("");
                        $("#setModelsByCompany").val("").change();
                        $("#getModelsByCompany").val("").change();
                        $("#setCarType").val("").change();
                        $('#addCar').html("{{__('Save')}}");
                        $('#carsCount').html(data.carsCount);
                        var output = document.getElementById('output_image');
                        output.src = '';
                        table.draw();
                        if(returnBack === "addCarAndBack") {
                            window.location.replace("{{url('admin/customers')}}")
                        }
                    },
                    error: function (data) {

                        console.log(data.responseJSON);
                        $('#addCar').html("{{__('Save')}}");
                        $('#addCarAndBack').html("{{__('Save And Back')}}");

                        if(returnBack !== "addCarAndBack") {

                            swal("{{__('Error')}}",  data.responseJSON, "error",{

                                buttons: {
                                    confirm: {
                                        text: "{{__('Ok')}}",
                                    },
                                }
                            });
                        }

                        if(returnBack === "addCarAndBack") {
                            window.location.replace("{{url('admin/customers')}}")
                        }
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

                let carTypes =  `<option value="">{{__('Select Car Type')}}</option>
                @foreach(\App\Models\CarType::all() as $type)
                <option value="{{$type->id}}" >{{$type->type}}</option>
                @endforeach`;

                let companies =  `<option value="">{{__('Select Company')}}</option>
                @foreach(\App\Models\Company::all() as $company)
                <option value="{{$company->id}}">{{$company->name}}</option>
                @endforeach`;

                let models =  `<option value="">{{__('Select Car Model')}}</option>
                @foreach(\App\Models\CarModel::all() as $carModel)
                <option value="{{$carModel->id}}" >{{$carModel->name}}</option>
                @endforeach`;

                $('#car_id').val(carData[0]);
                $('#setCarType').html(carTypes);
                $('#getModelsByCompany').html(companies);
                $('#setModelsByCompany').html(models);
                $('#model').val(carData[2]);
                $('#plate_number').val(carData[3]);
                $('#Chassis_number').val(carData[4]);
                $('#speedometer').val(carData[5]);
                $('#barcode').val(carData[6]);
                $('#color').val(carData[7]);
                $('#motor_number').val(carData[9]);
                // $('#image').val(carData[8]);
               $("#output_image").attr("src", "{{asset('storage/images/cars/')}}"+"/"+carData[8]);
               $("#openLargeImage").attr("href", "{{asset('storage/images/cars/')}}"+"/"+carData[8]);



                $('#setCarType').val(carData[1]).change();
                $('#getModelsByCompany').val(carData[10]).change();

                $('#setModelsByCompany').val(carData[2]).change();

            }

        function openWin() {

            var qty = $("#barcode_qty").val();
            var barcode = $("#barcode").val();

            $.ajax({
                type: 'get',
                data: {barcode: barcode,qty: qty},
                url: "{{route('admin:cars.print.barcode')}}",
                success: function (data) {
                    var myWindow = window.open('', '', 'width=700,height=500');
                    myWindow.document.write(data);

                    myWindow.document.close();
                },
                error: function (jqXhr, json, errorThrown) {

                    var errors = jqXhr.responseJSON;
                    swal("Error!", errors[0], "error");
                },
            });

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
