   <?PHP







	//--------------------------------------------------------------------------------



	//					<<<<Creation of the Class User class>>>>	



	error_reporting(0);



	//--------------------------------------------------------------------------------



	//					<<<<Headers including area>>>>>



	//===================================================================================







	class classEvents



	{

		//--------------------------------------------------------------------------------



								// Class Properties (Class Level Variables)



		//--------------------------------------------------------------------------------



			var $objDbConn		;

			var	$rowsPerPage 	;

			var $linkPerPage	;

			var	$PageNo			; 	# Shows # of Page			

			var $PageIndex	 	;	# Shows # of Page Index				



		//..........................................................................................................//



		/*Constructor initialize all the variables*/



//-------------------------------------------------------------------------------------------//



		function classEvents($objDBcon)

		{

			$this->objDbConn	= 	$objDBcon; # Populate the Connection property with the Connection Class Object.

		}

//-------------------------------------------------------------------------------------------//
/*
		function addVendor($spram)

		{

		 $add_que = "INSERT INTO tbl_vendors(ven_name,ven_url,ven_home,ven_hot,ven_status,ven_title,ven_keywords,ven_desc,ven_date,ven_descr,allvendors,ven_upddate)VALUES('".$spram[0]."','".$spram[1]."','".$spram[4]."','".$spram[5]."','".$spram[10]."','".str_replace("'","\'",$spram[7])."','".str_replace("'","\'",$spram[8])."','".str_replace("'","\'",$spram[9])."','".date("Y-m-d")."','".str_replace("'","\'",$spram[6])."','".$spram[11]."','0000-00-00')";

		 $qryexe = mysql_query($add_que) or die(mysql_error()); 

		}
*/
		function addVendor($spram)
        {
            // Escape all fields for safety
            $ven_name     = mysql_real_escape_string($spram[0]);
            $ven_url      = mysql_real_escape_string($spram[1]);
            $ven_home     = mysql_real_escape_string($spram[4]);
            $ven_hot      = mysql_real_escape_string($spram[5]);
            $ven_status   = mysql_real_escape_string($spram[10]);
            $ven_title    = mysql_real_escape_string($spram[7]);
            $ven_keywords = mysql_real_escape_string($spram[8]);
            $ven_desc     = mysql_real_escape_string($spram[9]);
            $ven_descr    = mysql_real_escape_string($spram[6]);
            $allvendors   = mysql_real_escape_string($spram[11]);
            $youtube_links = isset($spram['youtube_links']) ? mysql_real_escape_string($spram['youtube_links']) : '[]';
            $demo_json = isset($spram['attachments']) ? mysql_real_escape_string($spram['attachments']) : '[]';
            $default_image = isset($spram['default_image']) ? mysql_real_escape_string($spram['default_image']) : '';
            
            $add_que = "
                INSERT INTO tbl_vendors
                (ven_name, ven_url, ven_home, ven_hot, ven_status, ven_title, ven_keywords, ven_desc, ven_date, ven_descr, allvendors, ven_upddate, youtube_links, demo_json, default_image)
                VALUES
                ('$ven_name', '$ven_url', '$ven_home', '$ven_hot', '$ven_status', '$ven_title', '$ven_keywords', '$ven_desc', '".date("Y-m-d")."', '$ven_descr', '$allvendors', '0000-00-00', '$youtube_links', '$demo_json', '$default_image')
            ";
        
            $qryexe = mysql_query($add_que) or die(mysql_error());
        }


		function addCertif($spram)

		{

		 $add_que = "INSERT INTO tbl_cert(	ven_id,cert_name,cert_url,cert_home,cert_hot,cert_status,cert_title,cert_keywords,cert_desc_seo,cert_date,cert_descr,allcerts,cert_upddate,demo_data)VALUES('".$spram[12]."','".$spram[0]."','".$spram[1]."','".$spram[4]."','".$spram[5]."','".$spram[10]."','".str_replace("'","\'",$spram[7])."','".str_replace("'","\'",$spram[8])."','".str_replace("'","\'",$spram[9])."','".$spram[29]."','".str_replace("'","\'",$spram[6])."','".$spram[13]."','0000-00-00','".$spram[30]."')";

		 $qryexe = mysql_query($add_que) or die(mysql_error()); 

		}

		function addCourse($spram)

		{

		 $add_que = "INSERT INTO tbl_course(name,status,created)VALUES('".$spram[0]."','".$spram[1]."','".$spram[2]."')";

		 $qryexe = mysql_query($add_que) or die(mysql_error()); 

		}

		

		function addExam($spram)

		{

		 $add_que = "INSERT INTO tbl_exam(	

		 ven_id,

		 cert_id,

		 exam_name,

		 exam_fullname,

		 exam_url,

		 exam_pri3,

		 exam_pri0,

		 both_pri,
		 engn_pri3,
		 exam_home,

		 exam_hot,

		 exam_status,

		 exam_title,

		 exam_keywords,

		 exam_desc_seo,

		 exam_date,

		 exam_descr,
		 exam_descr2,
		 exam_related_descr,

		 QA,

		 discount,

		 exam_upddate,
		 
		 whatsapp_url,
		 
		 skype_url,
		 
		 demo_images,
		 
		 youtube_links,
		 free_dump_pdf

		 )VALUES(

		 '".$spram[12]."',

		 '".$spram[13]."',

		 '".$spram[0]."',

		 '".$spram[14]."',

		 '".$spram[1]."',

		 '".$spram[2]."',

		 '".$spram[2]."',
		 '".$spram[3]."',
		 '".$spram[22]."',

		 '".$spram[4]."',

		 '".$spram[5]."',

		 '".$spram[10]."',

		 '".str_replace("'","\'",$spram[7])."',
		 '".str_replace("'","\'",$spram[39])."',
		 '".str_replace("'","\'",$spram[41])."',

		 '".str_replace("'","\'",$spram[8])."',

		 '".str_replace("'","\'",$spram[9])."',

		 '".$spram[29]."',

		 '".str_replace("'","\'",$spram[6])."',

		 '".$spram[20]."',

		 '0',

		 '0000-00-00',
		 '".$spram[30]."',
		 '".$spram[31]."',
         '".$spram[42]."',
         '".$spram['youtube_links']."',
         '".mysql_real_escape_string(isset($spram['free_dump_pdf']) ? $spram['free_dump_pdf'] : '')."'
         
         )";

		 $qryexe = mysql_query($add_que) or die(mysql_error()); 

		}

		function delete_Banner($pro_id)
		{
			mysql_query("DELETE from sliders where s_id='".$pro_id."'");
		}
		
				function delete_Banner_new($pro_id)
		{
			mysql_query("DELETE from sliders_new where s_id='".$pro_id."'");
		}
		
		function delete_course_manage($pro_id)
		{
			mysql_query("DELETE from tbl_course where id='".$pro_id."'");
		}

		

		function delete_Vendor($pro_id)

			{

				$getev	=	mysql_query("SELECT * from tbl_cert where ven_id ='".$pro_id."'");

				while($getfetch	=	mysql_fetch_array($getev)){

					mysql_query("DELETE from tbl_cert where cert_id='".$getfetch['cert_id']."'");

				}

				mysql_query("DELETE from tbl_exam where ven_id='".$pro_id."'");

				mysql_query("DELETE from tbl_vendors where ven_id='".$pro_id."'");

				

			}

		function delete_Certif($pro_id)

			{

				mysql_query("DELETE from tbl_exam where cert_id='".$pro_id."'");

				mysql_query("DELETE from tbl_cert where cert_id='".$pro_id."'");

				

			}

		function delete_exam($pro_id)

			{

			//	echo $pro_id;exit();

				//echo "SELECT * from tbl_exam where exam_id ='".$pro_id."'";exit();

				$getev	=	mysql_query("SELECT * from tbl_exam where exam_id ='".$pro_id."'");

				$getfetch	=	mysql_fetch_array($getev);

				if($getfetch['exam_demo']!=''){

					unlink("../devil/demo/".$getfetch['exam_demo']);

				}

				if($getfetch['exam_full']!=''){

					unlink("../devil/full/".$getfetch['exam_full']);

				}

				mysql_query("DELETE from tbl_exam where exam_id ='".$pro_id."'");

				

			}

		function get_vendor_by_id($arr)

			{

			 	$strQury="Select * from tbl_vendors  where ven_id ='".$arr[0]."'";

				$rsResult = $this->objDbConn->Dml_Query_Parser($strQury);

				return $rsResult;

			}

		function get_certif_by_id($arr)

			{

			 	$strQury="Select * from tbl_cert  where cert_id='".$arr[0]."'";

				$rsResult = $this->objDbConn->Dml_Query_Parser($strQury);

				return $rsResult;

			}

		function get_exam_by_id($arr)

			{

			 	$strQury="Select * from tbl_exam  where exam_id='".$arr[0]."'";

				$rsResult = $this->objDbConn->Dml_Query_Parser($strQury);

				return $rsResult;

			}

//-------------------------------------------------------------------------------------------//
        function updateVendor($spram)
        {
            // Escape all user inputs to prevent SQL issues
            $ven_name     = mysql_real_escape_string($spram[1]);
            $ven_url      = mysql_real_escape_string($spram[2]);
            $ven_home     = mysql_real_escape_string($spram[5]);
            $ven_hot      = mysql_real_escape_string($spram[6]);
            $ven_descr    = mysql_real_escape_string($spram[7]);
            $ven_title    = mysql_real_escape_string($spram[8]);
            $ven_keywords = mysql_real_escape_string($spram[9]);
            $ven_desc     = mysql_real_escape_string($spram[10]);
            $ven_status   = mysql_real_escape_string($spram[11]);
            $youtube_links = isset($spram['youtube_links']) ? mysql_real_escape_string($spram['youtube_links']) : '[]';
            $demo_json = isset($spram['attachments']) ? mysql_real_escape_string($spram['attachments']) : '[]';
            $default_image = isset($spram['default_image']) ? mysql_real_escape_string($spram['default_image']) : '';


            $add_que = "
                UPDATE tbl_vendors 
                SET 
                    ven_name       = '$ven_name',
                    ven_url        = '$ven_url',
                    ven_home       = '$ven_home',
                    ven_hot        = '$ven_hot',
                    ven_descr      = '$ven_descr',
                    ven_title      = '$ven_title',
                    ven_keywords   = '$ven_keywords',
                    ven_desc       = '$ven_desc',
                    ven_status     = '$ven_status',
                    youtube_links  = '$youtube_links',
                    demo_json      = '$demo_json',
                    default_image      = '$default_image',
                    ven_upddate    = '" . date("Y-m-d") . "'
                WHERE ven_id = '" . mysql_real_escape_string($spram[0]) . "'
            ";
        
            $qryexe = mysql_query($add_que) or die(mysql_error());
        }

/*
		function updateVendor($spram)

		{

		 $add_que = "UPDATE tbl_vendors set ven_name='".$spram[1]."',ven_url='".$spram[2]."',ven_home='".$spram[5]."',ven_hot='".$spram[6]."',ven_descr='".str_replace("'","\'",$spram[7])."',ven_title='".str_replace("'","\'",$spram[8])."',ven_keywords='".str_replace("'","\'",$spram[9])."',ven_desc='".str_replace("'","\'",$spram[10])."',ven_status='".$spram[11]."',ven_upddate='".date("Y-m-d")."' where ven_id='".$spram[0]."'";

		 $qryexe = mysql_query($add_que) or die(mysql_error()); 

		}
*/
//-------------------------------------------------------------------------------------------//

		function updateCERT($spram)

		{

			$add_que = "UPDATE tbl_cert set ven_id='".$spram[12]."',cert_name='".$spram[1]."',cert_url='".$spram[2]."',cert_home='".$spram[5]."',cert_hot='".$spram[6]."',cert_descr='".str_replace("'","\'",$spram[7])."',cert_title='".str_replace("'","\'",$spram[8])."',cert_keywords='".str_replace("'","\'",$spram[9])."',cert_desc_seo='".str_replace("'","\'",$spram[10])."',cert_status='".$spram[11]."',allcerts='".$spram[13]."',cert_date='".$spram[29]."',demo_data='".$spram[30]."' where cert_id ='".$spram[0]."'";
	
			$qryexe = mysql_query($add_que) or die(mysql_error()); 

		}

		function updateCourse($spram)

		{
			$add_que = "UPDATE tbl_course set name='".$spram[1]."',status='".$spram[2]."' where id ='".$spram[0]."'";
	
			$qryexe = mysql_query($add_que) or die(mysql_error()); 
			return $qryexe;

		}

				

		function updateExam($spram)

				{

				 $add_que = "UPDATE tbl_exam set ven_id='".$spram[12]."',cert_id='".$spram[13]."',exam_fullname='".$spram[14]."',exam_name='".$spram[1]."',labName='".$spram[32]."',exam_url='".$spram[2]."',exam_pri3='".$spram[3]."',exam_pri0='".$spram[3]."',both_pri='".$spram[4]."',engn_pri3='".$spram[33]."',QA='".$spram[20]."',exam_home='".$spram[5]."',exam_hot='".$spram[6]."',exam_descr='".str_replace("'","\'",$spram[7])."',exam_descr2='".str_replace("'","\'",$spram[39])."',exam_related_descr='".str_replace("'","\'",$spram[41])."'

				 ,exam_tests='".str_replace("'","\'",$spram[77])."',exam_title='".str_replace("'","\'",$spram[8])."',exam_keywords='".str_replace("'","\'",$spram[9])."',exam_desc_seo='".str_replace("'","\'",$spram[10])."',exam_status='".$spram[11]."',exam_date='".$spram[29]."',telegram_url='".$spram[38]."',whatsapp_url='".$spram[30]."',skype_url='".$spram[31]."',video_code='".$spram[37]."',course_image='".$spram[36]."',youtube_links='".$spram['youtube_links']."',free_dump_pdf='".mysql_real_escape_string(isset($spram['free_dump_pdf']) ? $spram['free_dump_pdf'] : '')."' where exam_id ='".$spram[0]."'";

				 $qryexe = mysql_query($add_que) or die(mysql_error()); 
				 /* var_dump($add_que);exit; */

				}

//-------------------------------------------------------------------------------------------//		

		function getAllphotos($eventId){

		$get_all_Com	=	$this->objDbConn->Sql_Query_Exec('*','tbl_eventphoto','event_id='.$eventId.' order by `photo_id` DESC');

		return $get_all_Com;

		}

//-------------------------------------------------------------------------------------------//		

		function Addeventphoto($event){

		$event[2]	=	upload_event_img_user($event[2]);

		mysql_query("INSERT INTO tbl_eventphoto(event_id,photo_name,uid)VALUES('".$event[1]."','".$event[2]."','".$event[3]."')");

		$resl	=	'Photo has been added successfully.';

		return $resl;

		}

//-------------------------------------------------------------------------------------------//		

		

		function Addeventrsvp($event){

		$get_rsvp	=	$this->objDbConn->Sql_Query_Exec('*','tbl_eventguest','gue_uid='.$event[3].' and gue_eid='.$event[1].'');

		$total	=	mysql_num_rows($get_rsvp);

		if($total=='0'){

		mysql_query("INSERT INTO tbl_eventguest(gue_uid,gue_eid,gue_answer)VALUES('".$event[3]."','".$event[1]."','".$event[2]."')");

		$resl	=	'RSVP has been added successfully.';

		}else{

		mysql_query("UPDATE tbl_eventguest set gue_answer='".$event[2]."' where gue_uid='".$event[3]."' and gue_eid='".$event[1]."'");

		$resl	=	'RSVP has been updated successfully.';

		}

		return $resl;

		}

//-------------------------------------------------------------------------------------------//		

		function Deleventphoto($photoId){

		$get_all_Com	=	mysql_fetch_array($this->objDbConn->Sql_Query_Exec('*','tbl_eventphoto','photo_id='.$photoId.''));

		unlink("../upload/".$get_all_Com['photo_name']);

		unlink("../upload/thumbs/".$get_all_Com['photo_name']);

		$del	=	"DELETE from tbl_eventphoto where photo_id='".$photoId."'";

		mysql_query($del);

		$resl	=	'Photo has been deleted successfully.';

		return $resl;

		}

//-------------------------------------------------------------------------------------------//

function fillComboCategory($intValue = 0)

			{

			$txtSelected = "";	//Set the option to Selected

			$qry = mysql_query("select * from tbl_vendors where ven_status!='0' ORDER BY ven_name");

			$txtCombo = "<select name=\"cmb_cate\" id=\"cmb_cate\" class=\"csstxtfield2\">";

			$txtCombo .= "<option value='' >None</option>";	

					while ($row1 = mysql_fetch_array($qry)) 

					{

						if($intValue == $row1["ven_id"])

								{

									$txtSelected = "selected";

									$txtCombo .= "<option value=\"".$row1['ven_id']."\" $txtSelected >".$row1['ven_name']."</option>";

								}

								else

								{

									$txtSelected = "";

									$txtCombo .= "<option value=\"".$row1['ven_id']."\"  >".$row1['ven_name']."</option>";

								}

						}



					return $txtCombo."</select> " ;

				}

////////////////////////////////////////////////////////////////////////////////////////////////

//-------------------------------------------------------------------------------------------//

function fillComboCategory_exam($intValue = 0)

			{

			$txtSelected = "";	//Set the option to Selected

			$qry = mysql_query("select * from tbl_vendors where ven_status!='0' ORDER BY ven_name");

			$txtCombo = "<select name=\"cmb_cate\" id=\"cmb_cate\" class=\"csstxtfield2\" onChange='showsubcate(this.value)'  style='width:200px;'>";

			$txtCombo .= "<option value='' >None</option>";	

					while ($row1 = mysql_fetch_array($qry)) 

					{

						if($intValue == $row1["ven_id"])

								{

									$txtSelected = "selected";

									$txtCombo .= "<option value=\"".$row1['ven_id']."\" $txtSelected >".$row1['ven_name']."</option>";

								}

								else

								{

									$txtSelected = "";

									$txtCombo .= "<option value=\"".$row1['ven_id']."\"  >".$row1['ven_name']."</option>";

								}

						}



					return $txtCombo."</select> " ;

				}

				

function fillComboSubCategory($intValue = 0,$cateID)

			{

							

			$txtSelected = "";	//Set the option to Selected

			$qry = mysql_query("select * from tbl_cert  where ven_id=".$cateID."  ORDER BY cert_name");

			if(!empty($intValue))

			{

				$selArr = explode(",",$intValue);

			}

			$txtCombo = "<select name='cert_id' class='csstxtfield2'>";

				while ($row1 = mysql_fetch_array($qry))

				{

						$checked = '';

						if(is_array($selArr) && in_array($row1["cert_id"], $selArr)!==false)

						{

							$checked = 'selected="selected"';

						}

						$txtCombo .= "<option $checked value='".$row1['cert_id']."'>".$row1['cert_name']."</option>";

					}

				$txtCombo .= "</select>";	

					return $txtCombo;

				}

			function fillComboExam($SubcateID = 0,$intValue = 0,$cateID = 0)

			{

							

			$txtSelected = "";	//Set the option to Selected

/* 			$qry = mysql_query("select * from tbl_exam  where cert_id=".$SubcateID."  ORDER BY exam_name"); */
			$qry = mysql_query("select * from tbl_exam  where exam_status!='0' ORDER BY exam_name");

			

			$txtCombo = "<select name='group_id' class='csstxtfield2' id='group_id'>";

				while ($row1 = mysql_fetch_array($qry))

				{

						 
						$txtCombo .= "<option value='".$row1['exam_id']."'>".$row1['exam_name']."</option>";

					}

				$txtCombo .= "</select>";	

					return $txtCombo;

				}


////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



}



?> 