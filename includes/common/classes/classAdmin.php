<?PHP
require_once dirname(dirname(__FILE__)) . '/sanitize.php';
require_once dirname(dirname(__FILE__)) . '/system_log.php';

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

			

			$sqlQuery = "UPDATE tbladmin SET admin_password='".md5($spram[1])."' where admin_id='".san_int($_SESSION['adminId'])."'";

			 							

			//Update the User password 

			 $this->objDbConn->Dml_Query_Parser($sqlQuery);								

			syslog_write('Admin Users', 'ADMIN_PASSWORD_CHANGED', syslog_who().' changed their own password.', array('entity_type' => 'admin user', 'entity_id' => isset($_SESSION['adminId']) ? (int)$_SESSION['adminId'] : 0));
			//Close the connection with the DB.

			 $this->objDbConn->CloseConnection();

		}

		

		function changeContactUs($spram)

		{

			

			$sqlQuery = "UPDATE website SET contactus_email='".db_esc($spram[1])."', from_email='".db_esc($spram[2])."'";

			 							

			//Update the User password 

			 $syslogBefore = syslog_snapshot_one('website');
			 $this->objDbConn->Dml_Query_Parser($sqlQuery);
			 syslog_log_setting('SETTINGS_UPDATED', 'Contact and from email addresses', $syslogBefore, syslog_snapshot_one('website'), 'website');								

			//Close the connection with the DB.

			 $this->objDbConn->CloseConnection();

			

		}

		

		

			function changeTitle($spram)

		{

			

			$sqlQuery = "UPDATE website SET title='".db_esc($spram[1])."'";

								

			//Update the User password 

			 $syslogBefore = syslog_snapshot_one('website');
			 $this->objDbConn->Dml_Query_Parser($sqlQuery);
			 syslog_log_setting('SETTINGS_UPDATED', 'Website title', $syslogBefore, syslog_snapshot_one('website'), 'website');								

			//Close the connection with the DB.

			 $this->objDbConn->CloseConnection();

		}





		function changeCopyright($spram)

		{

			

			$sqlQuery = "UPDATE website SET copyright='".db_esc($spram[1])."'";

								

			//Update the User password 

			 $syslogBefore = syslog_snapshot_one('website');
			 $this->objDbConn->Dml_Query_Parser($sqlQuery);
			 syslog_log_setting('SETTINGS_UPDATED', 'Website copyright', $syslogBefore, syslog_snapshot_one('website'), 'website');								

			//Close the connection with the DB.

			 $this->objDbConn->CloseConnection();

		}

		

		function changephone($spram)

		{

			

			$sqlQuery = "UPDATE website SET phone='".db_esc($spram[1])."',timming='".db_esc($spram[2])."'";

								

			//Update the User password 

			 $syslogBefore = syslog_snapshot_one('website');
			 $this->objDbConn->Dml_Query_Parser($sqlQuery);
			 syslog_log_setting('SETTINGS_UPDATED', 'Phone and timing', $syslogBefore, syslog_snapshot_one('website'), 'website');								

			//Close the connection with the DB.

			 $this->objDbConn->CloseConnection();

		}

		function changebanner($spram)

		{

			

			$sqlQuery = "UPDATE website SET top_banner='".db_esc($spram[1])."'";

								

			//Update the User password 

			 $syslogBefore = syslog_snapshot_one('website');
			 $this->objDbConn->Dml_Query_Parser($sqlQuery);
			 syslog_log_setting('SETTINGS_UPDATED', 'Top banner', $syslogBefore, syslog_snapshot_one('website'), 'website');								

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

		$rsName = 	$this->objDbConn->Sql_Query_Exec("count(*) as cnt","tbladmin","admin_name='".db_esc($strUser)."' and admin_password='".md5($strPassword)."' and admin_status='Active'");

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

		

		$strSql="SELECT *  FROM tbladmin WHERE admin_name='".db_esc($strUsername)."'";

		$rs=$this->objDbConn->Dml_Query_Parser($strSql);	

		$row	=	mysql_fetch_array($rs);

		return $adm_id	=	$row['admin_id'];

		

		}

		////////////////////////////////////////////////////////////////////

		function checkAdminDuplication($login)



			{					

				$qry = "select  * from tbladmin where admin_name  = '".db_esc($login)."'"; 

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

											admin_name,

											admin_role

											)

											VALUES

											(

											'".db_esc($spram[1])."',

											'".db_esc($spram[2])."',

											'".db_esc($spram[3])."',

											'".md5($spram[4])."',

											'".db_esc($spram[5])."',

											'".db_esc($spram[6])."',

											'".db_esc($spram[7])."'

											)";



			mysql_query($add_que);

			$userid = mysql_insert_id();
			syslog_log_created('Admin Users', 'ADMIN_USER_CREATED', 'admin user', $userid, $spram[6], 'Role: '.$spram[7].', status: '.$spram[5].'.');

			return $userid;



		}

		//////////////////////////////////////Set Admin Permissions///////////////////////////////////////

		function addAdminPermission($adminId)

		{
			if (function_exists('cms2_apply_default_permissions_for_admin')) {
				$role = 'admin';
				$adminRoleResult = mysql_query("SELECT admin_role FROM tbladmin WHERE admin_id='".(int)$adminId."'");
				if ($adminRoleResult) {
					$adminRoleRow = mysql_fetch_array($adminRoleResult);
					if (isset($adminRoleRow['admin_role']) && $adminRoleRow['admin_role'] != '') {
						$role = $adminRoleRow['admin_role'];
					}
				}
				cms2_apply_default_permissions_for_admin($adminId, $role);
				return;
			}

			$qry = "select * from  tblmodules";

			$res = mysql_query($qry);

			$i=0;

			while($rows	=	mysql_fetch_array($res))

			{ 

			  $add_que = "INSERT INTO `tblpermissions` (`AdminID`,`ModuleID`,`Read`,`Write`,`Edit`,`Delete`) VALUES (".san_int($adminId).",".san_int($rows['ModuleID']).",1,1,1,1)"; 

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

							

							if(permission("Admin Users Module","Edit")==TRUE)

							{

							if($rows['admin_id']=='1')

							{

							$edit	=	'<a href="editadmin.php?nid='.$rows['admin_id'].'">Edit </a>&nbsp;&nbsp;&nbsp;&nbsp;';

							}else{

							$edit	=	'<a href="editadmin.php?nid='.$rows['admin_id'].'">Edit </a> | <a href="permission.php?nid='.$rows['admin_id'].'">Set Permissions </a>';}

							}

							

							if(permission("Admin Users Module","Delete")==TRUE)

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

	 <td align="center" class=item>'.(isset($rows['admin_role']) ? cms2_role_label($rows['admin_role']) : 'Admin').'</td>

	 <td align="center" class=item nowrap=nowrap>'.$edit.''.$del.' </td>

  </tr>';	

				$counter++;

								}

			return $showTr.'<input name="counter" type="hidden" value="'.$counter.'" />';

				}

	function getAdminEdit($spram)

		{					

				$qry = "select * from tbladmin where admin_id = '".san_int($spram[0])."'"; 

				$qryexe =  mysql_query($qry); 

				return $qryexe;

		}

			

	function UpdateAdmin($spram)

	  {

			$syslogBefore = syslog_snapshot('tbladmin', 'admin_id', san_int($spram[0]));
			$strQury	=	"update tbladmin set 



													admin_fname			 = '".db_esc($spram[1])."',

													admin_lname  	 	 = '".db_esc($spram[2])."',

													admin_email 		 = '".db_esc($spram[3])."',

													admin_password 		 = '".db_esc($spram[5])."',	

													admin_status 	     = '".db_esc($spram[4])."',

													admin_role 	     	 = '".db_esc($spram[6])."'



													where  admin_id  	 = '".san_int($spram[0])."'";



			mysql_query($strQury);
			$syslogAfter = syslog_snapshot('tbladmin', 'admin_id', san_int($spram[0]));
			syslog_log_updated('Admin Users', 'ADMIN_USER_UPDATED', 'admin user', san_int($spram[0]), syslog_pick($syslogAfter, 'admin_name'), $syslogBefore, $syslogAfter, 'tbladmin');



	  }	

	  

	  function deleteAdmin($spram)

			 {

					$syslogRow = syslog_snapshot('tbladmin', 'admin_id', san_int($spram));
					$delpermissions	=	mysql_query("DELETE FROM tblpermissions WHERE AdminID='".san_int($spram)."'");

					

					$strSql	=	'DELETE FROM tbladmin WHERE admin_id="'.san_int($spram).'"';

					mysql_query($strSql);
					if ($syslogRow) {
						syslog_log_deleted('Admin Users', 'ADMIN_USER_DELETED', 'admin user', san_int($spram), syslog_pick($syslogRow, 'admin_name'), 'Role was: '.syslog_pick($syslogRow, 'admin_role', '-').'.');
					}

       		 }

			 

		

		function changesalesoption($spram)

		{

			$sqlQuery1 = "UPDATE website SET title='".db_esc($spram['title'])."', website_url ='".db_esc($spram['website_url'])."', admin_title ='".db_esc($spram['admin_title'])."', course_update ='".db_esc($spram['course_update'])."', copyright='".db_esc($spram['copyright'])."', contactus_email='".db_esc($spram['contactus_email'])."', paypalid='".db_esc($spram['paypalid'])."', from_email='".db_esc($spram['from_email'])."'";

			$syslogBefore = syslog_snapshot_one('website');
			$this->objDbConn->Dml_Query_Parser($sqlQuery1);
			syslog_log_setting('SETTINGS_UPDATED', 'Website configuration', $syslogBefore, syslog_snapshot_one('website'), 'website');							

			//Close the connection with the DB.

//			$this->objDbConn->CloseConnection();

		}	  	  

//-------------------------------------------------------------------------------------------//



		

	}

?>
