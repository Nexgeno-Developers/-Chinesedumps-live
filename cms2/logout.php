<?PHP 
ob_start();
session_start();
if (!empty($_SESSION["adminId"])) {
	require_once dirname(dirname(__FILE__)) . '/includes/common/system_log.php';
	include_once dirname(dirname(__FILE__)) . '/includes/config/classDbConnection.php';
	$syslogDb = new classDbConnection(); // opens the DB link the logger needs
	syslog_write('Authentication', 'ADMIN_LOGOUT', 'Admin user "' . (isset($_SESSION["strAdmin"]) ? $_SESSION["strAdmin"] : '') . '" logged out of the admin panel.', array(
		'entity_type' => 'admin user', 'entity_id' => (int)$_SESSION["adminId"], 'entity_label' => isset($_SESSION["strAdmin"]) ? $_SESSION["strAdmin"] : ''
	));
}
session_destroy();
unset($_SESSION["strAdmin"]);
header("location:login.php");

//root path
/*
$LinkPath="../";
//title of the page
$strTitle	=	"Get Logged In to the admin panel";
//javascript file name if any
$javascriptFile	=	"";

//include session header
require_once($LinkPath."includes/common/inc/sessionheader.php");

if (isset($_SESSION["strAdmin"])) 
	{	session_destroy();
		unset($_SESSION["strAdmin"]);
	}
	
	header("location:index.php");
*/
?>