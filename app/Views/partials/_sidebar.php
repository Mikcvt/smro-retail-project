<?php
/**
 * app/Views/partials/_sidebar.php
 *
 * Role-aware sidebar navigation for SMRO.
 * Rendered inside both:
 *   - #desktopSidebar  (layouts/main.php — visible on lg+)
 *   - #sidebarOffcanvas (layouts/main.php — Bootstrap offcanvas on mobile)
 *
 * Role visibility matrix:
 * ┌─────────────────────────────┬────────────┬─────────┬───────┐
 * │ Nav Item                    │ superadmin │ manager │ staff │
 * ├─────────────────────────────┼────────────┼─────────┼───────┤
 * │ Dashboard                   │     ✓      │    ✓    │   ✓   │
 * │ Products                    │     ✓      │    ✓    │   ✓   │
 * │ New Sale                    │     ✓      │    ✓    │   ✓   │
 * │ Sales History               │     ✓      │    ✓    │   ✓   │
 * │ Returns                     │     ✓      │    ✓    │   ✓   │
 * │ Reports                     │     ✓      │    ✓    │   ✗   │
 * │ User Management             │     ✓      │    ✗    │   ✗   │
 * │ Tenant Settings             │     ✓      │    ✗    │   ✗   │
 * └─────────────────────────────┴────────────┴─────────┴───────┘
 *
 * Reads: session('role'), session('user_name')
 *
 * @package  SMRO
 * @author   Member 5 — Frontend UI & Dashboard
 */


// Pull role once — used throughout this file
$role = session('role') ?? 'staff';


/**
 * Helper: render a single nav link <li>.
 *
 * @param string $href    Relative URL path (passed through base_url())
 * @param string $icon    Bootstrap Icons class (e.g. 'bi-speedometer2')
 * @param string $label   Human-readable link text
 * @param string $badge   Optional badge text (e.g. alert count)
 * @return void
 */
function smroNavLink(string $href, string $icon, string $label, string $badge = ''): void
{
    $url         = base_url($href);
    $currentPath = '/' . ltrim(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH), '/');
    $linkPath    = '/' . ltrim(parse_url($url, PHP_URL_PATH), '/');


    // Active only when exact match
    $isActive    = ($currentPath === $linkPath);


    $activeClass = $isActive ? ' active' : '';
    $ariaCurrent = $isActive ? ' aria-current="page"' : '';
    ?>
    <li class="smro-nav-item">
        <a
            href="<?= esc($url) ?>"
            class="smro-nav-link d-flex align-items-center gap-2 px-4 py-2 text-decoration-none<?= $activeClass ?>"
            <?= $ariaCurrent ?>
        >
            <i class="bi <?= esc($icon) ?> smro-nav-icon flex-shrink-0" aria-hidden="true"></i>
            <span class="smro-nav-label"><?= esc($label) ?></span>
            <?php if ($badge !== '') : ?>
                <span class="badge bg-danger ms-auto smro-nav-badge" aria-label="<?= esc($badge) ?> alerts">
                    <?= esc($badge) ?>
                </span>
            <?php endif; ?>
        </a>
    </li>
    <?php
}


/**
 * Helper: render a section divider with a label.
 *
 * @param string $label  Section heading text
 * @return void
 */
function smroNavSection(string $label): void
{
    ?>
    <li class="smro-nav-section-label px-4 pt-3 pb-1" aria-hidden="true">
        <span class="text-uppercase fw-semibold" style="font-size: .65rem; letter-spacing: .08em;">
            <?= esc($label) ?>
        </span>
    </li>
    <?php
}
?>


<nav class="smro-sidebar-nav d-flex flex-column" aria-label="Sidebar navigation">

    <div class="smro-sidebar-brand d-flex align-items-center gap-3 px-4 py-3 border-bottom border-white-15 flex-shrink-0">
        <div class="smro-brand-icon">
            <i class="bi bi-shop"></i>
        </div>
        <div class="smro-brand-text">
            <div class="smro-brand-name">SMRO Retail & Apparel Hub</div>
            <div class="text-white-50 small">Secure Retail Dashboard</div>
        </div>
    </div>

    <!-- ── Scrollable navigation area ────────────────────────────────── -->
    <div class="smro-sidebar-scrollable flex-grow-1 overflow-y-auto overflow-x-hidden">
        <ul class="smro-nav-list list-unstyled mb-0 pt-2">


        <!-- ·· MAIN ·· -->
        <?php smroNavSection('Main') ?>


        <?php smroNavLink('dashboard', 'bi-speedometer2', 'Dashboard') ?>


        <!-- ·· INVENTORY ·· -->
        <?php smroNavSection('Inventory') ?>


        <?php smroNavLink('products', 'bi-box-seam', 'Products') ?>


        <?php if (in_array($role, ['superadmin', 'manager'], true)) : ?>
            <?php
            $lowStockCount = model(\App\Models\ProductModel::class)
                ->select('products.id')
                ->join('product_variants', 'product_variants.product_id = products.id', 'left')
                ->where('products.is_active', 1)
                ->groupBy('products.id')
                ->having('COALESCE(SUM(product_variants.stock_quantity), 0) <', 10)
                ->countAllResults();
            $lowStockBadge = $lowStockCount > 0 ? (string) $lowStockCount : '';
            smroNavLink('products/low-stock', 'bi-exclamation-triangle', 'Low Stock', $lowStockBadge);
            ?>
        <?php endif; ?>


        <!-- ·· SALES ·· -->
        <?php smroNavSection('Sales') ?>


        <?php smroNavLink('sales/create', 'bi-cart-plus', 'New Sale') ?>
        <?php smroNavLink('sales',        'bi-receipt',   'Sales History') ?>
        <?php smroNavLink('returns',      'bi-arrow-return-left', 'Returns') ?>


        <!-- ·· REPORTS — manager and superadmin only ·· -->
        <?php if (in_array($role, ['superadmin', 'manager'], true)) : ?>
            <?php smroNavSection('Analytics') ?>
            <?php smroNavLink('reports',          'bi-bar-chart-line', 'Reports') ?>
            <?php smroNavLink('reports/export',   'bi-download',       'Export Data') ?>
        <?php endif; ?>


        <!-- ·· ADMINISTRATION — superadmin only ·· -->
        <?php if ($role === 'superadmin') : ?>
            <?php smroNavSection('Administration') ?>
            <?php smroNavLink('users',            'bi-people',         'User Management') ?>
            <?php smroNavLink('users/roles',      'bi-shield-check',   'Roles & Permissions') ?>
            <?php smroNavLink('settings',         'bi-gear',           'Tenant Settings') ?>
            <?php smroNavLink('settings/audit',   'bi-journal-text',   'Audit Log') ?>
        <?php endif; ?>


        <!-- ── Bottom utility links removed from sidebar (available in user dropdown) -->
        <li class="smro-nav-divider border-top border-secondary border-opacity-25 my-2"></li>

        <?php smroNavLink('support',  'bi-question-circle', 'Help & Support') ?>
        </ul>
    </div>


</nav>
