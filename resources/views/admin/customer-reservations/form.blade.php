<form action="{{ route('admin:reservations.update' ,['id' => $reservation->id]) }}" method="POST" onsubmit="return submit_update(event)">
    @csrf
    @method('PUT')
    <div class="modal-body invoiceDatatoPrint">
        <div class="col-md-4 form-group">
            <label> {{ __('Date') }} </label>
            <input class="form-control" disabled
                type="date" value="{{ $reservation->event_date }}"/>
        </div>
        <div class="col-md-4 form-group">
            <label> {{ __('Time') }} </label>
            <input class="form-control" disabled
                type="time" value="{{ $reservation->event_time }}"/>
        </div>
        <div class="col-md-4 form-group">
            <label> {{ __('reservations.event-title') }} </label>
            <input class="form-control" disabled
                type="text" value="{{ $reservation->event_title }}"/>
        </div>
        <div class="col-md-6" style="margin-bottom: 5px; margin-top: 5px;">
            <label> {{ __('reservations.event-comment') }} </label>
            <textarea rows="4" disabled
                class="form-control" placeholder="{{ __('reservations.event-comment') }}">{{ $reservation->customer_comment }}</textarea>
        </div>
        <div class="col-md-6" style="margin-bottom: 5px; margin-top: 5px;">
            <label> {{ __('reservations.admin-comment') }} </label>
            <textarea name="admin_comment" rows="4"
                class="form-control" placeholder="{{ __('reservations.admin-comment') }}">{{ $reservation->admin_comment }}</textarea>
        </div>
        <div class="col-md-12" style="margin-bottom: 5px; margin-top: 5px;">
            <label> {{ __('reservations.reservation-status') }} </label>
            <select class="form-control" name="status">
                <option {{ $reservation->status == 'pending' ? 'selected' : '' }} value=""> {{ __('reservations.pending') }} </option>
                <option {{ $reservation->status == 'approved' ? 'selected' : '' }} value="approved"> {{ __('reservations._approved') }} </option>
                <option {{ $reservation->status == 'rejected' ? 'selected' : '' }} value="rejected"> {{ __('reservations._rejected') }} </option>
            </select>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="modal-footer" style="text-align:center;margin-top:10px">
        <button class="btn btn-primary"> {{ __('reservations.event-update') }} </button>
        <button type="button" class="btn btn-danger waves-effect waves-light"
                data-dismiss="modal">
            <i class='fa fa-close'></i>
            {{__('Close')}}
        </button>
    </div>
</form>