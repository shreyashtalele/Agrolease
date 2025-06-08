<?php
session_start();
include 'db.php';

// Check if vendor is logged in
if (!isset($_SESSION['vendorid'])) {
    header("Location: vendor_login.php");
    exit();
}

$vendorID = $_SESSION['vendorid'];

// Get vendor information
$vendorQuery = "SELECT * FROM vendor WHERE vendorid = '$vendorID'";
$vendorResult = mysqli_query($conn, $vendorQuery);
$vendor = mysqli_fetch_assoc($vendorResult);

// Get statistics for dashboard
$equipmentCount = mysqli_fetch_assoc(mysqli_query($conn, 
    "SELECT COUNT(*) as count FROM equipment WHERE vendor_id = '$vendorID'"))['count'];

$pendingRequests = mysqli_fetch_assoc(mysqli_query($conn, 
    "SELECT COUNT(*) as count FROM orders WHERE vendor_id = '$vendorID' AND status = 'pending'"))['count'];

$activeRentals = mysqli_fetch_assoc(mysqli_query($conn, 
    "SELECT COUNT(*) as count FROM orders WHERE vendor_id = '$vendorID' AND status = 'confirmed'"))['count'];

$overdueReturns = mysqli_fetch_assoc(mysqli_query($conn, 
    "SELECT COUNT(*) as count FROM orders WHERE vendor_id = '$vendorID' AND status = 'confirmed' 
     AND rental_end_date < CURDATE()"))['count'];

// Get recent activity
$activityQuery = "SELECT o.*, u.full_name, e.equipment_name 
                 FROM orders o 
                 JOIN user u ON o.user_id = u.userid 
                 JOIN equipment e ON o.equipment_id = e.equipment_id 
                 WHERE o.vendor_id = '$vendorID' 
                 ORDER BY o.created_at DESC LIMIT 4";
$activityResult = mysqli_query($conn, $activityQuery);
$recentActivities = mysqli_fetch_all($activityResult, MYSQLI_ASSOC);

// Get unread notifications count
$notificationCount = mysqli_fetch_assoc(mysqli_query($conn, 
    "SELECT COUNT(*) as count FROM orders WHERE vendor_id = '$vendorID' AND status = 'pending'"))['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Vendor Dashboard - Agrolease</title>

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <!-- Boxicons -->
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

  <style>
    :root {
      --primary: #1f7a1f;
      --primary-light: #e6f7e6;
      --primary-dark: #065f46;
      --secondary: #f59e0b;
      --dark: #1f2937;
      --light: #f3f4f6;
      --success: #10b981;
      --warning: #f59e0b;
      --danger: #ef4444;
      --white: #ffffff;
      --gray: #9ca3af;
      --gray-light: #e5e7eb;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background-color: var(--light);
      color: var(--dark);
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      line-height: 1.6;
    }

    header {
      background: var(--primary);
      color: var(--white);
      padding: 1rem 2rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      position: sticky;
      top: 0;
      z-index: 100;
    }

    .logo-block {
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    .logo-block img {
      width: 50px;
      height: 50px;
      border-radius: 12px;
      object-fit: cover;
      border: 2px solid var(--white);
    }

    .site-info h1 {
      font-size: 1.5rem;
      font-weight: 700;
      margin: 0;
    }

    .site-info p {
      font-size: 0.8rem;
      color: rgba(255, 255, 255, 0.8);
      font-weight: 300;
    }

    .header-links {
      display: flex;
      align-items: center;
      gap: 1.5rem;
    }

    .header-links a {
      color: var(--white);
      font-weight: 500;
      text-decoration: none;
      font-size: 0.95rem;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      transition: all 0.3s ease;
      padding: 0.5rem 0;
      position: relative;
    }

    .header-links a:hover {
      opacity: 0.9;
      transform: translateY(-2px);
    }

    .header-links a::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 0;
      height: 2px;
      background-color: var(--white);
      transition: width 0.3s ease;
    }

    .header-links a:hover::after {
      width: 100%;
    }

    .notification-badge {
      position: relative;
    }

    .badge {
      position: absolute;
      top: -8px;
      right: -8px;
      background-color: var(--danger);
      color: white;
      border-radius: 50%;
      width: 18px;
      height: 18px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.6rem;
      font-weight: bold;
    }

    main {
      flex: 1;
      padding: 2rem;
      max-width: 1400px;
      margin: 0 auto;
      width: 100%;
    }

    .welcome-section {
      background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
      color: var(--white);
      padding: 2rem;
      border-radius: 16px;
      margin-bottom: 2.5rem;
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
    }

    .welcome-section h2 {
      font-size: 1.8rem;
      font-weight: 700;
      margin: 0;
    }

    .welcome-section p {
      opacity: 0.9;
      font-weight: 300;
      margin: 0;
    }

    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1.5rem;
      margin-bottom: 2.5rem;
    }

    .stat-card {
      background-color: var(--white);
      padding: 1.5rem;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
      display: flex;
      align-items: center;
      gap: 1rem;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .stat-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .stat-icon {
      width: 50px;
      height: 50px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
    }

    .stat-icon.primary {
      background-color: var(--primary-light);
      color: var(--primary);
    }

    .stat-icon.warning {
      background-color: rgba(245, 158, 11, 0.1);
      color: var(--warning);
    }

    .stat-icon.success {
      background-color: rgba(16, 185, 129, 0.1);
      color: var(--success);
    }

    .stat-icon.danger {
      background-color: rgba(239, 68, 68, 0.1);
      color: var(--danger);
    }

    .stat-content h3 {
      font-size: 1.2rem;
      font-weight: 600;
      margin-bottom: 0.25rem;
    }

    .stat-content p {
      font-size: 0.9rem;
      color: var(--gray);
      margin: 0;
    }

    .actions-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 1.5rem;
      margin-bottom: 2rem;
    }

    .action-card {
      background-color: var(--white);
      padding: 1.5rem;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
      text-align: center;
      transition: all 0.3s ease;
      border: 1px solid var(--gray-light);
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 1rem;
    }

    .action-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
      border-color: var(--primary);
    }

    .action-icon {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      background-color: var(--primary-light);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.8rem;
      color: var(--primary);
    }

    .action-card h3 {
      font-size: 1.2rem;
      font-weight: 600;
      margin: 0;
    }

    .action-card p {
      font-size: 0.9rem;
      color: var(--gray);
      margin: 0;
    }

    .action-btn {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      background-color: var(--primary);
      color: var(--white);
      padding: 0.6rem 1.2rem;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 500;
      font-size: 0.95rem;
      transition: all 0.3s ease;
      margin-top: 0.5rem;
    }

    .action-btn:hover {
      background-color: var(--primary-dark);
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .recent-activity {
      background-color: var(--white);
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
      padding: 1.5rem;
      margin-top: 2rem;
    }

    .recent-activity h3 {
      font-size: 1.3rem;
      font-weight: 600;
      margin-bottom: 1.5rem;
      color: var(--dark);
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .activity-list {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }

    .activity-item {
      display: flex;
      align-items: flex-start;
      gap: 1rem;
      padding-bottom: 1rem;
      border-bottom: 1px solid var(--gray-light);
    }

    .activity-item:last-child {
      border-bottom: none;
      padding-bottom: 0;
    }

    .activity-icon {
      width: 40px;
      height: 40px;
      border-radius: 8px;
      background-color: var(--primary-light);
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--primary);
      font-size: 1rem;
      flex-shrink: 0;
    }

    .activity-content {
      flex: 1;
    }

    .activity-content p {
      font-size: 0.9rem;
      margin: 0;
    }

    .activity-content p strong {
      font-weight: 600;
    }

    .activity-time {
      font-size: 0.8rem;
      color: var(--gray);
      margin-top: 0.25rem;
      display: flex;
      align-items: center;
      gap: 0.25rem;
    }

    footer {
      background-color: var(--primary);
      color: var(--white);
      text-align: center;
      padding: 1.5rem;
      font-size: 0.9rem;
      margin-top: 2rem;
    }

    footer p {
      margin: 0;
      opacity: 0.8;
    }

    @media (max-width: 768px) {
      header {
        padding: 1rem;
        flex-direction: column;
        gap: 1rem;
      }

      .logo-block {
        flex-direction: column;
        text-align: center;
      }

      .header-links {
        width: 100%;
        justify-content: center;
      }

      main {
        padding: 1rem;
      }

      .welcome-section {
        padding: 1.5rem;
      }

      .stats-grid {
        grid-template-columns: 1fr 1fr;
      }
    }

    @media (max-width: 480px) {
      .stats-grid {
        grid-template-columns: 1fr;
      }

      .actions-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>

<header>
  <div class="logo-block">
    <img src="img/logo.jpeg" alt="Agrolease Logo" />
    <div class="site-info">
      <h1>Agrolease</h1>
      <p>Harvest Success with Innovative Farming Solutions</p>
    </div>
  </div>
  <nav class="header-links">
  
    <a href="logout.php">
      <i class="fas fa-sign-out-alt"></i> Logout
    </a>
  </nav>
</header>

<main>
  <section class="welcome-section">
    <h2>Welcome back, <?= htmlspecialchars($vendor['vendor_name']) ?>!</h2>
    <p>Here's what's happening with your business today</p>
  </section>

  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-icon primary">
        <i class="fas fa-tractor"></i>
      </div>
      <div class="stat-content">
        <h3><?= $equipmentCount ?></h3>
        <p>Total Equipment</p>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon warning">
        <i class="fas fa-handshake"></i>
      </div>
      <div class="stat-content">
        <h3><?= $pendingRequests ?></h3>
        <p>Pending Requests</p>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon success">
        <i class="fas fa-check-circle"></i>
      </div>
      <div class="stat-content">
        <h3><?= $activeRentals ?></h3>
        <p>Active Rentals</p>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon danger">
        <i class="fas fa-exclamation-circle"></i>
      </div>
      <div class="stat-content">
        <h3><?= $overdueReturns ?></h3>
        <p>Overdue Returns</p>
      </div>
    </div>
  </div>

  <h3 style="font-size: 1.5rem; margin-bottom: 1.5rem; color: var(--dark);">Quick Actions</h3>
  <div class="actions-grid">
    <div class="action-card">
      <div class="action-icon">
        <i class="fas fa-tractor"></i>
      </div>
      <h3>Add Equipment</h3>
      <p>List new  equipment for rental</p>
      <a href="Equipment.php" class="action-btn">
        <i class="fas fa-plus"></i> Add Now
      </a>
    </div>
    <div class="action-card">
      <div class="action-icon">
        <i class="fas fa-handshake"></i>
      </div>
      <h3>Rent Requests</h3>
      <p>Manage incoming rental requests</p>
      <a href="rentdashboard.php" class="action-btn">
        <i class="fas fa-eye"></i> View Requests
      </a>
    </div>
    <div class="action-card">
      <div class="action-icon">
        <i class="fas fa-boxes"></i>
      </div>
      <h3>Current Stock</h3>
      <p>View and manage your inventory</p>
      <a href="currentstock.php" class="action-btn">
        <i class="fas fa-box-open"></i> View Stock
      </a>
    </div>
    <div class="action-card">
      <div class="action-icon">
        <i class="fas fa-chart-line"></i>
      </div>
      <h3>View Reports</h3>
      <p>Analyze your business performance</p>
      <a href="reports.php" class="action-btn">
        <i class="fas fa-chart-pie"></i> Generate Reports
      </a>
    </div>
  </div>

  <div class="recent-activity">
    <h3><i class="fas fa-history"></i> Recent Activity</h3>
    <div class="activity-list">
      <?php if (!empty($recentActivities)): ?>
        <?php foreach ($recentActivities as $activity): ?>
          <div class="activity-item">
            <div class="activity-icon">
              <?php if ($activity['status'] == 'pending'): ?>
                <i class="fas fa-exclamation-circle"></i>
              <?php elseif ($activity['status'] == 'confirmed'): ?>
                <i class="fas fa-check-circle"></i>
              <?php elseif ($activity['status'] == 'completed'): ?>
                <i class="fas fa-dollar-sign"></i>
              <?php else: ?>
                <i class="fas fa-tractor"></i>
              <?php endif; ?>
            </div>
            <div class="activity-content">
              <p>
                <strong>
                  <?= ucfirst($activity['status']) ?> rental
                </strong> 
                for <?= htmlspecialchars($activity['equipment_name']) ?> by <?= htmlspecialchars($activity['full_name']) ?>
              </p>
              <div class="activity-time">
                <i class="far fa-clock"></i> 
                <?= date("M j, Y g:i A", strtotime($activity['created_at'])) ?>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="activity-item">
          <div class="activity-icon">
            <i class="fas fa-info-circle"></i>
          </div>
          <div class="activity-content">
            <p>No recent activity found</p>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
</main>

<footer>
  <p>&copy; <?= date('Y') ?> Agrolease. All rights reserved.</p>
</footer>

</body>
</html>