@foreach($parts as $part)
    <td class="align-center col-xs-3">
        <div class="card">
            <div class="card__image-holder">
                <a class="example-image-link" href="{{$part->image}}"
                   data-lightbox="example-1">
                    <img class="example-image" src="{{$part->image}}"
                         id="output_image"/>
                    <div class="frame"></div>
                </a>
            </div>
            <a class="nav-link active"
               onclick="getPartsDetails('{{$part->id}}')" href="#" id="part_details">
                <div class="card-title">
                    <h2 class="text-center h2-inv"
                        style="font-size: 12px !important">
                        {{$part->name}} <p
                            style="display: none">{{$part->barcode}}</p>
                        <i class="fa fa-plus"></i>
                    </h2>
                </div>
            </a>
        </div>

    </td>
@endforeach
