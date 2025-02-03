<aside class="main-sidebar sidebar-dark-primary elevation-4" id="mainSlider">
    <!-- Brand Logo -->
    <a href="/accounts/" class="brand-link">
        <img src="{{ asset('dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Admin Dashboard</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center">
            <div class="image">
                <img src="{{ asset('dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="/accounts/" class="d-block text-white font-weight-bold text-capitalize">
                    {{ \Illuminate\Support\Facades\Auth::user()->name }}
                </a>
                <small class="text-muted">
                    @if (Auth::user()->store && Auth::user()->store->name)
                        {{ Auth::user()->store->name }}  <br>
                        {{ Auth::user()->roles['0']->name }}
                    @else
                        {{ Auth::user()->email }}
                    @endif
                </small>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="/accounts/" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-header">MANAGEMENT</li>
                @if (!Auth::user()->hasRole('agent'))
                    <li class="nav-item">
                        <a href="{{route('users.index')}}" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Users</p>
                        </a>
                    </li>
                @endif


                <li class="nav-item">
                    <a href="{{route('clients.index')}}" class="nav-link">
                        <i class="nav-icon fas fa-user-friends"></i>
                        <p>{{trans('Clients')}}</p>
                    </a>
                </li>
                @if(!Auth::user()->hasRole('agent'))
                    <li class="nav-item">
                        <a href="{{route('stores.index')}}" class="nav-link">
                            <i class="nav-icon fas fa-store"></i>
                            <p>Stores</p>
                        </a>
                    </li>
                @endif

                <li class="nav-item">
                    <a href="{{route('products.index')}}" class="nav-link">
                        <i class="nav-icon fas fa-box"></i>
                        <p>Products</p>
                    </a>
                </li>
                <li class="nav-header">FINANCIAL</li>
                <li class="nav-item">
                    <a href="{{route('invoices.index')}}" class="nav-link">
                        <i class="nav-icon fas fa-file-invoice-dollar"></i>
                        <p>Invoices</p>
                    </a>
                </li>
                <li class="nav-item ml-3">
                    <a href="{{route('returns.index')}}" class="nav-link">
                        <i class="nav-icon fas fa-undo"></i>
                        <p>Returns</p>
                    </a>
                </li>
                <li class="nav-item ml-3">
                    <a href="{{route('receipts.index')}}" class="nav-link">
                        <i class="nav-icon fas fa-undo"></i>
                        <p>Returns</p>
                    </a>
                </li>
                <li class="nav-item ml-3">
                    <a href="/invoices/payments" class="nav-link">
                        <i class="nav-icon fas fa-credit-card"></i>
                        <p>Payments</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/expenses" class="nav-link">
                        <i class="nav-icon fas fa-wallet"></i>
                        <p>Expenses</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/suppliers" class="nav-link">
                        <i class="nav-icon fas fa-truck"></i>
                        <p>Suppliers</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/returns" class="nav-link">
                        <i class="nav-icon fas fa-money-check"></i>
                        <p>Transactions</p>
                    </a>
                </li>
                <li class="nav-header">SETTINGS</li>
                <li class="nav-item">
                    <a href="/settings" class="nav-link">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>Settings</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
