<?php
require_once __DIR__ . '/includes/config/config.php';

$siteKey = secret('RECAPTCHA_SITE_KEY_SHIELDSQUARE', ''); // Enter your sitekey obtained from reCaptcha Website
$secret  = secret('RECAPTCHA_SECRET_SHIELDSQUARE', ''); // Enter your secret key obtained from reCaptcha Website
?>
<?php 
    if(isset($_POST['g-recaptcha-response'])) {
        $response = getCurlData("https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$_POST['g-recaptcha-response']);
    $response = json_decode($response, true);
    if($response["success"] === true) {
        $redirPage = $_COOKIE["currentPagename"];
        setcookie(md5("captchaResponse"),md5("1".$redirPage.$_SERVER[$shieldsquare_config_data_captcha->_ipaddress].$shieldsquare_config_data_captcha->_sid),time()+60*60,"/");
        header("Location:".$redirPage);
        exit();
    }  else  {
        header("Location:".$_SERVER["PHP_SELF"]);
        exit();
    }
}
?>
<!-- The GET Request Captcha PAGE (Styles and Includes) -->
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title>ShieldSquare reCAPTCHA Page</title>
        <link rel="shortcut icon" href="https://cdn.perfdrive.com/icons/favicon.png" type="image/x-icon"/>
        <style type="text/css">
            body {
                margin: 1em 5em 0 5em;
                font-family: sans-serif;
            }
            fieldset {
                display: inline;
                padding: 1em;
            }
        </style>
    </head>
    <body align="center">
        <a href="<?php echo BASE_URL; ?>" target="_blank"><img src="https://cdn.perfdrive.com/icons/shieldsquarelogo.png">
        </a>
            <hr>
        <h1>Suspicious Activity Detected</h1>
       


    <!-- The Form Display -- You can Customize this form for Yourself! --> 
        
    <p>Complete the reCAPTCHA then submit the form.</p>
        <fieldset>
            <legend>Solve Captcha</legend>
    <form action="<?php $_SERVER['PHP_SELF']; ?>"  method=POST>
            <div class="g-recaptcha" data-sitekey="<?php echo $siteKey; ?>"></div>
            <script type="text/javascript"
                    src="https://www.google.com/recaptcha/api.js?hl=<?php echo $lang; ?>">
            </script><br>
            <input type=Submit Value="GO" />
        </fieldset>
    </form>
    <br><br><br><br> <hr> <br>
    <footer>
    <small>� Copyright 2016 chinesedumps.com, All Rights Reserved. </small>
        </footer>
        
</body>
</html>

<?php
function getCurlData($url)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_TIMEOUT, 10);
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16");
    $curlData = curl_exec($curl);
    curl_close($curl);
    return $curlData;
}
?>
