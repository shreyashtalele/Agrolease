<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #eef2f3;
      margin: 0;
      padding: 0;
    }

    header {
      background-color: #004d00;
      color: #fff;
      padding: 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .logo-container {
      width: 70px;
      height: 70px;
      border-radius: 50%;
      overflow: hidden;
      border: 2px solid #fff;
    }

    .logo {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .header-content {
      flex-grow: 1;
      padding-left: 20px;
    }

    .website-name {
      margin: 0;
      font-size: 26px;
      font-weight: bold;
    }

    .tagline {
      margin: 5px 0 0;
      font-size: 13px;
      font-style: italic;
    }

    .header-link {
      color: #fff;
      text-decoration: none;
      padding: 10px 18px;
      background-color: #006400;
      border-radius: 5px;
      font-weight: bold;
      transition: background 0.3s;
    }

    .header-link:hover {
      background-color: #003300;
    }

    .container {
      max-width: 900px;
      margin: 30px auto;
      padding: 25px;
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }

    h2 {
      margin-bottom: 25px;
      color: #004d00;
      border-bottom: 2px solid #ddd;
      padding-bottom: 10px;
    }

    .vendor-request {
      border: 1px solid #ccc;
      padding: 20px;
      margin-bottom: 20px;
      border-radius: 10px;
      background-color: #fdfdfd;
      transition: transform 0.2s ease;
    }

    .vendor-request:hover {
      transform: scale(1.01);
    }

    .vendor-request p {
      margin: 6px 0;
      font-size: 15px;
    }

    .vendor-request label {
      font-weight: 600;
      margin-right: 20px;
    }

    .vendor-request input[type="radio"] {
      margin-right: 6px;
    }

    .vendor-request button {
      background-color: #006400;
      color: #fff;
      border: none;
      padding: 10px 18px;
      border-radius: 6px;
      cursor: pointer;
      font-size: 14px;
      font-weight: bold;
      transition: background-color 0.3s ease;
      margin-top: 10px;
    }

    .vendor-request button:hover {
      background-color: #004d00;
    }

    .footer {
      text-align: center;
      padding: 15px 0;
      background-color: #004d00;
      color: #fff;
      margin-top: 40px;
    }

    @media (max-width: 600px) {
      header {
        flex-direction: column;
        align-items: flex-start;
      }
      .header-content {
        padding-left: 0;
        margin-top: 10px;
      }
      .container {
        margin: 15px;
        padding: 15px;
      }
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
    <a href="logout.php" class="header-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
  </header>

  <div class="container">
    <h2><i class="fas fa-user-clock"></i> Pending Vendor Requests</h2>
    <div id="vendor-requests">
      <?php
      include 'db.php';

      // If individual form is submitted
      if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['vendor_id']) && isset($_POST['action'])) {
          $vendorId = $_POST['vendor_id'];
          $action = $_POST['action'];
          $status = ($action == "approved") ? "approved" : "rejected";

          $updateSql = "UPDATE vendor SET status = '$status' WHERE vendorid = $vendorId";
          $conn->query($updateSql);
      }

      $sql = "SELECT * FROM vendor WHERE status = 'pending'";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
              $vendorId = $row['vendorid'];
              $vendorName = $row['vendor_name'];
              $email = $row['email'];
              $village = $row['village'];
              $taluka = $row['taluka'];

              echo '<form method="POST" action="">';
              echo '<div class="vendor-request">';
              echo '<input type="hidden" name="vendor_id" value="' . $vendorId . '">';
              echo '<p><strong>Name:</strong> ' . $vendorName . '</p>';
              echo '<p><strong>Email:</strong> ' . $email . '</p>';
              echo '<p><strong>Village:</strong> ' . $village . '</p>';
              echo '<p><strong>Taluka:</strong> ' . $taluka . '</p>';
              echo '<label><input type="radio" name="action" value="approved" required> Approve</label>';
              echo '<label><input type="radio" name="action" value="rejected" required> Reject</label>';
              echo '<br>';
              echo '<button type="submit"><i class="fas fa-check-circle"></i> Submit</button>';
              echo '</div>';
              echo '</form>';
          }
      } else {
          echo '<p>No pending vendor requests found.</p>';
      }

      $conn->close();
      ?>
    </div>
  </div>

  <footer class="footer">
    <p>&copy; 2024 Agrolease. All rights reserved.</p>
  </footer>
</body>
</html>
