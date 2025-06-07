<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="" class="brand-link">
        <img src="{{ asset('dist/img/AdminLTELogo.png') }}" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">النور للحلول البرمجية</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- User Panel -->
        <div class="user-panel d-flex align-items-center mt-3 pb-3 mb-3">
            <div class="image me-2">
                <img src="{{ asset('dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image" style="width: 50px; height: 50px;">
            </div>
            <div>
                <a href="/accounts/" class="d-block text-white font-weight-bold text-capitalize">
                    {{ Auth::user()->name }}
                </a>
                <small class="text-muted d-block">
                    @if (Auth::user()->store && Auth::user()->store->name)
                        <span class="fw-bold text-info">{{ Auth::user()->store->name }}</span><br>
                        <span class="text-secondary">{{ Auth::user()->roles[0]->name }}</span>
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
                        <p>{{trans('Users')}}</p>
                    </a>
                </li>
                @endif

                                <!-- المخازن -->
                @if (!Auth::user()->hasRole('agent'))
                <li class="nav-item">
                    <a href="{{ route('stores.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-store me-2"></i>
                        <p>الشركات</p>
                    </a>
                </li>
                @endif

                <!-- المنتجات -->
                <li class="nav-item">
                    <a href="{{ route('products.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-box me-2"></i>
                        <p>منتجات العملاء</p>
                    </a>
                </li>

                                <li class="nav-item">
                    <a href="{{ route('supplier_products.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-box me-2"></i>
                        <p>منتجات الموردين</p>
                    </a>
                </li>

                <!-- العملاء -->
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-user-friends me-2"></i>
                        <p>
                            العملاء
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item"><a href="{{ route('clients.index') }}" class="nav-link"><i class="far fa-circle nav-icon"></i><p>قائمة العملاء</p></a></li>
                        <li class="nav-item"><a href="{{ route('invoices.index') }}" class="nav-link"><i class="far fa-circle nav-icon"></i><p>فواتير العملاء</p></a></li>
                        <li class="nav-item"><a href="{{ route('invoices.create') }}" class="nav-link"><i class="far fa-circle nav-icon"></i><p>إضافة فاتورة</p></a></li>
                        <li class="nav-item"><a href="{{ route('returns.index') }}" class="nav-link"><i class="far fa-circle nav-icon"></i><p>مرتجعات العملاء</p></a></li>
                        <li class="nav-item"><a href="{{ route('receipts.index') }}" class="nav-link"><i class="far fa-circle nav-icon"></i><p>مدفوعات العملاء</p></a></li>
                        <li class="nav-item"><a href="#" class="nav-link"><i class="far fa-circle nav-icon"></i><p>إضافات العملاء</p></a></li>
                        <li class="nav-item"><a href="{{ route('deductions.index') }}" class="nav-link"><i class="far fa-circle nav-icon"></i><p>خصومات العملاء</p></a></li>
                    </ul>
                </li>

                <!-- الموردين -->
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link" >
                        <i class="nav-icon fas fa-truck me-2"></i>
                        <p>
                            الموردين
                            <i class="right fas fa-angle-left"></i>
                           
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item"><a href="{{ route('suppliers.index') }}" class="nav-link"><i class="far fa-circle nav-icon"></i><p>قائمة الموردين</p></a></li>
                        <li class="nav-item"><a href="{{ route('supplier_invoices.index') }}" class="nav-link"><i class="far fa-circle nav-icon"></i><p>فواتير الموردين</p></a></li>
                        <li class="nav-item"><a href="#" class="nav-link"><i class="far fa-circle nav-icon"></i><p>مرتجعات الموردين</p></a></li>
                        <li class="nav-item"><a href="#" class="nav-link"><i class="far fa-circle nav-icon"></i><p>مدفوعات الموردين</p></a></li>
                        <li class="nav-item"><a href="#" class="nav-link"><i class="far fa-circle nav-icon"></i><p>إضافات الموردين</p></a></li>
                        <li class="nav-item"><a href="#" class="nav-link"><i class="far fa-circle nav-icon"></i><p>خصومات الموردين</p></a></li>
                    </ul>
                </li>

                <!-- الإدارة المالية -->
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-money-check-alt me-2"></i>
                        <p>
                            الإدارة المالية
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item"><a href="#" class="nav-link"><i class="far fa-circle nav-icon"></i><p>دفتر اليومية</p></a></li>
                        <li class="nav-item"><a href="#" class="nav-link"><i class="far fa-circle nav-icon"></i><p>دفتر الشيكات</p></a></li>
                    </ul>
                </li>



            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
