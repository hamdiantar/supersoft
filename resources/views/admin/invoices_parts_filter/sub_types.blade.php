@foreach($subSparParts as $partsType)

    <td class="align-center col-xs-3">
        <div class="card">
            <div class="card__image-holder">
                <a class="example-image-link" href="{{$partsType->img}}" data-lightbox="example-1">
                    <img class="example-image" src="{{$partsType->img}}" id="output_image"/>
                    <div class="frame"></div>
                </a>
            </div>
            <a class="nav-link active" onclick="getSubPartsTypes({{$partsType->id}}, 'sub_type')" id="spare_part_type" style="cursor: pointer">
                <div class="card-title">
                    <h2 class="text-center" style="font-size: 12px">
                        {{$partsType->type}}
                        <i class="fa fa-search"></i>
                    </h2>
                </div>
            </a>
        </div>
    </td>

@endforeach
