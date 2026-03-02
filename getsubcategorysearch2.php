<?PHP 
include("includes/config/classDbConnection.php");

//-------------------------------------------------------------------------------------
	$objCon		 = 	new classDbConnection();
	
	$cateID=$_GET['q'];
	echo fillComboSubCategory($intValue = 0,$cateID,$siteId);
	function fillComboSubCategory($intValue = 0,$cateID,$siteId)
			{
					
			$txtSelected = "";	//Set the option to Selected
			$qry = mysql_query("select * from tbl_cert  where ven_id='".$cateID."' ORDER BY cert_name");
				   
			$txtCombo = "<select name=\"cmb_subcategory\" id=\"cmb_subcategory\" style='width:200px;' onChange='showsubcateexams(this.value)'>";
			$txtCombo .= "<option value=''>Select Certification</option>";	
				while ($row1 = mysql_fetch_array($qry)) 
					{
			
						if($intValue == $row1["cert_id"])
								{
									$txtSelected = "selected";
									$txtCombo .= "<option value=\"".$row1['cert_id']."\" $txtSelected >".$row1['cert_name']."</option>";
								}
								else
								{
									$txtSelected = "";
									$txtCombo .= "<option value=\"".$row1['cert_id']."\"  >".$row1['cert_name']."</option>";
								}
					}
						
					return $txtCombo."</select> " ;
				}
//--------------------------------------------------------------------------------------
?>