<?php
// Include the database connection file
include 'db.php';

// Start the session
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $equipment_name = $_POST['equipment_name'];
    $rent_per_day = $_POST['rent_per_day'];
    $total_stock = $_POST['total_stock'];
    $category_name = $_POST['category'];
    $description = $_POST['description'];

    // Fetch vendor_id from session
    if (isset($_SESSION['vendorid'])) {
        $vendor_id = $_SESSION['vendorid'];
    } else {
        echo "Vendor ID not found in session";
        exit;
    }

    // Fetch category_id based on category name
    $category_query = "SELECT categoryid FROM category WHERE categoryname = '$category_name'";
    $category_result = $conn->query($category_query);
    if ($category_result->num_rows > 0) {
        $category_row = $category_result->fetch_assoc();
        $category_id = $category_row['categoryid'];
    } else {
        echo "Category not found: $category_name";
        exit;
    }

    // Check if an image is uploaded
    if(isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        // Read image data
        $image_data = file_get_contents($_FILES['image']['tmp_name']);

        // Escape image data to prevent SQL injection
        $escaped_image_data = $conn->real_escape_string($image_data);

        // Insert data into the equipment table along with image data
        $insert_query = "INSERT INTO equipment (vendor_id, category_id, equipment_name, rent_per_day, total_stock, description, image_data) 
                         VALUES ('$vendor_id', '$category_id', '$equipment_name', '$rent_per_day', '$total_stock', '$description', '$escaped_image_data')";

        if ($conn->query($insert_query) === TRUE) {
            // Close the database connection
            $conn->close();
            
            // Show alert on window screen
            echo "<script>alert('Equipment added successfully'); window.location.href = 'Equipment.php';</script>";
            exit;
        } else {
            echo "Error: " . $insert_query . "<br>" . $conn->error;
        }
    } else {
        echo "Error uploading image: " . $_FILES['image']['error'];
    }
}
?>