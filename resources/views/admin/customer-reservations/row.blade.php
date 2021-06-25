<tr>
    <td> {{ isset($reservation) ? $reservation->cust_name : '' }} </td>
    <td> {{ isset($reservation) ? $reservation->cust_phone : '' }} </td>
    <td> {{ isset($reservation) ? $reservation->reservation_date : '' }} </td>
    <td> {{ isset($reservation) ? $reservation->reservation_time : '' }} </td>
    <td>
        @if ( isset($reservation) )
            @if ($reservation->reservation_status == 'approved')
                <span class="label label-success"> {{ __('reservations.approved') }} </span>
            @elseif ($reservation->reservation_status == 'rejected')
            <span class="label label-danger"> {{ __('reservations.rejected') }} </span>
            @else
                <span class="label label-warning"> {{ __('reservations.pending') }} </span>
            @endif
        @endif
    </td>
    <td> {{ isset($reservation) ? $reservation->reservation_title : '' }} </td>
    <td>
        @if (isset($reservation))
            <a class="btn btn-info" href="{{ route('admin:customers.edit' ,['customer' => $reservation->cust_id]) }}">
                <i class="fa fa-user"></i>
            </a>
            <button type="button" class="btn btn-success"
                onclick="load_reservation('{{ route('admin:reservations.get_reservation' ,['id' => $reservation->reservation_id]) }}')">
                <i class="fa fa-edit"></i>
            </button>
        @endif
    </td>
</tr>