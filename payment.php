<?php
include 'dbcon.php';
session_start();

// Check if session variables exist; otherwise, set them to defaults
$selected_time = $_SESSION['selected_time'] ?? null;
$selected_date = $_SESSION['selected_date'] ?? null;
$selected_services = $_SESSION['selected_services'] ?? [];
$selected_services_json = json_encode($selected_services, JSON_HEX_APOS);
$base_total = $_SESSION['total_amount'] ?? 1500;
$reservation_fee = 500;
$total_with_reservation = $base_total + $reservation_fee;
$user_name = $_SESSION['name'] ?? '';

$formatted_date = $selected_date ? date("F j, Y", strtotime($selected_date)) : 'Not selected';
$mysql_date = $selected_date ? date("Y-m-d", strtotime($selected_date)) : null;
$formatted_time = $selected_time ? date("g:i A", strtotime($selected_time)) . ' - ' . date("g:i A", strtotime("+1 hour", strtotime($selected_time))) : 'Not selected';

if (isset($_GET['goBack']) && $_GET['goBack'] === '1') {
    if (isset($_SESSION['selected_time']) && isset($_SESSION['selected_date'])) {
        $stmt = $con->prepare("UPDATE time_slots SET status = 'available' WHERE date = ? AND time_slot = ?");
        $stmt->bind_param("ss", $_SESSION['selected_date'], $_SESSION['selected_time']);
        if ($stmt->execute()) {
            unset($_SESSION['selected_time'], $_SESSION['selected_date']);
        }
    }
    header("Location: appoint.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Extract form values
    $payment_method = $_POST['method'] ?? '';
    $gcash_number = $_POST['payment_method'] ?? '';
    $gcash_name = $_POST['gcash-name'] ?? '';
    $gcash_ref = $_POST['gcash_ref'] ?? '';
    $date = $_POST['date'] ?? '';
    $time = $_POST['time'] ?? '';
    $amount = $_POST['amount'] ?? 0;
    $services = $_POST['services'] ?? '[]';

    if ($payment_method === 'gcash' && empty($gcash_ref)) {
        $error_message = "Please enter a valid GCash reference number.";
    } else {
        $_SESSION['gcash_payment'] = [
            'payment_method' => $payment_method,
            'gcash_name' => $gcash_name,
            'amount' => $amount,
            'services' => $services,
            'time' => $time,
            'gcash_ref' => $gcash_ref
        ];

        // Insert payment into the database
        $stmt = $con->prepare("INSERT INTO payments (payment_method, gcash_name, amount, services, time, gcash_qr, date) 
                              VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssissss", $payment_method, $gcash_name, $amount, $services, $time, $gcash_ref, $mysql_date);
        if ($stmt->execute()) {
            echo '<script>
                alert("Submitted! Please wait for a message once it is confirmed");
                window.location.href = "user.php";
            </script>';
        } else {
            $error_message = "Error inserting into the database.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Payment - Dr. Cheung Dental Clinic</title>
    <link rel="stylesheet" href="css/payment.css" />
    <style>
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        .modal.visible {
            display: flex;
        }
        .modal-content {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 500px;
            max-height: 85vh;
            overflow-y: auto;
            position: relative;
        }
        .close-button {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 24px;
            cursor: pointer;
            color: #888;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .qr-container {
            text-align: center;
            margin: 10px 0;
        }
        #summary-services {
            background-color: #f0f0f0;
            padding: 8px;
            border-radius: 4px;
            margin-bottom: 10px;
            word-break: break-word;
            font-size: 14px;
        }
        button[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            margin-top: 15px;
            font-size: 16px;
        }
        #summary-services ul {
            margin: 0;
            padding-left: 20px;
        }
    </style>
</head>
<body>
<div class="header">
    <h1><img src="img/logo.png" alt="Logo" class="clinic-logo" /> DR. CHEUNG DENTAL CLINIC</h1>
    <a href="payment.php?goBack=1" onclick="goBack()">&lt; Go Back to Appointments</a>
</div>

<div class="container">
    <h2>Payment Method</h2>
    <div class="payment-methods">
        <?php
        $methods = [
            "paypal" => "paypal.png",
            "visa" => "visa.png",
            "mastercard" => "mastercard.png",
            "maya" => "maya.jpg",
            "bdo" => "bdo.jpg",
            "gcash" => "gcash.jpg",
            "in person" => "tl.png"
        ];
        foreach ($methods as $name => $img) {
            $clickable = in_array($name, ['gcash', 'in person']);
            echo '<div class="payment-method' . ($clickable ? '' : ' unavailable') . '" data-method="' . $name . '">';
            echo "<img src='img/{$img}' alt='" . ucfirst($name) . "' />";
            echo "<span>" . ucfirst($name) . "</span>";
            if (!$clickable) echo "<div class='unavailable-label'>Unavailable</div>";
            echo '</div>';
        }
        ?>
    </div>

   <div id="payment-modal" class="modal hidden">
    <form action="payment.php" method="POST" id="gcash-form">
        <div class="modal-content">
            <span class="close-button" onclick="closeModal()">&times;</span>
            <h3 id="payment-title">Payment Details</h3>
            <div class="appointment-summary">
                <h4>Appointment Summary</h4>
                <p><strong>Date:</strong> <span id="summary-date"><?php echo $formatted_date; ?></span></p>
                <p><strong>Time:</strong> <span id="summary-time"><?php echo $formatted_time; ?></span></p>
                <h4>Selected Services</h4>
                <div id="summary-services"></div>
                <p><strong>Reservation Fee:</strong> ₱<span id="summary-fee"><?php echo number_format($reservation_fee, 2); ?></span></p>
                <p><strong>Total Amount:</strong> ₱<span id="summary-amount"><?php echo number_format($total_with_reservation, 2); ?></span></p>
            </div>

            <div id="payment-fields"></div>

            <div id="selected-payment-method" style="font-size: 16px; color: #333; margin-top: 20px;">
            </div>

            <div id="gcash-name-group" class="form-group" style="display: none;">
                <label for="gcash-name">Account Name</label>
                <input type="text" name="gcash-name" id="gcash-name" placeholder="Enter account name" value="<?php echo htmlspecialchars($user_name); ?>" />
            </div>

            <div class="form-group">
                <label>Scan this QR Code:</label>
                <div class="qr-container">
                    <img src="img/qr.jpg" alt="QR Code" style="width: 180px; height: auto; border: 1px solid #ccc; padding: 5px;" />
                </div>
            </div>

            <div class="form-group">
                <label for="gcash-ref">Reference No.</label>
                <input type="text" name="gcash_ref" id="gcash-ref" placeholder="Enter your reference number" required />
            </div>

            <input type="hidden" name="date" value="<?php echo htmlspecialchars($selected_date); ?>">
            <input type="hidden" name="time" value="<?php echo htmlspecialchars($selected_time); ?>">
            <input type="hidden" name="services" value='<?php echo $selected_services_json; ?>'>
            <input type="hidden" name="amount" value="<?php echo htmlspecialchars($reservation_fee); ?>">
            <input type="hidden" name="method" id="payment-method-hidden" value="">

            <button type="submit">Submit Payment</button>
        </div>
    </form>
</div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentMethods = document.querySelectorAll('.payment-method');
    const modal = document.getElementById('payment-modal');
    const paymentFields = document.getElementById('payment-fields');
    const gcashNameField = document.getElementById('gcash-name-group');
    const selectedMethodDisplay = document.getElementById('selected-payment-method');
    const servicesSummary = document.getElementById('summary-services');
    const userNameInput = document.getElementById('gcash-name');
    const amountField = document.querySelector('input[name="amount"]');
    const summaryAmount = document.getElementById('summary-amount');
    const summaryFee = document.getElementById('summary-fee');
    const methodHiddenField = document.getElementById('payment-method-hidden');

    let selectedServices = <?php echo $selected_services_json; ?>;

    // Generate services list to display in the modal
    let servicesList = '';
    if (selectedServices && Object.keys(selectedServices).length > 0) {
        servicesList = '<ul>';
        for (const [service, quantity] of Object.entries(selectedServices)) {
            servicesList += `<li>${service}: ${quantity}</li>`;
        }
        servicesList += '</ul>';
    } else {
        servicesList = 'No services selected';
    }
    servicesSummary.innerHTML = servicesList;

    // Event listener for payment method selection
    paymentMethods.forEach(function(method) {
        method.addEventListener('click', function() {
            let methodName = this.getAttribute('data-method');
            
            // Update the payment method display and hidden field
            selectedMethodDisplay.innerHTML = `<strong>${methodName.charAt(0).toUpperCase() + methodName.slice(1)} Payment</strong>`;
            methodHiddenField.value = methodName;

            // Clear any previous fields
            paymentFields.innerHTML = '';

            // Show fields based on the selected payment method
            if (methodName === 'gcash') {
                gcashNameField.style.display = 'block';
                userNameInput.value = "<?php echo htmlspecialchars($user_name); ?>";
                paymentFields.innerHTML = `
                    <input type="text" name="payment_method" value="gcash" readonly class="form-control">
                `;

                amountField.value = "<?php echo $total_with_reservation; ?>";
                summaryAmount.textContent = "<?php echo number_format($total_with_reservation, 2); ?>";
                summaryFee.textContent = "<?php echo number_format($reservation_fee, 2); ?>";

            } else if (methodName === 'in person') {
                gcashNameField.style.display = 'none';
                paymentFields.innerHTML = `
                    <input type="text" name="payment_method" value="in person" readonly class="form-control">
                `;

                amountField.value = "<?php echo $reservation_fee; ?>";
                summaryAmount.textContent = "<?php echo number_format($reservation_fee, 2); ?>";
                summaryFee.textContent = "<?php echo number_format($reservation_fee, 2); ?>";
            }
            
            // Show the modal
            modal.classList.remove('hidden');
            modal.classList.add('visible');
        });
    });

    // Close the modal when the close button (X) is clicked
    document.querySelector('.close-button').addEventListener('click', function() {
        modal.classList.add('hidden');
        modal.classList.remove('visible');
    });
});
</script>
</body>
</html>