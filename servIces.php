<?php
header('Content-Type: application/json');

$host = "localhost";
$user = "root";
$password = "";
$dbname = "delubyo";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

$sql = "SELECT * FROM services";
$result = $conn->query($sql);

$services = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $services[] = $row;
    }
}

echo json_encode($services);

$conn->close();
?>
