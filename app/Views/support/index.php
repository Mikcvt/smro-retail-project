<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Help & Support<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row g-4">
    <div class="col-lg-8">
        <!-- FAQ -->
        <div class="card bg-dark border-secondary mb-4">
            <div class="card-header border-secondary">
                <h5 class="mb-0"><i class="bi bi-question-circle me-2"></i>Frequently Asked Questions</h5>
            </div>
            <div class="card-body">
                <div class="accordion accordion-flush" id="faqAccordion">
                    <?php
                    $faqs = [
                        ['How do I process a new sale?', 'Go to Sales → New Sale. Select a product, choose a variant, set the quantity, then click "Complete Sale". Stock will be automatically deducted.'],
                        ['How do I process a return?', 'Go to Returns → New Return. Search for the sale by reference number, select the items to return, provide a reason, and submit. Managers can then approve or reject the return.'],
                        ['How do I add a new product?', 'Go to Products → Add Product (Manager/SuperAdmin only). Fill in the product details, add variants with SKU, size, color, and stock quantity.'],
                        ['How do I adjust stock levels?', 'Go to Products, click on a product to view it, then use the stock adjustment form next to each variant.'],
                        ['I forgot my password. What do I do?', 'Contact your SuperAdmin to reset your password. They can update it from the User Management section.'],
                        ['How do I view sales reports?', 'Go to Reports (Manager/SuperAdmin only) to view sales analytics, revenue summaries, and recent transactions.'],
                        ['What are the different roles?', 'SuperAdmin has full access. Manager can manage inventory, sales, returns, and view reports. Staff can process sales and view history.'],
                    ];
                    foreach ($faqs as $i => [$q, $a]):
                    ?>
                    <div class="accordion-item bg-dark border-secondary border-start-0 border-end-0">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed bg-dark text-white" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#faq<?= $i ?>">
                                <?= esc($q) ?>
                            </button>
                        </h2>
                        <div id="faq<?= $i ?>" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted"><?= esc($a) ?></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Quick Links -->
        <div class="card bg-dark border-secondary mb-4">
            <div class="card-header border-secondary">
                <h6 class="mb-0"><i class="bi bi-lightning me-2"></i>Quick Actions</h6>
            </div>
            <div class="card-body d-grid gap-2">
                <a href="<?= site_url('sales/create') ?>" class="btn btn-outline-primary btn-sm text-start"><i class="bi bi-cart-plus me-2"></i>New Sale</a>
                <a href="<?= site_url('products') ?>" class="btn btn-outline-secondary btn-sm text-start"><i class="bi bi-box-seam me-2"></i>View Products</a>
                <a href="<?= site_url('returns/create') ?>" class="btn btn-outline-warning btn-sm text-start"><i class="bi bi-arrow-return-left me-2"></i>Process Return</a>
                <a href="<?= site_url('profile') ?>" class="btn btn-outline-info btn-sm text-start"><i class="bi bi-person me-2"></i>My Profile</a>
            </div>
        </div>

        <!-- Contact -->
        <div class="card bg-dark border-secondary">
            <div class="card-header border-secondary">
                <h6 class="mb-0"><i class="bi bi-headset me-2"></i>Contact Support</h6>
            </div>
            <div class="card-body small text-muted">
                <p><i class="bi bi-envelope me-2"></i>support@smro.com</p>
                <p><i class="bi bi-telephone me-2"></i>(02) 8XXX-XXXX</p>
                <p><i class="bi bi-clock me-2"></i>Mon–Fri, 8AM–6PM</p>
                <hr class="border-secondary">
                <p class="mb-0">System Version: <span class="text-white">v1.0.0-beta</span></p>
                <p class="mb-0">Framework: <span class="text-white">CodeIgniter 4.7.2</span></p>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
