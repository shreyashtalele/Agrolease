<?php
session_start();
include 'db.php';

if (!isset($_SESSION['vendorid'])) {
    echo "Vendor ID is missing.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = $_POST['order_id'];
    if (isset($_POST['accept'])) {
        $update_query = "UPDATE orders SET status = 'confirmed' WHERE order_id = '$order_id'";
        if (mysqli_query($conn, $update_query)) {
            $update_stock_query = "UPDATE equipment SET total_stock = total_stock - 1 WHERE equipment_id IN (SELECT equipment_id FROM orders WHERE order_id = '$order_id')";
            mysqli_query($conn, $update_stock_query);
        }
    } elseif (isset($_POST['deny'])) {
        $update_query = "UPDATE orders SET status = 'cancelled' WHERE order_id = '$order_id'";
        mysqli_query($conn, $update_query);
    }
    header("Location: rentdashboard.php");
    exit;
}

$vendorId = $_SESSION['vendorid'];
$query = "SELECT o.*, u.full_name AS user_name, e.equipment_name
          FROM orders o
          INNER JOIN user u ON o.user_id = u.userid
          INNER JOIN equipment e ON o.equipment_id = e.equipment_id
          WHERE o.vendor_id = '$vendorId' AND o.status = 'pending'";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Pending Orders | Agrolease</title>
  <link rel="icon" href="img/logo.jpeg" type="image/jpeg">

  <!-- Font Awesome Icons CDN -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  
  <style>
    :root {
      --primary-color: #2e7d32;
      --primary-light: #4caf50;
      --primary-dark: #1b5e20;
      --secondary-color: #f5f5f5;
      --text-dark: #333;
      --text-light: #666;
      --white: #ffffff;
      --danger: #d32f2f;
      --warning: #ffa000;
      --success: #388e3c;
      --border-radius: 12px;
      --box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
      --transition: all 0.3s ease;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f9f9f9;
      color: var(--text-dark);
      line-height: 1.6;
    }

    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px;
    }

    /* Header Styles */
    header {
      background-color: var(--white);
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      padding: 15px 30px;
      position: sticky;
      top: 0;
      z-index: 100;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .logo-container {
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .logo {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      object-fit: cover;
    }

    .brand-name {
      font-size: 1.5rem;
      font-weight: 600;
      color: var(--primary-dark);
    }

    .header-actions {
      display: flex;
      gap: 15px;
      align-items: center;
    }

    .btn {
      padding: 10px 20px;
      border-radius: var(--border-radius);
      font-weight: 500;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      cursor: pointer;
      transition: var(--transition);
      text-decoration: none;
      border: none;
    }

    .btn-outline {
      background: transparent;
      border: 1px solid var(--primary-color);
      color: var(--primary-color);
    }

    .btn-outline:hover {
      background: var(--primary-color);
      color: var(--white);
    }

    .btn-primary {
      background: var(--primary-color);
      color: var(--white);
    }

    .btn-primary:hover {
      background: var(--primary-dark);
      transform: translateY(-2px);
    }

    /* Page Title */
    .page-header {
      margin: 30px 0;
      text-align: center;
    }

    .page-title {
      font-size: 2rem;
      color: var(--primary-dark);
      margin-bottom: 10px;
      position: relative;
      display: inline-block;
    }

    .page-title::after {
      content: '';
      position: absolute;
      bottom: -8px;
      left: 50%;
      transform: translateX(-50%);
      width: 80px;
      height: 3px;
      background: var(--primary-light);
      border-radius: 3px;
    }

    .page-subtitle {
      color: var(--text-light);
      font-weight: 400;
    }

    /* Orders Grid */
    .orders-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
      gap: 25px;
      margin-top: 40px;
    }

    /* Order Card */
    .order-card {
      background: var(--white);
      border-radius: var(--border-radius);
      box-shadow: var(--box-shadow);
      overflow: hidden;
      transition: var(--transition);
    }

    .order-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 35px rgba(0, 0, 0, 0.12);
    }

    .order-header {
      padding: 20px;
      background: var(--primary-light);
      color: var(--white);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .order-user {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .user-avatar {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: var(--white);
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--primary-dark);
      font-weight: 600;
    }

    .user-name {
      font-weight: 500;
    }

    .order-id {
      font-size: 0.85rem;
      opacity: 0.9;
    }

    .order-body {
      padding: 20px;
    }

    .order-detail {
      display: flex;
      margin-bottom: 15px;
      align-items: flex-start;
    }

    .detail-icon {
      width: 36px;
      height: 36px;
      background: rgba(46, 125, 50, 0.1);
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--primary-color);
      margin-right: 15px;
      flex-shrink: 0;
    }

    .detail-content {
      flex: 1;
    }

    .detail-label {
      font-size: 0.85rem;
      color: var(--text-light);
      margin-bottom: 2px;
    }

    .detail-value {
      font-weight: 500;
    }

    .price {
      font-size: 1.2rem;
      color: var(--primary-dark);
      font-weight: 600;
    }

    .order-footer {
      padding: 0 20px 20px;
      display: flex;
      gap: 15px;
    }

    .btn-accept {
      background: var(--success);
      color: var(--white);
      flex: 1;
    }

    .btn-accept:hover {
      background: #2e7d32;
    }

    .btn-deny {
      background: var(--danger);
      color: var(--white);
      flex: 1;
    }

    .btn-deny:hover {
      background: #b71c1c;
    }

    /* Empty State */
    .empty-state {
      text-align: center;
      padding: 60px 20px;
      grid-column: 1 / -1;
    }

    .empty-icon {
      font-size: 5rem;
      color: var(--primary-light);
      opacity: 0.3;
      margin-bottom: 20px;
    }

    .empty-text {
      font-size: 1.2rem;
      color: var(--text-light);
      margin-bottom: 20px;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .orders-grid {
        grid-template-columns: 1fr;
      }
      
      header {
        flex-direction: column;
        gap: 15px;
        padding: 15px;
      }
      
      .header-actions {
        width: 100%;
        justify-content: space-between;
      }
      
      .order-footer {
        flex-direction: column;
      }
    }
  </style>
</head>
<body>

<header>
  <div class="logo-container">
    <img src="img/logo.jpeg" alt="Agrolease Logo" class="logo">
    <span class="brand-name">Agrolease</span>
  </div>
  <div class="header-actions">
    <a href="vendor_dashboard.php" class="btn btn-outline">
      <i class="fas fa-arrow-left"></i> Dashboard
    </a>
  </div>
</header>

<div class="container">
  <div class="page-header">
    <h1 class="page-title">Pending Orders</h1>
    <p class="page-subtitle">Review and manage incoming equipment rental requests</p>
  </div>

  <div class="orders-grid">
    <?php if (mysqli_num_rows($result) > 0): ?>
      <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <div class="order-card">
          <div class="order-header">
            <div class="order-user">
              <div class="user-avatar">
                <?php echo strtoupper(substr($row['user_name'], 0, 1)); ?>
              </div>
              <div>
                <div class="user-name"><?php echo htmlspecialchars($row['user_name']); ?></div>
                <div class="order-id">Order #<?php echo $row['order_id']; ?></div>
              </div>
            </div>
            <i class="fas fa-clock"></i>
          </div>
          
          <div class="order-body">
            <div class="order-detail">
              <div class="detail-icon">
                <i class="fas fa-tractor"></i>
              </div>
              <div class="detail-content">
                <div class="detail-label">Equipment</div>
                <div class="detail-value"><?php echo htmlspecialchars($row['equipment_name']); ?></div>
              </div>
            </div>
            
            <div class="order-detail">
              <div class="detail-icon">
                <i class="far fa-calendar-alt"></i>
              </div>
              <div class="detail-content">
                <div class="detail-label">Rental Period</div>
                <div class="detail-value">
                  <?php echo date('M j, Y', strtotime($row['rental_start_date'])); ?> - 
                  <?php echo date('M j, Y', strtotime($row['rental_end_date'])); ?>
                </div>
              </div>
            </div>
            
            <div class="order-detail">
              <div class="detail-icon">
                <i class="fas fa-rupee-sign"></i>
              </div>
              <div class="detail-content">
                <div class="detail-label">Total Amount</div>
                <div class="detail-value price">₹<?php echo number_format($row['price'], 2); ?></div>
              </div>
            </div>
          </div>
          
          <div class="order-footer">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="order-form">
              <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
              <button type="submit" name="accept" class="btn btn-accept">
                <i class="fas fa-check-circle"></i> Accept
              </button>
              <button type="submit" name="deny" class="btn btn-deny">
                <i class="fas fa-times-circle"></i> Deny
              </button>
            </form>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="empty-state">
        <div class="empty-icon">
          <i class="far fa-check-circle"></i>
        </div>
        <h3 class="empty-text">No pending orders at the moment</h3>
        <a href="vendor_dashboard.php" class="btn btn-primary">
          <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
      </div>
    <?php endif; ?>
  </div>
</div>

</body>
</html>

<?php mysqli_close($conn); ?>