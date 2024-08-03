<?php
include 'functions.php';

if (isset($_GET['id'])) {
    delete('customers', $_GET['id']);
}

header('Location: /db/customers.php');
exit();
?>
