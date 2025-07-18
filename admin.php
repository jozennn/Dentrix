

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dr. Cheung Dental Clinic</title>
  <link rel="stylesheet" href="css/admin.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<header>
      <div class="logo">
          <img src="img/logo.png" alt="Logo"> DR. CHEUNG DENTAL CLINIC
      </div>
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
                      <li><a class="dropdown-item" href="appointadmin.php">Schedule</a></li>
                      <li><a class="dropdown-item" href="dashboard.php">Dashboard</a></li>
                      <li><hr class="dropdown-divider"></li>
                      <li><button class="dropdown-item text-danger" onclick="confirmLogout()">Log out</button></li>
                  </ul>
              </li>
          </ul>
      </nav>
  </header>

<!-- Manage Carousel Section -->
<section class="manage-carousel">
  <h2>Manage Carousel</h2>
  <form action="add_carousel.php" method="POST" enctype="multipart/form-data" onsubmit="return confirmAddCarousel()">
      <input type="file" name="image" accept="image/*" required>
      <input type="text" name="title" placeholder="Title" required>
      <textarea name="description" placeholder="Description" rows="3" required></textarea>
      <button type="submit">Add Slide</button>
  </form>

  <table class="slides-table">
      <thead>
          <tr>
              <th>Title</th>
              <th>Description</th>
              <th>Action</th>
          </tr>
      </thead>
      <tbody>
          <?php
          include 'dbcon.php';

          $sql = "SELECT * FROM carousel";
          $result = mysqli_query($con, $sql);

          if (mysqli_num_rows($result) > 0) {
              while ($row = mysqli_fetch_assoc($result)) {
                  echo '<tr data-id="' . $row['carousel_id'] . '">';
                  echo '<td>' . htmlspecialchars($row['title']) . '</td>';
                  echo '<td>' . htmlspecialchars($row['description']) . '</td>';
                  echo '<td><button class="btn btn-danger" onclick="deleteCarousel(' . $row['carousel_id'] . ')">Delete</button></td>';
                  echo '</tr>';
              }
          } else {
              echo '<tr><td colspan="3">No slides available.</td></tr>';
          }
          ?>
      </tbody>
  </table>
</section>
<!-- About Section Management -->
<section class="manage-about">
  <h2>Manage About Section</h2>
  <?php
  // Fetch the current "About Us" content
  $sql = "SELECT * FROM about LIMIT 1";
  $result = mysqli_query($con, $sql);
  $about = mysqli_fetch_assoc($result);
  ?>
  <form action="update_about.php" method="POST">
    <label for="title">Title:</label>
    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($about['title']); ?>" required>
    
    <label for="content">Content:</label>
    <textarea id="content" name="content" rows="5" required><?php echo htmlspecialchars($about['content']); ?></textarea>
    
    <button type="submit" class="btn btn-primary">Update About Section</button>
  </form>
</section>

<!-- Services Section -->
<section class="services-section" id="services">
  <div class="services-bg">
    <p>General Dentistry and Orthodontist</p>
  </div>

  <div class="service-cards">
    <div class="card">
      <img src="img/oral-prophylaxis.png" alt="Oral Prophylaxis">
      <p>Oral Prophylaxis</p>
    </div>
    <div class="card">
      <img src="img/tooth.jpg" alt="Tooth Extraction">
      <p>Tooth Extraction</p>
    </div>
    <div class="card">
      <img src="img/dentures.jpg" alt="Dentures">
      <p>Dentures</p>
    </div>
  </div>

  <div class="button">
  <a href="appoint_admin.php">Manage Services →</a>
</div>
</section>

<!-- Reviews Section -->
<section class="manage-reviews">
  <h2>Manage Reviews</h2>
  <table class="slides-table">
    <thead>
      <tr>
        <th>Name</th>
        <th>Rating</th>
        <th>Review</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $sql = "SELECT r.review_id, r.review, r.rating, t.name 
              FROM reviews r 
              JOIN users t ON r.user_id = t.id 
              ORDER BY r.created_at DESC";
      $result = mysqli_query($con, $sql);

      if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
              echo '<tr data-id="' . $row['review_id'] . '">';
              echo '<td>' . htmlspecialchars($row['name']) . '</td>';
              echo '<td>' . str_repeat('★', $row['rating']) . '</td>';
              echo '<td>' . htmlspecialchars($row['review']) . '</td>';
              echo '<td><button class="btn btn-danger" onclick="deleteReview(' . $row['review_id'] . ')">Delete</button></td>';
              echo '</tr>';
          }
      } else {
          echo '<tr><td colspan="4">No reviews available.</td></tr>';
      }
      ?>
    </tbody>
  </table>
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
  function confirmAddCarousel() {
      return confirm("Are you sure you want to add this slide?");
  }

  function deleteCarousel(carouselId) {
    if (!confirm("Are you sure you want to delete this slide?")) return;

    fetch('delete_carousel.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `id=${encodeURIComponent(carouselId)}`
    })
    .then(res => res.text())
    .then(response => {
        if (response === 'success') {
            document.querySelector(`tr[data-id="${carouselId}"]`).remove();
        } else {
            alert("Error deleting slide.");
        }
    })
    .catch(error => {
        console.error("Error:", error);
        alert("An error occurred while deleting the slide.");
    });
}
  function confirmLogout() {
      var confirmation = confirm("Are you sure you want to log out?");
      if (confirmation) {
          window.location.href = 'logout.php';
      }
  }
</script>
<script>
 function deleteReview(reviewId) {
    if (confirm("Are you sure you want to delete this review?")) {
        fetch('delete_review.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `review_id=${reviewId}` // Send the review ID to delete
        })
        .then(response => response.text())
        .then(data => {
            if (data === 'success') {
                // If deletion is successful, remove the row from the table
                const row = document.querySelector(`tr[data-id="${reviewId}"]`);
                if (row) {
                    row.remove(); // Remove the review row from the table
                }
            } else {
                alert('Failed to delete review. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error deleting review:', error);
            alert('An error occurred while deleting the review.');
        });
    }
}

</script>
</body>
</html>