<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= $title ?? 'Smart Absensi'; ?></title>

    <link rel="icon"
          href="<?= base_url('assets/images/logo.png')?>">

    <!-- Bootstrap -->
    <link rel="stylesheet"
          href="<?= base_url('assets/adminlte/plugins/bootstrap/css/bootstrap.min.css')?>">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- AdminLTE -->
    <link rel="stylesheet"
          href="<?= base_url('assets/adminlte/dist/css/adminlte.min.css')?>">

          <link rel="stylesheet"
href="https://cdn.datatables.net/2.3.1/css/dataTables.bootstrap5.css">

          <!-- sweatalert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- url jurusan -->
    <script> const base_url="<?= base_url();?>"; </script>

    <!-- chart.js -->
      <script src="https://cdn.jsdelivr.net/npm/chart.js@4.5.0/dist/chart.umd.min.js"></script>
    <!-- Custom -->
    <!-- CSS khusus layout Superadmin -->
    <link rel="stylesheet" href="<?= base_url('assets/css/superadmin-layout.css')?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/superadmin-sekolah.css') ?>">


</head>