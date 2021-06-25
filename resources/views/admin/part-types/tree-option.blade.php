<option value="{{ $child->id }}"  {{ isset($_GET['partId']) && $_GET['partId'] ==  $child->id ? 'selected' : '' }}
    class="{{ isset($className) ? $className : '' }}"
    {{ isset($is_selected) ? $is_selected : '' }}>
    {{ isset($counter) ? $counter.' .' : ''  }} {{ $child->type }}
</option>
