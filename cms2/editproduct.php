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

			$objDBcon    =  new classDbConnection; 			// VALIDATION CLASS OBJECT
			$objValid 	 =  new Validate_fields;
			$objMain	 =	new classMain($objDBcon);	// VALIDATION CLASS classCategory
			$objProduct  =	new classProduct($objDBcon);
	$strError	=	"";
		$spram[1]		 =  "";
		$spram[2]		 =  "";
		$spram[3]		 =  "";
		$spram[4]		 =  "";
	
	if(isset($_POST['Submit']) && $_POST['Submit']  == "Submit")

	{
		$spram[1]		 =  $_POST['title'];
		$spram[2]		 =  $_POST['pcode'];
		$spram[3]		 =  $_POST['catid'];
		$spram[4]		 =  '';
		$spram[5]		 =  '0';
		$spram[6]		 =  $_FILES['proimg'];
		$spram[7]		 =  $_POST['sstock'];
		$spram[8]		 =  $_POST['pro_mate'];
		$spram[9]		 =  $_POST['pro_info'];
		$spram[10]		 =  $_POST['sprice'];
	 	$spram[11]		 =  $_POST['txt_StartDate'];
		$spram[12]		 =  $_POST['txt_EndDate'];
		$spram[13]		 =  $_POST['status'];
		$spram[14]		 =  $_POST['FCKeditor'];
		$spram[15]		 =  $_POST['pq'];
		$spram[16]		 =  $_POST['feature'];
		$spram[23]		 =  $_POST['relpro'];
		
		
		//$objValid->add_text_field("Product Name", $spram[1],"text", "y");
		$objValid->add_text_field("Product Quantity", $spram[15],"text", "y");
		//$objValid->add_text_field("Product Image", $spram[6]['name'],"text", "y");
		//$objValid->add_text_field("Product Default Price", $spram[5],"text", "y");
		$objValid->add_text_field("Product Category", $_POST['catid'][0],"text", "y");
		$objValid->add_text_field("Product Code", $spram[2],"text", "y");
		$objValid->add_text_field("Product Name", $spram[1],"text", "y");
		
		
		
		if (!$objValid->validation())
		$strError = $objValid->create_msg();
		
		if(empty($strError))
		{
		
		if($spram[6]['name'] == ''){
		$spram[6]	=	$_POST['hiidenimg'];
		}else{
		$spram[6]	= upload_color_img($spram[6]);
		}
			
			list($date1,$month1,$year1)	=	explode("-",$spram[11]);
		$spram[11]	=	$year1."-".$month1."-".$date1;
		
		list($date2,$month2,$year2)	=	explode("-",$spram[12]);
		$spram[12]	=	$year2."-".$month2."-".$date2;
		
			$seotitle	=	str_replace(" ","-",$_POST['title']);
		$insert	=	mysql_query("Update tbl_pro set 
		pro_name='".$spram[1]."',
		pro_manu='".$spram[4]."',
		pro_code='".$spram[2]."',
		pro_defprice='".$spram[5]."',
		pro_img='".$spram[6]."',
		pro_stock='".$spram[7]."',
		pro_mater='".$spram[8]."',
		pro_info='".$spram[9]."',
		pro_spprice='".$spram[10]."',
		pro_start='".$spram[11]."',
		pro_end='".$spram[12]."',
		pro_status='".$spram[13]."',
		pro_desc='".$spram[14]."',
		name_seo='".$seotitle."',
		pro_quantity='".$spram[15]."',
		pro_fet='".$spram[16]."' where pro_id='".$_GET['bottom_id']."'");
		
		$lastId	=	$_GET['bottom_id'];
		mysql_query("DELETE FROM tbl_catsproduct where ProId='".$_GET['bottom_id']."'");
		$manufacturer	=	$objProduct->addCategoriesproupdate($spram[3],$lastId);
		$lastId	=	$_GET['bottom_id'];
		mysql_query("DELETE FROM tbl_relproduct where ProId='".$_GET['bottom_id']."'");
		$objProduct->addrelpro($spram[23],$lastId);
		
		//header("location:addcolourlength.php?pId=".$lastId."");
		$strError	.= "Prodcut has been Updated successfully";
		}
		}
		
		$resultSet		=	$objProduct->getProductsbyId($_GET['bottom_id']);
			
			$rowgetdata		=	mysql_fetch_array($resultSet);
			//echo $rowgetdata['pro_id'];
			
			$getcatee	 =	$objMain->get_all_Tabledate('','tbl_catsproduct',"where ProId='".$rowgetdata['pro_id']."'");
			while($getcat	 =	mysql_fetch_array($getcatee)){
			$allcats		 .=  $getcat['catId'].",";
			//echo $spram[3];
			}
			
			$allcats			 =	explode(",",$allcats);
			
			$getcateerel	 =	$objMain->get_all_Tabledate('','tbl_relproduct',"where ProId='".$rowgetdata['pro_id']."'");
			while($getcatrel	 =	mysql_fetch_array($getcateerel)){
			$allrel		 .=  $getcatrel['catId'].",";
			//echo $spram[3];
			}
				$allrel			 =	explode(",",$allrel);
				$spram[1]		 =  $rowgetdata['pro_name'];
				$spram[2]		 =  $rowgetdata['pro_code'];
				
				//$spram[3]		 =  $allcats;
				//print_r($spram[3]);
				$spram[4]		 =  $rowgetdata['pro_manu'];
				$spram[5]		 =  $rowgetdata['pro_defprice'];
				
				$spram[6]		 =  $rowgetdata['pro_img'];
				$spram[7]		 =  $rowgetdata['sstock'];
				$spram[8]		 =  $rowgetdata['pro_mater'];
				$spram[9]		 =  $rowgetdata['pro_info'];
				$spram[10]		 =  $rowgetdata['pro_spprice'];
		list($date,$month,$year)	=	explode("-",$rowgetdata['pro_start']);
		$spram[11]	=	$year."-".$month."-".$date;
		
		list($date,$month,$year)	=	explode("-",$rowgetdata['pro_end']);
		$spram[12]	=	$year."-".$month."-".$date;
				
				$spram[13]		 =  $rowgetdata['pro_status'];
				$spram[14]		 =  $rowgetdata['pro_desc'];
				$spram[15]		 =  $rowgetdata['pro_quantity'];
				$spram[16]		 =  $rowgetdata['pro_fet'];
					
			
		
		if(isset($spram[4]) || isset($_POST['manid'])){
			if($spram[4] !=''){
			$maincat	= $spram[4];}else{
			$maincat	= $_POST['manid'];
			}
		}else{
		$maincat	=	"";
		}
		$manufacturer	=	$objProduct->ComboAllManufectures($maincat);
		
		
		if(isset($allcats) || isset($_POST['catid'])){
			if($allcats !=''){
			$maincat	= $allcats;}else{
			$maincat	= $_POST['catid'];
			}
		}else{
		$maincat	=	"";
		}
		$categories	=	$objProduct->ComboAllcats($maincat);
		
		if(isset($allrel) || isset($_POST['relpro'])){
			if($allrel !=''){
			$mainrel	= $allrel;}else{
			$mainrel	= $_POST['relpro'];
			}
		}else{
		$mainrel	=	"";
		}
		$categoriesrel	=	$objProduct->ComboAllcatsrel($mainrel);
		
		
//------------------------------------------------------------------------------------//

include "html/editproduct.html";

//------------------------------------------------------------------------------------//
?>