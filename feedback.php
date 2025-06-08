<?php
// Start session
session_start();

// Include database connection file
include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['userid'])) {
    // Redirect to login page or display an error message
    echo "Please log in to submit feedback.";
    exit;
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user ID from session
    $user_id = $_SESSION['userid'];

    // Retrieve comment from the form
    $comment = $_POST['comment'];

    // Insert feedback into the database
    $insert_query = "INSERT INTO feedback (userid, comment) VALUES ('$user_id', '$comment')";
    $insert_result = mysqli_query($conn, $insert_query);

    if (!$insert_result) {
        // Handle error if insertion fails
        echo "Error: " . mysqli_error($conn);
        exit;
    }
}

// Fetch existing feedback from the database
$select_query = "SELECT * FROM feedback";
$result = mysqli_query($conn, $select_query);

if (!$result) {
    // Handle error if query fails
    echo "Error: " . mysqli_error($conn);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Page</title>
  <style>
    /* styles.css */

body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
}

header {
    background-color: #006400;
    color: #f2f2f2;
    padding: 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.logo-container {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    overflow: hidden;
}

.logo {
    width: 100%;
    height: auto;
    display: block;
}

.header-content {
    flex-grow: 1;
    text-align: center;
}

.website-name {
    margin: 0;
    font-size: 24px;
}

.tagline {
    margin: 5px 0 0;
    font-size: 14px;
}

.header-links {
    display: flex;
    align-items: center;
}

.header-link {
    color: #f2f2f2;
    text-decoration: none;
    margin-left: 15px;
    display: flex;
    align-items: center;
}

.header-link img {
    width: 24px;
    height: 24px;
    margin-right: 5px;
}

.container {
    max-width: 800px;
    margin: 20px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.container h2 {
    text-align: center;
    margin-bottom: 30px;
    color: #333;
}

form textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 20px;
    border: 1px solid #ddd;
    border-radius: 5px;
    resize: none;
}

form button {
    background-color: #006400;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
}

form button:hover {
    background-color: #004d00;
}

.feedback-list {
    margin-top: 30px;
}

.feedback-item {
    border-bottom: 1px solid #ddd;
    padding: 20px 0;
}

.feedback-item:last-child {
    border-bottom: none;
}

.feedback-item p {
    margin: 5px 0;
}

.footer {
    background-color: #006400;
    color: #f2f2f2;
    padding: 20px;
    text-align: center;
    position: fixed;
    bottom: 0;
    width: 100%;
}

    </style>
</head>
<body>
<header>
    <div class="logo-container">
        <img src="img/logo.jpeg" alt="Logo" class="logo">
    </div>
    <div class="header-content">
        <h1 class="website-name">Agrolease</h1>
        <p class="tagline">Harvest Success with Our Innovative Farming Solutions</p>
    </div>
    <div class="header-links">
        <a href="dashboard.php" class="header-link">Dashboard</a>
        <a href="logout.php" class="header-link">Logout</a>
    </div>
</header>

<div class="container">
    <h2>Leave a Feedback</h2>
    <form method="POST">
        <textarea name="comment" rows="4" placeholder="Enter your feedback here..." required></textarea><br>
        <button type="submit">Submit Feedback</button>
    </form>

    <h2>Existing Feedback</h2>
    <div class="feedback-list">
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <div class="feedback-item">
                <p><strong>User ID:</strong> <?php echo $row['userid']; ?></p>
                <p><strong>Comment:</strong> <?php echo $row['comment']; ?></p>
                <p><strong>Date:</strong> <?php echo $row['feedback_date']; ?></p>
            </div>
        <?php } ?>
    </div>
</div>

<footer class="footer">
    <p>&copy; 2024 Agrolease. All rights reserved.</p>
</footer>

</body>
</html>
