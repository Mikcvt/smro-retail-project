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
<div class="smro-alerts-wrapper" id="smroAlertsWrapper" role="region" aria-label="Notifications">
    <?php foreach ($flashTypes as $key => $config) : ?>
        <?php $message = session()->getFlashdata($key); ?>
        <?php if ($message) : ?>
            <div
                class="alert <?= $config['class'] ?> alert-dismissible fade show d-flex align-items-start gap-2 shadow-sm mb-2"
                role="alert"
                data-smro-alert
            >
                <i class="bi <?= $config['icon'] ?> flex-shrink-0 mt-1" aria-hidden="true"></i>
                <div class="flex-grow-1">
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
                    class="btn-close flex-shrink-0"
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
        const AUTO_DISMISS_MS = 4000;

        function dismissAlerts() {
            const alerts = document.querySelectorAll('[data-smro-alert]');
            alerts.forEach(function (el) {
                setTimeout(function () {
                    // Use Bootstrap's Alert API if available, else just remove
                    if (window.bootstrap && window.bootstrap.Alert) {
                        const bsAlert = window.bootstrap.Alert.getOrCreateInstance(el);
                        bsAlert.close();
                    } else {
                        el.classList.remove('show');
                        el.addEventListener('transitionend', function () {
                            el.remove();
                        }, { once: true });
                    }
                }, AUTO_DISMISS_MS);
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