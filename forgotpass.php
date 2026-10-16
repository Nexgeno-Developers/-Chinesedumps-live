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
                    $publicUrl = !empty($exec['website_url']) ? rtrim($exec['website_url'], '/') . '/' : base_url();
                    $url = $publicUrl.'reset-password.html?selector='.urlencode($selector).'&token='.urlencode($token);
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
<style>
.password-page { background:#f7f9fb; padding:52px 20px 68px; min-height:430px; }
.password-card { max-width:620px; margin:0 auto; padding:34px 42px 38px; background:#fff; border:1px solid #e1e8ef; border-radius:16px; box-shadow:0 12px 28px rgba(24,67,96,.10); }
.password-card h1 { margin:0 0 10px; text-align:center; font-size:25px; color:#1f3c55; }
.password-card .intro { margin:0 0 28px; text-align:center; color:#4b5f70; line-height:1.6; }
.password-card label { display:block; margin:0 0 8px; font-weight:700; color:#233f55; }
.password-card .input-field { width:100%; height:46px; padding:10px 14px; border:1px solid #b8c9d7 !important; border-radius:8px; box-sizing:border-box; }
.password-card .g-recaptcha { margin:20px 0; }
.password-card .password-action { min-width:160px; border:0; border-radius:24px; padding:12px 24px; }
.password-card .form-message { margin:0 0 22px; padding:12px 14px; border-radius:8px; background:#eef6fb; color:#244c68; text-align:center; }
@media (max-width:600px) { .password-page { padding:30px 14px 44px; } .password-card { padding:27px 22px 30px; } }
</style>
<div class="password-page"><div class="password-card">
<h1>Forgot your password?</h1>
<p class="intro">Enter the email address associated with your account. We’ll send you a secure link to choose a new password.</p>
<?php if ($Errmg !== '') { ?><div class="form-message" role="status"><?php echo $Errmg; ?></div><?php } ?>
<form id="form1" name="form1" method="post" action="">
<input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(auth_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
<label for="email">Email address</label>
<input type="email" class="input-field" name="email" id="email" placeholder="you@example.com" autocomplete="email" required>
<div class="g-recaptcha" data-sitekey="<?php echo htmlspecialchars((string)secret('RECAPTCHA_SITE_KEY_DEFAULT', ''), ENT_QUOTES, 'UTF-8'); ?>"></div>
<button type="submit" name="Send" value="Send" class="orange-button password-action">Send reset link</button>
</form>
</div></div>
<?php include('includes/footer.php'); ?>