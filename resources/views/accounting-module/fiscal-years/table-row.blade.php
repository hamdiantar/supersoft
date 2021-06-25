<tr>
    <td> {{ $row->name_ar }} </td>
    <td> {{ $row->name_en }} </td>
    <td>
        <div class="form-group">
            <ul class="list-inline">
                <li>
                    <div class="radio pink">
                        <input type="radio" name="fiscal_year_status" class="{{ $row->status == 1 ? 'selected-year' : '' }}"
                            onclick="change_fiscal_year_status(event ,{{ $row->status }} ,'{{ route('fiscal-years.change-status' ,['id' => $row->id ]) }}')"
                            value="{{ $row->status }}" {{ $row->status == 1 ? 'checked' : '' }}
                            id="radio-{{$row->id}}"><label for="radio-{{$row->id}}">{{ __('accounting-module.status-'.$row->status) }}</label></div>
                </li>
            </ul>
        </div>
    </td>
    <td> {{ $row->start_date }} </td>
    <td> {{ $row->end_date }} </td>
    <td>
        @if($editable)
            <a class="btn btn-info"
                @if(user_can_access_accounting_module(NULL ,'fiscal-years' ,'edit'))
                    href="{{ route('fiscal-years.edit' ,['fiscal_year' => $row->id ]) }}"
                @else
                    disabled
                @endif
                >
                <i class="fa fa-edit"></i>
            </a>
            <button class="btn btn-danger"
                {{ user_can_access_accounting_module(NULL ,'fiscal-years' ,'delete') ? '' : 'disabled' }}
                onclick="confirmDelete('{{ route('fiscal-years.delete' ,['id' => $row->id ]) }}')">
                <i class="fa fa-trash-o"></i>
            </button>
        @endif
    </td>
</tr>