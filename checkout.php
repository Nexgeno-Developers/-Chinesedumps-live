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



	$package_price = 199.00;





$getPage[4] = "Chinesedumps Chckout Page";

$getPage[5] = "Chinesedumps Chckout Page";

$getPage[0] = "Chinesedumps Chckout Page";



?>



<?php include("includes/header.php");?>

<script language="JavaScript" type="text/javascript" src="js/addcartmonthly.js"></script>

<div class="content-box">

        <div class="about-chinesedumps">

            <div class="max-width">

                <h1 class="center-heading"><span>Unlimited Secure PDC Exams Package for Lifetime</span></h1>

                <div class="black-heading">You are going to purchase any of the following Packages</div>    

            </div>

            <div class="max-width">

            	<div class="cart-packages" style="width:25%;">

                    <div class="group-box">

                        <div class="package-heading">

                            <div><a href="#" style="font-size:20px;">Unlimited Access (1 Months)</a></div>

                            <div style="width:42%;">Unlimited Access Over 3200+ Secure PDC Exams for Only $19.99/ Month</div>

                        </div>

                        <div class="certification-price certification-price03" style="width:159px; padding: 50px 0px 0px 15px;"> <span>$</span>19.99</div>

                        <div class="button-box group">&nbsp;

                        <button class="orange-button" onclick='submitPromonthly("1");'>Buy Now!</button>

                        </div>

                    </div>

                </div>

                <div class="cart-packages" style="width:25%;">

                    <div class="group-box">

                        <div class="package-heading">

                            <div><a href="#" style="font-size:20px;">Unlimited Access (3 Months)</a></div>

                            <div style="width:42%;">Unlimited Access Over 3200+ Secure PDC Exams for Only $19.99/ Month</div>

                        </div>

                        <div class="certification-price certification-price03" style="width:159px;padding: 50px 0px 0px 15px;"> <span>$</span>59.97</div>

                        <div class="button-box group">&nbsp;

                        <button class="orange-button" onclick='submitPromonthly("3");'>Buy Now!</button>

                        </div>

                    </div>

                </div>

                <div class="cart-packages" style="width:25%;">

                    <div class="group-box">

                        <div class="package-heading">

                            <div><a href="#" style="font-size:20px;">Unlimited Access (6 Months)</a></div>

                            <div style="width:42%;">Unlimited Access Over 3200+ Secure PDC Exams for Only $19.99/ Month</div>

                        </div>

                        <div class="certification-price certification-price03" style="width:159px;padding: 50px 0px 0px 15px;"> <span>$</span>119.94</div>

                        <div class="button-box group">&nbsp;

                        <button class="orange-button" onclick='submitPromonthly("6");'>Buy Now!</button>

                        </div>

                    </div>

                </div>

                <div class="cart-packages" style="width:25%;">

                    <div class="group-box">

                        <div class="package-heading">

                            <div><a href="#" style="font-size:20px;">Unlimited Access (12 Months)</a></div>

                            <div style="width:42%;">Unlimited Access Over 3200+ Secure PDC Exams for Only $19.99/ Month</div>

                        </div>

                        <div class="certification-price certification-price03" style="width:159px;padding: 50px 0px 0px 15px; "> <span>$</span>239.88</div>

                        <div class="button-box group">&nbsp;

                        <button class="orange-button" onclick='submitPromonthly("12");'>Buy Now!</button>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        

        

        

        

        

        

        

	</div>

	</div>

              <? include("includes/footer.php");?>

