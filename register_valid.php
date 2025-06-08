<?php
// Include the database connection file
include 'db.php';

// Check if the register button is clicked
if (isset($_POST['registerBtn'])) {
    // Retrieve user input from the registration form
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $house_name = $_POST['house_name'];
    $area = $_POST['area'];
    $village = $_POST['village'];
    $pincode = $_POST['pincode'];
    $taluka = $_POST['taluka'];
    $district = $_POST['district'];
    $state = $_POST['state'];

    // Perform validation if necessary (e.g., password match)

    // Insert user data into the database
    $sql = "INSERT INTO user (full_name, email, mobile, password, house_name, area, village, pincode, taluka, district, state) 
            VALUES ('$full_name', '$email', '$mobile', '$password', '$house_name', '$area', '$village', '$pincode', '$taluka', '$district', '$state')";
    
    if ($conn->query($sql) === TRUE) {
        // Registration successful
        echo '<script>alert("Registration successful"); window.location.href = "login.php";</script>';
    } else {
        // Registration failed
        echo '<script>alert("Error: Registration failed");</script>';
    }
}

// Close the database connection
$conn->close();
?>