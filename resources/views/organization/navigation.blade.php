@php $current_route = Route::currentRouteName(); @endphp

<div class="sidebar-logo-area">
    <a href="#" class="sidebar-logos">
        <img class="sidebar-logo-lg" height="50px" src="{{ get_image(get_frontend_settings('dark_logo')) }}"
            alt="">
        <img class="sidebar-logo-sm" height="40px" src="{{ get_image(get_frontend_settings('favicon')) }}" alt="">
    </a>
    <button class="sidebar-cross menu-toggler d-block d-lg-none">
        <span class="fi-rr-cross"></span>
    </button>
</div>
<h3 class="sidebar-title fs-12px px-30px pb-20px text-uppercase mt-4">{{ get_phrase('Main Menu') }}</h3>
<div class="sidebar-nav-area">
    <nav class="sidebar-nav">
        <ul class="px-14px pb-24px">

            <li class="sidebar-first-li {{ $current_route == 'organization.dashboard' ? 'active' : '' }}">
                <a href="{{ route('organization.dashboard') }}">
                    <span class="icon fi-rr-house-blank"></span>
                    <div class="text">
                        <span>{{ get_phrase('Dashboard') }}</span>
                    </div>
                </a>
            </li>

            <li class="sidebar-first-li {{ $current_route == 'organization.subscription' ? 'active' : '' }}">
                <a href="{{ route('organization.subscription') }}">
                    <span class="icon fi fi-rr-e-learning"></span>
                    <div class="text">
                        <span>{{ get_phrase('Subscription') }}</span>
                    </div>
                </a>
            </li>


            <li class="sidebar-first-li first-li-have-sub @if (
                $current_route == 'organization.users' ||
                    $current_route == 'organization.users.create' ||
                    $current_route == 'organization.users.edit') active showMenu @endif">
                <a href="javascript:void(0);">
                    <span class="icon fi-rr-circle-user"></span>
                    <div class="text">
                        <span>{{ get_phrase('Users') }}</span>
                    </div>
                </a>
                <ul class="first-sub-menu">
                    <li class="first-sub-menu-title fs-14px mb-18px">{{ get_phrase('Users') }}</li>
                    <li class="sidebar-second-li @if ($current_route == 'organization.users' || $current_route == 'organization.users.edit') active @endif">
                        <a href="{{ route('organization.users') }}">{{ get_phrase('Manage Users') }}</a>
                    </li>
                    <li class="sidebar-second-li @if ($current_route == 'organization.users.create') active @endif">
                        <a href="{{ route('organization.users.create') }}">{{ get_phrase('Add New User') }}</a>
                    </li>
                </ul>
            </li>

            <li class="sidebar-first-li first-li-have-sub @if (
                $current_route == 'organization.teams' ||
                    $current_route == 'organization.teams.create' ||
                    $current_route == 'organization.teams.edit' ||
                    $current_route == 'organization.teams.users') active showMenu @endif">
                <a href="javascript:void(0);">
                    <span class="icon fi fi-sr-users-alt"></span>
                    <div class="text">
                        <span>{{ get_phrase('Teams') }}</span>
                    </div>
                </a>
                <ul class="first-sub-menu">
                    <li class="first-sub-menu-title fs-14px mb-18px">{{ get_phrase('Teams') }}</li>
                    <li class="sidebar-second-li @if ($current_route == 'organization.teams' || $current_route == 'organization.teams.edit') active @endif">
                        <a href="{{ route('organization.teams') }}">{{ get_phrase('Manage Teams') }}</a>
                    </li>
                    <li class="sidebar-second-li @if ($current_route == 'organization.teams.create') active @endif">
                        <a href="{{ route('organization.teams.create') }}">{{ get_phrase('Add New Team') }}</a>
                    </li>
                    <li class="sidebar-second-li @if ($current_route == 'organization.teams.users') active @endif">
                        <a href="{{ route('organization.teams.users') }}">{{ get_phrase('Add Team User') }}</a>
                    </li>
                </ul>
            </li>
            <li class="sidebar-first-li first-li-have-sub @if (
                $current_route == 'organization.progress') active showMenu @endif">
                <a href="javascript:void(0);">
                    <span class="icon fi fi-sr-arrow-trend-up"></span>
                    <div class="text">
                        <span>{{ get_phrase('Team Progress') }}</span>
                    </div>
                </a>
                <ul class="first-sub-menu">
                    <li class="first-sub-menu-title fs-14px mb-18px">{{ get_phrase('Teams') }}</li>
                    <li class="sidebar-second-li @if ($current_route == 'organization.progress') active @endif">
                        <a href="{{ route('organization.progress') }}">{{ get_phrase('Team Progress') }}</a>
                    </li>
                    {{-- <li class="sidebar-second-li @if ($current_route == 'organization.teams.create') active @endif">
                        <a href="{{ route('organization.teams.create') }}">{{ get_phrase('Add New Team') }}</a>
                    </li> --}}
                </ul>
            </li>
            <li class="sidebar-first-li {{ $current_route == 'organization.my.profile' ? 'active' : '' }}">
                <a href="{{ route('organization.my.profile') }}">
                    <span class="icon fi-rr-circle-user"></span>
                    <div class="text">
                        <span>{{ get_phrase('My Profile') }}</span>
                    </div>
                </a>
            </li>
        </ul>
    </nav>
</div>
