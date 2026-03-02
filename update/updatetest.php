<?php include("../includes/certManagerDB/db.php");?>
<html>
	<body>
		<form name="test" method="post" action="/update/checkupdate.php">
		Order number: <input type="text" name="order_number"><br/>
		Serial number: <input type="text" name="serial_number"><br/>
		Mboard number: <input type="text" name="mboard_number"><br/><br/>
		Operating System: <input type="text" name="os"><br/><br/>
		Version: <input type="text" name="version"><br/><br/>
		Code: <input type="text" name="code"><br/><br/>
		<input type="submit" value="Submit">
		</form>
	</body>	
	

</html>