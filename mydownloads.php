<?php

ob_start();

session_start();

if(isset($_SESSION['uid'])){

}else{

header("location:login.html");

}

include("includes/config/classDbConnection.php");



include("includes/common/classes/classmain.php");

include("includes/common/classes/classProductMain.php");

include("includes/common/classes/classcart.php");



$objDBcon   =   new classDbConnection; 

$objMain	=	new classMain($objDBcon);

$objPro		=	new classProduct($objDBcon);

$objCart	=	new classCart($objDBcon);



$getPage	=	$objMain->getContent(4);

$getPage[0]	=	"Downloads";

$getPage[1]	=	"Downloads";

$getPage[2]	=	"Downloads";

$getPage[4]	=	"Downloads";

$getPage[5]	=	"Downloads";





function find_file ($dirname, $fname, &$file_path) {



  $dir = opendir($dirname);



  while ($file = readdir($dir)) {

    if (empty($file_path) && $file != '.' && $file != '..') {

      if (is_dir($dirname.'/'.$file)) {

        find_file($dirname.'/'.$file, $fname, $file_path);

      }

      else {

        if (file_exists($dirname.'/'.$fname)) {

          $file_path = $dirname.'/'.$fname;

          return;

        }

      }

    }

  }



} // find_file

    $quer	=	$objDBcon->Sql_Query_Exec('*','website','');

	$exec	=	mysql_fetch_array($quer);

	

	$copyright	=	$exec['copyright'];

	

	if(isset($_POST['ok'])){

		header("location:download.php?x=".$_POST['hiddenid']."&y=".$_POST['cmb_subcategoryexam']."");

	}

	$no_assigned=1;

	$str1 = "";



		

	$userId = $_SESSION['uid'];

	$show_orders	=	"";

	$result = $objCart->getAllCurrentProducts($userId);

	

	if(mysql_num_rows($result)<=0){

		if($no_assigned==1)

		{

			$show_orders	=	"<div class='redbold'>NO Products available for download in your account.</div>";

		}

	}else{

		$show_orders	=	$objCart->show_current_products($result);

	}	

	$show_orders	.=	$str1;



	$firstlink	=	" ".$getPage[1];

	

?>

<? include("includes/header.php");?>

<script language="JavaScript" type="text/javascript" src="js/searchcategory2.js"></script>

<script language="JavaScript" type="text/javascript" src="js/searchcategoryexams.js"></script>

<script language="javascript" type="text/javascript">

function emailvalideok(){

				 if(document.form44ok.cmb_category.selectedIndex ==0)

						{

							alert("Please select the Vendor.");

							return false;

						}

				 if(document.form44ok.cmb_subcategory.selectedIndex ==0)

						{

							alert("Please select the Certification.");

							return false;

						}

				 if(document.form44ok.cmb_subcategoryexam.selectedIndex ==0)

						{

							alert("Please select the Exam.");

							return false;

						}

				return true;

						

						

	}

</script>

<style>
    .order_tables {
    border: 1px solid #ccc;
    border-top: 0px;
}
ul.list-box02.order_tables {
    padding: 10px;
}

@media(max-width:767px){
    .about-chinesedumps
    {
        padding-bottom:30px;
    }
}
</style>

<div class="content-box">

        <div class="certification-box certification-box03">

            <div class="container">

                <a href="<?php echo $websiteURL; ?>" class="blutext">Home</a> > <?php echo $getPage[1]; ?>

            </div>

        </div>

        <div class="about-chinesedumps">

            <div class="container">

                <h1 class="main_heading text-center">
                <span><?php echo $getPage[1]; ?></span>
            </h1>

                <div class="black-heading">Here Is Your Member Area </div>

                <ul class="popular-vendor-list popular-group1-list ">

                    <li class="justify_center">

                    <?php include("html/account.html");	?>

                    </li>

                </ul>
 <br>

                <h1 class="main_heading text-center pt50">My<span> Downloads</span></h1>

                <ul class="popular-vendor-list popular-group1-list">

                    <li class="justify_center">

                    <ul class="group">

                    <?php

				  	if(isset($venlist) and $venlist != ""){

              	 	echo $venlist;

					}

               		?>

                    </ul>

                    </li>

                </ul>

     

                <ul class="list-box list-title mobile_tables_check">

                	<li>Exam Detail</li>

                	<li></li>

                	<li>Status</li>

                </ul>

                <ul class="list-box02 order_tables">

                    <?=$show_orders?>

                </ul>

            </div>

        </div>

        

        



	</div>



  

              <? include("includes/footer.php");?>