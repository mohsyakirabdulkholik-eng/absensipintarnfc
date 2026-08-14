<!DOCTYPE html>
<html lang="id">

<!-- Master layout khusus Superadmin -->

<?= $this->include('partials/superadmin_header') ?>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary superadmin-layout">

    <div class="app-wrapper">

        <?= $this->include('partials/navbar') ?>

        <?= $this->include('partials/sidebar_superadmin') ?>

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