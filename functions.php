<?php
include 'db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function getAll($table) {
    global $conn;
    $sql = "SELECT * FROM $table";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
}

function getById($table, $id) {
    global $conn;
    $sql = "SELECT * FROM $table WHERE ${table}_id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetch();
}

function insert($table, $data) {
    global $conn;
    $keys = implode(", ", array_keys($data));
    $values = ":" . implode(", :", array_keys($data));
    $sql = "INSERT INTO $table ($keys) VALUES ($values)";
    $stmt = $conn->prepare($sql);
    foreach ($data as $key => &$value) {
        $stmt->bindParam(":$key", $value);
    }
    if ($stmt->execute()) {
        return $conn->lastInsertId(); // Return the ID of the last inserted row
    } else {
        echo "Error inserting data: " . $stmt->errorInfo()[2];
        return null;
    }
}

function update($table, $data, $id) {
    global $conn;
    $fields = "";
    foreach ($data as $key => $value) {
        $fields .= "$key = :$key, ";
    }
    $fields = rtrim($fields, ", ");
    $sql = "UPDATE $table SET $fields WHERE ${table}_id = :id";
    $stmt = $conn->prepare($sql);
    foreach ($data as $key => &$value) {
        $stmt->bindParam(":$key", $value);
    }
    $stmt->bindParam(':id', $id);
    return $stmt->execute();
}

function delete($table, $id) {
    global $conn;
    $sql = "DELETE FROM $table WHERE ${table}_id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
}

function deleteOrderItems($orders_id) {
    global $conn;
    $sql = "DELETE FROM order_items WHERE orders_id = :orders_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':orders_id', $orders_id);
    $stmt->execute();
}


function getCustomerEmailById($customerId) {
    global $conn;
    
    // Define the SQL query to fetch the customer's email by their ID
    $sql = "SELECT email FROM customers WHERE customers_id = :id";
    
    // Prepare and execute the statement
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $customerId);
    $stmt->execute();
    
    // Fetch the result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Return the email if found, or null if not
    return $result ? $result['email'] : null;
}

function getAllBy($table, $conditions) {
    global $conn;
    
    // Build the SQL query with conditions
    $conditionStrings = [];
    foreach ($conditions as $column => $value) {
        $conditionStrings[] = "$column = :$column";
    }
    $conditionsSql = implode(' AND ', $conditionStrings);
    
    $sql = "SELECT * FROM $table WHERE $conditionsSql";
    $stmt = $conn->prepare($sql);
    
    // Bind the parameters
    foreach ($conditions as $column => $value) {
        $stmt->bindValue(":$column", $value);
    }
    
    $stmt->execute();
    return $stmt->fetchAll();
}


?>
