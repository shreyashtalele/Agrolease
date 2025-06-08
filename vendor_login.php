<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Vendor Login - Agrolease</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    :root {
      --primary-color: #2e7d32;
      --primary-dark: #1b5e20;
      --primary-light: #e8f5e9;
      --accent-color: #ffab00;
      --text-dark: #333;
      --text-light: #555;
      --white: #fff;
      --gray-light: #f5f5f5;
      --error-color: #d32f2f;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, var(--primary-light), #f1f8e9);
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      line-height: 1.6;
    }

    header {
      background-color: var(--primary-color);
      color: var(--white);
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 20px 5%;
      flex-wrap: wrap;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .logo-container {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      overflow: hidden;
      flex-shrink: 0;
      transition: transform 0.3s ease;
    }

    .logo-container:hover {
      transform: scale(1.05);
    }

    .logo {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .header-content {
      flex-grow: 1;
      margin-left: 20px;
    }

    .website-name {
      font-size: 26px;
      font-weight: bold;
      margin-bottom: 4px;
      letter-spacing: 0.5px;
    }

    .tagline {
      font-size: 14px;
      font-style: italic;
      opacity: 0.9;
    }

    .header-link {
      background-color: var(--primary-dark);
      color: var(--white);
      padding: 10px 16px;
      border-radius: 5px;
      text-decoration: none;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .header-link:hover {
      background-color: #0c3b12;
      transform: translateY(-2px);
    }

    .container {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 40px 20px;
    }

    .login-section {
      display: flex;
      flex-wrap: wrap;
      max-width: 900px;
      width: 100%;
      background-color: var(--white);
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      border-radius: 16px;
      overflow: hidden;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .login-section:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
    }

    .user-info, .login-form {
      flex: 1;
      min-width: 300px;
      padding: 40px;
      text-align: center;
    }

    .user-info {
      background-color: var(--primary-light);
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
    }

    .user-info img {
      width: 150px;
      height: 150px;
      border-radius: 50%;
      margin-bottom: 20px;
      border: 4px solid var(--primary-color);
      object-fit: cover;
      transition: transform 0.3s ease;
    }

    .user-info img:hover {
      transform: scale(1.05);
    }

    .user-info p {
      font-size: 20px;
      color: var(--text-dark);
      font-weight: 500;
      margin-bottom: 10px;
    }

    .user-info .welcome-text {
      font-size: 16px;
      color: var(--text-light);
      max-width: 300px;
      margin: 0 auto;
    }

    .login-form h2 {
      font-size: 28px;
      margin-bottom: 30px;
      color: var(--primary-color);
      position: relative;
      display: inline-block;
    }

    .login-form h2::after {
      content: '';
      position: absolute;
      bottom: -10px;
      left: 50%;
      transform: translateX(-50%);
      width: 60px;
      height: 3px;
      background-color: var(--accent-color);
      border-radius: 3px;
    }

    .form-group {
      margin-bottom: 20px;
      text-align: left;
    }

    .form-group label {
      display: block;
      margin-bottom: 8px;
      font-weight: 500;
      color: var(--text-dark);
    }

    .input-wrapper {
      position: relative;
    }

    .input-wrapper i.fas {
      position: absolute;
      top: 50%;
      left: 15px;
      transform: translateY(-50%);
      color: var(--primary-color);
      z-index: 1;
    }

    .login-form input {
      width: 100%;
      padding: 12px 40px 12px 40px; /* Adjusted padding for both sides */
      border: 2px solid #ddd;
      border-radius: 8px;
      font-size: 16px;
      transition: all 0.3s;
      background-color: var(--gray-light);
      position: relative;
    }
    .login-form input:focus {
      border-color: var(--primary-color);
      outline: none;
      box-shadow: 0 0 0 3px rgba(46, 125, 50, 0.2);
      background-color: var(--white);
    }

    .password-toggle {
      position: absolute;
      top: 50%;
      right: 15px;
      transform: translateY(-50%);
      cursor: pointer;
      color: var(--text-light);
      z-index: 1;
    }

    .login-form button {
      width: 100%;
      padding: 14px;
      background-color: var(--primary-color);
      color: var(--white);
      border: none;
      border-radius: 8px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      margin-top: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
    }

    .login-form button:hover {
      background-color: var(--primary-dark);
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .login-form button:active {
      transform: translateY(0);
    }

    .login-form .forgot-password {
      display: block;
      text-align: right;
      margin-top: 5px;
      font-size: 14px;
    }

    .login-form .register-link {
      margin-top: 25px;
      font-size: 15px;
      color: var(--text-light);
    }

    .login-form a {
      color: var(--primary-color);
      font-weight: 500;
      text-decoration: none;
      transition: color 0.3s;
    }

    .login-form a:hover {
      color: var(--primary-dark);
      text-decoration: underline;
    }

    .error-message {
      color: var(--error-color);
      font-size: 14px;
      margin-top: 5px;
      display: none;
    }

    footer {
      background-color: var(--primary-color);
      color: var(--white);
      text-align: center;
      padding: 20px;
      font-size: 14px;
      margin-top: auto;
    }

    .social-icons {
      display: flex;
      justify-content: center;
      gap: 15px;
      margin-top: 20px;
    }

    .social-icons a {
      color: var(--primary-color);
      background-color: var(--white);
      width: 36px;
      height: 36px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.3s ease;
    }

    .social-icons a:hover {
      background-color: var(--primary-dark);
      color: var(--white);
      transform: translateY(-3px);
    }

    @media (max-width: 768px) {
      header {
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: 20px;
      }

      .header-content {
        margin: 15px 0;
      }

      .login-section {
        flex-direction: column;
        max-width: 95%;
      }

      .user-info, .login-form {
        padding: 30px 25px;
      }

      .user-info {
        padding-bottom: 20px;
      }
    }
  </style>
</head>
<body>

<header>
  <div class="logo-container">
    <img src="img/logo.jpeg" alt="Agrolease Logo" class="logo" />
  </div>
  <div class="header-content">
    <h1 class="website-name">Agrolease</h1>
    <p class="tagline">Harvest Success with Our Innovative Farming Solutions</p>
  </div>
  <a href="home.php" class="header-link">
    <i class="fas fa-arrow-left"></i> Back to Home
  </a>
</header>

<div class="container">
  <div class="login-section">
    <div class="user-info">
      <img src="img/farmer.jpeg" alt="Vendor Avatar">
      <p>Welcome Vendor Partner</p>
      <p class="welcome-text">Access your vendor dashboard to manage products, orders, and grow your business with us.</p>
      
      <div class="social-icons">
        <a href="#"><i class="fab fa-facebook-f"></i></a>
        <a href="#"><i class="fab fa-twitter"></i></a>
        <a href="#"><i class="fab fa-linkedin-in"></i></a>
        <a href="#"><i class="fab fa-instagram"></i></a>
      </div>
    </div>
    <div class="login-form">
      <h2>Vendor Login</h2>
      <form id="vendorLoginForm" action="vendor_valid.php" method="post">
        <div class="form-group">
          <label for="email">Email Address</label>
          <div class="input-wrapper">
            <i class="fas fa-envelope"></i>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>
          </div>
          <div id="email-error" class="error-message">Please enter a valid email address</div>
        </div>
        
        <div class="form-group">
          <label for="password">Password</label>
          <div class="input-wrapper">
            <i class="fas fa-lock"></i>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
            <i class="far fa-eye password-toggle" id="togglePassword"></i>
          </div>
          <div id="password-error" class="error-message">Password must be at least 8 characters</div>
          <a href="forgot-password.php" class="forgot-password">Forgot Password?</a>
        </div>
        
        <button type="submit">
          <i class="fas fa-sign-in-alt"></i> Login
        </button>
        
        <p class="register-link">
          New vendor? <a href="vendor_register.php">Create an account</a>
        </p>
      </form>
    </div>
  </div>
</div>

<footer>
  &copy; 2024 Agrolease. All rights reserved. | <a href="privacy-policy.php" style="color: white; text-decoration: underline;">Privacy Policy</a> | <a href="terms.php" style="color: white; text-decoration: underline;">Terms of Service</a>
</footer>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('vendorLoginForm');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const togglePassword = document.getElementById('togglePassword');
    const emailError = document.getElementById('email-error');
    const passwordError = document.getElementById('password-error');
    
    // Toggle password visibility
    togglePassword.addEventListener('click', function() {
      const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordInput.setAttribute('type', type);
      this.classList.toggle('fa-eye-slash');
    });
    
    // Email validation
    emailInput.addEventListener('input', function() {
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(this.value.trim())) {
        emailError.style.display = 'block';
        this.style.borderColor = 'var(--error-color)';
      } else {
        emailError.style.display = 'none';
        this.style.borderColor = '#ddd';
      }
    });
    
    // Password validation
    passwordInput.addEventListener('input', function() {
      if (this.value.length > 0 && this.value.length < 8) {
        passwordError.style.display = 'block';
        this.style.borderColor = 'var(--error-color)';
      } else {
        passwordError.style.display = 'none';
        this.style.borderColor = '#ddd';
      }
    });
    
    // Form submission validation
    form.addEventListener('submit', function(e) {
      let isValid = true;
      
      // Validate email
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(emailInput.value.trim())) {
        emailError.style.display = 'block';
        emailInput.style.borderColor = 'var(--error-color)';
        isValid = false;
      }
      
      // Validate password
      if (passwordInput.value.length < 8) {
        passwordError.style.display = 'block';
        passwordInput.style.borderColor = 'var(--error-color)';
        isValid = false;
      }
      
      if (!isValid) {
        e.preventDefault();
        // Scroll to the first error
        const firstError = document.querySelector('.error-message[style*="display: block"]');
        if (firstError) {
          firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
      }
    });
    
    // Add animation to form inputs on focus
    const inputs = document.querySelectorAll('input');
    inputs.forEach(input => {
      input.addEventListener('focus', function() {
        this.parentNode.querySelector('i').style.color = 'var(--primary-color)';
      });
      
      input.addEventListener('blur', function() {
        this.parentNode.querySelector('i').style.color = 'var(--primary-color)';
      });
    });
  });
</script>

</body>
</html>