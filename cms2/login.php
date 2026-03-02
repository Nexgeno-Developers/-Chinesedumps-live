<?PHP 
session_start();
//root path
$LinkPath="../";

include ("../includes/config/classDbConnection.php");
//title of the page
$strTitle	=	"Get Logged In to the admin panel";
//javascript file name if any
$javascriptFile	=	"";
$strError	=	"";//contains the error message


$strError = "";//contains the error message

if (isset($_SESSION['error']))
{
$strError = $_SESSION['error'];
$_SESSION['error'] = "";
}


//--------------------------------------------------------------------------------------

/*Html View Area*/

include "html/index.html";


//--------------------------------------------------------------------------------------
?>