<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>AdminLTE 3 | Dashboard</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    {{-- <a href="/admin" class="brand-link">
      <img src="{{ asset('img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">AdminLTE 3</span>
    </a> --}}

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="info">
          <a class="d-block">{{ Auth::user()->name }}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a href="/admin/games" class="nav-link">
              <i class="nav-icon fas fa-trophy"></i>
              <p>Игры</p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    @yield('content')
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <strong>Разработка: <a href="https://daniel-savchenko.com" target="_blank">Данил Савченко</a></strong>
    <div class="float-right d-none d-sm-inline-block">
      <b><a href="http://adminlte.io">AdminLTE.io</a></b> Version 3.0.0-rc.1
    </div>
  </footer>
</div>
<!-- ./wrapper -->

<!-- Scripts -->
<script src="{{ asset('js/admin.js') }}"></script>
<script>
  $.widget.bridge('uibutton', $.ui.button)
  $('[data-datetimepicker]')
    .daterangepicker({
      singleDatePicker: true,
      timePicker: true,
      autoUpdateInput: false,
      timePicker24Hour: true
    })
    .on('apply.daterangepicker', function(e, picker) {
      $(this).val(picker.startDate.format('DD.MM.YYYY HH:mm'))
    })
  $('[data-toggle="tooltip"]').tooltip();
</script>
@yield('scripts')
</body>
</html>
