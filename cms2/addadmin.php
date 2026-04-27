<?PHP

ob_start();
session_start();

	include_once ("../includes/config/classDbConnection.php");
	include("../includes/common/classes/validation_class.php");
	include("../includes/common/functions/func_uploadimg.php");
	include ("../includes/common/inc/sessionheader.php");
	include("../includes/common/classes/classAdmin.php");

	$objDBcon    = new classDbConnection;
	$objValid 	 = new Validate_fields;
	$objAdmin	 = new classAdmin($objDBcon);
	$strError	=	"";

include_once 'functions.php';
$ccAdminData = get_session_data();
require_role_permission('staff', 'write', false);

if (isset($_POST['Submit']) && $_POST['Submit'] == "Submit")
{
	$spram = array();
	$spram[1] = isset($_POST['fname']) ? trim($_POST['fname']) : '';
	$spram[2] = isset($_POST['lname']) ? trim($_POST['lname']) : '';
	$spram[3] = isset($_POST['email']) ? trim($_POST['email']) : '';
	$spram[4] = isset($_POST['user_password']) ? $_POST['user_password'] : '';
	$spram[5] = isset($_POST['user_status']) ? $_POST['user_status'] : 'Active';
	$spram[6] = isset($_POST['login']) ? trim($_POST['login']) : '';
	$spram[7] = cms2_normalize_admin_role(isset($_POST['admin_role']) ? $_POST['admin_role'] : 'admin');

	$objValid->add_text_field("First Name", $spram[1], "text", "y");
	$objValid->add_text_field("Last Name", $spram[2], "text", "y");
	$objValid->add_text_field("Email", $spram[3], "email", "y");
	$objValid->add_text_field("Login", $spram[6], "text", "y");
	$objValid->add_text_field("Password", $spram[4], "text", "y");

	if (!$objValid->validation()) {
		$strError = $objValid->create_msg();
	}

	if ($spram[4] != (isset($_POST['user_cpassword']) ? $_POST['user_cpassword'] : '')) {
		$strError .= "The field Password and Confirm Password are not same.";
	}

	if (!$objAdmin->checkAdminDuplication($spram[6])) {
		$strError .= "The Login is not available.";
	}

	if (empty($strError)) {
		$adminId = $objAdmin->addAdmin($spram);
		$objAdmin->addAdminPermission($adminId);
		$strError = "Admin has been added successfully";
		$_POST = array();
	}
}

include "html/addadmin.html";

?>
