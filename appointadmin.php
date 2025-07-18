<?php
include 'dbcon.php'; // ensure your DB connection is loaded

$query_appointments = "
SELECT 
    a.id,
    a.name,
    a.payment_method,
    a.services,
    a.date,
    a.time,
    a.status,
    a.created_at
FROM appointments a
ORDER BY a.date DESC, a.time DESC
";

$result_appointments = mysqli_query($con, $query_appointments);

if (!$result_appointments) {
    die('Error in appointments query: ' . mysqli_error($con));
}

$appointments = mysqli_num_rows($result_appointments) > 0
    ? mysqli_fetch_all($result_appointments, MYSQLI_ASSOC)
    : [];
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
            <li class="title"><img src="img/logo3.png" alt=""/>
              <span class="nav-item">Dentrix</span>
            </li>
            <li><a href="dashboard.php"> 
              <i class="fa-solid fa-house"></i>
              <span class="nav-item">Dashboard</span>
            </a></li>
            <li><a href="appointadmin.php" class="active">
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
          <h1>Appointment Schedule</h1>
        </div>

        <!-- Schedule Table -->
        <div class="table-section">
          <h3>All Booked Appointments</h3>
          <div class="table-scroll">
            <table class="schedule-table">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Contact</th>
                  <th>Services</th>
                  <th>Schedule Date</th>
                  <th>Schedule Time</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <?php if (count($appointments) > 0): ?>
                  <?php foreach ($appointments as $appointment): ?>
                    <tr>
                      <td><?= htmlspecialchars($appointment['name']) ?></td>
                      <td><?= htmlspecialchars($appointment['payment_method']) ?></td>
                      <td><?= htmlspecialchars($appointment['services']) ?></td>
                      <td><?= date("F j, Y", strtotime($appointment['date'])) ?></td>
                      <td><?= date("g:i A", strtotime($appointment['time'])) ?></td>
                      <td>
                        <?php
                          // Display status in a more user-friendly manner
                          switch (strtolower($appointment['status'])) {
                            case 'booked':
                              echo '<span style="color: green;">Booked</span>';
                              break;
                            case 'pending':
                              echo '<span style="color: orange;">Pending</span>';
                              break;
                            case 'cancelled':
                              echo '<span style="color: red;">Cancelled</span>';
                              break;
                            default:
                              echo '<span style="color: gray;">Unknown</span>';
                              break;
                          }
                        ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr><td colspan="6">No appointments found.</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </section>
  </div>

  <script>
    function confirmLogout() {
      if (confirm("Are you sure you want to log out?")) {
        window.location.href = 'logout.php';
      }
    }
  </script>

</body>
</html>
