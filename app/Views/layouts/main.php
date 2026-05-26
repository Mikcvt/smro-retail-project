<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Secure Multi-Tenant Resource Orchestrator — Retail & Apparel Hub">
    <?php $pageTitle = trim($this->renderSection('title')) ?: 'SMRO Retail'; ?>
    <title><?= esc($pageTitle) ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/smro.css') ?>">
    <?= $this->renderSection('styles') ?>
</head>

<body class="smro-shell">
<div class="smro-sidebar offcanvas-lg offcanvas-start bg-dark text-white border-0" id="sidebarOffcanvas" tabindex="-1" aria-labelledby="sidebarOffcanvasLabel" style="width:240px;">
    <div class="offcanvas-header d-lg-none border-bottom border-white-10">
        <h5 class="offcanvas-title" id="sidebarOffcanvasLabel">SMRO Menu</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-0 d-flex flex-column">
        <?= $this->include('partials/_sidebar') ?>
    </div>
</div>

<div class="smro-main-wrap">
    <div class="smro-main">
        <?= $this->include('partials/_navbar') ?>

        <main class="smro-content py-4">
            <div class="container-fluid">
                <div class="row mb-4">
                    <div class="col-12">
                        <h1 class="h3 mb-0 text-white"><?= esc($pageTitle) ?></h1>
                    </div>
                </div>

                <?= $this->include('partials/_alerts') ?>

                <?= $this->renderSection('content') ?>
            </div>
        </main>

        <footer class="smro-footer">
            <div class="container-fluid text-center small text-muted">
                &copy; <?= date('Y') ?> SMRO — Retail & Apparel Hub
            </div>
        </footer>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const desktopToggle = document.getElementById('sidebarToggleDesktop');
        const sidebarElement = document.getElementById('sidebarOffcanvas');

        if (!desktopToggle || !sidebarElement) {
            return;
        }

        desktopToggle.addEventListener('click', function () {
            if (window.matchMedia('(max-width: 991.98px)').matches) {
                const offcanvas = bootstrap.Offcanvas.getOrCreateInstance(sidebarElement);
                offcanvas.toggle();
                return;
            }

            document.body.classList.toggle('smro-sidebar-collapsed');
        });
    });
</script>
<?= $this->renderSection('scripts') ?>
</body>
</html>