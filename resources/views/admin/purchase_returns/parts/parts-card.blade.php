<div class=" form-group col-md-12">
    <div class="box-content card bordered-all blue-1 js__card">
        <h4 class="box-title bg-blue-1 with-control">
            {{__('Spare Parts Details')}}
            <span class="controls">
							<button type="button" class="control fa fa-minus js__card_minus"></button>
							<button type="button" class="control fa fa-times js__card_remove"></button>
						</span>
        </h4>
        <div class="card-content js__card_content" style="">
            <div class="row">
                <div class="col-md-6">
                    <h3>{{__('Spare Parts Type')}}</h3>
                    <input type="text" placeholder="{{__('Search')}}" id="searchInSparePartsType" class="form-control">
                    <ul class="nav nav-pills nav-stacked anyClass form-control" style="text-align: center">
                        <div class="searchResultSparePartsType">
                            <li class="nav-item">
                                <a class="nav-link active" href="#" onclick="getSparePartsById('all')"
                                   id="spare_part_type"
                                >
                                    <div class="card">
                                        <div class="card__image-holder">
                                            <img class="card__image" src="{{url('default-images/defualt.png')}}" alt="wave" style="max-height: 100px; max-width: 100px;"/>
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
                            @foreach(\App\Models\SparePart::where('status', 1)->get() as $part)
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
                                                    {{$part->type}}
                                                    <i class="fa fa-search"></i>
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
                    <input type="text" id="searchInParts" placeholder="{{__('Search')}}" class="form-control">
                    <ul class="nav nav-pills nav-stacked anyClass" id="add_parts" class="form-control" style="text-align: center">
                        @foreach(\App\Models\Part::all() as $part)
                            @php
                                $imageUrl = $part->img ? url('storage/images/parts/'.$part->img) : url('default-images/defualt.png');
                            @endphp
                            <li class="nav-item">
                                <a class="nav-link active" onclick="getPartsDetails('{{$part->id}}')"
                                   href="#" id="part_details">
                                    <div class="card">
                                        <div class="card__image-holder">
                                            <img class="card__image" src="{{$imageUrl}}" alt="wave" width="100px" height="100px"/>
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
            </div>
        </div>
    </div>
</div>
