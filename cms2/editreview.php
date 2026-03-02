<?PHP 
ini_set("display_errors","0");
ob_start();
session_start();

	include ("../includes/config/classDbConnection.php");
	include("../includes/common/classes/validation_class.php");
	include("../includes/common/functions/func_uploadimg.php");
	include ("../includes/common/inc/sessionheader.php");
	include ("../includes/common/classes/classmain.php");
	include ("../includes/common/classes/classProduct.php");
	

include_once 'functions.php';
$ccAdminData = get_session_data();


//--------------------Creat objects---------------------------

			$objDBcon    = new classDbConnection; 			// VALIDATION CLASS OBJECT
			$objValid 	 = new Validate_fields;
			$objMain	 =	new classMain($objDBcon);	// VALIDATION CLASS classCategory
			$objProduct  =	new classProduct($objDBcon);
	$strError	=	"";
		$spram[1]		 =  "";
		$spram[2]		 =  "";
		$spram[3]		 =  "";
	
	
	$strQury	=	"update tbl_reviews set rev_new='0' where rev_id = '".$_GET['bottom_id']."'";
					
				$rsResult = mysql_query($strQury);
	if(isset($_POST['Submit']) && $_POST['Submit']  == "Submit")

	{
		$spram[0]		 =  $_GET['bottom_id'];
		$spram[1]		 =  $_POST['hiddenproname'];
		$spram[2]		 =  $_POST['status'];
		$spram[3]		 =  date("Y-m-d");
		$spram[4]		 =  date("H-i-s");
		$spram[5]		 =  $_POST['FCKeditor'];
		

		//$objValid->add_text_field("Review Status", $spram[2],"text", "y");
		//$objValid->add_text_field("Product Name", $spram[1],"text", "y");
		
		
		//list($date,$month,$year)	=	explode("-",$arryPr[3]);
		//$arryPr[3]	=	$year."-".$month."-".$date;
		
		if (!$objValid->validation())
		$strError = $objValid->create_msg();
		
		if(empty($strError))
		{
			//$spram[6]	= upload_color_img($spram[6]);
			$brandadd	=	$objProduct->updateReviews($spram);
		
		$strError	.= "Review has been added successfully";
		}
		}else{
		$get	=	mysql_fetch_array(mysql_query("Select * from tbl_reviews where rev_id='".$_GET['bottom_id']."'"));
		$spram[1]	=	$get['rev_proid'];
		$spram[2]	=	$get['rev_status'];
		//$spram[3]	=	$get['album_id'];
		//$spram[4]	=	$get['pro_desc'];
		$spram[5]	=	$get['rev_desc'];
		}
		if(isset($spram[1]) || isset($_POST['hiddenproname'])){
			if($spram[1] !=''){
			$maincat	= $spram[1];}else{
			$maincat	= $_POST['pproduct'];
			}
		}else{
		$maincat	=	"";
		}
		$manufacturer	=	$objProduct->ComboAllproduct($maincat);
		
		
		
//------------------------------------------------------------------------------------//

include "html/editreview.html";

//------------------------------------------------------------------------------------//
?>