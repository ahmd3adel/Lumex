@include('layouts.parts.head')
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Navbar -->
 @include('layouts.parts.navbar')
  <!-- /.navbar -->
  <!-- Main Sidebar Container -->
  @include('layouts.parts.darkside')
  <!-- Content Wrapper. Contains page content -->
  @yield('breadcramp')
    <!-- /.content-header -->
@yield('content')
    @stack('jsModal')
    @stack('style')
    @stack('cssModal')
{{--    @include('layouts.parts.transferswidgs')--}}
    @yield('transferswidgs')
    @yield('charts')
    @yield('CurrenciesAccounts')
    @yield('lastTransfers')

  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
 @include('layouts.parts.sidebar')
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
  @include('layouts.parts.footer')
</div>


@include('layouts.parts.scripts')
@stack('scripts')
</body>
</html>
