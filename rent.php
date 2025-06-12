<?php
session_start();
include 'db.php';

// Check if equipment_id is set either via GET or session
if (isset($_GET['equipment_id'])) {
    $_SESSION['equipment_id'] = $_GET['equipment_id'];
}

if (!isset($_SESSION['equipment_id'])) {
    echo "<script>alert('Equipment ID is missing.'); window.location.href = 'error.php';</script>";
    exit;
}

$equipmentId = $_SESSION['equipment_id'];

// Get user details from session
if (!isset($_SESSION['user'])) {
    // Redirect to login if user not logged in
    header('Location: login.php');
    exit;
}

$userDetails = $_SESSION['user'];

// Fetch equipment details
$query = "SELECT * FROM equipment WHERE equipment_id = '$equipmentId'";
$result = mysqli_query($conn, $query);
if (!$result) {
    echo "Error: " . mysqli_error($conn);
    exit;
}

$row = mysqli_fetch_assoc($result);

// Default rental dates and duration
$rentalStartDate = date('Y-m-d');
$rentalEndDate = date('Y-m-d', strtotime('+1 day'));
$rentalDuration = 1;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $quantity = intval($_POST['quantity']);
    $rentalStartDate = $_POST['rental_start_date'];
    $rentalEndDate = $_POST['rental_end_date'];
    $rentalDuration = intval($_POST['rental_duration']);

    // Basic validation
    if ($quantity < 1 || $quantity > $row['total_stock']) {
        $_SESSION['error_message'] = "Invalid quantity selected.";
        header("Location: rent.php");
        exit;
    }
    if (strtotime($rentalEndDate) < strtotime($rentalStartDate)) {
        $_SESSION['error_message'] = "End date cannot be before start date.";
        header("Location: rent.php");
        exit;
    }

    // Calculate total price
    $price = $quantity * $row['rent_per_day'] * $rentalDuration;

    $userId = $userDetails['userid'];
    $vendorId = $row['vendor_id'];

    // Insert order
    $insertQuery = "INSERT INTO orders (user_id, equipment_id, vendor_id, quantity, rental_start_date, rental_end_date, rental_duration, price)
                    VALUES ('$userId', '$equipmentId', '$vendorId', '$quantity', '$rentalStartDate', '$rentalEndDate', '$rentalDuration', '$price')";

    if (mysqli_query($conn, $insertQuery)) {
        $_SESSION['success_message'] = "Your request is sent to Vendor, please check notifications section.";
        header("Location: rent.php");
        exit;
    } else {
        $_SESSION['error_message'] = "Error: " . mysqli_error($conn);
        header("Location: rent.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Rent Equipment - Agrolease</title>

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet" />

<!-- Google Material Icons -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />

<style>
    body {
        font-family: 'Roboto', Arial, sans-serif;
        background-color: #f9fafb;
        margin: 0;
        padding: 0;
    }
    header {
        background-color: #006400;
        color: #fff;
        padding: 20px 40px;
        display: flex;
        align-items: center;
    }
    .logo-container {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        overflow: hidden;
        margin-right: 15px;
    }
    .logo-container img {
        width: 100%;
        height: auto;
        display: block;
    }
    .header-content {
        flex-grow: 1;
    }
    .website-name {
        margin: 0;
        font-size: 28px;
        font-weight: 700;
        letter-spacing: 1.2px;
    }
    .tagline {
        margin: 4px 0 0 0;
        font-size: 14px;
        font-style: italic;
        opacity: 0.85;
    }
    .header-link {
        color: #b2d8b2;
        text-decoration: none;
        background-color: #004d00;
        padding: 8px 14px;
        border-radius: 5px;
        font-weight: 500;
        transition: background-color 0.3s ease;
    }
    .header-link:hover {
        background-color: #003300;
    }
    .container {
        max-width: 600px;
        margin: 40px auto;
        background: white;
        padding: 30px 40px;
        border-radius: 10px;
        box-shadow: 0 8px 15px rgba(0,0,0,0.1);
    }
    h2 {
        margin-bottom: 20px;
        font-weight: 700;
        color: #004d00;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    label {
        font-weight: 600;
        display: block;
        margin-bottom: 6px;
        color: #333;
    }
    input[type=text], input[type=email], input[type=date], select {
        width: 100%;
        padding: 10px 12px;
        border: 1.5px solid #ccc;
        border-radius: 6px;
        margin-bottom: 20px;
        font-size: 15px;
        transition: border-color 0.3s ease;
    }
    input[type=text]:focus, input[type=email]:focus, input[type=date]:focus, select:focus {
        border-color: #006400;
        outline: none;
    }
    input[readonly] {
        background-color: #e9ecef;
        cursor: not-allowed;
    }
    .submit-button {
        background-color: #006400;
        color: white;
        border: none;
        padding: 14px;
        font-size: 18px;
        border-radius: 8px;
        cursor: pointer;
        width: 100%;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: background-color 0.3s ease;
    }
    .submit-button:hover {
        background-color: #004d00;
    }
    .total-amount {
        font-weight: 700;
        font-size: 18px;
        color: #006400;
        margin-top: -10px;
        margin-bottom: 20px;
    }
    .message {
        max-width: 600px;
        margin: 20px auto;
        padding: 15px;
        border-radius: 8px;
        font-weight: 700;
        text-align: center;
    }
    .success-message {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    .error-message {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
</style>

<script>
    function updateTotalAmount() {
        const rentPerDay = <?php echo json_encode($row['rent_per_day']); ?>;
        const quantity = parseInt(document.getElementById('quantity').value);
        const startDate = document.getElementById('rental_start_date').value;
        const endDate = document.getElementById('rental_end_date').value;

        if (!startDate || !endDate) {
            document.getElementById('rental_duration').value = 0;
            document.getElementById('total_amount').value = '0.00';
            return;
        }

        const start = new Date(startDate);
        const end = new Date(endDate);

        // Calculate duration in days (inclusive)
        const diffTime = end - start;
        const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24)) + 1;

        if(diffDays <= 0){
            document.getElementById('rental_duration').value = 0;
            document.getElementById('total_amount').value = '0.00';
            return;
        }

        document.getElementById('rental_duration').value = diffDays;

        const total = rentPerDay * quantity * diffDays;
        document.getElementById('total_amount').value = total.toFixed(2);
    }
    window.addEventListener('DOMContentLoaded', updateTotalAmount);
</script>

</head>
<body>

<header>
    <div class="logo-container">
        <img src="img/logo.jpeg" alt="Agrolease Logo" />
    </div>
    <div class="header-content">
        <h1 class="website-name">Agrolease</h1>
        <p class="tagline">Harvest Success with Our Innovative Farming Solutions</p>
    </div>
    <a href="userequipment.php" class="header-link">
        <span class="material-icons">arrow_back</span> Back
    </a>
</header>

<?php
// Show success or error message
if (isset($_SESSION['success_message'])) {
    echo '<div class="message success-message">' . $_SESSION['success_message'] . '</div>';
    unset($_SESSION['success_message']);
} elseif (isset($_SESSION['error_message'])) {
    echo '<div class="message error-message">' . $_SESSION['error_message'] . '</div>';
    unset($_SESSION['error_message']);
}
?>

<div class="container">
    <form method="post" action="rent.php" oninput="updateTotalAmount()">
        <h2><span class="material-icons">person</span> User Details</h2>
        <label for="name">Name</label>
        <input type="text" id="name" readonly value="<?php echo htmlspecialchars($userDetails['name']); ?>" />

        <label for="email">Email</label>
        <input type="email" id="email" readonly value="<?php echo htmlspecialchars($userDetails['email']); ?>" />

        <label for="mobile">Mobile</label>
        <input type="text" id="mobile" readonly value="<?php echo htmlspecialchars($userDetails['mobile']); ?>" />

        <h2><span class="material-icons">build</span> Equipment Details</h2>

        <label for="equipment_name">Equipment Name</label>
        <input type="text" id="equipment_name" readonly value="<?php echo htmlspecialchars($row['equipment_name']); ?>" />

        <label for="rent_per_day">Rent per Day</label>
        <input type="text" id="rent_per_day" readonly value="<?php echo htmlspecialchars($row['rent_per_day']); ?>" />

        <label for="quantity">Quantity</label>
        <select name="quantity" id="quantity" required>
            <?php
            for ($i = 1; $i <= $row['total_stock']; $i++) {
                echo "<option value='$i'>$i</option>";
            }
            ?>
        </select>

        <h2><span class="material-icons">calendar_today</span> Rental Duration</h2>

        <label for="rental_start_date">Start Date</label>
        <input type="date" id="rental_start_date" name="rental_start_date" required value="<?php echo $rentalStartDate; ?>" />

        <label for="rental_end_date">End Date</label>
        <input type="date" id="rental_end_date" name="rental_end_date" required value="<?php echo $rentalEndDate; ?>" />

        <label for="rental_duration">Rental Duration (days)</label>
        <input type="text" id="rental_duration" name="rental_duration" readonly value="<?php echo $rentalDuration; ?>" />

        <label for="total_amount">Total Amount (₹)</label>
        <input type="text" id="total_amount" name="total_amount" readonly value="<?php echo $row['rent_per_day']; ?>" />

        <button type="submit" class="submit-button">
            <span class="material-icons">check_circle</span> Confirm Rent
        </button>
    </form>
</div>

<footer style="background-color:#006400; color:#fff; text-align:center; padding:20px; margin-top:50px;">
    &copy; <?php echo date("Y"); ?> Agrolease. All rights reserved.
</footer>

</body>
</html>
