<?php
include 'functions.php'; // Ensure this file contains functions for database operations

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate and sanitize input
    $customer_id = isset($_POST['customer_id']) ? intval($_POST['customer_id']) : 0;
    $order_date = isset($_POST['order_date']) ? $_POST['order_date'] : '';
    $menu_items = isset($_POST['menu_items']) ? $_POST['menu_items'] : [];

    // Calculate total amount
    $total_amount = 0.00;
    $filtered_items = [];
    foreach ($menu_items as $menu_item_id => $quantity) {
        $quantity = intval($quantity);
        if ($quantity > 0) {
            // Retrieve menu item price
            $menu_item = getById('menu_items', $menu_item_id);
            if ($menu_item) {
                $total_amount += $menu_item['price'] * $quantity;
                $filtered_items[$menu_item_id] = $quantity;
            }
        }
    }
    if (count($filtered_items) > 0) {
        // Insert the order into the orders table
        $order_data = [
            'customers_id' => $customer_id,
            'order_date' => $order_date,
            'total_amount' => $total_amount
        ];
        // Insert order and get the inserted order_id
        $order_id = insert('orders', $order_data); // Ensure your insert function returns the ID of the inserted row

        if ($order_id) {
            // Insert each valid menu item into the order_items table
            foreach ($filtered_items as $menu_item_id => $quantity) {
                $order_item_data = [
                    'orders_id' => $order_id,
                    'menu_items_id' => $menu_item_id,
                    'quantity' => $quantity
                ];
                $result = insert('order_items', $order_item_data);
                if (!$result) {
                    echo "Error inserting order item: $menu_item_id with quantity: $quantity";
                }
            }
        } else {
            echo "Error inserting order";
        }
    }

    // Redirect to order list page
    header('Location: /db/orders.php');
    exit();
}

$customers = getAll('customers');
$orders = getAll('orders');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Manage Orders</h1>
    <div class="card mb-4">
        <div class="card-header">Add Order</div>
        <div class="card-body">
            <form action="/db/orders.php" method="POST">
                <div class="mb-3">
                    <label for="customerId" class="form-label">Customer</label>
                    <select class="form-control" id="customerId" name="customer_id" required>
                        <?php foreach ($customers as $customer): ?>
                        <option value="<?= $customer['customers_id'] ?>"><?= $customer['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="orderDate" class="form-label">Order Date</label>
                    <input type="date" class="form-control" id="orderDate" name="order_date" required>
                </div>
                <div class="mb-3">
                    <label for="menuItems" class="form-label">Menu Items</label>
                    <div id="menuItems">
                        <!-- Dynamic menu items will be added here -->
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Add Order</button>
            </form>
        </div>
    </div>

    <h2>Order List</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Order Date</th>
                <th>Total Amount</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
            <tr>
                <td><?= $order['orders_id'] ?></td>
                <td><?=  getCustomerEmailById($order['customers_id']) ?></td>
                <td><?= $order['order_date'] ?></td>
                <td><?= $order['total_amount'] ?></td>
                <td>
                    <a href="/db/delete_order.php?id=<?= $order['orders_id'] ?>" class="btn btn-sm btn-danger">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="/db/index.php" class="btn btn-secondary">Back to Home</a>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var menuItems = <?php echo json_encode(getAll('menu_items')); ?>;
        var menuContainer = document.getElementById('menuItems');
        menuItems.forEach(function(item) {
            var div = document.createElement('div');
            div.className = 'mb-3';
            div.innerHTML = `
                <label class="form-label">${item.name}</label>
                <input type="number" class="form-control" name="menu_items[${item.menu_items_id}]" placeholder="Quantity" min="1">
            `;
            menuContainer.appendChild(div);
        });
    });
</script>
</body>
</html>
