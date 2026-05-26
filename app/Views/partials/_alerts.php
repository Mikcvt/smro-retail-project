<?php
/**
 * Partial: Flash Message Alerts
 * Reads CI4 flash data and renders Bootstrap 5 dismissible alerts.
 * Auto-dismisses after 4 seconds via vanilla JS.
 *
 * Supported keys: success | error | warning | info
 *
 * @var \CodeIgniter\View\View $this
 */

$flashTypes = [
    'success' => [
        'class' => 'alert-success',
        'icon'  => 'bi-check-circle-fill',
    ],
    'error' => [
        'class' => 'alert-danger',
        'icon'  => 'bi-x-octagon-fill',
    ],
    'warning' => [
        'class' => 'alert-warning',
        'icon'  => 'bi-exclamation-triangle-fill',
    ],
    'info' => [
        'class' => 'alert-info',
        'icon'  => 'bi-info-circle-fill',
    ],
];

$hasAlert = false;

foreach ($flashTypes as $key => $config) {
    if (session()->getFlashdata($key)) {
        $hasAlert = true;
        break;
    }
}
?>

<?php if ($hasAlert) : ?>
<div class="smro-alerts-wrapper" id="smroAlertsWrapper" role="region" aria-live="polite" aria-atomic="true">
    <?php foreach ($flashTypes as $key => $config) : ?>
        <?php $message = session()->getFlashdata($key); ?>
        <?php if ($message) : ?>
            <div
                class="smro-alert-toast smro-alert-<?= $key ?> shadow-sm"
                role="alert"
                data-smro-alert
            >
                <div class="smro-alert-icon">
                    <i class="bi <?= $config['icon'] ?>" aria-hidden="true"></i>
                </div>
                <div class="smro-alert-body">
                    <?php if (is_array($message)) : ?>
                        <ul class="mb-0 ps-3">
                            <?php foreach ($message as $line) : ?>
                                <li><?= esc($line) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else : ?>
                        <?= esc($message) ?>
                    <?php endif; ?>
                </div>
                <button
                    type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="alert"
                    aria-label="Close alert"
                ></button>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>

<script>
    (function () {
        'use strict';
        const AUTO_DISMISS_MS = 4200;

        function dismissAlerts() {
            const alerts = document.querySelectorAll('[data-smro-alert]');
            alerts.forEach(function (el, index) {
                setTimeout(function () {
                    if (window.bootstrap && window.bootstrap.Alert) {
                        const bsAlert = window.bootstrap.Alert.getOrCreateInstance(el);
                        bsAlert.close();
                    } else {
                        el.classList.add('smro-alert-hidden');
                        el.addEventListener('transitionend', function () {
                            el.remove();
                        }, { once: true });
                    }
                }, AUTO_DISMISS_MS + index * 200);
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', dismissAlerts);
        } else {
            dismissAlerts();
        }
    }());
</script>
<?php endif; ?>