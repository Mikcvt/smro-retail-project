<?php
$mysqli = new mysqli('localhost', 'root', '', 'smro_retail');
if ($mysqli->connect_errno) {
    echo 'CONNECT_FAIL: ' . $mysqli->connect_error . "\n";
    exit(1);
}
$check = $mysqli->query("SELECT p.id, p.name, v.id AS vid, v.sku, v.stock_quantity FROM products p JOIN product_variants v ON v.product_id = p.id WHERE p.name = 'Minimalist Crewneck Tee'");
if (!$check) {
    echo 'CHECK_QUERY_FAIL: ' . $mysqli->error . "\n";
    exit(1);
}
while ($row = $check->fetch_assoc()) {
    echo 'PID=' . $row['id'] . ' VID=' . $row['vid'] . ' SKU=' . $row['sku'] . ' STOCK=' . $row['stock_quantity'] . "\n";
}
$check->close();

$saleRes = $mysqli->query("SELECT s.id, s.reference_no, s.total_amount, s.created_at FROM sales s ORDER BY s.id DESC LIMIT 1");
if ($saleRes && $saleRes->num_rows) {
    $sale = $saleRes->fetch_assoc();
    echo "LAST_SALE_ID=" . $sale['id'] . " REF=" . $sale['reference_no'] . " AMOUNT=" . $sale['total_amount'] . " CREATED=" . $sale['created_at'] . "\n";
    $items = $mysqli->query("SELECT * FROM sale_items WHERE sale_id = " . $sale['id']);
    while ($item = $items->fetch_assoc()) {
        echo 'SALE_ITEM VARIANT=' . $item['variant_id'] . ' QTY=' . $item['quantity'] . ' PRICE=' . $item['unit_price'] . ' SUB=' . $item['subtotal'] . "\n";
    }
    $items->close();
} else {
    echo "NO_SALES\n";
}

$mysqli->close();
