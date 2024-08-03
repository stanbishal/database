<?php
include 'functions.php';

if (isset($_GET['id'])) {
    deleteOrderItems($_GET['id']);    
    delete('orders', $_GET['id']);

}

header('Location: /db/orders.php');
exit();
?>
