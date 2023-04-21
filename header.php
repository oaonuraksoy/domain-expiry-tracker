<?php
file_exists('process/db.php') ?: header('Location: installer') && exit();
// error_reporting(0);
// file_exists('installer.php') && rename('installer.php', 'installer.php.bak');
session_start();
require_once('process/function.php');
$domainTable = new DomainTable();
$login = new Login('', '');
if (!$login->isLoggedIn()) header('Location: login') && exit;
$data = $domainTable->getDomainData();
?> 

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Domain Expiry Tracker | Powered by Hackonomist</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<!-- sidebar-collapse -->
<body class="hold-transition sidebar-mini layout-fixed ">
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
      <!-- <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
          <i class="fas fa-th-large"></i>
        </a>
      </li> -->
      <li class="nav-item">
        <a class="nav-link" href="process/logout.php" role="button">
        <i class="fas fa-sign-out-alt" style="color:red;"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/" class="brand-link">
      <img src="dist/img/oAdminLogo.png" alt="oAdmin Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Domain Expiry Tracker</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <!-- Sidebar Menu -->
      <nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
      
    <li class="nav-header">Menü</li><li class="nav-item">
        <a href="/" class="nav-link">
          <i class="nav-icon fas fa-th-list"></i>
          <p>
            Alan Adları
            <span class="badge badge-info right"><?php echo $domainTable->countDomains(); ?></span>
          </p>
        </a>
     
      </li>
      <li class="nav-item">
        <a href="/hakkimizda" class="nav-link">
          <i class="nav-icon fas fa-question"></i>
          <p>
            Hakkımızda
            <span class="badge badge-info right"></span>
          </p>
        </a>
      </li>
    </ul>
  </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>