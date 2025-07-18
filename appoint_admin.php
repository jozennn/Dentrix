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
                <li><a href="admin.php">Go back to Homepage</a></li>
            </ul>
        </nav>
    </header>

    <section class="manage-services">
        <h2>Manage Services</h2>
        <form action="add_service.php" method="POST" enctype="multipart/form-data" onsubmit="return confirmAddService()">
            <input type="text" name="name" placeholder="Service Name" required>
            <input type="number" name="price" placeholder="Price (₱)" step="0.01" required>
            <input type="file" name="image" accept="image/*" required>
            <button type="submit">Add Service</button>
        </form>
    </section>

    <main class="container">
        <section class="services-section">
            <h2>Choose a Service</h2>
            <div class="service-grid">
                <?php
                include 'dbcon.php';

                $sql = "SELECT * FROM services";
                $result = mysqli_query($con, $sql);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $image = !empty($row['image']) && file_exists('uploads/' . $row['image'])
                            ? 'uploads/' . htmlspecialchars($row['image'])
                            : 'img/default.png';
                        echo '<div class="service-card" data-id="' . $row['id'] . '">';
                        echo '<img src="' . $image . '" alt="' . htmlspecialchars($row['name']) . '">';
                        echo '<p>' . htmlspecialchars($row['name']) . '</p>';
                        echo '<p>₱' . number_format($row['price'], 2) . '</p>';
                        echo '<button class="delete-btn" onclick="deleteService(' . $row['id'] . ')">🗑️</button>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>No services available.</p>';
                }
                ?>
            </div>
        </section>
    </main>

    <script>
        function deleteService(serviceId) {
            if (!confirm("Are you sure you want to delete this service?")) return;

            const serviceCard = document.querySelector(`.service-card[data-id="${serviceId}"]`);
            if (serviceCard) {
                serviceCard.style.opacity = "0.5"; // Dim the card to indicate loading
            }

            fetch('delete_service.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `id=${encodeURIComponent(serviceId)}`
                })
                .then(res => res.text())
                .then(response => {
                    if (response === 'success') {
                        if (serviceCard) {
                            serviceCard.remove(); // Remove the card from the DOM
                        }
                    } else {
                        alert("Error deleting service.");
                        if (serviceCard) {
                            serviceCard.style.opacity = "1"; // Restore opacity if deletion fails
                        }
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("An error occurred while deleting the service.");
                    if (serviceCard) {
                        serviceCard.style.opacity = "1"; // Restore opacity if an error occurs
                    }
                });
        }

        function confirmAddService() {
            return confirm("Are you sure you want to add this service?");
        }
    </script>

</body>

</html>