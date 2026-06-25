<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
  .footer-section {
    background: linear-gradient(135deg, #020617, #081042, #4c1d95);
    border-top: 1px solid rgba(124, 58, 237, 0.2);
    padding: 30px 0 15px;
    margin-top: 40px;
  }

  .footer-col {
    margin-bottom: 20px;
  }

  .footer-title {
    font-size: 16px;
    font-weight: 700;
    color: #60a5fa;
    margin-bottom: 10px;
    background: linear-gradient(135deg, #60a5fa 0%, #8b5cf6 100%);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
  }

  .footer-text {
    color: #cbd5e1;
    line-height: 1.8;
    font-size: 14px;
  }

  .footer-links {
    list-style: none;
    padding: 0;
  }

  .footer-links li {
    margin-bottom: 6px;
  }

  .footer-links a {
    color: #cbd5e1;
    text-decoration: none;
    font-size: 14px;
    transition: all 0.3s ease;
  }

  .footer-links a:hover {
    color: #60a5fa;
    padding-left: 5px;
  }

  .social-links {
    display: flex;
    gap: 15px;
  }

  .social-links a {
    width: 40px;
    height: 40px;
    display: flex;
    justify-content: center;
    align-items: center;
    background: rgba(96, 165, 250, 0.1);
    border-radius: 50%;
    color: #60a5fa;
    transition: all 0.3s ease;
  }

  .social-links a:hover {
    background: linear-gradient(90deg, #2563eb, #7c3aed);
    color: white;
    transform: translateY(-3px);
  }

  .footer-divider {
    border-color: rgba(124, 58, 237, 0.2);
    margin: 20px 0;
  }

  .footer-bottom {
    text-align: center;
    color: #94a3b8;
    font-size: 14px;
  }

  @media (max-width: 768px) {
    .footer-section {
      padding: 40px 0 20px;
    }

    .footer-title {
      font-size: 16px;
    }

    .social-links {
      justify-content: flex-start;
    }
  }
</style>

<!-- FOOTER SECTION -->
<footer class="footer-section">
  <div class="container">
    <div class="row">
      <div class="col-md-6 col-lg-3 footer-col">
        <h4 class="footer-title">
          Career Advisor
        </h4>
        <p class="footer-text">
          Your perfect guide to choose the best career path based on your
          interests and aptitude.
        </p>
      </div>

      <div class="col-md-6 col-lg-3 footer-col">
        <h4 class="footer-title">Quick Links</h4>
        <ul class="footer-links">
          <li><a href="index.php">Home</a></li>
          <li><a href="quiz.php">Take Quiz</a></li>
          <li><a href="course_details.php">Courses</a></li>
          <li><a href="colleges.php">Colleges</a></li>
        </ul>
      </div>

      <div class="col-md-6 col-lg-3 footer-col">
        <h4 class="footer-title">Resources</h4>
        <ul class="footer-links">
          <li><a href="#">About Us</a></li>
          <li><a href="#">Contact</a></li>
          <li><a href="#">Privacy Policy</a></li>
          <li><a href="#">Terms & Conditions</a></li>
        </ul>
      </div>

      <div class="col-md-6 col-lg-3 footer-col">
        <h4 class="footer-title">Follow Us</h4>
        <div class="social-links">
          <a href="#"><i class="fab fa-facebook"></i></a>
          <a href="#"><i class="fab fa-twitter"></i></a>
          <a href="#"><i class="fab fa-linkedin"></i></a>
          <a href="#"><i class="fab fa-instagram"></i></a>
        </div>
      </div>
    </div>

    <hr class="footer-divider" />

    <div class="footer-bottom">
      <p>&copy; 2026 Career Advisor. All rights reserved.</p>
    </div>
  </div>
</footer>