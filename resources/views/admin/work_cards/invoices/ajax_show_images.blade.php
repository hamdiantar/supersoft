<div class="row removeOldImages">
    @if(count($images) != 0)
        @foreach($images as $image)
            <div class="col-md-3 col-xs-6">
                <a href="{{asset('storage/images/maintenance_parts/'.$image)}}" target="_blank" class="thumbnail">
                    <img alt="100%x180" data-src="holder.js/100%x180"
                         src="{{asset('storage/images/maintenance_parts/'.$image)}}"
                         data-holder-rendered="true" style="height: 180px; width: 100%; display: block;">
                </a>
            </div>
        @endforeach
        @else
        <div style="text-align: center">
            <span>{{__('sorry no images')}}</span>
        </div>

    @endif
</div>