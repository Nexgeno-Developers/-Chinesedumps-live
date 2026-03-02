<?
	if (isset($_SESSION["userName"]))
{
}
else
{
	header ("location:".$LinkPath."login.php");
}
?>