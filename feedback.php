<?php
ob_start();
session_start();
include("includes/config/classDbConnection.php");

include("includes/common/classes/classmain.php");
include("includes/common/classes/classProductMain.php");
include("includes/common/classes/classcart.php");

include("includes/shoppingcart.php");
include("functions.php");

$objDBcon   =   new classDbConnection; 
$objMain	=	new classMain($objDBcon);
$objPro		=	new classProduct($objDBcon);
$objCart	=	new classCart($objDBcon);



$getPage	=	$objMain->getContent(1);



    $quer	=	$objDBcon->Sql_Query_Exec('*','website','');

	$exec	=	mysql_fetch_array($quer);
 	$copyright	=	$exec['copyright'];

	$phonephone	=	$exec['phone'];

	

	
 	
 		
	///////////////////////////////////////sign up portion//////////////////////////////////
	if(isset($_POST['emails']) && isset($_POST['fName']))
	{
	
		$sparm[1]	=	$_POST['fName'];
		$sparm[5]	=	$_POST['emails'];
		$sparm[9]	=	$_POST['comments'];
		$sparm[7]	=   $_SERVER['REMOTE_ADDR'];
		include_once("securimage/securimage.php");
		$securimage = new Securimage();
		
		$code = $_POST['captcha_code'];
		if ($securimage->check($code) == false)
		{
			$msg = "<font color='#FF0000' size='2'>Please enter values shown in image for validation (Reload image if possible)<font>";
		}
		else
		{
			$headers= 'MIME-Version: 1.0' . "\r\n";
			$headers.= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$to	=	$exec['from_email'];
			$headers.="From: ".$to;
			$contactMessage= $sparm[1]." send feedback as below:<br /><br />Email: ".$sparm[5]."<br />Comments". str_replace("\n","<br />", $sparm[9]);
			sendEmail("a4atiph@gmail.com", "Feedback from testbells.com", $contactMessage, $headers );
// 			mail("a4atiph@gmail.com", "Feedback from testbells.com", $contactMessage, $headers );
			$_POST['fName']="";
			$_POST['emails']="";
//			mail($to, "Feedback from testbells.com", $contactMessage, $headers );
			$msg = "<font color='#FF0000' size='2'>Thank you for your feedback.<font>";
		}
	}

?>
<? include("includes/header.php");

?>
<script language="JavaScript" type="text/javascript" src="js/latestJquery.js"></script>
<script language="JavaScript" type="text/javascript" src="js/user.js"></script>
              <tr>
              <?php
              	 	if($exec['menucolum']=='Left'){  
                    	include("includes/leftmenu.php");
                     } 
               ?>
                <td width="74%" valign="top">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td><table width="600" border="0" align="center" cellpadding="0" cellspacing="0">
                      <tr>
                        <td height="25" align="center" bgcolor="#C4E2FC" class="bluebold">Feedback</td>
                      </tr>
	                  <tr>
                            <td class="text"><br />
                            <form id="form1wwww" name="form1wwww" method="post" action="" onSubmit="return chekemailsignup();">
                    <table width="97%" border="0" align="center" cellpadding="0" cellspacing="0">
                      <tr>
                        <td colspan="2" align="left" valign="middle" class="vandornav"><img src="images/spacer.gif" width="1" height="1" /></td>
                      </tr>
                      <tr>
                        <td colspan="2" align="center" class="redbold" style="color:#FF0000;"><div id="txtHintemail"><? if(isset($msg)){ echo $msg; } ?></div></td>
                       </tr>
                      <tr>
                        <td height="35" align="right" valign="middle" class="text">Name:&nbsp;&nbsp;</td>
                        <td height="35" align="left" valign="middle" class="wheading"><span class="reg_bd_ul_p2">
                          <input class="required email text-input" type="text" name="fName" id="fName" value="<? if(isset($_POST['fName'])){ echo $_POST['fName']; } ?>" />
                        </span> </td>
                      </tr>
                      <!-- <tr>
                        <td height="35" align="left" valign="middle" class="text"><span class="ornold">Last Name:</span></td>
                        <td height="35" align="left" valign="middle" class="wheading"><span class="reg_bd_ul_p2">
                          <input class="required email text-input" type="text" name="lastname" id="lastname" value="<? if(isset($_POST['lastname'])){ echo $_POST['lastname']; } ?>" />
                        </span> </td>
                      </tr> -->
                      <tr>
                        <td width="37%" height="35" align="right" valign="middle" class="text">Your e-mail address:&nbsp;&nbsp;</td>
                        <td width="63%" height="35" align="left" valign="middle" class="wheading"><span class="reg_bd_ul_p2">
                          <input class="required email text-input" name="emails" type="text" id="emails" value="<? if(isset($_POST['emails'])){ echo $_POST['emails']; } ?>" />
                        </span> </td>
                      </tr>
                      <tr class="text">
                        <td colspan="2" align="left" valign="middle" class="vandornav"><img src="images/spacer.gif" width="1" height="1" /></td>
                      </tr>
                      <tr>
                        <td height="35" align="right" valign="middle" class="text">Comments:&nbsp;&nbsp;</td>
                        <td height="35" align="left" valign="middle" class="wheading"><span class="reg_bd_ul_p2">
                          <textarea name="comments" id="comments" cols="40" rows="5"></textarea>
                        </span> </td>
                      </tr>
                      <tr class="text">
                        <td height="25" colspan="2" align="left" valign="middle" class="certnav2" style="padding-left:170px;"><img id="captcha" src="securimage/securimage_show.php" alt="CAPTCHA Image" />&nbsp;&nbsp;<a href="#" onclick="document.getElementById('captcha').src = 'securimage/securimage_show.php?' + Math.random(); return false" style="text-decoration:none" class="bodtg">Reload Image</a></td>
                      </tr>
                      <tr>
                        <td height="35" align="right" valign="middle" class="text">Security Code:&nbsp;&nbsp;</td>
                        <td height="35" align="left" valign="middle" class="wheading"><span class="reg_bd_ul_p2">
                          <input type="text" name="captcha_code" size="10" maxlength="6" id="captcha_code" class="required email text-input"  />
                        </span> </td>
                      </tr>
                      
                      <tr>
                        <td height="8" colspan="2"><img src="images/spacer.gif" width="1" height="1" /></td>
                      </tr>
                      <tr>
                        <td height="35" colspan="2" align="center" valign="middle" class="wheading"><span class="text">
                          <input name="Login2" type="submit" id="Login2" value="Send" />
                        </span></td>
                      </tr>
                    </table>
                    </form>
                            <br /></td>
                      </tr>
                    </table></td>
                  </tr>
                </table></td>
                <?php
              	 	if($exec['menucolum']=='Right'){  
                    	include("includes/leftmenu.php");
                     } 
               	?>
              </tr>
              <? include("includes/footer.php");?>