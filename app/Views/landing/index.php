<!DOCTYPE html>
<html lang="id">

<head>

    <?= $this->include('partials/header') ?>
    <link rel="stylesheet" href="<?= base_url('assets/css/landing.css')?>">

<body>

<?= $this->include('landing/partials/navbar') ?>

<?= $this->include('landing/partials/hero') ?>

<?= $this->include('landing/partials/feature') ?>

<?= $this->include('landing/partials/workflow') ?>

<?= $this->include('landing/partials/dashboard') ?>

<?= $this->include('landing/partials/statistic') ?>

<?= $this->include('landing/partials/advantage') ?>

<?= $this->include('landing/partials/faq') ?>

<?= $this->include('landing/partials/cta') ?>

<?= $this->include('landing/partials/footer') ?>

<?= $this->include('partials/script') ?>

<script src="<?= base_url('assets/js/landing.js') ?>"></script>

</body>

</html>