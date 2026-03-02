<?PHP

/*



	

*/

	//--------------------------------------------------------------------------------

	//					<<<<Creation of the Class Admin class>>>>	

	error_reporting(15);

	//--------------------------------------------------------------------------------

	//					<<<<Headers including area>>>>>

	//===================================================================================



	class classAdmin

	{

		//--------------------------------------------------------------------------------

								// Class Properties (Class Level Variables)

		//--------------------------------------------------------------------------------

			var $objDbConn		;

		//..........................................................................................................



		/*Constructor initialize all the variables*/

		

		function classAdmin($objDbConn)

		{

			$this->objDbConn	= 	$objDbConn; # Populate the Connection property with the Connection Class Object.

		}



		/*

			Input: Username 

			Output: Password

			Purpose: To Get the admin password

		*/

		//function getAdminPassword($strUsername)

//		{

//			//=======================================================================

//			//					<<<<<LOCAL VARIALES AREA>>>>>

//			//This Variable is used to store the Query Result

//

//			$strPassword	=	"";//store password variable in it.

//			$sqlResult 		=	"";//store the result set regarding to the admin password

//			//=======================================================================

//			//					<<<<<LOCAL Function Logic>>>>>

//			//This Variable is used to store the Query Result

//			

//			$sqlResult 		= $this->objDbConn->Sql_Query_Exec("adm_password ", "tbladmin ", "adm_login= '". $strUsername."'");

//

//			while ($row1	=	mysql_fetch_object($sqlResult))

//			{

//				$strPassword=	$row1->adm_password;

//			}

//			return $strPassword;

//

//		}



		/*

			Input: Username, Old Password, New Password

			Output: None

			Purpose: To change the password

		*/

		function changePassword($spram)

		{

			

			$sqlQuery = "UPDATE tbladmin SET admin_password='".md5($spram[1])."' where admin_id='".$_SESSION['adminId']."'";

			 							

			//Update the User password 

			 $this->objDbConn->Dml_Query_Parser($sqlQuery);								

			//Close the connection with the DB.

			 $this->objDbConn->CloseConnection();

		}

		

		function changeContactUs($spram)

		{

			

			$sqlQuery = "UPDATE website SET contactus_email='".$spram[1]."', from_email='".$spram[2]."'";

			 							

			//Update the User password 

			 $this->objDbConn->Dml_Query_Parser($sqlQuery);								

			//Close the connection with the DB.

			 $this->objDbConn->CloseConnection();

			

		}

		

		

			function changeTitle($spram)

		{

			

			$sqlQuery = "UPDATE website SET title='".$spram[1]."'";

								

			//Update the User password 

			 $this->objDbConn->Dml_Query_Parser($sqlQuery);								

			//Close the connection with the DB.

			 $this->objDbConn->CloseConnection();

		}





		function changeCopyright($spram)

		{

			

			$sqlQuery = "UPDATE website SET copyright='".$spram[1]."'";

								

			//Update the User password 

			 $this->objDbConn->Dml_Query_Parser($sqlQuery);								

			//Close the connection with the DB.

			 $this->objDbConn->CloseConnection();

		}

		

		function changephone($spram)

		{

			

			$sqlQuery = "UPDATE website SET phone='".$spram[1]."',timming='".$spram[2]."'";

								

			//Update the User password 

			 $this->objDbConn->Dml_Query_Parser($sqlQuery);								

			//Close the connection with the DB.

			 $this->objDbConn->CloseConnection();

		}

		function changebanner($spram)

		{

			

			$sqlQuery = "UPDATE website SET top_banner='".$spram[1]."'";

								

			//Update the User password 

			 $this->objDbConn->Dml_Query_Parser($sqlQuery);								

			//Close the connection with the DB.

			 $this->objDbConn->CloseConnection();

		}

		

		



		//Check for the presence of old pwd.

		// This Function takes one parameters.

		// 1:	$txtOldPwd --> Old Password. 

		//	Returns True or False

		//function checkOldPwd($txtUserName,$txtOldPwd)

//		{

//			//=======================================================================

//			//					<<<<<LOCAL VARIALES AREA>>>>>

//			//This Variable is used to store the Query Result

//			$sqlResult   = "";

//			//This variable is used to store number of rows in a result set

//			$nNum_rows   = 0;

//			//=======================================================================

//			//					<<<<<LOCAL LOGIC AREA>>>>>

//		

//			//Get Count From Db to Check the Duplication Exists or not

//			$sqlResult = $this->objDbConn->Sql_Query_Exec("adm_password ",

//								"tbladmin ", " adm_password = '". md5($txtOldPwd)."' AND adm_login = '".$txtUserName."'	");					  

//	

//			//Get the # of rows.

//			$nNum_rows = mysql_num_rows($sqlResult);

//

//			//If there are no records in the result

//			if($nNum_rows == 0)

//				return false;

//			else

//				return true;

//		}# End of checkOldPwd Function

	//-----------------------------------------------------------------------------------

		/*

			Input: User Email Address, User Password

			Output: Returns true in case of no duplication, Returns false in case of duplication

			Purpose: Purpose of this function is to check the User Email duplication

		*/

		function boolValidateAdmin($strUser,$strPassword)

		{

		$ncount	=	0;

		$rsName = 	$this->objDbConn->Sql_Query_Exec("count(*) as cnt","tbladmin","admin_name='".$strUser."' and admin_password='".md5($strPassword)."' and admin_status='Active'");

		while ($row1	=	mysql_fetch_object($rsName))

			{

			$ncount	=	$row1->cnt;

			}

			if ($ncount>0)	

				return true;

		else 

				return false;

		}





		/*

			Input: Username, Password

			Output: Returns True in case of password match, 

					Returns False in case of password not match.

			Purpose: To change the password

		*/

		function getId($strUsername)

		{

		

		$strSql="SELECT *  FROM tbladmin WHERE admin_name='".$strUsername."'";

		$rs=$this->objDbConn->Dml_Query_Parser($strSql);	

		$row	=	mysql_fetch_array($rs);

		return $adm_id	=	$row['admin_id'];

		

		}

		////////////////////////////////////////////////////////////////////

		function checkAdminDuplication($login)



			{					

				$qry = "select  * from tbladmin where admin_name  = '".$login."'"; 

				$qryexe =  mysql_query($qry); 

				if(mysql_num_rows($qryexe)>0)	

				return false;

				else

				return true;



			}

		//////////////////////////////////////////////////////////////////////////

		function addAdmin($spram)

		{

		 $add_que = "INSERT INTO tbladmin

		  								   (

										    admin_fname,

										    admin_lname,

											admin_email,

											admin_password,

											admin_status,

 											admin_name

											)

											VALUES

											(

											'".$spram[1]."',

											'".$spram[2]."',

											'".$spram[3]."',

											'".md5($spram[4])."',

											'".$spram[5]."',

											'".$spram[6]."'

											)";



			mysql_query($add_que);

			$userid = mysql_insert_id();

			return $userid;



		}

		//////////////////////////////////////Set Admin Permissions///////////////////////////////////////

		function addAdminPermission($adminId)

		{

			$qry = "select * from  tblmodules";

			$res = mysql_query($qry);

			$i=0;

			while($rows	=	mysql_fetch_array($res))

			{ 

			  $add_que = "INSERT INTO `tblpermissions` (`AdminID`,`ModuleID`,`Read`,`Write`,`Edit`,`Delete`) VALUES (".$adminId.",".$rows['ModuleID'].",1,1,1,1)"; 

			mysql_query($add_que);

			}

		}

		/////////////////////////////////////////////////////////////////////////////

		function getAdmin($spram)

				{

					$showTr 	=	"";

					$counter	=	"";

					$edit	=	"";

					$del	=	"";

					

					while($rows=mysql_fetch_array($spram))

				{	

										if (($counter % 2)!=0 )

											$intColor = "";

										else

											$intColor = "bgcolor='#f3eeee'";

							

							if(permission("Admin User Management","Edit")==TRUE)

							{

							if($rows['admin_id']=='1')

							{

							$edit	=	'<a href="editadmin.php?nid='.$rows['admin_id'].'">Edit </a>&nbsp;&nbsp;&nbsp;&nbsp;';

							}else{

							$edit	=	'<a href="editadmin.php?nid='.$rows['admin_id'].'">Edit </a> | <a href="permission.php?nid='.$rows['admin_id'].'">Set Permissions </a>';}

							}

							

							if(permission("Admin User Management","Delete")==TRUE)

							{

							if($rows['admin_id']=='1')

							{

							$del	=	'&nbsp;&nbsp;&nbsp;';

							}else{

							$del	=	'| <a href="manageadmin.php?action=up&nid='.$rows['admin_id'].'">Delete</a>';

							}

							}

									$showTr		.='<tr '.$intColor.'>

									<td class=item align="center" valign="middle">

	<input type="checkbox" name="chkbox'.$counter.'" id="chkbox'.$counter.'" value="'.$rows['admin_id'].'" /></td>

    <td width=5 align="center" class=item style=padding-center: 0px;>'.$rows['admin_fname'].' '.$rows['admin_lname'].'</td>

    <td class=item align="center" style=padding-center: 0px;>'.$rows['admin_email'].'</td>

    <td align="center" class=item>'.$rows['admin_name'].'</td>

	 <td align="center" class=item>'.$rows['admin_status'].'</td>

	 <td align="center" class=item nowrap=nowrap>'.$edit.''.$del.' </td>

  </tr>';	

				$counter++;

								}

			return $showTr.'<input name="counter" type="hidden" value="'.$counter.'" />';

				}

	function getAdminEdit($spram)

		{					

				$qry = "select * from tbladmin where admin_id = '".$spram[0]."'"; 

				$qryexe =  mysql_query($qry); 

				return $qryexe;

		}

			

	function UpdateAdmin($spram)

	  {

			$strQury	=	"update tbladmin set 



													admin_fname			 = '".$spram[1]."',

													admin_lname  	 	 = '".$spram[2]."',

													admin_email 		 = '".$spram[3]."',

													admin_password 		 = '".$spram[5]."',	

													admin_status 	     = '".$spram[4]."'																																											



													where  admin_id  	 = '".$spram[0]."'";



			mysql_query($strQury);



	  }	

	  

	  function deleteAdmin($spram)

			 {

					$delpermissions	=	mysql_query("DELETE FROM tblpermissions WHERE AdminID='".$spram."'");

					

					$strSql	=	'DELETE FROM tbladmin WHERE admin_id="'.$spram.'"';

					mysql_query($strSql);

       		 }

			 

		

		function changesalesoption($spram)

		{

			$sqlQuery1 = "UPDATE website SET title='".$spram['title']."', website_url ='".$spram['website_url']."', admin_title ='".$spram['admin_title']."', course_update ='".$spram['course_update']."', copyright='".$spram['copyright']."', contactus_email='".$spram['contactus_email']."', paypalid='".$spram['paypalid']."', from_email='".$spram['from_email']."'";

			$this->objDbConn->Dml_Query_Parser($sqlQuery1);							

			//Close the connection with the DB.

//			$this->objDbConn->CloseConnection();

		}	  	  

//-------------------------------------------------------------------------------------------//



		

	}

?>