<li>
    <span class="fa fa-plus clickable" data-current-ul="ul-tree-{{ $child->id }}"></span>  <span class="folder-span fa fa-folder"></span> 
    <span onclick="select_cost_center( {{ $child->id }})">
        {{ $counting. ' ' .($lang == 'ar' ? $child->name_ar : $child->name_en) }}
    </span>