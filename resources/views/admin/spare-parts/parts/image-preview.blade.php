@if($image === null)
    <td>
        <div class="image-container small-wg handing ani image-format-hd" style="width: 144px;">
            <a href="{{asset('default-images/defualt.png')}}" data-lightbox="roadtrip">
                <img src="{{asset('default-images/defualt.png')}}">
            </a>
            <div class="frame"></div>
        </div>
    </td>
@endif

@if($image !== null)
    <td>
        <div class="image-container small-wg handing ani image-format-hd" style="width: 144px;" >
            <a href="{{asset($path.$image)}}" data-lightbox="roadtrip">
                <img src="{{asset($path.$image)}}">
            </a>
            <div class="frame"></div>
        </div>
    </td>
@endif