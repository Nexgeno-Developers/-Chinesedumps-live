<? 
include("includes/header.php");

$base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
?>
<style>
.lead{
    margin-bottom:1px;
    font-weight: 300;
    line-height :1.4;
}

.thank_you img {
    width: 170px;
    margin: 10px 0px 20px;
}
.thank_you h4 {
    font-size: 60px;
    font-family: cursive;
}
.thank_you a{
      background-color: #3c85ba;
    color: #fff;
    padding: 10px 20px;
    border-radius: 15px;
    text-decoration: none !important;
    font-size: 17px;
    margin-top: 30px;
    display: inline-block;
    font-weight: 500;
}

.thank_you a:hover
{
    background: #363636 !important;
} 
@media(max-width:767px)
{
    .thank_you img {
        width: 120px;
        margin: 10px 0px 20px;
    }
    .thank_you h4 {
        font-size: 42px;
        font-family: cursive;
    }
}
</style>
<div class="content-box">
	<div class="exam-group-box paddtop60 paddbottom60">
		<div class="max-width">
			<div class="col-md-12">
			    <div class="thank_you text-center">
                    <h4>Thanks for reaching out!</h4> <img src="images/thank_youimg.png">
                    <p class="lead"><strong>Please check your email and click on  the verification link to confirm and proceed.</strong>
                    <p class="leads"><strong>One of our team members will contact you within 24 hours to help you with your query.</strong>
                    <br><a href="<?php echo $base_url; ?>">Go Home Page</a>
                    </p>
                </div>
			</div>
		</div>
	</div>
</div>
<? include("includes/footer.php");?>