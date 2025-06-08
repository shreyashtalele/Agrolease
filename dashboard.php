<?php
session_start();
include 'db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Agrolease Dashboard</title>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

  <style>
    :root {
      --primary: #16a34a;
      --primary-dark: #15803d;
      --primary-light: #86efac;
      --secondary: #047857;
      --accent: #f59e0b;
      --light: #f8fafc;
      --dark: #1e293b;
      --gray: #64748b;
      --light-gray: #e2e8f0;
      --white: #ffffff;
      --card-bg: rgba(255, 255, 255, 0.8);
      --shadow-sm: 0 1px 2px rgba(0,0,0,0.05);
      --shadow-md: 0 4px 6px rgba(0,0,0,0.05);
      --shadow-lg: 0 10px 15px rgba(0,0,0,0.1);
      --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      --border-radius: 12px;
      --border-radius-lg: 16px;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', sans-serif;
      background-color: #f7fdf9;
      color: var(--dark);
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      line-height: 1.5;
    }

    header {
      background: linear-gradient(135deg, var(--primary), var(--secondary));
      color: var(--white);
      padding: 1rem 2rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      box-shadow: var(--shadow-md);
      position: sticky;
      top: 0;
      z-index: 50;
    }

    .logo-block {
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    .logo-block img {
      width: 48px;
      height: 48px;
      border-radius: var(--border-radius);
      object-fit: cover;
      border: 2px solid rgba(255,255,255,0.2);
      box-shadow: var(--shadow-sm);
    }

    .site-info h1 {
      font-size: 1.25rem;
      font-weight: 700;
      margin: 0;
      letter-spacing: -0.5px;
    }

    .site-info p {
      font-size: 0.75rem;
      color: rgba(255,255,255,0.9);
      font-weight: 300;
    }

    .header-links a {
      color: var(--white);
      font-weight: 500;
      text-decoration: none;
      font-size: 0.9rem;
      margin-left: 1rem;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      transition: var(--transition);
      padding: 0.5rem 1rem;
      border-radius: var(--border-radius);
      background-color: rgba(255,255,255,0.1);
      border: 1px solid rgba(255,255,255,0.2);
    }

    .header-links a:hover {
      background-color: rgba(255,255,255,0.2);
      transform: translateY(-2px);
    }

    main {
      flex: 1;
      padding: 2rem 1.5rem;
      max-width: 1400px;
      margin: 0 auto;
      width: 100%;
    }

    .welcome-section {
      text-align: center;
      margin-bottom: 3rem;
      position: relative;
      padding: 0 1rem;
    }

    .welcome-section h2 {
      font-size: 2rem;
      font-weight: 700;
      margin-bottom: 0.75rem;
      color: var(--dark);
      position: relative;
      display: inline-block;
      background: linear-gradient(90deg, var(--primary), var(--secondary));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .welcome-section p {
      color: var(--gray);
      max-width: 600px;
      margin: 0 auto;
      font-size: 1rem;
    }

    .grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 2rem;
    }

    .card {
      background-color: var(--card-bg);
      padding: 2rem 1.75rem;
      border-radius: var(--border-radius-lg);
      box-shadow: var(--shadow-md);
      text-align: center;
      transition: var(--transition);
      border: 1px solid var(--light-gray);
      position: relative;
      overflow: hidden;
      backdrop-filter: blur(4px);
    }

    .card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 6px;
      background: linear-gradient(90deg, var(--primary), var(--primary-light));
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: var(--shadow-lg);
    }

    .card-icon {
      width: 64px;
      height: 64px;
      margin: 0 auto 1.5rem;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, rgba(22, 163, 74, 0.1), rgba(5, 150, 105, 0.1));
      border-radius: 50%;
    }

    .card i {
      font-size: 1.75rem;
      color: var(--primary);
    }

    .card h3 {
      font-size: 1.25rem;
      margin-bottom: 0.75rem;
      color: var(--dark);
      font-weight: 600;
    }

    .card p {
      color: var(--gray);
      font-size: 0.95rem;
      margin-bottom: 1.75rem;
      line-height: 1.6;
    }

    .card a {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      padding: 0.75rem 1.5rem;
      background-color: var(--primary);
      color: var(--white);
      border-radius: var(--border-radius);
      text-decoration: none;
      font-weight: 500;
      transition: var(--transition);
      width: 100%;
      max-width: 200px;
      margin: 0 auto;
      border: 1px solid transparent;
    }

    .card a:hover {
      background-color: var(--primary-dark);
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(22, 163, 74, 0.2);
      border-color: rgba(255,255,255,0.2);
    }

    footer {
      background: linear-gradient(135deg, var(--primary), var(--secondary));
      color: var(--white);
      text-align: center;
      padding: 2rem 1.5rem;
      font-size: 0.9rem;
      margin-top: 4rem;
    }

    .footer-content {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 0.75rem;
      max-width: 1200px;
      margin: 0 auto;
    }

    .footer-links {
      display: flex;
      gap: 1.5rem;
      margin-top: 1rem;
      flex-wrap: wrap;
      justify-content: center;
    }

    .footer-links a {
      color: var(--white);
      text-decoration: none;
      transition: var(--transition);
      font-size: 0.85rem;
    }

    .footer-links a:hover {
      color: var(--accent);
    }

    .social-links {
      display: flex;
      gap: 1.25rem;
      margin-top: 1.25rem;
    }

    .social-links a {
      color: var(--white);
      font-size: 1.1rem;
      transition: var(--transition);
      width: 36px;
      height: 36px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      background-color: rgba(255,255,255,0.1);
    }

    .social-links a:hover {
      color: var(--accent);
      transform: translateY(-2px);
      background-color: rgba(255,255,255,0.2);
    }

    .copyright {
      margin-top: 1.5rem;
      font-size: 0.8rem;
      opacity: 0.8;
    }

    @media (max-width: 768px) {
      header {
        padding: 1rem;
        flex-direction: column;
        gap: 1rem;
      }
      
      .header-links {
        margin-top: 1rem;
        width: 100%;
        display: flex;
        justify-content: center;
      }
      
      .header-links a {
        margin: 0 0.5rem;
      }
      
      .welcome-section h2 {
        font-size: 1.75rem;
      }
      
      .grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
      }
      
      .card {
        padding: 1.75rem 1.5rem;
      }
    }

    /* Animation */
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .card {
      animation: fadeIn 0.6s ease-out forwards;
      opacity: 0;
    }

    .card:nth-child(1) { animation-delay: 0.1s; }
    .card:nth-child(2) { animation-delay: 0.2s; }
    .card:nth-child(3) { animation-delay: 0.3s; }
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
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Sign Out</a>
  </nav>
</header>

<main>
  <div class="welcome-section">
    <h2>Namaskar Apla Swagat Ahe</h2>
    <p>Access farming equipment, manage payments, and view your rental history all in one place</p>
  </div>
  
  <div class="grid">
    <div class="card">
      <div class="card-icon">
        <i class="fas fa-tractor"></i>
      </div>
      <h3>Rent Equipment</h3>
      <p>Browse and rent the latest farming equipment for your agricultural needs</p>
      <a href="userequipment.php">Get Started <i class="fas fa-arrow-right"></i></a>
    </div>
    
    <div class="card">
      <div class="card-icon">
        <i class="fas fa-money-bill-wave"></i>
      </div>
      <h3>Payment</h3>
      <p>Make secure payments and view your transaction history</p>
      <a href="payment.php">Pay Now <i class="fas fa-arrow-right"></i></a>
    </div>
    
    <div class="card">
      <div class="card-icon">
        <i class="fas fa-box-open"></i>
      </div>
      <h3>Order History</h3>
      <p>Track your previous rentals and equipment usage</p>
      <a href="orderhistory.php">View History <i class="fas fa-arrow-right"></i></a>
    </div>
  </div>
</main>

<footer>
  <div class="footer-content">
    <div class="footer-links">
      <a href="#">About Us</a>
      <a href="#">Services</a>
      <a href="#">Contact</a>
      <a href="#">Privacy Policy</a>
      <a href="#">Terms of Service</a>
    </div>
    <div class="social-links">
      <a href="#"><i class="fab fa-facebook-f"></i></a>
      <a href="#"><i class="fab fa-twitter"></i></a>
      <a href="#"><i class="fab fa-instagram"></i></a>
      <a href="#"><i class="fab fa-linkedin-in"></i></a>
    </div>
    <div class="copyright">
      &copy; 2024 Agrolease. All rights reserved.
    </div>
  </div>
</footer>

</body>
</html>