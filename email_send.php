  <?php
            if (!defined('BASE_URL')) {
               include_once __DIR__ . '/includes/config/config.php';
            }
            if(isset($_POST['home_form'])){
               $to = "mehtab.makent@gmail.com";
               $subject = "Chinese Dumps home page enquiry";
               $txt = "Name: ".$_POST['name']."\r\n";
               $txt .= "Email: ".$_POST['email']."\r\n";
               $txt .= "Tel: ".$_POST['tel']."\r\n";
               $headers = "From: ".$_POST['email'];
            
               $mail_send = sendEmail($to,$subject,$txt,$headers);
            //   $mail_send=mail($to,$subject,$txt,$headers);
               if($mail_send){
                  echo "<script>alert('Message Send successfully');</script>";
                  header('Refresh: 1;url=' . BASE_URL . 'thanks-you.php'); 
               }
            }
            ?>
