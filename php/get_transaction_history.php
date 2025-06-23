<?php
session_start();
require_once 'loadenv.php';
require('./fpdf/fpdf.php'); // Include FPDF library

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
$user = $_SESSION['user']; // Get username from session

$startDate = $_GET['start_date'];
$endDate = $_GET['end_date'];

// Update query to fetch comments
$query = "SELECT date, time, type, amount, comment FROM $table_name WHERE date BETWEEN ? AND ? ORDER BY date DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $startDate, $endDate);
$stmt->execute();
$result = $stmt->get_result();

$transactions = [];
while ($row = $result->fetch_assoc()) {
    $transactions[] = $row;
}

// If 'generate_pdf' is set, generate the PDF
if (isset($_GET['generate_pdf']) && $_GET['generate_pdf'] == 'true') {
    generatePDF($transactions, $startDate, $endDate, $user);
    exit();
}

// Return transactions as JSON
echo json_encode(['transactions' => $transactions]);
$conn->close();

// Function to generate the PDF
function generatePDF($transactions, $startDate, $endDate, $user) {
    $pdf = new FPDF();
    $pdf->AddPage();

    // Title: "Income & Expense Tracker"
    $pdf->SetFont('Arial', 'B', 20);
    $pdf->Cell(190, 12, 'Income & Expense Tracker', 0, 1, 'C');
    $pdf->Ln(8);

    // Subtitle: "Transaction History"
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(190, 10, 'Transaction History', 0, 1, 'C');
    $pdf->Ln(5);

    // User Information
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(190, 10, "User: $user", 0, 1, 'L');
    $pdf->Cell(190, 10, "Date Range: $startDate to $endDate", 0, 1, 'L');
    $pdf->Ln(5);

    // Table Headers
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(40, 10, 'Date', 1);
    $pdf->Cell(30, 10, 'Time', 1);
    $pdf->Cell(50, 10, 'Type', 1);
    $pdf->Cell(40, 10, 'Amount (BDT)', 1);
    $pdf->Cell(30, 10, 'Reason', 1); // Add comment header
    $pdf->Ln();

    // Table Data
    $pdf->SetFont('Arial', '', 12);
    foreach ($transactions as $transaction) {
        $pdf->Cell(40, 10, $transaction['date'], 1);
        $pdf->Cell(30, 10, $transaction['time'], 1);
        $pdf->Cell(50, 10, ucfirst($transaction['type']), 1);
        $pdf->Cell(40, 10, 'BDT ' . number_format($transaction['amount'], 2), 1);
        $pdf->Cell(30, 10, $transaction['comment'], 1); // Display comment
        $pdf->Ln();
    }

    // Generate a unique filename
    $filename = "transactions_{$user}_{$startDate}_to_{$endDate}.pdf";

    // Output the PDF as a download
    $pdf->Output('D', $filename);
}
?>
