<?php 

mysql_connect("localhost","root","");
mysql_select_db("exam-collections");

$p=0;
$w=0;
$c=0;
$sd = "2012-2-27";
$ed = "2014-2-27";

	
	
//$query= "SELECT * FROM tbl_user WHERE creatDate BETWEEN ".$sd." AND ".$ed."";
echo $query;
$result=mysql_query($query);
echo mysql_num_rows($result);
if($result)
{
	echo mysql_num_rows($result);
	for($i=0; $i<mysql_num_rows($result);$i++)
	{
		$row = mysql_fetch_array($result);
		$count = $row["user_id"];
		echo $count;
		$p+=$count;
		//$price=$row["price"];
		//$w=$count*$price;
		$c=$c+1;
	}
echo "<h1>Total Items Sold Between $sd And $ed: ".$p."</h1><br/>";
echo "<h1>Total Earning Between $sd And $ed: ".$w."</h1><br/>";
echo "<h1>Total Sale Records Between $sd And $ed: ".$c."</h1><br/>";
}
else {echo "error";}

?>