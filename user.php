<?php
session_start();
include 'dbcon.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Dr. Cheung Dental Clinic</title>
  <link rel="stylesheet" href="css/user.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    /* Popup style */
    .popup, .overlay {
      display: none;
    }

    .popup.active, .overlay.active {
      display: block;
    }

    .overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
    }

    .popup {
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background: white;
      padding: 20px;
      max-width: 400px;
      width: 100%;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .close-button {
      display: block;
      margin: 10px 0;
      background-color: red;
      color: white;
      border: none;
      padding: 10px;
      cursor: pointer;
    }
  </style>
</head>

<body>

  <header>
    <div class="logo"> <img src="img/logo.png"> DR. CHEUNG DENTAL CLINIC</div>
    <nav>
      <ul>
        <li><a href="#">Home</a></li>
        <li><a href="#about">About</a></li>
        <li><a href="#services">Services</a></li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-person-circle"></i> More...
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="profile.php">Profile</a></li>
            <li><a class="dropdown-item" href="invoice.php">My Appointment</a></li>
            <li><a id="openInquiryBtn" class="btn">Submit Inquiry</a></li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li><button class="dropdown-item text-danger" onclick="confirmLogout()">Log out</button></li>
          </ul>
        </li>
      </ul>
    </nav>
  </header>

  <!-- Inquiry Popup -->
  <div id="inquiryModal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h2>Submit Inquiry</h2>
      <form action="submit_inquiry.php" method="POST">
        <label for="message">Message:</label>
        <textarea id="message" name="message" rows="4" required></textarea>
        <button type="submit" class="btn btn-success">Submit Inquiry</button>
      </form>
    </div>
  </div>

  <!-- Review Popup -->
  <div class="overlay" id="reviewOverlay"></div>
  <div class="popup" id="reviewPopup">
    <form class="popup-form" action="submit_review.php" method="POST">
      <h2>Submit Review</h2>
      <textarea name="review" placeholder="Write your review..." rows="4" required></textarea>
      <select name="rating" required>
        <option value="">Select Rating</option>
        <option value="5">★★★★★ - Excellent</option>
        <option value="4">★★★★ - Good</option>
        <option value="3">★★★ - Fair</option>
        <option value="2">★★ - Poor</option>
        <option value="1">★ - Very Poor</option>
      </select>
      <button type="submit" class="button">Submit Review</button>
    </form>
    <button id="closeReviewPopup" class="close-button">Close</button>
  </div>

  <section class="hero-section">
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-inner">
        <?php
        $sql = "SELECT * FROM carousel";
        $result = mysqli_query($con, $sql);
        $active = true;

        if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
            echo '<div class="carousel-item ' . ($active ? 'active' : '') . '">';
            echo '<img src="uploads/' . htmlspecialchars($row['image']) . '" class="d-block w-100 carousel-image" alt="' . htmlspecialchars($row['title']) . '">';
            echo '<div class="carousel-caption d-none d-md-block">';
            echo '<h1>' . htmlspecialchars($row['title']) . '</h1>';
            echo '<p>' . htmlspecialchars($row['description']) . '</p>';
            echo '</div>';
            echo '</div>';
            $active = false;
          }
        } else {
          echo '<p>No slides available.</p>';
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
    <?php
    $sql = "SELECT * FROM about LIMIT 1";
    $result = mysqli_query($con, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
      $about = mysqli_fetch_assoc($result);
    } else {
      $about = ['title' => 'About Us', 'content' => 'No content available.'];
    }
    ?>
    <h2><?php echo htmlspecialchars($about['title']); ?></h2>
    <p><?php echo nl2br(htmlspecialchars($about['content'])); ?></p>
  </section>

  <section class="services-section" id="services">
    <div class="services-bg">
      <h1>General Dentistry and Orthodontist</h1>
    </div>

    <div class="service-cards">
      <div class="card">
        <img src="img/oral-prophylaxis.png" alt="Oral Prophylaxis" href="appoint.php">
        <p>Oral Prophylaxis</p>
      </div>
      <div class="card">
        <img src="img/tooth.jpg" alt="Tooth Extraction" href="appoint.php">
        <p>Tooth Extraction</p>
      </div>
      <div class="card">
        <img src="img/dentures.jpg" alt="Dentures" href="appoint.php">
        <p>Dentures</p>
      </div>
    </div>

    <div class="button">
      <a href="appoint.php">More Services →</a>
    </div>
  </section>

  <section class="ratings" id="ratings">
    <div class="container">
      <h2 class="section-title">Reviews</h2>
      <div class="review-cards">
        <?php
        $sql = "SELECT r.review, r.rating, r.created_at, t.name 
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
            echo '<p class="review-date">' . date('F j, Y', strtotime($row['created_at'])) . '</p>';
            echo '</div>';
          }
        } else {
          echo '<p>No reviews yet. Be the first to leave a review!</p>';
        }
        ?>
      </div>
      <button id="openReviewPopup" class="button">Submit a Review ★★★★★</button>
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
    function confirmLogout() {
      if (confirm("Are you sure you want to logout?")) {
        window.location.href = "logout.php";
      }
    }

    // Review popup control
    const reviewPopup = document.getElementById('reviewPopup');
    const reviewOverlay = document.getElementById('reviewOverlay');
  
    // Open review modal
    document.getElementById('openReviewPopup').onclick = function () {
      reviewPopup.classList.add('active');
      reviewOverlay.classList.add('active');
    };

    // Close review modal
    document.getElementById('closeReviewPopup').onclick = function () {
      reviewPopup.classList.remove('active');
      reviewOverlay.classList.remove('active');
    };

    // Close modal when clicking outside
    window.onclick = function(event) {
      if (event.target == reviewOverlay) {
        reviewPopup.classList.remove('active');
        reviewOverlay.classList.remove('active');
      }
    };
  </script>

</body>

</html>
