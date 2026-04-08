<?PHP
ob_start();
session_start();

	include ("../includes/config/classDbConnection.php");
	include("../includes/common/classes/validation_class.php");
	include("../includes/common/functions/func_uploadimg.php");
	include ("../includes/common/inc/sessionheader.php");
	include("../includes/common/classes/classUser.php");

	$objDBcon    = new classDbConnection;
	$objValid 	 = new Validate_fields;
	$objUser	 = new classUser($objDBcon);
	$strError	 = "";
	$formNoticeClass = 'user-form-notice is-error';

include_once 'functions.php';
$ccAdminData = get_session_data();

if(!function_exists('admin_user_normalize_phone'))
{
	function admin_user_normalize_phone($value)
	{
		$value = trim($value);
		return preg_replace('/\s+/', ' ', $value);
	}
}

$spram = array();
$spram[0] = "";
$spram[1] = "";
$spram[2] = "";
$spram[5] = "";
$spram[9] = "";
$spram[10] = "Active";
$spram[13] = "";

$nid = (isset($_GET['nid']) && ctype_digit((string)$_GET['nid'])) ? $_GET['nid'] : '';
$returnParams = array();
$returnKeys = array('criteria', 'textSearch', 'txt_StartDate', 'txt_StartDate2', 'currentPage', 'pgIndex');
foreach($returnKeys as $returnKey)
{
	if(isset($_GET[$returnKey]) && $_GET[$returnKey] !== '')
	{
		$returnParams[$returnKey] = $_GET[$returnKey];
	}
}
$backToListUrl = 'manageuser.php';
if(!empty($returnParams))
{
	$backToListUrl .= '?'.http_build_query($returnParams);
}

$existingUser = false;
if($nid != '')
{
	$resultSet = $objUser->getUser($nid);
	$existingUser = mysql_fetch_array($resultSet);
	if($existingUser)
	{
		$spram[0] = $nid;
		$spram[1] = $existingUser['user_fname'];
		$spram[2] = $existingUser['user_lname'];
		$spram[5] = $existingUser['user_email'];
		$spram[9] = $existingUser['user_password'];
		$spram[10] = $existingUser['user_status'];
		$spram[13] = $existingUser['user_phone'];
	}
	else
	{
		$strError = "The selected user could not be found.";
	}
}
else
{
	$strError = "Invalid user selected.";
}

if(isset($_POST['submit']) && $_POST['submit'] == "submit" && $existingUser)
{
	$spram[0]	= $nid;
	$spram[1]    = trim($_POST['fname']);
	$spram[2]	= trim($_POST['surname']);
	$spram[5]	= $existingUser['user_email'];
	$spram[9]	= isset($_POST['passuser']) ? $_POST['passuser'] : '';
	$spram[10]	= isset($_POST['user_status']) ? trim($_POST['user_status']) : 'Active';
	$spram[13]	= admin_user_normalize_phone(isset($_POST['user_phone']) ? $_POST['user_phone'] : '');

	$objValid->add_text_field("Last Name", $spram[2], "text", "y");
	$objValid->add_text_field("First Name", $spram[1], "text", "y");

	if (!$objValid->validation())
	{
		$strError = $objValid->create_msg();
	}

	if($spram[13] == '')
	{
		$strError	.=	"<b>The field Phone is empty.</b>";
	}
	elseif(strlen($spram[13]) > 255)
	{
		$strError	.=	"<b>The field Phone must be 255 characters or fewer.</b>";
	}
	elseif(!preg_match('/^[0-9+\-\(\)\s\.]+$/', $spram[13]))
	{
		$strError	.=	"<b>The field Phone contains invalid characters.</b>";
	}

	if($_POST['passuser'] == "" && $_POST['user_cpassword'] == "")
	{
		$spram[9] = $_POST['user_hpassword'];
	}
	elseif($_POST['passuser'] != $_POST['user_cpassword'])
	{
		$strError	.=	"<b>The field Password and Confirm Password are not same.</b>";
	}

	if(empty($strError))
	{
		$objUser->UpdateUserAdmin($spram);
		$strError = "User has been Updated successfully";
		$formNoticeClass = 'user-form-notice is-success';

		$resultSet = $objUser->getUser($nid);
		$existingUser = mysql_fetch_array($resultSet);
		if($existingUser)
		{
			$spram[1] = $existingUser['user_fname'];
			$spram[2] = $existingUser['user_lname'];
			$spram[5] = $existingUser['user_email'];
			$spram[9] = $existingUser['user_password'];
			$spram[10] = $existingUser['user_status'];
			$spram[13] = $existingUser['user_phone'];
		}
	}
}

include "html/edituser.html";
?>
