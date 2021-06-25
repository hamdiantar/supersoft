
<div class="row">
    <div class="col-xs-12">
        <div class="box-content card">
            <h4 class="box-title"><i class="fa fa-gear"></i>{{__('Package Info')}}</h4>
            <div class="card-content">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-xs-5"><label> {{__('Name')}}</label></div>
                            <!-- /.col-xs-5 -->
                            <div class="col-xs-7">{{$servicePackage->name}}</div>
                            <!-- /.col-xs-7 -->
                        </div>
                        <!-- /.row -->
                    </div>
                    <!-- /.col-md-6 -->
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-xs-5"><label>{{__('Total before discount')}}</label></div>
                            <!-- /.col-xs-5 -->
                            <div class="col-xs-7">{{$servicePackage->total_before_discount}}</div>
                            <!-- /.col-xs-7 -->
                        </div>
                        <!-- /.row -->
                    </div>

                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-xs-5"><label>{{__('Total after discount')}}</label></div>
                            <!-- /.col-xs-5 -->
                            <div class="col-xs-7">{{$servicePackage->total_after_discount}}</div>
                            <!-- /.col-xs-7 -->
                        </div>
                        <!-- /.row -->
                    </div>
                    <!-- /.col-md-6 -->
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-xs-5"><label>{{__('Discount type')}}</label></div>
                            <!-- /.col-xs-5 -->
                            <div class="col-xs-7">{{$servicePackage->discount_type}}</div>
                            <!-- /.col-xs-7 -->
                        </div>
                        <!-- /.row -->
                    </div>

                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-xs-5"><label>{{__('Discount value')}}</label></div>
                            <!-- /.col-xs-5 -->
                            <div class="col-xs-7">{{$servicePackage->discount_value}}</div>
                            <!-- /.col-xs-7 -->
                        </div>
                        <!-- /.row -->
                    </div>

                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-xs-5"><label>{{__('Branch')}}</label></div>
                            <!-- /.col-xs-5 -->
                            <div class="col-xs-7">{{optional($servicePackage->branch)->name}}</div>
                            <!-- /.col-xs-7 -->
                        </div>
                        <!-- /.row -->
                    </div>

                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-xs-5"><label>{{__('Number of hours')}}</label></div>
                            <!-- /.col-xs-5 -->
                            <div class="col-xs-7">{{$servicePackage->number_of_hours}}</div>
                            <!-- /.col-xs-7 -->
                        </div>
                        <!-- /.row -->
                    </div>

                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-xs-5"><label>{{__('Number of minutes')}}</label></div>
                            <!-- /.col-xs-5 -->
                            <div class="col-xs-7">{{$servicePackage->number_of_min}}</div>
                            <!-- /.col-xs-7 -->
                        </div>
                        <!-- /.row -->
                    </div>

                <!-- /.col-md-6 -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.card-content -->
        </div>
        <!-- /.box-content card -->


    </div>
    <div class="col-xs-12">
        <div class="box-content card">
            <h4 class="box-title"><i class="fa fa-gear"></i>{{__('Package services')}}</h4>
            <div class="card-content">
                <div class="row">
                    <table class="table table-bordered" style="width:100%">
                        <thead>
                        <tr>
                            <th scope="col">{!! __('Service Name') !!}</th>
                            <th scope="col">{!! __('Price') !!}</th>
                            <th scope="col">{!! __('Hours') !!}</th>
                            <th scope="col">{!! __('Minutes') !!}</th>
                            <th scope="col">{!! __('Quantity') !!}</th>
                            <th scope="col">{!! __('Total') !!}</th>
{{--                            <th scope="col">{!! __('Action') !!}</th>--}}
                        </tr>
                        </thead>
                        <tbody id="add_services">
                        @foreach($services  as $service)
                            <tr data-id='{{$service['id']}}' id='{{$service['id']}}'>
                                <input type="hidden" name="service_id[]" value='{{$service['id']}}'>
                                <td>{{ $service['name'] }}</td>
                                <td id="servicePrice-{{$service['id']}}">{{$service['price']}}</td>
                                <td id="serviceH-{{$service['id']}}" value='{{$service['hours']}}'>{{$service['hours']}}</td>
                                <td style="display: none"><input type="hidden" class="form-control" value="{{$service['totalHours']}}" id="totalH-{{$service['id']}}"></td>
                                <td id="serviceM-{{$service['id']}}" value='{{$service['minutes']}}'>{{$service['minutes']}}</td>
                                <td>{{$service['q']}}</td>
                                <td>{{$service['total']}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.card-content -->
        </div>
        <!-- /.box-content card -->
    </div>
</div>