<?php
session_start();
include("config/config.php");

// Check login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Require FPDF library
require('fpdf/fpdf.php');
mysqli_set_charset($conn, "utf8mb4");

// --- Fetch user info ---
$user_id = $_SESSION['user_id'];
$user_sql = "SELECT * FROM users WHERE id='$user_id'";
$user_res = mysqli_query($conn, $user_sql);
$user = mysqli_fetch_assoc($user_res);

// --- Fetch latest order ---
$order_id = $_SESSION['order_id'] ?? '';
$order_sql = "SELECT * FROM orders WHERE order_id='$order_id' AND user_id='$user_id'";
$order_res = mysqli_query($conn, $order_sql);
$order = mysqli_fetch_assoc($order_res);

if (!$order) {
    die("Order not found.");
}

// --- Prepare items array ---
$order_items = explode(',', $order['items']); // Assuming stored like "T-shirt x 2, Jeans x 1"

$pdf = new FPDF();
$pdf->AddPage();

// Draw page border
$pdf->SetLineWidth(0.5);
$pdf->Rect(5, 5, 200, 287);

// ===== HEADER =====
$pdf->SetFont('Arial', 'B', 18);
$pdf->Cell(0, 10, "INVOICE", 0, 1, "C");
$pdf->Ln(3);

// Logo (Top Left)
$pdf->Image('images/logo/2-01.png', 10, 20, 30); // <- replace with your logo path

// Company Info (Top Right)
$pdf->SetXY(140, 20);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(60, 6, "Rare Wing Pvt Ltd", 0, 1, "L");

$pdf->SetFont('Arial', '', 11);
$pdf->SetXY(140, 26);
$pdf->Cell(60, 6, "Email: info@rarewing.com", 0, 1, "L");
$pdf->SetXY(140, 32);
$pdf->Cell(60, 6, "Phone: +91-9876543210", 0, 1, "L");
$pdf->SetXY(140, 38);
$pdf->Cell(60, 6, "CIN/GST: 123456789", 0, 1, "L");


$pdf->Ln(25);

// ===== INVOICE INFO =====
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(60, 6, "Date: " . date("d M Y", strtotime($order['created_at'])), 0, 1, "L");
$pdf->Cell(100, 6, "Invoice No: INV-" . $order['id'], 0, 0);

$pdf->Ln(5);

// ===== BILL TO =====
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(100, 6, "Bill To:", 0, 1);

$pdf->SetFont('Arial', '', 11);
$pdf->Cell(100, 6, $order['customer_name'], 0, 1);
$pdf->Cell(100, 6, $order['customer_email'], 0, 1);
$pdf->Cell(100, 6, $order['customer_phone'], 0, 1);
$pdf->MultiCell(100, 6, $order['address'], 0, 1);

$pdf->Ln(10);

// ===== ORDER TABLE =====
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(80, 10, "Item", 1, 0, 'C');
$pdf->Cell(20, 10, "Qty", 1, 0, 'C');
$pdf->Cell(40, 10, "Status", 1, 0, 'C');
$pdf->Cell(50, 10, "Amount (Rs)", 1, 1, 'C');

$pdf->SetFont('Arial', '', 11);
$total_amount = 0;

//$total_amount = 0;

foreach ($order_items as $item) {
    // Split by ' x ' first
    $parts = explode(' x ', trim($item)); // ["Arc Hoodie", "1 = 8000.00"]
    $name = $parts[0];

    // Split second part by ' = '
    $qty_price = explode(' = ', $parts[1] ?? '1 = 0'); // ["1", "8000.00"]
    $qty = (int) $qty_price[0];
    $subtotal = (float) $qty_price[1];

    $total_amount += $subtotal;
    $status = ucfirst($order['status']);

    $pdf->Cell(80, 10, $name, 1);
    $pdf->Cell(20, 10, $qty, 1, 0, 'C');
    $pdf->Cell(40, 10, $status, 1, 0, 'C');
    $pdf->Cell(50, 10, number_format($subtotal, 2), 1, 1, 'R'); // Amount correctly
}


// ===== TOTAL =====
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(140, 10, "Total Amount (Rs)", 1);
$pdf->Cell(50, 10, number_format($order['amount'], 2), 1, 1, 'R');

// ===== FOOTER =====
$pdf->Ln(10);
$pdf->SetFont('Arial', 'I', 10);
$pdf->Cell(0, 10, "Thank you for shopping with Rare Wing!", 0, 1, "C");

$pdf->Output("D", "Invoice-" . $order['order_id'] . ".pdf");
?>
