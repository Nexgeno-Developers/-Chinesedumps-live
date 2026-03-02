<?PHP
session_start();
//ob_start();
//root path
$LinkPath="../";
//title of the page
$strTitle	=	"";
//javascript file name if any
$javascriptFile	=	"";

/*include files*/

include $LinkPath."includes/common/functions/validation_functions.php";
include $LinkPath."includes/common/variables/GlobalVariables.php";
include $LinkPath."includes/config/classDbConnection.php";
include $LinkPath."includes/common/classes/classAdmin.php";
include $LinkPath."includes/common/classes/classSessionHeader.php";

//-------------------------------------------------------------------------------//
	$strUser			=	"";
	$strPassword	=	"";
	if (isset($_POST['btnLogin']))
		{
			$objCon		= 	new classDbConnection();
			$objAdmin 	= 	new classAdmin($objCon); //Creat Object of Class Admin.
			$strUser	=	$_POST["textUser"];
		 	$strPassword=	$_POST["textfield3"];

			if (CheckUsername($strUser))
			{
				
					if ($objAdmin->boolValidateAdmin($strUser,$strPassword))
						{
							$objSession	=	new classSessionHeader();
							$adm_id		=	$objAdmin->getId($strUser);
							$objSession->createSession("adminId",$adm_id);
						 	$objSession->createSession("strAdmin",$strUser);
							?>
                            <script>document.location="sum.php";</script>
                            <?php
					//		header ("Location: sum.php");
						}
					else
						{
							$_SESSION['error']	=	"*Username or Password is incorrect";
							?>
                            <script>document.location="index.php";</script>
                            <?php
//							header ("Location: index.php");
						}
					}
			else
			{
				$_SESSION['error']	=	"*Invalid user name only Characters and numeric characters allowed";
							?>
                            <script>document.location="index.php";</script>
                            <?php
	//			header ("Location: index.php");
			}
		}
?>