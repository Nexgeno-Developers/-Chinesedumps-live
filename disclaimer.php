<?php
ob_start();
session_start();
include("includes/config/classDbConnection.php");

include("includes/common/classes/classmain.php");
include("includes/common/classes/classProductMain.php");
include("includes/common/classes/classcart.php");

include("includes/shoppingcart.php");

$objDBcon   =   new classDbConnection; 
$objMain	=	new classMain($objDBcon);
$objPro		=	new classProduct($objDBcon);
$objCart	=	new classCart($objDBcon);

$getPage	=	$objMain->getContent(33);

    $quer	=	$objDBcon->Sql_Query_Exec('*','website','');
	$exec	=	mysql_fetch_array($quer);
	
	$copyright	=	$exec['copyright'];
		
		$firstlink	=	" ".$getPage[1];
?>

<? include("includes/header.php");?>

<div class="content-box paddbottom60">
        <div class="exam-group-box">
            <div class="max-width">
                <?php echo $getPage[2]; ?>
            </div>
        </div>
	</div>
<? include("includes/footer.php");?>