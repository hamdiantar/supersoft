@if($cars->count() != 0)
    <tr id="customer_cars_loading" style="display: none;">
        <td colspan="8" style="background:white;">
            <img src="{{asset('default-images/loading.gif')}}">
        </td>
    </tr>

    @foreach($cars as $index=>$car)
        <tr class="removeOldCustomerCars">
            <td>{{$index+1}}</td>
            <td>{{$car->type}}</td>
            <td>{{$car->plate_number}}</td>
            <td>{{$car->Chassis_number}}</td>
            <td>{{$car->barcode}}</td>
            <td>{{$car->color}}</td>
            <td>
                <a href="{{asset('storage/images/cars/'.$car->image)}}" target="_blank">
                    <img src="{{asset('storage/images/cars/'.$car->image)}}" alt="" style="    width: 50px;height: 30px;">
                </a>
            </td>
            <td>
                <button class="btn btn-primary btn-xs" onclick="selectCustomerCar('{{$car->id}}','select_cars')">
                    {{__('Select')}}
                </button>
            </td>
        </tr>
    @endforeach
@else
    <span>{{{__('sorry no cars for this customer')}}}</span>
@endif