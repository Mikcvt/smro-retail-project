<?php
/**
 * @var array $products
 */
?>
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <h2 class="mb-4">New Sale</h2>

    <?= $this->include('partials/_alerts') ?>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="<?= site_url('sales') ?>" method="post" id="sale-form">
                <?= csrf_field() ?>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Select Product</label>
                        <select id="product-select" class="form-select">
                            <option value="">-- Choose Product --</option>
                            <?php foreach ($products as $product): ?>
                                <option value="<?= $product['id'] ?>"
                                        data-variants="<?= esc(json_encode($product['variants'] ?? []), 'attr') ?>">
                                    <?= esc($product['name']) ?> — ₱<?= number_format((float) $product['base_price'], 2) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Variant</label>
                        <select id="variant-select" class="form-select" disabled>
                            <option value="">-- Select Variant --</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-outline-primary w-100" id="add-item-btn" disabled>
                            Add Item
                        </button>
                    </div>
                </div>

                <div class="table-responsive mb-3">
                    <table class="table table-dark table-bordered align-middle" id="items-table">
                        <thead class="table-dark text-white">
                            <tr>
                                <th>Product / Variant</th>
                                <th>SKU</th>
                                <th>Unit Price</th>
                                <th style="width:120px">Qty</th>
                                <th>Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="items-body">
                            <tr id="empty-row">
                                <td colspan="6" class="text-center text-muted py-3">No items added yet.</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-end fw-bold">Total</td>
                                <td colspan="2" class="fw-bold" id="grand-total">₱0.00</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="mb-3">
                    <label class="form-label">Notes (optional)</label>
                    <textarea name="notes" class="form-control" rows="2"></textarea>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success" id="submit-btn" disabled>Complete Sale</button>
                    <a href="<?= site_url('sales') ?>" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const productSelect  = document.getElementById('product-select');
    const variantSelect  = document.getElementById('variant-select');
    const addItemBtn     = document.getElementById('add-item-btn');
    const itemsBody      = document.getElementById('items-body');
    const grandTotalEl   = document.getElementById('grand-total');
    const submitBtn      = document.getElementById('submit-btn');
    const emptyRow       = document.getElementById('empty-row');

    let itemIndex = 0;
    let grandTotal = 0;

    productSelect.addEventListener('change', function () {
        const selected = this.options[this.selectedIndex];
        const variants = JSON.parse(selected.dataset.variants || '[]');

        variantSelect.innerHTML = '<option value="">-- Select Variant --</option>';
        variantSelect.disabled = true;
        addItemBtn.disabled = true;

        if (!this.value || variants.length === 0) return;

        variants.forEach(v => {
            const opt = document.createElement('option');
            opt.value = v.id;
            opt.textContent = `${v.size} / ${v.color} — SKU: ${v.sku} (Stock: ${v.stock_quantity})`;
            opt.dataset.price = v.price_modifier;
            opt.dataset.sku   = v.sku;
            opt.dataset.stock = v.stock_quantity;
            variantSelect.appendChild(opt);
        });

        variantSelect.disabled = false;
    });

    variantSelect.addEventListener('change', function () {
        addItemBtn.disabled = !this.value;
    });

    addItemBtn.addEventListener('click', function () {
        const productOpt = productSelect.options[productSelect.selectedIndex];
        const variantOpt = variantSelect.options[variantSelect.selectedIndex];

        if (!productOpt.value || !variantOpt.value) return;

        if (emptyRow) emptyRow.remove();

        const price = parseFloat(variantOpt.dataset.price) || 0;
        const stock = parseInt(variantOpt.dataset.stock) || 0;
        const idx   = itemIndex++;

        const row = document.createElement('tr');
        row.dataset.index = idx;
        row.innerHTML = `
            <td>${productOpt.text.split('—')[0].trim()} — ${variantOpt.text.split('—')[0].trim()}
                <input type="hidden" name="variants[]" value="${variantOpt.value}">
            </td>
            <td>${variantOpt.dataset.sku}</td>
            <td>₱${price.toFixed(2)}</td>
            <td>
                <input type="number" name="quantities[]" class="form-control form-control-sm qty-input"
                       value="1" min="1" max="${stock}" data-price="${price}">
            </td>
            <td class="subtotal">₱${price.toFixed(2)}</td>
            <td>
                <button type="button" class="btn btn-sm btn-outline-danger remove-row">×</button>
            </td>
        `;

        itemsBody.appendChild(row);
        updateTotal();
        submitBtn.disabled = false;

        productSelect.value = '';
        variantSelect.innerHTML = '<option value="">-- Select Variant --</option>';
        variantSelect.disabled = true;
        addItemBtn.disabled = true;
    });

    itemsBody.addEventListener('input', function (e) {
        if (e.target.classList.contains('qty-input')) {
            const price    = parseFloat(e.target.dataset.price) || 0;
            const qty      = parseInt(e.target.value) || 0;
            const subtotal = price * qty;
            e.target.closest('tr').querySelector('.subtotal').textContent = '₱' + subtotal.toFixed(2);
            updateTotal();
        }
    });

    itemsBody.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-row')) {
            e.target.closest('tr').remove();
            updateTotal();
            if (itemsBody.children.length === 0) {
                submitBtn.disabled = true;
            }
        }
    });

    function updateTotal() {
        grandTotal = 0;
        document.querySelectorAll('.subtotal').forEach(el => {
            grandTotal += parseFloat(el.textContent.replace('₱', '')) || 0;
        });
        grandTotalEl.textContent = '₱' + grandTotal.toFixed(2);
    }
</script>
<?= $this->endSection() ?>
