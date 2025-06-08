<?php
include 'db.php'; // Include your database connection file

// Check if the register button is clicked
if (isset($_POST['registerBtn'])) {
    // Retrieve user input from the registration form
    $vendor_name = $_POST['vendor_name'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $house_name = $_POST['house_name'];
    $area = $_POST['area'];
    $village = $_POST['village'];
    $taluka = $_POST['taluka'];
    $pincode = $_POST['pincode'];
    $district = $_POST['district'];
    $state = $_POST['state'];

    // Insert vendor data into the database
    $sql = "INSERT INTO vendor (vendor_name, email, mobile, password, housename, area, village, taluka, pincode, district, state, status) 
            VALUES ('$vendor_name', '$email', '$mobile', '$password', '$house_name', '$area', '$village', '$taluka', '$pincode', '$district', '$state', 'pending')";
    
    if ($conn->query($sql) === TRUE) {
        // Registration successful
        echo '<script>alert("Thank You For Registration. You Will get Notified When Your verification Completes."); window.location.href = "vendor_login.php";</script>';

        // Send verification request to admin (You can implement this part separately)
    } else {
        // Registration failed
        echo '<script>alert("Error: Registration failed");</script>';
    }
}

// Close the database connection
$conn->close();
?>