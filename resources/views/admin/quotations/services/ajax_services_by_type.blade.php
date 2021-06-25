
<div style="text-align: center; display: none; margin-top: 66px; " id="services_by_type_loading">
    <img src="{{asset('default-images/loading.gif')}}"
         style="height: 45px;width: 45px;">
</div>

@foreach($services as $service)
<li class="nav-item bg-blue text-white pakage-item  ">
        <a style="font-size: 12px" class="nav-link active" onclick="getServiceDetails('{{$service->id}}')"
           href="#" id="part_details">
                        {{$service->name}} <i class="fa fa-plus"></i>
        </a>
    </li>
@endforeach
