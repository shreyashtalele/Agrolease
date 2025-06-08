<?php
include 'db.php';
$login_success = false;
$login_message = "";
session_start();

// Retrieve user input from the login form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query the database to check if the vendor is valid and approved
    $sql = "SELECT * FROM vendor WHERE email = '$email' AND password = '$password' AND status = 'approved'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Vendor is valid and approved, set login success flag and message
        $login_success = true;
        $login_message = "Login successful!";
        $_SESSION['email'] = $email;
        
        // Fetch vendor's address and vendorid from the database
        $vendor_query = "SELECT vendorid, area, village, taluka, pincode FROM vendor WHERE email = '$email'";
        $vendor_result = $conn->query($vendor_query);
        if ($vendor_result->num_rows > 0) {
            $vendor_row = $vendor_result->fetch_assoc();
            $_SESSION['vendorid'] = $vendor_row['vendorid']; // Store vendorid in the session
            $_SESSION['area'] = $vendor_row['area'];
            $_SESSION['village'] = $vendor_row['village'];
            $_SESSION['taluka'] = $vendor_row['taluka'];
            $_SESSION['pincode'] = $vendor_row['pincode'];
        } else {
            echo "Vendor details not found for email: $email";
            exit;
        }
        
        header("Location: vendor_dashboard.php");
        exit; // Terminate the script after redirecting
    } else {
        // Check if the vendor exists but is not approved
        $sql = "SELECT * FROM vendor WHERE email = '$email'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            // Vendor exists but is not approved, set login failure message
            $login_message = "Your account is not yet approved. Please wait for verification or contact support.";
            // Redirect to the vendor login page after displaying the message
            echo '<script>alert("Your account is not yet approved. Please wait for verification or contact support."); window.location.href = "vendor_login.php";</script>';
            exit;
        } else {
            // No matching record found, set login failure message
            $login_message = "Invalid email or password";
        }
    }
}

// Close the database connection
$conn->close();
?>