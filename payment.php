<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user']['userid'])) {
    echo "User ID is missing. Please log in again.";
    exit;
}

$userID = $_SESSION['user']['userid'];

$query = "SELECT o.*, e.equipment_name, e.rent_per_day
          FROM orders o
          INNER JOIN equipment e ON o.equipment_id = e.equipment_id
          WHERE o.user_id = '$userID'
            AND o.status = 'confirmed'
            AND o.payment_status = 'pending'";

$result = mysqli_query($conn, $query);
if (!$result) {
    echo "Error: " . mysqli_error($conn);
    exit;
}

// Simulate payment processing
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    sleep(2); // Simulate processing delay
    
    // Generate realistic payment response
    $payment_id = 'PAY-' . strtoupper(bin2hex(random_bytes(5)));
    $timestamp = date('Y-m-d H:i:s');
    $order_id = $_POST['order_id'];
    
    // Update payment status in database
    $update_query = "UPDATE orders SET 
                payment_status = 'success' 
                WHERE order_id = '$order_id'";

    
    $update_result = mysqli_query($conn, $update_query);
    
    if (!$update_result) {
        echo json_encode([
            'success' => false,
            'message' => 'Database update failed: ' . mysqli_error($conn)
        ]);
        exit;
    }
    
    echo json_encode([
        'success' => true,
        'payment_id' => $payment_id,
        'timestamp' => $timestamp,
        'method' => 'card',
        'bank_ref' => 'BANK' . mt_rand(100000, 999999),
        'auth_code' => strtoupper(bin2hex(random_bytes(3)))
    ]);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Agrolease - Secure Payment Portal</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    :root {
      --primary: #2e7d32;
      --primary-light: #60ad5e;
      --primary-dark: #005005;
      --secondary: #f8f9fa;
      --accent: #ffc107;
      --text-dark: #2d3748;
      --text-light: #718096;
      --error: #e53e3e;
      --success: #38a169;
      --warning: #dd6b20;
      --border-radius: 12px;
      --box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
      --transition: all 0.3s ease;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background: #f8fafc;
      color: var(--text-dark);
      line-height: 1.6;
    }

    /* Modern Header */
    .payment-header {
      background: linear-gradient(135deg, var(--primary-dark), var(--primary));
      color: white;
      padding: 1.5rem 5%;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      position: relative;
      z-index: 100;
    }

    .payment-brand {
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    .payment-logo {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid rgba(255, 255, 255, 0.2);
    }

    .payment-title {
      font-size: 1.5rem;
      font-weight: 700;
    }

    .payment-tagline {
      font-size: 0.85rem;
      opacity: 0.9;
    }

    .back-btn {
      background: rgba(255, 255, 255, 0.15);
      color: white;
      padding: 0.75rem 1.25rem;
      border-radius: 8px;
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 0.5rem;
      transition: var(--transition);
    }

    .back-btn:hover {
      background: rgba(255, 255, 255, 0.25);
      transform: translateY(-2px);
    }

    /* Main Container */
    .payment-container {
      max-width: 1200px;
      margin: 2rem auto;
      padding: 0 5%;
    }

    .page-title {
      font-size: 2rem;
      font-weight: 700;
      color: var(--primary);
      margin-bottom: 2rem;
      position: relative;
      display: inline-block;
    }

    .page-title::after {
      content: '';
      position: absolute;
      bottom: -10px;
      left: 0;
      width: 60px;
      height: 4px;
      background: var(--accent);
      border-radius: 2px;
    }

    /* Payment Cards Grid */
    .payment-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
      gap: 1.5rem;
      margin-top: 2rem;
    }

    /* Payment Card */
    .payment-card {
      background: white;
      border-radius: var(--border-radius);
      box-shadow: var(--box-shadow);
      padding: 1.5rem;
      transition: var(--transition);
      border: 1px solid rgba(0, 0, 0, 0.05);
      position: relative;
      overflow: hidden;
    }

    .payment-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 5px;
      height: 100%;
      background: var(--primary);
    }

    .payment-card-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1.5rem;
    }

    .payment-id {
      font-weight: 700;
      color: var(--primary);
      font-size: 1.1rem;
    }

    .payment-status {
      font-size: 0.75rem;
      font-weight: 700;
      padding: 0.5rem 1rem;
      border-radius: 20px;
      text-transform: uppercase;
    }

    .status-pending {
      background: rgba(221, 107, 32, 0.1);
      color: var(--warning);
    }

    .status-paid {
      background: rgba(56, 161, 105, 0.1);
      color: var(--success);
    }

    .payment-details {
      margin-bottom: 1.5rem;
    }

    .detail-row {
      display: flex;
      justify-content: space-between;
      margin-bottom: 0.75rem;
      font-size: 0.95rem;
    }

    .detail-label {
      color: var(--text-light);
      font-weight: 500;
    }

    .detail-value {
      font-weight: 600;
    }

    .payment-total {
      border-top: 1px dashed #eee;
      padding-top: 1rem;
      margin-top: 1rem;
      font-weight: 700;
      color: var(--primary);
    }

    .pay-btn {
      width: 100%;
      padding: 1rem;
      background: var(--primary);
      color: white;
      border: none;
      border-radius: 8px;
      font-weight: 600;
      font-size: 1rem;
      cursor: pointer;
      transition: var(--transition);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      box-shadow: 0 4px 12px rgba(46, 125, 50, 0.2);
    }

    .pay-btn:hover:not(:disabled) {
      background: var(--primary-dark);
      transform: translateY(-2px);
      box-shadow: 0 6px 16px rgba(46, 125, 50, 0.3);
    }

    .pay-btn:disabled {
      background: #e2e8f0;
      color: #a0aec0;
      cursor: not-allowed;
    }

    /* Payment Modal */
    .payment-modal {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0, 0, 0, 0.5);
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 1000;
      opacity: 0;
      pointer-events: none;
      transition: var(--transition);
      backdrop-filter: blur(5px);
    }

    .payment-modal.active {
      opacity: 1;
      pointer-events: auto;
    }

    .modal-content {
      background: white;
      border-radius: var(--border-radius);
      width: 100%;
      max-width: 500px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
      transform: translateY(20px);
      transition: var(--transition);
      overflow: hidden;
    }

    .payment-modal.active .modal-content {
      transform: translateY(0);
    }

    .modal-header {
      padding: 1.5rem;
      background: var(--primary);
      color: white;
      position: relative;
    }

    .modal-title {
      font-size: 1.5rem;
      font-weight: 600;
    }

    .modal-close {
      position: absolute;
      top: 1.5rem;
      right: 1.5rem;
      background: none;
      border: none;
      color: white;
      font-size: 1.5rem;
      cursor: pointer;
    }

    .modal-body {
      padding: 1.5rem;
    }

    .form-group {
      margin-bottom: 1.5rem;
    }

    .form-label {
      display: block;
      margin-bottom: 0.5rem;
      font-weight: 500;
    }

    .form-control {
      width: 100%;
      padding: 0.75rem 1rem;
      border: 1px solid #e2e8f0;
      border-radius: 8px;
      font-size: 1rem;
      transition: var(--transition);
    }

    .form-control:focus {
      border-color: var(--primary-light);
      box-shadow: 0 0 0 3px rgba(46, 125, 50, 0.1);
      outline: none;
    }

    .card-group {
      display: flex;
      gap: 1rem;
    }

    .card-input {
      flex: 1;
    }

    .card-expiry-cvv {
      display: flex;
      gap: 1rem;
    }

    .expiry-input, .cvv-input {
      width: 100px;
    }

    .modal-footer {
      padding: 1.5rem;
      border-top: 1px solid #e2e8f0;
      display: flex;
      justify-content: flex-end;
      gap: 1rem;
    }

    .btn {
      padding: 0.75rem 1.5rem;
      border-radius: 8px;
      font-weight: 600;
      cursor: pointer;
      transition: var(--transition);
      border: none;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .btn-primary {
      background: var(--primary);
      color: white;
    }

    .btn-primary:hover {
      background: var(--primary-dark);
    }

    .btn-outline {
      background: white;
      border: 1px solid #e2e8f0;
      color: var(--text-light);
    }

    .btn-outline:hover {
      border-color: var(--primary-light);
      color: var(--primary);
    }

    /* Payment Processing */
    .processing-overlay {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(255, 255, 255, 0.9);
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      z-index: 2000;
      opacity: 0;
      pointer-events: none;
      transition: var(--transition);
    }

    .processing-overlay.active {
      opacity: 1;
      pointer-events: auto;
    }

    .processing-spinner {
      width: 60px;
      height: 60px;
      border: 5px solid rgba(46, 125, 50, 0.2);
      border-top-color: var(--primary);
      border-radius: 50%;
      animation: spin 1s linear infinite;
      margin-bottom: 1.5rem;
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }

    .processing-text {
      font-size: 1.25rem;
      font-weight: 600;
      color: var(--primary);
      margin-bottom: 0.5rem;
    }

    .processing-subtext {
      color: var(--text-light);
      max-width: 300px;
      text-align: center;
    }

    /* Payment Success */
    .success-overlay {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: white;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      z-index: 2000;
      opacity: 0;
      pointer-events: none;
      transition: var(--transition);
      padding: 2rem;
      text-align: center;
    }

    .success-overlay.active {
      opacity: 1;
      pointer-events: auto;
    }

    .success-icon {
      width: 80px;
      height: 80px;
      background: rgba(56, 161, 105, 0.1);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 1.5rem;
    }

    .success-icon i {
      font-size: 2.5rem;
      color: var(--success);
    }

    .success-title {
      font-size: 1.75rem;
      font-weight: 700;
      color: var(--success);
      margin-bottom: 1rem;
    }

    .success-details {
      background: #f8f9fa;
      border-radius: 8px;
      padding: 1.5rem;
      width: 100%;
      max-width: 400px;
      margin-bottom: 2rem;
      text-align: left;
    }

    .success-row {
      display: flex;
      justify-content: space-between;
      margin-bottom: 0.75rem;
    }

    .success-label {
      color: var(--text-light);
      font-weight: 500;
    }

    .success-value {
      font-weight: 600;
    }

    .success-btn {
      padding: 1rem 2rem;
      background: var(--primary);
      color: white;
      border: none;
      border-radius: 8px;
      font-weight: 600;
      font-size: 1rem;
      cursor: pointer;
      transition: var(--transition);
    }

    .success-btn:hover {
      background: var(--primary-dark);
    }

    /* Empty State */
    .empty-state {
      text-align: center;
      padding: 3rem 0;
      grid-column: 1 / -1;
    }

    .empty-icon {
      font-size: 4rem;
      color: #e2e8f0;
      margin-bottom: 1.5rem;
    }

    .empty-title {
      font-size: 1.5rem;
      font-weight: 600;
      color: var(--text-light);
      margin-bottom: 0.5rem;
    }

    .empty-text {
      color: #a0aec0;
      margin-bottom: 1.5rem;
      max-width: 500px;
      margin-left: auto;
      margin-right: auto;
    }

    .empty-btn {
      padding: 0.75rem 1.5rem;
      background: var(--primary);
      color: white;
      border-radius: 8px;
      font-weight: 600;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      transition: var(--transition);
    }

    .empty-btn:hover {
      background: var(--primary-dark);
    }

    /* Responsive */
    @media (max-width: 768px) {
      .payment-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
      }

      .payment-brand {
        justify-content: center;
      }

      .back-btn {
        width: 100%;
        justify-content: center;
      }

      .payment-grid {
        grid-template-columns: 1fr;
      }

      .card-group {
        flex-direction: column;
      }

      .card-expiry-cvv {
        flex-direction: column;
      }

      .expiry-input, .cvv-input {
        width: 100%;
      }
    }
  </style>
</head>
<body>

<!-- Header -->
<header class="payment-header">
  <div class="payment-brand">
    <img src="img/logo.jpeg" alt="Agrolease Logo" class="payment-logo">
    <div>
      <h1 class="payment-title">Agrolease</h1>
      <p class="payment-tagline">Secure Payment Portal</p>
    </div>
  </div>
  <a href="dashboard.php" class="back-btn">
    <i class="fas fa-arrow-left"></i> Back to Dashboard
  </a>
</header>

<!-- Main Content -->
<main class="payment-container">
  <h1 class="page-title">Pending Payments</h1>

  <?php if (mysqli_num_rows($result) == 0): ?>
    <div class="empty-state">
      <i class="far fa-check-circle empty-icon"></i>
      <h2 class="empty-title">No Pending Payments</h2>
      <p class="empty-text">You don't have any orders requiring payment at this time.</p>
      <a href="equipment.php" class="empty-btn">
        <i class="fas fa-tractor"></i> Browse Equipment
      </a>
    </div>
  <?php else: ?>
    <div class="payment-grid">
      <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <?php
          $totalDays = ceil((strtotime($row['rental_end_date']) - strtotime($row['rental_start_date'])) / (60 * 60 * 24));
          $totalAmount = $row['price'];
        ?>
        <div class="payment-card">
          <div class="payment-card-header">
            <span class="payment-id">Order #<?php echo $row['order_id']; ?></span>
            <span class="payment-status status-pending">Pending Payment</span>
          </div>
          
          <div class="payment-details">
            <div class="detail-row">
              <span class="detail-label">Equipment:</span>
              <span class="detail-value"><?php echo $row['equipment_name']; ?></span>
            </div>
            <div class="detail-row">
              <span class="detail-label">Rental Period:</span>
              <span class="detail-value">
                <?php echo date('M j, Y', strtotime($row['rental_start_date'])); ?> - 
                <?php echo date('M j, Y', strtotime($row['rental_end_date'])); ?>
              </span>
            </div>
            <div class="detail-row">
              <span class="detail-label">Duration:</span>
              <span class="detail-value"><?php echo $totalDays; ?> days</span>
            </div>
            <div class="detail-row">
              <span class="detail-label">Daily Rate:</span>
              <span class="detail-value">₹<?php echo number_format($row['rent_per_day'], 2); ?></span>
            </div>
            <div class="detail-row payment-total">
              <span class="detail-label">Total Amount:</span>
              <span class="detail-value">₹<?php echo number_format($totalAmount, 2); ?></span>
            </div>
          </div>
          
          <button class="pay-btn" onclick="openPaymentModal('<?php echo $row['order_id']; ?>', <?php echo $totalAmount; ?>)">
            <i class="fas fa-credit-card"></i> Pay Now
          </button>
        </div>
      <?php endwhile; ?>
    </div>
  <?php endif; ?>
</main>

<!-- Payment Modal -->
<div class="payment-modal" id="paymentModal">
  <div class="modal-content">
    <div class="modal-header">
      <h2 class="modal-title">Complete Payment</h2>
      <button class="modal-close" onclick="closeModal()">&times;</button>
    </div>
    
    <div class="modal-body">
      <!-- Card Payment -->
      <div class="method-content active" id="cardContent">
        <div class="form-group">
          <label class="form-label">Card Number</label>
          <input type="text" class="form-control" placeholder="1234 5678 9012 3456" maxlength="19">
        </div>
        
        <div class="form-group">
          <label class="form-label">Cardholder Name</label>
          <input type="text" class="form-control" placeholder="Your Name ">
        </div>
        
        <div class="form-group card-group">
          <div class="card-expiry-cvv">
            <label class="form-label">Expiry Date</label>
            <input type="text" class="form-control expiry-input" placeholder="MM/YY">
          </div>
          <div class="card-expiry-cvv">
            <label class="form-label">CVV</label>
            <input type="text" class="form-control cvv-input" placeholder="123" maxlength="3">
          </div>
        </div>
      </div>
    </div>
    
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="closeModal()">
        <i class="fas fa-times"></i> Cancel
      </button>
      <button class="btn btn-primary" id="confirmPaymentBtn" onclick="processPayment()">
        <i class="fas fa-lock"></i> Pay Securely
      </button>
    </div>
  </div>
</div>

<!-- Processing Overlay -->
<div class="processing-overlay" id="processingOverlay">
  <div class="processing-spinner"></div>
  <h3 class="processing-text">Processing Payment</h3>
  <p class="processing-subtext">Please wait while we process your payment. Do not refresh or close this window.</p>
</div>

<!-- Success Overlay -->
<div class="success-overlay" id="successOverlay">
  <div class="success-icon">
    <i class="fas fa-check"></i>
  </div>
  <h2 class="success-title">Payment Successful!</h2>
  
  <div class="success-details">
    <div class="success-row">
      <span class="success-label">Order ID:</span>
      <span class="success-value" id="successOrderId"></span>
    </div>
    <div class="success-row">
      <span class="success-label">Amount Paid:</span>
      <span class="success-value" id="successAmount"></span>
    </div>
    <div class="success-row">
      <span class="success-label">Payment Method:</span>
      <span class="success-value" id="successMethod"></span>
    </div>
    <div class="success-row">
      <span class="success-label">Transaction ID:</span>
      <span class="success-value" id="successTxnId"></span>
    </div>
    <div class="success-row">
      <span class="success-label">Date & Time:</span>
      <span class="success-value" id="successDate"></span>
    </div>
  </div>
  
  <button class="success-btn" onclick="closeSuccess()">
    <i class="fas fa-check"></i> Done
  </button>
</div>

<script>
  // Global variables
  let currentOrderId = null;
  let currentAmount = 0;
  
  // DOM elements
  const paymentModal = document.getElementById('paymentModal');
  const processingOverlay = document.getElementById('processingOverlay');
  const successOverlay = document.getElementById('successOverlay');
  const confirmBtn = document.getElementById('confirmPaymentBtn');
  
  // Open payment modal
  function openPaymentModal(orderId, amount) {
    currentOrderId = orderId;
    currentAmount = amount;
    paymentModal.classList.add('active');
    document.body.style.overflow = 'hidden';
  }
  
  // Close modal
  function closeModal() {
    paymentModal.classList.remove('active');
    document.body.style.overflow = '';
  }
  
  // Process payment
  function processPayment() {
    // Show processing overlay
    processingOverlay.classList.add('active');
    
    // Simulate API call with 2 second delay
    setTimeout(() => {
      // Create form data
      const formData = new FormData();
      formData.append('order_id', currentOrderId);
      
      // Make AJAX request to same page
      fetch(window.location.href, {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Show success overlay
          processingOverlay.classList.remove('active');
          showSuccess(data);
        } else {
          alert('Payment failed: ' + (data.message || 'Unknown error'));
          processingOverlay.classList.remove('active');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Payment failed. Please try again.');
        processingOverlay.classList.remove('active');
      });
    }, 2000);
  }
  
  // Show success screen
  function showSuccess(data) {
    document.getElementById('successOrderId').textContent = currentOrderId;
    document.getElementById('successAmount').textContent = '₹' + currentAmount.toFixed(2);
    document.getElementById('successMethod').textContent = 'Credit/Debit Card';
    document.getElementById('successTxnId').textContent = data.payment_id;
    document.getElementById('successDate').textContent = data.timestamp;
    
    successOverlay.classList.add('active');
  }
  
  // Close success overlay
  function closeSuccess() {
    successOverlay.classList.remove('active');
    paymentModal.classList.remove('active');
    document.body.style.overflow = '';
    
    // Reload the page to update payment status
    setTimeout(() => location.reload(), 300);
  }
  
  // Format card number input
  document.addEventListener('DOMContentLoaded', function() {
    const cardInput = document.querySelector('#cardContent input[type="text"]');
    if (cardInput) {
      cardInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\s+/g, '');
        if (value.length > 0) {
          value = value.match(new RegExp('.{1,4}', 'g')).join(' ');
        }
        e.target.value = value;
      });
    }
    
    // Format expiry date input
    const expiryInput = document.querySelector('.expiry-input');
    if (expiryInput) {
      expiryInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length >= 2) {
          value = value.substring(0, 2) + '/' + value.substring(2, 4);
        }
        e.target.value = value;
      });
    }
  });
</script>

</body>
</html>