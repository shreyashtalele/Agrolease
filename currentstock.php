<?php
session_start();
include 'db.php';

if (!isset($_SESSION['vendorid'])) {
    header("Location: login.php");
    exit;
}

// Handle stock update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_stock'])) {
        $equipment_id = mysqli_real_escape_string($conn, $_POST['equipment_id']);
        $new_stock = intval($_POST['new_stock']);
        
        $update_query = "UPDATE equipment SET total_stock = $new_stock 
                        WHERE equipment_id = $equipment_id AND vendor_id = " . $_SESSION['vendorid'];
        mysqli_query($conn, $update_query);
        
        // Set success message
        $_SESSION['message'] = "Stock updated successfully!";
        header("Location: ".$_SERVER['PHP_SELF']);
        exit;
    }
    
    // Handle equipment deletion
    if (isset($_POST['delete_equipment'])) {
        $equipment_id = mysqli_real_escape_string($conn, $_POST['equipment_id']);
        
        // First check if there are any active rentals for this equipment
        $check_rentals = "SELECT * FROM orders WHERE equipment_id = $equipment_id AND status IN ('pending', 'confirmed')";
        $result = mysqli_query($conn, $check_rentals);
        
        if (mysqli_num_rows($result) > 0) {
            $_SESSION['error'] = "Cannot delete equipment with active or pending rentals!";
        } else {
            $delete_query = "DELETE FROM equipment WHERE equipment_id = $equipment_id AND vendor_id = " . $_SESSION['vendorid'];
            if (mysqli_query($conn, $delete_query)) {
                $_SESSION['message'] = "Equipment deleted successfully!";
            } else {
                $_SESSION['error'] = "Error deleting equipment: " . mysqli_error($conn);
            }
        }
        header("Location: ".$_SERVER['PHP_SELF']);
        exit;
    }
}

// Display messages
$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
unset($_SESSION['message']);
unset($_SESSION['error']);

$vendorID = $_SESSION['vendorid'];
$query = "SELECT * FROM equipment WHERE vendor_id = '$vendorID'";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Manage Equipment | Agrolease</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" href="img/logo.jpeg" type="image/jpeg">
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
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

    .btn-danger {
      background: var(--danger);
      color: var(--white);
    }

    .btn-danger:hover {
      background: #b71c1c;
    }

    /* Page Header */
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

    /* Alerts */
    .alert {
      padding: 15px;
      border-radius: var(--border-radius);
      margin: 20px auto;
      max-width: 800px;
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .alert-success {
      background-color: rgba(56, 142, 60, 0.2);
      border-left: 4px solid var(--success);
      color: var(--success);
    }

    .alert-error {
      background-color: rgba(211, 47, 47, 0.2);
      border-left: 4px solid var(--danger);
      color: var(--danger);
    }

    /* Equipment Grid */
    .equipment-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 25px;
      margin: 40px auto;
      max-width: 1200px;
      padding: 0 20px;
    }

    /* Equipment Card */
    .equipment-card {
      background: var(--white);
      border-radius: var(--border-radius);
      box-shadow: var(--box-shadow);
      overflow: hidden;
      transition: var(--transition);
      position: relative;
    }

    .equipment-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 35px rgba(0, 0, 0, 0.12);
    }

    .equipment-image {
      width: 100%;
      height: 200px;
      object-fit: cover;
    }

    .equipment-body {
      padding: 20px;
    }

    .equipment-title {
      font-size: 1.2rem;
      margin-bottom: 10px;
      color: var(--primary-dark);
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .equipment-details {
      margin-bottom: 15px;
    }

    .detail-row {
      display: flex;
      justify-content: space-between;
      margin-bottom: 8px;
    }

    .detail-label {
      color: var(--text-light);
      font-size: 0.9rem;
    }

    .detail-value {
      font-weight: 500;
    }

    .stock-value {
      font-weight: 600;
      color: var(--primary-dark);
    }

    .out-of-stock {
      color: var(--danger);
    }

    .equipment-actions {
      display: flex;
      gap: 10px;
      margin-top: 15px;
    }

    /* Edit Form */
    .edit-form {
      display: none;
      margin-top: 15px;
      padding-top: 15px;
      border-top: 1px dashed #eee;
    }

    .edit-form.active {
      display: block;
    }

    .form-group {
      margin-bottom: 15px;
    }

    .form-group label {
      display: block;
      margin-bottom: 5px;
      font-size: 0.9rem;
      color: var(--text-light);
    }

    .form-control {
      width: 100%;
      padding: 8px 12px;
      border: 1px solid #ddd;
      border-radius: var(--border-radius);
      font-family: inherit;
    }

    .form-actions {
      display: flex;
      gap: 10px;
      margin-top: 15px;
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

    /* Add Equipment Button */
    .add-equipment {
      position: fixed;
      bottom: 30px;
      right: 30px;
      width: 60px;
      height: 60px;
      border-radius: 50%;
      background: var(--primary-color);
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
      cursor: pointer;
      transition: var(--transition);
      z-index: 90;
      text-decoration: none;
    }

    .add-equipment:hover {
      background: var(--primary-dark);
      transform: translateY(-3px) scale(1.05);
    }

    /* Delete Confirmation Modal */
    .modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      z-index: 1000;
      align-items: center;
      justify-content: center;
    }

    .modal-content {
      background: white;
      padding: 30px;
      border-radius: var(--border-radius);
      max-width: 500px;
      width: 90%;
      box-shadow: 0 5px 30px rgba(0, 0, 0, 0.3);
    }

    .modal-title {
      font-size: 1.3rem;
      margin-bottom: 15px;
      color: var(--primary-dark);
    }

    .modal-text {
      margin-bottom: 25px;
      color: var(--text-light);
    }

    .modal-actions {
      display: flex;
      gap: 15px;
      justify-content: flex-end;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .equipment-grid {
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
      
      .equipment-actions {
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
    <a href="logout.php" class="btn btn-primary">
      <i class="fas fa-sign-out-alt"></i> Logout
    </a>
  </div>
</header>

<div class="page-header">
  <h1 class="page-title">Manage Equipment</h1>
  <p class="page-subtitle">Update stock levels and manage your listed equipment</p>
</div>

<?php if ($message): ?>
  <div class="alert alert-success">
    <i class="fas fa-check-circle"></i>
    <span><?php echo $message; ?></span>
  </div>
<?php endif; ?>

<?php if ($error): ?>
  <div class="alert alert-error">
    <i class="fas fa-exclamation-circle"></i>
    <span><?php echo $error; ?></span>
  </div>
<?php endif; ?>

<div class="equipment-grid">
  <?php if (mysqli_num_rows($result) > 0): ?>
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
      <?php 
      $imageData = base64_encode($row['image_data']);
      $imageSrc = 'data:image/jpeg;base64,' . $imageData;
      $isOutOfStock = $row['total_stock'] <= 0;
      ?>
      <div class="equipment-card">
        <img src="<?php echo $imageSrc; ?>" alt="<?php echo htmlspecialchars($row['equipment_name']); ?>" class="equipment-image">
        
        <div class="equipment-body">
          <h3 class="equipment-title">
            <i class="fas fa-tractor"></i>
            <?php echo htmlspecialchars($row['equipment_name']); ?>
          </h3>
          
          <div class="equipment-details">
            <div class="detail-row">
              <span class="detail-label">Current Stock:</span>
              <span class="detail-value stock-value <?php echo $isOutOfStock ? 'out-of-stock' : ''; ?>">
                <?php echo $row['total_stock']; ?>
                <?php if ($isOutOfStock): ?>
                  <i class="fas fa-exclamation-circle" title="Out of stock"></i>
                <?php endif; ?>
              </span>
            </div>
            
            <div class="detail-row">
              <span class="detail-label">Daily Rate:</span>
              <span class="detail-value">₹<?php echo number_format($row['rent_per_day'], 2); ?></span>
            </div>
            
            <div class="detail-row">
              <span class="detail-label">Location:</span>
              <span class="detail-value"><?php echo htmlspecialchars($row['location'] ?? 'N/A'); ?></span>
            </div>
          </div>
          
          <div class="equipment-actions">
            <button class="btn btn-primary" onclick="toggleEditForm('<?php echo $row['equipment_id']; ?>')">
              <i class="fas fa-pen"></i> Edit Stock
            </button>
            
            <button class="btn btn-danger" onclick="confirmDelete('<?php echo $row['equipment_id']; ?>', '<?php echo htmlspecialchars($row['equipment_name']); ?>')">
              <i class="fas fa-trash-alt"></i> Delete
            </button>
          </div>
          
          <form method="POST" id="form-<?php echo $row['equipment_id']; ?>" class="edit-form">
            <input type="hidden" name="equipment_id" value="<?php echo $row['equipment_id']; ?>">
            
            <div class="form-group">
              <label for="stock-<?php echo $row['equipment_id']; ?>">Update Stock Level</label>
              <input type="number" id="stock-<?php echo $row['equipment_id']; ?>" 
                     name="new_stock" min="0" value="<?php echo $row['total_stock']; ?>" 
                     class="form-control" required>
            </div>
            
            <div class="form-actions">
              <button type="button" class="btn btn-outline" onclick="toggleEditForm('<?php echo $row['equipment_id']; ?>')">
                Cancel
              </button>
              <button type="submit" name="update_stock" class="btn btn-primary">
                <i class="fas fa-save"></i> Save Changes
              </button>
            </div>
          </form>
        </div>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <div class="empty-state">
      <div class="empty-icon">
        <i class="fas fa-tractor"></i>
      </div>
      <h3 class="empty-text">You haven't listed any equipment yet</h3>
      <a href="add_equipment.php" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add Equipment
      </a>
    </div>
  <?php endif; ?>
</div>

<!-- Add Equipment Floating Button -->
<a href="add_equipment.php" class="add-equipment">
  <i class="fas fa-plus"></i>
</a>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="modal">
  <div class="modal-content">
    <h3 class="modal-title">Confirm Deletion</h3>
    <p class="modal-text" id="deleteModalText">Are you sure you want to delete this equipment?</p>
    <form id="deleteForm" method="POST">
      <input type="hidden" name="equipment_id" id="deleteEquipmentId">
      <div class="modal-actions">
        <button type="button" class="btn btn-outline" onclick="closeModal()">Cancel</button>
        <button type="submit" name="delete_equipment" class="btn btn-danger">
          <i class="fas fa-trash-alt"></i> Delete
        </button>
      </div>
    </form>
  </div>
</div>

<script>
  // Toggle edit form visibility
  function toggleEditForm(id) {
    const form = document.getElementById(`form-${id}`);
    form.classList.toggle('active');
  }

  // Delete confirmation modal
  function confirmDelete(id, name) {
    const modal = document.getElementById('deleteModal');
    const modalText = document.getElementById('deleteModalText');
    const equipmentIdInput = document.getElementById('deleteEquipmentId');
    
    modalText.textContent = `Are you sure you want to delete "${name}"? This action cannot be undone.`;
    equipmentIdInput.value = id;
    modal.style.display = 'flex';
  }

  function closeModal() {
    document.getElementById('deleteModal').style.display = 'none';
  }

  // Close modal when clicking outside
  window.onclick = function(event) {
    const modal = document.getElementById('deleteModal');
    if (event.target === modal) {
      closeModal();
    }
  }
</script>

</body>
</html>

<?php mysqli_close($conn); ?>