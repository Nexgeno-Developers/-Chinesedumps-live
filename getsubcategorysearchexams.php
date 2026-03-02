<?PHP 
include("includes/config/classDbConnection.php");
//-------------------------------------------------------------------------------------
	$objCon		 = 	new classDbConnection();
	
	$cateID=$_GET['q'];
	echo fillComboSubCategory($intValue = 0,$cateID);
	function fillComboSubCategory($intValue = 0,$cateID)
			{
			$txtSelected = "";	//Set the option to Selected
			$qry = mysql_query("select * from tbl_exam  where cert_id='".$cateID."'  ORDER BY exam_name");
			   
			$txtCombo = "<select name=\"cmb_subcategoryexam\" id=\"cmb_subcategoryexam\" class=\"csstxtfield2\" style='width:200px;'>";
			$txtCombo .= "<option value=''>Select Exam</option>";	
				while ($row1 = mysql_fetch_array($qry)) 
					{
									$txtSelected = "";
									$txtCombo .= "<option value=\"".$row1['exam_id']."\"  >".$row1['exam_name']."</option>";
							
					}
						
					return $txtCombo."</select> " ;
				}
//--------------------------------------------------------------------------------------
?>


