<div class="box-content box-content-wg card bordered-all blue-1 js__card">
    <h4 class="box-title bg-blue-1 with-control">
        {{__('Customer data')}}
        <span class="controls">
							<button type="button" class="control fa fa-minus js__card_minus"></button>
							<button type="button" class="control fa fa-times js__card_remove"></button>
                            </span>
    </h4>

    <div class="card-content js__card_content" style="">
        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-xs-5"><label>{{__('Name')}}:</label></div>
                    <!-- /.col-xs-5 -->
                    <div class="col-xs-7">{{$customer->name}}</div>
                    <!-- /.col-xs-7 -->
                </div>
                <!-- /.row -->
            </div>

            <!-- /.col-md-6 -->
            <div class="col-md-6">
                <div class="row">
                    <div class="col-xs-5"><label>{{__('Type')}}:</label></div>
                    <!-- /.col-xs-5 -->
                    <div class="col-xs-7">{{$customer->type}}</div>
                    <!-- /.col-xs-7 -->
                </div>
                <!-- /.row -->
            </div>

            <!-- /.col-md-6 -->
            <div class="col-md-6">
                <div class="row">
                    <div class="col-xs-5"><label>{{__('phone')}}:</label></div>
                    <!-- /.col-xs-5 -->
                    <div class="col-xs-7">{{$customer->phone1}}</div>
                    <!-- /.col-xs-7 -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.col-md-6 -->
            <div class="col-md-6">
                <div class="row">
                    <div class="col-xs-5"><label>{{__('Email')}}:</label></div>
                    <!-- /.col-xs-5 -->
                    <div class="col-xs-7">{{$customer->email ? $customer->email : '---'}}</div>
                    <!-- /.col-xs-7 -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.col-md-6 -->
            <div class="col-md-6">
                <div class="row">
                    <div class="col-xs-5"><label>{{__('Cars Number')}}:</label></div>
                    <!-- /.col-xs-5 -->
                    <div class="col-xs-7">{{$customer->cars_number}}</div>
                    <!-- /.col-xs-7 -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.col-md-6 -->
            <div class="col-md-6">
                <div class="row">
                    <div class="col-xs-5"><label>{{__('Country')}}:</label></div>
                    <!-- /.col-xs-5 -->
                    <div class="col-xs-7">{{$customer->country ? $customer->country->name : '---'  }}</div>
                    <!-- /.col-xs-7 -->
                </div>
                <!-- /.row -->
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-xs-5"><label>{{__('City')}}:</label></div>
                    <!-- /.col-xs-5 -->
                    <div class="col-xs-7">{{$customer->city ? $customer->city->name : '---'  }}</div>
                    <!-- /.col-xs-7 -->
                </div>
                <!-- /.row -->
            </div>

            <div class="col-md-6">
                <div class="row">
                    <div class="col-xs-5"><label>{{__('Area')}}:</label></div>
                    <!-- /.col-xs-5 -->
                    <div class="col-xs-7">{{$customer->area ? $customer->area->name : '---'  }}</div>
                    <!-- /.col-xs-7 -->
                </div>
                <!-- /.row -->
            </div>

            <!-- /.col-md-6 -->
        </div>
    </div>
</div>
