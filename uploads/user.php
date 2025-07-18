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
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
            aria-expanded="false">
            <i class="bi bi-person-circle"></i> More...
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="profile.php">Profile</a></li>
            <li><a id="openAppointmentBtn" class="dropdown-item" href="#">My Appointment</a></li>
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

  <div id="appointmentModal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h2>Your Appointment</h2>
      <div id="appointmentDetails">
        <p><strong>Date:</strong> </p>
        <p><strong>Time:</strong> </p>
      </div>
    </div>
  </div>

  <!--pop up inquiry-->
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
  <script>
    // Get modal and button
    var modal = document.getElementById("inquiryModal");
    var btn = document.getElementById("openInquiryBtn");
    var span = document.getElementsByClassName("close")[0];

    // Open modal
    btn.onclick = function() {
      modal.style.display = "flex"; // Ensure the modal uses flex display
    };

    // Close modal when X clicked
    span.onclick = function() {
      modal.style.display = "none";
    };

    // Close modal if outside clicked
    window.onclick = function(event) {
      if (event.target == modal) {
        modal.style.display = "none";
      }
    }
  </script>

  <!--dulo ng pop up inquiry-->

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
            // Add the title and description here
            echo '<div class="carousel-caption d-none d-md-block">';
            echo '<h1>' . htmlspecialchars($row['title']) . '</h1>'; // Title
            echo '<p>' . htmlspecialchars($row['description']) . '</p>'; // Description
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
  $about = mysqli_fetch_assoc($result);
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
     

        // Fetch reviews from the database
        $sql = "SELECT r.review, r.rating, r.created_at, t.name 
              FROM reviews r 
              JOIN test t ON r.user_id = t.id 
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
      var confirmation = confirm("Are you sure you want to log out?");
      if (confirmation) {
        window.location.href = 'logout.php';
      }
    }


    var appointmentModal = document.getElementById("appointmentModal");
    var openAppointmentBtn = document.getElementById("openAppointmentBtn");
    var closeAppointmentBtn = appointmentModal.getElementsByClassName("close")[0];

    openAppointmentBtn.onclick = function () {
      appointmentModal.style.display = "flex";
    };

    closeAppointmentBtn.onclick = function () {
      appointmentModal.style.display = "none";
    };

    window.onclick = function (event) {
      if (event.target == appointmentModal) {
        appointmentModal.style.display = "none";
      }
    };

    const reviewPopup = document.getElementById('reviewPopup');
    const reviewOverlay = document.getElementById('reviewOverlay');

    document.getElementById('openReviewPopup').onclick = () => {
      reviewPopup.classList.add('active');
      reviewOverlay.classList.add('active');
    };

    document.getElementById('closeReviewPopup').onclick = () => {
      reviewPopup.classList.remove('active');
      reviewOverlay.classList.remove('active');
    };

    // Review Submit Confirmation
    function submitReview(event) {
      event.preventDefault();
      document.getElementById('reviewSuccess').style.display = 'block';
      setTimeout(() => {
        reviewPopup.classList.remove('active');
        reviewOverlay.classList.remove('active');
        document.getElementById('reviewSuccess').style.display = 'none';
      }, 2000);
    }
  </script>
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

</body>

</html>