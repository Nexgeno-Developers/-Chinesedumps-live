<?php
session_start();

include("includes/config/classDbConnection.php");
include("includes/common/classes/classmain.php");
include("includes/common/classes/classProductMain.php");
include("includes/common/classes/classcart.php");
//-------------------------------------------------//
$objDBcon   =   new classDbConnection;
$objMain	=	new classMain($objDBcon);
$objPro		=	new classProduct($objDBcon);
$objCart	=	new classCart($objDBcon);
//------------------------------------------------//
	$getPage	=	$objMain->getContent(1);

    $guest = session_id();
	$_SESSION['guestId'] = $guest;
	$rr	=	 $_SERVER['QUERY_STRING'];
		
	$string		=	"/";
		
	$certUrl		= str_replace('name=','',$rr);
	$cert =  $objPro->getTrackID($certUrl); // get track nick id
	$certID	=	$cert['cert_id'];
		
	$_SESSION['vname'] = $certUrl;

//---------------------------product Id------------------//


	
	if($cert['cert_status'] !='1')
	{
		header('location: '.$websiteURL);
		exit();
	}
			 $certName		=	$cert['cert_name'];
			 $certFName		= 	$cert['cert_fname'];
			 $vendor       	=  $objPro->getVendorsIDID($cert['ven_id']);
			// $check  		=	$objTrack->searchVN($certName);
			 //$rch			=	mysql_fetch_array($check);
			 $venName	=   $vendor['ven_name'];
			 $venID		=	$vendor['ven_id'];
		
			$result			=	$objPro->getProduct($certID);
			$spam			=	"";
			$spam[1]		= 	$result;
			$spam[2]		=	$venName;
		    $spam[3]		=	$certName;
		
			
			$getPage[0]	=	$cert['cert_title'];
			$getPage[1]	=	$cert['cert_name'];
			$getPage[2]	=	$cert['cert_descr']; // full description
			$getPage[4]	=	$cert['cert_keywords'];
			$getPage[5]	=	$cert['cert_desc_seo'];	
			
			$getglobart	=	mysql_fetch_array(mysql_query("SELECT * from tbl_globlecontents where page_id='2'"));
		
			if($getPage[0]=='')
			{
			$getPage00 = str_replace("[VendorName]",$venName,$getglobart['page_title']);
			$getPage0	= str_replace("[CertFullName]",$certFName,$getPage00);
			$getPage[0]	= str_replace("[CertName]",$certName,$getPage0);
			}
			if($getPage[1]=='')
			{
			$getPage11 = str_replace("[VendorName]",$venName,$getglobart['content_title']);
			$getPage1	= str_replace("[CertFullName]",$certFName,$getPage11);
			$getPage[1]	=	str_replace("[CertName]",$certName,$getPage1);
			}
			if($getPage[5]=='')
			{
			$getPage55 = str_replace("[VendorName]",$venName,$getglobart['meta_descx']);
			$getPage5	= str_replace("[CertFullName]",$certFName,$getPage55);
			$getPage[5]	=	str_replace("[CertName]",$certName,$getPage5);
			}
			if($getPage[4]=='')
			{
			$getPage44 = str_replace("[VendorName]",$venName,$getglobart['meta_keywords']);
			$getPage4	= str_replace("[CertFullName]",$certFName,$getPage44);
			$getPage[4]	=	str_replace("[CertName]",$certName,$getPage4);
			}
			if($getPage[2]=='' || strlen($getPage[2])<='15')
			{
			$getPage22	=	str_replace("[VendorName]",$venName,$getglobart['page_contents']);
			$getPage2	= str_replace("[CertFullName]",$certFName,$getPage22);
			$getPage[2]	=	str_replace("[CertName]",$certName,$getPage2);
			}
			else
			{
				$getPage22	=	"<br />".str_replace("[VendorName]",$venName,$getglobart['page_contents']);
				$getPage2	= str_replace("[CertFullName]",$certFName,$getPage22);
				$getPage[2]	.=	str_replace("[CertName]",$certName,$getPage2);
			}
			
					
			//$bundles		=	$objProduct->getProductBundle($certID,$siteId);
			$certifysale	=	$objPro->getCertifySale();
			
			 if(mysql_num_rows($certifysale)>='1'){
			
			$showbundles	=	$objPro->showCertBundels($certifysale,$venName,$base_path,$cert);
			}else{
			$showbundles	=	'';
			}
			
			$Products		=	$objPro->showAllexams($spam,$certName,$base_path);
			
						
			$url		=	$base_path."".str_replace(' ', '-',$vendor['ven_url'])."-certification-training.html";
			$_SESSION['globlearticle']	=	$certName;
			
			$firstlink	=	" <a href='".$url."' class='producttitletext' title='".$venName."'><strong>".$venName." ></strong></a>";
			$secondlink	=	" ".$certName."";	
	    $getrelcert	=	"SELECT * from tbl_cert where ven_id='".$cert['ven_id']."' and cert_id !='".$cert['cert_id']."' and cert_status='1'";
		$certrel	=	mysql_query($getrelcert);
		$my_certrel	=	mysql_num_rows($certrel);
//---------------------------------------------------------------------------------//
// Legacy include removed (html/items.html does not exist; page renders inline below)
//---------------------------------------------------------------------------------//

?>

<? include("includes/header.php");?>
<script language="JavaScript" type="text/javascript" src="js/addcart.js"></script>
<script language="JavaScript" type="text/javascript" src="js/addcart3.js"></script>
<style type="text/css">
<!--
.style1 {color: #FF0000}
-->

.vender_listings
{
    display:none;
}
.sub_vendors ul {
    padding-left: 0 !important;
}

.sub_vendors .black-heading {
    text-align: left;
}
</style>

<div class="inner-banner-box group">
        <div class="max-width banner-text"><?php echo $getPage[1]; ?> Certification </div>
    </div>
<div class="content-box">
        <div class="certification-box">
            <div class="max-width">
                <a href="<?php echo $websiteURL; ?>" class="blutext">Home</a> > <a href="<?=$url?>"><?php echo $venName ?></a> > <span class="blutext"><?php echo $getPage[1]; ?>
            </div>
        </div>
        <div class="exam-list-box">
            <div class="max-width">
                <h1 class="center-heading center-heading02"><span><?php echo $getPage[1]; ?> </span></h1>
                <div class="black-heading">Chinesedumps <?php echo $getPage[1]; ?> Certification Practice Exams</div>
                
                <ul class="list-box list-title">
                	<li>Exams</li>
                	<li>Buy Now</li>
                	<li></li>
                </ul>
                <ul class="list-box02">
                    <?=$Products?>
                </ul>
        	</div>
        </div>
        <div class="about-chinesedumps sub_vendors">
            <div class="max-width">

                <p><?php echo $getPage[2]; ?></p>
                <br><br>
                <ul class="popular-vendor-list group vender_listings">
                    <li>
                       <ul class="group">
                                <?php
							$sql	=	"select * from tbl_exam where ven_id='$venID' and exam_status='1'";
							$result	=	mysql_query($sql)or die(mysql_error()."There is no vendor");
							$iex	=	1;
							while($used=mysql_fetch_array($result))
							{
								$pageright		= 	$used['exam_url']; 
								$urlright		=	$websiteURL."".$pageright.".htm";
								$titleright		=	$used['exam_name'];                    			
							?>
                      <li style="width:10%;"><a href="<?=$urlright?>" title="<?=$titleright?>"><span>&nbsp;</span><?php echo $used['exam_name']; ?></a></li>
                              <? 
							$iex++;
							} ?>
                                  
                                </ul>
                    </li>
                   
                </ul>
            </div>
        </div>

	</div>
    <!-- -->
    
   
              <? include("includes/footer.php");?>
