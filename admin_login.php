<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Login - Agrolease</title>
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    :root {
      --primary: #2e7d32;
      --primary-light: #e8f5e9;
      --primary-dark: #1b5e20;
      --secondary: #f5f5f5;
      --text: #333333;
      --text-light: #666666;
      --white: #ffffff;
      --shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
      --radius: 12px;
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
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      line-height: 1.6;
    }

    header {
      background: linear-gradient(135deg, var(--primary), var(--primary-dark));
      color: var(--white);
      padding: 1.5rem 2rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      box-shadow: var(--shadow);
      position: relative;
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

    .back-btn {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      background-color: rgba(255, 255, 255, 0.1);
      color: var(--white);
      padding: 0.6rem 1.2rem;
      border-radius: var(--radius);
      text-decoration: none;
      font-weight: 500;
      transition: var(--transition);
    }

    .back-btn:hover {
      background-color: rgba(255, 255, 255, 0.2);
      transform: translateY(-2px);
    }

    main {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 2rem;
    }

    .login-container {
      width: 100%;
      max-width: 450px;
    }

    .login-card {
      background: var(--white);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      padding: 2.5rem;
      text-align: center;
      transition: var(--transition);
    }

    .login-card:hover {
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    }

    .login-header {
      margin-bottom: 2rem;
    }

    .login-header h2 {
      font-size: 1.75rem;
      font-weight: 700;
      color: var(--primary-dark);
      margin-bottom: 0.5rem;
    }

    .login-header p {
      color: var(--text-light);
      font-size: 0.9rem;
    }

    .login-icon {
      width: 80px;
      height: 80px;
      margin: 0 auto 1.5rem;
      background-color: var(--primary-light);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--primary);
      font-size: 2rem;
    }

    .form-group {
      margin-bottom: 1.5rem;
      text-align: left;
    }

    .form-group label {
      display: block;
      margin-bottom: 0.5rem;
      font-weight: 500;
      color: var(--text);
    }

    .input-field {
      position: relative;
    }

    .input-field input {
      width: 100%;
      padding: 0.9rem 1rem 0.9rem 3rem;
      border: 1px solid #e0e0e0;
      border-radius: var(--radius);
      font-size: 1rem;
      transition: var(--transition);
    }

    .input-field input:focus {
      border-color: var(--primary);
      outline: none;
      box-shadow: 0 0 0 3px rgba(46, 125, 50, 0.2);
    }

    .input-icon {
      position: absolute;
      left: 1rem;
      top: 50%;
      transform: translateY(-50%);
      color: var(--text-light);
    }

    .login-btn {
      width: 100%;
      background: linear-gradient(to right, var(--primary), var(--primary-dark));
      color: var(--white);
      padding: 1rem;
      border: none;
      border-radius: var(--radius);
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      transition: var(--transition);
      margin-top: 1rem;
    }

    .login-btn:hover {
      background: linear-gradient(to right, var(--primary-dark), var(--primary));
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(46, 125, 50, 0.2);
    }

    .forgot-link {
      display: block;
      text-align: right;
      margin-top: 0.5rem;
      color: var(--primary);
      font-size: 0.85rem;
      text-decoration: none;
      transition: var(--transition);
    }

    .forgot-link:hover {
      text-decoration: underline;
    }

    footer {
      background: linear-gradient(135deg, var(--primary), var(--primary-dark));
      color: var(--white);
      text-align: center;
      padding: 1.5rem;
      font-size: 0.9rem;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
      header {
        flex-direction: column;
        gap: 1rem;
        padding: 1.5rem 1rem;
      }

      .brand {
        flex-direction: column;
        text-align: center;
      }

      .login-card {
        padding: 2rem 1.5rem;
      }
    }

    @media (max-width: 480px) {
      .login-card {
        padding: 1.5rem 1rem;
      }

      .login-icon {
        width: 70px;
        height: 70px;
        font-size: 1.8rem;
      }
    }
  </style>
</head>
<body>
  <header>
    <div class="brand">
      <img src="img/logo.jpeg" alt="Agrolease Logo" class="logo">
      <div class="brand-text">
        <h1>Agrolease</h1>
        <p>Harvest Success with Innovative Farming Solutions</p>
      </div>
    </div>
    <a href="home.php" class="back-btn">
      <i class="fas fa-arrow-left"></i> Back to Home
    </a>
  </header>

  <main>
    <div class="login-container">
      <div class="login-card">
        <div class="login-icon">
          <i class="fas fa-lock"></i>
        </div>
        <div class="login-header">
          <h2>Admin Login</h2>
          <p>Access your admin dashboard</p>
        </div>
        
        <form action="admin_valid.php" method="post" onsubmit="return validateForm()">
          <div class="form-group">
            <label for="email">Email Address</label>
            <div class="input-field">
              <i class="fas fa-envelope input-icon"></i>
              <input type="email" id="email" name="email" placeholder="admin@example.com" required>
            </div>
          </div>
          
          <div class="form-group">
            <label for="password">Password</label>
            <div class="input-field">
              <i class="fas fa-key input-icon"></i>
              <input type="password" id="password" name="password" placeholder="••••••••" required>
            </div>
            <a href="forgot_password.php" class="forgot-link">Forgot password?</a>
          </div>
          
          <button type="submit" class="login-btn">
            <i class="fas fa-sign-in-alt"></i> Login
          </button>
        </form>
      </div>
    </div>
  </main>

  <footer>
    <p>&copy; <script>document.write(new Date().getFullYear())</script> Agrolease. All rights reserved.</p>
  </footer>

  <script>
    function validateForm() {
      const email = document.getElementById('email').value.trim();
      const password = document.getElementById('password').value.trim();
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

      if (!emailRegex.test(email)) {
        alert('Please enter a valid email address');
        return false;
      }

      if (password.length < 6) {
        alert('Password must be at least 6 characters long');
        return false;
      }

      return true;
    }
  </script>
</body>
</html>