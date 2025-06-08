<?php
include 'db.php'; // Include the database connection file

// Check if the login form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query the database to check if the admin is valid
    $sql = "SELECT * FROM admin WHERE emailid = '$email' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Admin is valid, redirect to requests.php
        echo '<script>alert("Successful login"); window.location.href = "request.php";</script>';
        exit(); // Exit to prevent further execution
    } else {
        // Admin is not valid, display an error message
        echo '<script>alert("Invalid email or password"); window.location.href = "admin_login.php";</script>';
    }
}

// Close the database connection
$conn->close();
?>