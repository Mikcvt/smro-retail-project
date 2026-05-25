<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <h2 class="mb-4">Process Return</h2>

    <?= $this->include('partials/_alerts') ?>

    <!-- Step 1: Look up sale -->
    <div class="card shadow-sm mb-4" id="lookup-card">
        <div class="card-body">
            <label class="form-label fw-bold">Search Sale by Reference No.</label>
            <div class="input-group" style="max-width:400px">
                <input type="text" id="ref-input" class="form-control" placeholder="e.g. SALE-ABCD1234-20260101">
                <button type="button" class="btn btn-outline-primary" id="lookup-btn">Search</button>
            </div>
            <div id="lookup-error" class="text-danger mt-2 d-none"></div>
        </div>
    </div>

    <!-- Step 2: Return form (hidden until sale found) -->
    <div class="card shadow-sm d-none" id="return-card">
        <div class="card-body">
            <form action="<?= site_url('returns') ?>" method="post" id="return-form">
                <?= csrf_field() ?>
                <input type="hidden" name="reference_no" id="hidden-ref">

                <div class="alert alert-info" id="sale-info"></div>

                <h5 class="mb-3">Select Items to Return</h5>
                <div class="table-responsive mb-3">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th><input type="checkbox" id="check-all"></th>
                                <th>Variant</th>
                                <th>SKU</th>
                                <th>Qty Purchased</th>
                                <th>Return Qty</th>
                                <th>Item Reason</th>
                            </tr>
                        </thead>
                        <tbody id="return-items-body"></tbody>
                    </table>
                </div>

                <div class="mb-3">
                    <label class="form-label">Overall Return Reason <span class="text-danger">*</span></label>
                    <textarea name="reason" class="form-control <?= session('errors.reason') ? 'is-invalid' : '' ?>"
                              rows="2" required><?= old('reason') ?></textarea>
                    <div class="invalid-feedback"><?= session('errors.reason') ?></div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-warning">Submit Return</button>
                    <a href="<?= site_url('returns') ?>" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('lookup-btn').addEventListener('click', function () {
        const ref = document.getElementById('ref-input').value.trim();
        const errEl = document.getElementById('lookup-error');

        if (!ref) {
            errEl.textContent = 'Please enter a reference number.';
            errEl.classList.remove('d-none');
            return;
        }

        fetch(`<?= site_url('api/sales/lookup') ?>?ref=${encodeURIComponent(ref)}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.status !== 'success') {
                errEl.textContent = data.message;
                errEl.classList.remove('d-none');
                document.getElementById('return-card').classList.add('d-none');
                return;
            }

            errEl.classList.add('d-none');
            const sale = data.data;

            document.getElementById('hidden-ref').value = sale.reference_no;
            document.getElementById('sale-info').textContent =
                `Sale: ${sale.reference_no} | Total: ₱${parseFloat(sale.total_amount).toFixed(2)} | Date: ${sale.created_at}`;

            const tbody = document.getElementById('return-items-body');
            tbody.innerHTML = '';

            sale.items.forEach((item, i) => {
                tbody.innerHTML += `
                    <tr>
                        <td><input type="checkbox" class="item-check" data-index="${i}"></td>
                        <td>${item.size} / ${item.color}</td>
                        <td>${item.sku}</td>
                        <td>${item.quantity}</td>
                        <td>
                            <input type="number" name="quantities[]" class="form-control form-control-sm return-qty d-none"
                                   min="1" max="${item.quantity}" value="1" data-index="${i}">
                            <input type="hidden" name="variant_ids[]" class="variant-id-input d-none"
                                   value="${item.variant_id}" data-index="${i}">
                        </td>
                        <td>
                            <input type="text" name="item_reasons[]" class="form-control form-control-sm item-reason d-none"
                                   placeholder="Optional" data-index="${i}">
                        </td>
                    </tr>
                `;
            });

            document.getElementById('return-card').classList.remove('d-none');

            // Toggle inputs on checkbox
            tbody.addEventListener('change', function (e) {
                if (e.target.classList.contains('item-check')) {
                    const idx = e.target.dataset.index;
                    const checked = e.target.checked;
                    tbody.querySelector(`.return-qty[data-index="${idx}"]`).classList.toggle('d-none', !checked);
                    tbody.querySelector(`.variant-id-input[data-index="${idx}"]`).classList.toggle('d-none', !checked);
                    tbody.querySelector(`.item-reason[data-index="${idx}"]`).classList.toggle('d-none', !checked);
                }
            });
        })
        .catch(() => {
            errEl.textContent = 'Error looking up sale. Please try again.';
            errEl.classList.remove('d-none');
        });
    });

    document.getElementById('check-all').addEventListener('change', function () {
        document.querySelectorAll('.item-check').forEach(cb => {
            cb.checked = this.checked;
            cb.dispatchEvent(new Event('change', { bubbles: true }));
        });
    });
</script>
<?= $this->endSection() ?>
