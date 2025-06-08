<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>User Registration | Agrolease</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" />
  <style>
:root {
  --primary: #2e7d32;
  --primary-dark: #1b5e20;
  --primary-light: #e8f5e9;
  --secondary: #ff9800;
  --text-dark: #2e2e2e;
  --text-light: #666;
  --light-gray: #f5f5f5;
  --white: #ffffff;
  --shadow-sm: 0 3px 10px rgba(0, 0, 0, 0.08);
  --shadow-md: 0 14px 35px rgba(46, 125, 50, 0.18);
  --shadow-lg: 0 20px 50px rgba(46, 125, 50, 0.30);
  --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
}

* {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}

body {
  font-family: 'Poppins', sans-serif;
  background: linear-gradient(135deg, var(--primary-light), #f8fff8);
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  color: var(--text-dark);
  line-height: 1.6;
}

header {
  background-color: var(--primary);
  color: var(--white);
  padding: 1.2rem 2rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
  box-shadow: var(--shadow-sm);
  position: relative;
  z-index: 10;
}

.logo-container {
  display: flex;
  align-items: center;
  gap: 1rem;
  transition: var(--transition);
}

.logo-container:hover {
  transform: translateX(5px);
}

.logo {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  object-fit: cover;
  box-shadow: 0 0 15px rgba(255, 255, 255, 0.4);
  border: 2px solid rgba(255, 255, 255, 0.2);
}

.header-content {
  display: flex;
  flex-direction: column;
}

.website-name {
  font-size: 1.75rem;
  font-weight: 700;
  letter-spacing: 0.05em;
  line-height: 1.2;
}

.tagline {
  font-size: 0.85rem;
  opacity: 0.9;
  margin-top: 0.3rem;
  font-weight: 400;
  font-style: italic;
}

.main-container {
  flex: 1;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 2rem;
  position: relative;
}

.container {
  max-width: 900px;
  width: 100%;
  background: var(--white);
  border-radius: 20px;
  box-shadow: var(--shadow-md);
  overflow: hidden;
  display: grid;
  grid-template-columns: 1fr 1fr;
  transition: var(--transition);
}

.container:hover {
  box-shadow: var(--shadow-lg);
}

.welcome-section {
  background: linear-gradient(135deg, rgba(46, 125, 50, 0.95), rgba(27, 94, 32, 0.97));
  color: var(--white);
  padding: 3rem 2.5rem;
  display: flex;
  flex-direction: column;
  justify-content: center;
  text-align: center;
}

.welcome-content {
  max-width: 100%;
}

.welcome-section h2 {
  font-size: 1.8rem;
  margin-bottom: 1.5rem;
  font-weight: 600;
  position: relative;
  display: inline-block;
}

.welcome-section h2::after {
  content: '';
  position: absolute;
  bottom: -10px;
  left: 50%;
  transform: translateX(-50%);
  width: 80px;
  height: 3px;
  background-color: var(--secondary);
  border-radius: 3px;
}

.welcome-section p {
  margin-bottom: 2rem;
  opacity: 0.9;
  font-weight: 300;
}

.welcome-image {
  width: 180px;
  height: 180px;
  border-radius: 50%;
  margin: 0 auto 1.5rem;
  object-fit: cover;
  border: 4px solid rgba(255, 255, 255, 0.3);
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

.login-link {
  display: inline-block;
  margin-top: 1.5rem;
  color: var(--white);
  text-decoration: none;
  font-weight: 500;
  transition: var(--transition);
  border-bottom: 1px solid transparent;
}

.login-link:hover {
  border-bottom-color: var(--white);
}

.registration-form {
  padding: 3rem 2.5rem;
}

.registration-form h2 {
  font-size: 1.8rem;
  margin-bottom: 2rem;
  color: var(--primary);
  font-weight: 700;
  text-align: center;
  position: relative;
}

.registration-form h2::after {
  content: '';
  position: absolute;
  bottom: -10px;
  left: 50%;
  transform: translateX(-50%);
  width: 80px;
  height: 3px;
  background-color: var(--secondary);
  border-radius: 3px;
}

.form-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1.2rem;
}

.form-group {
  position: relative;
  margin-bottom: 1.2rem;
}

.form-group.full-width {
  grid-column: span 2;
}

.form-group i {
  position: absolute;
  left: 15px;
  top: 50%;
  transform: translateY(-50%);
  color: var(--text-light);
  font-size: 1rem;
  transition: var(--transition);
}

.form-group input {
  width: 100%;
  padding: 0.9rem 1rem 0.9rem 2.8rem;
  border: 1.8px solid #ddd;
  border-radius: 12px;
  font-size: 0.95rem;
  font-weight: 500;
  color: var(--text-dark);
  background-color: var(--light-gray);
  transition: var(--transition);
}

.form-group input::placeholder {
  color: #a3a3a3;
  font-weight: 400;
}

.form-group input:focus {
  border-color: var(--primary);
  outline: none;
  box-shadow: 0 0 0 3px rgba(46, 125, 50, 0.2);
  background-color: var(--white);
}

.form-group input:focus + i {
  color: var(--primary);
}

.password-toggle {
  position: absolute;
  right: 15px;
  top: 50%;
  transform: translateY(-50%);
  cursor: pointer;
  color: var(--text-light);
  transition: var(--transition);
}

.password-toggle:hover {
  color: var(--primary);
}

.terms {
  display: flex;
  align-items: center;
  margin: 1.5rem 0;
  grid-column: span 2;
}

.terms input {
  margin-right: 0.8rem;
}

.terms label {
  font-size: 0.85rem;
  color: var(--text-light);
}

.terms a {
  color: var(--primary);
  text-decoration: none;
  font-weight: 500;
}

.terms a:hover {
  text-decoration: underline;
}

.register-btn {
  width: 100%;
  padding: 1rem;
  background: linear-gradient(135deg, var(--primary), var(--primary-dark));
  color: var(--white);
  font-size: 1rem;
  font-weight: 600;
  border: none;
  border-radius: 12px;
  cursor: pointer;
  box-shadow: 0 8px 20px rgba(27, 94, 32, 0.3);
  transition: var(--transition);
  text-transform: uppercase;
  letter-spacing: 0.05em;
  grid-column: span 2;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.8rem;
}

.register-btn:hover {
  background: linear-gradient(135deg, var(--primary-dark), #0d3c10);
  box-shadow: 0 12px 25px rgba(20, 82, 20, 0.5);
  transform: translateY(-2px);
}

.register-btn:active {
  transform: translateY(0);
}

footer {
  background-color: var(--primary);
  color: var(--white);
  text-align: center;
  padding: 1.2rem;
  font-size: 0.85rem;
  font-weight: 400;
}

footer a {
  color: var(--white);
  text-decoration: none;
  font-weight: 500;
  margin: 0 0.5rem;
}

footer a:hover {
  text-decoration: underline;
}

/* Responsive styles */
@media (max-width: 768px) {
  .container {
    grid-template-columns: 1fr;
    max-width: 600px;
  }
  
  .welcome-section {
    display: none;
  }
  
  .registration-form {
    padding: 2.5rem 2rem;
  }
  
  .form-grid {
    grid-template-columns: 1fr;
  }
  
  .form-group.full-width {
    grid-column: span 1;
  }
  
  .terms, .register-btn {
    grid-column: span 1;
  }
}

@media (max-width: 480px) {
  header {
    padding: 1rem;
    flex-direction: column;
    text-align: center;
  }
  
  .logo-container {
    margin-bottom: 0.8rem;
    justify-content: center;
  }
  
  .registration-form {
    padding: 2rem 1.5rem;
  }
  
  .registration-form h2 {
    font-size: 1.5rem;
    margin-bottom: 1.5rem;
  }
  
  .form-group input {
    padding: 0.8rem 0.8rem 0.8rem 2.5rem;
    font-size: 0.9rem;
  }
  
  .register-btn {
    padding: 0.9rem;
    font-size: 0.95rem;
  }
}

/* Animations */
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.container {
  animation: fadeInUp 0.6s ease-out;
}

/* Custom checkbox */
.terms input[type="checkbox"] {
  appearance: none;
  -webkit-appearance: none;
  width: 18px;
  height: 18px;
  border: 2px solid #ccc;
  border-radius: 4px;
  outline: none;
  cursor: pointer;
  position: relative;
  transition: var(--transition);
}

.terms input[type="checkbox"]:checked {
  background-color: var(--primary);
  border-color: var(--primary);
}

.terms input[type="checkbox"]:checked::after {
  content: '\f00c';
  font-family: 'Font Awesome 6 Free';
  font-weight: 900;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  color: white;
  font-size: 0.7rem;
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

  <div class="main-container">
    <div class="container">
      <div class="welcome-section">
        <div class="welcome-content">
          <img src="img/farmer.jpeg" alt="Farmer" class="welcome-image" />
          <h2>Join Our Farming Community</h2>
          <p>Register today to access exclusive farming tools, resources, and connect with other agricultural enthusiasts.</p>
          <p>Already have an account? <a href="login.php" class="login-link">Sign In</a></p>
        </div>
      </div>
      
      <form class="registration-form" name="registrationForm" action="register_valid.php" method="post" onsubmit="return validateForm()">
        <h2>Create Account</h2>
        
        <div class="form-grid">
          <div class="form-group full-width">
            <i class="fas fa-user"></i>
            <input type="text" name="full_name" placeholder="Full Name" required />
          </div>
          
          <div class="form-group">
            <i class="fas fa-envelope"></i>
            <input type="email" name="email" placeholder="Email Address" required />
          </div>
          
          <div class="form-group">
            <i class="fas fa-phone"></i>
            <input type="tel" name="mobile" placeholder="Mobile Number" required />
          </div>
          
          <div class="form-group">
            <i class="fas fa-lock"></i>
            <input type="password" name="password" id="password" placeholder="Password" required />
            <span class="password-toggle" onclick="togglePassword('password')">
              <i class="fas fa-eye"></i>
            </span>
          </div>
          
          <div class="form-group">
            <i class="fas fa-lock"></i>
            <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required />
            <span class="password-toggle" onclick="togglePassword('confirm_password')">
              <i class="fas fa-eye"></i>
            </span>
          </div>
          
          <div class="form-group full-width">
            <i class="fas fa-home"></i>
            <input type="text" name="house_name" placeholder="House Name" required />
          </div>
          
          <div class="form-group">
            <i class="fas fa-map-marker-alt"></i>
            <input type="text" name="area" placeholder="Area/Locality" required />
          </div>
          
          <div class="form-group">
            <i class="fas fa-tree"></i>
            <input type="text" name="village" placeholder="Village" required />
          </div>
          
          <div class="form-group">
            <i class="fas fa-building"></i>
            <input type="text" name="taluka" placeholder="Taluka" required />
          </div>
          
          <div class="form-group">
            <i class="fas fa-map-pin"></i>
            <input type="text" name="pincode" placeholder="Pincode" required />
          </div>
          
          <div class="form-group">
            <i class="fas fa-city"></i>
            <input type="text" name="district" placeholder="District" required />
          </div>
          
          <div class="form-group">
            <i class="fas fa-flag"></i>
            <input type="text" name="state" placeholder="State" required />
          </div>
          
          <div class="terms">
            <input type="checkbox" id="agreeTerms" required />
            <label for="agreeTerms">I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a></label>
          </div>
          
          <button type="submit" name="registerBtn" class="register-btn">
            <i class="fas fa-user-plus"></i> Register Now
          </button>
        </div>
      </form>
    </div>
  </div>

  <footer>
    <p>&copy; 2025 Agrolease. All rights reserved. | <a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a> | <a href="#">Contact Us</a></p>
  </footer>

  <script>
    function validateForm() {
      const form = document.forms["registrationForm"];
      const emailRegex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
      const phoneRegex = /^\d{10}$/;
      const pincodeRegex = /^\d{6}$/;

      if (!emailRegex.test(form.email.value)) {
        alert("Please enter a valid email address.");
        return false;
      }
      
      if (!phoneRegex.test(form.mobile.value)) {
        alert("Mobile number must be 10 digits.");
        return false;
      }
      
      if (form.password.value.length < 8) {
        alert("Password must be at least 8 characters long.");
        return false;
      }
      
      if (form.password.value !== form.confirm_password.value) {
        alert("Passwords do not match.");
        return false;
      }
      
      if (!pincodeRegex.test(form.pincode.value)) {
        alert("Please enter a valid 6-digit pincode.");
        return false;
      }
      
      if (!form.agreeTerms.checked) {
        alert("You must agree to the Terms of Service and Privacy Policy.");
        return false;
      }

      return true;
    }
    
    function togglePassword(fieldId) {
      const field = document.getElementById(fieldId);
      const icon = document.querySelector(`#${fieldId} + .password-toggle i`);
      
      if (field.type === "password") {
        field.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
      } else {
        field.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
      }
    }
  </script>
</body>
</html>