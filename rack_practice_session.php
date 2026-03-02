<? include("includes/header.php");?>

<style>
    .groupbox {
    width: 25%;
    float: left;
    margin-top: 13px;
    margin-bottom: 20px;
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
    
</style>
<div class="content-box rack_session paddbottom60">
        <div class="exam-group-box">
            <div class="max-width">
                 
                 <div class="main_heading text-center paddbtm10 paddtpbtm">CCIE Enterprise Infrastructure v1.1 <span>Real Lab Logical Topology</span></div>
                
  <div class="row">
    <div class="col-sm-6">
<img src="images/CCIE_ei_topology.jpg" width="85%">
   </div>
    <div class="col-sm-6">
<iframe class="video_iframes" src="https://www.youtube.com/embed/JrAkLL-Fyyw" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen=""></iframe>

    </div>
  </div>

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
				              <a class="submit_btn" href="img/CCIE_ei_topology.jpg" download="CCIE_ei_topology.jpg">Download Topology <i class="fa fa-download"></i></a>
				     </div>
				     
				       <div class="groupbox">
				              <a class="submit_btn" target="_blank" href="https://bit.ly/3tHAK3G">Download EVE <i class="fa fa-download"></i></a>
				     </div>
				     
				     <div class="groupbox">
				              <a class="submit_btn" href="img/CCIE_ei__Deploy.pdf" download="CCIE_ei__Deploy.pdf">Download Deploy <i class="fa fa-download"></i></a>
				     </div>
				     
				      <div class="groupbox">
				              <a class="submit_btn" href="img/CCIE-EI-v1.0-Design-Demo.pdf" download="CCIE-EI-v1.0-Design-Demo.pdf"> Download Design <i class="fa fa-download"></i></a>
				     </div>
				     
				       
				     
				</div>
          </div>
                <?php //echo $getPage[2]; ?>
            </div>
        </div>
<? include("includes/footer.php");?>
