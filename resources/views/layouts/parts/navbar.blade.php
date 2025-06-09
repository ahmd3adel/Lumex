<nav class="main-header navbar navbar-expand navbar-white navbar-light d-flex justify-content-between">
    <!-- Left Side: Burger Menu -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- Right Side (actually left in RTL): Language + User -->
    <ul class="navbar-nav align-items-center">
        <!-- User Dropdown -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <img src="{{ asset('dist/img/user2-160x160.jpg') }}" alt="User Avatar"
                     class="img-size-32 img-circle me-2">
            </a>
            <div class="dropdown-menu dropdown-menu-lg">
                <a href="#" class="dropdown-item"><i class="fas fa-user-circle me-2"></i> Profile</a>
                <a href="#" class="dropdown-item"><i class="fas fa-cog me-2"></i> Settings</a>
                <div class="dropdown-divider"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item">
                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                    </button>
                </form>
            </div>
        </li>

        <!-- Language Switcher -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#" role="button">
                <span class="fi fi-{{ app()->getLocale() === 'en' ? 'us' : (app()->getLocale() === 'ar' ? 'sa' : app()->getLocale()) }} me-2"></span>
                {{ LaravelLocalization::getCurrentLocaleNative() }}
            </a>
            <div class="dropdown-menu dropdown-menu-lg">
                @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                    <a href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}"
                       class="dropdown-item {{ LaravelLocalization::getCurrentLocale() === $localeCode ? 'active' : '' }}">
                        <span class="fi fi-{{ $localeCode === 'en' ? 'us' : ($localeCode === 'ar' ? 'sa' : $localeCode) }} me-2"></span>
                        {{ $properties['native'] }}
                    </a>
                @endforeach
            </div>
        </li>
    </ul>
</nav>

<style>
    /* تأكد من أن القوائم تفتح باتجاه الداخل */
    .dropdown-menu {
        right: auto !important;
        left: 0 !important;
    }
    
    /* للوضع RTL (العربية) */
    [dir="rtl"] .dropdown-menu {
        left: auto !important;
        right: 0 !important;
    }
</style>