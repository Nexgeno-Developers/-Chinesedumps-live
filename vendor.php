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
		$venURL		= str_replace('name=','',$rr);
		$venURL = $venURL;
		
	    $vendor        =  $objPro->getVendorsID($venURL);
	
		$vid		= $vendor['ven_id'];
		
		$_SESSION['vname'] = $venURL;

//---------------------------product Id------------------//


//---------------------------------------track Area--------------------------------//
	$vid		= $vendor['ven_id'];
	if(empty($vid) || $vendor['ven_status'] !='1')
	{
		header('location: '.$websiteURL);
		exit();
	}
			//$vidnew		= $vendor['catid'];
		//$veName		= $row_new['name'];
		
		
		$veName		= $vendor['ven_name'];
		$_SESSION['vname'] = $veName;
		$getPage[0]	=	$vendor['ven_title'];
		$getPage[1]	=	$vendor['ven_name'];
		$getPage[2]	=	$vendor['ven_descr']; // full description
		$getPage[4]	=	$vendor['ven_keywords'];
		$getPage[5]	=	strlen($vendor['ven_desc']);
		
		$getglobart	=	mysql_fetch_array(mysql_query("SELECT * from tbl_globlecontents where page_id='1'"));
		
		if($getPage[0]=='')
		{
		$getPage[0]	=	str_replace("[VendorName]",$veName,$getglobart['page_title']);
		}
		if($getPage[1]=='')
		{
		$getPage[1]	=	str_replace("[VendorName]",$veName,$getglobart['content_title']);
		}
		if($getPage[5]=='')
		{
		$getPage[5]	=	str_replace("[VendorName]",$veName,$getglobart['meta_descx']);
		}
		if($getPage[4]=='')
		{
		$getPage[4]	=	str_replace("[VendorName]",$veName,$getglobart['meta_keywords']);
		}
		if($getPage[2]=='' || strlen($getPage[2])<='15')
		{
		$getPage[2]	=	str_replace("[VendorName]",$veName,$getglobart['page_contents']);
		}
		else
		{
			$getPage[2]	.=	"<br />".str_replace("[VendorName]",$veName,$getglobart['page_contents']);
		}
		
						
		$result			=	$objPro->getTrack($vid);
		
		$vendorssale		=	$objPro->getTrackSale();
		
		if(mysql_num_rows($vendorssale)>='1'){
			$showbundles	=	$objPro->showTrackBundels($vendorssale,$venURL,$base_path,$vendor);
		}else{
			$showbundles	=	'';
		}
		
		$showTracks		=	$objPro->showTrack($result,$venURL,$websiteURL) ;
				
		$firstlink	=	' '.$veName;
		
		
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
</style>

<div class="inner-banner-box group">
        <div class="max-width banner-text"><?php echo $getPage[1]; ?> Certification </div>
    </div>

<div class="content-box">
        <div class="certification-box">
            <div class="max-width">
              <a href="<?php echo $websiteURL; ?>" class="blutext">Home</a> > <span class="blutext"><?php echo $getPage[1]; ?>
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
                    <?=$showTracks?>
                </ul>
        	</div>
        </div>
        <div class="about-chinesedumps">
            <div class="max-width">
                <p><?php echo $getPage[2]; ?></p>
                <br><br>
                <ul class="popular-vendor-list group vender_listings">
                    <li>
                       <ul class="group">
                                <?php
							$sql	=	"Select * from tbl_exam where ven_id='$vid' and exam_status='1'";
							$result	=	mysql_query($sql)or die(mysql_error()."There is no vendor");
							$iex	=	1;
							while($used=mysql_fetch_array($result))
							{
								$pageright		= 	$used['exam_url']; 
								$urlright		=	$pageright.".htm";
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
        
        <!--<div class="trust-and-moneyback">-->
        <!--    <div class="max-width">-->
        <!--        <div class="trust-us">-->
        <!--            <h5>People Trust Us</h5>-->
        <!--            <div class="group">-->
        <!--                <div class="trust-us-img"><img src="images/trust-img.png" alt=""></div>-->
        <!--                <div class="trust-us-text">-->
        <!--                    <div class="testimonial"><span class="quote-start">&nbsp;</span>I owe deep gratitude to Exam Collection professionals for my amazing success. Exam Collection 624-874 Study Guide remained the best source of preparation for me. Easy language, simplified content, updated information benefited me too much.<span class="quote-end">&nbsp;</span></div>-->
        <!--                    <div class="client-name">Derek Marcus</div>-->
        <!--                </div>-->
        <!--            </div>-->
        <!--        </div>-->
        <!--        <div class="moneyback-guarantee">-->
        <!--            <h6>Money Back Guarantee</h6>-->
        <!--            <div class="group">-->
        <!--                <div class="guarantee-img"><img src="images/guarantee-image.png" alt=""></div>-->
        <!--                <div class="guarantee-text">Our Money back Guarantee is valid for all the IT Certification Exams mentioned. We have 30 Days back Passing Guarantee on our individual Exam Engine purchase. For more information please visit our Guarantee Page.</div>-->
        <!--            </div>-->
        <!--        </div>-->
        <!--    </div>-->
        <!--</div>-->
        <!--<div class="newsletter-box">-->
        <!--    <div class="max-width">-->
        <!--        <div class="newsletter-heading">Subscribe Newsletter</div>-->
        <!--        <p>Signup now to our newsletter to get the latest updates of our products, news and many more. We do not spam.</p>-->
        <!--        <div class="group">-->
        <!--            <input type="text" class="input-field" value="Your Name" onblur="if (this.value == '') {this.value = 'Your Name';}" onfocus="if (this.value == 'Your Name') {this.value = '';}">-->
        <!--            <input type="email" class="input-field" value="Your Email Address" onblur="if (this.value == '') {this.value = 'Your Email Address';}" onfocus="if (this.value == 'Your Email Address') {this.value = '';}">-->
        <!--            <button class="orange-button subscribe-button">Subscribe</button>-->
        <!--        </div>-->
        <!--    </div>-->
        <!--</div>-->

	</div>
    <!-- -->

 <? include("includes/footer.php");?>