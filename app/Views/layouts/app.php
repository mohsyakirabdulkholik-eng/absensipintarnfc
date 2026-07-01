<!DOCTYPE html>
<html lang="id">
    <!-- master layout untuk website -->
<?= $this->include('partials/header') ?>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <?= $this->include('partials/navbar') ?>
        <?= $this->include('partials/sidebar') ?>
        <main class="app-main">
            <?= $this->include('components/breadcrumb') ?>
            <div class="app-content">
                <div class="container-fluid">
                    <?= $this->include('components/flash_message') ?>
                    <?= $this->renderSection('content') ?>
                </div>
            </div>
        </main>
        <?= $this->include('partials/footer') ?>
    </div>
    <?= $this->include('partials/script') ?>
</body>
</html>