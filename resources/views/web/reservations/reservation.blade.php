@if($reservation->status == 'pending')
<form action="{{ route('web:reservations.update' ,['id' => $reservation->id]) }}" method="POST" onsubmit="return editable_form_submit(event)">
    @csrf
    @method('PUT')
@endif
    <div class="modal-body invoiceDatatoPrint">
        <div class="col-md-4 form-group">
            <label> {{ __('Date') }} </label>
            <input name="event_date" class="form-control" {{ $reservation->status != 'pending' ? 'disabled' : '' }}
                type="date" value="{{ $reservation->event_date }}"/>
        </div>
        <div class="col-md-4 form-group">
            <label> {{ __('Time') }} </label>
            <input name="event_time" class="form-control" {{ $reservation->status != 'pending' ? 'disabled' : '' }}
                type="time" value="{{ $reservation->event_time }}"/>
        </div>
        <div class="col-md-4 form-group">
            <label> {{ __('reservations.event-title') }} </label>
            <input name="event_title" class="form-control" {{ $reservation->status != 'pending' ? 'disabled' : '' }}
                type="text" value="{{ $reservation->event_title }}"/>
        </div>
        <div class="col-md-{{ $reservation->status != 'pending' ? '6' : '12' }}" style="margin-bottom: 5px; margin-top: 5px;">
            <label> {{ __('reservations.event-comment') }} </label>
            <textarea name="customer_comment" rows="4" {{ $reservation->status != 'pending' ? 'disabled' : '' }}
                class="form-control" placeholder="{{ __('reservations.event-comment') }}">{{ $reservation->customer_comment }}</textarea>
        </div>
        @if($reservation->status != 'pending')
            <div class="col-md-6" style="margin-bottom: 5px; margin-top: 5px;">
                <label> {{ __('reservations.admin-comment') }} </label>
                <textarea name="customer_comment" rows="4" {{ $reservation->status != 'pending' ? 'disabled' : '' }}
                    class="form-control" placeholder="{{ __('reservations.admin-comment') }}">{{ $reservation->admin_comment }}</textarea>
            </div>
        @endif
    </div>
    <div class="clearfix"></div>
    <div class="modal-footer" style="text-align:center;margin-top:10px">
        @if($reservation->status == 'pending')
            <button class="btn btn-primary"> {{ __('reservations.event-update') }} </button>
        @endif
        <button type="button" class="btn btn-danger waves-effect waves-light"
                data-dismiss="modal">
            <i class='fa fa-close'></i>
            {{__('Close')}}
        </button>
    </div>
@if($reservation->status == 'pending')
</form>
@endif