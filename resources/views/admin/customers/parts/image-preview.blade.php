@if ($part->image === null)


<td>
<div class="image-container small-wg handing ani image-format-hd" style="width: 100%;">
<a href="{{asset('default-images/defualt.png')}}" data-lightbox="roadtrip">
<img src="{{asset('default-images/defualt.png')}}">
</a>
<div class="frame"></div>
</div>
</td>
    <!-- <td><img  style="    height: 59px;width: 107px;" src="{{asset('default-images/defualt.png')}}"></td> -->
@endif

@if ($part->image !== null)


<td>
<div class="image-container small-wg handing ani image-format-hd" style="width: 100%;">
<a href="{{asset('storage/images/spare-parts/'.$part->image)}}" data-lightbox="roadtrip">
<img src="{{asset('storage/images/spare-parts/'.$part->image)}}">
</a>
<div class="frame"></div>
</div>
</td>
    <!-- <td><img  style="    height: 59px;width: 107px;" src="{{asset('storage/images/spare-parts/'.$part->image)}}"></td> -->
@endif

