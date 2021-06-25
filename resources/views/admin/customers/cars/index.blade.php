<div class="col-xs-12">
    <div class="box-content  box-content-wg card bordered-all blue-1 js__card">
        <h4 class="box-title bg-blue-1 with-control">
            {{__('Cars List')}}
            <span class="controls">
                <button type="button" class="control fa fa-minus js__card_minus"></button>
                <button type="button" class="control fa fa-times js__card_remove"></button>
            </span>
        </h4>
        <div class="card-content js__card_content" style="">
            <table id="cars" class="table table-bordered table-responsive" style="width:100%">
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

                @foreach($cars as $index => $car)
                    <tr>
                        <td>{{$index+1}}</td>
                        <td>{{optional($car->carType)->type}}</td>
                        <td>{{$car->plate_number}}</td>
                        <td>{{$car->Chassis_number ? $car->Chassis_number : '---' }}</td>
                        <td>
                            <ul class="list-inline ul-wg">

                                <li class="list-inline-item">

                                    <a class="btn btn-wg-edit hvr-radial-out" data-toggle="modal"
                                       data-target="#boostrapModal-2"
                                       onclick="carsEditForm({{$car->id}})">
                                        <i class="fa fa-edit"></i>
                                        {{__('Edit')}}
                                    </a>

                                </li>

                                <li class="list-inline-item">

                                    <a class="btn btn btn-print-wg text-white " onclick="openWin({{$car->id}})">
                                        <i class="fa fa-print"></i>
                                        {{__('print barcode')}}
                                    </a>

                                </li>

                                <li class="list-inline-item">

                                    @component('admin.buttons._delete_button',[
                                                 'id'=>$car->id,
                                                 'route' => 'admin:cars.destroy',
                                                 'tooltip' => __('Delete '),
                                                  ])
                                    @endcomponent
                                </li>
                            </ul>


                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
