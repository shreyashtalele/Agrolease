<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Payment</title>
    <style>
        /* Add your CSS styles here */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .invoice {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 20px;
        }
        .invoice th, .invoice td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        .invoice th {
            background-color: #f2f2f2;
        }
        .confirm-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .confirm-btn:hover {
            background-color: #45a049;
        }
        header {
            background-color: #006400;
            color: #fff;
            padding: 20px;
            text-align: center;
        }
        .logo-container {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            overflow: hidden;
            margin: 0 auto 20px;
        }
        .logo {
            width: 100%;
            height: auto;
            display: block;
        }
    </style>
</head>
<body>
<header>
    <div class="logo-container">
        <img src="img/logo.jpeg" alt="Logo" class="logo">
    </div>
    <h1>Agrolease</h1>
    <p>Harvest Success with Our Innovative Farming Solutions</p>
</header>
<div class="container">
    <h2>Confirm Payment</h2>
    <?php
    // Start session
    session_start();

    // Include database connection file
    include 'db.php';

    // Check if order_id is set in the URL
    if (!isset($_GET['order_id'])) {
        // Redirect to an error page or display an error message
        echo "Order ID is missing.";
        exit;
    }

    // Fetch order details from the database
    $orderID = $_GET['order_id'];
    $query = "SELECT o.*, u.full_name
              FROM orders o
              INNER JOIN user u ON o.user_id = u.userid
              WHERE o.order_id = '$orderID'";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        // Handle error if query fails
        echo "Error: " . mysqli_error($conn);
        exit;
    }

    // Display order details in an invoice-like structure
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        ?>
        <table class="invoice">
            <tr>
                <th>Bill No</th>
                <td><?php echo $row['order_id']; ?></td>
            </tr>
            <tr>
                <th>User Name</th>
                <td><?php echo $row['full_name']; ?></td>
            </tr>
            <tr>
                <th>Price</th>
                <td><?php echo $row['price']; ?></td>
            </tr>
            <tr>
                <th>Rental Start Date</th>
                <td><?php echo $row['rental_start_date']; ?></td>
            </tr>
            <tr>
                <th>Rental End Date</th>
                <td><?php echo $row['rental_end_date']; ?></td>
            </tr>
            <tr>
                <th>Order Date</th>
                <td><?php echo $row['created_at']; ?></td>
            </tr>
        </table>
        <form method="post" action="updatepaymentstatus.php">
            <input type="hidden" name="order_id" value="<?php echo $orderID; ?>">
            <button type="submit" class="confirm-btn">Confirm Payment</button>
        </form>
        <?php
    } else {
        // Display a message if no order found
        echo "No order found with the provided ID.";
    }
    ?>
</div>
</body>
</html>
