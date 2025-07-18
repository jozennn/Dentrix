<?php session_start(); ?>
<?php 
    include('dbcon.php');
    if(isset($_POST["verify"])){
        $otp= $_SESSION['otp'];
        $email = $_SESSION['email'];
        $otp_code = $_POST['otp_code'];

        if($otp != $otp_code){
            ?>
           <script>
               alert("Invalid OTP code");
           </script>
           <?php
        }
        else{
            mysqli_query($con, "UPDATE test SET verify_status = 1 WHERE email = '$email'");
            ?>
             <script>
                 alert("Verfiy account done, you may sign in now");
                   window.location.replace("login.php");
             </script>
             <?php
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>OTP Verification</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
  <div class="bg-white p-8 rounded-2xl shadow-md w-full max-w-md">
    <h2 class="text-2xl font-bold mb-4 text-center">Verify OTP</h2>
    <?php if (isset($_SESSION['error'])): ?>
      <p class="text-red-500 text-center mb-4"><?= $_SESSION['error']; unset($_SESSION['error']); ?></p>
    <?php endif; ?>
    <form method="POST" action="verify_otp.php" class="flex flex-col space-y-4">
      <input 
        type="text" 
        name="otp" 
        maxlength="6"
        pattern="\d{6}" 
        required
        placeholder="Enter 6-digit OTP"
        class="w-full px-4 py-3 border rounded-xl text-center text-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
      >
      <button 
        type="submit" 
        class="bg-blue-600 text-white py-2 rounded-xl hover:bg-blue-700 transition"
      >
        Verify OTP
      </button>
    </form>
    <p class="text-sm text-gray-600 text-center mt-4">
      Didn’t receive the code? <a href="send_otp.php" class="text-blue-600 hover:underline">Resend</a>
    </p>
  </div>
</body>
</html>
