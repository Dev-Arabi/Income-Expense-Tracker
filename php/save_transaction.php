<?php
session_start();
require_once 'loadenv.php';

if (!isset($_SESSION['user']) || !isset($_SESSION['table_name'])) {
    die(json_encode(["success" => false, "error" => "Unauthorized"]));
}

$servername = getenv('DB_HOST');
$username = getenv('DB_USER');
$password = getenv('DB_PASS');
$dbname = getenv('DB_NAME');

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["success" => false, "error" => "Connection failed"]));
}

$table_name = $_SESSION['table_name']; // Get user-specific table

$date = $_POST['date'];
$type = $_POST['type'];
$amount = $_POST['amount'];
$comment = isset($_POST['comment']) ? substr($_POST['comment'], 0, 10) : null; // Limit to 10 characters

// Check if 'time' is sent and log it
if (isset($_POST['time'])) {
    $time = $_POST['time'];
} else {
    $time = date('H:i:s'); // Default to server time if no time is passed
}

// Prepare the SQL statement with the comment field included
$sql = "INSERT INTO $table_name (date, type, amount, time, comment) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssdss", $date, $type, $amount, $time, $comment);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "date" => $date, "type" => $type, "amount" => $amount, "time" => $time, "comment" => $comment]);
} else {
    echo json_encode(["success" => false, "error" => $conn->error]);
}

$conn->close();
?>
