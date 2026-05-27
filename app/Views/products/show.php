<?php
/**
 * @var array $product
 * @var array $variants
 */
?>
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><?= esc($product['name']) ?></h2>
        <a href="<?= site_url('products') ?>" class="btn btn-outline-secondary">← Back to List</a>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <?php if ($product['image_path']): ?>
                    <img src="<?= base_url('uploads/' . $product['image_path']) ?>" 
                         class="card-img-top" alt="<?= esc($product['name']) ?>" style="object-fit: cover; height: 300px;">
                <?php else: ?>
                    <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center text-white" style="height: 300px;">
                        No Image
                    </div>
                <?php endif; ?>
                <div class="card-body">
                    <h5 class="card-title"><?= esc($product['brand']) ?></h5>
                    <div class="mb-3">
                        <span class="badge bg-secondary me-2"><?= esc($product['category_name'] ?? 'Uncategorized') ?></span>
                        <span class="badge bg-info text-dark">₱<?= number_format((float)$product['base_price'], 2) ?></span>
                    </div>
                    <div class="mb-3">
                        <span class="badge bg-<?= $product['total_stock'] < 10 ? 'danger' : ($product['total_stock'] < 50 ? 'warning' : 'success') ?>">
                            Available stock: <?= esc($product['total_stock']) ?>
                        </span>
                    </div>
                    <p class="card-text text-muted"><?= esc($product['description'] ?? 'No description') ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <span>Product Variants</span>
                    <span class="badge bg-info"><?= count($variants) ?> variant(s)</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Size</th>
                                    <th>Color</th>
                                    <th>SKU</th>
                                    <th>Stock</th>
                                    <th>Price Modifier</th>
                                    <th>Final Price</th>
                                    <?php if (in_array(session('role'), ['superadmin', 'manager', 'staff'], true)): ?>
                                        <th>Action</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($variants)): ?>
                                    <tr><td colspan="7" class="text-center text-muted">No variants found.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($variants as $v): ?>
                                        <tr>
                                            <td><?= esc($v['size']) ?></td>
                                            <td><?= esc($v['color']) ?></td>
                                            <td><code><?= esc($v['sku']) ?></code></td>
                                            <td>
                                                <span class="badge bg-<?= $v['stock_quantity'] < 10 ? 'danger' : ($v['stock_quantity'] < 50 ? 'warning' : 'success') ?>">
                                                    <?= $v['stock_quantity'] ?>
                                                </span>
                                            </td>
                                            <td>₱<?= number_format((float)$v['price_modifier'], 2) ?></td>
                                            <td>₱<?= number_format((float)$product['base_price'] + (float)$v['price_modifier'], 2) ?></td>
                                            <?php if (in_array(session('role'), ['superadmin', 'manager', 'staff'], true)): ?>
                                                <td>
                                                    <form action="<?= site_url('products/' . $product['id'] . '/stock') ?>" method="post" class="d-flex gap-1">
                                                        <?= csrf_field() ?>
                                                        <input type="hidden" name="variant_id" value="<?= $v['id'] ?>">
                                                        <input type="number" name="stock_quantity" class="form-control form-control-sm" 
                                                               value="<?= $v['stock_quantity'] ?>" min="0" style="width: 70px;">
                                                        <button type="submit" class="btn btn-sm btn-outline-primary">Update</button>
                                                    </form>
                                                </td>
                                            <?php endif; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>