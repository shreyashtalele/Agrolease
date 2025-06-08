<?php
session_start();
include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user']['userid'])) {
    header("Location: login.php");
    exit;
}

$userID = $_SESSION['user']['userid'];

// Get user's confirmed orders
$query = "SELECT o.*, e.equipment_name, e.rent_per_day
          FROM orders o
          INNER JOIN equipment e ON o.equipment_id = e.equipment_id
          WHERE o.user_id = '$userID' AND o.status = 'confirmed'";
$result = mysqli_query($conn, $query);

// Handle payment processing
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $order_id = mysqli_real_escape_string($conn, $_POST['order_id']);
    $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method'] ?? 'card');
    
    // Simulate payment processing (10% chance of failure)
    $success = (rand(1, 10) !== 1);
    
    if ($success) {
        $update = mysqli_query($conn, "UPDATE orders SET payment_status='paid', payment_method='$payment_method' WHERE order_id='$order_id'");
        if ($update) {
            echo json_encode([
                'success' => true,
                'payment_id' => 'pmt_' . bin2hex(random_bytes(8)),
                'timestamp' => date('Y-m-d H:i:s')
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database error']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Payment declined by bank']);
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Payment Portal</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    :root {
      --primary: #2e7d32;
      --primary-light: #60ad5e;
      --primary-dark: #005005;
      --text-dark: #333;
      --text-light: #666;
      --success: #388e3c;
      --warning: #ffa000;
      --error: #d32f2f;
      --border-radius: 8px;
      --box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }
    
    body {
      font-family: 'Poppins', sans-serif;
      background: #f5f5f5;
      color: var(--text-dark);
      line-height: 1.6;
    }
    
    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px;
    }
    
    header {
      background: var(--primary);
      color: white;
      padding: 15px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 30px;
    }
    
    .logo {
      font-weight: 600;
      font-size: 1.5rem;
    }
    
    .back-btn {
      color: white;
      text-decoration: none;
      background: rgba(255,255,255,0.2);
      padding: 8px 15px;
      border-radius: var(--border-radius);
      font-size: 0.9rem;
    }
    
    h1 {
      text-align: center;
      margin-bottom: 30px;
      color: var(--primary);
    }
    
    .orders-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 20px;
    }
    
    .order-card {
      background: white;
      border-radius: var(--border-radius);
      box-shadow: var(--box-shadow);
      padding: 20px;
      position: relative;
    }
    
    .order-id {
      font-weight: 600;
      color: var(--primary);
      margin-bottom: 10px;
    }
    
    .status {
      position: absolute;
      top: 20px;
      right: 20px;
      font-size: 0.8rem;
      padding: 3px 10px;
      border-radius: 20px;
      background: #e0f7fa;
      color: #00796b;
    }
    
    .status.paid {
      background: #e8f5e9;
      color: var(--success);
    }
    
    .order-details p {
      margin: 8px 0;
      font-size: 0.9rem;
      color: var(--text-light);
      display: flex;
      justify-content: space-between;
    }
    
    .order-details p strong {
      color: var(--text-dark);
    }
    
    .total {
      margin-top: 15px;
      padding-top: 10px;
      border-top: 1px dashed #eee;
      font-weight: 600;
    }
    
    .pay-btn {
      width: 100%;
      margin-top: 15px;
      padding: 10px;
      background: var(--primary);
      color: white;
      border: none;
      border-radius: var(--border-radius);
      cursor: pointer;
      font-weight: 500;
    }
    
    .pay-btn:disabled {
      background: #ccc;
      cursor: not-allowed;
    }
    
    .empty-state {
      text-align: center;
      padding: 50px 20px;
    }
    
    .empty-state i {
      font-size: 3rem;
      color: #ddd;
      margin-bottom: 15px;
    }
    
    /* Modal Styles */
    .modal {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0,0,0,0.5);
      display: none;
      justify-content: center;
      align-items: center;
      z-index: 100;
    }
    
    .modal-content {
      background: white;
      border-radius: var(--border-radius);
      width: 90%;
      max-width: 500px;
      padding: 25px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    
    .payment-methods {
      display: flex;
      gap: 10px;
      margin-bottom: 20px;
    }
    
    .method {
      flex: 1;
      padding: 15px;
      border: 1px solid #ddd;
      border-radius: var(--border-radius);
      text-align: center;
      cursor: pointer;
    }
    
    .method.selected {
      border-color: var(--primary);
      background: #f0f9f0;
    }
    
    .payment-form {
      margin-bottom: 20px;
    }
    
    .form-group {
      margin-bottom: 15px;
    }
    
    .form-group input, .form-group select {
      width: 100%;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: var(--border-radius);
    }
    
    .modal-footer {
      display: flex;
      gap: 10px;
    }
    
    .modal-btn {
      flex: 1;
      padding: 10px;
      border: none;
      border-radius: var(--border-radius);
      cursor: pointer;
    }
    
    .confirm-btn {
      background: var(--primary);
      color: white;
    }
    
    /* Success Message */
    .success-message {
      text-align: center;
      display: none;
    }
    
    .success-message i {
      font-size: 3rem;
      color: var(--success);
      margin-bottom: 15px;
    }
    
    /* Toast */
    .toast {
      position: fixed;
      bottom: 20px;
      left: 50%;
      transform: translateX(-50%);
      background: var(--primary);
      color: white;
      padding: 10px 20px;
      border-radius: 20px;
      display: none;
      z-index: 1000;
    }
    
    .toast.error {
      background: var(--error);
    }
  </style>
</head>
<body>

<header>
  <div class="logo">Agrolease</div>
  <a href="dashboard.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back</a>
</header>

<div class="container">
  <h1>Payment Portal</h1>
  
  <?php if (mysqli_num_rows($result) == 0): ?>
    <div class="empty-state">
      <i class="far fa-check-circle"></i>
      <h3>No Pending Payments</h3>
      <p>You don't have any orders requiring payment at this time.</p>
    </div>
  <?php else: ?>
    <div class="orders-grid">
      <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <?php
          $isPaid = ($row['payment_status'] === 'paid');
          $totalDays = ceil((strtotime($row['rental_end_date']) - strtotime($row['rental_start_date'])) / (60 * 60 * 24));
          $totalAmount = $row['price'];
        ?>
        <div class="order-card">
          <div class="order-id">Order #<?php echo htmlspecialchars($row['order_id']); ?></div>
          <div class="status <?php echo $isPaid ? 'paid' : ''; ?>">
            <?php echo $isPaid ? 'Paid' : 'Pending'; ?>
          </div>
          
          <div class="order-details">
            <p><strong>Equipment:</strong> <?php echo htmlspecialchars($row['equipment_name']); ?></p>
            <p><strong>Period:</strong> <?php echo date('M j', strtotime($row['rental_start_date'])); ?> - <?php echo date('M j, Y', strtotime($row['rental_end_date'])); ?></p>
            <p><strong>Days:</strong> <?php echo $totalDays; ?></p>
            <p><strong>Rate:</strong> ₹<?php echo number_format($row['rent_per_day'], 2); ?>/day</p>
            
            <p class="total"><strong>Total:</strong> ₹<?php echo number_format($totalAmount, 2); ?></p>
          </div>
          
          <button class="pay-btn" onclick="openModal('<?php echo $row['order_id']; ?>', <?php echo $totalAmount; ?>)" <?php echo $isPaid ? 'disabled' : ''; ?>>
            <?php echo $isPaid ? '<i class="fas fa-check"></i> Paid' : '<i class="fas fa-credit-card"></i> Pay Now'; ?>
          </button>
        </div>
      <?php endwhile; ?>
    </div>
  <?php endif; ?>
</div>

<!-- Payment Modal -->
<div class="modal" id="paymentModal">
  <div class="modal-content">
    <div id="paymentForm">
      <div class="modal-header">
        <h3>Complete Payment</h3>
      </div>
      
      <div class="payment-methods">
        <div class="method selected" data-method="card" onclick="selectMethod('card')">
          <i class="far fa-credit-card"></i>
          <div>Card</div>
        </div>
        <div class="method" data-method="netbanking" onclick="selectMethod('netbanking')">
          <i class="fas fa-university"></i>
          <div>Net Banking</div>
        </div>
        <div class="method" data-method="upi" onclick="selectMethod('upi')">
          <i class="fas fa-mobile-alt"></i>
          <div>UPI</div>
        </div>
      </div>
      
      <div class="payment-form" id="cardForm">
        <div class="form-group">
          <label>Card Number</label>
          <input type="text" placeholder="1234 5678 9012 3456" maxlength="16" id="cardNumber">
        </div>
        
        <div class="form-group">
          <label>Expiry Date</label>
          <input type="text" placeholder="MM/YY" maxlength="5" id="expiryDate">
        </div>
        
        <div class="form-group">
          <label>CVV</label>
          <input type="text" placeholder="123" maxlength="4" id="cvv">
        </div>
      </div>
      
      <div class="payment-form" id="netbankingForm" style="display:none">
        <div class="form-group">
          <label>Select Bank</label>
          <select id="bankSelect">
            <option value="">Choose your bank</option>
            <option>SBI</option>
            <option>HDFC</option>
            <option>ICICI</option>
          </select>
        </div>
      </div>
      
      <div class="payment-form" id="upiForm" style="display:none">
        <div class="form-group">
          <label>UPI ID</label>
          <input type="text" placeholder="name@upi" id="upiId">
        </div>
      </div>
      
      <div class="modal-footer">
        <button class="modal-btn cancel-btn" onclick="closeModal()">Cancel</button>
        <button class="modal-btn confirm-btn" id="confirmBtn" onclick="processPayment()">Pay ₹<span id="amountDisplay">0</span></button>
      </div>
    </div>
    
    <div id="successMessage" class="success-message">
      <i class="fas fa-check-circle"></i>
      <h3>Payment Successful!</h3>
      <button class="modal-btn confirm-btn" onclick="closeModal()">Done</button>
    </div>
  </div>
</div>

<!-- Toast Notification -->
<div class="toast" id="toast"></div>

<script>
  let currentOrderId = null;
  let currentAmount = 0;
  let currentMethod = 'card';
  
  function openModal(orderId, amount) {
    currentOrderId = orderId;
    currentAmount = amount;
    document.getElementById('paymentForm').style.display = 'block';
    document.getElementById('successMessage').style.display = 'none';
    document.getElementById('amountDisplay').textContent = amount.toFixed(2);
    document.getElementById('paymentModal').style.display = 'flex';
  }
  
  function closeModal() {
    document.getElementById('paymentModal').style.display = 'none';
  }
  
  function selectMethod(method) {
    currentMethod = method;
    document.querySelectorAll('.method').forEach(el => {
      el.classList.toggle('selected', el.dataset.method === method);
    });
    document.getElementById('cardForm').style.display = method === 'card' ? 'block' : 'none';
    document.getElementById('netbankingForm').style.display = method === 'netbanking' ? 'block' : 'none';
    document.getElementById('upiForm').style.display = method === 'upi' ? 'block' : 'none';
  }
  
  function showToast(message, isError = false) {
    const toast = document.getElementById('toast');
    toast.textContent = message;
    toast.className = isError ? 'toast error' : 'toast';
    toast.style.display = 'block';
    setTimeout(() => toast.style.display = 'none', 3000);
  }
  
  function processPayment() {
    const btn = document.getElementById('confirmBtn');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
    btn.disabled = true;
    
    const formData = new FormData();
    formData.append('order_id', currentOrderId);
    formData.append('payment_method', currentMethod);
    
    fetch(window.location.href, {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        document.getElementById('paymentForm').style.display = 'none';
        document.getElementById('successMessage').style.display = 'block';
        showToast('Payment successful!');
        
        // Update the UI
        const payBtn = document.querySelector(`button[onclick="openModal('${currentOrderId}', ${currentAmount})"]`);
        if (payBtn) {
          payBtn.innerHTML = '<i class="fas fa-check"></i> Paid';
          payBtn.disabled = true;
          payBtn.closest('.order-card').querySelector('.status').textContent = 'Paid';
          payBtn.closest('.order-card').querySelector('.status').classList.add('paid');
        }
      } else {
        showToast(data.message || 'Payment failed', true);
        btn.innerHTML = 'Pay ₹' + currentAmount.toFixed(2);
        btn.disabled = false;
      }
    })
    .catch(error => {
      showToast('Payment failed. Please try again.', true);
      btn.innerHTML = 'Pay ₹' + currentAmount.toFixed(2);
      btn.disabled = false;
    });
  }
</script>

</body>
</html>