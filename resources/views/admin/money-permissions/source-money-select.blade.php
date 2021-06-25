<option value=""> {{ __($select_one) }} </option>
@foreach($money_source as $source)
    <option
        @if($include_balance)
            data-balance="{{ $source->balance }}"
        @endif
        value="{{ $source->id }}"> {{ $source->name }} </option>
@endforeach