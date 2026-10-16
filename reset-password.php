<?php
require_once __DIR__ . '/includes/config/load_secrets.php';
require_once __DIR__ . '/includes/common/auth.php';
ob_start();
session_start();
include('includes/config/classDbConnection.php');
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
<div class="content-box"><div class="exam-group-box"><div class="max-width">
    <h4 class="center-heading center-heading02"><span>Reset Password</span></h4>
    <?php if ($message !== '') { ?><p class="forgot_message"><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p><?php } ?>
    <?php if ($validLink) { ?>
    <form method="post" action="reset-password.php?selector=<?php echo urlencode($selector); ?>&amp;token=<?php echo urlencode($token); ?>">
        <input type="hidden" name="selector" value="<?php echo htmlspecialchars($selector, ENT_QUOTES, 'UTF-8'); ?>">
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token, ENT_QUOTES, 'UTF-8'); ?>">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(auth_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
        <p><label>New password<br><input class="input-field" type="password" name="password" minlength="12" autocomplete="new-password" required></label></p>
        <p><label>Confirm new password<br><input class="input-field" type="password" name="confirm_password" minlength="12" autocomplete="new-password" required></label></p>
        <p><small>Use at least 12 characters.</small></p>
        <p><button class="orange-button" type="submit">Reset password</button></p>
    </form>
    <?php } elseif ($message === '') { ?>
    <p>This password-reset link is invalid or has expired. <a href="forgotpass.html">Request a new link</a>.</p>
    <?php } ?>
</div></div></div>
<?php include('includes/footer.php'); ?>
