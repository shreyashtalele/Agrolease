<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Agrolease - Empowering Farmers</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    /* Reset */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f4fdf6;
      color: #333;
      line-height: 1.6;
      scroll-behavior: smooth;
    }

    /* Header */
    header {
      background: linear-gradient(90deg, #006400, #228B22);
      color: #fff;
      padding: 15px 30px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      box-shadow: 0 3px 15px rgba(0,0,0,0.2);
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 1000;
      transition: all 0.4s ease;
      transform: translateY(0);
    }

    header.hidden {
      transform: translateY(-100%);
    }

    .logo-container {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      overflow: hidden;
      border: 2px solid #fff;
      flex-shrink: 0;
      transition: transform 0.3s ease;
    }
    .logo-container:hover {
      transform: scale(1.1);
    }

    .logo-container img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .header-title {
      flex: 1;
      text-align: center;
      user-select: none;
      transition: all 0.3s ease;
    }

    .website-name {
      font-size: 24px;
      font-weight: 700;
      letter-spacing: 1.5px;
      text-shadow: 1px 1px 3px rgba(0,0,0,0.2);
    }

    .tagline {
      font-size: 12px;
      font-style: italic;
      margin-top: 3px;
      letter-spacing: 0.5px;
      opacity: 0.9;
    }

    nav {
      display: flex;
      align-items: center;
    }

    nav a {
      margin-left: 12px;
      text-decoration: none;
      color: #fff;
      font-weight: 600;
      padding: 8px 15px;
      background-color: rgba(255, 255, 255, 0.12);
      border-radius: 6px;
      transition: all 0.3s ease;
      user-select: none;
      font-size: 14px;
      white-space: nowrap;
    }

    nav a:hover {
      background-color: rgba(255, 255, 255, 0.3);
      transform: translateY(-2px);
    }

    nav a.active {
      background-color: rgba(255, 255, 255, 0.25);
      font-weight: 700;
    }

    /* Hero Section */
    .hero {
      background: linear-gradient(135deg, #d6f6d6 0%, #ffffff 100%);
      padding: 120px 20px 80px;
      text-align: center;
      position: relative;
      overflow: hidden;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
    }

    .hero::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: url('https://images.unsplash.com/photo-1500382017468-9049fed747ef?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80') no-repeat center center;
      background-size: cover;
      opacity: 0.15;
      z-index: 0;
    }

    .hero-content {
      position: relative;
      z-index: 1;
      max-width: 900px;
    }

    .hero h1 {
      font-size: 3.5rem;
      color: #006400;
      margin-bottom: 25px;
      opacity: 0;
      animation: fadeInUp 1s forwards 0.3s;
      letter-spacing: 1.5px;
      line-height: 1.2;
      text-shadow: 1px 1px 3px rgba(0,0,0,0.1);
    }

    .hero p {
      font-size: 1.3rem;
      max-width: 720px;
      margin: 0 auto 40px;
      color: #355e35;
      opacity: 0;
      animation: fadeInUp 1s forwards 0.6s;
      line-height: 1.6;
    }

    .cta-button {
      display: inline-block;
      padding: 16px 40px;
      background-color: #228B22;
      color: white;
      font-size: 18px;
      font-weight: 600;
      border-radius: 50px;
      text-decoration: none;
      box-shadow: 0 6px 20px rgba(34, 139, 34, 0.4);
      cursor: pointer;
      user-select: none;
      opacity: 0;
      animation: bounceIn 1s forwards 0.9s;
      transition: all 0.3s ease;
      border: none;
      position: relative;
      overflow: hidden;
    }

    .cta-button::after {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(rgba(255,255,255,0.1), rgba(255,255,255,0.1));
      opacity: 0;
      transition: opacity 0.3s ease;
    }

    .cta-button:hover {
      background-color: #1a701a;
      box-shadow: 0 8px 25px rgba(23, 90, 23, 0.6);
      transform: translateY(-3px);
    }

    .cta-button:hover::after {
      opacity: 1;
    }

    .cta-button:active {
      transform: translateY(1px);
      box-shadow: 0 4px 15px rgba(23, 90, 23, 0.6);
    }

    /* Features Section */
    .features {
      padding: 100px 20px;
      background-color: #eaf7ee;
      text-align: center;
      position: relative;
    }

    .section-title {
      font-size: 2.5rem;
      color: #006400;
      margin-bottom: 60px;
      letter-spacing: 1px;
      position: relative;
      display: inline-block;
    }

    .section-title::after {
      content: '';
      position: absolute;
      bottom: -15px;
      left: 50%;
      transform: translateX(-50%);
      width: 80px;
      height: 4px;
      background-color: #228B22;
      border-radius: 2px;
    }

    .feature-grid {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 30px;
      max-width: 1200px;
      margin: 0 auto;
    }

    .feature-card {
      background-color: white;
      padding: 40px 30px;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.08);
      width: 350px;
      transition: all 0.4s ease;
      cursor: default;
      position: relative;
      overflow: hidden;
    }

    .feature-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 5px;
      background-color: #228B22;
      transition: height 0.3s ease;
    }

    .feature-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 15px 35px rgba(0,0,0,0.12);
    }

    .feature-card:hover::before {
      height: 8px;
    }

    .feature-icon {
      font-size: 50px;
      color: #228B22;
      margin-bottom: 25px;
      transition: transform 0.3s ease;
    }

    .feature-card:hover .feature-icon {
      transform: scale(1.1);
    }

    .feature-card h3 {
      margin-bottom: 20px;
      color: #228B22;
      font-size: 24px;
      user-select: none;
      font-weight: 600;
    }

    .feature-card p {
      font-size: 16px;
      color: #555;
      line-height: 1.6;
    }

    /* How It Works Section */
    .how-it-works {
      padding: 100px 20px;
      background-color: #ffffff;
      text-align: center;
      position: relative;
    }

    .steps {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 40px;
      max-width: 1000px;
      margin: 0 auto;
    }

    .step-card {
      background-color: #fff;
      padding: 35px 30px;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(34,139,34,0.1);
      width: 300px;
      user-select: none;
      transition: all 0.4s ease;
      position: relative;
      border: 2px solid #e1f3e1;
    }

    .step-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 15px 35px rgba(34,139,34,0.15);
      border-color: #228B22;
    }

    .step-number {
      font-size: 40px;
      font-weight: 700;
      color: #e1f3e1;
      margin-bottom: 15px;
      user-select: none;
      position: relative;
      display: inline-block;
    }

    .step-number::after {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 60px;
      height: 60px;
      background-color: #228B22;
      border-radius: 50%;
      z-index: -1;
    }

    .step-title {
      font-size: 22px;
      font-weight: 600;
      margin-bottom: 15px;
      color: #175a17;
    }

    .step-desc {
      font-size: 15px;
      color: #555;
      line-height: 1.6;
    }

    /* About Section */
    .about {
      padding: 100px 20px;
      background-color: #f7fff7;
      text-align: center;
      max-width: 900px;
      margin: 0 auto;
      position: relative;
    }

    .about p {
      font-size: 16px;
      color: #355e35;
      text-align: justify;
      line-height: 1.8;
      margin-bottom: 30px;
    }

    .stats {
      display: flex;
      justify-content: space-around;
      flex-wrap: wrap;
      margin-top: 50px;
      gap: 20px;
    }

    .stat-item {
      text-align: center;
      padding: 20px;
      min-width: 150px;
    }

    .stat-number {
      font-size: 42px;
      font-weight: 700;
      color: #228B22;
      margin-bottom: 5px;
    }

    .stat-label {
      font-size: 16px;
      color: #355e35;
      font-weight: 500;
    }

    /* Testimonials */
    .testimonials {
      padding: 100px 20px;
      background-color: #eaf7ee;
      text-align: center;
    }

    .testimonial-grid {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 30px;
      max-width: 1200px;
      margin: 0 auto;
    }

    .testimonial-card {
      background-color: white;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.08);
      width: 350px;
      text-align: left;
      position: relative;
    }

    .testimonial-card::before {
      content: '"';
      position: absolute;
      top: 20px;
      left: 20px;
      font-size: 60px;
      color: rgba(34, 139, 34, 0.1);
      font-family: serif;
      line-height: 1;
    }

    .testimonial-text {
      font-size: 15px;
      color: #555;
      line-height: 1.7;
      margin-bottom: 20px;
      position: relative;
      z-index: 1;
    }

    .testimonial-author {
      display: flex;
      align-items: center;
    }

    .author-avatar {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      overflow: hidden;
      margin-right: 15px;
    }

    .author-avatar img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .author-info h4 {
      color: #228B22;
      margin-bottom: 5px;
      font-size: 16px;
    }

    .author-info p {
      font-size: 14px;
      color: #777;
    }

    /* Newsletter */
    .newsletter {
      padding: 80px 20px;
      background: linear-gradient(135deg, #006400, #228B22);
      color: white;
      text-align: center;
    }

    .newsletter h2 {
      font-size: 2rem;
      margin-bottom: 20px;
    }

    .newsletter p {
      max-width: 600px;
      margin: 0 auto 30px;
      opacity: 0.9;
    }

    .newsletter-form {
      display: flex;
      max-width: 500px;
      margin: 0 auto;
    }

    .newsletter-input {
      flex: 1;
      padding: 15px 20px;
      border: none;
      border-radius: 50px 0 0 50px;
      font-size: 16px;
      outline: none;
    }

    .newsletter-button {
      padding: 15px 30px;
      background-color: #1a701a;
      color: white;
      border: none;
      border-radius: 0 50px 50px 0;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .newsletter-button:hover {
      background-color: #145614;
    }

    /* Footer */
    footer {
      background-color: #003d00;
      color: #fff;
      text-align: center;
      padding: 70px 20px 30px;
      user-select: none;
    }

    .footer-content {
      display: flex;
      justify-content: space-around;
      flex-wrap: wrap;
      max-width: 1200px;
      margin: 0 auto 40px;
      gap: 30px;
      text-align: left;
    }

    .footer-column {
      flex: 1;
      min-width: 250px;
    }

    .footer-column h3 {
      font-size: 18px;
      margin-bottom: 20px;
      color: #9be29b;
      position: relative;
      padding-bottom: 10px;
    }

    .footer-column h3::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 40px;
      height: 2px;
      background-color: #228B22;
    }

    .footer-column ul {
      list-style: none;
    }

    .footer-column li {
      margin-bottom: 12px;
    }

    .footer-column a {
      color: #ddd;
      text-decoration: none;
      transition: color 0.3s ease;
      font-size: 15px;
    }

    .footer-column a:hover {
      color: #9be29b;
    }

    .contact-info {
      margin-top: 15px;
    }

    .contact-info p {
      margin-bottom: 10px;
      display: flex;
      align-items: center;
      font-size: 15px;
    }

    .contact-info i {
      margin-right: 10px;
      color: #9be29b;
      width: 20px;
      text-align: center;
    }

    .socials {
      display: flex;
      gap: 15px;
      margin-top: 20px;
    }

    .socials a {
      color: #fff;
      font-size: 20px;
      transition: all 0.3s ease;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background-color: rgba(155, 226, 155, 0.1);
    }

    .socials a:hover {
      color: #003d00;
      background-color: #9be29b;
      transform: translateY(-3px);
    }

    .copyright {
      font-size: 14px;
      margin-top: 40px;
      opacity: 0.7;
      padding-top: 20px;
      border-top: 1px solid rgba(255,255,255,0.1);
    }

    .back-to-top {
      position: fixed;
      bottom: 30px;
      right: 30px;
      width: 50px;
      height: 50px;
      background-color: #228B22;
      color: white;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
      cursor: pointer;
      opacity: 0;
      visibility: hidden;
      transition: all 0.3s ease;
      box-shadow: 0 5px 15px rgba(0,0,0,0.2);
      z-index: 999;
    }

    .back-to-top.visible {
      opacity: 1;
      visibility: visible;
    }

    .back-to-top:hover {
      background-color: #1a701a;
      transform: translateY(-3px);
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

    @keyframes bounceIn {
      0%, 20%, 50%, 80%, 100% {
        transform: translateY(0);
      }
      40% {
        transform: translateY(-15px);
      }
      60% {
        transform: translateY(-7px);
      }
    }

    /* Responsive */
    @media (max-width: 992px) {
      .hero h1 {
        font-size: 2.8rem;
      }
      
      .feature-card, .testimonial-card {
        width: 100%;
        max-width: 450px;
      }
    }

    @media (max-width: 768px) {
      header {
        padding: 12px 20px;
      }
      
      .website-name {
        font-size: 20px;
      }
      
      .tagline {
        font-size: 11px;
      }
      
      nav a {
        margin-left: 8px;
        padding: 6px 12px;
        font-size: 12px;
      }
      
      .hero {
        padding: 100px 20px 60px;
      }
      
      .hero h1 {
        font-size: 2.2rem;
      }
      
      .hero p {
        font-size: 1.1rem;
      }
      
      .cta-button {
        padding: 14px 30px;
        font-size: 16px;
      }
      
      .section-title {
        font-size: 2rem;
      }
      
      .steps {
        flex-direction: column;
        align-items: center;
      }
      
      .step-card {
        width: 100%;
        max-width: 400px;
      }
      
      .newsletter-form {
        flex-direction: column;
      }
      
      .newsletter-input {
        border-radius: 50px;
        margin-bottom: 10px;
      }
      
      .newsletter-button {
        border-radius: 50px;
      }
    }

    @media (max-width: 576px) {
      .hero h1 {
        font-size: 1.8rem;
      }
      
      .hero p {
        font-size: 1rem;
      }
      
      .section-title {
        font-size: 1.8rem;
      }
      
      .footer-content {
        flex-direction: column;
        gap: 30px;
      }
      
      .back-to-top {
        width: 40px;
        height: 40px;
        font-size: 16px;
        bottom: 20px;
        right: 20px;
      }
    }
  </style>
</head>
<body>

  <header id="main-header">
    <div class="logo-container">
      <img src="img/logo.jpeg" alt="Agrolease Logo" />
    </div>
    <div class="header-title">
      <div class="website-name">Agrolease</div>
      <div class="tagline">Harvest Success with Our Innovative Farming Solutions</div>
    </div>
    <nav>
      <a href="#about">About</a>
      <a href="#features">Features</a>
      <a href="#how-it-works">How It Works</a>
      <a href="#testimonials">Testimonials</a>
      <a href="login.php">User Login</a>
      <a href="vendor_login.php">Vendor Login</a>
    </nav>
  </header>

  <section class="hero">
    <div class="hero-content">
      <h1>Empowering Farmers Across India</h1>
      <p>
        Agrolease makes high-quality farming equipment accessible and affordable. Rent what you need, when you need it, with just a few clicks. Join thousands of farmers growing smarter every day.
      </p>
      <a href="login.php" class="cta-button">Get Started <i class="fas fa-arrow-right" style="margin-left: 8px;"></i></a>
    </div>
  </section>

  <section id="features" class="features">
    <h2 class="section-title">Why Choose Agrolease?</h2>
    <div class="feature-grid">
      <div class="feature-card" title="Affordable Rentals">
        <div class="feature-icon"><i class="fas fa-rupee-sign"></i></div>
        <h3>Affordable Rentals</h3>
        <p>Access premium equipment without high ownership costs. Pay only for what you use with our flexible rental plans that fit your budget and farming cycle.</p>
      </div>
      <div class="feature-card" title="Wider Reach">
        <div class="feature-icon"><i class="fas fa-map-marked-alt"></i></div>
        <h3>Wider Reach</h3>
        <p>Available across multiple districts with fast delivery and pickup options. We're expanding our network to serve more farmers every month.</p>
      </div>
      <div class="feature-card" title="Vendor Opportunities">
        <div class="feature-icon"><i class="fas fa-handshake"></i></div>
        <h3>Vendor Opportunities</h3>
        <p>List your machinery and grow income by serving local farmers efficiently. Our platform helps you maximize equipment utilization with minimal effort.</p>
      </div>
    </div>
  </section>

  <section id="how-it-works" class="how-it-works">
    <h2 class="section-title">How It Works</h2>
    <div class="steps">
      <div class="step-card">
        <div class="step-number">1</div>
        <div class="step-title">Browse Equipment</div>
        <div class="step-desc">Explore our extensive catalog of farming machines available for rent in your area. Filter by equipment type, price, or availability.</div>
      </div>
      <div class="step-card">
        <div class="step-number">2</div>
        <div class="step-title">Select & Book</div>
        <div class="step-desc">Choose the equipment you need and book it with flexible rental durations. Our transparent pricing shows all costs upfront.</div>
      </div>
      <div class="step-card">
        <div class="step-number">3</div>
        <div class="step-title">Get It Delivered</div>
        <div class="step-desc">Receive timely delivery and support, so you can focus on your harvest. Our partners ensure equipment arrives ready to work.</div>
      </div>
    </div>
  </section>

  <section id="about" class="about">
    <h2 class="section-title">About Us</h2>
    <p>
      Agrolease is a mission-driven platform simplifying equipment rental for Indian farmers. We bring together vendors and cultivators on one easy-to-use interface, improving productivity and cutting down operational hurdles. Whether you need a tractor for a day or a harvester for a week, Agrolease ensures timely, cost-effective access to tools that power your farm's success.
    </p>
    <p>
      Founded in 2023 by agricultural technology enthusiasts, we believe in sustainability, innovation, and empowering local communities through technology. Our team combines farming expertise with digital solutions to create services that truly meet the needs of modern Indian agriculture.
    </p>
    
    <div class="stats">
      <div class="stat-item">
        <div class="stat-number">5,000+</div>
        <div class="stat-label">Happy Farmers</div>
      </div>
      <div class="stat-item">
        <div class="stat-number">1,200+</div>
        <div class="stat-label">Equipment Vendors</div>
      </div>
      <div class="stat-item">
        <div class="stat-number">50+</div>
        <div class="stat-label">Districts Served</div>
      </div>
    </div>
  </section>

  <section id="testimonials" class="testimonials">
    <h2 class="section-title">What Farmers Say</h2>
    <div class="testimonial-grid">
      <div class="testimonial-card">
        <div class="testimonial-text">
          Agrolease saved me from buying an expensive tractor. Now I can rent one whenever I need it at a fraction of the cost. The service is reliable and the equipment is always in good condition.
        </div>
        <div class="testimonial-author">
          <div class="author-avatar">
            <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Rajesh Kumar">
          </div>
          <div class="author-info">
            <h4>Rajesh Kumar</h4>
            <p>Farmer, Punjab</p>
          </div>
        </div>
      </div>
      <div class="testimonial-card">
        <div class="testimonial-text">
          As a small-scale farmer, I couldn't afford my own harvesting equipment. Thanks to Agrolease, I can now compete with larger farms by renting quality machines during harvest season.
        </div>
        <div class="testimonial-author">
          <div class="author-avatar">
            <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Priya Patel">
          </div>
          <div class="author-info">
            <h4>Priya Patel</h4>
            <p>Farm Owner, Gujarat</p>
          </div>
        </div>
      </div>
      <div class="testimonial-card">
        <div class="testimonial-text">
          Listing my equipment on Agrolease has doubled my income. The platform is easy to use and payments come on time. I've expanded my fleet thanks to the increased demand.
        </div>
        <div class="testimonial-author">
          <div class="author-avatar">
            <img src="https://randomuser.me/api/portraits/men/67.jpg" alt="Vikram Singh">
          </div>
          <div class="author-info">
            <h4>Vikram Singh</h4>
            <p>Equipment Vendor, Maharashtra</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="newsletter">
    <h2>Stay Updated</h2>
    <p>Subscribe to our newsletter for the latest updates, farming tips, and special offers.</p>
    <form class="newsletter-form">
      <input type="email" placeholder="Enter your email address" class="newsletter-input" required>
      <button type="submit" class="newsletter-button">Subscribe</button>
    </form>
  </section>

  <footer id="contact">
    <div class="footer-content">
      <div class="footer-column">
        <h3>Quick Links</h3>
        <ul>
          <li><a href="#about">About Us</a></li>
          <li><a href="#features">Services</a></li>
          <li><a href="#how-it-works">How It Works</a></li>
          <li><a href="#testimonials">Testimonials</a></li>
          <li><a href="login.php">User Login</a></li>
          <li><a href="vendor_login.php">Vendor Login</a></li>
        </ul>
      </div>
      
      <div class="footer-column">
        <h3>Equipment</h3>
        <ul>
          <li><a href="#">Tractors</a></li>
          <li><a href="#">Harvesters</a></li>
          <li><a href="#">Plows</a></li>
          <li><a href="#">Seeders</a></li>
          <li><a href="#">Irrigation</a></li>
          <li><a href="#">All Equipment</a></li>
        </ul>
      </div>
      <div class="footer-column">
        <h3>Contact Us</h3>
        <div class="contact-info">
          <p><i class="fas fa-map-marker-alt"></i> Kate Wasti, Punawale, Pune</p>
          <p><i class="fas fa-phone-alt"></i> <a href="tel:+918379052688">+91 8379052688</a></p>
          <p><i class="fas fa-envelope"></i> <a href="mailto:info@agrolease.com">info@agrolease.com</a></p>
        </div>
        <div class="socials">
          <a href="#" title="Facebook" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
          <a href="#" title="Twitter" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
          <a href="#" title="Instagram" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
          <a href="#" title="LinkedIn" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
          <a href="#" title="YouTube" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
        </div>
      </div>
    </div>
    <div class="copyright">
      © 2025 Agrolease. All rights reserved.
    </div>
  </footer>

  <div class="back-to-top" id="back-to-top">
    <i class="fas fa-arrow-up"></i>
  </div>

  <script>
    // Hide header on scroll down, show on scroll up
    let lastScroll = 0;
    const header = document.getElementById('main-header');
    
    window.addEventListener('scroll', () => {
      const currentScroll = window.pageYOffset;
      
      if (currentScroll <= 0) {
        header.classList.remove('hidden');
        return;
      }
      
      if (currentScroll > lastScroll && !header.classList.contains('hidden')) {
        // Scroll down
        header.classList.add('hidden');
      } else if (currentScroll < lastScroll && header.classList.contains('hidden')) {
        // Scroll up
        header.classList.remove('hidden');
      }
      
      lastScroll = currentScroll;
    });

    // Back to top button
    const backToTopButton = document.getElementById('back-to-top');
    
    window.addEventListener('scroll', () => {
      if (window.pageYOffset > 300) {
        backToTopButton.classList.add('visible');
      } else {
        backToTopButton.classList.remove('visible');
      }
    });
    
    backToTopButton.addEventListener('click', () => {
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    });

    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function(e) {
        e.preventDefault();
        
        const targetId = this.getAttribute('href');
        if (targetId === '#') return;
        
        const targetElement = document.querySelector(targetId);
        if (targetElement) {
          window.scrollTo({
            top: targetElement.offsetTop - 80,
            behavior: 'smooth'
          });
        }
      });
    });

    // Highlight active section in navigation
    const sections = document.querySelectorAll('section');
    const navLinks = document.querySelectorAll('nav a');
    
    window.addEventListener('scroll', () => {
      let current = '';
      
      sections.forEach(section => {
        const sectionTop = section.offsetTop;
        const sectionHeight = section.clientHeight;
        
        if (pageYOffset >= sectionTop - 150) {
          current = section.getAttribute('id');
        }
      });
      
      navLinks.forEach(link => {
        link.classList.remove('active');
        if (link.getAttribute('href') === `#${current}`) {
          link.classList.add('active');
        }
      });
    });
  </script>

</body>
</html>