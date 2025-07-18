
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dr. Cheung Dental Clinic</title>
  <link rel="stylesheet" href="css/foradmin.css">
  <link rel="stylesheet" href="css/inquiries.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"/>
  <style>
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

            <li><a href="dashboard.php"> 
              <i class="fa-solid fa-house"></i>
              <span class="nav-item">DashBoard</span>
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
    <h1>Inquiries</h1>
  </div>

  <!-- Schedule Table -->
  <div class="table-section">
    <h3>List of Inquiries</h3>
    <div class="table-scroll">
    <table class="schedule-table">
<thead>
    <tr>
        <th>Name</th>
        <th>Contact Number</th>
        <th>Email</th>
        <th>Message</th>
        <th>Date and Time</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
</thead>
<tbody>
  <!--Information in Database-->
<?php
include('dbcon.php');
$sql = "SELECT * FROM inquiries";
$result = $con->query($sql);

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>".htmlspecialchars($row['name'])."</td>";
        echo "<td>".htmlspecialchars($row['phone'])."</td>";
        echo "<td>".htmlspecialchars($row['email'])."</td>";
        echo "<td>".htmlspecialchars($row['message'])."</td>";
        echo "<td>".htmlspecialchars($row['created_at'])."</td>";
        echo "<td>".($row['status'] == 'replied' ? 'Replied' : 'Pending')."</td>";
        echo "<td><button class='replyBtn' 
                    data-id='".$row['inquiry_id']."' 
                    data-email='".$row['email']."' 
                    data-name='".$row['name']."' 
                    data-message='".htmlspecialchars($row['message'])."'>
                    Reply
              </button></td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='7'>No inquiries found.</td></tr>";
}
?>
<!--Information in Database-->

<!--pop up inquiry-->
</tbody>
</table>
<div id="replyModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h2>Reply to Inquiry</h2>
    <form id="replyForm" method="POST" action="reply_inquiry.php">
    <input type="hidden" name="inquiry_id" id="inquiry_id">
        <input type="hidden" name="email" id="email">
        
        <label>Client's Message:</label><br>
        <textarea id="client_message" style="width: 600px;" rows="3" disabled></textarea><br><br>

        <label>Your Reply:</label><br>
        <textarea name="reply_message" style="width: 600px;" id="reply_message" rows="5" required></textarea><br><br>

        <button type="submit" class="btn btn-success">Send Reply</button>
    </form>
  </div>
</div>


<!--pop up inquiry-->

 <!--para sa mag pop up-->
<script>
var modal = document.getElementById("replyModal");
var span = document.getElementsByClassName("close")[0];
var replyBtns = document.querySelectorAll(".replyBtn");

replyBtns.forEach(function(button) {
    button.addEventListener('click', function() {
        var id = this.getAttribute('data-id');
        var email = this.getAttribute('data-email');
        var message = this.getAttribute('data-message');

        document.getElementById('inquiry_id').value = id;
        document.getElementById('email').value = email;
        document.getElementById('client_message').value = message;

        modal.style.display = "block";
    });
});

// Close modal when clicking X
span.onclick = function() {
  modal.style.display = "none";
}

// Close modal when clicking outside
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
</script>
    </div>
  </div>
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
