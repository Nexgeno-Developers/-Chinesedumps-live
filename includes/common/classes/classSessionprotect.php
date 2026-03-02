<?PHP
	ob_start();
	error_reporting(0);
	session_start();
	if($_SESSION["userName"]=='')
	{
		?>
		 <script language="javascript" type="text/javascript">
		 window.open('webprotect.php','title','height=250,width=500,scrollbars=1,resizable=yes');
		</script>
		<?
		
	}
?>