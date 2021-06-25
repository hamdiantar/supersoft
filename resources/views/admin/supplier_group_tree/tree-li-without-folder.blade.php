<li>
    <!-- {{ isset($counter) ? $counter.'- ' : ''  }} -->
    {{ $child->name }}
    {{ authIsSuperAdmin() ? '('.optional($child->branch)->name.')' : '' }}
