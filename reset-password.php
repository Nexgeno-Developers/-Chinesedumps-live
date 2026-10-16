<?php
require_once __DIR__ . '/includes/config/load_secrets.php';
require_once __DIR__ . '/includes/common/auth.php';
ob_start();
session_start();
include('includes/config/classDbConnection.php');
$objDBcon = new classDbConnection; // Required before token lookup and password update.
include('functions.php');
$getPage = array('Reset Password', 'Reset Password', '', '', 'Reset Password', 'Reset Password');
$firstlink = ' Reset Password';

$selector = isset($_GET['selector']) ? preg_replace('/[^a-f0-9]/i', '', $_GET['selector']) : '';
$token = isset($_GET['token']) ? preg_replace('/[^a-f0-9]/i', '', $_GET['token']) : '';
$message = '';
$validLink = strlen($selector) === 24 && strlen($token) === 64;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selector = isset($_POST['selector']) ? preg_replace('/[^a-f0-9]/i', '', $_POST['selector']) : '';
    $token = isset($_POST['token']) ? preg_replace('/[^a-f0-9]/i', '', $_POST['token']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
    $validLink = strlen($selector) === 24 && strlen($token) === 64;

    if (!auth_csrf_is_valid(isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '')) {
        $message = 'Your session has expired. Please request a new password-reset link.';
        $validLink = false;
    } elseif ($password !== $confirm) {
        $message = 'The passwords do not match.';
    } elseif (!auth_password_is_valid($password, $passwordError)) {
        $message = $passwordError;
    } elseif ($validLink) {
        $selectorSql = mysql_real_escape_string($selector);
        $result = mysql_query("SELECT id,user_id,token_hash FROM password_reset_tokens WHERE selector='".$selectorSql."' AND used_at IS NULL AND expires_at > NOW() LIMIT 1");
        $row = $result ? mysql_fetch_assoc($result) : false;
        if (!$row || !auth_timing_safe_equals($row['token_hash'], hash('sha256', $token))) {
            $message = 'This password-reset link is invalid or has expired. Please request a new one.';
            $validLink = false;
        } else {
            $hash = mysql_real_escape_string(auth_password_hash($password));
            $userId = (int)$row['user_id'];
            mysql_query("UPDATE tbl_user SET user_password='".$hash."' WHERE user_id='".$userId."'");
            mysql_query("UPDATE password_reset_tokens SET used_at=NOW() WHERE user_id='".$userId."'");
            session_regenerate_id(true);
            $message = 'Your password has been reset. You can now sign in.';
            $validLink = false;
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
.password-card label { display:block; margin:18px 0 8px; font-weight:700; color:#233f55; }
.password-card .input-field { width:100%; height:46px; padding:10px 14px; border:1px solid #b8c9d7 !important; border-radius:8px; box-sizing:border-box; }
.password-card .password-action { margin-top:24px; min-width:160px; border:0; border-radius:24px; padding:12px 24px; }
.password-card .form-message { margin:0 0 22px; padding:12px 14px; border-radius:8px; background:#eef6fb; color:#244c68; text-align:center; }
.password-card .form-message.error { background:#fff2f2; color:#9b2626; }
.password-card .form-help { margin:14px 0 0; color:#617485; font-size:13px; }
.password-card .request-link { color:#287fb6; font-weight:700; }
@media (max-width:600px) { .password-page { padding:30px 14px 44px; } .password-card { padding:27px 22px 30px; } }
</style>
<div class="password-page"><div class="password-card">
<h1>Reset your password</h1>
<?php if ($validLink) { ?><p class="intro">Choose a new password for your account.</p><?php } ?>
<?php if ($message !== '') { ?><div class="form-message <?php echo $validLink ? '' : 'error'; ?>" role="status"><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></div><?php } ?>
<?php if ($validLink) { ?>
<form method="post" action="reset-password.php?selector=<?php echo urlencode($selector); ?>&amp;token=<?php echo urlencode($token); ?>">
<input type="hidden" name="selector" value="<?php echo htmlspecialchars($selector, ENT_QUOTES, 'UTF-8'); ?>">
<input type="hidden" name="token" value="<?php echo htmlspecialchars($token, ENT_QUOTES, 'UTF-8'); ?>">
<input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(auth_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
<label for="password">New password</label>
<input class="input-field" id="password" type="password" name="password" minlength="12" autocomplete="new-password" required>
<label for="confirm_password">Confirm new password</label>
<input class="input-field" id="confirm_password" type="password" name="confirm_password" minlength="12" autocomplete="new-password" required>
<p class="form-help">Use at least 12 characters. This link expires after 30 minutes and works only once.</p>
<button class="orange-button password-action" type="submit">Reset password</button>
</form>
<?php } elseif ($message === '') { ?>
<p class="intro">This password-reset link is invalid or has expired.</p>
<p style="text-align:center;"><a class="request-link" href="forgotpass.html">Request a new reset link</a></p>
<?php } else { ?>
<p style="text-align:center;"><a class="request-link" href="forgotpass.html">Request a new reset link</a></p>
<?php } ?>
</div></div>
<?php include('includes/footer.php'); ?>