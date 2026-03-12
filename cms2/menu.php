<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>

<?php
// Get current page for active state
$current_page = basename($_SERVER['PHP_SELF']);
function is_active($page_url) {
	global $current_page;
	$menu_page = basename($page_url);
	if (strpos($page_url, '?') !== false) {
		$menu_page = basename(parse_url($page_url, PHP_URL_PATH));
		$query_string = parse_url($page_url, PHP_URL_QUERY);
		if ($query_string && isset($_GET)) {
			parse_str($query_string, $query_params);
			foreach ($query_params as $key => $value) {
				if (isset($_GET[$key]) && $_GET[$key] == $value) {
					return ($current_page == $menu_page) ? ' active' : '';
				}
			}
		}
	}
	return ($current_page == $menu_page) ? ' active' : '';
}
?>

<TABLE width="250" cellPadding=0 cellSpacing=0 class=menu>

        <TBODY>

        <TR>

          <TD width="198" class=menu_header>Global</TD>

        </TR>

        <TR>

          <TD><IMG src="images/admin_menu_bar.gif" border=0></TD></TR>

        <tr>

	      <td class="menu"><a class="menu<?=is_active('global.php')?>" href="global.php"><i class="fa-solid fa-gear"></i> Configuration</a></td>

        </tr>

        <TR>

          <TD class=menu><A class=menu 

            href="sum.php" class="menu<?=is_active('sum.php')?>"><i class="fa-solid fa-chart-line"></i> Summary</A></TD>

        </TR>

	

        <TR>

          <TD class=menu><A class=menu 

            href="password.php" class="menu<?=is_active('password.php')?>"><i class="fa-solid fa-key"></i> Change Password </A></TD>

        </TR>

      

		

		 <TR>

          <TD class=menu><A class=menu 

            href="copyright.php" class="menu<?=is_active('copyright.php')?>"><i class="fa-solid fa-copyright"></i> Website Copyright</A></TD>

        </TR>

        <TR>

        <?php /*
          <TD class=menu><A class=menu 

            href="contactus.php" class="menu<?=is_active('contactus.php')?>"><i class="fa-solid fa-envelope"></i> Change Email Address</A></TD>

        </TR>
        */ ?>

        <TR>

          <TD class=menu><A class=menu 

            href="logout.php" class="menu<?=is_active('logout.php')?>"><i class="fa-solid fa-right-from-bracket"></i> Logout</A></TD>

        </TR></TBODY></TABLE>

        

<TABLE width="250" cellPadding=0 cellSpacing=0 class=menu2>

        <TBODY>

        <TR>

          <TD width="198" class=menu_header>Stats</TD>

        </TR>

        <TR>

          <TD><IMG src="images/admin_menu_bar.gif" border=0></TD></TR>

        <TR>

          <TD class=menu><A class=menu 

            href="stats.php" class="menu<?=is_active('stats.php')?>"><i class="fa-solid fa-chart-bar"></i> Quick Stats</A></TD>

        </TR>

        <TR>

          <TD class=menu><A class=menu 

            href="userstats.php?ref=all" class="menu<?=is_active('userstats.php') && isset($_GET['ref']) && $_GET['ref'] == 'all' ? ' active' : ''?>"><i class="fa-solid fa-users"></i> All Registered Users</A></TD>

        </TR>

        <TR>

          <TD class=menu><A class=menu 

            href="userstats.php?ref=p"><i class="fa-solid fa-cart-shopping"></i> Users with Purchse(s)</A></TD>

        </TR>

        <TR>

          <TD class=menu><A class=menu 

            href="userstats.php?ref=np"><i class="fa-solid fa-user-slash"></i> Users without Purchse</A></TD>

</TR></TBODY></TABLE>

<TABLE width="250" cellPadding=0 cellSpacing=0 class=menu2>

        <TBODY>

        <TR>

          <TD width="198" class=menu_header>Sliders</TD>

        </TR>

        <TR>

          <TD><IMG src="images/admin_menu_bar.gif" border=0></TD></TR>

        <TR>

          <TD class=menu><A class=menu 

            href="bannermanage.php" class="menu<?=is_active('bannermanage.php')?>"><i class="fa-solid fa-images"></i> Banners Management</A></TD>

        </TR>

        <TR>

          <TD class=menu><A class=menu 

            href="addslider.php" class="menu<?=is_active('addslider.php')?>"><i class="fa-solid fa-plus-circle"></i> Add Banner</A></TD>

        </TR>

        <TR>

          <TD class=menu><A class=menu 

            href="userstats.php?ref=p"><i class="fa-solid fa-cart-shopping"></i> Users with Purchse(s)</A></TD>

        </TR>

        <TR>

          <TD class=menu><A class=menu 

            href="userstats.php?ref=np"><i class="fa-solid fa-user-slash"></i> Users without Purchse</A></TD>

</TR></TBODY></TABLE>   








<TABLE width="250" cellPadding=0 cellSpacing=0 class=menu2>

        <TBODY>

        <TR>

          <TD width="198" class=menu_header>Gallery</TD>

        </TR>

        <TR>

          <TD><IMG src="images/admin_menu_bar.gif" border=0></TD></TR>

        <TR>

          <TD class=menu><A class=menu 

            href="galley_management.php" class="menu<?=is_active('galley_management.php')?>"><i class="fa-solid fa-photo-film"></i> Gallery Management</A></TD>

        </TR>

        <TR>

          <TD class=menu><A class=menu 

            href="add_gallery.php" class="menu<?=is_active('add_gallery.php')?>"><i class="fa-solid fa-plus"></i> Add Gallery</A></TD>

        </TR>

       

    </TBODY></TABLE>   





     

        

<TABLE width="250" cellPadding=0 cellSpacing=0 class=menu2>

        <TBODY>

        <TR>

          <TD width="198" class=menu_header>Bulk Update</TD>

        </TR>

        <TR>

          <TD><IMG src="images/admin_menu_bar.gif" border=0></TD></TR>

        <TR>

          <TD class=menu><A class="menu<?=is_active('bulk_update.php') && isset($_GET['action']) && $_GET['action'] == 'all_date' ? ' active' : ''?>" href="bulk_update.php?action=all_date"><i class="fa-solid fa-calendar-days"></i> Update Date of all Exams</A></TD>

        </TR>

        <TR>

          <TD class=menu><A class="menu<?=is_active('bulk_update.php') && isset($_GET['action']) && $_GET['action'] == 'bundle_date' ? ' active' : ''?>" href="bulk_update.php?action=bundle_date"><i class="fa-solid fa-calendar-days"></i> Update Date of Exams in Vendor</A></TD>

        </TR>

        <TR>

          <TD class=menu><A class="menu<?=is_active('bulk_update.php') && isset($_GET['action']) && $_GET['action'] == 'cert_date' ? ' active' : ''?>" href="bulk_update.php?action=cert_date"><i class="fa-solid fa-calendar-days"></i> Update Date of Exams in Certificates</A></TD>

        </TR>

        <TR>

          <TD class=menu><A class="menu<?=is_active('bulk_update.php') && isset($_GET['action']) && $_GET['action'] == 'all_price' ? ' active' : ''?>" href="bulk_update.php?action=all_price"><i class="fa-solid fa-dollar-sign"></i> Update Price of all Exams</A></TD>

        </TR>

        <TR>

          <TD class=menu><A class="menu<?=is_active('bulk_update.php') && isset($_GET['action']) && $_GET['action'] == 'bundle_price' ? ' active' : ''?>" href="bulk_update.php?action=bundle_price"><i class="fa-solid fa-dollar-sign"></i> Update Price of Exams in Vendor</A></TD>

        </TR>

        <TR>

          <TD class=menu><A class="menu<?=is_active('bulk_update.php') && isset($_GET['action']) && $_GET['action'] == 'cert_price' ? ' active' : ''?>" href="bulk_update.php?action=cert_price"><i class="fa-solid fa-dollar-sign"></i> Update Price of Exams in Certificates</A></TD>

        </TR>

</TBODY></TABLE>        

        <table width="250" cellpadding="0" cellspacing="0" class="menu2">

        <tbody>

          <tr>

            <td width="198" class="menu_header">Products Module</td>

          </tr>

          <tr>

            <td><img src="images/admin_menu_bar.gif" border="0" /></td>

          </tr>

		  <tr>

	      <td class="menu"><a class="menu<?=is_active('addvendors.php')?>" href="addvendors.php"><i class="fa-solid fa-plus"></i> Add Vendors</a></td>

        </tr>

		  <tr>

	      <td class="menu"><a class="menu<?=is_active('vendormanage.php')?>" href="vendormanage.php"><i class="fa-solid fa-store"></i> Vendors Management</a></td>

        </tr>

         <tr>

	      <td class="menu"><a class="menu<?=is_active('addcertif.php')?>" href="addcertif.php"><i class="fa-solid fa-certificate"></i> Add Certification</a></td>

        </tr>

		  <tr>

	      <td class="menu"><a class="menu<?=is_active('certifmanage.php')?>" href="certifmanage.php"><i class="fa-solid fa-certificate"></i> Certifications Management</a></td>

        </tr>

        <tr>

	      <td class="menu"><a class="menu<?=is_active('addexam.php')?>" href="addexam.php"><i class="fa-solid fa-plus"></i> Add Exam</a></td>

        </tr>

		  <tr>

	      <td class="menu"><a class="menu<?=is_active('exammanage.php')?>" href="exammanage.php"><i class="fa-solid fa-clipboard-list"></i> Exams Management</a></td>

        </tr>

			  <tr>

	      <td class="menu"><a class="menu<?=is_active('addfiles.php')?>" href="addfiles.php"><i class="fa-solid fa-layer-group"></i> Add Multi Exams</a></td>

        </tr>

		  <tr>

	      <td class="menu"><a class="menu<?=is_active('updateDate.php')?>" href="updateDate.php"><i class="fa-solid fa-calendar"></i> Update Exam Date</a></td>

        </tr>

        </tbody>

</table>

	    <table width="250" cellpadding="0" cellspacing="0" class="menu2">

          <tbody>

            <tr>

              <td width="198" class="menu_header">Demo &amp; Exams  Mangement Modules</td>

            </tr>

            <tr>

              <td><img src="images/admin_menu_bar.gif" border="0" /></td>

            </tr>

            

            <tr>

              <td class="menu"><a class="menu" 

            href="view_demoDownload.php" class="menu<?=is_active('view_demoDownload.php')?>"><i class="fa-solid fa-envelope-open"></i> View Demo Emails </a></td>

            </tr>

            <tr>

              <td class="menu"><a class="menu" 

            href="exam_request.php" class="menu<?=is_active('exam_request.php')?>"><i class="fa-solid fa-envelope"></i> View Exam Request Email </a></td>

            </tr>

            <tr>

              <td class="menu"><a class="menu" 

            href="managefull.php" class="menu<?=is_active('managefull.php')?>"><i class="fa-solid fa-download"></i> Full Download Management</a></td>

            </tr>

          </tbody>

</table>

	    <table width="250" cellpadding="0" cellspacing="0" class="menu2">

        <tbody>

          <tr>

            <td width="198" class="menu_header">Cart Mangement Modules</td>

          </tr>

          <tr>

            <td><img src="images/admin_menu_bar.gif" border="0" /></td>

          </tr>

           

           <tr>

	      <td class="menu"><a class="menu" 

            href="masterorder.php" class="menu<?=is_active('masterorder.php')?>"><i class="fa-solid fa-shopping-bag"></i> Final Orders</a></td>

        </tr>

        <tr>

	      <td class="menu"><a class="menu" 

            href="odrproces.php" class="menu<?=is_active('odrproces.php')?>"><i class="fa-solid fa-list"></i> List Cart</a></td>

        </tr>

       <tr>

	      <td class="menu"><a class="menu" 

            href="reodrproces.php" class="menu<?=is_active('reodrproces.php')?>"><i class="fa-solid fa-rotate-right"></i> Re-Order Cart</a></td>

        </tr>

       

	

		</tbody>

</table>

<table width="250" cellpadding="0" cellspacing="0" class="menu2">

			<tbody>

				<tr>

					<td width="198" class="menu_header">Coupon Mangement Module</td>

				</tr>

				<tr>

					<td><img src="images/admin_menu_bar.gif" border="0" /></td>

				</tr>

				 <tr>

			<td class="menu"><a class="menu" 

					href="managecoupon.php" class="menu<?=is_active('managecoupon.php')?>"><i class="fa-solid fa-ticket"></i> Manage Coupons</a></td>

			</tr>

			<tr>

			<td class="menu"><a class="menu" 

					href="addcoupon.php" class="menu<?=is_active('addcoupon.php')?>"><i class="fa-solid fa-plus"></i> Add new Coupon</a></td>

			</tr>	

	</tbody>

</table>


<table width="250" cellpadding="0" cellspacing="0" class="menu2">

			<tbody>

				<tr>

					<td width="198" class="menu_header">Stable/Unstable Course Management Module</td>

				</tr>

				<tr>

					<td><img src="images/admin_menu_bar.gif" border="0" /></td>

				</tr>

				<tr>

					<td class="menu"><a class="menu" 

					href="courses_status_manage.php" class="menu<?=is_active('courses_status_manage.php')?>"><i class="fa-solid fa-book"></i> Manage Course</a></td>

			</tr>

			<tr>

			<td class="menu"><a class="menu" 

					href="course_add.php" class="menu<?=is_active('course_add.php')?>"><i class="fa-solid fa-plus"></i> Add new Course</a></td>

			</tr>	

	</tbody>

</table>

<table width="250" cellpadding="0" cellspacing="0" class="menu2">



        <tbody>



          <tr>



            <td width="198" class="menu_header">Exam Mangement Module</td>



          </tr>



          <tr>



            <td><img src="images/admin_menu_bar.gif" border="0" /></td>



          </tr>



        	

  



		<tr>



	      <td class="menu"><a class="menu" 



            href="availableSoon.php"><img src="images/News-Edit.gif" width="16" height="16" border="0" 



            class="icon2" />Available Soon</a></td>



        </tr>



		</tbody>



</table>

      <table width="250" cellpadding="0" cellspacing="0" class="menu2">

        <tbody>

          <tr>

            <td width="198" class="menu_header">Global Content</td>

          </tr>

          <tr>

            <td><img src="images/admin_menu_bar.gif" border="0" /></td>

          </tr>

		  

          <tr>

			  <td class="menu">

			  <a class="menu<?=is_active('editemails.php')?>" href="editemails.php"><i class="fa-solid fa-envelope"></i> Edit Emails</a>			  </td>

		  </tr>

        

        

        <tr>

	      <td class="menu"><a class="menu<?=is_active('vendorgloble.php') && isset($_GET['pid']) && $_GET['pid'] == '1' ? ' active' : ''?>" href="vendorgloble.php?pid=1"><i class="fa-solid fa-globe"></i> Vendor Global Content</a></td>

        </tr>

        <tr>

	      <td class="menu"><a class="menu<?=is_active('vendorgloble.php') && isset($_GET['pid']) && $_GET['pid'] == '2' ? ' active' : ''?>" href="vendorgloble.php?pid=2"><i class="fa-solid fa-globe"></i> Certification Global Content</a></td>

        </tr>

        <tr>

	      <td class="menu"><a class="menu<?=is_active('vendorgloble.php') && isset($_GET['pid']) && $_GET['pid'] == '3' ? ' active' : ''?>" href="vendorgloble.php?pid=3"><i class="fa-solid fa-globe"></i> Exam Global Content</a></td>

        </tr>

        

        

         

       		

		  <?php if(permission("Articles","Read")==TRUE){ 

		  	 if(permission("Articles","Write")==TRUE){ ?>

	  <!-- <tr>

	      <td class="menu"><a class="menu" 

            href="addarticles.php"><img src="images/News-Add.gif" width="16" height="16" border="0" 

            class="icon2" />Add Articles</a></td>

        </tr>-->

		<? } ?>

	   <!-- <tr>

	      <td class="menu"><a class="menu" 

            href="articlesmanage.php"><img src="images/News-Edit.gif" width="16" height="16" border="0" 

            class="icon2" />Articles Management</span></a></td>

        </tr> -->

	  <? } ?>

		</tbody>

</table>

      <table width="250" cellpadding="0" cellspacing="0" class="menu2">

        <tbody>

          <tr>

            <td width="198" class="menu_header">Users Module</td>

          </tr>

          <tr>

            <td><img src="images/admin_menu_bar.gif" border="0" /></td>

          </tr>

		  

	     <tr>

	      <td class="menu"><a class="menu" 

            href="adduser.php" class="menu<?=is_active('adduser.php')?>"><i class="fa-solid fa-user-plus"></i> Add <span class="menu_header">User</span></a></td>

        </tr>

		<tr>

	      <td class="menu"><a class="menu" 

            href="manageuser.php" class="menu<?=is_active('manageuser.php')?>"><i class="fa-solid fa-users-gear"></i> User<span class="menu_header"> Management</span></a></td>

        </tr>

        

			   </tr>

        </tbody>

</table>

	  <?php if(permission("Articles","Read")==TRUE){ ?>

		<table width="250" cellpadding="0" cellspacing="0" class="menu2">

        <tbody>

          <tr>

            <td width="198" class="menu_header">Articles Module</td>

          </tr>

          <tr>

            <td><img src="images/admin_menu_bar.gif" border="0" /></td>

          </tr>

		  <?php if(permission("Articles","Write")==TRUE){ ?>

	     <tr>

	      <td class="menu"><a class="menu" 

            href="addarticles.php"><i class="fa-solid fa-plus"></i> Add <span class="menu_header">Articles</span></a></td>

        </tr>

		<? } ?>

	    <tr>

	      <td class="menu"><a class="menu" 

            href="articlesmanage.php"><i class="fa-solid fa-newspaper"></i> Articles<span class="menu_header"> Management</span></a></td>

        </tr>

			   </tr>

        </tbody>

</table>

	   <? } ?>

	  <?php if(permission("Contents Management","Read")==TRUE){ 

	 

	  ?>

     

	  <?php if(permission("Contents Management","Read")==TRUE){ 

	 

	  ?>  

      

      <TABLE width="250" cellPadding=0 cellSpacing=0 class=menu2>

        <TBODY>

        <TR>

          <TD width="168" class=menu_header>Manage Content</TD>

        </TR>

        <TR>

          <TD background="images/admin_menu_bar.gif"><IMG src="images/admin_menu_bar.gif" border=0></TD>

        </TR>

		  <tr>

          <td class="menu"><a class="menu" href="editpagetext.php?pid=1"><i class="fa-solid fa-house"></i> Home</a></td>

        </tr>

		<tr>

          <td class="menu"><a class="menu" href="editpagetext.php?pid=4"><i class="fa-solid fa-store"></i> All Vendors</a></td>

        </tr>

        <tr>

          <td class="menu"><a class="menu" href="editpagetext.php?pid=5"><i class="fa-solid fa-right-to-bracket"></i> Login Page</a></td>

        </tr>

        <tr>

          <td class="menu"><a class="menu" href="editpagetext.php?pid=6"><i class="fa-solid fa-key"></i> Forgot Your Password</a></td>

        </tr>

         <tr>

          <td class="menu"><a class="menu" href="editpagetext.php?pid=7"><i class="fa-solid fa-headset"></i> Support</a></td>

        </tr>

         <tr>

          <td class="menu"><a class="menu" href="editpagetext.php?pid=8"><i class="fa-solid fa-address-book"></i> Contact Us</a></td>

        </tr>

         <tr>

          <td class="menu"><a class="menu" href="editpagetext.php?pid=9"><i class="fa-solid fa-circle-question"></i> FAQs</a></td>

        </tr>

         <tr>

          <td class="menu"><a class="menu" href="editpagetext.php?pid=11"><i class="fa-solid fa-credit-card"></i> Paypal Payment Page</a></td>

        </tr>

		<tr>

          <td class="menu"><a class="menu" href="editpagetext.php?pid=2"><i class="fa-solid fa-shield"></i> Guarantee</a></td>

        </tr>

        <tr>

          <td class="menu"><a class="menu" href="editpagetext.php?pid=3"><i class="fa-solid fa-lock"></i> Privacy and Policy</a></td>

        </tr>

        <tr>

          <td class="menu"><a class="menu" href="editpagetext.php?pid=13"><i class="fa-solid fa-thumbs-up"></i> Thanks Page</a></td>

        </tr>

		<tr>

          <td class="menu"><a class="menu" href="editpagetext.php?pid=14"><i class="fa-solid fa-triangle-exclamation"></i> File Not Found Page</a></td>

        </tr>

        <tr>

          <td class="menu"><a class="menu" href="editpagetext.php?pid=21"><i class="fa-solid fa-quote-left"></i> Testimonials</a></td>

        </tr>        

		<tr>

          <td class="menu"><a class="menu" href="editpagetext.php?pid=26"><i class="fa-solid fa-play-circle"></i> Demo</a></td>

        </tr>

		<tr>

          <td class="menu"><a class="menu" href="editpagetext.php?pid=29"><i class="fa-solid fa-circle-info"></i> About Us</a></td>

        </tr>

		<tr>

          <td class="menu"><a class="menu" href="editpagetext.php?pid=30"><i class="fa-solid fa-briefcase"></i> Services</a></td>

        </tr>

		<tr>

          <td class="menu"><a class="menu" href="editpagetext.php?pid=33"><i class="fa-solid fa-gavel"></i> Disclaimer</a></td>

        </tr>

		<tr>

          <td class="menu"><a class="menu" href="editpagetext.php?pid=31"><i class="fa-solid fa-house"></i> Home Text 2</a></td>

        </tr>

		<tr>

          <td class="menu"><a class="menu" href="editpagetext.php?pid=32"><i class="fa-solid fa-house"></i> Home Text 3</a></td>

        </tr>

</TBODY></TABLE>

      <? } ?>  

	  

	  <? } ?>