<?php
session_start();

// Store service selections when coming from appoint.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['selected_services'])) {
        $_SESSION['selected_services'] = $_POST['selected_services'];
    }
    if (isset($_POST['total_amount'])) {
        $_SESSION['total_amount'] = $_POST['total_amount'];
    }
    if (isset($_POST['date']) && isset($_POST['time_slot'])) {
        $_SESSION['selected_date'] = $_POST['date'];
        $_SESSION['selected_time'] = $_POST['time_slot'];
    }
}

include 'dbcon.php';

// Handle AJAX reset request from browser back/unload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'resetOnBack') {
    $date = $_POST['date'];
    $time = $_POST['time_slot'];

    $query = "UPDATE time_slots SET status = 'available' WHERE date = ? AND time_slot = ? AND status = 'pending'";
    $stmt = $con->prepare($query);
    $stmt->bind_param("ss", $date, $time);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }
    exit;
}

// Main logic to save time and date if selected
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['date'], $_POST['time_slot'])) {
    $selected_date = $_POST['date'];
    $time_slot = $_POST['time_slot'];

    // Check if selected slot already exists
    $query = "SELECT * FROM time_slots WHERE date = ? AND time_slot = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("ss", $selected_date, $time_slot);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['status'] === 'available') {
            $update_query = "UPDATE time_slots SET status = 'pending' WHERE date = ? AND time_slot = ?";
            $stmt = $con->prepare($update_query);
            $stmt->bind_param("ss", $selected_date, $time_slot);
            $stmt->execute();
        }
    } else {
        // If not found, insert it as 'pending'
        $insert_query = "INSERT INTO time_slots (date, time_slot, status) VALUES (?, ?, 'pending')";
        $stmt = $con->prepare($insert_query);
        $stmt->bind_param("ss", $selected_date, $time_slot);
        $stmt->execute();
    }

    // Save to session and go to payment
    $_SESSION['selected_date'] = $selected_date;
    $_SESSION['selected_time'] = $time_slot;

    header("Location: payment.php");
    exit();
}

// Load slots for currently selected date
$selected_date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
$query = "SELECT * FROM time_slots WHERE date = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("s", $selected_date);
$stmt->execute();
$result = $stmt->get_result();

$slots = [];
while ($row = $result->fetch_assoc()) {
    $slots[] = $row;
}

$availableTimes = [];
for ($hour = 8; $hour <= 17; $hour++) {
    if ($hour == 12) continue;
    $time = sprintf('%02d:00:00', $hour);
    $availableTimes[] = $time;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Scheduling Calendar</title>
  <link rel="stylesheet" href="css/schedule.css">
</head>
<body>
  <div class="header">
    <h1>DR. CHEUNG DENTAL CLINIC</h1>
  </div>

  <div class="container">
    <div class="main-content">
      <div class="calendar-section">
        <div class="calendar-header">
          <button id="prevMonth">‹</button>
          <h2 id="monthYear"></h2>
          <button id="nextMonth">›</button>
        </div>
        <div class="calendar" id="calendarDays"></div>
      </div>

      <div class="slots-section">
        <h3>Available Times</h3>
        <?php foreach ($availableTimes as $time_slot):
          $status = 'available';
          foreach ($slots as $slot) {
            if ($slot['time_slot'] === $time_slot) {
              $status = $slot['status'];
              break;
            }
          }
          $start_time_am_pm = date("g:i A", strtotime($time_slot));
          $end_time_am_pm = date("g:i A", strtotime("+1 hour", strtotime($time_slot)));
        ?>
        <div class="slot <?php echo $status; ?>">
          <span data-time="<?php echo $time_slot; ?>"><?php echo "$start_time_am_pm - $end_time_am_pm"; ?></span>
          <span class="status <?php echo $status; ?>"><?php echo ucfirst($status); ?></span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="actions">
      <button class="back"><a href="appoint.php">Back</a></button>
      <button class="next" id="nextButton">Next</button>
    </div>
  </div>

<script>
let currentDate = new Date(2025, 4, 1);
const calendarDays = document.getElementById('calendarDays');
const monthYearText = document.getElementById('monthYear');
const daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

function renderCalendar(date) {
  calendarDays.innerHTML = '';
  monthYearText.textContent = date.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
  daysOfWeek.forEach(day => {
    const div = document.createElement('div');
    div.className = 'day';
    div.textContent = day;
    calendarDays.appendChild(div);
  });

  const year = date.getFullYear();
  const month = date.getMonth();
  const firstDay = new Date(year, month, 1).getDay();
  const daysInMonth = new Date(year, month + 1, 0).getDate();

  for (let i = 0; i < firstDay; i++) calendarDays.appendChild(document.createElement('div'));

  for (let day = 1; day <= daysInMonth; day++) {
    const dateEl = document.createElement('div');
    const current = new Date(year, month, day);
    const isWeekend = current.getDay() === 0 || current.getDay() === 6;

    dateEl.className = 'date';
    dateEl.textContent = day;
    if (!isWeekend) dateEl.classList.add('available');
    if (current.toDateString() === new Date().toDateString()) dateEl.classList.add('today');

    dateEl.addEventListener('click', function () {
      if (!isWeekend) {
        document.querySelectorAll('.calendar .date').forEach(d => d.classList.remove('selected'));
        this.classList.add('selected');
      }
    });

    calendarDays.appendChild(dateEl);
  }
}

renderCalendar(currentDate);

document.getElementById('prevMonth').addEventListener('click', () => {
  const minYear = 2025;
  const minMonth = 4;
  if (currentDate.getFullYear() > minYear || (currentDate.getFullYear() === minYear && currentDate.getMonth() > minMonth)) {
    currentDate.setMonth(currentDate.getMonth() - 1);
    renderCalendar(currentDate);
  }
});

document.getElementById('nextMonth').addEventListener('click', () => {
  currentDate.setMonth(currentDate.getMonth() + 1);
  renderCalendar(currentDate);
});

document.querySelectorAll('.slot').forEach(slot => {
  slot.addEventListener('click', function () {
    document.querySelectorAll('.slot').forEach(s => s.classList.remove('highlight'));
    this.classList.add('highlight');
  });
});

let selectedDateForUnload = null;
let selectedTimeForUnload = null;

document.getElementById('nextButton').addEventListener('click', function () {
  const selectedDateEl = document.querySelector('.calendar .selected');
  const selectedSlotEl = document.querySelector('.slot.highlight');

  if (selectedDateEl && selectedSlotEl) {
    const day = selectedDateEl.textContent;
    const month = currentDate.getMonth() + 1;
    const year = currentDate.getFullYear();
    const fullDate = `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

    const timeSlot = selectedSlotEl.querySelector('span').getAttribute('data-time');
    selectedDateForUnload = fullDate;
    selectedTimeForUnload = timeSlot;

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'schedule.php';
    form.innerHTML = `
      <input type="hidden" name="date" value="${fullDate}" />
      <input type="hidden" name="time_slot" value="${timeSlot}" />
    `;

    document.body.appendChild(form);
    form.submit();
  } else {
    alert('Please select a date and time slot.');
  }
});

window.addEventListener('beforeunload', function () {
  if (selectedDateForUnload && selectedTimeForUnload) {
    navigator.sendBeacon('schedule.php', new URLSearchParams({
      action: 'resetOnBack',
      date: selectedDateForUnload,
      time_slot: selectedTimeForUnload
    }));
  }
});
</script>
</body>
</html>
