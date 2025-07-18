<?php
include('dbcon.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $inquiry_id = $_POST['inquiry_id'];
    $email = $_POST['email'];
    $reply_message = $_POST['reply_message'];

    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'akosinicolas1@gmail.com';
        $mail->Password   = 'mdipvwgwjbcinszl'; // Gmail app password
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // Sender & recipient
        $mail->setFrom('akosinicolas1@gmail.com', 'Dentrix Dental Clinic');
        $mail->addAddress($email);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'Reply to Your Inquiry - Dentrix Dental Clinic';
        $mail->Body    = "
            <html>
            <body>
                <h2>Response to your inquiry</h2>
                <p>$reply_message</p>
                <br>
                <p>Thank you,<br>Dentrix Dental Clinic Team</p>
            </body>
            </html>
        ";

        $mail->send();

        // ✅ Update the status to 'replied' in the database
        $stmt = $con->prepare("UPDATE inquiries SET status = 'replied' WHERE inquiry_id = ?");
        $stmt->bind_param("i", $inquiry_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "<script>alert('Reply sent and status updated successfully!'); window.location.href='inquiries.php';</script>";
        } else {
            echo "<script>alert('Email sent but failed to update status.'); window.location.href='inquiries.php';</script>";
        }

    } catch (Exception $e) {
        echo "<script>alert('Message could not be sent. Mailer Error: {$mail->ErrorInfo}'); window.location.href='inquiries.php';</script>";
    }
}
?>
