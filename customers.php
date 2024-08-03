<?php
include 'functions.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'name' => $_POST['name'],
        'email' => $_POST['email']
    ];
    insert('customers', $data);
    header('Location: /db/customers.php');
    exit();
}

$customers = getAll('customers');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Customers</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Manage Customers</h1>
    <div class="card mb-4">
        <div class="card-header">Add Customer</div>
        <div class="card-body">
            <form action="/db/customers.php" method="POST">
                <div class="mb-3">
                    <label for="customerName" class="form-label">Name</label>
                    <input type="text" class="form-control" id="customerName" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="customerEmail" class="form-label">Email</label>
                    <input type="email" class="form-control" id="customerEmail" name="email" required>
                </div>
                <button type="submit" class="btn btn-primary">Add Customer</button>
            </form>
        </div>
    </div>

    <h2>Customer List</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($customers as $customer): ?>
            <tr>
                <td><?= $customer['customers_id'] ?></td>
                <td><?= $customer['name'] ?></td>
                <td><?= $customer['email'] ?></td>
                <td>
                    <a href="/db/edit_customer.php?id=<?= $customer['customers_id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="/db/delete_customer.php?id=<?= $customer['customers_id'] ?>" class="btn btn-sm btn-danger">Delete</a>
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
