<?php
// Start session
session_start();

// Include database connection file
include 'db.php';

// Check if equipment_id is provided in the URL
if (!isset($_SESSION['equipment_id'])) {
    // Redirect to an error page or display an error message
    echo "<script>alert('Equipment ID is missing.'); window.location.href = 'error.php';</script>";
    exit;
}

// Retrieve user details from session
$userDetails = $_SESSION['user'];

// Retrieve equipment_id from session
$equipmentId = $_SESSION['equipment_id'];

// Retrieve equipment details from database
$query = "SELECT * FROM equipment WHERE equipment_id = '$equipmentId'";
$result = mysqli_query($conn, $query);

if (!$result) {
    // Handle error if query fails
    echo "Error: " . mysqli_error($conn);
    exit;
}

$row = mysqli_fetch_assoc($result);

// Calculate rental duration
$rentalStartDate = date('Y-m-d');
$rentalEndDate = date('Y-m-d', strtotime('+1 day')); // Default to one day rental
$rentalDuration = 1; // Default duration in days

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $quantity = $_POST['quantity'];
    $rentalStartDate = $_POST['rental_start_date'];
    $rentalEndDate = $_POST['rental_end_date'];
    $rentalDuration = $_POST['rental_duration'];
    
    // Calculate price based on quantity and rent per day
    $price = $quantity * $row['rent_per_day'];

    // Insert order into database
    $userId = $userDetails['userid'];
    $vendorId = $row['vendor_id'];

    $insertQuery = "INSERT INTO orders (user_id, equipment_id, vendor_id, quantity, rental_start_date, rental_end_date, rental_duration, price) 
                    VALUES ('$userId', '$equipmentId', '$vendorId', '$quantity', '$rentalStartDate', '$rentalEndDate', '$rentalDuration', '$price')";

    if (mysqli_query($conn, $insertQuery)) {
        // Display success message
        echo "<script>alert('Your request is created successfully. You will get notified by email when the vendor accepts the request.');</script>";
        // Redirect to userequipment.php on success
        header("Location: userequipment.php");
        exit;
    } else {
        // Handle error if insertion fails
        echo "Error: " . mysqli_error($conn);
        exit;
    }
}
?>