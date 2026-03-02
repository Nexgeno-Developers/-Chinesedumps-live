<? include("functions.php"); include ('includes/common/classes/classEmailvalidate.php');  

$emailValidator = new classEmailvalidate();
?>


<?php

$base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];

if (isset($_POST['contactus_form'])) {
    var_dump(1);
exit();
    function sanitizeInput($data) {
        return htmlspecialchars(strip_tags(trim($data)));
    }

    $errors = [];
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        $name = sanitizeInput($_POST['name']);
        $email = sanitizeInput($_POST['email']);
        $tel = sanitizeInput($_POST['tel']);
        $cCode = sanitizeInput($_POST['concode']);
        $message = sanitizeInput($_POST['message']);
        
        // Validate Name
        if (empty($name) || preg_match('/[^a-zA-Z0-9\s]/', $name)) {
            $errors[] = "Invalid name. No special characters allowed.";
        }
        
        // Validate Email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        }
    
        // Validate Mobile Number
        if (!preg_match('/^\d{10}$/', $tel)) {
            $errors[] = "Mobile number must be 10 digits.";
        }
        
        // Validate Message
        if (!empty($message) && preg_match('/[^a-zA-Z0-9\s]/', $message)) {
            $errors[] = "Message cannot contain special characters.";
        }
        
        if (!isset($_POST['g-recaptcha-response']) || empty($_POST['g-recaptcha-response'])) {
            $errors[] = "Please fill reCAPTCHA.";
        }
        
        $response = $emailValidator->email_validate_api($email);
        
        $data = json_decode($response, true);

        if ($data && isset($data['result'])) {
            if($data['result'] == 'undeliverable'){
                $errors[] = "Email Address is not valid please try again";
            } 
        } else {
            $errors[] = "Somthing Went Wrong";
        }

        // Display Errors or Process Form
        if (!empty($errors) && count($errors) > 0) {
            $_SESSION['errors'] = $errors;
        }

    } else {
        $_SESSION['errors'] = 'Somthing Went Wrong!';
    }
    
    if (!empty($errors) && count($errors) > 0) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'errors' => $errors]);
    }
    
    if (empty($errors) && count($errors) == 0) {
        
        // $to = "umair.makent@gmail.com";
        $to = "sales@chinesedumps.com";
        $subject = 'Chinese Dumps ' . $type . ' page enquiry';
        $txt = "<strong>Name:</strong> " . $name . "<br><br>";
        $txt .= "<strong>Email:</strong> " . $email . "<br><br>";
        $txt .= "<strong>Tel:</strong> +" . $cCode .'-'. $tel . "<br><br>";
        $txt .= "<strong>Message</strong> " . $message . "<br><br>";
        $headers = "From: " . $email . "\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8";

        // $mail_send = false; //to test mail
        $mail_send = sendEmail($to, $subject, $txt, $headers);
        
        // AJAX? detect by header
        $xhr = '';
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            $xhr = strtolower($_SERVER['HTTP_X_REQUESTED_WITH']);
        }
        if ($xhr === 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode([
              'success' => true
            ]);
            exit;
        }
        
        // normal POST → redirect
        if ($mail_send) {
            header('Location: ' . $base_url . '/thankyou-page.php');
            exit;
        } else {
            $_SESSION['errors'] = array('Failed to send email. Please try again later.');
            header('Location: ' . ($_SERVER['HTTP_REFERER'] ?: '/'));
            exit;
        }
        
    }
}

        
?>
