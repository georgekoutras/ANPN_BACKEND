@php
    $r = \Route::current()->getAction();
    $route = (isset($r['as'])) ? $r['as'] : '';
@endphp

@if(auth()->user()->role == 'administrator')
    <li class="nav-item">
        <a class="sidebar-link {{ Str::startsWith($route, 'accounts') ? 'active' : '' }}" href="{{ route('accounts.index') }}">
            <span class="icon-holder">
                <i class="ti-user"></i>
            </span>
            <span class="title">Λογαριασμοί</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="sidebar-link {{ Str::startsWith($route, 'daily_reports') ? 'active' : '' }}" href="{{ route('daily_reports.patients') }}">
            <span class="icon-holder">
                <i class="ti-receipt"></i>
            </span>
            <span class="title">Ημερήσιες Αναφορές</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="sidebar-link {{ Str::startsWith($route, 'notifications') ? 'active' : '' }}" href="{{ route('notifications.accounts') }}">
            <span class="icon-holder">
                <i class="ti-tag"></i>
            </span>
            <span class="title">Ειδοποιήσεις</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="sidebar-link {{ Str::startsWith($route, 'cat') ? 'active' : '' }}" href="{{ route('cats.patients') }}">
            <span class="icon-holder">
                <i class="ti-direction"></i>
            </span>
            <span class="title">CATS</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="sidebar-link {{ Str::startsWith($route, 'ccq') ? 'active' : '' }}" href="{{ route('ccqs.patients') }}">
            <span class="icon-holder">
                <i class="ti-credit-card"></i>
            </span>
            <span class="title">CCQS</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="sidebar-link {{ Str::startsWith($route, 'cci') ? 'active' : '' }}" href="{{ route('ccis.patients') }}">
            <span class="icon-holder">
                <i class="ti-agenda"></i>
            </span>
            <span class="title">Charlsons</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="sidebar-link {{ Str::startsWith($route, 'reading') ? 'active' : '' }}" href="{{ route('readings.patients') }}">
            <span class="icon-holder">
                <i class="ti-view-list-alt"></i>
            </span>
            <span class="title">Διαγνώσεις</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="sidebar-link {{ Str::startsWith($route, 'treatment') ? 'active' : '' }}" href="{{ route('treatments.patients') }}">
            <span class="icon-holder">
                <i class="ti-layout-accordion-list"></i>
            </span>
            <span class="title">Θεραπείες</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="sidebar-link {{ Str::startsWith($route, 'death') ? 'active' : '' }}" href="{{ route('deaths.patients') }}">
            <span class="icon-holder">
                <i class="ti-ink-pen"></i>
            </span>
            <span class="title">Θάνατοι</span>
        </a>
    </li>
@elseif(auth()->user()->role == 'doctor')
    <li class="nav-item">
        <a class="sidebar-link {{ Str::startsWith($route, 'accounts') ? 'active' : '' }}" href="{{ route('accounts.index') }}">
            <span class="icon-holder">
                <i class="ti-user"></i>
            </span>
            <span class="title">Ασθενείς</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="sidebar-link {{ Str::startsWith($route, 'daily_reports') ? 'active' : '' }}" href="{{ route('daily_reports.patients') }}">
            <span class="icon-holder">
                <i class="ti-receipt"></i>
            </span>
            <span class="title">Ημερήσιες Αναφορές</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="sidebar-link {{ Str::startsWith($route, 'notifications') ? 'active' : '' }}" href="{{ route('notifications.accounts') }}">
            <span class="icon-holder">
                <i class="ti-tag"></i>
            </span>
            <span class="title">Ειδοποιήσεις</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="sidebar-link {{ Str::startsWith($route, 'cat') ? 'active' : '' }}" href="{{ route('cats.patients') }}">
            <span class="icon-holder">
                <i class="ti-direction"></i>
            </span>
            <span class="title">CATS</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="sidebar-link {{ Str::startsWith($route, 'ccq') ? 'active' : '' }}" href="{{ route('ccqs.patients') }}">
            <span class="icon-holder">
                <i class="ti-credit-card"></i>
            </span>
            <span class="title">CCQS</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="sidebar-link {{ Str::startsWith($route, 'cci') ? 'active' : '' }}" href="{{ route('ccis.patients') }}">
            <span class="icon-holder">
                <i class="ti-agenda"></i>
            </span>
            <span class="title">Charlsons</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="sidebar-link {{ Str::startsWith($route, 'reading') ? 'active' : '' }}" href="{{ route('readings.patients') }}">
            <span class="icon-holder">
                <i class="ti-view-list-alt"></i>
            </span>
            <span class="title">Διαγνώσεις</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="sidebar-link {{ Str::startsWith($route, 'treatment') ? 'active' : '' }}" href="{{ route('treatments.patients') }}">
            <span class="icon-holder">
                <i class="ti-layout-accordion-list"></i>
            </span>
            <span class="title">Θεραπείες</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="sidebar-link {{ Str::startsWith($route, 'death') ? 'active' : '' }}" href="{{ route('deaths.patients') }}">
            <span class="icon-holder">
                <i class="ti-ink-pen"></i>
            </span>
            <span class="title">Θάνατοι</span>
        </a>
    </li>
@elseif(auth()->user()->role == 'patient')
    <li class="nav-item">
        <a class="sidebar-link {{ Str::startsWith($route, 'daily_reports') ? 'active' : '' }}" href="{{ route('daily_reports.patients.reports', $patientId) }}">
            <span class="icon-holder">
                <i class="ti-receipt"></i>
            </span>
            <span class="title">Ημερήσιες Αναφορές</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="sidebar-link {{ Str::startsWith($route, 'notifications') ? 'active' : '' }}" href="{{ route('notifications.accounts.list',$accountId) }}">
            <span class="icon-holder">
                <i class="ti-tag"></i>
            </span>
            <span class="title">Ειδοποιήσεις</span>
        </a>
    </li>
@endif
