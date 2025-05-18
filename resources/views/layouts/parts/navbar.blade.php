<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="index3.html" class="nav-link">Home</a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="#" class="nav-link">Contact</a>
        </li>
    </ul>

    <!-- SEARCH FORM -->
    <form class="form-inline ms-3 me-auto">
        <div class="input-group input-group-sm">
            <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </form>

    <!-- Right navbar links -->
    <ul class="navbar-nav ms-auto align-items-center">

        <!-- Language Switcher -->
        <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#" role="button">
    <span class="fi fi-{{ app()->getLocale() === 'en' ? 'us' : (app()->getLocale() === 'ar' ? 'sa' : app()->getLocale()) }} me-2"></span>
    {{ LaravelLocalization::getCurrentLocaleNative() }}
</a>

    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
        @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
            <a href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}"
               class="dropdown-item {{ LaravelLocalization::getCurrentLocale() === $localeCode ? 'active' : '' }}">
                <span class="fi fi-{{ $localeCode === 'en' ? 'us' : ($localeCode === 'ar' ? 'sa' : $localeCode) }} me-2"></span>
                {{ $properties['native'] }}
            </a>
        @endforeach
    </div>
</li>


        <!-- User Dropdown -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <img src="{{ asset('dist/img/user2-160x160.jpg') }}" alt="User Avatar"
                     class="img-size-32 img-circle me-2">
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                <a href="#" class="dropdown-item">
                    <i class="fas fa-user-circle me-2"></i> Profile
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-cog me-2"></i> Settings
                </a>
                <div class="dropdown-divider"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item">
                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                    </button>
                </form>
            </div>
        </li>

        <!-- Messages Dropdown -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-comments"></i>
                <span class="badge badge-danger navbar-badge">3</span>
            </a>
        </li>

        <!-- Control Sidebar -->
        <li class="nav-item">
            <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#">
                <i class="fas fa-th-large"></i>
            </a>
        </li>
    </ul>
</nav>
