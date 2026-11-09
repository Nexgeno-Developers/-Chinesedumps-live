<?php 
require_once dirname(__FILE__) . '/includes/common/system_log.php'; require_once dirname(__FILE__) . '/includes/common/sanitize.php'; include("functions.php"); include ('includes/common/classes/classEmailvalidate.php');  

$emailValidator = new classEmailvalidate();


$base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];

if (isset($_POST['home_form'])) {
    
    function sanitizeInput($data) {
        return htmlspecialchars(strip_tags(trim(san_text($data))));
    }

    $errors = [];
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        $name = sanitizeInput($_POST['name']);
        $email = sanitizeInput($_POST['email']);
        $tel = sanitizeInput($_POST['tel']);
        $cCode = sanitizeInput($_POST['concode']);
        // $courses = sanitizeInput($_POST['courses']);
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
        // if (!preg_match('/^\d{10}$/', $tel)) {
        //     $errors[] = "Mobile number must be 10 digits.";
        // }
        if ($cCode == '91') {
            // India: exactly 10 digits
            if (!preg_match('/^\d{10}$/', $tel)) {
                $errors[] = "Mobile number must be exactly 10 digits for India.";
            }
        } else {
            // Other countries: digits only (any length)
            if (!preg_match('/^\d+$/', $tel)) {
                $errors[] = "Mobile number must contain digits only.";
            }
        }
        
    
        // Validate Course
        // if (empty($courses)) {
        //     $errors[] = "Please select a course.";
        // }
        
        // Course (validate only if field exists)
        $courses = '';
        if (isset($_POST['courses'])) {
            $courses = sanitizeInput($_POST['courses']);
        
            if ($courses === '') {
                $errors[] = "Please select a course.";
            }
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
        if (!empty($errors) && app_count($errors) > 0) {
            $_SESSION['errors'] = $errors;
        }

    } else {
        $_SESSION['errors'] = 'Somthing Went Wrong!';
    }
    
    if (!empty($errors) && app_count($errors) > 0) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'errors' => $errors]);
    }
    
    if (empty($errors) && app_count($errors) == 0) {
        
        // $to = "webdeveloper@nexgeno.in";
        $to = "sales@chinesedumps.com";
        $subject = 'Chinese Dumps ' . (isset($type) ? str_replace(array("\r", "\n", "%0a", "%0d"), '', $type) : '') . ' page enquiry';
        $txt = "<strong>Name:</strong> " . $name . "<br><br>";
        $txt .= "<strong>Email:</strong> " . $email . "<br><br>";
        $txt .= "<strong>Tel:</strong> +" . $cCode .'-'. $tel . "<br><br>";
        if (!empty($courses)) {
            $txt .= "<strong>Course:</strong> " . $courses . "<br><br>";
        }
        $txt .= "<strong>Message</strong> " . $message . "<br><br>";
        if (function_exists('utm_seo_email_html')) {
            $txt .= utm_seo_email_html();
        }
        if (function_exists('utm_seo_save')) {
            utm_seo_save('enquiry', '', $email);
        }
        $headers = "From: " . str_replace(array("\r", "\n", "%0a", "%0d"), '', $email) . "\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8";

        // $mail_send = false; //to test mail
        $mail_send = sendEmail($to, $subject, $txt, $headers);
        syslog_connect();
        syslog_write('Enquiries', 'ENQUIRY_FORM_SUBMITTED', ($mail_send ? '' : '(email to sales failed) ').syslog_who().' sent the "'.(isset($type) ? $type : '').'" page enquiry form (name: '.$name.', email: '.$email.', phone: +'.$cCode.'-'.$tel.(!empty($courses) ? ', course: '.$courses : '').').', array('status' => $mail_send ? 'success' : 'failed', 'entity_type' => 'enquiry', 'entity_label' => $email, 'details' => array('message' => $message, 'course' => !empty($courses) ? $courses : '')));
        
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
            $thankYouUrl = defined('BASE_URL') ? rtrim(BASE_URL, '/') . '/thankyou-page.php' : 'thankyou-page.php';
            header('Location: ' . $thankYouUrl);
            exit;
        } else {
            $_SESSION['errors'] = array('Failed to send email. Please try again later.');
            header('Location: ' . ($_SERVER['HTTP_REFERER'] ?: '/'));
            exit;
        }
        
    }
}

        
?>
