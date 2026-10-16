<?php
require_once __DIR__ . '/includes/config/load_secrets.php';
require_once __DIR__ . '/includes/common/auth.php';
ob_start();
session_start();
include('includes/config/classDbConnection.php');
include('includes/common/classes/classmain.php');
include('includes/common/classes/classProductMain.php');
include('includes/common/classes/classcart.php');
include('functions.php');
include('includes/shoppingcart.php');

$objDBcon = new classDbConnection;
$objMain = new classMain($objDBcon);
$objPro = new classProduct($objDBcon);
$objCart = new classCart($objDBcon);
$getPage = $objMain->getContent(6);
$quer = $objDBcon->Sql_Query_Exec('*', 'website', '');
$exec = mysql_fetch_array($quer);
$copyright = $exec['copyright'];
$from_email = $exec['from_email'];
$firstlink = ' '.$getPage[1];
$Errmg = '';

if (isset($_POST['Send'])) {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $Errmg = '<span>If an account exists for that email address, we have sent a password-reset link.</span>';

    if (!auth_csrf_is_valid(isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '')) {
        $Errmg = '<span>Your session has expired. Please refresh the page and try again.</span>';
    } elseif (empty($_POST['g-recaptcha-response'])) {
        $Errmg = '<span>Please complete the reCAPTCHA.</span>';
    } elseif (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailSql = mysql_real_escape_string($email);
        $rs = mysql_query("SELECT user_id,user_fname,user_lname,user_email FROM tbl_user WHERE user_email='".$emailSql."' AND user_status='Active' LIMIT 1");
        $user = $rs ? mysql_fetch_assoc($rs) : false;

        if ($user) {
            /* Created once, then stores only a SHA-256 digest of each random token. */
            mysql_query("CREATE TABLE IF NOT EXISTS password_reset_tokens (id INT NOT NULL AUTO_INCREMENT, user_id INT NOT NULL, selector CHAR(24) NOT NULL, token_hash CHAR(64) NOT NULL, expires_at DATETIME NOT NULL, used_at DATETIME NULL, created_at DATETIME NOT NULL, PRIMARY KEY (id), UNIQUE KEY selector (selector), KEY user_id (user_id)) ENGINE=InnoDB DEFAULT CHARSET=utf8");
            mysql_query("DELETE FROM password_reset_tokens WHERE user_id='".(int)$user['user_id']."'");
            try {
                $selector = bin2hex(auth_random_bytes(12));
                $token = bin2hex(auth_random_bytes(32));
                $tokenHash = hash('sha256', $token);
                $inserted = mysql_query("INSERT INTO password_reset_tokens (user_id,selector,token_hash,expires_at,created_at) VALUES ('".(int)$user['user_id']."','".$selector."','".$tokenHash."',DATE_ADD(NOW(), INTERVAL 30 MINUTE),NOW())");
                if ($inserted) {
                    $url = base_url('reset-password.php?selector='.urlencode($selector).'&token='.urlencode($token));
                    passwordResetEmail($user, $url, $from_email);
                }
            } catch (Exception $e) {
                error_log('Password reset request failed: '.$e->getMessage());
            }
        }
    }
}
?>
<?php include('includes/header.php'); ?>
<div class="content-box"><div class="exam-group-box"><div class="max-width">
<h4 class="center-heading center-heading02"><span><?php echo $getPage[1]; ?></span></h4>
<div class="black-heading"><?php echo $getPage[2]; ?></div>
<form id="form1" name="form1" method="post" action="">
<input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(auth_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
<table width="544" border="0">
<tr><td colspan="2" align="center" class="redbold forgot_message"><?php echo $Errmg; ?></td></tr>
<tr><td width="249" height="33" align="right" class="text" style="padding-right:10px;">Email :</td><td width="285"><input type="email" class="input-field" name="email" id="email" placeholder="Email..." autocomplete="email" required></td></tr>
<tr><td></td><td style="padding-top:15px;"><div class="g-recaptcha" data-sitekey="<?php echo htmlspecialchars((string)secret('RECAPTCHA_SITE_KEY_DEFAULT', ''), ENT_QUOTES, 'UTF-8'); ?>"></div></td></tr>
<tr><td height="33"></td><td><button type="submit" name="Send" value="Send" class="orange-button">Send reset link</button></td></tr>
</table>
</form>
</div></div></div>
<?php include('includes/footer.php'); ?>