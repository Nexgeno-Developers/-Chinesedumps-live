<?php

ob_start();

session_start();

include("includes/config/classDbConnection.php");

include("includes/common/classes/classmain.php");

include("includes/common/classes/classcart.php");



include("functions.php");



$objDBcon   =   new classDbConnection; 

$objMain	=	new classMain($objDBcon);

$objCart	=	new classCart($objDBcon);



$todaydate	=	date("Y-m-d");

$after7days	=	date('Y-m-d',strtotime('+7 days' ) );



$q = "select * from tbl_exam where dateUpdateStatus='0'";

$qry = mysql_query($q);

	if(mysql_num_rows($qry) != "0")

		{

			$ss = mysql_query("update tbl_exam set exam_upddate='$todaydate', dateUpdateStatus='1' where dateUpdateStatus='0' limit 500");

			if($ss){echo "query run";} else {echo "query not run";}

		}

	else{

			mysql_query("update tbl_exam set dateUpdateStatus='0' where dateUpdateStatus='1'");

		}



/////////////////////////////////////////////////////////////////Update Date/////////////////////////////////////////////////////////


$q = mysql_query("select * from tbl_exam where QA !='0'");
for($i=0;$i<mysql_num_rows($q);$i++){
$examData = mysql_fetch_array($q);

	$r = mysql_query("select * from tbl_package_engine where package_id='".$examData["exam_id"]."'");
	if(mysql_num_rows($r) != "0"){
		
	}
	else{
	$vData = mysql_fetch_array(mysql_query("select * from tbl_vendors where ven_id='".$examData["ven_id"]."'"));
	$pth = $vData["ven_name"]."/".$examData["exam_name"]."Sim.zip";
	echo "insert into tbl_package_engine (package_id, version, type, path, os, price) values ('".$examData["exam_id"]."','6.0','Simulator','".$pth."','Win','74.99')"."<br>";
	mysql_query("insert into tbl_package_engine (package_id, version, type, path, os, price) values ('".$examData["exam_id"]."','6.0','Simulator','".$pth."','Win','74.99')"."");
	}
}


////////////////////////////////////////Delete Demo Data //////////////////
$a = mysql_query("select * from tbl_demo_download_email");

for($b=0;$b<mysql_num_rows($a);$b++){
$fetchDemo = mysql_fetch_array($a);

$fetchUser = mysql_query("select u.*, o.*, e.* from tbl_user u, order_detail o, tbl_exam e where (o.UserID=u.user_id and o.ProductID=e.exam_id) and (u.user_email='".$fetchDemo["demo_download_email"]."' and e.exam_name='".$fetchDemo["exam_code"]."')");

if(mysql_num_rows($fetchUser) != "0"){
$fetchDemos = mysql_fetch_array($fetchUser);
echo $fetchDemos["user_email"]." - ".$fetchDemos["exam_name"]."<br>";

mysql_query("delete from tbl_demo_download_email where demo_download_email='".$fetchDemos["user_email"]."' and exam_code='".$fetchDemos["exam_name"]."'");

}
}
////////////////////////////////////////Delete Demo Data //////////////////

	

?>