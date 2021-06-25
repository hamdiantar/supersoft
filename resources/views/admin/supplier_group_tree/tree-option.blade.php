<option value="{{ $child->id }}"
    class="{{ isset($className) ? $className : '' }}"
    {{isset($supplier) &&
in_array($child->id, $supplier->main_groups_id->pluck('id')->toArray()) ? 'selected':''}}>
    {{ isset($counter) ? $counter.' .' : ''  }} {{ $child->name_ar }}
</option>
