<?php
session_start();
require_once 'loadenv.php';

if (!isset($_SESSION['user']) || !isset($_SESSION['table_name'])) {
    echo json_encode(["error" => "Unauthorized"]);
    exit();
}

$servername = getenv('DB_HOST');
$username = getenv('DB_USER');
$password = getenv('DB_PASS');
$dbname = getenv('DB_NAME');

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(["error" => "Connection failed"]);
    exit();
}

$table_name = $_SESSION['table_name'];

// Query to calculate the current balance
$query = "SELECT SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as total_income,
                 SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as total_expense
          FROM $table_name";

$result = $conn->query($query);
$row = $result->fetch_assoc();

$currentBalance = $row['total_income'] - $row['total_expense'];

// Return the current balance as JSON
echo json_encode(['currentBalance' => $currentBalance]);

$conn->close();
?>
