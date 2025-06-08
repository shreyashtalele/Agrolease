<?php
include 'db.php';

if (isset($_POST['registerBtn'])) {
    $vendor_name = $_POST['full_name'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $house_name = $_POST['house_name'];
    $area = $_POST['area'];
    $village = $_POST['village'];
    $taluka = $_POST['taluka'];
    $pincode = $_POST['pincode'];
    $district = $_POST['district'];
    $state = $_POST['state'];

    if ($password !== $confirm_password) {
        echo '<script>alert("Passwords do not match."); window.history.back();</script>';
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO vendor (vendor_name, email, mobile, password, housename, area, village, taluka, pincode, district, state, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param(
            "sssssssssss",
            $vendor_name,
            $email,
            $mobile,
            $hashed_password,
            $house_name,
            $area,
            $village,
            $taluka,
            $pincode,
            $district,
            $state
        );

        if ($stmt->execute()) {
            echo '<script>alert("Thank You For Registration. You Will get Notified When Your verification Completes."); window.location.href = "vendor_login.php";</script>';
        } else {
            echo '<script>alert("Error: Registration failed. Please try again."); window.history.back();</script>';
        }
        $stmt->close();
    } else {
        echo '<script>alert("Database error: Could not prepare statement."); window.history.back();</script>';
    }

    $conn->close();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Vendor Registration | Agrolease</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <style>
  :root {
    --primary: #2e7d32;
    --primary-dark: #1b5e20;
    --primary-light: #e8f5e9;
    --accent: #FFC107;
    --text-dark: #263238;
    --text-light: #607d8b;
    --white: #ffffff;
    --gray-light: #f5f5f5;
    --error: #f44336;
    --success: #4caf50;
  }

  * {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
  }
  
  body {
    font-family: 'Poppins', sans-serif;
    background-color: #f8faf8;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    color: var(--text-dark);
    line-height: 1.6;
  }
  
  header {
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: var(--white);
    padding: 1.2rem 2rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  }
  
  .logo-container {
    display: flex;
    align-items: center;
    gap: 1rem;
  }
  
  .logo {
    width: 3.5rem;
    height: 3.5rem;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid rgba(255, 255, 255, 0.2);
    transition: transform 0.3s ease;
  }
  
  .logo:hover {
    transform: scale(1.05);
  }
  
  .header-content {
    display: flex;
    flex-direction: column;
  }
  
  .website-name {
    font-size: 1.6rem;
    font-weight: 700;
    letter-spacing: 0.5px;
    text-shadow: 0 2px 4px rgba(0,0,0,0.1);
  }
  
  .tagline {
    font-size: 0.85rem;
    opacity: 0.9;
    margin-top: 0.2rem;
    font-weight: 400;
  }
  
  .container {
    max-width: 800px;
    width: 90%;
    background: var(--white);
    margin: 2.5rem auto 3rem;
    padding: 2rem;
    border-radius: 16px;
    box-shadow: 0 8px 32px rgba(46, 125, 50, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }

  .container:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 40px rgba(46, 125, 50, 0.15);
  }
  
  .registration-header {
    text-align: center;
    margin-bottom: 2.5rem;
  }
  
  .registration-header h2 {
    font-size: 1.8rem;
    color: var(--primary);
    font-weight: 700;
    margin-bottom: 0.5rem;
    position: relative;
    display: inline-block;
  }

  .registration-header h2::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 4px;
    background-color: var(--accent);
    border-radius: 2px;
  }
  
  .registration-header p {
    color: var(--text-light);
    font-size: 0.95rem;
  }
  
  .form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
  }
  
  .form-group {
    position: relative;
    margin-bottom: 1.2rem;
  }
  
  .form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: var(--text-dark);
    font-size: 0.95rem;
  }
  
  .input-wrapper {
    position: relative;
  }
  
  .form-group i {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-light);
    font-size: 1.1rem;
    transition: color 0.3s ease;
  }
  
  .form-group input {
    width: 100%;
    padding: 0.9rem 1rem 0.9rem 2.8rem;
    border: 1px solid #e0e0e0;
    border-radius: 10px;
    font-size: 0.95rem;
    font-weight: 500;
    color: var(--text-dark);
    background: var(--gray-light);
    transition: all 0.3s ease;
  }
  
  .form-group input::placeholder {
    color: #a3a3a3;
    font-weight: 400;
  }
  
  .form-group input:focus {
    border-color: var(--primary);
    outline: none;
    box-shadow: 0 0 0 3px rgba(46, 125, 50, 0.2);
    background: var(--white);
  }
  
  .form-group input:focus + i {
    color: var(--primary);
  }
  
  .password-toggle {
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    color: var(--text-light);
    transition: color 0.3s ease;
  }

  .password-toggle:hover {
    color: var(--primary);
  }
  
  .submit-btn {
    grid-column: 1 / -1;
    margin-top: 1rem;
  }
  
  .registration-form button {
    width: 100%;
    padding: 1.1rem;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: var(--white);
    font-size: 1.05rem;
    font-weight: 600;
    border: none;
    border-radius: 12px;
    cursor: pointer;
    box-shadow: 0 4px 15px rgba(27, 94, 32, 0.3);
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.8rem;
  }
  
  .registration-form button:hover {
    background: linear-gradient(135deg, var(--primary-dark), #145214);
    box-shadow: 0 6px 20px rgba(20, 82, 20, 0.4);
    transform: translateY(-2px);
  }

  .registration-form button:active {
    transform: translateY(0);
  }
  
  .login-link {
    text-align: center;
    margin-top: 1.5rem;
    color: var(--text-light);
    font-size: 0.95rem;
  }
  
  .login-link a {
    color: var(--primary);
    font-weight: 600;
    text-decoration: none;
    transition: color 0.3s ease;
  }
  
  .login-link a:hover {
    color: var(--primary-dark);
    text-decoration: underline;
  }
  
  footer {
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: var(--white);
    text-align: center;
    padding: 1.5rem;
    margin-top: auto;
    font-size: 0.9rem;
  }

  footer p {
    margin-bottom: 0.5rem;
  }

  .footer-links {
    display: flex;
    justify-content: center;
    gap: 1.5rem;
    margin-top: 0.8rem;
  }

  .footer-links a {
    color: var(--white);
    text-decoration: none;
    font-weight: 500;
    transition: opacity 0.3s ease;
  }

  .footer-links a:hover {
    opacity: 0.8;
    text-decoration: underline;
  }
  
  @media (max-width: 768px) {
    .container {
      padding: 1.5rem;
      margin: 1.5rem auto;
    }

    .form-grid {
      grid-template-columns: 1fr;
    }

    header {
      padding: 1rem;
      flex-direction: column;
      text-align: center;
      gap: 0.8rem;
    }

    .logo-container {
      justify-content: center;
    }

    .registration-header h2 {
      font-size: 1.5rem;
    }
  }

  @media (max-width: 480px) {
    .form-group input {
      padding: 0.8rem 0.8rem 0.8rem 2.5rem;
    }

    .registration-form button {
      padding: 1rem;
      font-size: 1rem;
    }
  }
  </style>
</head>
<body>
  <header>
    <div class="logo-container">
      <img src="img/logo.jpeg" alt="Agrolease Logo" class="logo" />
      <div class="header-content">
        <h1 class="website-name">Agrolease</h1>
        <p class="tagline">Harvest Success with Our Innovative Farming Solutions</p>
      </div>
    </div>
  </header>

  <div class="container">
    <form class="registration-form" name="registrationForm" action="" method="post" onsubmit="return validateForm()">
      <div class="registration-header">
        <h2>Vendor Registration</h2>
        <p>Join our platform to grow your business with farmers across the region</p>
      </div>

      <div class="form-grid">
        <div class="form-group">
          <label for="full_name">Full Name</label>
          <div class="input-wrapper">
            <i class="fas fa-user"></i>
            <input type="text" id="full_name" name="full_name" placeholder="Enter your full name" required />
          </div>
        </div>

        <div class="form-group">
          <label for="email">Email Address</label>
          <div class="input-wrapper">
            <i class="fas fa-envelope"></i>
            <input type="email" id="email" name="email" placeholder="Enter your email" required />
          </div>
        </div>

        <div class="form-group">
          <label for="mobile">Mobile Number</label>
          <div class="input-wrapper">
            <i class="fas fa-phone"></i>
            <input type="tel" id="mobile" name="mobile" placeholder="Enter 10-digit number" required />
          </div>
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <div class="input-wrapper">
            <i class="fas fa-lock"></i>
            <input type="password" id="password" name="password" placeholder="Create password" required />
            <i class="fas fa-eye password-toggle" id="togglePassword"></i>
          </div>
        </div>

        <div class="form-group">
          <label for="confirm_password">Confirm Password</label>
          <div class="input-wrapper">
            <i class="fas fa-lock"></i>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required />
            <i class="fas fa-eye password-toggle" id="toggleConfirmPassword"></i>
          </div>
        </div>

        <div class="form-group">
          <label for="house_name">House/Building Name</label>
          <div class="input-wrapper">
            <i class="fas fa-home"></i>
            <input type="text" id="house_name" name="house_name" placeholder="Enter house/building name" required />
          </div>
        </div>

        <div class="form-group">
          <label for="area">Area/Locality</label>
          <div class="input-wrapper">
            <i class="fas fa-map-marker-alt"></i>
            <input type="text" id="area" name="area" placeholder="Enter your area/locality" required />
          </div>
        </div>

        <div class="form-group">
          <label for="village">Village/Town</label>
          <div class="input-wrapper">
            <i class="fas fa-tree"></i>
            <input type="text" id="village" name="village" placeholder="Enter village/town" required />
          </div>
        </div>

        <div class="form-group">
          <label for="taluka">Taluka</label>
          <div class="input-wrapper">
            <i class="fas fa-building"></i>
            <input type="text" id="taluka" name="taluka" placeholder="Enter taluka" required />
          </div>
        </div>

        <div class="form-group">
          <label for="pincode">Pincode</label>
          <div class="input-wrapper">
            <i class="fas fa-map-pin"></i>
            <input type="text" id="pincode" name="pincode" placeholder="Enter 6-digit pincode" required />
          </div>
        </div>

        <div class="form-group">
          <label for="district">District</label>
          <div class="input-wrapper">
            <i class="fas fa-city"></i>
            <input type="text" id="district" name="district" placeholder="Enter district" required />
          </div>
        </div>

        <div class="form-group">
          <label for="state">State</label>
          <div class="input-wrapper">
            <i class="fas fa-flag"></i>
            <input type="text" id="state" name="state" placeholder="Enter state" required />
          </div>
        </div>

        <div class="submit-btn">
          <button type="submit" name="registerBtn">
            <i class="fas fa-user-plus"></i> Register Now
          </button>
        </div>
      </div>

      <div class="login-link">
        Already have an account? <a href="vendor_login.php">Login here</a>
      </div>
    </form>
  </div>

  <footer>
    <p>&copy; 2025 Agrolease. All rights reserved.</p>
    <div class="footer-links">
      <a href="privacy-policy.php">Privacy Policy</a>
      <a href="terms.php">Terms of Service</a>
      <a href="contact.php">Contact Us</a>
    </div>
  </footer>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Toggle password visibility
      const togglePassword = document.getElementById('togglePassword');
      const password = document.getElementById('password');
      const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
      const confirmPassword = document.getElementById('confirm_password');

      togglePassword.addEventListener('click', function() {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        this.classList.toggle('fa-eye-slash');
      });

      toggleConfirmPassword.addEventListener('click', function() {
        const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
        confirmPassword.setAttribute('type', type);
        this.classList.toggle('fa-eye-slash');
      });

      // Real-time validation
      const email = document.getElementById('email');
      const mobile = document.getElementById('mobile');
      const passwordInput = document.getElementById('password');
      const confirmPasswordInput = document.getElementById('confirm_password');

      email.addEventListener('input', validateEmail);
      mobile.addEventListener('input', validateMobile);
      passwordInput.addEventListener('input', validatePassword);
      confirmPasswordInput.addEventListener('input', validateConfirmPassword);

      function validateEmail() {
        const emailRegex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        if (!emailRegex.test(email.value)) {
          email.style.borderColor = 'var(--error)';
        } else {
          email.style.borderColor = '#e0e0e0';
        }
      }

      function validateMobile() {
        const phoneRegex = /^\d{10}$/;
        if (!phoneRegex.test(mobile.value)) {
          mobile.style.borderColor = 'var(--error)';
        } else {
          mobile.style.borderColor = '#e0e0e0';
        }
      }

      function validatePassword() {
        if (passwordInput.value.length > 0 && passwordInput.value.length < 8) {
          passwordInput.style.borderColor = 'var(--error)';
        } else {
          passwordInput.style.borderColor = '#e0e0e0';
        }
        validateConfirmPassword();
      }

      function validateConfirmPassword() {
        if (confirmPasswordInput.value !== passwordInput.value) {
          confirmPasswordInput.style.borderColor = 'var(--error)';
        } else {
          confirmPasswordInput.style.borderColor = '#e0e0e0';
        }
      }

      // Form validation
      window.validateForm = function() {
        const form = document.forms["registrationForm"];
        const emailRegex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        const phoneRegex = /^\d{10}$/;

        if (!emailRegex.test(form.email.value)) {
          alert("Please enter a valid email address.");
          form.email.focus();
          return false;
        }

        if (!phoneRegex.test(form.mobile.value)) {
          alert("Mobile number must be 10 digits.");
          form.mobile.focus();
          return false;
        }

        if (form.password.value.length < 8) {
          alert("Password must be at least 8 characters long.");
          form.password.focus();
          return false;
        }

        if (form.password.value !== form.confirm_password.value) {
          alert("Passwords do not match.");
          form.confirm_password.focus();
          return false;
        }

        return true;
      }
    });
  </script>
</body>
</html>