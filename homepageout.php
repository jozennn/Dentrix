<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Dr. Cheung Dental Clinic</title>
  <link rel="stylesheet" href="css/homepageout.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>

  <header>
    <div class="logo">DR. CHEUNG DENTAL CLINIC</div>
    <nav>
      <ul>
        <li><a href="#">Home</a></li>
        <li><a href="#about">About</a></li>
        <li><a href="#services">Services</a></li>
        <li><a href="#contact">Contact</a></li>
        <li><a href="register.php">Sign up</a></li>
        <li><a href="portal.php">Login</a></li>
      </ul>
    </nav>
  </header>

  <!-- Carousel Section -->
  <section class="hero-section">
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-inner">
        <?php
        include 'dbcon.php';
        $sql = "SELECT * FROM carousel";
        $result = mysqli_query($con, $sql);
        $isActive = true;

        if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
            echo '<div class="carousel-item ' . ($isActive ? 'active' : '') . '">';
            echo '<img src="uploads/' . htmlspecialchars($row['image']) . '" class="d-block w-100" alt="' . htmlspecialchars($row['title']) . '">';
            echo '<div class="carousel-caption d-none d-md-block">';
            echo '<h1>' . htmlspecialchars($row['title']) . '</h1>';
            echo '<p>' . htmlspecialchars($row['description']) . '</p>';
            echo '</div>';
            echo '</div>';
            $isActive = false;
          }
        } else {
          echo '<div class="carousel-item active">';
          echo '<img src="img/default-carousel.jpg" class="d-block w-100" alt="Default Carousel">';
          echo '<div class="carousel-caption d-none d-md-block">';
          echo '<h1>Welcome to Dr. Cheung Dental Clinic</h1>';
          echo '<p>Your smile is our priority.</p>';
          echo '</div>';
          echo '</div>';
        }
        ?>
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
    </div>
  </section>

  <!-- About Section -->
  <section class="about" id="about">
    <h2>ABOUT US</h2>
    <?php
    $sql = "SELECT * FROM about LIMIT 1";
    $result = mysqli_query($con, $sql);
    $about = mysqli_fetch_assoc($result);

    if ($about) {
      echo '<p>' . htmlspecialchars($about['content']) . '</p>';
    } else {
      echo '<p>Welcome to Dr. Cheung Dental Clinic. Your smile is our priority.</p>';
    }
    ?>
  </section>
<!-- Services Section -->
<section class="services-section" id="services">
  <div class="services-bg">
    <h2>Services</h2>
    <p>General Dentistry and Orthodontist</p>
  </div>

  <div class="service-cards">
    <?php
    $sql = "SELECT * FROM services LIMIT 3"; // Limit to 3 services
    $result = mysqli_query($con, $sql);

    if (mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
        echo '<div class="card">';
        echo '<img src="uploads/' . htmlspecialchars($row['image']) . '" alt="' . htmlspecialchars($row['name']) . '">';
        echo '<p>' . htmlspecialchars($row['name']) . '</p>';
        echo '</div>';
      }
    } else {
      echo '<p>No services available at the moment. Please check back later.</p>';
    }
    ?>
  </div>

  <button id="openPopup" class="button">More Services →</button>

  <div class="overlay" id="overlay"></div>
  <div class="popup" id="popup">
    <h2>Log In</h2>
    <form action="login.php" method="post">
      <input type="email" name="email" placeholder="Email" required />
      <div class="password-wrapper">
        <input type="password" name="password" id="password" placeholder="Password" required>
        <i class="bi bi-eye-slash" id="togglePassword" tabindex="-1"></i>
      </div>
      <!-- User Type Selection -->
      <div class="user-type-box">
        <p>Select Account Type:</p>
        <div class="radio-options">
          <label>
            <input type="radio" name="type" value="Admin" required />
            Admin
          </label>
          <label>
            <input type="radio" name="type" value="Customer" required />
            Customer
          </label>
        </div>
      </div>
      <button type="submit" name="login">Log In</button>
      <p>Don't have an account? <a href="register.php">Register</a></p>
      <p><a href="recover_psw.php">Forgot your password?</a></p>
    </form>
    <button id="closePopup">Close</button>
  </div>
</section>

<!-- Reviews Section -->
<section class="ratings" id="ratings">
  <h2>Reviews</h2>
  <div class="review-cards">
    <?php
    $sql = "SELECT r.review, r.rating, t.name 
            FROM reviews r 
            JOIN users t ON r.user_id = t.id 
            ORDER BY r.created_at DESC";
    $result = mysqli_query($con, $sql);

    if (mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
        echo '<div class="review-card">';
        echo '<h3 class="review-name">' . htmlspecialchars($row['name']) . '</h3>';
        echo '<p class="review-rating">' . str_repeat('★', $row['rating']) . '</p>';
        echo '<p class="review-text">"' . htmlspecialchars($row['review']) . '"</p>';
        echo '</div>';
      }
    } else {
      echo '<p>No reviews available yet. Be the first to leave a review!</p>';
    }
    ?>
  </div>

  <button id="openPopup2" class="button">Submit a Review ★★★★★</button>

  <div class="overlay" id="overlay2"></div>
  <div class="popup" id="popup2">
    <h2>Log In</h2>
    <form action="login.php" method="post">
      <input type="email" name="email" placeholder="Email" required />
      <div class="password-wrapper">
        <input type="password" name="password" id="password2" placeholder="Password" required>
        <i class="bi bi-eye-slash" id="togglePassword2" tabindex="-1"></i>
      </div>
      <!-- User Type Selection -->
      <div class="user-type-box">
        <p>Select Account Type:</p>
        <div class="radio-options">
          <label>
            <input type="radio" name="type" value="Admin" required />
            Admin
          </label>
          <label>
            <input type="radio" name="type" value="Customer" required />
            Customer
          </label>
        </div>
      </div>
      <button type="submit" name="login">Log In</button>
      <p>Don't have an account? <a href="register.php">Register</a></p>
      <p><a href="recover_psw.php">Forgot your password?</a></p>
    </form>
    <button id="closePopup2">Close</button>
  </div>
</section>

  <footer>
    <div class="footer-content" id="contact">
      <div>
        <strong>Email:</strong><br>
        henrycheung0912@gmail.com
      </div>
      <div>
        <strong>Location:</strong><br>
        2F Hoveler Building, M.L. Quezon Extn. Dalig, Antipolo City
      </div>
      <div>
        <strong>Clinic Hours:</strong><br>
        Mon-Fri: 10AM – 5PM<br>
        Sat-Sun: 10AM – 3PM
      </div>
      <div>
        <strong>Phone:</strong><br>
        395-0937
      </div>
    </div>
  </footer>

  <script>
    // For "More Services" popup
document.getElementById('openPopup').onclick = function () {
  document.getElementById('popup').style.display = 'block';
  document.getElementById('overlay').style.display = 'block';
};
document.getElementById('closePopup').onclick = function () {
  document.getElementById('popup').style.display = 'none';
  document.getElementById('overlay').style.display = 'none';
};
    // For "Submit a Review" popup
    document.getElementById('openPopup2').onclick = function () {
      document.getElementById('popup2').style.display = 'block';
      document.getElementById('overlay2').style.display = 'block';
    };
    document.getElementById('closePopup2').onclick = function () {
      document.getElementById('popup2').style.display = 'none';
      document.getElementById('overlay2').style.display = 'none';
    };
  </script>
</body>

</html>
<script>
  // Fade-in animation when About section is in view
  document.addEventListener("DOMContentLoaded", function() {
    const aboutSection = document.querySelector('.about');

    function reveal() {
      const windowHeight = window.innerHeight;
      const elementTop = aboutSection.getBoundingClientRect().top;
      const elementVisible = 150;

      if (elementTop < windowHeight - elementVisible) {
        aboutSection.classList.add("show");
      }
    }

    window.addEventListener("scroll", reveal);
    reveal(); // Check immediately on page load
  });
</script>
