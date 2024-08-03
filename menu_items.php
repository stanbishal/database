<?php
include 'functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['menu_items_id'])) {
        // Update menu item
        $data = [
            'name' => $_POST['name'],
            'price' => $_POST['price'],
            'category' => $_POST['category']
        ];
        update('menu_items', $data, $_POST['menu_items_id']);
    } else {
        // Add new menu item
        $data = [
            'name' => $_POST['name'],
            'price' => $_POST['price'],
            'category' => $_POST['category']
        ];
        insert('menu_items', $data);
    }
    header('Location: /db/menu_items.php');
    exit();
}

if (isset($_GET['delete'])) {
    delete('menu_items', $_GET['delete']);
    header('Location: /db/menu_items.php');
    exit();
}

$menu_items = getAll('menu_items');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Menu Items</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h1 class="mb-4">Manage Menu Items</h1>
    <div class="card mb-4">
        <div class="card-header">Add/Edit Menu Item</div>
        <div class="card-body">
            <form action="/db/menu_items.php" method="POST">
                <?php if (isset($_GET['edit'])):
                    $menu_item = getById('menu_items', $_GET['edit']);
                ?>
                <input type="hidden" name="menu_items_id" value="<?= $menu_item['menu_items_id'] ?>">
                <?php endif; ?>
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?= $menu_item['name'] ?? '' ?>" required>
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Price</label>
                    <input type="number" step="0.01" class="form-control" id="price" name="price" value="<?= $menu_item['price'] ?? '' ?>" required>
                </div>
                <div class="mb-3">
                    <label for="category" class="form-label">Category</label>
                    <input type="text" class="form-control" id="category" name="category" value="<?= $menu_item['category'] ?? '' ?>" required>
                </div>
                <button type="submit" class="btn btn-primary"><?= isset($menu_item) ? 'Update Item' : 'Add Item' ?></button>
            </form>
        </div>
    </div>

    <h2>Menu Item List</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Item ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Category</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($menu_items as $item): ?>
            <tr>
                <td><?= $item['menu_items_id'] ?></td>
                <td><?= $item['name'] ?></td>
                <td>$<?= number_format($item['price'], 2) ?></td>
                <td><?= $item['category'] ?></td>
                <td>
                    <a href="/db/menu_items.php?edit=<?= $item['menu_items_id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="/db/menu_items.php?delete=<?= $item['menu_items_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="/db/index.php" class="btn btn-secondary">Back to Home</a>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
