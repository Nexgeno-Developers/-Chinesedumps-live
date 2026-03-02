<?php
include_once __DIR__ . '/config/classDbConnection.php';
if (function_exists('start_root_url_rewrite')) {
	start_root_url_rewrite();
}
   global $this_page;
   
   $pagez 		 = 	substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
   
   $pageurl	 =	$pagez;
   
   $pagez 		.= 	"?".$_SERVER['QUERY_STRING'];
   
   $page_outQuery = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
   
   $gethotvendor	=	mysql_query("SELECT * from tbl_vendors where ven_hot='1' and ven_status='1' order by ven_id ASC limit 0,5");
	 
	function checkCourseStatus($name){
		$get_course = mysql_fetch_assoc(mysql_query("SELECT * from tbl_course where name='".$name."'"));
		if(!empty($get_course)){
			if($get_course['status'] == 1){
				$status = 'stable';
			}else{
				$status = 'unstable';
			}
			return $status;
		}else{
			return false;
		}
	} 	
	
	 if(isset($_POST['su_course']) && $_POST['su_course']  == "Submit")	{
		 $spram[0]	=	$_POST["email"];		
		 $spram[1] = $_POST["course_name"];		
		 $spram[2] = date('Y-m-d H:i:s');				
		 mysql_query("insert into tbl_course_subscription (email, course, created_at) values ('".$spram[0]."','".$spram[1]."','".$spram[2]."')");	
		 
		/*for subscriber */
		$to = $spram[0];
		$subject = "www.chinesedumps.com" ;
	  $txt = "Subscription Successful ,  course Name ( ".$spram[1]." )" ;
		$headers = "From: www.chinesedumps.com" . "\r\n" ;
    mail($to,$subject,$txt,$headers); 		 
		 /*for admin */
		$to_admin = "sales@chinesedumps.com";
		$subject_admin = "www.chinesedumps.com" ;
	 $txt_admin = "Subscribe By " .$spram[0]." , course Name ( ".$spram[1]." )" ;
		$headers_admin = "From: www.chinesedumps.com" . "\r\n" ;
     mail($to_admin,$subject_admin,$txt_admin,$headers_admin);	 
	 }
	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">
<html
	xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<base href="
			<?php echo $websiteURL?>" />
			<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
			<?php $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
?>
			<link rel="canonical" href="<?php echo $actual_link;?>" />

					<title>
						<?=$getPage[0]?>
					</title>
					<!--<meta name="keywords" content="
				<?=$getPage[4]?>" />-->
				<meta name="description" content="
					<?=$getPage[5]?>" />
					<link rel="shortcut icon" href="
						<?php echo $websiteURL?>favicon.ico" />
						<meta charset="utf-8">
							<meta name="viewport" content="width=device-width, user-scalable=no">
								<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
									<link href="css/themecss/chinesedumps-min-style.css" rel="stylesheet" type="text/css" />
									<link href="<?php echo BASE_URL; ?>css/style.css" rel="stylesheet" type="text/css" />
									
<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
										<!-- New -->
										<link rel="stylesheet" href="css/bootstrap.min.css">
											<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
											<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
											
<script type="text/javascript" src="jquery.simplyscroll.min.js"></script>
<link rel="stylesheet" href="jquery.simplyscroll.css" media="all" 
type="text/css">
 
											<!-- Thumb Slider New -->
											<!--<script src="<?php echo BASE_URL; ?>includes/js/multislider.min.js"></script>-->
											<script type="text/javascript">
         function chekemailsignup()         
         {         
         	var srch = document.getElementById('srch').value;	         
         	if(srch=='')         
         	{         
         		alert("Please Enter exam code to search.");         
         		document.getElementById('srch').focus();         
         		return false;         
         	}         
         	return true;         
         }         
      </script>
											<script language="JavaScript" type="text/javascript" src="js/user.js"></script>
											<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
												<style>
    
.dropdown-menu > li.kopie > a {
    padding-left:5px;
}
 
.dropdown-submenu {
    position:relative;
}
.dropdown-submenu>.dropdown-menu {
   top:0;left:100%;
   margin-top:-6px;margin-left:-1px;
   -webkit-border-radius:0 6px 6px 6px;-moz-border-radius:0 6px 6px 6px;border-radius:0 6px 6px 6px;
 }
  
.dropdown-submenu > a:after {
  border-color: transparent transparent transparent #333;
  border-style: solid;
  border-width: 5px 0 5px 5px;
  content: " ";
  display: block;
  float: right;  
  height: 0;     
  margin-right: -10px;
  margin-top: 5px;
  width: 0;
}
 
.dropdown-submenu:hover>a:after {
    border-left-color:#555;
 }

.dropdown-menu > li > a:hover, .dropdown-menu > .active > a:hover {
  text-decoration: underline;
}  
  
@media (max-width: 767px) {
  .navbar-nav  {
     display: inline;
  }
  .navbar-default .navbar-brand {
    display: inline;
  }
  .navbar-default .navbar-toggle .icon-bar {
    background-color: #fff;
  }
  .navbar-default .navbar-nav .dropdown-menu > li > a {
    color: red;
    background-color: #ccc;
    border-radius: 4px;
    margin-top: 2px;   
  }
   .navbar-default .navbar-nav .open .dropdown-menu > li > a {
     color: #333;
   }
   .navbar-default .navbar-nav .open .dropdown-menu > li > a:hover,
   .navbar-default .navbar-nav .open .dropdown-menu > li > a:focus {
     background-color: #ccc;
   }

   .navbar-nav .open .dropdown-menu {
     border-bottom: 1px solid white; 
     border-radius: 0;
   }
  .dropdown-menu {
      padding-left: 10px;
      padding-top:0px;
      padding-bottom:0px;
  }
  .dropdown-menu .dropdown-menu {
      padding-left: 20px;
   }
   .dropdown-menu .dropdown-menu .dropdown-menu {
      padding-left: 30px;
   }
   li.dropdown.open {
    border: 0px solid red;
   }
   section#header-main span.caret {
   display: inline-block !important;
}

}  
 
@media (min-width: 768px) {
  ul.nav li:hover > ul.dropdown-menu {
    display: block;
  }
  #navbar {
    text-align: center;
	margin-top: 10px;
  }
}  
.navbar-default {
    background-color: #fff;
    border-color: #fff;
}  

 s
.navbar-default .navbar-nav>li>a {
    color: #000;
} 
ul.nav.navbar-nav ul.dropdown-menu {
    background: #ffffff;
		
}

ul.nav.navbar-nav ul.dropdown-menu a {
    color: #1d1d1d;
    font-size: 14px;
    font-weight: normal;
		transition:0.3sec;    padding: 5px 5px;
}

ul.nav.navbar-nav ul.dropdown-menu a:hover {
    color: #f72019 !important;
}
ul.nav.navbar-nav ul.dropdown-menu a:hover {
    color: #1d1d1d !important;
background:transparent;
}

#navbar nav.navbar-static-top {
    padding-top: 8px;
    float: right;
}
ul.nav.navbar-nav ul.dropdown-menu a:hover {
    color: #e93635 !important;
}
.navbar-default {
    background: transparent !important;
    border: none !important;
}
.navbar {
    margin-bottom: 0  !important;
}
ul.nav.navbar-nav ul.dropdown-menu li {display: block;padding-bottom: 0px;padding-top: 0px; width:100%;}
ul.nav.navbar-nav .dropdown-menu .dropdown-menu {
    border-left: 1px solid rgba(73,71,71,.15);
    left: 100%;
    right: auto;
    top: 0;
    margin-top: -1px;
    height: 420px;
    border-radius: 0;
    padding-left: 10px;
}
ul.dropdown-menu {
    height: 420px !important;
}
ul.nav.navbar-nav li .dropdown-menu li, ul.nav.navbar-nav li .dropdown li{
	margin: 0px 0px !important;
	
}

.foter_telg a {
       font-size: 14px;
    text-decoration: none;
    color: #b6b6b6;
    padding-left:5px;
}
.foter_telg img {
width: 20px;
    padding-top: 0px;
    
}

section#header-main span.caret {
    display: none;
}

div#header-area {
    background: linear-gradient(to right,rgb(145, 28, 22) 0, rgb(251, 59, 59) 100%) !important;
    border-bottom: 1px solid #d8d7d7;
    padding: 0px 40px;
}

section#header-main .navbar-collapse {
    padding-right: 0px;
    padding-left: 0px;
    overflow-x: visible;
    border-top: 1px solid transparent;
    -webkit-box-shadow: inset 0 1px 0 rgb(255 255 255 / 10%);
    box-shadow: inset 0 1px 0 rgb(255 255 255 / 10%);
    -webkit-overflow-scrolling: touch;
    padding-top: 20px;
}

p.foter_telg {
        margin-top: 4px;
    margin-bottom: 0;
}

.menu_mrg
{
    margin-top:-6px !important;
}

@media(max-width:767px)
{
    .menu_mrg
{
    margin-top:0px !important;
}
ul.dropdown-menu {
    height: auto !important;
}
ul.dropdown-menu .bounce li a{
  -moz-animation-duration: 0s;
  -webkit-animation-duration: 0s;
  -moz-animation-name: slidein-left;
  -webkit-animation-name: slidein-left;
}

    
}

ul.dropdown-menu .bounce li a{
  -moz-animation-duration: 1s;
  -webkit-animation-duration: 1s;
  -moz-animation-name: slidein-left;
  -webkit-animation-name: slidein-left;
}

@-moz-keyframes slidein-left {
  from {
    margin-left: 20%;
  }
  to {
    margin-left: 0%;
  }
}

@-webkit-keyframes slidein-left {
  from {
    margin-left: 20%;
  }
  to {
    margin-left: 0%;
  }
}

ul.dropdown-menu .bounce li ul.dropdown-menu li a
{
      -moz-animation-duration: 0s;
  -webkit-animation-duration: 0s;
  -moz-animation-name: slidein-left;
  -webkit-animation-name: slidein-left;
}

</style>
<meta name="google-site-verification" content="yODErouiH7tWT1B41PP9DfPOaEW2A-ykOKe7ESEadsI" />
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-159970348-1"></script>
<script>
 window.dataLayer = window.dataLayer || [];
 function gtag(){dataLayer.push(arguments);}
 gtag('js', new Date());

 gtag('config', 'UA-159970348-1');
</script>

<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-K4FJC5Q');</script>
<!-- End Google Tag Manager -->

	</head>
			<body>
			    
			    <!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-K4FJC5Q"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

				<div id="top-bar">
					<div class="container">
						<div class="row">
							<div class="col-md-2 col-sm-3 col-xs-5">
								<p class="info">
									<a href="tel:+917777079803">
										<i class="fa fa-phone" aria-hidden="true"></i>+91 7777079803

									</a>
								</span>
							</p>
						</div>
						<div class="col-md-3 col-sm-4 col-xs-7">
						<p class="foter_telg"><img style="width:20px;" src="<?php echo BASE_URL; ?>images/telegram.png" alt="" /> <a target="_blank" href="http://cciestudygroups.com/"> Telegram Groups <i class='fa fa-angle-double-right faa-horizontal animated'></i></a></p>
						
					</div>
					<div class="col-md-2 col-sm-5 col-xs-12 col-md-push-1">
						<form action="all_exams.html" method="post" onsubmit="return checkSearch()">
							<input type="text" name="search"  class="nav-search top_frm" placeholder="Search"  id="main_search" required>
								<div class="search-submit-box top_frm_icon">
									<input type="submit" class="search-submit" value="&nbsp;">
									</div>
								</form>
							</div>
							<div class="col-md-4 col-sm-5 col-xs-12 col-md-push-1">
								<div class="cart-and-memberarea">
									<div class="header-cart-box">
										<a href="cart.php">
											<div class="header-cart">
												<span>(
													<?php if(isset($_SESSION["totalprod"])){ echo $_SESSION["totalprod"];} else{echo "0";} ?>)
												</span> Item  $
												<?php if(isset($_SESSION["totalricecc"])){ echo $_SESSION["totalricecc"];} else{echo "0.00";} ?>
											</div>
										</a>
										<div class="cart-dropdown">
											<ul>
												<li>Option 1</li>
												<li>Option 2</li>
												<li>Option 3</li>
												<li>
													<a href="#">Go Cart</a>
												</li>
											</ul>
										</div>
										<a class="cart-icon-box" href="cart.php">
											<span class="cart-icon">&nbsp;</span>
										</a>
									</div>
									<ul class="member-area">
										<li style="margin-top:-30px"></li>
										<?php if(isset($_SESSION['uid'])){ ?>
										<li>
											<a href="signout.html" class="chat-icon">Signout</a>
										</li>
										<li>
											<a href="mydownloads.html" class="chat-icon">My Account</a>
										</li>
										<?php } else { ?>
										<li>
											<a href="login.html" class="login-icon">
												<i class="fa fa-lock" aria-hidden="true"></i>Login
											</a>
										</li>
										<li>
											<a href="login.html" class="register-icon">
												<i class="fa fa-user" aria-hidden="true"></i>Register
											</a>
										</li>
										<?php } ?>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				
				
				
				<section id="header-main">
  <div class="header_bottom">
    <div class="">
      <div class="row">
          <div class="col-md-2">
					<div class="header">
						<div class="">
							<div class="logo-box">
								<span class="logo">
									<a href="<?php echo BASE_URL; ?>">
										<strong>Chinesedumps</strong>
									</a>
								</span>
							</div>
						</div>
					</div>
				</div>
		<div class="col-md-10">		
        <nav role="navigation" class="navbar navbar-expand-sm navbar-default">
          <div class="navbar-header">
            <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
             <!--- <span class="sr-only">Toggle navigation</span>-->
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
            </button>
          </div>
          <!-- Collection of nav links and other content for toggling -->
          <div id="navbarCollapse" class="collapse navbar-collapse">
                    <ul id="fresponsive" class="nav navbar-nav dropdown">  
									<?php /**/  ?>
									
			<li class="dropdown">      
										<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')"  
											<?php if($this_page == "exam-cisco") { ?> id="nav_act"
											<?php } ?> href="<?php echo BASE_URL; ?>exam-cisco.htm" >Cisco </span>
										</a>
											<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
										<ul class="dropdown-menu">
											<li>
												<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="#">Enterprise Infrastructure v1.0</a>
													<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
												<ul class="dropdown-menu menu_mrg bounce">
													<li>
														<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="new-ccna-200-301-dumps-cert.htm">CCNA</a>
															<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
														<ul class="dropdown-menu menu_mrg">
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="200-301-ccna-dumps.htm">200-301 CCNA</a>
															</li>
															
														</ul>
													</li>
													<li>
														<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="ccnp-Enterprise-Infrastructure-cert.htm">CCNP</a>
															<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
														<ul class="dropdown-menu">
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="encor-350-401-dumps.htm">	350-401 ENCOR</a>
															</li>
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="300-410-ccnp-ent-enarsi-dumps.htm">	300-410 ENARSI</a>
															</li>
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="300-415-ccnp-ent-ensdwi-dumps.htm">	300-415 ENSDWI</a>
															</li>
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="300-420-ccnp-ent-ensld-dumps.htm">	300-420 ENSLD</a>
															</li>
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="300-425-ccnp-ent-enwlsd-dumps.htm">	300-425 ENWLSD</a>
															</li>
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="300-430-ccnp-ent-enwlsi-dumps.htm">	300-430 ENWLSI</a>
															</li>
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="300-435-ccnp-ent-enauto-dumps.htm">		300-435 ENAUTO</a>
															</li>
														</ul>
													</li>
													<li>
														<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="ccie-routing-and-switching-cert.htm">CCIE</a>
															<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
														<ul class="dropdown-menu">
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="encor-350-401-dumps.htm">	350-401 ENCOR</a>
															</li>
														
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="CCIE-EI-Lab.htm">CCIE EI Lab</a>
																	<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
																<ul class="dropdown-menu">
																	<li>
																		<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="CCIE-EI-Lab.htm">Workbook</a>
																	</li>
																	<li>
																		<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="EI-Bootcamp.htm">Bootcamp</a>
																	</li>
																		<li>
																		<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="workbooks-policy.php">Workbook Policy</a>
																	</li>
																
																	
																	<li>
																		<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="demo.php">Demo</a>
																	</li>
															</ul>
																
															</li>
															
															<li>
																		<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="workbooks-policy.php">Workbook Policy</a>
																	</li>
																		<li>
																		<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="http://ccierack.rentals/">Rack Rental</a>
																	</li>
																	
																	<li>
																		<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="demo.php">Demo</a>
																	</li>
														</ul>
													</li>
												</ul>
											</li>
											<li>
												<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="#">Service Provider v5.0 </a>
													<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
												<ul class="dropdown-menu bounce">
													<li>
														<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="new-ccna-200-301-dumps-cert.htm">CCNA</a>
															<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
														<ul class="dropdown-menu menu_mrg">
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="200-301-ccna-dumps.htm">	200-301 CCNA</a>
															</li>
															
														</ul>
													</li>
													<li>
														<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="ccnp-service-provider-cert.htm">CCNP</a>
															<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
														<ul class="dropdown-menu">
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="spcor-350-501-ccnp-dumps.htm">	350-501 SPCOR</a>
															</li>
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="300-510-ccnp-spri-dumps.htm">	300-510 SPRI</a>
															</li>
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="300-515-ccnp-spvi-dumps.htm">	300-515 SPVI</a>
															</li>
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="300-535-ccnp-spauto-dumps.htm">		300-535 SPAUTO</a>
															</li>
														</ul>
													</li>
													<li>
														<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="ccie-service-provider-cert.htm">CCIE</a>
															<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
														<ul class="dropdown-menu">
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="spcor-350-501-ccnp-dumps.htm">SPCOR 350-501</a>
															</li>
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="CCIE-SP-Lab.htm">	Service Provider Lab <span class="caret"></span></a>
																	<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
																	<ul class="dropdown-menu">
																	<li>
																		<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="CCIE-SP-Lab.htm">Workbook</a>
																	</li>
																	<li>
																		<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="SP-Bootcamp.htm">Bootcamp</a>
																	</li>
																		<li>
																		<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="workbooks-policy.php">Workbook Policy</a>
																	</li>
			
															</ul>
															</li>
																<li>
																		<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="workbooks-policy.php">Workbook Policy</a>
																	</li>
																	<li>
																		<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="http://ccierack.rentals/sp-pricing/">Rack Rental</a>
																	</li>
														</ul>
													</li>
												</ul>
											</li>
											<li>
												<a data-toggle="dropdown" class="dropdown-toggle" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="#">Security v6.0 </a>
											 	<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
												<ul class="dropdown-menu bounce">
													<li>
														<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="new-ccna-200-301-dumps-cert.htm">CCNA</a>
															<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
														<ul class="dropdown-menu menu_mrg">
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="200-301-ccna-dumps.htm">200-301 CCNA</a>
															</li>
														</ul>
													</li>
													<li>
														<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="ccnp-security-cert.htm">CCNP</a>
															<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
														<ul class="dropdown-menu">
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="350-701-ccnp-scor-dumps.htm">350-701 SCOR</a>
															</li>
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="300-710-ccnp-security-sncf-dumps.htm">	300-710 SNCF</a>
															</li>
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="300-715-ccnp-security-sise-dumps.htm">300-715 SISE</a>
															</li>
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="300-720-ccnp-security-sesa-dumps.htm">	300-720 SESA</a>
															</li>
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="300-725-ccnp-security-swsa-dumps.htm">300-725 SWSA</a>
															</li>
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="300-730-ccnp-security-svpn-dumps.htm">	300-730 SVPN</a>
															</li>
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="300-735-ccnp-security-sauto-dumps.htm">	300-735 SAUTO</a>
															</li>
														
														
															
														</ul>
													</li>
													<li>
														<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="ccie-security-cert.htm">CCIE</a>
															<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
														<ul class="dropdown-menu">
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="350-701-ccnp-scor-dumps.htm">	SCOR 350-701</a>
															</li>
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="CCIE-Security-Lab.htm">	CCIE Security Lab </a>
																		<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
																	<ul class="dropdown-menu">
																	<li>
																		<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="CCIE-Security-Lab.htm">Workbook</a>
																	</li>
																	<li>
																		<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="Security-Bootcamp.htm">Bootcamp</a>
																	</li>
																		<li>
																		<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="workbooks-policy.php">Workbook Policy</a>
																	</li>
																		<li>
																		<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="sec-demo.php">Demo</a>
																	</li>
															</ul>
															</li>
																<li>
																		<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="workbooks-policy.php">Workbook Policy</a>
																	</li>
																	<li>
																		<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="http://ccierack.rentals/security-pricing/">Rack Rental</a>
																	</li>
																	<li>
																		<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="sec-demo.php">Demo</a>
																	</li>
														</ul>
													</li>
												</ul>
											</li>
											<li>
												<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="#">Data Center v3.1 </a>
													<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
												<ul class="dropdown-menu bounce">
													<li>
														<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="new-ccna-200-301-dumps-cert.htm">CCNA </a>
															<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
														<ul class="dropdown-menu menu_mrg">
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="200-301-ccna-dumps.htm">	200-301 CCNA</a>
															</li>
														</ul>
													</li>
													<li>
														<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="ccnp-data-center-cert.htm">CCNP </a>
															<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
														<ul class="dropdown-menu">
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="350-601-ccnp-dccor-dumps.htm">350-601 DCCOR</a>
															</li>
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="300-610-ccnp-dcid-dumps.htm">300-610 DCID</a>
															</li>
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="300-615-ccnp-dcit-dumps.htm">	300-615 DCIT</a>
															</li>
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="300-620-ccnp-dcaci-dumps.htm">	300-620 DCACI</a>
															</li>
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="300-625-ccnp-dcsan-dumps.htm">	300-625 DCSAN</a>
															</li>
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="300-635-ccnp-dcauto-dumps.htm">	300-635 DCAUTO</a>
															</li> 
														</ul>
													</li>
													<li>
														<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="ccie-dc-cert.htm">CCIE</a>
													 	<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
														<ul class="dropdown-menu">
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="350-601-ccnp-dccor-dumps.htm">	DCCOR 350-601</a>
															</li>
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="CCIE-DC-Lab.htm">Data Center Lab</a>
																		<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
																	<ul class="dropdown-menu">
																	<li>
																		<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="CCIE-DC-Lab.htm">Workbook</a>
																	</li>
																	<li>
																		<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="DC-Bootcamp.htm">Bootcamp</a>
																	</li>
																		<li>
																		<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="workbooks-policy.php">Workbook Policy</a>
																	</li>
															</ul>
															</li>
																<li>
																		<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="workbooks-policy.php">Workbook Policy</a>
																	</li>
																	<li>
																		<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="http://ccierack.rentals/datacenter-pricing/">Rack Rental</a>
																	</li>
														</ul>
													</li>
												</ul>
											</li>
											<li>
												<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="#">Collaboration v3.0 </a>
													<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
												<ul class="dropdown-menu bounce">
													<li>
														<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="new-ccna-200-301-dumps-cert.htm">CCNA </a>
															<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
														<ul class="dropdown-menu menu_mrg">
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="200-301-ccna-dumps.htm">		200-301 CCNA</a>
															</li>
														
														</ul>
													</li>
													<li>
														<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="ccnp-collaboration-cert.htm">CCNP</a>
															<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
														<ul class="dropdown-menu">
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="clcor-350-801-ccnp-dumps.htm">CLCOR 350-801</a>
															</li>
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="300-810-ccnp-collaboration-clica-dumps.htm">	300-810 CLICA</a>
															</li>
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="300-815-ccnp-collaboration-claccm-dumps.htm">		300-815 CLACCM</a>
															</li>
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="300-820-ccnp-collaboration-clcei-dumps.htm">	300-820 CLCEI</a>
															</li>
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="300-835-ccnp-clauto-dumps.htm">	300-835 CLAUTO</a>
															</li>
															
														
															
														</ul>
													</li>
													<li>
														<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="ccie-collaboration-cert.htm">CCIE</a>
															<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
														<ul class="dropdown-menu">
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="clcor-350-801-ccnp-dumps.htm">	CLCOR 350-801</a>
															</li>
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="CCIE-Collab-Lab.htm">	Collab Lab </a>
																		<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
																	<ul class="dropdown-menu">
																	<li>
																		<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="CCIE-Collab-Lab.htm">Workbook</a>
																	</li>
																	<li>
																		<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="Collab-Bootcamp.htm">Bootcamp</a>
																	</li>
																		<li>
																		<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="workbooks-policy.php">Workbook Policy</a>
																	</li>
																		<li>
																		<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="collab-demo.php">Demo</a>
																	</li>
															</ul>
															</li>
																<li>
																		<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="workbooks-policy.php">Workbook Policy</a>
																	</li>
																	<li>
																		<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="http://ccierack.rentals/collaboration-pricing/">Rack Rental</a>
																	</li>
																	<li>
																		<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="collab-demo.php">Demo</a>
																	</li>
														</ul>
													</li>
														
												</ul>
											</li>
											<li>
												<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="#">Wireless v1.0 </a>
													<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
												<ul class="dropdown-menu bounce">
													<li>
														<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="new-ccna-200-301-dumps-cert.htm">CCNA</a>
															<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
														<ul class="dropdown-menu menu_mrg">
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="200-301-ccna-dumps.htm">	200-301 CCNA</a>
															</li>
														</ul>
													</li>
													<li>
														<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="ccnp-wireless-cert.htm">CCNP </a>
															<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
														<ul class="dropdown-menu">
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="encor-350-401-dumps.htm">	350-401 ENCOR</a>
															</li>
																<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="300-410-ccnp-ent-enarsi-dumps.htm">	300-410 ENARSI</a>
															</li>
																<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="300-415-ccnp-ent-ensdwi-dumps.htm">300-415 ENSDWI</a>
															</li>
																<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="300-420-ccnp-ent-ensld-dumps.htm">	300-420 ENSLD</a>
															</li>
																<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="300-425-ccnp-ent-enwlsd-dumps.htm">	300-425 ENWLSD</a>
															</li>
																<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="300-430-ccnp-ent-enwlsi-dumps.htm">		300-430 ENWLSI</a>
															</li>
																<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="300-435-ccnp-ent-enauto-dumps.htm">	300-435 ENAUTO</a>
															</li> 
														</ul>
													</li>
													<li>
														<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="ccie-wireless-cert.htm">CCIE</a>
															<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
														<ul class="dropdown-menu">
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="encor-350-401-dumps.htm">	350-401 ENCOR</a>
															</li>
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="CCIE-Wireless-Lab.htm">	CCIE Wireless Lab</a>
																	<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
																	<ul class="dropdown-menu">
																	<li>
																		<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="CCIE-Wireless-Lab.htm">Workbook</a>
																	</li>
																	<li>
																		<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="Wireless-Bootcamp.htm">Bootcamp</a>
																	</li>
																		<li>
																		<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="workbooks-policy.php">Workbook Policy</a>
																	</li>
																
															   </ul>
															</li>
																<li>
																		<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="workbooks-policy.php">Workbook Policy</a>
																	</li>
																	<li>
																		<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="http://ccierack.rentals/wireless-pricing/">Rack Rental</a>
																	</li>
																		<li>
																		<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="enterprise-wireless-v1-demo.php">Demo</a>
																	</li>
														</ul>
													</li>
												</ul>
											</li>
											<!--Cybersecurity>-->
											<li>
												<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="#">Cybersecurity Ops </a>
													<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
												<ul class="dropdown-menu bounce">
													<li>
														<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="CCNA-Cyber-Ops-cert.htm">CCNA </a>
															<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
														<ul class="dropdown-menu menu_mrg">
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="210-250-ccna-cyber-ops-dumps.htm">210-250 SECFND</a>
															</li>
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="210-255-ccna-cybersecurity-dumps.htm">210-255 SECOPS</a>
															</li>
														</ul>
													</li> 
												</ul>
											</li> 
											<!--Cybersecurity>-->
											 








											<!--DevNet>-->
											<li>
												<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="#"> DevNet</a>
													<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
												<ul class="dropdown-menu bounce">
													<li>
														<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="Cisco-Certified-DevNet-Associate-cert.htm">CCNA </a>
															<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
														<ul class="dropdown-menu menu_mrg">
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="200-901-DEVASC-dumps.htm">	200-901 DEVASC</a>
															</li>
														</ul>
													</li>
													<li>
														<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="ccna-wireless-cert.htm">CCNP </a>
															<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
														<ul class="dropdown-menu">
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="350-901-ccnp-devnet-devcor-dumps.htm">350-901 DEVCOR</a>
															</li>
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="300-435-ccnp-ent-enauto-dumps.htm">300-435 ENAUTO</a>
															</li>

															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="300-835-ccnp-clauto-dumps.htm">300-835 CLAUTO</a>
															</li>
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="300-635-ccnp-dcauto-dumps.htm">300-635 DCAUTO</a>
															</li>
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="300-535-ccnp-spauto-dumps.htm">300-535 SPAUTO</a>
															</li>
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="300-735-ccnp-security-sauto-dumps.htm">300-735 SAUTO</a>
															</li>
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="300-910-DEVOPS-dumps.htm">300-910 DEVOPS</a>
															</li>
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="300-915-deviot-dumps.htm">300-915 DEVIOT</a>
															</li>
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="300-920-devwbx-dumps.htm">300-920 DEVWBX</a>
															</li>
														</ul>
													</li> 
												</ul>
											</li> 
											<!--DevNet>-->
											<li>
												<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="#">Cloud </a>
												<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
												<ul class="dropdown-menu bounce">
													<li>
														<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="new-ccna-200-301-dumps-cert.htm">CCNA </a>
															<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
														<ul class="dropdown-menu menu_mrg">
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="200-301-ccna-dumps.htm">200-301 CCNA</a>
															</li>
														</ul>
													</li>
													<li>
														<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="<?php echo BASE_URL; ?>ccnp-collaboration-cert.htm">CCNP</a>
															<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
														<ul class="dropdown-menu">
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="350-601-ccnp-dccor-dumps.htm">350-601 DCCOR</a>
															</li>
																<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="300-610-ccnp-dcid-dumps.htm">300-610 DCID</a>
															</li>
																<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="300-615-ccnp-dcit-dumps.htm">300-615 DCIT</a>
															</li>
																<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="300-620-ccnp-dcaci-dumps.htm">300-620 DCACI</a>
															</li>
																<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="300-625-ccnp-dcsan-dumps.htm">300-625 DCSAN</a>
															</li>
																<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="300-635-ccnp-dcauto-dumps.htm">300-635 DCAUTO</a>
															</li> 
														</ul>
													</li>
													<li>
														<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="ccie-collaboration-cert.htm">CCIE</a> 
													</li>
												</ul>
											</li>
											<li>
												<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="#">Design</a>
													<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
												<ul class="dropdown-menu bounce">
												
													<li>
														<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="200-301-ccna-design-dumps-cert.htm">CCDA</a>
															<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
														<ul class="dropdown-menu menu_mrg">
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="200-301-ccna-dumps.htm">200-301 CCNA</a>
															</li>
														</ul>
													</li>
													<li>
														<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="<?php echo BASE_URL; ?>CCDP-Design.htm">CCDP </a>
															<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
														<ul class="dropdown-menu">
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="encor-350-401-dumps.htm">350-401 ENCOR</a>
															</li>
													    	<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="300-410-ccnp-ent-enarsi-dumps.htm">		300-410 ENARSI</a>
															</li>
	                           <li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="300-415-ccnp-ent-ensdwi-dumps.htm">	300-415 ENSDWI</a>
															</li>
	                            <li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="300-420-ccnp-ent-ensld-dumps.htm">300-420 ENSLD</a>
															</li>
                            	<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="300-425-ccnp-ent-enwlsd-dumps.htm">	300-425 ENWLSD</a>
															</li>
	                          <li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="300-430-ccnp-ent-enwlsi-dumps.htm">		300-430 ENWLSI</a>
															</li>
                          	<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="300-435-ccnp-ent-enauto-dumps.htm">	2300-435 ENAUTO</a>
															</li> 
														</ul>
													</li>
													<li>
														<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="<?php echo BASE_URL; ?>CCDE-Design.htm">CCDE</a>
															<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
														<ul class="dropdown-menu">
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="ccde-352-001-ccie-design-dumps.htm">	352-001 ENCOR</a>
															</li>
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="<?php echo BASE_URL; ?>CCDE-Lab.htm">CCDE Lab</a>
																	<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
																	<ul class="dropdown-menu">
																	<li>
																		<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="<?php echo BASE_URL; ?>CCDE-Lab.htm">Workbook</a>
																	</li>
																		<li>
																		<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="<?php echo BASE_URL; ?>CCDE-Bootcamp.htm">Bootcamp</a>
																	</li>
																		<li>
																		<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="workbooks-policy.php">Workbook Policy</a>
																	</li>
																	
															</ul>
															</li>
																<li>
																		<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="workbooks-policy.php">Workbook Policy</a>
																	</li>
														</ul>
													</li>
												</ul>
											</li>
										</ul>
									</li> <?php /**/  ?>
									
										<li class="dropdown">
										<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											<?php if($this_page == "checkout") { ?>id="nav_act"
											<?php } ?> href="<?php echo BASE_URL; ?>Google-Cloud-Certified-cert.htm">Google</span>
										</a>
											<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
										     <ul class="dropdown-menu">
														<li>
															<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="associate-cloud-engineer.htm">Associate Cloud Engineer</a>
														</li>
														<li>
															<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="Professional-Cloud-Architect.htm">Professional Cloud Architect</a>
														</li>
														<li>
															<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="Professional-Data-Engineer.htm">Professional Data Engineer</a>
														</li>
														<li>
															<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="professional-cloud-devops-engineer.htm">Professional Cloud DevOps Engineer</a>
														</li>
														<li>
															<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="Professional-Cloud-Network-Engineer.htm">Professional Cloud Network Engineer</a>
														</li>
														<li>
															<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="professional-cloud-security-engineer.htm">Professional Cloud Security Engineer</a>
														</li>
														<li>
															<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="professional-collaboration-engineer.htm">Professional Collaboration Engineer</a>
														</li>
														<li>
															<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="professional-machine-learning-engineer.htm">Professional Machine Learning Engineer</a>
														</li> 
													</ul>
									</li>
									<li>
										<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											<?php if($this_page == "checkout") { ?>id="nav_act"
											<?php } ?> href="<?php echo BASE_URL; ?>exam-CompTIA.htm">CompTIA</span>
										</a>
										<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
										     <ul class="dropdown-menu">
														<li>
															<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="fco-u61.htm">CompTIA IT Fundamentals FCO-U61</a>
														</li>
														<li>
															<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="220-1001.htm">CompTIA A+ 220-1001</a>
														</li>
														<li>
															<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="N10-007.htm">CompTIA Network+ N10-007</a>
														</li>
														<li>
															<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="SY0-501.htm">CompTIA Security+ SY0-501</a>
														</li>
														<li>
															<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="CV0-002.htm">CompTIA Cloud+ CV0-002</a>
														</li>
														<li>
															<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="XK0-004.htm">CompTIA Linux+ XK0-004</a>
														</li>
														<li>
															<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="SK0-004.htm">CompTIA Server+ SK0-004</a>
														</li>
														<li>
															<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="CS0-001.htm">CompTIA CySA+ CS0-001</a>
														</li> 
														<li>
															<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="CAS-003.htm">CompTIA CASP+ CAS-003</a>
														</li> 
														<li>
															<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="PT0-001.htm">CompTIA PenTest+ PT0-001</a>
														</li> 
														<li>
															<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="PK0-004.htm">CompTIA Project+ PK0-004</a>
														</li> 
														<li>
															<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="TK0-201.htm">CompTIA CTT+ TK0-201</a>
														</li> 
														<li>
															<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="CLO-001.htm">CompTIA Cloud Essentials+ CLO-001</a>
														</li>  
													</ul>
									</li>
									<li>
										<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											<?php if($this_page == "demo") { ?>id="nav_act"
											<?php } ?> href="exam-Amazon-Web-Services.htm">AWS</span>
										</a>
											<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
										<ul class="dropdown-menu">
											<li>
												<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="#">AWS Foundational</a>
													<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
												<ul class="dropdown-menu menu_mrg">
													<li>
														<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="#">AWS Certified Cloud Practitioner</a>
															<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
														<ul class="dropdown-menu menu_mrg">
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="CLF-C01.htm">CLF-C01</a>
															</li>
														</ul>
													</li>
												</ul>
											</li>
											<li>
												<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="#">AWS Associate</a>
												<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
												<ul class="dropdown-menu">
													<li>
														<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="#">AWS Certified Solutions Architect </a>
														<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
														<ul class="dropdown-menu menu_mrg">
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="SAA-C01.htm">SAA-C01</a>
															</li>
														</ul>
													</li>
													<li>
														<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="#">AWS Certified SysOps Administrator </a>
															<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
														<ul class="dropdown-menu">
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="SOA-C01.htm">SOA-C01</a>
															</li>
														</ul>
													</li>
													<li>
														<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="#">AWS Certified Developer</a>
															<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
														<ul class="dropdown-menu">
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="DVA-C01.htm">DVA-C01</a>
															</li>
														</ul>
													</li>
												</ul>
											</li>
											<li>
												<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="#">AWS Professional </a>
												<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
												<ul class="dropdown-menu">
													<li>
														<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="#">AWS Certified Solutions Architect</a>
															<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
														<ul class="dropdown-menu menu_mrg">
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="SAP-C01.htm">SAP-C01</a>
															</li>
														</ul>
													</li>
													<li>
														<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="#">AWS Certified DevOps Engineer </a>
														<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
														<ul class="dropdown-menu">
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="DOP-C01.htm">DOP-C01</a>
															</li>
														</ul>
													</li>
												</ul>
											</li>
										</ul>
									</li>
									<li>
										<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')"  
											<?php if($this_page == "exam-Microsoft") { ?>id="nav_act"
											<?php } ?> href="<?php echo BASE_URL; ?>exam-Microsoft.htm">Microsoft</span>
										</a>
											<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
										<ul class="dropdown-menu">
											<li>
												<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="#">Azure exams</a>
													<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
												<ul class="dropdown-menu menu_mrg">
											<li>
												<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="az-103.htm">AZ-103 Microsoft Azure Administrator</a>
											</li>
											
												<li>
												<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="az-120.htm">AZ-120 Planning and Administering Microsoft Azure for SAP Workloads</a>
											</li>
											
												<li>
												<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="az-203.htm">AZ-203 Developing Solutions for Microsoft Azure</a>
											</li>
											
												<li>
												<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="az-300.htm">AZ-300 Microsoft Azure Architect Technologies</a>
											</li>
											
												<li>
												<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="az-301.htm">AZ-301 Microsoft Azure Architect Design</a>
											</li>
											
												<li>
												<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="az-400.htm">AZ-400 Microsoft Azure DevOps Solutions</a>
											</li>
											
											<li>
												<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="az-500.htm">AZ-500 Microsoft Azure Security Technologies</a>
											</li>
											
												<li>
												<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="az-900.htm">AZ-900 Microsoft Azure Fundamentals</a>
											</li>
											
											
												<li>
												<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="70-487.htm">70-487 Developing Microsoft Azure and Web Services </a>
											</li>
											
											<li>
												<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="70-537.htm"> 70-537 Configuring and Operating a Hybrid Cloud with Microsoft Azure Stack </a>
											</li>
											</ul>
											</li>
											
											
												<li>
												<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="#">Data exams </a>
													<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
												<ul class="dropdown-menu">
														<li>
															<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="ai-100.htm">AI-100 Designing and Implementing an Azure AI Solution</a>
														</li>
														<li>
															<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="dp-100.htm">DP-100 Designing and Implementing a Data Science Solution on Azure</a>
														</li>
														<li>
															<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="dp-200.htm">DP-200 Implementing an Azure Data Solution</a>
														</li>
														<li>
															<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="dp-201.htm">DP-201 Designing an Azure Data Solution</a>
														</li>
														<li>
															<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="70-761.htm">70-761 Querying Data with Transact-SQL</a>
														</li>
														<li>
															<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="70-762.htm">70-762 Developing SQL Databases</a>
														</li>
															<li>
															<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="70-764.htm">70-764 Administering a SQL Database Infrastructure</a>
														</li>
															<li>
															<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="70-765.htm">70-765 Provisioning SQL Databases</a>
														</li>
															<li>
															<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="70-767.htm">70-767 Implementing a Data Warehouse Using SQL</a>
														</li>
														<li>
															<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="70-768.htm">70-768 Developing SQL Data Models</a>
														</li>
														<li>
															<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="70-777.htm">70-777  Implementing Microsoft Azure Cosmos DB Solutions</a>
														</li>
														<li>
															<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="70-778.htm">
                                  70-778 Analyzing and Visualizing Data with Microsoft Power BI</a>
														</li>
														<li>
															<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="70-779.htm">
                                 70-779 Analyzing and Visualizing Data with Microsoft Excel</a>
														</li>
												</ul>		 
											</li>
											
												<li>
												  <a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="#">Microsoft 365 exams</a>
													<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
												<ul class="dropdown-menu">
														<li>
															<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="md-100.htm">MD-100 Windows 10</a>
														</li>
															<li>
															<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="md-101.htm">MD-101 Managing Modern Desktops</a>
														</li>
															<li>
															<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="ms-100.htm">MS-100 Microsoft 365 Identity and Services</a>
														</li>
															<li>
															<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="ms-101.htm">MS-101 Microsoft 365 Mobility and Security</a>
														</li>
															<li>
															<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="ms-200.htm">MS-200 Planning and Configuring a Messaging Platform</a>
														</li>
															<li>
															<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="ms-201.htm">MS-201 Implementing a Hybrid and Secure Messaging Platform</a>
														</li>
															<li>
															<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="ms-300.htm">MS-300 Deploying Microsoft 365 Teamwork</a>
														</li>
														<li>
															<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="ms-301.htm">MS-301 Deploying SharePoint Server Hybrid</a>
														</li>
														<li>
															<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="ms-500.htm">MS-500 Microsoft 365 Security Administration</a>
														</li>
												</ul>
											</li>
											
												<li>
												<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="#">Modern Desktop exams</a>
													<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
												<ul class="dropdown-menu">
														<li>
															<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="md-100.htm">MD-100 Windows 10</a>
														</li>
														<li>
															<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="md-101.htm">MD-101 Managing Modern Desktops</a>
														</li>
														</ul>
											</li>
											
												<li>
												<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="#">windows Server 2016 exams </a>
													<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
												<ul class="dropdown-menu">
														<li>
															<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="70-740.htm">70-740 Installation, Storage, and Compute with Windows Server 2016</a>
														</li>
															<li>
															<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="70-741.htm">70-741 Networking with Windows Server 2016</a>
														</li>
															<li>
															<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="70-742.htm">70-742 Identity with Windows Server 2016</a>
														</li>
															<li>
															<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="70-743.htm">70-743 Upgrading Your Skills to MCSA: Windows Server 2016</a>
														</li>
														<li>
															<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="70-744.htm"> 70-744 Securing Windows Server 2016</a>
														</li>
														<li>
															<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="70-745.htm">70-745 Implementing a Software-Defined Datacenter</a>
														</li>
												</ul>
											</li>
											
												<li>
												<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="#">Microsoft Dynamics 365 exams </a>
													<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
													<ul class="dropdown-menu">
														<li>
															<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="mb-200.htm">MB-200 Microsoft Power Platform + Dynamics 365 Core</a>
														</li>
														<li>
															<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="mb-210.htm">MB-210 Microsoft Dynamics 365 Sales</a>
														</li>
															<li>
															<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="mb-220.htm">MB-220 Microsoft Dynamics 365 Marketing</a>
														</li>
															<li>
															<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="mb-230.htm">MB-230 Microsoft Dynamics 365 Customer Service</a>
														</li>
															<li>
															<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="mb-240.htm">MB-240 Microsoft Dynamics 365 Field Service</a>
														</li>
															<li>
															<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="mb-300.htm">MB-300 Microsoft Dynamics 365: Core Finance and Operations</a>
														</li>
															<li>
															<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="mb-310.htm">MB-310 Microsoft Dynamics 365 Finance</a>
														</li>
															<li>
															<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="mb-320.htm">MB-320 Microsoft Dynamics 365 Supply Chain Management, Manufacturing</a>
														</li>
															<li>
															<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="mb-330.htm">MB-330 Microsoft Dynamics 365 Supply Chain Management</a>
														</li>
															<li>
															<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="mb-400.htm">MB-400 Microsoft Power Apps + Dynamics 365 Developer</a>
														</li>
															<li>
															<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="mb-500.htm">MB-500 Microsoft Dynamics 365: Finance and Operations Apps Developer</a>
														</li>
															<li>
															<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="mb-900.htm">MB-900 Microsoft Dynamics 365 Fundamentals</a>
														</li>
													</ul>
											</li>
											
										</ul>
									</li>
									<li>
										<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											<?php if($this_page == "checkout") { ?>id="nav_act"
											<?php } ?> href="#">PMI
										</a>
											<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
										<ul class="dropdown-menu">
														<li>
															<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="pmp-acp.htm">PMP ACP</a>
														</li>
														<li>
															<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="pmp.htm">PMP</a>
														</li>
														</ul>
									</li>
									<li>
										<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="exam-juniper.htm">JUNIPER</a>
											<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
										<ul class="dropdown-menu">
											<li>
												<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="#">Data Center</a>
												<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
												<ul class="dropdown-menu menu_mrg">
													<li>
														<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="jncia-cert.htm?">JNCIA-JUNOS
														</a>
															<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
															<ul class="dropdown-menu menu_mrg">
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="JN0-102.htm">JN0-102</a>
															</li>
													</ul>
													</li>
														<li>
														<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="jncis-ent-cert.htm?">JNCIS-ENT</a>
															<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
														<ul class="dropdown-menu">
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="JN0-348.htm">JN0-348</a>
															</li>
													</ul>
													</li>
														<li>
														<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="jncip-dc-cert.htm?">JNCIP-DC</a>
															<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
														<ul class="dropdown-menu">
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="JN0-681.htm">JN0-681</a>
															</li>
													</ul>
													</li>
														<!---<li>
														<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="#">JNCIE-DC</a>
													</li>-->
											</ul>
											</li>
											<li>
												<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="#">Enterprise Infrastructure </a>
													<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
												<ul class="dropdown-menu">
													<li>
														<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="jncia-cert.htm?">JNCIA-JUNOS</a>
															<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
															<ul class="dropdown-menu menu_mrg">
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="JN0-102.htm">JN0-102</a>
															</li>
													</ul>
														
													</li>
													<li>
														<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="jncis-ent-cert.htm?">JNCIS-ENT</a>
															<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
															<ul class="dropdown-menu">
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="JN0-348.htm">JN0-348</a>
															</li>
													</ul>
													</li>
													<li>
														<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="jncip-ent-cert.htm?">JNCIP-ENT</a>
															<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
															<ul class="dropdown-menu">
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="JN0-647.htm">JN0-647</a>
															</li>
													</ul>
													</li>
													<!--<li>
														<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="#">JNCIE-ENT</a>
													</li>-->
													</ul>
											</li>
											<li>
												<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="#">Juniper Security </a>
														<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
													<ul class="dropdown-menu">
													<li>
														<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="associate-jncia-sec-cert.htm?">JNCIA-SEC</a>
															<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
														<ul class="dropdown-menu menu_mrg">
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="JN0-230.htm">JN0-230</a>
															</li>
													</ul>
													</li>
													<li>
														<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="jncis-sec-cert.htm?">JNCIS-SEC</a>
															<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
														<ul class="dropdown-menu">
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="JN0-334.htm">JN0-334</a>
															</li>
													</ul>
													</li>
													<li>
														<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="#">JNCIP-SEC</a>
													</li>
													<!--<li>
														<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="#">JNCIE-SEC</a>
													</li>-->
													</ul>
										</li>
										
										<li>
												<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="#">Service Provider Routing & Switching </a>
													<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
													<ul class="dropdown-menu">
													<li>
														<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="jncia-cert.htm?">JNCIA-JUNOS</a>
															<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
														<ul class="dropdown-menu menu_mrg">
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="JN0-102.htm">JN0-102</a>
															</li>
													</ul>
													</li>
													<!--<li>
														<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="#">JNCIS-SP </a>
													</li>-->
													<li>
														<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="jncip-sp-cert.htm?">JNCIP-SP </a>
															<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
														<ul class="dropdown-menu">
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="JN0-662.htm">JN0-662</a>
															</li>
													</ul>
													</li>
													<!--<li>
														<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="#">JNCIE-SP</a>
													</li>-->
													</ul>
										</li>
										
										<li>
												<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="#">Automation and DevOps </a>
														<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
													<ul class="dropdown-menu">
													<li>
														<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="automation-and-devops-cert.htm?">JNCIA-DevOps</a>
															<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
														<ul class="dropdown-menu menu_mrg">
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="JN0-220.htm">JN0-220</a>
															</li>
													</ul>
													</li>
													<!--<li>
														<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="#">JNCIS-DevOps</a>
													</li>-->
												
													</ul>
										</li>
										
											
										<li>
												<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="#">Juniper Cloud </a>
													<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
													<ul class="dropdown-menu">
													<li>
														<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="/jncia-cloud-cert.htm?">JNCIA-Cloud</span></a>
																<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
															<ul class="dropdown-menu menu_mrg">
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="JN0-210.htm">JN0-210</a>
															</li>
													</ul>
													</li>
													<li>
														<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="jncis-cloud-cert.htm?">JNCIS-Cloud </a>
															<a data-toggle="dropdown" class="dropdown-toggle responsive_caret" onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											href="#"><span class="caret"></span>
										</a>
															<ul class="dropdown-menu">
															<li>
																<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="JN0-411.htm">JN0-411</a>
															</li>
													</ul>
													</li>
													<li>
														<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="#">JNCIP-Cloud</a>
													</li>
													<!--<li>
														<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" href="#">JNCIE-Cloud</a>
													</li>-->
												
													</ul>
										</li>
										
										</ul>
									</li>
									<li>
										<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" id="nav_act" href="<?php echo BASE_URL; ?>group_study.php">Group Study</a>
									</li>
									<li>
										<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											<?php if($this_page == "proxy-exams") { ?>id="nav_act"
											<?php } ?> href="proxy-exams.html">Proxy Exams
										</a>
									</li>
									<li>
										<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" 
											<?php if($this_page == "test-centers") { ?>id="nav_act"
											<?php } ?> href="test-centers.html">Test Centers
										</a>
									</li>
									<li>
										<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" id="nav_act" href="<?php echo BASE_URL; ?>expert.html">Expert</a>
									</li>
									<li>
										<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" id="nav_act" href="<?php echo BASE_URL; ?>stable-unstable.html">Stable/Unstable</a>
									</li>
										
									
										<li>
										<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" id="nav_act" href="<?php echo BASE_URL; ?>agent.php">Agent</a>
									</li>
									
								
									<li>
										<a onmouseover="PlaySound('mySound')" onmouseout="StopSound('mySound')" id="nav_act" href="<?php echo BASE_URL; ?>blog/">Ask</a>
									</li>
								</ul>
          </div>
          
          
          
      <audio id='mySound' src='css/tick.mp3'/>
							<script>
function PlaySound(soundobj) {
var thissound=document.getElementById(soundobj);
thissound.play();
}

function StopSound(soundobj) {
var thissound=document.getElementById(soundobj);
thissound.pause();
thissound.currentTime = 0;
}
</script>

        </nav>
        </div>
      </div> 
    </div>            
  </div><!-- /.header_bottom -->
</section>





   <script>
            (function($){
		$('.dropdown-menu a.dropdown-toggle').on('click', function(e) {
		  if (!$(this).next().hasClass('show')) {
			$(this).parents('.dropdown-menu').first().find('.show').removeClass("show");
		  }
		  var $subMenu = $(this).next(".dropdown-menu");
		  $subMenu.toggleClass('show');

		  $(this).parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function(e) {
			$('.dropdown-submenu .show').removeClass("show");
		  });

		  return false;
		});
	})(jQuery)
          
      </script>
      

      


