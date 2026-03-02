<?PHP



	error_reporting(0);







	class classProduct



	{



		//--------------------------------------------------------------------------------



								// Class Properties (Class Level Variables)



		//--------------------------------------------------------------------------------



			var $objDbConn		;



			var	$rowsPerPage 	;



			var $linkPerPage	;



			var	$PageNo			; 	# Shows # of Page			



			var $PageIndex	 	;	# Shows # of Page Index				



//..........................................................................................................







		function classProduct($objDbConn)



		{



			$this->objDbConn	= 	$objDbConn; 



		}



//.................................................................................................











//.................................................................................................







//.................................................................................................





		//////////.............................



function delete_hair($pro_id)



			{



				$strQury 	= 	"DELETE FROM  tbl_length WHERE length_id  = '".$pro_id."'";



				$sqlResult	=	$this->objDbConn->Dml_Query_Parser($strQury);



			}



function delete_manufecture($pro_id)



			{



				$strQury 	= 	"DELETE FROM  tbl_manufec WHERE man_id = '".$pro_id."'";



				$sqlResult	=	$this->objDbConn->Dml_Query_Parser($strQury);



			}



	function delete_propro($pro_id)



			{



				$q1		=	"select * FROM tbl_pro WHERE pro_id ='".$pro_id."'";



				$dw3	=	mysql_query($q1) or die(mysql_error());	



				$row	=	mysql_fetch_array($dw3);



				unlink("../upload/".$row['pro_img']);



				unlink("../upload/thumbs/".$row['pro_img']);



				



				$strQury44 	= 	"DELETE FROM  tbl_catsproduct WHERE ProId = '".$pro_id."'";



				$sqlResult44	=	$this->objDbConn->Dml_Query_Parser($strQury44);



				



				$strQury2 	= 	"DELETE FROM  tbl_differprice WHERE pro_id = '".$pro_id."'";



				$sqlResult2	=	$this->objDbConn->Dml_Query_Parser($strQury2);



				



				$strQurylink 	= 	"DELETE FROM  tbl_links WHERE module='product' and controlId = '".$pro_id."'";



				$sqlResultlink	=	$this->objDbConn->Dml_Query_Parser($strQurylink);



				



				$strQury 	= 	"DELETE FROM  tbl_pro WHERE pro_id = '".$pro_id."'";



				$sqlResult	=	$this->objDbConn->Dml_Query_Parser($strQury);



			}



		function delete_ordertotal($pro_id)



			{



				$strQury44 	= 	"DELETE FROM   tbl_tmporder WHERE processord = '".$pro_id."'";



				$sqlResult44	=	$this->objDbConn->Dml_Query_Parser($strQury44);



				



				$strQury2 	= 	"DELETE FROM  tbl_order WHERE ord_id = '".$pro_id."'";



				$sqlResult2	=	$this->objDbConn->Dml_Query_Parser($strQury2);



			



			}







function delete_review($pro_id)



			{



				$strQury 	= 	"DELETE FROM   tbl_reviews WHERE rev_id = '".$pro_id."'";



				$sqlResult	=	$this->objDbConn->Dml_Query_Parser($strQury);



			}



function delete_brand($pro_id)



			{



				//...................................................................



				$q1		=	"select * FROM tbl_brands WHERE cat_id ='".$pro_id."'";



				$dw3	=	mysql_query($q1) or die(mysql_error());	



				$row	=	mysql_fetch_array($dw3);



				unlink("../upload/".$row['cat_img']);



				unlink("../upload/thumbs/".$row['cat_img']);



				//...................................................................



				$strQurylink 	= 	"DELETE FROM  tbl_links WHERE module='category' and controlId = '".$pro_id."'";



				$sqlResultlink	=	$this->objDbConn->Dml_Query_Parser($strQurylink);



				



				$strQury 	= 	"DELETE FROM  tbl_brands WHERE cat_id = '".$pro_id."'";



				$sqlResult	=	$this->objDbConn->Dml_Query_Parser($strQury);



			}



function getBrands($pro_id){



				$strQury 	= 	"SELECT * FROM  tbl_brands WHERE cat_id = '".$pro_id."'";



				$sqlResult	=	$this->objDbConn->Dml_Query_Parser($strQury);



				return $sqlResult;



}



function getProductsbyId($pro_id){



				$strQury 	= 	"SELECT * FROM  tbl_pro WHERE pro_id = '".$pro_id."'";



				$sqlResult	=	$this->objDbConn->Dml_Query_Parser($strQury);



				return $sqlResult;



}



//....................................Hair Show Function.............................................................



	function show_Hairs($resutlSet)



			{



					$counter = 1;



					$edit	=	"";



					$del	=	"";



				



					while($rows=mysql_fetch_array($resutlSet))



						{	



								//..........................................................



										if (($counter % 2)!=0 )



											$intColor = "";



												



										else



											$intColor = "bgcolor='#f3eeee'";



								//..........................................................



						



							$edit	=	'<a href="editlength.php?bottom_id='.$rows['length_id'].'">Edit </a>';



							$del	=	'| <a href="lengthmanage.php?action=up&nid='.$rows['length_id'].'" onclick= "return delete_pro();">Delete</a>';



							



								



								$showTr		.='



									<tr '.$intColor.'>



									<td class=item align="center" valign="middle"><input type="checkbox" name="chkbox'.$counter.'" id="chkbox'.$counter.'" value="'.$rows['length_id'].'" /></td>



    



    <td class=item style=padding-left: 0px; align="center">'.$rows['length_id'].'</td>



	 <td class=item style=padding-left: 0px; align="center">'.$rows['length_name'].'</td>



	<td class=item align="center" width="164">'.$rows['hair_status'].'</td>



    <td class=item nowrap=nowrap align="center" >'.$edit.''.$del.'</td>



</tr>';	



						   $counter++;	



				



				}



		return $showTr.'<input name="counter" type="hidden" value="'.$counter.'" />';



		



			}



			



			function addCategoriespro($catids,$lastId,$seotitle){



				$total	=	count($catids);



				$i=0;



				while($i<=$total){



				if($catids[$i] !=''){



				$queryinsert	=	mysql_query("INSERT INTO tbl_catsproduct(catId,ProId)VALUES('".$catids[$i]."','".$lastId."')");



				$rowrow	=	mysql_fetch_array(mysql_query("SELECT * FROM tbl_brands where cat_id='".$catids[$i]."'"));



				$newurl	=	$rowrow['br_seotitle'].'/'.$seotitle;



				mysql_query("INSERT INTO tbl_links(url,module,controlId) VALUES('".$newurl."','product','".$lastId."')");



				}



				$i++;



			}



			}



			



			function addCategoriesproupdate($catids,$lastId){



				



				$i=0;



				while($i<=$total){



				if($catids[$i] !=''){



				$queryinsert	=	mysql_query("INSERT INTO tbl_catsproduct(catId,ProId)VALUES('".$catids[$i]."','".$lastId."')");



		



				}



				$i++;



			}



			}



			



			function addrelpro($catids,$lastId){



				$total	=	count($catids);



				$i=0;



				while($i<=$total){



				if($catids[$i] !=''){



				$queryinsert	=	mysql_query("INSERT INTO tbl_relproduct(catId,ProId)VALUES('".$catids[$i]."','".$lastId."')");



				



				}



				$i++;



			}



			}



			



			function show_manu($resutlSet)



			{



					$counter = 1;



					$edit	=	"";



					$del	=	"";



				



					while($rows=mysql_fetch_array($resutlSet))



						{	



								//..........................................................



										if (($counter % 2)!=0 )



											$intColor = "";



												



										else



											$intColor = "bgcolor='#f3eeee'";



								//..........................................................



						



							$edit	=	'<a href="editmanu.php?bottom_id='.$rows['man_id'].'">Edit </a>';



							$del	=	'| <a href="manumanage.php?action=up&nid='.$rows['man_id'].'" onclick= "return delete_pro();">Delete</a>';



							



								



								$showTr		.='



									<tr '.$intColor.'>



									<td class=item align="center" valign="middle"><input type="checkbox" name="chkbox'.$counter.'" id="chkbox'.$counter.'" value="'.$rows['man_id'].'" /></td>



    



    <td class=item style=padding-left: 0px; align="center">'.$rows['man_id'].'</td>



	 <td class=item style=padding-left: 0px; align="center">'.$rows['man_name'].'</td>



	



    <td class=item nowrap=nowrap align="center" >'.$edit.''.$del.'</td>



</tr>';	



						   $counter++;	



				



				}



		return $showTr.'<input name="counter" type="hidden" value="'.$counter.'" />';



		



			}



	



	function show_product($resutlSet)



			{



					$counter = 1;



					$edit	=	"";



					$del	=	"";



				



					while($rows=mysql_fetch_array($resutlSet))



						{	



								//..........................................................



										if (($counter % 2)!=0 )



											$intColor = "";



												



										else



											$intColor = "bgcolor='#f3eeee'";



								//..........................................................



						



							$addcol	=	'<a href="addcolourlength.php?pId='.$rows['pro_id'].'" >Colour/Length Management</a>';



							



							$edit	=	'| <a href="editproduct.php?bottom_id='.$rows['pro_id'].'">Edit </a>';



							$del	=	'| <a href="productmanage.php?action=up&nid='.$rows['pro_id'].'" onclick= "return delete_pro();">Delete</a>';



							



								



								$showTr		.='



									<tr '.$intColor.'>



									<td class=item align="center" valign="middle"><input type="checkbox" name="chkbox'.$counter.'" id="chkbox'.$counter.'" value="'.$rows['pro_id'].'" /></td>



    



    <td class=item style=padding-left: 0px; align="center">'.$rows['pro_code'].'</td>



	 <td class=item style=padding-left: 0px; align="center">'.$rows['pro_name'].'</td>



	 <td class=item style=padding-left: 0px; align="center">'.$rows['pro_quantity'].'</td>



	 <td class=item style=padding-left: 0px; align="center">'.$rows['pro_status'].'</td>



    <td class=item nowrap=nowrap align="center" >'.$addcol.''.$edit.''.$del.'</td>



</tr>';	



						   $counter++;	



				



				}



		return $showTr.'<input name="counter" type="hidden" value="'.$counter.'" />';



		



			}



	



	function show_allorders($resutlSet)



			{



					$counter = 1;



					$edit	=	"";



					$del	=	"";



				



					while($rows=mysql_fetch_array($resutlSet))



						{	



								//..........................................................



										if (($counter % 2)!=0 )



											$intColor = "";



												



										else



											$intColor = "bgcolor='#f3eeee'";



								//..........................................................



						



							



							$edit	=	'<a href="editorder.php?order_id='.$rows['ord_id'].'">Edit/View </a>';



							$del	=	'| <a href="ordermanage.php?action=up&nid='.$rows['ord_id'].'" onclick= "return delete_pro();">Delete</a>';



							



							list($year,$month,$day)	=	explode("-",$rows['ord_date']);



							$newsate	=	$day.'-'.$month.'-'.$year;



								$showTr		.='



									<tr '.$intColor.'>



									<td class=item align="center" valign="middle"><input type="checkbox" name="chkbox'.$counter.'" id="chkbox'.$counter.'" value="'.$rows['pro_id'].'" /></td>



    



    <td class=item style=padding-left: 0px; align="center">'.$rows['order_id'].'</td>



	 <td class=item style=padding-left: 0px; align="center">'.$rows['bill_fname'].'&nbsp;'.$rows['bill_lname'].'</td>



	 <td class=item style=padding-left: 0px; align="center">'.$rows['bill_email'].'</td>



	 <td class=item style=padding-left: 0px; align="center">'.$newsate.'</td>



	 <td class=item style=padding-left: 0px; align="center">'.$rows['order_status'].'</td>



    <td class=item nowrap=nowrap align="center" >'.$edit.''.$del.'</td>



</tr>';	



						   $counter++;	



				



				}



		return $showTr.'<input name="counter" type="hidden" value="'.$counter.'" />';



		



			}



	



	



	



	function show_review($resutlSet)

	{

		$counter = 1;

		$edit	=	"";

		$del	=	"";



		while($rows=mysql_fetch_array($resutlSet)){	



			//..........................................................

			if (($counter % 2)!=0 ){

				$intColor = "";

			}else{

				$intColor = "bgcolor='#f3eeee'";

			}

			//..........................................................



						



							$edit	=	'<a href="editvendor.php?ven_id='.$rows['ven_id'].'">Edit </a>';



							$del	=	'| <a href="vendormanage.php?action=up&nid='.$rows['ven_id'].'" onclick= "return delete_pro();">Delete</a>';



							if($rows['ven_status']=='1')



							{



							$chek='Enable';



			  				$st	=	'';



							}else{



							$chek='Disable';



							$st	=	'style="color:#FF0000"';



							}



							



							if($rows['ven_home']=='1'){	$home	=	'Yes'; }else{ $home	=	'No'; }



							if($rows['ven_hot']=='1'){	$hot	=	'Yes'; }else{ $hot	=	'No'; }



							//if($rows['allvendors']=='1'){	$all	=	'ON'; }else{ $all	=	'OFF'; }



							



							$tableName  ="tbl_vendors";



							$tablefield ="ven_id";



							$tablestatus ="ven_status";



							



							$statususer= "<div id='txtHint".$rows['ven_id']."'><a href='javascript:showHint(".$rows['ven_id'].", \"$tableName\",\"$tablefield\",\"$tablestatus\")' ".$st.">".$chek." </a></div>";

								

							$statushome= "<div id='txtHint".$rows['ven_id']."'><a href='javascript:showHome(".$rows['ven_id'].", \"$tableName\",\"$tablefield\",\"$tablestatus\")' ".$st.">".$home." </a></div>";	



								$showTr		.='



									<tr '.$intColor.'>



									<td class=item align="center" valign="middle"><input type="checkbox" name="chkbox'.$counter.'" id="chkbox'.$counter.'"  value="'.$rows['ven_id'].'" /></td>



    



    <td class=item style=padding-left: 0px; align="center">'.$rows['ven_name'].'</td>



	 <td class=item style=padding-left: 0px; align="center">'.$hot.'</td>



	 <td class=item style=padding-left: 0px; align="center">'.$statushome.'</td>



	<td class=item style=padding-left: 0px; align="center">'.$statususer.'</td>



    <td class=item nowrap=nowrap align="center" >'.$edit.''.$del.'</td>



</tr>';	



						   $counter++;	



				



				}



		return $showTr.'<input name="counter" type="hidden" value="'.$counter.'" />';



		



			}



//////////////////////////////////////Certification Manage



function show_certif($resutlSet)



			{



					$counter = 1;



					$edit	=	"";



					$del	=	"";



				



					while($rows=mysql_fetch_array($resutlSet))



						{	



								//..........................................................



										if (($counter % 2)!=0 )



											$intColor = "";



												



										else



											$intColor = "bgcolor='#f3eeee'";



								//..........................................................



						



							$edit	=	'<a href="editcertif.php?cer_id='.$rows['cert_id'].'">Edit </a>';



							$del	=	'| <a href="certifmanage.php?action=up&nid='.$rows['cert_id'].'" onclick= "return delete_pro();">Delete</a>';



							if($rows['cert_status']=='1')



							{



							$chek='Enable';



			  				$st	=	'';



							}else{



							$chek='Disable';



							$st	=	'style="color:#FF0000"';



							}



							



							if($rows['cert_home']=='1'){	$home	=	'Yes'; }else{ $home	=	'No'; }



							if($rows['cert_hot']=='1'){	$hot	=	'Yes'; }else{ $hot	=	'No'; }



							//if($rows['allcerts']=='1'){	$all	=	'ON'; }else{ $all	=	'OFF'; }



							



							$tableName  ="tbl_cert";



							$tablefield ="cert_id";



							$tablestatus ="cert_status";



							$getvendname	=	mysql_fetch_array(mysql_query("SELECT * from tbl_vendors where ven_id='".$rows['ven_id']."'"));



							$statususer= "<div id='txtHint".$rows['cert_id']."'><a href='javascript:showHint(".$rows['cert_id'].", \"$tableName\",\"$tablefield\",\"$tablestatus\")' ".$st.">".$chek." </a></div>";	



								$showTr		.='



									<tr '.$intColor.'>



									<td class=item align="center" valign="middle"><input type="checkbox" name="chkbox'.$counter.'" id="chkbox'.$counter.'"  value="'.$rows['cert_id'].'" /></td>



    



    <td class=item style=padding-left: 0px; align="center">'.$rows['cert_name'].'</td>



	<td class=item style=padding-left: 0px; align="center">'.$getvendname['ven_name'].'</td>



	 <td class=item style=padding-left: 0px; align="center">'.$hot.'</td>



	 <td class=item style=padding-left: 0px; align="center">'.$home.'</td>



	



	<td class=item style=padding-left: 0px; align="center">'.$statususer.'</td>



    <td class=item nowrap=nowrap align="center" >'.$edit.''.$del.'</td>



</tr>';	



						   $counter++;	



				



				}



		return $showTr.'<input name="counter" type="hidden" value="'.$counter.'" />';



		



			}



//////////////////////////////////////Certification Exams



function show_allExams($resutlSet)



			{



					$counter = 1;



					$edit	=	"";



					$del	=	"";



				



					while($rows=mysql_fetch_array($resutlSet))



						{	



								//..........................................................



										if (($counter % 2)!=0 )



											$intColor = "";



												



										else



											$intColor = "bgcolor='#f3eeee'";



								//..........................................................



						



							$edit	=	'<a href="editexam.php?exm_id='.$rows['exam_id'].'">Edit </a>';

							$assign	=	'<a href="assign_user.php?exm_id='.$rows['exam_id'].'&ptype=p">PDF</a>';
							$assign	.=	' | <a href="assign_user.php?exm_id='.$rows['exam_id'].'&ptype=sp">Engine</a>';
							$assign	.=	' | <a href="assign_user.php?exm_id='.$rows['exam_id'].'&ptype=both">Both</a>';



							$del	=	'| <a href="exammanage.php?action=up&nid='.$rows['exam_id'].'" onclick= "return delete_pro();">Delete</a>';



							if($rows['exam_status']=='1')



							{



							$chek='Enable';



			  				$st	=	'';



							}else{



							$chek='Disable';



							$st	=	'style="color:#FF0000"';



							}



							



							if($rows['exam_home']=='1'){	$home	=	'Yes'; }else{ $home	=	'No'; }



							if($rows['exam_hot']=='1'){	$hot	=	'Yes'; }else{ $hot	=	'No'; }



							



							$tableName  ="tbl_exam";



							$tablefield ="exam_id";



							$tablestatus ="exam_status";

							$tableshome ="exam_home";



							$getvendname	=	mysql_fetch_array(mysql_query("SELECT * from tbl_vendors where ven_id='".$rows['ven_id']."'"));

							$getcertname	=	mysql_fetch_array(mysql_query("SELECT GROUP_CONCAT(`cert_name` SEPARATOR ' , ') AS cert_name from tbl_cert where cert_id in (".trim($rows['cert_id'],",").")"));



							$statususer= "<div id='txtHint".$rows['exam_id']."'><a href='javascript:showHint(".$rows['exam_id'].", \"$tableName\",\"$tablefield\",\"$tablestatus\")' ".$st.">".$chek." </a></div>";	

							

							$statushome= "<div id='txtHint".$rows['exam_id']."'><a href='javascript:showHome(".$rows['exam_id'].", \"$tableName\",\"$tablefield\",\"$tableshome\")' ".$st.">".$home." </a></div>";	



								$showTr		.='



									<tr '.$intColor.'>



									<td class=item align="center" valign="middle"><input type="checkbox" name="chkbox'.$counter.'" id="chkbox'.$counter.'"  value="'.$rows['exam_id'].'" /></td>



    



    <td class=item style=padding-left: 0px; align="center">'.$rows['exam_name'].'</td>



	<td class=item style=padding-left: 0px; align="center">'.$getvendname['ven_name'].'</td>

	<td class=item style=padding-left: 0px; align="center">'.$getcertname['cert_name'].'</td>



	 <td class=item style=padding-left: 0px; align="center">'.$hot.'</td>



	 <td class=item style=padding-left: 0px; align="center">'.$statushome.'</td>



	<td class=item style=padding-left: 0px; align="center">'.$statususer.'</td>

	<td class=item nowrap=nowrap align="center" >'.$assign.'</td>

    <td class=item nowrap=nowrap align="center" >'.$edit.''.$del.'</td>



</tr>';	



						   $counter++;	



				



				}



		return $showTr.'<input name="counter" type="hidden" value="'.$counter.'" />';



		



			}



///////////////////////////////////////////////////////////////



	function show_brands($resutlSet,$type)



			{



					$counter = 1;



					$edit	=	"";



					$del	=	"";



				



					while($rows=mysql_fetch_array($resutlSet))



						{	



								//..........................................................



										if (($counter % 2)!=0 )



											$intColor = "";



												



										else



											$intColor = "bgcolor='#f3eeee'";



								//..........................................................



						



							$edit	=	'<a href="editbrand.php?type='.$type.'&bottom_id='.$rows['cat_id'].'">Edit </a>';



							$del	=	'| <a href="brandmanage.php?type='.$type.'&action=up&nid='.$rows['cat_id'].'" onclick= "return delete_pro();">Delete</a>';



							



								



								$showTr		.='



									<tr '.$intColor.'>



									<td class=item align="center" valign="middle"><input type="checkbox" name="chkbox'.$counter.'" id="chkbox'.$counter.'" value="'.$rows['cat_id'].'" /></td>



    



    <td class=item style=padding-left: 0px; align="center">'.$rows['cat_name'].'</td>



	 <td class=item style=padding-left: 0px; align="center"><img src="../upload/'.$rows['cat_img'].'" width="75" height="75"></td>



	  <td class=item style=padding-left: 0px; align="center">'.$rows['br_sort'].'</td>



	<td class=item style=padding-left: 0px; align="center">'.$rows['cat_status'].'</td>



    <td class=item nowrap=nowrap align="center" >'.$edit.''.$del.'</td>



</tr>';	



						   $counter++;	



				



				}



		return $showTr.'<input name="counter" type="hidden" value="'.$counter.'" />';



		



			}



//..............................................................................................................







	//..............................................................................................................





//////////////////////////////////////////////////////////////////////////////////////////////



}



?>