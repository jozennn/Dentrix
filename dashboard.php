<?php include 'dbcon.php';

// Fetch total users
$sql = "SELECT COUNT(*) AS total_users FROM users";
$result = $con->query($sql);
$row = $result->fetch_assoc();
$totalusers = $row['total_users'];

// Fetch total inquiries
$sql = "SELECT COUNT(*) AS total_inquiries FROM inquiries";
$result = $con->query($sql);
$totalinquiries = $result ? $result->fetch_assoc()['total_inquiries'] : 0;

// Fetch total appointments
$sql = "SELECT COUNT(*) AS total_appointments FROM appointments";
$result = $con->query($sql);
$totalappointments = $result ? $result->fetch_assoc()['total_appointments'] : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dr. Cheung Dental Clinic</title>
  <link rel="stylesheet" href="css/foradmin.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"/>
</head>
<body>

  <div class="container">
    <nav>
        <ul>
            <li class="title"><img src="img/logo3.png" alt="">
              <span class="nav-item">Dentrix</span>
            </li>

            <li><a href="dashboard.php"> 
              <i class="fa-solid fa-house"></i>
              <span class="nav-item">Dashboard</span>
            </a></li>

            <li><a href="appointadmin.php">
            <i class="fa-solid fa-calendar-check"></i>
            <span class="nav-item">Appointment</span>
            </a></li>

            <li><a href="inquiries.php">
            <i class="fa-solid fa-envelope"></i>
            <span class="nav-item">Inquiries</span>
            </a></li>

            <li><a href="acc.php">
          <i class="fa-solid fa-circle-user"></i>
          <span class="nav-item">Accounts</span>
            </a></li>

            <li><a href="pending.php">
          <i class="fa-solid fa-exclamation"></i>
          <span class="nav-item">Pending</span>
            </a></li>

            <li><a href="admin.php" class="logout">
            <i class="fa-solid fa-gear"></i>
            <span class="nav-item">Settings</span>
            </a></li>
        </ul>
    </nav>

    <section class="main">
      <div class="main">
        <div class="main-top">
          <h1>Dashboard</h1>
          <div class="button-group">
            <a href="reports.php?mode=view" class="btn-main">View Reports</a>
            <a href="reports.php?mode=download" class="btn-secondary">Download Reports</a>
          </div>
        </div>

        <!-- Top Cards -->
        <div class="card-container">
          <a href="acc.php" class="link">
            <div class="card">
              <i class="fa-solid fa-circle-user"></i>
              <h3>Number of Users</h3>
              <p><?php echo $totalusers; ?></p>
            </div>
          </a>

          <a href="inquiries.php" class="link">
            <div class="card">
              <i class="fa-solid fa-envelope"></i>
              <h3>Number of Inquiries</h3>
              <p><?php echo $totalinquiries; ?></p>
            </div>
          </a>

          <a href="appointadmin.php" class="link">
            <div class="card">
              <i class="fa-solid fa-calendar-check"></i>
              <h3>Number of Appointments</h3>
              <p><?php echo $totalappointments; ?></p>
            </div>
          </a>
        </div>

        <!-- Chart Container -->
        <div style="display: flex; justify-content: center; gap: 20px; flex-wrap: wrap; margin-top: 20px;">
          <!-- User Registrations Chart -->
          <div class="chart-container" style="width: 100%; max-width: 500px; padding: 10px; background-color: #f9f9f9; border-radius: 10px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);">
            <h3 style="text-align: center; color: #003366;">User Registrations</h3>
            <canvas id="userChart"></canvas>
          </div>

          <!-- Appointments Chart -->
          <div class="chart-container" style="width: 100%; max-width: 500px; padding: 10px; background-color: #f9f9f9; border-radius: 10px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);">
            <h3 style="text-align: center; color: #003366;">Appointments</h3>
            <canvas id="appointmentChart"></canvas>
          </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
          // Fetch data for User Registrations
          const userData = {
            daily: <?php 
              $sql = "SELECT COUNT(*) AS total FROM users WHERE DATE(created_at) = CURDATE()";
              $result = $con->query($sql);
              echo $result ? $result->fetch_assoc()['total'] : 0;
            ?>,
            weekly: <?php 
              $sql = "SELECT COUNT(*) AS total FROM users WHERE YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1)";
              $result = $con->query($sql);
              echo $result ? $result->fetch_assoc()['total'] : 0;
            ?>,
            monthly: <?php 
              $sql = "SELECT COUNT(*) AS total FROM users WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())";
              $result = $con->query($sql);
              echo $result ? $result->fetch_assoc()['total'] : 0;
            ?>,
            yearly: <?php 
              $sql = "SELECT COUNT(*) AS total FROM users WHERE YEAR(created_at) = YEAR(CURDATE())";
              $result = $con->query($sql);
              echo $result ? $result->fetch_assoc()['total'] : 0;
            ?>
          };

          // Fetch data for Appointments
          const appointmentData = {
            daily: <?php 
              $sql = "SELECT COUNT(*) AS total FROM patients WHERE DATE(schedule) = CURDATE()";
              $result = $con->query($sql);
              echo $result ? $result->fetch_assoc()['total'] : 0;
            ?>,
            weekly: <?php 
              $sql = "SELECT COUNT(*) AS total FROM patients WHERE YEARWEEK(schedule, 1) = YEARWEEK(CURDATE(), 1)";
              $result = $con->query($sql);
              echo $result ? $result->fetch_assoc()['total'] : 0;
            ?>,
            monthly: <?php 
              $sql = "SELECT COUNT(*) AS total FROM patients WHERE MONTH(schedule) = MONTH(CURDATE()) AND YEAR(schedule) = YEAR(CURDATE())";
              $result = $con->query($sql);
              echo $result ? $result->fetch_assoc()['total'] : 0;
            ?>,
            yearly: <?php 
              $sql = "SELECT COUNT(*) AS total FROM patients WHERE YEAR(schedule) = YEAR(CURDATE())";
              $result = $con->query($sql);
              echo $result ? $result->fetch_assoc()['total'] : 0;
            ?>
          };

          // Render User Registrations Chart
          const userCtx = document.getElementById('userChart').getContext('2d');
          if (userCtx) {
            new Chart(userCtx, {
              type: 'bar',
              data: {
                labels: ['Today', 'This Week', 'This Month', 'This Year'],
                datasets: [{
                  label: 'User Registrations',
                  data: [userData.daily, userData.weekly, userData.monthly, userData.yearly],
                  backgroundColor: ['#003366', '#0055aa', '#0077cc', '#0099ff'],
                  borderColor: ['#002244', '#004488', '#0066aa', '#0088cc'],
                  borderWidth: 1
                }]
              },
              options: {
                responsive: true,
                plugins: {
                  legend: {
                    display: false
                  }
                },
                scales: {
                  y: {
                    beginAtZero: true
                  }
                }
              }
            });
          }

          // Render Appointments Chart
          const appointmentCtx = document.getElementById('appointmentChart').getContext('2d');
          if (appointmentCtx) {
            new Chart(appointmentCtx, {
              type: 'bar',
              data: {
                labels: ['Today', 'This Week', 'This Month', 'This Year'],
                datasets: [{
                  label: 'Appointments',
                  data: [appointmentData.daily, appointmentData.weekly, appointmentData.monthly, appointmentData.yearly],
                  backgroundColor: ['#ff6666', '#ff9999', '#ffcccc', '#ffe6e6'],
                  borderColor: ['#cc3333', '#cc6666', '#cc9999', '#ccbbbb'],
                  borderWidth: 1
                }]
              },
              options: {
                responsive: true,
                plugins: {
                  legend: {
                    display: false
                  }
                },
                scales: {
                  y: {
                    beginAtZero: true
                  }
                }
              }
            });
          }
        </script>
      </div>
    </section>
  </div>

  <script>
    function confirmLogout() {
      var confirmation = confirm("Are you sure you want to log out?");
      if (confirmation) {
        window.location.href = 'logout.php';
      }
    }
  </script>

</body>
</html>