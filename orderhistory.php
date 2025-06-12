<?php
session_start();
include 'db.php';

// Check login
if (!isset($_SESSION['user']['userid'])) {
    echo "User ID is missing. Please log in.";
    exit;
}
$userID = $_SESSION['user']['userid'];

// Filters
$filter = $_GET['filter'] ?? 'all';
$dateFilter = '';
switch ($filter) {
    case 'last_15_days':
        $dateFilter = "AND o.created_at >= DATE_SUB(NOW(), INTERVAL 15 DAY)";
        break;
    case 'last_month':
        $dateFilter = "AND o.created_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
        break;
    case 'last_3_months':
        $dateFilter = "AND o.created_at >= DATE_SUB(NOW(), INTERVAL 3 MONTH)";
        break;
}

$sort = $_GET['sort'] ?? 'order_id';
$sortClause = "ORDER BY $sort";

// Query
$query = "SELECT o.*, e.equipment_name, e.rent_per_day 
          FROM orders o
          INNER JOIN equipment e ON o.equipment_id = e.equipment_id
          WHERE o.user_id = '$userID' $dateFilter $sortClause";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order History - Agrolease</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS + FontAwesome + DataTables -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />

    <style>
        :root {
            --primary-color: #2e7d32;
            --primary-light: #60ad5e;
            --primary-dark: #005005;
            --secondary-color: #f5f5f5;
            --accent-color: #ffc107;
            --text-dark: #333;
            --text-light: #666;
            --border-radius: 12px;
            --box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9fafb;
            color: var(--text-dark);
            line-height: 1.6;
        }
        
      header {
    background-color: #006400;
    color: #fff;
    padding: 20px 0;
    width: 100%;
}

.container-fluid {
    padding: 0 40px;
}

.header-link {
    color: #b2d8b2;
    text-decoration: none;
    background-color: #004d00;
    padding: 8px 14px;
    border-radius: 5px;
    font-weight: 500;
    transition: background-color 0.3s ease;
    display: flex;
    align-items: center;
    gap: 5px;
}

.header-link:hover {
    background-color: #003300;
    color: #fff;
}

.header-link i {
    font-size: 18px;
}
        .logo-container {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            overflow: hidden;
            border: 3px solid white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: var(--transition);
        }
        
        .logo-container:hover {
            transform: scale(1.05);
        }
        
        .logo-container img {
            width: 100%;
            height: auto;
            object-fit: cover;
        }
        
        .header-content h1 {
            margin: 0;
            font-size: 1.8rem;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        
        .header-content p {
            margin: 0.2rem 0 0;
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        .container {
            margin-top: 2.5rem;
            margin-bottom: 3rem;
        }
        
        .page-title {
            font-weight: 600;
            color: var(--primary-dark);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }
        
        .page-title i {
            font-size: 1.8rem;
            color: var(--primary-color);
        }
        
        .filter-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .form-label {
            font-weight: 500;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }
        
        .form-select, .form-control {
            border-radius: 8px;
            border: 1px solid #ddd;
            padding: 0.6rem 1rem;
            font-size: 0.95rem;
            transition: var(--transition);
        }
        
        .form-select:focus, .form-control:focus {
            border-color: var(--primary-light);
            box-shadow: 0 0 0 0.25rem rgba(46, 125, 50, 0.15);
        }
        
        .btn-success {
            background-color: var(--primary-color);
            border: none;
            border-radius: 8px;
            padding: 0.6rem 1.2rem;
            font-weight: 500;
            transition: var(--transition);
        }
        
        .btn-success:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
        }
        
        .btn-success:active {
            transform: translateY(0);
        }
        
        .table-responsive {
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--box-shadow);
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .table thead th {
            background-color: var(--primary-color);
            color: white;
            font-weight: 500;
            padding: 1rem 1.25rem;
            border: none;
        }
        
        .table tbody tr {
            transition: var(--transition);
        }
        
        .table tbody tr:hover {
            background-color: rgba(46, 125, 50, 0.05);
        }
        
        .table td {
            padding: 1rem 1.25rem;
            vertical-align: middle;
            border-color: #f0f0f0;
        }
        
        .badge {
            font-weight: 500;
            padding: 0.5rem 0.8rem;
            border-radius: 50px;
            font-size: 0.8rem;
            text-transform: capitalize;
        }
        
        .badge-success {
            background-color: rgba(46, 125, 50, 0.1);
            color: var(--primary-color);
        }
        
        .badge-warning {
            background-color: rgba(255, 193, 7, 0.1);
            color: #ff9800;
        }
        
        .badge-danger {
            background-color: rgba(244, 67, 54, 0.1);
            color: #f44336;
        }
        
        .badge-secondary {
            background-color: rgba(158, 158, 158, 0.1);
            color: #9e9e9e;
        }
        
        .btn-sm {
            padding: 0.35rem 0.75rem;
            font-size: 0.85rem;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }
        
        .btn-primary {
            background-color: #1976d2;
            border: none;
        }
        
        .btn-primary:hover {
            background-color: #1565c0;
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem 0;
        }
        
        .empty-state i {
            font-size: 4rem;
            color: #e0e0e0;
            margin-bottom: 1.5rem;
        }
        
        .empty-state h4 {
            color: var(--text-light);
            font-weight: 500;
            margin-bottom: 0.5rem;
        }
        
        .empty-state p {
            color: #999;
            font-size: 0.95rem;
        }
        
        @media (max-width: 768px) {
            .header-content h1 {
                font-size: 1.5rem;
            }
            
            .page-title {
                font-size: 1.5rem;
            }
            
            .filter-card {
                padding: 1rem;
            }
            
            .table thead th, .table td {
                padding: 0.75rem;
            }
        }
    </style>
</head>
<body>
<header>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-4">
                <div class="logo-container">
                    <img src="img/logo.jpeg" alt="Agrolease Logo">
                </div>
                <div class="header-content">
                    <h1>Agrolease</h1>
                    <p>Harvest Success with Our Innovative Farming Solutions</p>
                </div>
            </div>
            <a href="dashboard.php" class="header-link">
                <i class="material-icons">arrow_back</i> Back
            </a>
        </div>
    </div>
</header>

<div class="container">
    <h1 class="page-title">
        <i class="fas fa-clipboard-list"></i>
        Order History
    </h1>

    <div class="filter-card">
        <form class="row g-3" method="GET">
            <div class="col-md-4">
                <label for="filter" class="form-label">Time Period</label>
                <select name="filter" id="filter" class="form-select">
                    <option value="all" <?= $filter == 'all' ? 'selected' : '' ?>>All Orders</option>
                    <option value="last_15_days" <?= $filter == 'last_15_days' ? 'selected' : '' ?>>Last 15 Days</option>
                    <option value="last_month" <?= $filter == 'last_month' ? 'selected' : '' ?>>Last Month</option>
                    <option value="last_3_months" <?= $filter == 'last_3_months' ? 'selected' : '' ?>>Last 3 Months</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="sort" class="form-label">Sort By</label>
                <select name="sort" id="sort" class="form-select">
                    <option value="order_id" <?= $sort == 'order_id' ? 'selected' : '' ?>>Order ID</option>
                    <option value="created_at" <?= $sort == 'created_at' ? 'selected' : '' ?>>Order Date (Newest)</option>
                    <option value="created_at DESC" <?= $sort == 'created_at DESC' ? 'selected' : '' ?>>Order Date (Oldest)</option>
                    <option value="price" <?= $sort == 'price' ? 'selected' : '' ?>>Total Price (Low to High)</option>
                    <option value="price DESC" <?= $sort == 'price DESC' ? 'selected' : '' ?>>Total Price (High to Low)</option>
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-success w-100">
                    <i class="fas fa-filter me-2"></i> Apply Filters
                </button>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table id="orderTable" class="table table-hover">
            <thead>
            <tr>
                <th><i class="fas fa-hashtag me-2"></i>Order ID</th>
                <th><i class="far fa-calendar-alt me-2"></i>Date</th>
                <th><i class="fas fa-tractor me-2"></i>Equipment</th>
                <th><i class="fas fa-tag me-2"></i>Rent/Day</th>
                <th><i class="fas fa-receipt me-2"></i>Total</th>
                <th><i class="fas fa-info-circle me-2"></i>Status</th>
                <th><i class="fas fa-cog me-2"></i>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $status = ucfirst($row['status']);
                    $paymentStatus = strtolower($row['payment_status']);
                    $badgeClass = match ($status) {
                        'Confirmed' => 'success',
                        'Pending' => 'warning',
                        'Cancelled' => 'danger',
                        default => 'secondary',
                    };

                    echo "<tr>
                        <td class='fw-semibold'>#{$row['order_id']}</td>
                        <td>" . date('d M Y', strtotime($row['created_at'])) . "</td>
                        <td>{$row['equipment_name']}</td>
                        <td>₹" . number_format($row['rent_per_day'], 2) . "</td>
                        <td class='fw-semibold'>₹" . number_format($row['price'], 2) . "</td>
                        <td><span class='badge badge-{$badgeClass}'><i class='fas fa-circle me-1' style='font-size: 0.5rem; vertical-align: middle;'></i> {$status}</span></td>
                        <td>";

                    if ($status === 'Confirmed' && $paymentStatus === 'pending') {
                        echo "<a href='payment.php?order_id={$row['order_id']}' class='btn btn-sm btn-primary'>
                                <i class='fas fa-credit-card me-1'></i> Pay Now
                              </a>";
                    } elseif ($status === 'Confirmed' && $paymentStatus === 'success') {
                        echo "<span class='badge badge-success'><i class='fas fa-check-circle me-1'></i> Paid</span>";
                    } else {
                        echo "<span class='badge badge-secondary'><i class='fas fa-clock me-1'></i> Processing</span>";
                    }

                    echo "</td></tr>";
                }
            } else {
                echo '<tr><td colspan="7">
                        <div class="empty-state">
                            <i class="far fa-clipboard"></i>
                            <h4>No Orders Found</h4>
                            <p>You haven\'t placed any orders yet. Start renting equipment to see them here.</p>
                            <a href="equipment.php" class="btn btn-success mt-3">
                                <i class="fas fa-tractor me-2"></i>Browse Equipment
                            </a>
                        </div>
                    </td></tr>';
            }
            ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function () {
        $('#orderTable').DataTable({
            responsive: true,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search orders...",
                emptyTable: "No orders available",
                info: "Showing _START_ to _END_ of _TOTAL_ orders",
                infoEmpty: "Showing 0 to 0 of 0 orders",
                lengthMenu: "Show _MENU_ orders",
                paginate: {
                    first: "First",
                    last: "Last",
                    next: "Next",
                    previous: "Previous"
                }
            },
            dom: '<"top"f>rt<"bottom"lip><"clear">',
            initComplete: function() {
                $('.dataTables_filter input').addClass('form-control');
                $('.dataTables_length select').addClass('form-select');
            }
        });
    });
</script>

</body>
</html>