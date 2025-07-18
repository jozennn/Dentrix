<?php
    session_start();
    include('dbcon.php');
    if(isset($_POST["verify"])){
        $otp = $_SESSION['otp'];
        $email = $_SESSION['mail'];
        $otp_code = $_POST['otp'];
        
        if($otp != $otp_code){
            ?>
           <script>
               alert("Invalid OTP code");
               window.location.replace("verify-email.php");
           </script>
           <?php
        }
        else{
            mysqli_query($con, "UPDATE test SET verify_status = 'Active' WHERE email = '$email'");
            ?>
             <script>
                 alert("Verfiy account done, you may sign in now");
                   window.location.replace("login.php");
             </script>
             <?php
        }
    }

?>