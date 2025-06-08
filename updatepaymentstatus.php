<?php
// Start session
session_start();

// Include database connection file
include 'db.php';

// Check if order_id is set in the POST data
if (!isset($_POST['order_id'])) {
    // Redirect to an error page or display an error message
    echo "Order ID is missing.";
    exit;
}

// Fetch order_id from POST data
$orderID = $_POST['order_id'];

// Update payment_status in the database
$query = "UPDATE orders SET payment_status = 'success' WHERE order_id = '$orderID'";
$result = mysqli_query($conn, $query);

if (!$result) {
    // Handle error if query fails
    echo "Error updating payment status: " . mysqli_error($conn);
    exit;
}

// Display a thank you message
echo "Thank You For Confirming Payment";

// Redirect to dashboard.php after updating payment status
header("Location: dashboard.php");
exit;
?>
