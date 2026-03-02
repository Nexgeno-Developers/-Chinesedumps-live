<?php
ob_start();
session_start();
include("includes/config/classDbConnection.php");

include("includes/common/classes/classmain.php");
include("includes/common/classes/classProductMain.php");
include("includes/common/classes/classcart.php");

include("includes/shoppingcart.php");

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

?>
<? include("includes/header.php");

?>
<style>
.text ul
{
	list-style:none;
}
.text ul li
{
	float:left;
	height: 40px;
    width: 140px;
}
</style>
<script language="JavaScript" type="text/javascript" src="js/latestJquery.js"></script>
<script language="JavaScript" type="text/javascript" src="js/user.js"></script>
  <tr>
    <td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="0">
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
                        <td height="25" align="center" bgcolor="#C4E2FC" class="bluebold">Sitemap</td>
                      </tr>
	                  <tr>
                            <td class="text"><br />
                    <ul>
                      <li><a href="inex.html">Home</a></li>
                      <li><a href="vendors.html">All Vendors </a></li>
                      <li><a href="guarantee.html">Guarantee </a></li>
                      <li><a href="all_exams.html">All Exams</a></li>
                      <li><a href="faqs.html">FAQ's</a></li>
                      <li><a href="#">News </a></li>
                      <li><a href="login.html">Member </a></li>
                      <li><a href="feedback.html">Feedback</a></li>
                      <li><a href="cart.html">View Cart</a></li>
                      <li><a href="privacy.html">Privacy Policy</a></li>
                      <li><a href="contactus.html">Contact</a></li>
                      <li><a href="sitemap.html">Sitemap</a></li>
                    </ul>
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