<?php
include 'dbcon.php';

$limit = 4; // Show 4 users per page

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

$offset = ($page - 1) * $limit;

// Get total number of users
$countQuery = "SELECT COUNT(*) as total FROM users";
$countResult = mysqli_query($con, $countQuery);
$totalRows = mysqli_fetch_assoc($countResult)['total'];
$totalPages = ceil($totalRows / $limit);

// Get paginated users
$query = "SELECT id, name, phone, email, type, verify_status FROM users LIMIT $limit OFFSET $offset";
$result = mysqli_query($con, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($con));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dr. Cheung Dental Clinic</title>
  <link rel="stylesheet" href="css/foradmin.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"/>
  <style>
    /* Custom styles for the pagination buttons */
    .pagination {
      display: flex;
      justify-content: center;
      gap: 10px;
      margin-top: 20px;
    }
    .btn-paginate {
      padding: 8px 16px;
      border: 1px solid #ddd;
      background-color: #f4f4f4;
      color: #333;
      text-decoration: none;
      font-weight: bold;
      border-radius: 4px;
      transition: background-color 0.3s ease;
    }
    .btn-paginate:hover {
      background-color: #ccc;
    }
    .btn-paginate.active {
      background-color: #0066cc;
      color: white;
      border: 1px solid #0066cc;
    }
    .btn-paginate.disabled {
      background-color: #e0e0e0;
      color: #999;
      pointer-events: none;
    }
  </style>
</head>
<body>
<div class="container">
    <nav>
        <ul>
            <li class="title"><img src="img/logo3.png" alt="">
              <span class="nav-item">Dentrix</span>
            </li>
            <li><a href="dashboard.php"><i class="fa-solid fa-house"></i><span class="nav-item">Dashboard</span></a></li>
            <li><a href="appointadmin.php"><i class="fa-solid fa-calendar-check"></i><span class="nav-item">Appointment</span></a></li>
            <li><a href="inquiries.php"><i class="fa-solid fa-envelope"></i><span class="nav-item">Inquiries</span></a></li>
            <li><a href="acc.php"><i class="fa-solid fa-circle-user"></i><span class="nav-item">Accounts</span></a></li>
            <li><a href="pending.php"><i class="fa-solid fa-exclamation"></i><span class="nav-item">Pending</span></a></li>
            <li><a href="admin.php" class="logout"><i class="fa-solid fa-gear"></i><span class="nav-item">Settings</span></a></li>
        </ul>
    </nav>

    <section class="main">
      <div class="main">
        <div class="main-top">
          <h1>Account Manager</h1>
        </div>

        <!-- Accounts Table -->
        <div class="table-section">
          <h3>Account List</h3>
          <div class="table-scroll">
            <table class="schedule-table">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Phone</th>
                  <th>Email</th>
                  <th>Type</th>
                  <th>Status</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php
                if (mysqli_num_rows($result) > 0) {
                  while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                      <td>" . htmlspecialchars($row['name']) . "</td>
                      <td>" . htmlspecialchars($row['phone']) . "</td>
                      <td>" . htmlspecialchars($row['email']) . "</td>
                      <td>" . htmlspecialchars($row['type']) . "</td>
                      <td>" . htmlspecialchars($row['verify_status']) . "</td>
                      <td>
                        <a href='edit_acc.php?id=" . $row['id'] . "' class='btn-edit'>Edit</a>
                        <a href='delete_acc.php?id=" . $row['id'] . "' class='btn-delete' onclick='return confirm(\"Are you sure you want to delete this account?\")'>Delete</a>
                      </td>
                    </tr>";
                  }
                } else {
                  echo "<tr><td colspan='6'>No accounts found.</td></tr>";
                }
                ?>
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div class="pagination">
            <?php if ($page > 1): ?>
              <a href="?page=<?php echo $page - 1; ?>" class="btn-paginate">Previous</a>
            <?php else: ?>
              <a href="#" class="btn-paginate disabled">Previous</a>
            <?php endif; ?>

            <?php
              // Generate page numbers (up to 13 pages max)
              $start = max(1, $page - 2);
              $end = min($totalPages, $page + 2);

              for ($i = $start; $i <= $end; $i++) {
                echo "<a href='?page=$i' class='btn-paginate" . ($i == $page ? " active" : "") . "'>$i</a>";
              }
            ?>

            <?php if ($page < $totalPages): ?>
              <a href="?page=<?php echo $page + 1; ?>" class="btn-paginate">Next</a>
            <?php else: ?>
              <a href="#" class="btn-paginate disabled">Next</a>
            <?php endif; ?>
          </div>

        </div>
      </div>
    </section>
</div>
</body>
</html>
