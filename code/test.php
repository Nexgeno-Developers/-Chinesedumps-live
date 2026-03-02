<?php

ob_start();

session_start();

include("../includes/config/classDbConnection.php");

$objDBcon   =   new classDbConnection; 

/////////////////////Update IBM Replace Exams//////////////////////////////
$q = mysql_query("select n.*, o.* from TABLE42 n, tbl_exam o where n.COL1=o.exam_name");
for($i=1;$i<=mysql_num_rows($q);$i++){
$newData = mysql_fetch_array($q);
//echo $i." ".$newData["COL1"]." ".$newData["COL2"]."<br>";

$aaa = "update tbl_exam set exam_name='".$newData["COL2"]."' where exam_name='".$newData["COL1"]."'";
echo $i." ".$aaa."<br>";
mysql_query($aaa);

}
/////////////////////Update IBM Replace Exams//////////////////////////////




?>