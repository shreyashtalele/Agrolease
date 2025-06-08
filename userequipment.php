<?php
session_start();
include 'db.php';

// Fetch user location from session
$userArea = $_SESSION['user']['area'];
$userVillage = $_SESSION['user']['village'];
$userTaluka = $_SESSION['user']['taluka'];
$userPincode = $_SESSION['user']['pincode'];

// Base query
$query = "SELECT e.*, v.vendor_name, c.categoryname FROM equipment e 
          INNER JOIN vendor v ON e.vendor_id = v.vendorid 
          INNER JOIN category c ON e.category_id = c.categoryid
          WHERE v.area = '$userArea' AND v.village = '$userVillage' 
          AND v.taluka = '$userTaluka' AND v.pincode = '$userPincode'";

// Apply filters
if (isset($_GET['categories']) && !empty($_GET['categories'])) {
    $categories = array_map('intval', $_GET['categories']);
    $categoryList = implode(",", $categories);
    $query .= " AND e.category_id IN ($categoryList)";
}

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Agrolease Equipment</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --primary: #2e7d32;
            --primary-dark: #1b5e20;
            --primary-light: #81c784;
            --accent: #ffab00;
            --text: #263238;
            --text-light: #607d8b;
            --bg: #f5f7f6;
            --card-bg: #ffffff;
            --border: #e0e0e0;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.05);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.08);
            --shadow-lg: 0 15px 35px rgba(0,0,0,0.12);
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg);
            color: var(--text);
            line-height: 1.6;
        }

        header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 1.5rem 2rem;
            display: flex;
            align-items: center;
            box-shadow: var(--shadow-md);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .logo-container {
            width: 60px;
            height: 60px;
            border-radius: var(--radius-sm);
            overflow: hidden;
            background: white;
            padding: 4px;
            box-shadow: var(--shadow-sm);
        }

        .logo-container img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: calc(var(--radius-sm) - 2px);
        }

        .header-content {
            flex-grow: 1;
            margin-left: 1.25rem;
        }

        .website-name {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
            letter-spacing: -0.5px;
        }

        .tagline {
            font-size: 0.85rem;
            margin-top: 0.25rem;
            opacity: 0.9;
            font-weight: 300;
        }

        .header-link {
            text-decoration: none;
            background-color: rgba(255,255,255,0.15);
            padding: 0.75rem 1.5rem;
            color: white;
            border-radius: var(--radius-sm);
            font-weight: 500;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .header-link:hover {
            background-color: rgba(255,255,255,0.25);
            transform: translateY(-2px);
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }

        .filter-section {
            background: var(--card-bg);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
            padding: 1.5rem;
            margin-bottom: 2rem;
            border: 1px solid var(--border);
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--primary-dark);
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .section-title i {
            color: var(--primary);
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 0.75rem;
        }

        .filter-label {
            background-color: var(--card-bg);
            padding: 0.75rem 1rem;
            border-radius: var(--radius-sm);
            cursor: pointer;
            display: flex;
            align-items: center;
            font-size: 0.9rem;
            border: 1px solid var(--border);
            transition: var(--transition);
            user-select: none;
        }

        .filter-label:hover {
            border-color: var(--primary-light);
            box-shadow: 0 0 0 3px rgba(129, 199, 132, 0.2);
        }

        input[type='checkbox'] {
            appearance: none;
            -webkit-appearance: none;
            width: 18px;
            height: 18px;
            border: 2px solid var(--border);
            border-radius: 4px;
            margin-right: 0.75rem;
            position: relative;
            transition: var(--transition);
            cursor: pointer;
        }

        input[type='checkbox']:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        input[type='checkbox']:checked::after {
            content: '\f00c';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            color: white;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 0.7rem;
        }

        .filter-actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 1.5rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            font-size: 0.95rem;
            font-weight: 500;
            border-radius: var(--radius-sm);
            cursor: pointer;
            transition: var(--transition);
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(46, 125, 50, 0.3);
        }

        .equipment-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .equipment-card {
            background-color: var(--card-bg);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
            transition: var(--transition);
            border: 1px solid var(--border);
            display: flex;
            flex-direction: column;
        }

        .equipment-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary-light);
        }

        .card-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-bottom: 1px solid var(--border);
        }

        .card-body {
            padding: 1.25rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .card-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--text);
        }

        .card-text {
            font-size: 0.9rem;
            color: var(--text-light);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .card-text i {
            width: 18px;
            color: var(--primary);
        }

        .card-footer {
            margin-top: auto;
            padding-top: 1rem;
        }

        .btn-block {
            display: block;
            width: 100%;
            text-align: center;
        }

        .badge {
            display: inline-block;
            padding: 0.35rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 50px;
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .badge-success {
            background-color: #e8f5e9;
            color: var(--primary-dark);
        }

        .badge-danger {
            background-color: #ffebee;
            color: #c62828;
        }

        .badge-warning {
            background-color: #fff8e1;
            color: #ff8f00;
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            grid-column: 1 / -1;
        }

        .empty-icon {
            font-size: 3rem;
            color: var(--text-light);
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .empty-text {
            font-size: 1.1rem;
            color: var(--text-light);
            margin-bottom: 1.5rem;
        }

        .btn-secondary {
            background-color: var(--bg);
            color: var(--text);
            border: 1px solid var(--border);
        }

        .btn-secondary:hover {
            background-color: #e8e8e8;
        }

        footer {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 2rem;
            text-align: center;
            margin-top: 3rem;
        }

        .footer-content {
            max-width: 800px;
            margin: 0 auto;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            margin: 1.5rem 0;
        }

        .footer-link {
            color: white;
            text-decoration: none;
            transition: var(--transition);
        }

        .footer-link:hover {
            color: var(--accent);
        }

        .copyright {
            font-size: 0.9rem;
            opacity: 0.8;
        }

        @media (max-width: 768px) {
            header {
                padding: 1rem;
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }

            .header-content {
                margin-left: 0;
            }

            .header-link {
                margin-top: 1rem;
            }

            .container {
                padding: 1.5rem;
            }

            .filter-grid {
                grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            }

            .equipment-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .equipment-card {
            animation: fadeIn 0.4s ease-out forwards;
            opacity: 0;
        }

        .equipment-card:nth-child(1) { animation-delay: 0.1s; }
        .equipment-card:nth-child(2) { animation-delay: 0.2s; }
        .equipment-card:nth-child(3) { animation-delay: 0.3s; }
        .equipment-card:nth-child(4) { animation-delay: 0.4s; }
        .equipment-card:nth-child(5) { animation-delay: 0.5s; }
        .equipment-card:nth-child(6) { animation-delay: 0.6s; }
    </style>
</head>
<body>

<header>
    <div class="logo-container">
        <img src="img/logo.jpeg" alt="Agrolease Logo">
    </div>
    <div class="header-content">
        <h1 class="website-name">Agrolease</h1>
        <p class="tagline">Harvest Success with Our Innovative Farming Solutions</p>
    </div>
    <a href="dashboard.php" class="header-link">
        <i class="fas fa-arrow-left"></i> Back to Dashboard
    </a>
</header>

<div class="container">
    <section class="filter-section">
        <h2 class="section-title">
            <i class="fas fa-sliders-h"></i> Filter Equipment
        </h2>
        <form method="GET">
            <div class="filter-grid">
                <?php
                $categoryQuery = "SELECT * FROM category";
                $categoryResult = mysqli_query($conn, $categoryQuery);

                if ($categoryResult && mysqli_num_rows($categoryResult) > 0) {
                    while ($category = mysqli_fetch_assoc($categoryResult)) {
                        $checked = (isset($_GET['categories']) && in_array($category['categoryid'], $_GET['categories'])) ? "checked" : "";
                        echo "<label class='filter-label'>
                                <input type='checkbox' name='categories[]' value='" . $category['categoryid'] . "' $checked>
                                " . $category['categoryname'] . "
                              </label>";
                    }
                }
                ?>
            </div>
            <div class="filter-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter"></i> Apply Filters
                </button>
            </div>
        </form>
    </section>

    <section class="equipment-section">
        <?php if (mysqli_num_rows($result) > 0) { ?>
            <div class="equipment-grid">
                <?php while ($row = mysqli_fetch_assoc($result)) {
                    $imageData = base64_encode($row['image_data']);
                    $stock = (int)$row['total_stock'];
                    $price = (float)$row['rent_per_day'];
                    $disabled = $stock === 0 || $price === 0;
                    ?>
                    <div class="equipment-card">
                        <img src="data:image/jpeg;base64,<?= $imageData ?>" 
                             alt="<?= htmlspecialchars($row['equipment_name']) ?>" 
                             class="card-image">
                        <div class="card-body">
                            <h3 class="card-title"><?= htmlspecialchars($row['equipment_name']) ?></h3>
                            <p class="card-text">
                                <i class="fas fa-tag"></i>
                                <?= $row['categoryname'] ?>
                            </p>
                            <p class="card-text">
                                <i class="fas fa-store"></i>
                                <?= htmlspecialchars($row['vendor_name']) ?>
                            </p>
                            <p class="card-text">
                                <i class="fas fa-indian-rupee-sign"></i>
                                <strong>Price:</strong> <?= $price > 0 ? "₹$price/day" : "Contact Vendor" ?>
                            </p>
                            <?php if ($stock > 0): ?>
                                <span class="badge badge-success">
                                    <i class="fas fa-check-circle"></i> In Stock: <?= $stock ?>
                                </span>
                            <?php else: ?>
                                <span class="badge badge-danger">
                                    <i class="fas fa-times-circle"></i> Out of Stock
                                </span>
                            <?php endif; ?>
                        </div>
                        <div class="card-footer">
                            <?php if (!$disabled): ?>
                                <a href="rent.php?equipment_id=<?= $row['equipment_id'] ?>" class="btn btn-primary btn-block">
                                    <i class="fas fa-shopping-cart"></i> Rent Now
                                </a>
                            <?php else: ?>
                                <button class="btn btn-secondary btn-block" disabled>
                                    <i class="fas fa-ban"></i> Not Available
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } else { ?>
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-tractor"></i>
                </div>
                <h3 class="empty-text">No equipment found matching your criteria</h3>
                <a href="userequipment.php" class="btn btn-primary">
                    <i class="fas fa-sync-alt"></i> Reset Filters
                </a>
            </div>
        <?php } ?>
    </section>
</div>

<footer>
    <div class="footer-content">
        <div class="footer-links">
            <a href="#" class="footer-link">About Us</a>
            <a href="#" class="footer-link">Contact</a>
            <a href="#" class="footer-link">Terms</a>
            <a href="#" class="footer-link">Privacy</a>
        </div>
        <p class="copyright">
            &copy; <?= date("Y") ?> Agrolease. All rights reserved.
        </p>
    </div>
</footer>

<?php
mysqli_close($conn);
?>
</body>
</html>