<? include("includes/header.php");?>

<style>
    .groupbox {
    width: 25%;
    float: left;
    margin-top: 13px;
    margin-bottom: 20px;
}
.groupbox:nth-child(1) {
    margin-left: 12%;
}

.toplogy_img img {
    width: 900px;
    margin-left: auto;
    margin-right: auto;
    display: block;
    padding-top: 12px;
}

@media(max-width:992px) and (min-width:768px)
{
     .rack_session img
    {
        width:100%;
        padding-bottom:20px;
    }
    .groupbox {
       width: 48%;
    float: left;
    margin-top: 0px;
    margin-bottom: 0;
    margin-right: 12px;
}
.session_btn a {
    display: block;
    text-transform: capitalize;
    max-width: 100%;
   
}
}


@media(max-width:767px)
{
    .rack_session img
    {
        width:100%;
        padding-bottom:20px;
    }
    .groupbox {
    width: 100%;
    float: left;
    margin-top: 0px;
    margin-bottom: 0;
}
.session_btn a {
    display: block;
    text-transform: capitalize;
    margin-top: 15px;
    max-width: 100%;
   
}
 .groupbox:nth-child(1) {
    margin-left: 0 !important;
}
.toplogy_img img {
    width: 100%;
}
}

    
</style>
<div class="content-box paddtop60 paddbottom60">
        <div class="exam-group-box">
            <div class="max-width">
                 <div class="main_heading paddbtm10 text-center">CCIE Enterprise Wireless v1.0 Real Lab  <span>Demo </span></div>
                
<!--  <div class="row">-->
<!--    <div class="toplogy_img">-->
<!--<img src="images/chinese_wire_topology.jpg" width="85%">-->
<!--   </div>-->
<!--  </div>-->

                <table width="800" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td width="185" valign="top">
			<div w3-include-html="inc_mzone.html"></div>
			<div w3-include-html="inc_certifications.html"></div>
			<div w3-include-html="inc_rupdates.html"></div>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="10" ></td>
  </tr>
  <tr>
    
  </tr>
</table>

			
		  	</td>
          
          </tr>
      </table>
      <dibv class="session_btn">
     <div class="col-md-12">
				    <div class="groupbox" style="border-top:0px;"> 
				              <a class="submit_btn" href="images/chinese_wire_topology.jpg" download="chinese_wire_topology.jpg">Download Topology <i class="fa fa-download"></i></a>
				     </div>
				     
				      <!--- <div class="groupbox">
				              <a class="submit_btn" target="_blank" href="https://bit.ly/3tHAK3G">Download EVE <i class="fa fa-download"></i></a>
				     </div>--->
				     
				     <div class="groupbox">
				              <a class="submit_btn" href="images/ent_wirelss_deploy.pdf" download="ent_wirelss_deploy.pdf">Download Deploy <i class="fa fa-download"></i></a>
				     </div>
				     
				      <div class="groupbox">
				              <a class="submit_btn" href="images/ent_wirelss_design.pdf" download="ent_wirelss_design.pdf"> Download Design <i class="fa fa-download"></i></a>
				     </div>
				     
				       
				     
				</div>
          </div>
                <?php //echo $getPage[2]; ?>
            </div>
        </div>
	</div>
<? include("includes/footer.php");?>
