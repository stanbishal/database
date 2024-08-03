<?php
include 'functions.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = $_POST['order_id'];
    $customer_id = $_POST['customer_id'];
    $order_date = $_POST['order_date'];

    // Prepare data for updating the order
    $data = [
        'customers_id' => $customer_id,
        'order_date' => $order_date
    ];

    // Update order
    if (!update('orders', $data, $order_id)) {
        die('Error updating order. Please check your database and update function.');
    }

    // Remove existing order items
    if (!delete('order_items', ['orders_id' => $order_id])) {
        die('Error deleting order items. Please check your database and delete function.');
    }

    // Insert new order items
    $total_amount = 0.00;
    $menu_items = $_POST['menu_items'] ?? [];
    foreach ($menu_items as $menu_item_id => $quantity) {
        $quantity = intval($quantity);
        if ($quantity > 0) {
            // Retrieve menu item price
            $menu_item = getById('menu_items', $menu_item_id);
            if ($menu_item) {
                $total_amount += $menu_item['price'] * $quantity;
                $order_item_data = [
                    'orders_id' => $order_id,
                    'menu_items_id' => $menu_item_id,
                    'quantity' => $quantity
                ];
                if (!insert('order_items', $order_item_data)) {
                    die('Error inserting order item. Please check your database and insert function.');
                }
            }
        }
    }

    // Update the total amount
    $data['total_amount'] = $total_amount;
    if (!update('orders', $data, $order_id)) {
        die('Error updating total amount. Please check your database and update function.');
    }

    // Redirect to the orders page
    header('Location: /db/orders.php');
    exit();
}

$order_id = $_GET['id'];
$order = getById('orders', $order_id);
$customers = getAll('customers');
$menu_items = getAll('menu_items');

// Get order items associated with the order
$order_items = getAllBy('order_items', ['orders_id' => $order_id]);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Edit Order</h1>
    <div class="card mb-4">
        <div class="card-header">Edit Order Details</div>
        <div class="card-body">
            <form action="/db/edit_order.php" method="POST">
                <input type="hidden" name="order_id" value="<?= htmlspecialchars($order['orders_id']) ?>">
                <div class="mb-3">
                    <label for="customerId" class="form-label">Customer</label>
                    <select class="form-control" id="customerId" name="customer_id" required>
                        <?php foreach ($customers as $customer): ?>
                        <option value="<?= htmlspecialchars($customer['customers_id']) ?>" <?= $customer['customers_id'] == $order['customers_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($customer['name']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="orderDate" class="form-label">Order Date</label>
                    <input type="date" class="form-control" id="orderDate" name="order_date" value="<?= htmlspecialchars($order['order_date']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="menuItems" class="form-label">Menu Items</label>
                    <div id="menuItems">
                        <?php foreach ($menu_items as $item): ?>
                        <div class="mb-3">
                            <label class="form-label"><?= htmlspecialchars($item['name']) ?></label>
                            <input type="number" class="form-control" name="menu_items[<?= htmlspecialchars($item['menu_items_id']) ?>]" placeholder="Quantity" value="<?= htmlspecialchars($order_items[$item['menu_items_id']]['quantity'] ?? 0) ?>" min="0">
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Update Order</button>
            </form>
        </div>
    </div>
    <a href="/db/orders.php" class="btn btn-secondary">Back to Orders</a>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
