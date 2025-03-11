<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button" id="navlink"><i class="fas fa-bars"></i></a>
        </li>
{{--        <li class="nav-item d-none d-sm-inline-block">--}}
{{--            <a href="index3.html" class="nav-link">{{trans('app.Home')}}</a>--}}
{{--        </li>--}}
{{--        <li class="nav-item d-none d-sm-inline-block">--}}
{{--            <a href="#" class="nav-link" >Contact</a>--}}
{{--        </li>--}}
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Navbar Search -->
{{--        <li class="nav-item">--}}
{{--            <a class="nav-link" data-widget="navbar-search" href="#" role="button">--}}
{{--                <i class="fas fa-search"></i>--}}
{{--            </a>--}}
{{--            <div class="navbar-search-block">--}}
{{--                <form class="form-inline">--}}
{{--                    <div class="input-group input-group-sm">--}}
{{--                        <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">--}}
{{--                        <div class="input-group-append">--}}
{{--                            <button class="btn btn-navbar" type="submit">--}}
{{--                                <i class="fas fa-search"></i>--}}
{{--                            </button>--}}
{{--                            <button class="btn btn-navbar" type="button" data-widget="navbar-search">--}}
{{--                                <i class="fas fa-times"></i>--}}
{{--                            </button>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </form>--}}
{{--            </div>--}}
{{--        </li>--}}

        <!-- Profile Dropdown Menu -->


        <!-- Notifications Dropdown Menu -->
{{--        <li class="nav-item dropdown">--}}
{{--            <a class="nav-link" data-toggle="dropdown" href="#">--}}
{{--                <i class="far fa-bell"></i>--}}
{{--                <span class="badge badge-warning navbar-badge">15</span>--}}
{{--            </a>--}}
{{--            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">--}}
{{--                <span class="dropdown-header">15 Notifications</span>--}}
{{--                <div class="dropdown-divider"></div>--}}
{{--                <a href="#" class="dropdown-item">--}}
{{--                    <i class="fas fa-envelope mr-2"></i> 4 new messages--}}
{{--                    <span class="float-right text-muted text-sm">3 mins</span>--}}
{{--                </a>--}}
{{--                <div class="dropdown-divider"></div>--}}
{{--                <a href="#" class="dropdown-item">--}}
{{--                    <i class="fas fa-users mr-2"></i> 8 friend requests--}}
{{--                    <span class="float-right text-muted text-sm">12 hours</span>--}}
{{--                </a>--}}
{{--                <div class="dropdown-divider"></div>--}}
{{--                <a href="#" class="dropdown-item">--}}
{{--                    <i class="fas fa-file mr-2"></i> 3 new reports--}}
{{--                    <span class="float-right text-muted text-sm">2 days</span>--}}
{{--                </a>--}}
{{--                <div class="dropdown-divider"></div>--}}
{{--                <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>--}}
{{--            </div>--}}
{{--        </li>--}}

{{--        <!-- Fullscreen Button -->--}}
{{--        <li class="nav-item">--}}
{{--            <a class="nav-link" data-widget="fullscreen" href="#" role="button">--}}
{{--                <i class="fas fa-expand-arrows-alt"></i>--}}
{{--            </a>--}}
{{--        </li>--}}
{{--        <!-- Control Sidebar -->--}}
{{--        <li class="nav-item">--}}
{{--            <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">--}}
{{--                <i class="fas fa-th-large"></i>--}}
{{--            </a>--}}
{{--        </li>--}}
{{--        <li class="nav-item dropdown">--}}
            <a class="nav-link" data-toggle="dropdown" href="#" role="button">
                <img src="{{ asset('dist/img/user2-160x160.jpg') }}" alt="User Avatar" class="img-size-32 img-circle mr-2">

            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <a href="#" class="dropdown-item">
                    <i class="fas fa-user-circle mr-2"></i> Profile
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-cog mr-2"></i> Settings
                </a>
                <div class="dropdown-divider"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </button>
                </form>
            </div>
        </li>



        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#" role="button">
                <i class="fas fa-globe mr-2"></i> {{ LaravelLocalization::getCurrentLocaleNative() }}
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                    <a href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}"
                       class="dropdown-item {{ LaravelLocalization::getCurrentLocale() === $localeCode ? 'active' : '' }}">
                        <i class="flag-icon {{ $localeCode === 'en' ? 'flag-icon-us' : 'flag-icon-sa' }} mr-2"></i>
                        {{ $properties['native'] }}
                    </a>
                @endforeach
            </div>
        </li>














        {{--        <a href="{{ url('/change-language/en') }}">English</a>--}}
{{--        <a href="{{ url('/change-language/ar') }}">العربية</a>--}}





    </ul>
</nav>
