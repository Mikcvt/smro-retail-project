<?php
/**
 * app/Views/layouts/auth.php
 *
 * Minimal authentication layout for the Secure Multi-Tenant Resource Orchestrator (SMRO)
 * Used by: login, register, forgot-password, reset-password pages.
 *
 * No sidebar, no navbar — just a centered card on a branded background.
 *
 * Sections expected from child views:
 *   - title      : page <title> suffix (e.g. "Login")
 *   - card_title : heading shown inside the card (e.g. "Welcome back")
 *   - card_intro : optional short sub-heading / description line
 *   - content    : the form / body of the auth card
 *   - card_footer: optional link row below the card (e.g. "Don't have an account?")
 *   - styles     : extra <link> or <style> tags
 *   - scripts    : extra <script> tags
 *
 * @package  SMRO
 * @author   Member 5 — Frontend UI & Dashboard
 */
?>
<!doctype html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SMRO — Retail & Apparel Hub authentication">


    <title>
        <?= $this->renderSection('title') ?> | SMRO
    </title>


    <!-- ── Bootstrap 5.3 CSS ─────────────────────────────────────────── -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous"
    >


    <!-- ── Bootstrap Icons ───────────────────────────────────────────── -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >


    <!-- ── Google Fonts: DM Sans + Fira Code ─────────────────────────── -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300..700;1,9..40,300..700&family=Fira+Code:wght@400;500&display=swap"
        rel="stylesheet"
    >


    <!-- ── SMRO Custom CSS ────────────────────────────────────────────── -->
    <link rel="stylesheet" href="<?= base_url('css/smro.css') ?>">


    <?= $this->renderSection('styles') ?>
</head>


<body class="smro-auth-body d-flex flex-column min-vh-100">


    <!-- ── Decorative background blobs (CSS-driven, aria-hidden) ──────── -->
    <div class="smro-auth-bg" aria-hidden="true">
        <span class="smro-auth-blob smro-auth-blob--1"></span>
        <span class="smro-auth-blob smro-auth-blob--2"></span>
        <span class="smro-auth-blob smro-auth-blob--3"></span>
    </div>


    <!-- ── Flash alerts (redirect-back errors, e.g. invalid token) ────── -->
    <?php if (session()->getFlashdata('error') || session()->getFlashdata('success') || session()->getFlashdata('warning')) : ?>
        <div
            class="position-fixed top-0 start-50 translate-middle-x pt-3"
            style="z-index: 1090; min-width: 320px; max-width: 480px; width: 90%;"
            role="region"
            aria-live="polite"
            aria-label="Notifications"
        >
            <?= $this->include('partials/_alerts') ?>
        </div>
    <?php endif; ?>


    <!-- ── Centred auth card ─────────────────────────────────────────── -->
    <main class="smro-auth-main flex-grow-1 d-flex align-items-center justify-content-center px-3 py-5">


        <div class="smro-auth-card-wrapper w-100" style="max-width: 440px;">


            <!-- Brand mark above card -->
            <div class="text-center mb-4">
                <a href="<?= base_url('/') ?>" class="smro-auth-brand-link text-decoration-none" aria-label="SMRO home">
                    <span class="smro-auth-brand-icon d-inline-flex align-items-center justify-content-center rounded-3 mb-3">
                        <i class="bi bi-boxes" aria-hidden="true"></i>
                    </span>
                    <br>
                    <span class="smro-auth-brand-name fw-bold d-block">SMRO</span>
                    <span class="smro-auth-brand-sub text-muted small d-block">Retail & Apparel Hub</span>
                </a>
            </div>


            <!-- Card -->
            <div class="smro-auth-card card border-0 shadow-lg">


                <!-- Card header: title + optional intro -->
                <div class="smro-auth-card-header card-header border-0 pt-4 pb-0 bg-transparent text-center">
                    <h1 class="smro-auth-card-title h4 fw-semibold mb-1">
                        <?= $this->renderSection('card_title') ?>
                    </h1>


                    <?php
                    // Render card_intro only when the child view provides it
                    $cardIntro = $this->renderSection('card_intro');
                    if (trim($cardIntro) !== '') :
                    ?>
                        <p class="smro-auth-card-intro text-muted small mb-0">
                            <?= $cardIntro ?>
                        </p>
                    <?php endif; ?>
                </div>


                <!-- Card body: form content from child view -->
                <div class="smro-auth-card-body card-body px-4 py-4">
                    <?= $this->renderSection('content') ?>
                </div>


                <!-- Card footer: "Don't have an account?" style links -->
                <?php
                $cardFooter = $this->renderSection('card_footer');
                if (trim($cardFooter) !== '') :
                ?>
                    <div class="smro-auth-card-footer card-footer border-0 bg-transparent text-center pb-4 pt-0">
                        <small class="text-muted">
                            <?= $cardFooter ?>
                        </small>
                    </div>
                <?php endif; ?>


            </div><!-- /card -->


            <!-- Tenant context badge (shown only when a tenant is set) -->
            <?php $tenantName = session('tenant_name'); ?>
            <?php if ($tenantName !== null) : ?>
                <div class="text-center mt-3">
                    <span class="badge bg-secondary bg-opacity-25 text-secondary font-monospace px-3 py-2">
                        <i class="bi bi-building me-1" aria-hidden="true"></i>
                        <?= esc($tenantName) ?>
                    </span>
                </div>
            <?php endif; ?>


        </div><!-- /card-wrapper -->


    </main>
    <!-- /main -->




    <!-- ── Minimal footer ─────────────────────────────────────────────── -->
    <footer class="smro-auth-footer text-center py-3">
        <small class="text-muted">
            &copy; <?= date('Y') ?> SMRO &mdash; All rights reserved.
        </small>
    </footer>




    <!-- ═══════════════════════════════════════════════════════════════════
         SCRIPTS
         ═══════════════════════════════════════════════════════════════════ -->
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc4s9bIOgUxi8T/jzmh3E/J72sOF/MasrMf4TlBiS4Gi"
        crossorigin="anonymous"
    ></script>


    <script>
    (function () {
        'use strict';


        /* 1. Auto-dismiss flash alerts after 4 seconds ───────────────── */
        document.querySelectorAll('.smro-alert[data-auto-dismiss]').forEach(function (alertEl) {
            const delay = parseInt(alertEl.dataset.autoDismiss, 10) || 4000;
            setTimeout(function () {
                bootstrap.Alert.getOrCreateInstance(alertEl).close();
            }, delay);
        });


        /* 2. Password visibility toggle
        ────────────────────────────────
           Any <button data-smro-pwd-toggle="#inputId"> will toggle the
           type attribute of the target <input> between password / text.    */
        document.querySelectorAll('[data-smro-pwd-toggle]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const target = document.querySelector(btn.dataset.smroPwdToggle);
                if (!target) return;


                const isHidden = target.type === 'password';
                target.type   = isHidden ? 'text' : 'password';


                const icon = btn.querySelector('i');
                if (icon) {
                    icon.classList.toggle('bi-eye',      !isHidden);
                    icon.classList.toggle('bi-eye-slash', isHidden);
                }


                btn.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
            });
        });


        /* 3. Animate card in on load ─────────────────────────────────── */
        const card = document.querySelector('.smro-auth-card-wrapper');
        if (card) {
            card.classList.add('smro-auth-card--visible');
        }


    }());
    </script>


    <?= $this->renderSection('scripts') ?>


</body>
</html>