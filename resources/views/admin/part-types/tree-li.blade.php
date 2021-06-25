
<li>
    <span class="fa fa-plus clickable{{$child->id}}" onclick="openTree('clickable{{$child->id}}')" data-current-ul="ul-tree-{{ $child->id }}"></span>
    <span class="folder-span fa fa-folder" onclick="openTree('clickable{{$child->id}}')"></span>
    <span onclick="select_part_type( {{ $child->id }}, 'clickable{{$child->id}}')">
        {{ isset($counter) ? $counter.' ' : ''  }} {{ $child->type }} {{ authIsSuperAdmin() ? ' - ' .optional($child->branch)->name : '' }}
    </span>
