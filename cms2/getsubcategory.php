<?PHP
include ("../includes/config/classDbConnection.php");
//-------------------------------------------------------------------------------------
	$objCon		 = 	new classDbConnection();
	
	$cateID=$_GET['q'];
	$sel = @$_GET['sel'];
	$selArr = "";
	if(!empty($sel))
	{
		$selArr = explode(",",$sel);
	}
	echo fillComboSubCategory($intValue = 0,$cateID);
	function fillComboSubCategory($intValue = 0,$cateID)
			{
							
			$txtSelected = "";	//Set the option to Selected
			$qry = mysql_query("select * from tbl_cert  where ven_id=".$cateID."  ORDER BY cert_name");
				   
			$txtCombo = "<select name='cert_id' class='csstxtfield2'>";
				while ($row1 = mysql_fetch_array($qry))
					{
						$checked = '';
						if(@is_array($selArr) && in_array($row1["cert_id"], $selArr)!==false)
						{
							$checked = 'selected="selected"';
						}
						$txtCombo .= "<option $checked value='".$row1['cert_id']."'>".$row1['cert_name']."</option>";
					}
					$txtCombo .= "</select>";	
					return $txtCombo;
				}
//--------------------------------------------------------------------------------------
?>