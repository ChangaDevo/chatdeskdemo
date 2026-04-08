<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <!-- LOGO -->
    <div class="navbar-brand-box">
        <a href="{{ route('dashboard') }}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ URL::asset('build/images/logo-dark-sm.png') }}" alt="" height="26">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('build/images/logo-dark.png') }}" alt="" height="28">
            </span>
        </a>
        <a href="{{ route('dashboard') }}" class="logo logo-light">
            <span class="logo-lg">
                <img src="{{ URL::asset('build/images/logo-light.png') }}" alt="" height="30">
            </span>
            <span class="logo-sm">
                <img src="{{ URL::asset('build/images/logo-light-sm.png') }}" alt="" height="26">
            </span>
        </a>
    </div>

    <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect vertical-menu-btn">
        <i class="bx bx-menu align-middle"></i>
    </button>

    <div data-simplebar class="sidebar-menu-scroll">
        <div id="sidebar-menu">
            <ul class="metismenu list-unstyled" id="side-menu">

                <li class="menu-title">Principal</li>

                <li class="{{ request()->routeIs('dashboard') ? 'mm-active' : '' }}">
                    <a href="{{ route('dashboard') }}">
                        <i class="bx bx-home-alt icon nav-icon"></i>
                        <span class="menu-item">Dashboard</span>
                    </a>
                </li>

                <li class="menu-title">Gestión</li>

                <li class="{{ request()->routeIs('chat.*') ? 'mm-active' : '' }}">
                    <a href="{{ route('chat.index') }}">
                        <i class="bx bx-chat icon nav-icon"></i>
                        <span class="menu-item">Chat en Vivo</span>
                        @php $unread = \App\Models\Message::where('sender_type','client')->where('is_read',false)->count(); @endphp
                        @if($unread > 0)
                            <span class="badge rounded-pill bg-danger">{{ $unread }}</span>
                        @endif
                    </a>
                </li>

                <li class="{{ request()->routeIs('prospects.*') ? 'mm-active' : '' }}">
                    <a href="{{ route('prospects.index') }}">
                        <i class="bx bx-user-plus icon nav-icon"></i>
                        <span class="menu-item">Prospectos</span>
                    </a>
                </li>

                <li class="menu-title">Demo</li>

                <li>
                    <a href="{{ route('widget.demo') }}" target="_blank">
                        <i class="bx bx-window-open icon nav-icon"></i>
                        <span class="menu-item">ChatDESK</span>
                    </a>
                </li>

                <li class="menu-title">Cuenta</li>

                <li>
                    <a href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bx bx-log-out icon nav-icon"></i>
                        <span class="menu-item">Cerrar Sesión</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>

            </ul>
        </div>
    </div>
</div>
<!-- Left Sidebar End -->
