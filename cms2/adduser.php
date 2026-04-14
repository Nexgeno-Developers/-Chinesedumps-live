<?PHP
ob_start();
session_start();

	include ("../includes/config/classDbConnection.php");
	include("../includes/common/classes/validation_class.php");
	include("../includes/common/functions/func_uploadimg.php");
	include ("../includes/common/inc/sessionheader.php");
	include("../includes/common/classes/classUser.php");
	include("../functions.php");

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

$objDBcon    = new classDbConnection;
$objValid 	 = new Validate_fields;
$objUser	 = new classUser($objDBcon);

$strError			=	"";
$formNoticeClass	=	'user-form-notice is-error';
$backToListUrl		=	'manageuser.php';
$spram				=	array();
$spram[1]			=	"";
$spram[2]			=	"";
$spram[5]			=	"";
$spram[6]			=	"";
$spram[9]			=	"";
$spram[10]			=	"Active";
$spram[13]			=	"";

	if(isset($_POST['Submit']) && $_POST['Submit'] == "Submit")
	{
		$spram[1]	= trim($_POST['fname']);
		$spram[2]	= trim($_POST['surname']);
		$spram[5]	= trim($_POST['email']);
		$spram[9]	= isset($_POST['passuser']) ? $_POST['passuser'] : '';
		$spram[6]	= date("Y-m-d");
		$spram[10]	= isset($_POST['user_status']) ? trim($_POST['user_status']) : 'Active';
		$spram[13]	= admin_user_normalize_phone(isset($_POST['user_phone']) ? $_POST['user_phone'] : '');

		$objValid->add_num_field("Email", $spram[5], "email", "y");
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

		if(!isset($_POST['passuser']) || trim($_POST['passuser']) == '')
		{
			$strError	.=	"<b>The field Password is empty.</b>";
		}
		elseif($_POST['passuser'] != $_POST['user_cpassword'])
		{
			$strError	.=	"<b>The field Password and Confirm Password are not same.</b>";
		}

		if(!$objUser->checkUserDuplication($spram[5]))
		{
			$strError	.=	"<b><br />The Login is not available.</b>";
		}

		if(empty($strError))
		{
			$objUser->addUser($spram);
			$strError = "User has been added successfully";
			$formNoticeClass = 'user-form-notice is-success';
			$spram[1] = '';
			$spram[2] = '';
			$spram[5] = '';
			$spram[9] = '';
			$spram[10] = 'Active';
			$spram[13] = '';
		}
	}

include "html/adduser.html";
?>
