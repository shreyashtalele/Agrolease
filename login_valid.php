<?php
include 'db.php';
session_start();

// Retrieve user input from the login form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query the database to check if the email exists
    $check_email_query = "SELECT * FROM user WHERE email = '$email'";
    $check_email_result = $conn->query($check_email_query);

    if ($check_email_result && $check_email_result->num_rows > 0) {
        // Email exists, fetch user details
        $user_row = $check_email_result->fetch_assoc();
        $userid = $user_row['userid'];
        $name = $user_row['full_name']; // Corrected to 'full_name'
        $email = $user_row['email'];
        $mobile = $user_row['mobile'];
        $area = $user_row['area'];
        $village = $user_row['village'];
        $taluka = $user_row['taluka'];
        $pincode = $user_row['pincode'];
        $house_name = $user_row['house_name']; // Retrieve house_name from the database
        
        // Check if the password matches
        if ($password == $user_row['password']) {
            // Password is correct, set login success flag
            $_SESSION['user'] = [
                'userid' => $userid,
                'name' => $name,
                'email' => $email,
                'mobile' => $mobile,
                'house_name' => $house_name, // Store house_name in session
                'area' => $area,
                'village' => $village,
                'taluka' => $taluka,
                'pincode' => $pincode
            ];

            // Redirect to the dashboard
            echo "<script>alert('Login successful!'); window.location.href = 'dashboard.php';</script>";
            exit;
        } else {
            // Password does not match, redirect to login page with error message
            echo "<script>alert('Incorrect password'); window.location.href = 'login.php';</script>";
            exit;
        }
    } else {
        // Email does not exist in the database, redirect to login page with error message
        echo "<script>alert('Email not found'); window.location.href = 'login.php';</script>";
        exit;
    }
}

// Close the database connection
$conn->close();
?>