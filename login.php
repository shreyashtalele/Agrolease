<?php
session_start();
include 'db.php';

$error = "";
$success = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $error = "Please fill in all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {
        // Prepare and execute query securely to prevent SQL injection
        $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $user_row = $result->fetch_assoc();

            // Simple password check (consider hashing in real apps)
            if ($password === $user_row['password']) {
                $_SESSION['user'] = [
                    'userid' => $user_row['userid'],
                    'name' => $user_row['full_name'],
                    'email' => $user_row['email'],
                    'mobile' => $user_row['mobile'],
                    'house_name' => $user_row['house_name'],
                    'area' => $user_row['area'],
                    'village' => $user_row['village'],
                    'taluka' => $user_row['taluka'],
                    'pincode' => $user_row['pincode']
                ];

                // Redirect to dashboard immediately
                header("Location: dashboard.php");
                exit;
            } else {
                $error = "Incorrect password.";
            }
        } else {
            $error = "Email not found.";
        }
        $stmt->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login - Agrolease</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    :root {
      --primary-color: #2e7d32;
      --primary-dark: #1b5e20;
      --primary-light: #e0f5e9;
      --secondary-color: #ff9800;
      --text-dark: #333;
      --text-light: #666;
      --white: #ffffff;
      --light-bg: #f9f9f9;
      --error-bg: #f8d7da;
      --error-text: #721c24;
      --success-bg: #d4edda;
      --success-text: #155724;
      --shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
      --transition: all 0.3s ease;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, var(--primary-light), var(--white));
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      color: var(--text-dark);
      line-height: 1.6;
    }

    header {
      background-color: var(--primary-color);
      color: var(--white);
      padding: 1rem 2rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      box-shadow: var(--shadow);
    }

    .logo-container {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      overflow: hidden;
      transition: var(--transition);
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
      margin-left: 1.5rem;
    }

    .website-name {
      font-size: 1.8rem;
      font-weight: 700;
      margin-bottom: 0.25rem;
      letter-spacing: 0.5px;
    }

    .tagline {
      font-size: 0.9rem;
      opacity: 0.9;
      font-weight: 300;
    }

    .back-link {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      background-color: var(--primary-dark);
      color: var(--white);
      padding: 0.6rem 1.2rem;
      border-radius: 50px;
      text-decoration: none;
      font-size: 0.9rem;
      transition: var(--transition);
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .back-link:hover {
      background-color: #0d3c10;
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .container {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 2rem;
    }

    .login-section {
      display: flex;
      flex-wrap: wrap;
      max-width: 900px;
      width: 100%;
      background-color: var(--white);
      box-shadow: var(--shadow);
      border-radius: 16px;
      overflow: hidden;
      transition: var(--transition);
    }

    .login-section:hover {
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
    }

    .user-info, .login-form {
      flex: 1;
      min-width: 300px;
      padding: 2.5rem;
      text-align: center;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .user-info {
      background: linear-gradient(135deg, rgba(46, 125, 50, 0.9), rgba(27, 94, 32, 0.95));
      color: var(--white);
    }

    .user-info img {
      width: 140px;
      height: 140px;
      border-radius: 50%;
      margin: 0 auto 1.5rem;
      border: 4px solid rgba(255, 255, 255, 0.3);
      object-fit: cover;
      transition: var(--transition);
    }

    .user-info img:hover {
      transform: scale(1.05);
      border-color: var(--white);
    }

    .user-info h2 {
      font-size: 1.5rem;
      margin-bottom: 0.5rem;
      font-weight: 600;
    }

    .user-info p {
      font-size: 1rem;
      opacity: 0.9;
      margin-bottom: 1.5rem;
    }

    .login-form h2 {
      font-size: 1.8rem;
      margin-bottom: 1.5rem;
      color: var(--primary-color);
      position: relative;
      display: inline-block;
    }

    .login-form h2::after {
      content: '';
      position: absolute;
      bottom: -8px;
      left: 50%;
      transform: translateX(-50%);
      width: 60px;
      height: 3px;
      background-color: var(--secondary-color);
      border-radius: 3px;
    }

    .form-group {
      margin-bottom: 1.25rem;
      position: relative;
    }

    .form-group i {
      position: absolute;
      left: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--text-light);
    }

    .login-form input {
      width: 100%;
      padding: 0.9rem 1rem 0.9rem 2.5rem;
      margin: 0.25rem 0;
      border: 1px solid #ddd;
      border-radius: 8px;
      font-size: 0.95rem;
      transition: var(--transition);
      background-color: var(--light-bg);
    }

    .login-form input:focus {
      border-color: var(--primary-color);
      outline: none;
      box-shadow: 0 0 0 3px rgba(46, 125, 50, 0.2);
      background-color: var(--white);
    }

    .login-form button {
      width: 100%;
      padding: 0.9rem;
      background-color: var(--primary-color);
      color: var(--white);
      border: none;
      border-radius: 8px;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      transition: var(--transition);
      margin-top: 0.5rem;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
    }

    .login-form button:hover {
      background-color: var(--primary-dark);
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .login-form button:active {
      transform: translateY(0);
    }

    .login-form p {
      margin-top: 1.5rem;
      font-size: 0.9rem;
      color: var(--text-light);
    }

    .login-form a {
      color: var(--primary-color);
      text-decoration: none;
      font-weight: 500;
      transition: var(--transition);
    }

    .login-form a:hover {
      color: var(--primary-dark);
      text-decoration: underline;
    }

    .forgot-password {
      display: block;
      text-align: right;
      font-size: 0.85rem;
      margin-top: -0.75rem;
      margin-bottom: 1rem;
    }

    footer {
      background-color: var(--primary-color);
      color: var(--white);
      text-align: center;
      padding: 1.25rem;
      font-size: 0.85rem;
    }

    .social-icons {
      display: flex;
      justify-content: center;
      gap: 1rem;
      margin-top: 1.5rem;
    }

    .social-icons a {
      color: var(--white);
      background-color: rgba(255, 255, 255, 0.1);
      width: 36px;
      height: 36px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: var(--transition);
    }

    .social-icons a:hover {
      background-color: rgba(255, 255, 255, 0.2);
      transform: translateY(-3px);
    }

    /* Message box styles */
    .message-box {
      max-width: 100%;
      margin: 0 auto 1.5rem;
      padding: 0.75rem 1.25rem;
      border-radius: 8px;
      font-weight: 500;
      text-align: center;
      box-sizing: border-box;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
    }

    .message-box.error {
      background-color: var(--error-bg);
      color: var(--error-text);
      border: 1px solid #f5c6cb;
    }

    .message-box.success {
      background-color: var(--success-bg);
      color: var(--success-text);
      border: 1px solid #c3e6cb;
    }

    /* Responsive styles */
    @media (max-width: 768px) {
      header {
        padding: 1rem;
      }

      .header-content {
        margin: 0.5rem 0 0 0;
      }

      .website-name {
        font-size: 1.5rem;
      }

      .login-section {
        flex-direction: column;
      }

      .user-info, .login-form {
        padding: 2rem 1.5rem;
      }

      .user-info {
        padding-bottom: 1.5rem;
      }

      .login-form {
        padding-top: 1.5rem;
      }
    }

    /* Animation */
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .login-section {
      animation: fadeIn 0.5s ease-out;
    }

    /* Password toggle */
    .password-toggle {
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      color: var(--text-light);
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
    <a href="home.php" class="back-link">
      <i class="fas fa-arrow-left"></i> Back
    </a>
  </header>

  <div class="container">
    <div class="login-section">

      <div class="user-info">
        <img src="img/farmer.jpeg" alt="Farmer" />
        <h2>Welcome Back!</h2>
        <p>Login to access your farming tools and resources</p>
        <div class="social-icons">
          <a href="#"><i class="fab fa-facebook-f"></i></a>
          <a href="#"><i class="fab fa-twitter"></i></a>
          <a href="#"><i class="fab fa-instagram"></i></a>
          <a href="#"><i class="fab fa-linkedin-in"></i></a>
        </div>
      </div>

      <div class="login-form">
        <h2>User Login</h2>

        <!-- Show error or success messages here -->
        <?php if ($error): ?>
          <div class="message-box error">
            <i class="fas fa-exclamation-circle"></i>
            <?php echo htmlspecialchars($error); ?>
          </div>
        <?php endif; ?>

        <?php if ($success): ?>
          <div class="message-box success">
            <i class="fas fa-check-circle"></i>
            <?php echo htmlspecialchars($success); ?>
          </div>
        <?php endif; ?>

        <form action="" method="post" onsubmit="return validateForm()">
          <div class="form-group">
            <i class="fas fa-envelope"></i>
            <input type="email" id="email" name="email" placeholder="Email Address" required 
                   value="<?php echo isset($email) ? htmlspecialchars($email) : '' ?>">
          </div>
          
          <div class="form-group">
            <i class="fas fa-lock"></i>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <span class="password-toggle" onclick="togglePassword()">
              <i class="fas fa-eye"></i>
            </span>
          </div>
          
          <a href="forgot-password.php" class="forgot-password">Forgot Password?</a>
          
          <button type="submit">
            <i class="fas fa-sign-in-alt"></i> Login
          </button>
        </form>

        <p>Don't have an account? <a href="register.php">Register here</a></p>
      </div>

    </div>
  </div>

  <footer>
    <p>&copy; 2024 Agrolease. All rights reserved. | <a href="privacy.php" style="color: white; text-decoration: underline;">Privacy Policy</a></p>
  </footer>

  <script>
    function validateForm() {
      const email = document.getElementById("email").value.trim();
      const password = document.getElementById("password").value.trim();
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

      if (!emailRegex.test(email)) {
        alert("Please enter a valid email address.");
        return false;
      }

      if (!email || !password) {
        alert("All fields are required.");
        return false;
      }

      return true;
    }
    
    function togglePassword() {
      const passwordField = document.getElementById("password");
      const toggleIcon = document.querySelector(".password-toggle i");
      
      if (passwordField.type === "password") {
        passwordField.type = "text";
        toggleIcon.classList.remove("fa-eye");
        toggleIcon.classList.add("fa-eye-slash");
      } else {
        passwordField.type = "password";
        toggleIcon.classList.remove("fa-eye-slash");
        toggleIcon.classList.add("fa-eye");
      }
    }
  </script>
</body>
</html>