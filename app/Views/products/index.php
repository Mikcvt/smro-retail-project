<?php
/**
 * @var array $products
 * @var \CodeIgniter\Pager\Pager $pager
 * @var array $categories
 */
?>
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Product Inventory</h2>
        <?php if (in_array(session('role'), ['superadmin', 'manager'], true)): ?>
            <a href="<?= site_url('products/new') ?>" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Add Product
            </a>
        <?php endif; ?>
    </div>

    <?= $this->include('partials/_alerts') ?>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Brand</th>
                            <th>Base Price</th>
                            <th>Variants</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($products)): ?>
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">No products found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($products as $product): ?>
                                <tr>
                                    <td><?= esc($product['id']) ?></td>
                                    <td>
                                        <?php if ($product['image_path']): ?>
                                            <img src="<?= base_url('uploads/' . $product['image_path']) ?>" 
                                                 alt="<?= esc($product['name']) ?>" 
                                                 class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                                        <?php else: ?>
                                            <div class="bg-secondary text-white d-flex align-items-center justify-content-center rounded" 
                                                 style="width: 60px; height: 60px;">No Img</div>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= esc($product['name']) ?></td>
                                    <td><?= esc($product['category_name'] ?? 'N/A') ?></td>
                                    <td><?= esc($product['brand']) ?></td>
                                    <td>₱<?= number_format((float)$product['base_price'], 2) ?></td>
                                    <td>
                                        <a href="<?= site_url('products/' . $product['id']) ?>" class="btn btn-sm btn-info">
                                            View
                                        </a>
                                    </td>
                                    <td>
                                        <a href="<?= site_url('products/' . $product['id']) ?>" class="btn btn-sm btn-outline-primary">View</a>
                                        <?php if (in_array(session('role'), ['superadmin', 'manager'], true)): ?>
                                            <a href="<?= site_url('products/' . $product['id'] . '/edit') ?>" class="btn btn-sm btn-outline-warning">Edit</a>
                                            <form action="<?= site_url('products/' . $product['id']) ?>" method="post" class="d-inline" 
                                                  onsubmit="return confirm('Delete this product?');">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="_method" value="DELETE">
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?= $pager ? $pager->links() : '' ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>