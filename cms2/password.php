<?PHP 

error_reporting(0);

session_start();



//root path

$LinkPath="../";

//title of the page

$strTitle	=	"Add News";

/*include files*/



include ("../includes/config/classDbConnection.php");

include("../includes/common/classes/validation_class.php");

include ("../includes/common/inc/sessionheader.php");

include ("../includes/common/classes/classAdmin.php");



include_once 'functions.php';

$ccAdminData = get_session_data();



//-------------------------------------------------------------------------------------------

								/*General Coding Area*/

//-------------------------------------------------------------------------------------------

$strCatName	=	"";		//it will contain the category name in case of duplication

	$strError	=	"";		//it will contain the error message

	$catCombo	=	"";





	$objDBcon    = new classDbConnection; // VALIDATION CLASS OBJECT

	$objPassword = new classAdmin($objDBcon); // VALIDATION CLASS classCategory

	$objValid 	 = new Validate_fields;



	/*  local variables */

	$arrparm	=   array();

/*Rest of the coding area*/



	if (isset($_POST['changepassword']) && $_POST['changepassword']	==	"Change Password")

	{

	//print  $_FILES['txtImage']; exit();

		$spram[1]		 =  $_POST['textnewPassword'];

		$spram[2]		 =  $_POST['textconfirmPsw']; 

		

		

		// check validations

	 	$objValid->add_text_field("New Password", $_POST['textnewPassword'], "text", "y");

		$objValid->add_num_field("Confirm Password", $_POST['textconfirmPsw'], "text", "y");

		

		if (!$objValid->validation()) 

			$strError = $objValid->create_msg();

			

			if($spram[1]!=$spram[2]){

			$strError="Invalid Confirm Password";

			}

			

		if(empty($strError))

		{

		

			//echo "test";

			$objPassword->changePassword($spram);

			$strError	= "Data have been saved successfully";

		}

		//$objCat->addNewCategory($spram);

	}	

//--------------------------------------------------------------------------------------



	include ("html/password.html");

//-------------------------------------------------------------------------------------

?>