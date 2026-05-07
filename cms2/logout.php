<?PHP 
ob_start();
session_start();
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