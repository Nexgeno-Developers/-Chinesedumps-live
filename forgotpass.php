<?php
require_once __DIR__ . '/includes/config/load_secrets.php';
ob_start();
session_start();
include("includes/config/classDbConnection.php");

include("includes/common/classes/classmain.php");
include("includes/common/classes/classProductMain.php");
include("includes/common/classes/classcart.php");
include("includes/common/classes/classUser.php");
include("functions.php");
include("includes/shoppingcart.php");

$objDBcon   =   new classDbConnection; 
$objMain	=	new classMain($objDBcon);
$objPro		=	new classProduct($objDBcon);
$objCart	=	new classCart($objDBcon);
$objUser	=	new classUser($objDBcon);

	$getPage	=	$objMain->getContent(6);

    $quer	=	$objDBcon->Sql_Query_Exec('*','website','');
	$exec	=	mysql_fetch_array($quer);
	
	$copyright	=	$exec['copyright'];
	$from_email	=	$exec['from_email'];
	
	
	
if(isset($_POST['Send']))
{
$spram[10]	=	$_POST['email'];

$errors = [];
if (!isset($_POST['g-recaptcha-response']) || empty($_POST['g-recaptcha-response'])) {
    $errors[] = "Please fill reCAPTCHA.";
}

$sql	= "select * from tbl_user where user_email='".$spram[10]."'";
$rs		= mysql_query($sql);
$num=mysql_num_rows($rs);

if (empty($errors) && count($errors) == 0) {
if($num==1)
{

$arryUser=mysql_fetch_array($rs);

$name	    = $arryUser['user_fname'].' '.$arryUser['user_lname'];
$link		= $base_path;	
	
	
 $subject 	= "Your password for ChineseDumps Account. Thanks!";
     
  $spram[1]	=	$arryUser['user_fname'];
  $spram[2]	=	$arryUser['user_lname'];
  $spram[3]	=	$arryUser['user_password'];
  $spram[5]	=	$arryUser['user_email'];
  
  forgotemail($spram,$from_email);
  
  
$Errmg = "<span>Password sent to your Email Address</span>";
}
else
{
$Errmg = "<span >Email address is not valid</span>";
}

} else {
    $Errmg = "<span >Please fill reCAPTCHA. </span>";
}

}


$firstlink	=	' '.$getPage[1];

?>

<? include("includes/header.php");?>

<style>
   .exam-group-box input
    {
        background: #3C85BA0D;
    border: 1px solid rgb(60 133 186 / 55%) !important;
    box-shadow: none !important;
    border-radius: 15px;
    }
</style>
<div class="content-box">
        <div class="exam-group-box">
            <div class="max-width">
				<h4 class="center-heading center-heading02"><span style=""><?php echo $getPage[1]; ?></span></h4>
<div class="black-heading"><?php echo $getPage[2]; ?> </div>
<p><form id="form1" name="form1" method="post" action="">
  
  <table width="544" border="0">
   
    <tr>
      <td colspan="2"  align="center" class="redbold forgot_message"><? if(isset($Errmg)){ echo $Errmg; }?></td>
	 
    </tr>
    
    
    <tr>
      <td width="249" height="33" align="right" class="text" style="padding-right:10px;">Email : </td>
      <td width="285" align="left"><input type="email" class="input-field" name="email" id="email" placeholder="Email..." required /></td>
     
    </tr>
    
    <tr>
        <td width="249" height="33" align="right" class="text" style="padding-right:10px;"></td>
        <td width="285" align="left" style="padding-top:15px;">
            <div class="g-recaptcha" data-sitekey="<?php echo htmlspecialchars((string)secret('RECAPTCHA_SITE_KEY_DEFAULT', ''), ENT_QUOTES); ?>"></div>
            <input type="hidden" name="recaptcha" data-rule-recaptcha="true">
        </td>


    </tr>
    
    <tr>
      <td height="33">&nbsp;</td>
      <td  align="left"><input type="submit" name="billi" id="billi" class="orange-button"  /><input type="hidden" name="Send"  id="Send"  value="Send" /> &nbsp;&nbsp;</td>
      
    </tr>
  
  </table>
 
</form>
	<br />
	</p>
                
            </div>
        </div>
	</div>
<? include("includes/footer.php");?>
