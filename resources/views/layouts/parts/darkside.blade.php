<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
        <img src="{{ asset('dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
             style="opacity: .8">
        <span class="brand-text font-weight-light">النور للحلول البرمجية</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel d-flex align-items-center mt-3 pb-3 mb-3">
            <div class="image me-2">
                <img src="{{ asset('dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image" style="width: 50px; height: 50px;">
            </div>
            <div>
                <a href="/accounts/" class="d-block text-white font-weight-bold text-capitalize">
                    {{ \Illuminate\Support\Facades\Auth::user()->name }}
                </a>
                <small class="text-muted d-block">
                    @if (Auth::user()->store && Auth::user()->store->name)
                        <span class="fw-bold text-info">{{ Auth::user()->store->name }}</span> <br>
                        <span class="text-secondary">{{ Auth::user()->roles['0']->name }}</span>
                    @else
                        <span class="text-secondary">{{ Auth::user()->email }}</span>
                    @endif
                </small>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                @if (!Auth::user()->hasRole('agent'))
                    <li class="nav-item">
                        <a href="{{ route('users.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-users me-2"></i>
                            <p>Users</p>
                        </a>
                    </li>
                @endif

                <li class="nav-item">
                    <a href="{{ route('clients.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-user-friends me-2"></i>
                        <p>{{ trans('Clients') }}</p>
                    </a>
                </li>

                @if (!Auth::user()->hasRole('agent'))
                    <li class="nav-item">
                        <a href="{{ route('stores.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-store me-2"></i>
                            <p>Stores</p>
                        </a>
                    </li>
                @endif

                <li class="nav-item">
                    <a href="{{ route('products.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-box me-2"></i>
                        <p>{{ trans('Products') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('invoices.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-file-invoice-dollar me-2"></i>
                        <p>{{ trans('Invoices') }}</p>
                    </a>
                </li>

                <li class="nav-item ms-3">
                    <a href="{{ route('invoices.create') }}" class="nav-link">
                        <i class="nav-icon fas fa-plus-circle me-2"></i>
                        <p>{{ trans('Add Invoice') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('returns.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-undo me-2"></i>
                        <p>{{ trans('Returns') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('receipts.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-receipt me-2"></i>
                        <p>{{ trans('Receipts') }}</p>
                    </a>
                </li>

                                <li class="nav-item">
                    <a href="{{ route('receipts.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-receipt me-2"></i>
                        <p>{{ trans('Additions') }}</p>
                    </a>
                </li>

                     <li class="nav-item">
                    <a href="{{ route('deductions.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-receipt me-2"></i>
                        <p>{{ trans('Deductions') }}</p>
                    </a>
                </li>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
