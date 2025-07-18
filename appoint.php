<?php
session_start();
// Clear any previous session data related to appointment
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $_SESSION['selected_services'] = $_POST['selected_services']; // JSON string
  $_SESSION['total_amount'] = $_POST['total_amount'];
  $_SESSION['selected_date'] = $_POST['date'] ?? '';  // if present
  $_SESSION['selected_time'] = $_POST['time'] ?? '';  // if present

  header('Location: payment.php');
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Dr. Cheung Dental Clinic</title>
  <link rel="stylesheet" href="css/appoint.css">
</head>

<body>

  <header>
    <div class="logo">
      <img src="img/logo.png" alt="Logo">DR. CHEUNG DENTAL CLINIC
    </div>
    <nav>
      <ul>
        <li><a href="user.php"> <-- Go back to Homepage</a></li>
      </ul>
    </nav>
  </header>

  <main class="container">
    <section class="services-section">
      <h2>Choose a Service</h2>
      <div class="service-grid">
        <?php
        include 'dbcon.php';

        $servicesData = []; // Array to store services for JavaScript

        $sql = "SELECT * FROM services";
        $result = mysqli_query($con, $sql);

        if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
            $image = !empty($row['image']) && file_exists('uploads/' . $row['image'])
              ? 'uploads/' . htmlspecialchars($row['image'])
              : 'img/default.png';

            // Add service data to the array
            $servicesData[$row['name']] = $row['price'];

            echo '<div class="service-card" data-service="' . htmlspecialchars($row['name']) . '">';
            echo '<img src="' . $image . '" alt="' . htmlspecialchars($row['name']) . '">';
            echo '<p>' . htmlspecialchars($row['name']) . '</p>';
            echo '</div>';
          }
        } else {
          echo '<p>No services available.</p>';
        }
        ?>
      </div>
    </section>

    <aside class="selection-box">
      <h3>Selected Procedures</h3>

      <div class="procedure-list"></div>

      <div class="bottom-controls">
        <div class="total-container">
          <p class="total">Total</p>
          <p class="price"><span>₱0</span></p>
        </div>
        <div class="button-group">
          <form action="schedule.php" method="POST" id="scheduleForm">
            <input type="hidden" name="selected_services" id="selectedServices">
            <input type="hidden" name="total_amount" id="totalAmount">
            <button type="submit" class="next-btn">Next</button>
          </form>
        </div>
      </div>
    </aside>
  </main>

  <script>
    // Pass PHP services data to JavaScript
    const services = <?php echo json_encode($servicesData); ?>;

    const selected = {};
    const procedureList = document.querySelector('.procedure-list');
    const totalDisplay = document.querySelector('.price span');
    const selectedServicesInput = document.getElementById('selectedServices');
    const totalAmountInput = document.getElementById('totalAmount');
    const scheduleForm = document.getElementById('scheduleForm');

    function updateProcedureList() {
      procedureList.innerHTML = '';
      let total = 0;

      Object.entries(selected).forEach(([service, qty]) => {
        const price = services[service];
        const subtotal = price * qty;
        total += subtotal;

        const item = document.createElement('div');
        item.innerHTML = `
        <div class="procedure-row" style="display: flex; justify-content: space-between; align-items: center; padding: 6px 0;">
          <span style="flex: 1;">${service}</span>
          <div style="display: flex; align-items: center; gap: 20px;">
            <span>₱${subtotal.toLocaleString()}</span>
            <span class="qty-box">
              <button class="qty-btn" data-service="${service}" data-action="decrease">−</button>
              <span style="margin: 0 5px;">${qty}</span>
              <button class="qty-btn" data-service="${service}" data-action="increase">+</button>
            </span>
          </div>
        </div>
      `;
        procedureList.appendChild(item);
      });

      totalDisplay.textContent = `₱${total.toLocaleString()}`;
      selectedServicesInput.value = JSON.stringify(selected); // Update hidden input with services
      totalAmountInput.value = total; // Update hidden input with total amount
      attachQuantityButtonListeners();
    }

    function attachQuantityButtonListeners() {
      document.querySelectorAll('.qty-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
          e.preventDefault();
          const service = btn.dataset.service;
          const action = btn.dataset.action;

          if (action === 'increase') {
            selected[service]++;
          } else {
            selected[service]--;
            if (selected[service] <= 0) {
              delete selected[service];
            }
          }

          updateProcedureList();
        });
      });
    }

    document.querySelectorAll('.service-card').forEach(card => {
      card.addEventListener('click', () => {
        const service = card.dataset.service;

        if (selected[service]) {
          delete selected[service];
        } else {
          selected[service] = 1;
        }

        updateProcedureList();
      });
    });

    // Add validation for the Next button
    scheduleForm.addEventListener('submit', (e) => {
      if (Object.keys(selected).length === 0) {
        e.preventDefault(); // Prevent form submission
        alert('Please select at least one service before proceeding.');
      }
    });
  </script>

</body>

</html>