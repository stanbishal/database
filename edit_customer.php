<?php
include 'functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['customers_id'];
    $data = [
        'name' => $_POST['name'],
        'email' => $_POST['email']
    ];
    update('customers', $data, $id);
    header('Location: /db/customers.php');
    exit();
}

$customer_id = $_GET['id'];
$customer = getById('customers', $customer_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Customer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Edit Customer</h1>
    <div class="card mb-4">
        <div class="card-header">Edit Customer Details</div>
        <div class="card-body">
            <form action="/db/edit_customer.php" method="POST">
                <input type="hidden" name="customers_id" value="<?= $customer['customers_id'] ?>">
                <div class="mb-3">
                    <label for="customerName" class="form-label">Name</label>
                    <input type="text" class="form-control" id="customerName" name="name" value="<?= $customer['name'] ?>" required>
                </div>
                <div class="mb-3">
                    <label for="customerEmail" class="form-label">Email</label>
                    <input type="email" class="form-control" id="customerEmail" name="email" value="<?= $customer['email'] ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Update Customer</button>
            </form>
        </div>
    </div>
    <a href="/customers" class="btn btn-secondary">Back to Customers</a>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
