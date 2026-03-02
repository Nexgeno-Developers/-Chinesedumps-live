<?php
ob_start();
ob_flush();
//iniciate basket

//if(!(is_array ($_SESSION["basket"]))):

	//$_SESSION["basket"]=array();

//endif;

//if(!(is_array ($_SESSION["addr"]))):

	//$_SESSION['addr']=array();

//endif;

//print_r($_SESSION['addr']);

//add in basket     

if(isset($_POST["mode"]) && $_POST["mode"]=="basket"):

    $p_id=$_POST['pid'];

	$current=array($_POST["qty"], $_POST["price"], $_SESSION["userid"]);

	$found=false;

	$fqty=0;

	foreach ($_SESSION["basket"] as $key => $value):
		if($key==$p_id):
			$found=true;
			$fqty=$value[0]-0;
		endif;
   endforeach;

  		if($found==true):
			$current=array($_POST["qty"]+$fqty,$_POST["price"], $_SESSION["userid"]);
		endif;



	$_SESSION["basket"][$_POST["pid"]]=$current;

	#die();

//header("Location:product.php?pid=$p_id");
endif;
//delete item
if(isset($_GET['mode'])){
if(isset($_GET[mode]) && $_GET[mode]=="del"):
	if (array_key_exists($_GET[id], $_SESSION['basket'])):
   		unset($_SESSION['basket'][$_GET[id]]);
	endif;
endif;
}
//update cart

if(isset($_POST["mode"]) && $_POST["mode"]=="update"): 

 foreach ($_POST["qty1"] as $key => $value):

	$system=explode('-',$key);

	$kkey=$system[1];

	if (array_key_exists($key, $_SESSION['basket'])):

			$_SESSION['basket'][$key][0]=$value;

	endif;
    endforeach;
endif;
//get cart summery

$c=0;

$itemprice=0;

///foreach ($_SESSION["basket"] as $item):
	//c=$c+$item[0];
	//$itemprice=$itemprice+$item[0]*$item[1];
//endforeach;

	$totalprice=$itemprice;
	if (isset($_SESSION["payterms"]) && $_SESSION["payterms"]==1):
		$stot=$totalprice+1.8;
	elseif (isset($_SESSION["payterms"]) && $_SESSION["payterms"]==2): 
		$stot=$totalprice+0.0;
	else:
		$stot=$totalprice;
	endif;	

	if (isset($_SESSION['shpmethod']) && $_SESSION['shpmethod']!=0):
		$stot=$stot+$shipmentvalue[$_SESSION[shpmethod]];
		$ship=$shipmentvalue[$_SESSION[shpmethod]];
	endif;
?>