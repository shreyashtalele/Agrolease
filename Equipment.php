<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Add Equipment | Agrolease</title>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>

  <style>
    :root {
      --primary: #1a5f1a;
      --primary-light: #e6f7e6;
      --primary-dark: #0d4d0d;
      --secondary: #f59e0b;
      --dark: #1f2937;
      --light: #f8faf7;
      --gray: #6b7280;
      --gray-light: #e5e7eb;
      --white: #ffffff;
      --success: #10b981;
      --danger: #ef4444;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background-color: var(--light);
      color: var(--dark);
      line-height: 1.6;
    }

    header {
      background-color: var(--primary);
      color: var(--white);
      padding: 1.2rem 2rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .logo-container {
      width: 50px;
      height: 50px;
      border-radius: 12px;
      overflow: hidden;
      border: 2px solid var(--white);
    }

    .logo {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .header-content {
      flex-grow: 1;
      text-align: center;
      padding: 0 1rem;
    }

    .website-name {
      margin: 0;
      font-size: 1.5rem;
      font-weight: 700;
    }

    .tagline {
      font-size: 0.8rem;
      margin-top: 0.3rem;
      opacity: 0.9;
      font-weight: 300;
    }

    .header-link {
      background: var(--primary-dark);
      color: var(--white);
      padding: 0.7rem 1.2rem;
      border-radius: 8px;
      text-decoration: none;
      font-size: 0.9rem;
      font-weight: 500;
      transition: all 0.3s ease;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
    }

    .header-link:hover {
      background-color: var(--dark);
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .container {
      display: flex;
      justify-content: center;
      padding: 2rem 1rem 4rem;
      min-height: calc(100vh - 140px);
    }

    .form-container {
      background-color: var(--white);
      border-radius: 12px;
      padding: 2.5rem;
      width: 100%;
      max-width: 600px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
      margin: 1rem;
    }

    .form-header {
      text-align: center;
      margin-bottom: 2rem;
      position: relative;
    }

    .form-header h2 {
      color: var(--primary);
      font-size: 1.8rem;
      font-weight: 700;
      margin-bottom: 0.5rem;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.8rem;
    }

    .form-header p {
      color: var(--gray);
      font-size: 0.95rem;
    }

    .form-group {
      margin-bottom: 1.5rem;
      position: relative;
    }

    label {
      display: block;
      margin-bottom: 0.6rem;
      font-weight: 500;
      color: var(--dark);
      font-size: 0.95rem;
    }

    label i {
      margin-right: 0.5rem;
      color: var(--primary);
      width: 20px;
      text-align: center;
    }

    input[type="text"],
    input[type="number"],
    select,
    textarea {
      width: 100%;
      padding: 0.8rem 1rem;
      font-size: 0.95rem;
      border: 1px solid var(--gray-light);
      border-radius: 8px;
      transition: all 0.3s ease;
      background-color: var(--light);
    }

    input:focus,
    select:focus,
    textarea:focus {
      border-color: var(--primary);
      outline: none;
      box-shadow: 0 0 0 3px rgba(26, 95, 26, 0.1);
      background-color: var(--white);
    }

    textarea {
      resize: vertical;
      min-height: 100px;
    }

    .file-upload {
      position: relative;
      display: flex;
      flex-direction: column;
    }

    .file-upload-input {
      width: 0.1px;
      height: 0.1px;
      opacity: 0;
      overflow: hidden;
      position: absolute;
      z-index: -1;
    }

    .file-upload-label {
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1.5rem;
      border: 2px dashed var(--gray-light);
      border-radius: 8px;
      background-color: var(--light);
      cursor: pointer;
      transition: all 0.3s ease;
      text-align: center;
      flex-direction: column;
      gap: 0.8rem;
    }

    .file-upload-label:hover {
      border-color: var(--primary);
      background-color: rgba(26, 95, 26, 0.05);
    }

    .file-upload-icon {
      font-size: 2rem;
      color: var(--primary);
    }

    .file-upload-text {
      font-size: 0.9rem;
      color: var(--gray);
    }

    .file-upload-text strong {
      color: var(--primary);
      font-weight: 600;
    }

    .preview-container {
      margin-top: 1rem;
      display: none;
    }

    .image-preview {
      max-width: 100%;
      max-height: 200px;
      border-radius: 8px;
      border: 1px solid var(--gray-light);
      display: block;
      margin: 0 auto;
    }

    .submit-btn {
      background-color: var(--primary);
      color: var(--white);
      padding: 1rem;
      font-size: 1rem;
      font-weight: 600;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      transition: all 0.3s ease;
      width: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.8rem;
      margin-top: 1rem;
    }

    .submit-btn:hover {
      background-color: var(--primary-dark);
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(26, 95, 26, 0.2);
    }

    footer {
      background-color: var(--primary);
      color: var(--white);
      text-align: center;
      padding: 1.5rem;
      font-size: 0.9rem;
    }

    footer p {
      opacity: 0.9;
    }

    @media (max-width: 768px) {
      header {
        padding: 1rem;
        flex-direction: column;
        gap: 1rem;
      }

      .header-content {
        order: -1;
        margin-bottom: 0.5rem;
      }

      .form-container {
        padding: 1.5rem;
      }

      .form-header h2 {
        font-size: 1.5rem;
      }
    }

    @media (max-width: 480px) {
      .container {
        padding: 1rem 0.5rem 3rem;
      }

      .form-header h2 {
        flex-direction: column;
        gap: 0.5rem;
      }
    }
  </style>
</head>
<body>

<header>
  <div class="logo-container">
    <img src="img/logo.jpeg" alt="Agrolease Logo" class="logo">
  </div>
  <div class="header-content">
    <h1 class="website-name">Agrolease</h1>
    <p class="tagline">Harvest Success with Our Innovative Farming Solutions</p>
  </div>
  <a href="vendor_dashboard.php" class="header-link">
    <i class="fas fa-chevron-left"></i> Back to Dashboard
  </a>
</header>

<div class="container">
  <form action="insert_equipment.php" method="post" class="form-container" enctype="multipart/form-data">
    <div class="form-header">
      <h2>
        <i class="fas fa-tractor"></i> 
        Add New Equipment
      </h2>
      <p>Fill in the details to list your farming equipment for rental</p>
    </div>

    <div class="form-group">
      <label for="equipment_name">
        <i class="fas fa-tools"></i> Equipment Name
      </label>
      <input type="text" id="equipment_name" name="equipment_name" placeholder="e.g. John Deere Tractor 5050D" required>
    </div>

    <div class="form-group">
      <label for="rent_per_day">
        <i class="fas fa-rupee-sign"></i> Rent Per Day (₹)
      </label>
      <input type="number" id="rent_per_day" name="rent_per_day" placeholder="Enter daily rental price" required min="0" step="50">
    </div>

    <div class="form-group">
      <label for="total_stock">
        <i class="fas fa-boxes"></i> Available Quantity
      </label>
      <input type="number" id="total_stock" name="total_stock" placeholder="Number of units available" required min="1">
    </div>

    <div class="form-group">
      <label for="category">
        <i class="fas fa-layer-group"></i> Equipment Category
      </label>
      <select id="category" name="category" required>
        <option value="" disabled selected>Select equipment category</option>
        <option value="Tillage Equipment">Tillage Equipment</option>
        <option value="Planting Equipment">Planting Equipment</option>
        <option value="Harvesting Equipment">Harvesting Equipment</option>
        <option value="Irrigation Equipment">Irrigation Equipment</option>
        <option value="Crop Protection Equipment">Crop Protection Equipment</option>
        <option value="Other Equipment">Other Equipment</option>
      </select>
    </div>

    <div class="form-group">
      <label for="description">
        <i class="fas fa-align-left"></i> Equipment Description
      </label>
      <textarea id="description" name="description" placeholder="Provide detailed description including specifications, condition, and any special features..." required></textarea>
    </div>

    <div class="form-group">
      <label>
        <i class="fas fa-image"></i> Equipment Image
      </label>
      <div class="file-upload">
        <input type="file" id="image" name="image" accept="image/*" class="file-upload-input" required>
        <label for="image" class="file-upload-label">
          <i class="fas fa-cloud-upload-alt file-upload-icon"></i>
          <span class="file-upload-text">
            <strong>Click to upload</strong> or drag and drop<br>
            PNG, JPG (Max. 5MB)
          </span>
        </label>
        <div class="preview-container" id="previewContainer">
          <img src="" alt="Preview" class="image-preview" id="imagePreview">
        </div>
      </div>
    </div>

    <button type="submit" class="submit-btn">
      <i class="fas fa-paper-plane"></i> List Equipment
    </button>
  </form>
</div>

<footer>
  <p>&copy; 2024 Agrolease. All rights reserved.</p>
</footer>

<script>
  // Image preview functionality
  const imageInput = document.getElementById('image');
  const previewContainer = document.getElementById('previewContainer');
  const imagePreview = document.getElementById('imagePreview');

  imageInput.addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
      const reader = new FileReader();
      
      previewContainer.style.display = 'block';
      
      reader.addEventListener('load', function() {
        imagePreview.setAttribute('src', this.result);
      });
      
      reader.readAsDataURL(file);
    }
  });

  // Form validation
  const form = document.querySelector('form');
  form.addEventListener('submit', function(e) {
    let valid = true;
    
    // Check all required fields
    const requiredFields = form.querySelectorAll('[required]');
    requiredFields.forEach(field => {
      if (!field.value.trim()) {
        field.style.borderColor = 'var(--danger)';
        valid = false;
      } else {
        field.style.borderColor = 'var(--gray-light)';
      }
    });
    
    if (!valid) {
      e.preventDefault();
      alert('Please fill in all required fields');
    }
  });

  // Add input validation styling
  const inputs = form.querySelectorAll('input, select, textarea');
  inputs.forEach(input => {
    input.addEventListener('input', function() {
      if (this.checkValidity()) {
        this.style.borderColor = 'var(--gray-light)';
      }
    });
  });
</script>

</body>
</html>