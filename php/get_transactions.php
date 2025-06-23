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

// Check if the table exists before querying
$check_table_sql = "SHOW TABLES LIKE '$table_name'";
$result = $conn->query($check_table_sql);

if ($result->num_rows == 0) {
    echo json_encode([]); // Return an empty array if the table doesn't exist
    exit();
}

// Get the current date and the start of the current month
$currentDate = date('Y-m-d');
$currentMonth = date('Y-m');

// Query for daily total income and expenses
$query = "SELECT type, SUM(amount) as total FROM $table_name WHERE date = ? GROUP BY type";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $currentDate);
$stmt->execute();
$result = $stmt->get_result();
$dailyTotal = ['income' => 0, 'expense' => 0];
while ($row = $result->fetch_assoc()) {
    $dailyTotal[$row['type']] = $row['total'];
}

// Query for monthly total income and expenses
$query = "SELECT type, SUM(amount) as total FROM $table_name WHERE DATE_FORMAT(date, '%Y-%m') = ? GROUP BY type";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $currentMonth);
$stmt->execute();
$result = $stmt->get_result();
$monthlyTotal = ['income' => 0, 'expense' => 0];
while ($row = $result->fetch_assoc()) {
    $monthlyTotal[$row['type']] = $row['total'];
}

// Calculate daily and monthly savings/loss
$dailySavingsLose = $dailyTotal['income'] - $dailyTotal['expense'];
$monthlySavingsLose = $monthlyTotal['income'] - $monthlyTotal['expense'];

// Handle filtering
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'both';

if ($filter === "income") {
    $sql = "SELECT date, time, type, amount, comment FROM $table_name WHERE type = 'income' ORDER BY date DESC";
} elseif ($filter === "expense") {
    $sql = "SELECT date, time, type, amount, comment FROM $table_name WHERE type = 'expense' ORDER BY date DESC";
} else {
    $sql = "SELECT date, time, type, amount, comment FROM $table_name ORDER BY date DESC";
}

$result = $conn->query($sql);

$transactions = [];
while ($row = $result->fetch_assoc()) {
    $transactions[] = $row;
}

// Return data as JSON
echo json_encode([
    'transactions' => $transactions,
    'dailyTotal' => $dailyTotal,
    'monthlyTotal' => $monthlyTotal,
    'dailySavingsLose' => $dailySavingsLose,
    'monthlySavingsLose' => $monthlySavingsLose
]);

$conn->close();
?>
