<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a href="#" class="nav-link" data-widget="pushmenu" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>
    </ul>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item-dropdown">
            <a href="/" data-toggle="dropdown" aria-expanded="false" class="nav-link">
                {{ Auth::user()->name }}
                <i class="fas fa-caret-down"></i>
            </a>
            {{-- <div class="dropdown-menu mr-5 dropdown-menu-right">
                
            </div> --}}
            <div class="dropdown-menu mr-5 dropdown-menu-right">
                <a class="nav-link" href="{{ route('profile.show') }}" class="doropdown-item">
                    <i class="fas fa-user mr-2"></i> Profile
                </a>
                <a class="nav-link" href="javascript:;" class="doropdown-item" onclick="document.getElementById('form-logout').submit();">
                    <i class="fas fa-sign-out-alt mr-2"></i> Sign Out
                </a>
            </div>
        </li>
    </ul>
    <ul class="navbar-nav">
        <li class="nav-item">
            <a href="#" class="nav-link" data-widget="fullscreen" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
    </ul>
</nav>
<form action="{{ route('logout') }}" method="POST" id="form-logout" style="display: none;">
    @csrf
</form>