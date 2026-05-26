<?php
/**
 * Partial: Empty State
 * Reusable component when a table or list has no data.
 *
 * Accepted variables (all optional with sensible defaults):
 *   $icon        string  Bootstrap Icons class, e.g. 'bi-inbox'
 *   $message     string  Primary message text
 *   $subMessage  string  Secondary/helper text
 *   $actionLabel string  CTA button label (omit to hide button)
 *   $actionUrl   string  CTA button href
 *   $actionClass string  Extra Bootstrap btn classes, e.g. 'btn-primary'
 *
 * Usage:
 *   <?= view('partials/_empty_state', [
 *       'icon'        => 'bi-cart-x',
 *       'message'     => 'No sales found.',
 *       'subMessage'  => 'Try adjusting your filters.',
 *       'actionLabel' => 'Add Product',
 *       'actionUrl'   => base_url('/products/create'),
 *       'actionClass' => 'btn-primary',
 *   ]) ?>
 *
 * @var \CodeIgniter\View\View $this
 */

$icon        = $icon        ?? 'bi-inbox';
$message     = $message     ?? 'Nothing here yet.';
$subMessage  = $subMessage  ?? null;
$actionLabel = $actionLabel ?? null;
$actionUrl   = $actionUrl   ?? '#';
$actionClass = $actionClass ?? 'btn-outline-secondary';
?>

<div class="smro-empty-state d-flex flex-column align-items-center justify-content-center text-center py-5 px-3"
     role="status"
     aria-label="<?= esc($message) ?>">

    <!-- Icon -->
    <div class="smro-empty-icon mb-3" aria-hidden="true">
        <i class="bi <?= esc($icon) ?>"></i>
    </div>

    <!-- Primary message -->
    <p class="smro-empty-message fw-semibold text-dark mb-1">
        <?= esc($message) ?>
    </p>

    <!-- Secondary message -->
    <?php if ($subMessage !== null) : ?>
        <p class="smro-empty-sub text-muted small mb-3">
            <?= esc($subMessage) ?>
        </p>
    <?php endif; ?>

    <!-- Optional action button -->
    <?php if ($actionLabel !== null) : ?>
        <a href="<?= esc($actionUrl) ?>"
           class="btn btn-sm <?= esc($actionClass) ?> mt-2 smro-empty-action">
            <?= esc($actionLabel) ?>
        </a>
    <?php endif; ?>

</div>