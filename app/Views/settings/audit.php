<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Audit Log<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card bg-dark border-secondary">
    <div class="card-header border-secondary d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="bi bi-journal-text me-2"></i>System Activity Log</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle mb-0 small">
                <thead class="border-bottom border-secondary">
                    <tr>
                        <th class="px-4 py-3">Event</th>
                        <th>User</th>
                        <th>Module</th>
                        <th>Date & Time</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="px-4 py-3 text-muted" colspan="4">
                            <div class="text-center py-4">
                                <i class="bi bi-journal-text fs-2 d-block mb-2 text-secondary"></i>
                                Audit logging will be available in a future update.
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
