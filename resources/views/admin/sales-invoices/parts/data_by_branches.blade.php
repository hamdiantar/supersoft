<div class="col-md-6">
    <h3>{{__('Spare Parts Type')}}</h3>
    <input type="text" placeholder="{{__('Search')}}" id="searchInSparePartsType" onkeyup="searchInPartsType()" class="form-control">
    <ul class="nav nav-pills nav-stacked anyClass form-control" style="text-align: center">
        <div class="searchResultSparePartsType">
            <li class="nav-item">
                <a class="nav-link active" href="#" onclick="getSparePartsById('all')"
                   id="spare_part_type"
                >
                    <div class="card">
                        <div class="card__image-holder">
                            <img class="card__image" src="{{url('default-images/defualt.png')}}"
                                 alt="wave" style="max-height: 100px; max-width: 100px;"/>
                        </div>
                        <div class="card-title">
                            <h2 style="font-size: 20px">
                                {{__('All')}}
                                <i class="fa fa-search"></i>
                            </h2>
                        </div>
                    </div>
                </a>
            </li>
            <hr>
            @foreach($partsTypes as $part)
                @php
                    $imageUrl = $part->image ? url('storage/images/spare-parts/'.$part->image) : url('default-images/defualt.png');
                @endphp
                <li class="nav-item">
                    <a class="nav-link active" href="#"
                       onclick="getSparePartsById({{$part->id}})" id="spare_part_type">
                        <div class="card">
                            <div class="card__image-holder">
                                <img class="card__image" src="{{$imageUrl}}" alt="wave" width="100px" height="100px"/>
                            </div>
                            <div class="card-title">
                                <h2 style="font-size: 20px">
                                    {{$part->type}} <i class="fa fa-search"></i>
                                </h2>
                            </div>
                        </div>
                    </a>
                </li>
                <hr>
            @endforeach
        </div>
    </ul>
</div>
<div class="col-md-6">
    <h3>{{__('Spare Parts')}}</h3>
    <input type="text" id="searchInParts" placeholder="{{__('Search')}}" class="form-control" onkeyup="searchInPartsData()">
    <ul class="nav nav-pills nav-stacked anyClass" id="add_parts" class="form-control"
        style="text-align: center" i>
        @foreach($parts as $part)
            @php
                $imageUrl = $part->img ? url('storage/images/parts/'.$part->img) : url('default-images/defualt.png');
            @endphp
            <li class="nav-item">
                <a class="nav-link active" onclick="getPartsDetails('{{$part->id}}')"
                   href="#" id="part_details">
                    <div class="card">
                        <div class="card__image-holder">
                            <img class="card__image" src="{{$imageUrl}}" alt="wave" width="100px"
                                 height="100px"/>
                        </div>
                        <div class="card-title">
                            <h2 style="font-size: 20px">
                                {{$part->name}}
                                <i class="fa fa-plus"></i>
                            </h2>
                        </div>
                    </div>
                </a></li>
        @endforeach
    </ul>
</div>