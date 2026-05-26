<?php
/**
 * Partial: Top Navbar	
 * Displays project name, authenticated user info, role badge, and logout button.
 *
 * @var \CodeIgniter\View\View $this
 */
$userName  = session('name')  ?? 'Unknown User';
$userRole  = session('role')  ?? 'guest';
$userEmail = session('email') ?? '';

$roleBadgeClass = match ($userRole) {
    'superadmin' => 'bg-danger',
    'manager'    => 'bg-warning text-dark',
    'staff'      => 'bg-primary',
    default      => 'bg-secondary',
};

$roleLabel = match ($userRole) {
    'superadmin' => 'Super Admin',
    'manager'    => 'Manager',
    'staff'      => 'Staff',
    default      => ucfirst($userRole),
};

// Generate initials avatar from name
$nameParts = explode(' ', trim($userName));
$initials  = strtoupper(
    (isset($nameParts[0]) ? $nameParts[0][0] : '') .
    (isset($nameParts[1]) ? $nameParts[1][0] : '')
) ?: 'U';
?>

<nav class="navbar navbar-expand-lg smro-navbar px-3 py-2" id="topNavbar">

    <!-- Left: Sidebar toggle + Brand -->
    <div class="d-flex align-items-center gap-2">
        <!-- Toggle for desktop sidebar collapse and mobile menu open -->
        <button
            class="btn btn-sm btn-outline-light"
            id="sidebarToggleDesktop"
            type="button"
            title="Toggle Sidebar"
            aria-label="Toggle sidebar"
        >
            <i class="bi bi-layout-sidebar"></i>
        </button>

        <button
            class="btn btn-sm btn-outline-light d-lg-none"
            type="button"
            data-bs-toggle="offcanvas"
            data-bs-target="#sidebarOffcanvas"
            aria-controls="sidebarOffcanvas"
            aria-label="Open sidebar"
        >
            <i class="bi bi-list fs-5"></i>
        </button>

        <a class="navbar-brand fw-bold text-white mb-0 ms-1" href="<?= base_url('/dashboard') ?>">
            <i class="bi bi-boxes me-1"></i>SMRO
        </a>
    </div>

    <!-- Right: User info dropdown -->
    <div class="d-flex align-items-center ms-auto gap-2">

        <!-- Global search removed (not implemented) -->

        <!-- User dropdown -->
        <div class="dropdown">
            <button
                class="btn btn-sm d-flex align-items-center gap-2 smro-user-btn dropdown-toggle"
                type="button"
                id="userDropdown"
                data-bs-toggle="dropdown"
                aria-expanded="false"
                aria-haspopup="true"
            >
                <!-- Avatar circle with initials -->
                <span class="smro-avatar" aria-hidden="true">
                    <?= esc($initials) ?>
                </span>

                <!-- Name + Role (hidden on very small screens) -->
                <span class="d-none d-sm-flex flex-column align-items-start lh-1">
                    <span class="smro-nav-username fw-semibold">
                        <?= esc($userName) ?>
                    </span>
                    <span class="badge <?= $roleBadgeClass ?> smro-role-badge mt-1">
                        <?= esc($roleLabel) ?>
                    </span>
                </span>
            </button>

            <ul
                class="dropdown-menu dropdown-menu-end shadow-sm smro-user-dropdown"
                aria-labelledby="userDropdown"
            >
                <!-- User info header -->
                <li class="px-3 py-2 border-bottom">
                    <div class="fw-semibold text-dark"><?= esc($userName) ?></div>
                    <div class="small text-muted"><?= esc($userEmail) ?></div>
                    <span class="badge <?= $roleBadgeClass ?> mt-1">
                        <?= esc($roleLabel) ?>
                    </span>
                </li>

                <!-- Profile link -->
                <li>
                    <a class="dropdown-item d-flex align-items-center gap-2 py-2"
                       href="<?= base_url('/profile') ?>">
                        <i class="bi bi-person-circle text-muted"></i>
                        My Profile
                    </a>
                </li>

                <!-- Settings (superadmin only) -->
                <?php if ($userRole === 'superadmin') : ?>
                    <li>
                        <a class="dropdown-item d-flex align-items-center gap-2 py-2"
                           href="<?= base_url('/settings') ?>">
                            <i class="bi bi-gear text-muted"></i>
                            System Settings
                        </a>
                    </li>
                <?php endif; ?>

                <li><hr class="dropdown-divider my-1"></li>

                <!-- Logout -->
                <li>
                    <a class="dropdown-item d-flex align-items-center gap-2 py-2 text-danger"
                       href="<?= base_url('/logout') ?>"
                       onclick="return confirm('Are you sure you want to log out?')">
                        <i class="bi bi-box-arrow-right"></i>
                        Log Out
                    </a>
                </li>
            </ul>
        </div>
        <!-- /User dropdown -->

    </div>
</nav>