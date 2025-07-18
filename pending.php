<?php
include 'dbcon.php';

// Handle Accept/Decline
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payment_id = $_POST['payment_id'];
    $action = $_POST['action'];

    if ($action === 'accept') {
        // Fetch payment details first
        $payment_query = "SELECT * FROM payments WHERE id = ?";
        $stmt = $con->prepare($payment_query);
        $stmt->bind_param("i", $payment_id);
        $stmt->execute();
        $payment_result = $stmt->get_result();
        $payment_data = $payment_result->fetch_assoc();
        
        if ($payment_data) {
            // Set status to booked
            $status = 'booked';
            
            // Get the time_slot_id
            $slot_query = "SELECT id FROM time_slots WHERE date = ? AND time_slot = ?";
            $stmt2 = $con->prepare($slot_query);
            $stmt2->bind_param("ss", $payment_data['date'], $payment_data['time']);
            $stmt2->execute();
            $slot_result = $stmt2->get_result();
            $slot_data = $slot_result->fetch_assoc();
            
            if ($slot_data) {
                // Update time slot status to booked
                $stmt3 = $con->prepare("UPDATE time_slots SET status = ? WHERE id = ?");
                $stmt3->bind_param("si", $status, $slot_data['id']);
                $stmt3->execute();
                
                // Insert into appointments table
                $stmt4 = $con->prepare("INSERT INTO appointments (name, payment_method, services, status, time_slot_id, date, time) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt4->bind_param("ssssiss", $payment_data['gcash_name'], $payment_data['payment_method'], $payment_data['services'], $status, $slot_data['id'], $payment_data['date'], $payment_data['time']);
                $stmt4->execute();
                
                // Delete payment record
                $stmt5 = $con->prepare("DELETE FROM payments WHERE id = ?");
                $stmt5->bind_param("i", $payment_id);
                $stmt5->execute();
            }
        }
    } elseif ($action === 'decline') {
        // Get payment info first
        $stmt = $con->prepare("SELECT date, time FROM payments WHERE id = ?");
        $stmt->bind_param("i", $payment_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $payment_data = $result->fetch_assoc();
        
        if ($payment_data) {
            // Update time slot back to available
            $status = 'available';
            $stmt1 = $con->prepare("UPDATE time_slots SET status = ? WHERE date = ? AND time_slot = ?");
            $stmt1->bind_param("sss", $status, $payment_data['date'], $payment_data['time']);
            $stmt1->execute();
            
            // Delete payment record
            $stmt2 = $con->prepare("DELETE FROM payments WHERE id = ?");
            $stmt2->bind_param("i", $payment_id);
            $stmt2->execute();
        }
    }
    
    // Redirect to refresh the page
    header("Location: pending.php");
    exit();
}

// Fetch pending payments
$query = "SELECT id, gcash_name, payment_method, amount, services, date, time, created_at, gcash_qr FROM payments";
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
    .btn-edit, .btn-delete {
      padding: 6px 10px;
      margin: 2px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      color: white;
      font-weight: bold;
    }
    .btn-edit {
      background-color: #4CAF50;
    }
    .btn-delete {
      background-color: #f44336;
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
            <li><a href="admin.php" class="logout"><i class="fa-solid fa-right-from-bracket"></i><span class="nav-item">Homepage</span></a></li>
        </ul>
    </nav>

    <section class="main">
      <div class="main">
        <div class="main-top">
          <h1>Pending Schedule</h1>
        </div>

        <div class="table-section">
          <h3>Pending Payments</h3>
          <div class="table-scroll">
            <table class="schedule-table">
            <thead>
              <tr>
                <th>Name</th>
                <th>Mode of Payment</th>
                <th>Amount</th>
                <th>Services</th>
                <th>Schedule</th>
                <th>Reserved At</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                  <tr>
                    <td><?= htmlspecialchars($row['gcash_name']) ?></td> <!-- Full Name column -->
                    <td>
                      <?php 
                        // Display payment method (either GCash, In Person, etc.)
                        if (!empty($row['payment_method'])) {
                          echo htmlspecialchars($row['payment_method']);
                        } else {
                          echo 'N/A';
                        }
                      ?>
                    </td>
                    <td>₱<?= number_format($row['amount'], 2) ?></td>
                    <td><?= htmlspecialchars($row['services']) ?></td>
                    <td>
                      <?= date("F j, Y", strtotime($row['date'])) ?><br>
                      <?= date("g:i A", strtotime($row['time'])) ?>
                    </td>
                    <td>
                      <?= !empty($row['created_at']) ? date("F j, Y g:i A", strtotime($row['created_at'])) : 'N/A' ?>
                    </td>
                    <td>
                      <form method="POST" onsubmit="return confirm('Are you sure?')">
                        <input type="hidden" name="payment_id" value="<?= $row['id'] ?>">
                        <button type="submit" name="action" value="accept" class="btn-edit">Accept</button>
                        <button type="submit" name="action" value="decline" class="btn-delete">Decline</button>
                      </form>
                    </td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr><td colspan="7">No pending payments found.</td></tr>
              <?php endif; ?>
            </tbody>
            </table>
          </div>
        </div>
      </div>
    </section>
</div>
</body>
</html>
