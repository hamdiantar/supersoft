@foreach($items as $item)
    <div class="modal fade" id="execution_period_{{$item->id}}" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div style="background:#5685CC" class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 style="color:white !important" class="modal-title" id="myModalLabel-1">{{$title}}</h4>
                </div>

                <form action="{{$url}}" method="post" class="form">
                    @csrf
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="date" class="control-label">{{__('Date From')}}</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><li class="fa fa-dashcube"></li></span>
                                        <input type="date" name="date_from" class="form-control"
                                               value="{{old('date_from', $item->execution ? $item->execution->start_date : '')}}">
                                    </div>
                                    {{input_error($errors,'date_from')}}
                                </div>
                            </div>

                            <input type="hidden" name="item_id" value="{{$item->id}}">

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="date" class="control-label">{{__('Date To')}}</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><li class="fa fa-dashcube"></li></span>
                                        <input type="date" name="date_to" class="form-control"
                                               value="{{old('date_to', $item->execution ? $item->execution->end_date : '')}}">
                                    </div>
                                    {{input_error($errors,'date_to')}}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="date" class="control-label">{{__('Status')}}</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><li class="fa fa-dashcube"></li></span>
                                        <select name="status" class="form-control">
                                            <option value="pending"
                                                {{$item->execution && $item->execution->status == 'pending' ? 'selected':''}}>
                                                {{__('Processing')}}
                                            </option>
                                            <option value="finished"
                                                {{$item->execution && $item->execution->status == 'finished' ? 'selected':''}}>
                                                {{__('Finished')}}
                                            </option>
                                            <option value="late"
                                                {{$item->execution && $item->execution->status == 'late' ? 'selected':''}}>
                                                {{__('Late')}}
                                            </option>
                                        </select>
                                    </div>
                                    {{input_error($errors,'status')}}
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="date" class="control-label">{{__('Notes')}}</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><li class="fa fa-dashcube"></li></span>
                                        <textarea class="form-control" name="notes"
                                        >{{$item->execution ? $item->execution->notes : '' }}</textarea>
                                    </div>
                                    {{input_error($errors,'notes')}}
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="modal-footer" style="text-align:center">

                        <button type="submit" class="btn btn-primary waves-effect waves-light">
                            <i class='fa fa-print'></i>
                            {{__('Save')}}
                        </button>

                        <button type="button" class="btn btn-danger waves-effect waves-light" data-dismiss="modal">
                            <i class='fa fa-close'></i>
                            {{__('Close')}}
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
@endforeach
