

@foreach($parts as $part)
    @php
        $imageUrl = $part->img ? url('storage/images/parts/'.$part->img) : url('default-images/defualt.png');
    @endphp
    <td class="align-center col-xs-3">

        <div class="">
            <div class="card__image-holder">
                <a class="example-image-link" href="{{$imageUrl}}"
                   data-lightbox="example-1">
                    <img class="example-image"
                         src="{{$imageUrl}}" id="output_image"/>

                    <div class="frame"></div>
                </a>
            </div>

            <a class="nav-link active"
               onclick="getPartsDetails('{{$part->id}}')" href="#" id="part_details">
                <div class="card-title">
                    <h2 style="font-size: 12px">
                        {{$part->name}}
                        <i class="fa fa-plus"></i>
                    </h2>
                    <h2 style="font-size: 12px; display: none;">{{$part->barcode}}
                        <i class="fa fa-plus"></i>
                    </h2>
                </div>
            </a>
        </div>
    </td>
@endforeach
