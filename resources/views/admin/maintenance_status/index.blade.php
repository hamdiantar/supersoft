@extends('admin.layouts.app')
@section('title')
    <title>{{ __('Super Car') }} - {{ __('Maintenance Status') }} </title>
@endsection
@section('content')
    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Maintenance Status')}}</li>
            </ol>
        </nav>

        <div class="col-xs-12">
            <div class="box-content card bordered-all js__card top-search">
                <h4 class="box-title with-control">
                    <i class="fa fa-search"></i>{{__('Search filters')}}
                    <span class="controls">
							<button type="button" class="control fa fa-minus js__card_minus"></button>
							<button type="button" class="control fa fa-times js__card_remove"></button>
						</span>
                    <!-- /.controls -->
                </h4>
                <!-- /.box-title -->
                <div class="card-content js__card_content" style="padding:20px">
                    <form action="{{route('admin:maintenance.status.index.report')}}" method="get" id="filtration-form">

                        <ul class="list-inline margin-bottom-0 row">


                            <li class="form-group col-md-3">
                                <label> {{ __('Search') }} </label>
                                <input type="text" name="search" class="form-control"
                                       placeholder="{{ __('customer name, phone, car number') }}">
                            </li>

                            <li class="form-group col-md-3">
                                <label> {{ __('Date From') }} </label>
                                <input type="date" name="date_from" class="form-control"
                                       placeholder="{{ __('Date From') }}">
                            </li>


                            <li class="form-group col-md-3">
                                <label> {{ __('Date To') }} </label>
                                <input type="date" name="date_to" class="form-control"
                                       placeholder="{{ __('Date To') }}">
                            </li>

                            <li class="form-group col-md-4">

                            </li>
                        </ul>
                        <button type="submit" class="btn sr4-wg-btn   waves-effect waves-light hvr-rectangle-out">
                            <i class=" fa fa-search "></i>
                            {{__('Search')}}
                        </button>
                        <a href="{{route('admin:maintenance.status.index.report')}}"
                           class="btn bc-wg-btn   waves-effect waves-light hvr-rectangle-out">
                            <i class=" fa fa-reply"></i>
                            {{__('Back')}}
                        </a>

                    </form>
                </div>
            </div>
        </div>

        <div class="col-xs-12">
            <div class="box-content">

                <!-- /.box-title -->

                <!-- /.dropdown js__dropdown -->
                <div class="row">

                    <div class="col-md-12 margin-bottom-20">
                        <ul class="nav nav-tabs nav-justified wg-tabs-maintance" id="myTabs-justified" role="tablist">

                            <li role="presentation" class="active">
                                <a href="#Pending-justified" id="Pending-tab-justified" role="tab" data-toggle="tab"
                                   aria-controls="Pending" aria-expanded="true">
                                   <img src="http://localhost/cars/public/assets/images/f1.png" class="img-fluid" style="width: 50px; height: 50px; margin-top: 5px; margin-bottom: 0;"><br>
                                    {{__('Pending')}} ({{$count['pending']}})
                                </a>
                            </li>

                            <li role="presentation">
                                <a href="#Processing-justified" role="tab" id="Processing-tab-justified"
                                   data-toggle="tab" aria-controls="Processing">
                                   <img src="http://localhost/cars/public/assets/images/f1.png" class="img-fluid" style="width: 50px; height: 50px; margin-top: 5px; margin-bottom: 0;"><br>
                                    {{__('Processing')}} ({{$count['processing']}})
                                </a>
                            </li>

                            <li role="presentation">
                                <a href="#Finished-justified" role="tab" id="Finished-tab-justified" data-toggle="tab"
                                   aria-controls="Finished">
                                   <img src="http://localhost/cars/public/assets/images/f1.png" class="img-fluid" style="width: 50px; height: 50px; margin-top: 5px; margin-bottom:0;"><br>
                                    {{__('Finished')}} ({{$count['finished']}})
                                </a>
                            </li>

                            <li role="presentation">
                                <a href="#scheduled-justified" role="tab" id="scheduled-tab-justified" data-toggle="tab"
                                   aria-controls="scheduled">
                                   <img src="http://localhost/cars/public/assets/images/f1.png" class="img-fluid" style="width: 50px; height: 50px; margin-top: 5px; margin-bottom: 0;"><br>
                                    {{__('scheduled')}} ({{$count['scheduled']}})
                                </a>
                            </li>
                        </ul>
                        <!-- /.nav-tabs -->
                        <div class="tab-content" id="myTabContent-justified">

                            {{--  PENDING CARDS  --}}
                            <div class="tab-pane fade in active" role="tabpanel" id="Pending-justified"
                                 aria-labelledby="Pending-tab-justified">

                                <div class="row" style="padding: 10px;" id="more_pending_cards">

                                    @foreach($cardsPending as $cardPending)

                                        <div class="col-lg-2 col-md-6 col-xs-12"
                                             style="border: 1px solid #b9c0ca; border-radius: 10px;margin: 10px;">
                                            <div class="statistics-box with-icon ">
                                                <i class="ico fa fa-car text-info"></i>
                                                <a href="{{$cardPending->customer ? route('admin:cars', $cardPending->customer->id) : '#'}}">
                                                    <h2 class="counter text-primary fa fa-eye"></h2>
                                                </a>
                                                <p class="text">{{optional($cardPending->customer)->name}}</p>
                                            </div>
                                        </div>

                                    @endforeach

                                </div>

                                @if($cardsPending->count() >= 10)
                                    <div style="text-align:center;" id="div_pending_to_more_result">
                                        <hr>
                                        <button type="button" class="btn btn-default btn_load_pending"
                                                id="btn-clear-search" onclick="loadMoreCardPending()">
                                            {{__('Show More ')}}
                                        </button>
                                        <br>
                                        <img src="{{asset('default-images/card_invoice_loading.gif')}}"
                                             style="width: 30px;height: 30px; display: none;" id="loader_pending">
                                    </div>
                                @endif

                                <input type="hidden" id="page-pending-id" value="2">
                                <input type="hidden" id="page-pending-last" value="2">

                            </div>

                            {{--  PROCESSING CARDS  --}}
                            <div class="tab-pane fade" role="tabpanel" id="Processing-justified"
                                 aria-labelledby="Processing-tab-justified">

                                <div class="row" style="padding: 10px;" id="more_processing_cards">

                                    @foreach($cardsProcessing as $cardProcessing)

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

                                </div>

                                @if($cardsProcessing->count() >= 10)
                                    <div style="text-align:center;" id="div_processing_to_more_result">
                                        <hr>
                                        <button type="button" class="btn btn-default btn_load_processing"
                                                id="btn-clear-search" onclick="loadMoreCardProcessing()">
                                            {{__('Show More ')}}
                                        </button>
                                        <br>
                                        <img src="{{asset('default-images/card_invoice_loading.gif')}}"
                                             style="width: 30px;height: 30px; display: none;" id="loader_processing">
                                    </div>
                                @endif

                                <input type="hidden" id="page-processing-id" value="2">
                                <input type="hidden" id="page-processing-last" value="2">

                            </div>


                            {{--  FINISHED CARDS  --}}
                            <div class="tab-pane fade" role="tabpanel" id="Finished-justified"
                                 aria-labelledby="Finished-tab-justified">

                                <div class="row" style="padding: 10px;" id="more_finished_cards">

                                    @foreach($cardsFinished as $cardFinished)

                                        <div class="col-lg-2 col-md-6 col-xs-12"
                                             style="border: 1px solid #b9c0ca; border-radius: 10px;margin: 10px;">
                                            <div class="statistics-box with-icon ">
                                                <i class="ico fa fa-car text-info"></i>
                                                <a href="{{$cardFinished->customer ? route('admin:cars', $cardFinished->customer->id) : '#'}}">
                                                    <h2 class="counter text-primary fa fa-eye"></h2>
                                                </a>
                                                <p class="text">{{optional($cardFinished->customer)->name}}</p>
                                            </div>
                                        </div>

                                    @endforeach

                                </div>

                                @if($cardsFinished->count() >= 10)
                                    <div style="text-align:center;" id="div_finished_to_more_result">
                                        <hr>
                                        <button type="button" class="btn btn-default btn_load_finished"
                                                id="btn-clear-search" onclick="loadMoreCardFinished()">
                                            {{__('Show More ')}}
                                        </button>
                                        <br>
                                        <img src="{{asset('default-images/card_invoice_loading.gif')}}"
                                             style="width: 30px;height: 30px; display: none;" id="loader_finished">
                                    </div>
                                @endif

                                <input type="hidden" id="page-finished-id" value="2">
                                <input type="hidden" id="page-finished-last" value="2">
                            </div>

                            {{--  schedulded cards  --}}
                            <div class="tab-pane fade" role="tabpanel" id="scheduled-justified"
                                 aria-labelledby="scheduled-tab-justified">

                                <div class="row" style="padding: 10px;" id="more_scheduled_cards">

                                    @foreach($cardsScheduled as $cardScheduled)

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

                                </div>

                                @if($cardsScheduled->count() >= 10)
                                    <div style="text-align:center;" id="div_scheduled_to_more_result">
                                        <hr>
                                        <button type="button" class="btn btn-default btn_load_scheduled"
                                                id="btn-clear-search" onclick="loadMoreCardScheduled()">
                                            {{__('Show More ')}}
                                        </button>
                                        <br>
                                        <img src="{{asset('default-images/card_invoice_loading.gif')}}"
                                             style="width: 30px;height: 30px; display: none;" id="loader_scheduled">
                                    </div>
                                @endif

                                <input type="hidden" id="page-scheduled-id" value="2">
                                <input type="hidden" id="page-scheduled-last" value="2">

                            </div>


                        </div>
                        <!-- /.tab-content -->
                    </div>
                    <!-- /.col-md-6 -->
                </div>


            </div>
            <!-- /.box-content -->
        </div>

    </div>
@endsection

@section('js')

    <script>

        function loadMoreCardPending() {

            event.preventDefault();

            let search = "{{request()->query('search')}}";
            let date_from = "{{request()->query('date_from')}}";
            let date_to = "{{request()->query('date_to')}}";

            var page = $("#page-pending-id").val();
            var last_page = $("#page-pending-last").val();

            $("#page-pending-id").remove();
            $("#page-pending-last").remove();

            var path = '{{route('admin:maintenance.status.pending','page')}}' + '=' + page;

            $.ajax(
                {
                    url: path,
                    type: "GET",
                    data: {search: search, date_from: date_from, date_to: date_to},
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },

                    beforeSend: function () {
                        $('#loader_pending').show();
                    },

                    success: function (data) {

                        $('#loader_pending').hide();

                        if (data.html == "") {

                            $('.btn_load_pending').hide();

                            $('#div_pending_to_more_result').append(
                                "<p id='no_data' class='moreload'>{{__('No More Results')}}</span>");

                            return;
                        }

                        $("#more_pending_cards").append(data.html);

                    },
                    error: function (jqXhr, json, errorThrown) {

                        var errors = jqXhr.responseJSON;

                        $.toast({
                            heading: '{{__('Sorry')}}',
                            text: errors,
                            position: 'top-center',
                            stack: false,
                            hideAfter: 7000,
                            loaderBg: '#FF389F'
                        });
                    },
                });
        }

        function loadMoreCardProcessing() {

            event.preventDefault();

            let search = "{{request()->query('search')}}";
            let date_from = "{{request()->query('date_from')}}";
            let date_to = "{{request()->query('date_to')}}";


            var page = $("#page-processing-id").val();
            var last_page = $("#page-processing-last").val();

            $("#page-processing-id").remove();
            $("#page-processing-last").remove();

            var path = '{{route('admin:maintenance.status.processing','page')}}' + '=' + page;

            $.ajax(
                {
                    url: path,
                    type: "GET",
                    data: {search: search, date_from: date_from, date_to: date_to},
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },

                    beforeSend: function () {
                        $('#loader_processing').show();
                    },

                    success: function (data) {

                        $('#loader_processing').hide();

                        if (data.html == "") {

                            $('.btn_load_processing').hide();

                            $('#div_processing_to_more_result').append(
                                "<p id='no_data' class='moreload'>{{__('No More Results')}}</span>");

                            return;
                        }

                        $("#more_processing_cards").append(data.html);

                    },
                    error: function (jqXhr, json, errorThrown) {

                        var errors = jqXhr.responseJSON;

                        $.toast({
                            heading: '{{__('Sorry')}}',
                            text: errors,
                            position: 'top-center',
                            stack: false,
                            hideAfter: 7000,
                            loaderBg: '#FF389F'
                        });
                    },
                });
        }

        function loadMoreCardFinished() {

            event.preventDefault();

            let search = "{{request()->query('search')}}";
            let date_from = "{{request()->query('date_from')}}";
            let date_to = "{{request()->query('date_to')}}";


            var page = $("#page-finished-id").val();
            var last_page = $("#page-finished-last").val();

            $("#page-finished-id").remove();
            $("#page-finished-last").remove();

            var path = '{{route('admin:maintenance.status.finished','page')}}' + '=' + page;

            $.ajax(
                {
                    url: path,
                    type: "GET",
                    data: {search: search, date_from: date_from, date_to: date_to},
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },

                    beforeSend: function () {
                        $('#loader_finished').show();
                    },

                    success: function (data) {

                        $('#loader_finished').hide();

                        if (data.html == "") {

                            $('.btn_load_finished').hide();

                            $('#div_finished_to_more_result').append(
                                "<p id='no_data' class='moreload'>{{__('No More Results')}}</span>");

                            return;
                        }

                        $("#more_finished_cards").append(data.html);

                    },
                    error: function (jqXhr, json, errorThrown) {

                        var errors = jqXhr.responseJSON;

                        $.toast({
                            heading: '{{__('Sorry')}}',
                            text: errors,
                            position: 'top-center',
                            stack: false,
                            hideAfter: 7000,
                            loaderBg: '#FF389F'
                        });
                    },
                });
        }

        function loadMoreCardScheduled() {

            event.preventDefault();

            let search = "{{request()->query('search')}}";
            let date_from = "{{request()->query('date_from')}}";
            let date_to = "{{request()->query('date_to')}}";


            var page = $("#page-scheduled-id").val();
            var last_page = $("#page-scheduled-last").val();

            $("#page-scheduled-id").remove();
            $("#page-scheduled-last").remove();

            var path = '{{route('admin:maintenance.status.scheduled','page')}}' + '=' + page;

            $.ajax(
                {
                    url: path,
                    type: "GET",
                    data: {search: search, date_from: date_from, date_to: date_to},
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },

                    beforeSend: function () {
                        $('#loader_scheduled').show();
                    },

                    success: function (data) {

                        $('#loader_scheduled').hide();

                        if (data.html == "") {

                            $('.btn_load_scheduled').hide();

                            $('#div_scheduled_to_more_result').append(
                                "<p id='no_data' class='moreload'>{{__('No More Results')}}</span>");

                            return;
                        }

                        $("#more_scheduled_cards").append(data.html);

                    },
                    error: function (jqXhr, json, errorThrown) {

                        var errors = jqXhr.responseJSON;

                        $.toast({
                            heading: '{{__('Sorry')}}',
                            text: errors,
                            position: 'top-center',
                            stack: false,
                            hideAfter: 7000,
                            loaderBg: '#FF389F'
                        });
                    },
                });
        }


    </script>

@endsection
