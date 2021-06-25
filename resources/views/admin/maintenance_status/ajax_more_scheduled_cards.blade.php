@foreach($cardsScheduled as $cardScheduled )
    <div class="col-lg-2 col-md-6 col-xs-12"
         style="border: 1px solid #b9c0ca; border-radius: 10px;margin: 10px;">
        <div class="statistics-box with-icon ">
            <i class="ico fa fa-car text-info"></i>
            <a href="{{$cardScheduled->customer ? route('admin:cars', $cardScheduled->customer->id) : '#'}}">
                <h2 class="counter text-primary fa fa-eye"></h2>
            </a>
            <p class="text">{{optional($cardScheduled->customer)->name}}</p>
        </div>
    </div>
@endforeach

@if($cardsScheduled->count())
    <input type="hidden" id="page-scheduled-id" value="{{$page}}">
    <input type="hidden" id="page-scheduled-last" value="{{$last_page}}">
@endif
