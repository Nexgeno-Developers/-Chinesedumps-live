<?
//session_start();
if (isset($_SESSION["strAdmin"]))
{
}
else
{
	header ("location:index.php");
}
?>