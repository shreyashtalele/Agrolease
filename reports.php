<?php
session_start();
include 'db.php';

if (!isset($_SESSION['vendorid'])) {
    echo "Vendor ID is missing.";
    exit;
}

$vendorID = $_SESSION['vendorid'];
$query_vendor = "SELECT * FROM vendor WHERE vendorid = '$vendorID'";
$result_vendor = mysqli_query($conn, $query_vendor);
if (!$result_vendor || mysqli_num_rows($result_vendor) == 0) {
    echo "Vendor not found.";
    exit;
}
$row_vendor = mysqli_fetch_assoc($result_vendor);

$query_orders = "SELECT * FROM orders WHERE vendor_id = '$vendorID' AND status = 'confirmed' AND (payment_status = 'pending' OR payment_status = 'success')";
$result_orders = mysqli_query($conn, $query_orders);

$orders = [];
$total_revenue = 0;

if ($result_orders) {
    while ($row = mysqli_fetch_assoc($result_orders)) {
        $total_revenue += $row['price'];
        $orders[] = $row;
    }
} else {
    echo "Error fetching orders: " . mysqli_error($conn);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Agrolease | Order Report</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary: #2e7d32;
      --primary-light: #60ad5e;
      --primary-dark: #005005;
      --secondary: #f5f5f5;
      --text: #333333;
      --text-light: #666666;
      --border: #e0e0e0;
      --white: #ffffff;
      --success: #4caf50;
      --warning: #ff9800;
      --danger: #f44336;
      --shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
      --radius: 8px;
      --transition: all 0.3s ease;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8faf8;
      color: var(--text);
      line-height: 1.6;
    }

    header {
      background: linear-gradient(135deg, var(--primary), var(--primary-dark));
      color: var(--white);
      padding: 1rem 2rem;
      box-shadow: var(--shadow);
      position: relative;
      z-index: 10;
    }

    .header-container {
      max-width: 1200px;
      margin: 0 auto;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 1.5rem;
    }

    .brand {
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    .logo {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid rgba(255, 255, 255, 0.2);
    }

    .brand-text h1 {
      font-size: 1.5rem;
      font-weight: 600;
      line-height: 1.2;
    }

    .brand-text p {
      font-size: 0.75rem;
      opacity: 0.9;
    }

    .header-actions {
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    .btn {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.6rem 1.2rem;
      border-radius: var(--radius);
      font-weight: 500;
      text-decoration: none;
      transition: var(--transition);
      cursor: pointer;
      border: none;
    }

    .btn-primary {
      background-color: var(--white);
      color: var(--primary-dark);
    }

    .btn-primary:hover {
      background-color: rgba(255, 255, 255, 0.9);
      transform: translateY(-2px);
    }

    .btn-icon {
      font-size: 0.9rem;
    }

    .container {
      max-width: 1200px;
      margin: 2rem auto;
      padding: 0 1.5rem;
    }

    .page-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 2rem;
    }

    .page-title {
      font-size: 1.75rem;
      font-weight: 600;
      color: var(--primary-dark);
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }

    .page-title i {
      color: var(--primary);
    }

    .card-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 1.5rem;
      margin-bottom: 2rem;
    }

    .card {
      background: var(--white);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      padding: 1.5rem;
      transition: var(--transition);
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    }

    .card-header {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      margin-bottom: 1rem;
      padding-bottom: 0.75rem;
      border-bottom: 1px solid var(--border);
    }

    .card-header i {
      font-size: 1.25rem;
      color: var(--primary);
    }

    .card-header h3 {
      font-size: 1.1rem;
      font-weight: 600;
    }

    .card-body {
      padding: 0.5rem 0;
    }

    .info-row {
      display: flex;
      justify-content: space-between;
      margin-bottom: 0.5rem;
    }

    .info-label {
      color: var(--text-light);
      font-size: 0.9rem;
    }

    .info-value {
      font-weight: 500;
    }

    .total-revenue {
      font-size: 1.5rem;
      font-weight: 700;
      color: var(--primary);
      margin-top: 0.5rem;
    }

    .report-time {
      font-size: 0.75rem;
      color: var(--text-light);
      text-align: right;
      margin-top: 1rem;
    }

    .table-container {
      background: var(--white);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      overflow: hidden;
      margin-bottom: 2rem;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    thead {
      background: linear-gradient(to right, var(--primary), var(--primary-light));
      color: var(--white);
    }

    th {
      padding: 1rem;
      text-align: left;
      font-weight: 500;
    }

    td {
      padding: 1rem;
      border-bottom: 1px solid var(--border);
    }

    tbody tr:last-child td {
      border-bottom: none;
    }

    tbody tr:hover {
      background-color: rgba(46, 125, 50, 0.05);
    }

    .status-badge {
      display: inline-block;
      padding: 0.25rem 0.75rem;
      border-radius: 20px;
      font-size: 0.75rem;
      font-weight: 500;
    }

    .status-confirmed {
      background-color: rgba(76, 175, 80, 0.1);
      color: var(--success);
    }

    .status-pending {
      background-color: rgba(255, 152, 0, 0.1);
      color: var(--warning);
    }

    .status-success {
      background-color: rgba(76, 175, 80, 0.2);
      color: var(--success);
    }

    footer {
      background: linear-gradient(135deg, var(--primary), var(--primary-dark));
      color: var(--white);
      padding: 1.5rem;
      text-align: center;
      margin-top: 3rem;
    }

    .footer-text {
      font-size: 0.9rem;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
      .header-container {
        flex-direction: column;
        text-align: center;
      }
      
      .brand {
        flex-direction: column;
      }
      
      .page-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
      }
      
      .card-grid {
        grid-template-columns: 1fr;
      }
      
      table {
        display: block;
        overflow-x: auto;
      }
    }
  </style>
</head>
<body>

<header>
  <div class="header-container">
    <div class="brand">
      <img src="img/logo.jpeg" alt="Agrolease Logo" class="logo">
      <div class="brand-text">
        <h1>Agrolease</h1>
        <p>Harvest Success with Our Innovative Farming Solutions</p>
      </div>
    </div>
    <div class="header-actions">
      <a href="vendor_dashboard.php" class="btn btn-primary">
        <i class="fas fa-arrow-left btn-icon"></i>
        Back to Dashboard
      </a>
    </div>
  </div>
</header>

<div class="container">
  <div class="page-header">
    <h1 class="page-title">
      <i class="fas fa-chart-pie"></i>
      Order Summary Report
    </h1>
    <button class="btn btn-primary" onclick="downloadPDF()">
      <i class="fas fa-file-pdf btn-icon"></i>
      Download PDF
    </button>
  </div>

  <div class="card-grid">
    <div class="card">
      <div class="card-header">
        <i class="fas fa-user-tie"></i>
        <h3>Vendor Information</h3>
      </div>
      <div class="card-body">
        <div class="info-row">
          <span class="info-label">Name:</span>
          <span class="info-value"><?= htmlspecialchars($row_vendor['vendor_name']) ?></span>
        </div>
        <div class="info-row">
          <span class="info-label">Email:</span>
          <span class="info-value"><?= htmlspecialchars($row_vendor['email']) ?></span>
        </div>
        <div class="info-row">
          <span class="info-label">Mobile:</span>
          <span class="info-value"><?= htmlspecialchars($row_vendor['mobile']) ?></span>
        </div>
        <div class="report-time">
          Report generated on <?= date("F j, Y, g:i a") ?>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-header">
        <i class="fas fa-coins"></i>
        <h3>Financial Summary</h3>
      </div>
      <div class="card-body">
        <div class="info-row">
          <span class="info-label">Total Orders:</span>
          <span class="info-value"><?= count($orders) ?></span>
        </div>
        <div class="info-row">
          <span class="info-label">Total Revenue:</span>
          <span class="total-revenue">Rs. <?= number_format($total_revenue, 2) ?></span>
        </div>
      </div>
    </div>
  </div>

  <div class="table-container">
    <table>
      <thead>
        <tr>
          <th>Order ID</th>
          <th>Quantity</th>
          <th>Start Date</th>
          <th>End Date</th>
          <th>Duration</th>
          <th>Price</th>
          <th>Status</th>
          <th>Payment</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($orders)): ?>
          <?php foreach ($orders as $order): ?>
            <tr>
              <td>#<?= htmlspecialchars($order['order_id']) ?></td>
              <td><?= htmlspecialchars($order['quantity']) ?></td>
              <td><?= date("M j, Y", strtotime($order['rental_start_date'])) ?></td>
              <td><?= date("M j, Y", strtotime($order['rental_end_date'])) ?></td>
              <td><?= htmlspecialchars($order['rental_duration']) ?> days</td>
              <td>Rs. <?= number_format($order['price'], 2) ?></td>
              <td>
                <span class="status-badge status-confirmed">
                  <?= ucfirst(htmlspecialchars($order['status'])) ?>
                </span>
              </td>
              <td>
                <span class="status-badge status-<?= $order['payment_status'] === 'success' ? 'success' : 'pending' ?>">
                  <?= ucfirst(htmlspecialchars($order['payment_status'])) ?>
                </span>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="8" style="text-align: center; padding: 2rem;">
              <i class="fas fa-info-circle" style="color: var(--text-light); margin-right: 0.5rem;"></i>
              No orders found for this period
            </td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<footer>
  <p class="footer-text">&copy; <?= date("Y") ?> Agrolease. All rights reserved.</p>
</footer>

<!-- jsPDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
function downloadPDF() {
  const { jsPDF } = window.jspdf;
  const doc = new jsPDF({
    orientation: 'portrait',
    unit: 'mm',
    format: 'a4'
  });
  
  // Add logo
  doc.addImage('img/logo.jpeg', 'JPEG', 15, 10, 20, 20);
  
  // Title
  doc.setFontSize(18);
  doc.setTextColor(46, 125, 50);
  doc.text('Agrolease Order Report', 105, 20, { align: 'center' });
  
  // Vendor info
  doc.setFontSize(12);
  doc.setTextColor(0, 0, 0);
  doc.text(`Vendor: ${'<?= $row_vendor['vendor_name'] ?>'}`, 20, 40);
  doc.text(`Report Date: ${new Date().toLocaleDateString()}`, 20, 45);
  
  // Financial summary
  doc.setFontSize(14);
  doc.text('Financial Summary', 20, 60);
  doc.setFontSize(12);
  doc.text(`Total Orders: ${'<?= count($orders) ?>'}`, 20, 70);
  doc.text(`Total Revenue: Rs. ${'<?= number_format($total_revenue, 2) ?>'}`, 20, 75);
  
  // Order table header
  doc.setFontSize(12);
  doc.setTextColor(255, 255, 255);
  doc.setFillColor(46, 125, 50);
  doc.rect(20, 85, 170, 8, 'F');
  doc.text('Order ID', 25, 90);
  doc.text('Quantity', 45, 90);
  doc.text('Start Date', 65, 90);
  doc.text('End Date', 95, 90);
  doc.text('Price', 135, 90);
  doc.text('Status', 155, 90);
  doc.text('Payment', 175, 90);
  
  // Order data
  doc.setTextColor(0, 0, 0);
  let y = 95;
  <?php foreach ($orders as $order): ?>
    doc.text('<?= $order['order_id'] ?>', 25, y);
    doc.text('<?= $order['quantity'] ?>', 45, y);
    doc.text('<?= date("M j, Y", strtotime($order['rental_start_date'])) ?>', 65, y);
    doc.text('<?= date("M j, Y", strtotime($order['rental_end_date'])) ?>', 95, y);
    doc.text('Rs. <?= number_format($order['price'], 2) ?>', 135, y);
    doc.text('<?= ucfirst($order['status']) ?>', 155, y);
    doc.text('<?= ucfirst($order['payment_status']) ?>', 175, y);
    y += 5;
  <?php endforeach; ?>
  
  // Footer
  doc.setFontSize(10);
  doc.setTextColor(100, 100, 100);
  doc.text('© <?= date("Y") ?> Agrolease. All rights reserved.', 105, 290, { align: 'center' });
  
  doc.save(`Agrolease_Report_${new Date().toISOString().slice(0,10)}.pdf`);
}
</script>

</body>
</html>