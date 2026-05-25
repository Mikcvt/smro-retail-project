<?php
/**
 * @var array $categories
 * @var \CodeIgniter\Validation\Validation $validation
 */
?>
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <h2 class="mb-4">Add New Product</h2>

    <?= $this->include('partials/_alerts') ?>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="<?= site_url('products') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Product Name</label>
                        <input type="text" name="name" class="form-control <?= session('errors.name') ? 'is-invalid' : '' ?>" 
                               value="<?= old('name') ?>" required>
                        <div class="invalid-feedback"><?= session('errors.name') ?></div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-select <?= session('errors.category_id') ? 'is-invalid' : '' ?>" required>
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>" <?= old('category_id') == $cat['id'] ? 'selected' : '' ?>>
                                    <?= esc($cat['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback"><?= session('errors.category_id') ?></div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Brand</label>
                        <input type="text" name="brand" class="form-control <?= session('errors.brand') ? 'is-invalid' : '' ?>" 
                               value="<?= old('brand') ?>" required>
                        <div class="invalid-feedback"><?= session('errors.brand') ?></div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Base Price (₱)</label>
                        <input type="number" step="0.01" name="base_price" class="form-control <?= session('errors.base_price') ? 'is-invalid' : '' ?>" 
                               value="<?= old('base_price') ?>" required>
                        <div class="invalid-feedback"><?= session('errors.base_price') ?></div>
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"><?= old('description') ?></textarea>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Product Image</label>
                        <input type="file" name="image" class="form-control <?= session('errors.image') ? 'is-invalid' : '' ?>" 
                               accept="image/jpeg,image/png,image/webp">
                        <div class="form-text">Max 2MB. JPG, PNG, or WEBP only.</div>
                        <div class="invalid-feedback"><?= session('errors.image') ?></div>
                    </div>
                </div>

                <hr class="my-4">

                <h4>Product Variants</h4>
                <p class="text-muted small">Add size/color combinations. Each variant must have a unique SKU.</p>

                <div id="variants-container">
                    <div class="variant-row row g-2 mb-2 border rounded p-2 bg-light">
                        <div class="col-md-2">
                            <input type="text" name="variants[0][size]" class="form-control" placeholder="Size (S, M, L)" value="M" required>
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="variants[0][color]" class="form-control" placeholder="Color" value="Black" required>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="variants[0][sku]" class="form-control" placeholder="SKU" required>
                        </div>
                        <div class="col-md-2">
                            <input type="number" name="variants[0][stock]" class="form-control" placeholder="Stock" value="0" min="0" required>
                        </div>
                        <div class="col-md-2">
                            <input type="number" step="0.01" name="variants[0][price_modifier]" class="form-control" placeholder="Price ±" value="0">
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-outline-danger btn-sm remove-variant" disabled>×</button>
                        </div>
                    </div>
                </div>

                <button type="button" class="btn btn-outline-secondary btn-sm mb-4" id="add-variant">
                    + Add Another Variant
                </button>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Save Product</button>
                    <a href="<?= site_url('products') ?>" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('add-variant').addEventListener('click', function() {
    const container = document.getElementById('variants-container');
    const index = container.children.length;
    const row = document.createElement('div');
    row.className = 'variant-row row g-2 mb-2 border rounded p-2 bg-light';
    row.innerHTML = `
        <div class="col-md-2">
            <input type="text" name="variants[${index}][size]" class="form-control" placeholder="Size" required>
        </div>
        <div class="col-md-2">
            <input type="text" name="variants[${index}][color]" class="form-control" placeholder="Color" required>
        </div>
        <div class="col-md-3">
            <input type="text" name="variants[${index}][sku]" class="form-control" placeholder="SKU" required>
        </div>
        <div class="col-md-2">
            <input type="number" name="variants[${index}][stock]" class="form-control" placeholder="Stock" value="0" min="0" required>
        </div>
        <div class="col-md-2">
            <input type="number" step="0.01" name="variants[${index}][price_modifier]" class="form-control" placeholder="Price ±" value="0">
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-outline-danger btn-sm remove-variant">×</button>
        </div>
    `;
    container.appendChild(row);
    
    // Enable all remove buttons
    document.querySelectorAll('.remove-variant').forEach(btn => btn.disabled = false);
});

document.getElementById('variants-container').addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-variant')) {
        e.target.closest('.variant-row').remove();
    }
});
</script>
<?= $this->endSection() ?>