@can('view_points_log')
    <div class="col-xs-12">
        <div class="box-content box-content-wg card bordered-all blue-1 js__card">
            <h4 class="box-title bg-blue-1 with-control">
                {{__('Customer Points Log')}}
                <span style="float: right;"> {{__('Points')}} : {{$customer->points}}</span>
                <span class="controls">

                <button type="button" class="control fa fa-minus js__card_minus"></button>
                <button type="button" class="control fa fa-times js__card_remove"></button>
			</span>
            </h4>

            <div class="card-content js__card_content" style="">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>{{__('Amount money')}}</th>
                        <th>{{__('Points')}}</th>
                        <th>{{__('Log')}}</th>
                        <th>{{__('Type')}}</th>
                        <th>{{__('Created Date')}}</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($customer->pointsLogs()->paginate(10) as $pointsLog)
                        <tr>
                            <td>{{$pointsLog->amount}}</td>
                            <td>{{$pointsLog->points}}</td>
                            <td>{{__($pointsLog->log)}}</td>
                            <td>{{__($pointsLog->type)}}</td>
                            <td>{{$pointsLog->created_at}}</td>
                        </tr>
                    @endforeach

                </table>

                <div class="pagination justify-content-center">
                    {{$customer->pointsLogs()->paginate(10)}}
                </div>
            </div>
        </div>

    </div>
@endcan
