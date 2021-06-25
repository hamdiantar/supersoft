@foreach($cardsProcessing as $cardProcessing )
    <div class="col-lg-2 col-md-6 col-xs-12"
         style="border: 1px solid #b9c0ca; border-radius: 10px;margin: 10px;">
        <div class="statistics-box with-icon ">
            <i class="ico fa fa-car text-info"></i>
            <a href="{{$cardProcessing->customer ? route('admin:cars', $cardProcessing->customer->id) : '#'}}">
                <h2 class="counter text-primary fa fa-eye"></h2>
            </a>
            <p class="text">{{optional($cardProcessing->customer)->name}}</p>
        </div>
    </div>
@endforeach

@if($cardsProcessing->count())
    <input type="hidden" id="page-processing-id" value="{{$page}}">
    <input type="hidden" id="page-processing-last" value="{{$last_page}}">
@endif
