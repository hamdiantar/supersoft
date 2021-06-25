@php
    $module_screens = [
        'accounts-tree',
        'account-guide',
        'account-relations',
        'daily-restrictions',
        'cost-center',
        'general-ledger',
        'fiscal-years',
        'adverse-restrictions',
        'trial-balance',
        'balance-sheet',
        'income-list',
        'trading-account'
    ];
    $is_group_access = false;
    $current_url = url()->current();
    foreach($module_screens as $screen) {
        if (stripos($current_url ,$screen) !== false) {
            $is_group_access = true;
            break;
        }
    }
@endphp
<li class="{{ $is_group_access ? 'current active open' : '' }}">
    <a class="waves-effect parent-item js__control" href="#">
        <i style="color:#3C5DF2 !important" class="menu-icon fa fa-calculator"></i>
        <span> {{ __('accounting-module.accounting-module') }} </span><span class="menu-arrow fa fa-angle-down"></span>
    </a>
    <ul class="sub-menu js__content wg-menu2" style="">
        @if (!authIsSuperAdmin())
            <li class="{{ $current_url == route('accounts-tree-index') ? 'current' : '' }}">
                <a href="{{ route('accounts-tree-index') }}">
                    {{ __('accounting-module.accounts-tree-index') }}
                </a>
            </li>
            <li class="{{ $current_url == route('account-relations.index') ? 'current' : '' }}">
                <a href="{{ route('account-relations.index') }}">
                    {{ __('accounting-module.account-relations-index') }}
                </a>
            </li>
            <li class="{{ $current_url == route('account-guide-index') ? 'current' : '' }}">
                <a href="{{ route('account-guide-index') }}">
                    {{ __('accounting-module.account-guide-index') }}
                </a>
            </li>
            <li class="{{ $current_url == route('daily-restrictions.index') ? 'current' : '' }}">
                <a href="{{ route('daily-restrictions.index') }}">
                    {{ __('accounting-module.daily-restrictions-index') }}
                </a>
            </li>
            <li class="{{ $current_url == route('cost-centers.index') ? 'current' : '' }}">
                <a href="{{ route('cost-centers.index') }}">
                    {{ __('accounting-module.cost-centers-index') }}
                </a>
            </li>
            <li class="{{ $current_url == route('fiscal-years.index') ? 'current' : '' }}">
                <a href="{{ route('fiscal-years.index') }}">
                    {{ __('accounting-module.fiscal-years-index') }}
                </a>
            </li>
            <li class="{{ $current_url == route('adverse-restrictions') ? 'current' : '' }}">
                <a href="{{ route('adverse-restrictions') }}">
                    {{ __('accounting-module.adverse-restrictions') }}
                </a>
            </li>
        @endif
        <li class="{{ $current_url == route('accounting-general-ledger') ? 'current' : '' }}">
            <a href="{{ route('accounting-general-ledger') }}">
                {{ __('accounting-module.accounting-general-ledger') }}
            </a>
        </li>
        <li class="{{ $current_url == route('trial-balance-index') ? 'current' : '' }}">
            <a href="{{ route('trial-balance-index') }}">
                {{ __('accounting-module.trial-balance-index') }}
            </a>
        </li>
        <li class="{{ $current_url == route('balance-sheet-index') ? 'current' : '' }}">
            <a href="{{ route('balance-sheet-index') }}">
                {{ __('accounting-module.balance-sheet-index') }}
            </a>
        </li>
        <li class="{{ $current_url == route('income-list-index') ? 'current' : '' }}">
            <a href="{{ route('income-list-index') }}">
                {{ __('accounting-module.income-list-index') }}
            </a>
        </li>
        <li class="{{ $current_url == route('trading-account-index') ? 'current' : '' }}">
            <a href="{{ route('trading-account-index') }}">
                {{ __('accounting-module.trading-account-index') }}
            </a>
        </li>
    </ul>
</li>