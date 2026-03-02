<?php $this_page = "exams" ?>

<?php

ob_start();

session_start();

include("includes/config/classDbConnection.php");



include("includes/common/classes/classmain.php");

include("includes/common/classes/classProductMain.php");

include("includes/common/classes/classcart.php");

include("includes/common/classes/classUser.php");

include("includes/shoppingcart.php");



$objDBcon   =   new classDbConnection; 

$objMain	=	new classMain($objDBcon);

$objPro		=	new classProduct($objDBcon);

$objCart	=	new classCart($objDBcon);

$objUser	=	new classUser($objDBcon);





$getPage	=	$objMain->getContent(1);







    $quer	=	$objDBcon->Sql_Query_Exec('*','website','');



	$exec	=	mysql_fetch_array($quer);

 	$copyright	=	$exec['copyright'];



	$phonephone	=	$exec['phone'];



	

 	

 	$srch = $_POST["search"];



?>



<script language="javascript">

//submit form to the same page using js

function GoToPage(page)

{

	document.getElementById('current_page').value=page;

	document.frmPaging.submit();

}

</script>

<style>

/*************** Pagination ***************/



.pagination {

                text-align: right;

                padding: 20px 0 5px 0;

                font-family: Arial, Helvetica, sans-serif;

                font-size: 10px;

                }

.pagination a {

                margin: 0 5px 0 0;

                padding: 3px 6px;

                }



.pagination a.number {

				border: 1px solid #ddd;

                }



.pagination a.current {

/*                background: #469400 url('../images/bg-button-green.gif') top left repeat-x !important;*/

				background: #0D5289 !important;

                border-color: #0D5289 !important;

                color: #fff !important;

                }

				

.pagination a.current:hover {

				text-decoration: underline;

                }

.search_listing_pg .max-width.paddtop60.paddbottom60 a {
    background: #f4f4f4;
    padding: 6px 10px !important;
    border-radius: 4px;
    color: #333;
    float: left;
    margin-right: 10px;
    margin-bottom: 11px;
    font-size: 13px !important;
    font-weight: 600;
}

label.text-left {
    padding-top: 10px;
}
</style>

<? include("includes/header.php");?>

<div class="content-box ">

<div class="certification-box  certification-box03">
						<div class="container">
							<div id="destination" class="blutext" style="padding-left:5px;"> <a href="<?php echo BASE_URL; ?>" class="blutext">Home</a> &gt;
								<a href="" class="blutext">
									Search								</a>			</div>
													</div>
					</div>
					
					
        <div class="exam-group-box search_listing_pg">

            <div class="max-width paddtop60 paddbottom60">

            

            <div align="center">

                            <?php
 
                            //$srch = str_replace("-", ' ', $srch);
                            // $queryPop = "SELECT * FROM tbl_exam 
                            //  WHERE exam_status = '1' 
                            //  AND MATCH (exam_name, exam_url, exam_fullname) 
                            //  AGAINST ('+$srch' IN BOOLEAN MODE)";
                             
                            // $queryPop = "SELECT * FROM tbl_exam 
                            //              WHERE exam_status = '1' 
                            //              AND (exam_name LIKE '%$srch%' 
                            //              OR exam_url LIKE '%$srch%' 
                            //              OR exam_fullname LIKE '%$srch%')"; 
                            
                            
                            $srch = str_replace("-", ' ', $srch); // Replace hyphens with spaces
                            $words = explode(" ", $srch); // Break into words
                            $plusSearch = '';
                            
                            foreach ($words as $word) {
                                if (!empty($word)) {
                                    $plusSearch .= '+' . $word . ' ';
                                }
                            }
                            $plusSearch = trim($plusSearch); // Remove trailing space
                            
                            $queryPop = "SELECT * FROM tbl_exam 
                                         WHERE exam_status = '1' 
                                         AND MATCH (exam_name, exam_url, exam_fullname) 
                                         AGAINST ('$plusSearch' IN BOOLEAN MODE)";                                

                            //var_dump($queryPop);
                            $res7 = mysql_query($queryPop);
                            $total_pages = mysql_num_rows($res7);
                
                            if ($total_pages > 0) {
                                echo '<div class="main_heading paddbtm10 text-left"> Search   <span>Results </span></div>';
                                while ($row = mysql_fetch_assoc($res7)) {
                                    echo '<a href="' . BASE_URL . $row['exam_url'] . '.htm">' . htmlspecialchars($row['exam_fullname']) .' ('. $row['exam_name'] . ')</a>';
                                }
                            } else { ?>
                                 <div class="main_heading paddbtm10 text-center">Send Exam <span>Request</span></div>
                                 
    			                 <div class="black-heading">Following Exam is not available. We will try our best to arrange a copy for you. <br> Please use the request form below to submit so we can arrange this exam.</div>
                               
                                <form action="" name="demoForm2" method="post" onsubmit="return demoVerify3();" class="text-left">
                                    <div class="row">
                                        <div class="col-md-5">
                                           <label class="text-left">Exam Code:</label>
                                    <input type="text" class="input-field" name="request_ecode" id="request_ecode" value="<?php echo htmlspecialchars($srch); ?>" required />
                                        </div>
                                        
                                        <div class="col-md-5">
                                            <label class="text-left">Email:</label>
                                    <input type="email" class="input-field" name="request_email" id="request_email" required />
                                        </div>
                                        
                                        <div class="col-md-2">
                                            <div>
                                        <input type="submit" class="orange-button" value="Send Request" style="color:#fff !important"/>
                                    </div>
                                        </div>
                                    </div>
                                    
                                    
                                    
                                </form>
                
                                <script language="javascript">
                                    function demoVerify3() {
                                        regExp = /^[A-Za-z_0-9.\-]+@[A-Za-z0-9.\-]+\.[A-Za-z]{2,}$/;
                                        email = document.forms['demoForm2'].elements['request_email'].value;
                                        if (email == '' || !regExp.test(email)) {
                                            alert('Enter Email');
                                            document.forms['demoForm2'].elements['request_email'].focus();
                                            return false;
                                        }
                                    }
                                </script>
                            <?php } ?>

                

            <?/*<table width="20%" border="0" cellspacing="0" cellpadding="0">

                   <tr>

                  <td class="exams" valign="top" align="center">

                    <?

		            $queryPop = "SELECT * FROM tbl_exam where exam_status='1' and exam_name='$srch'";

					$res7 = mysql_query($queryPop);

					$total_pages=mysql_num_rows($res7);

					// listing query

				

					if($total_pages=='1')

					{

             		 header('Location: ' . BASE_URL . $srch . '.htm');

					 }

					 else{ ?>

					

                        <br />

				<form action="" name="demoForm2" method="post" onsubmit="return demoVerify3();">

			  Exam Code:<br />

				<input type="text" class="input-field" name="request_ecode" id="request_ecode" value="<?php echo $srch; ?>" required /><br />

			Email:<br />

				<input type="email" class="input-field" name="request_email" id="request_email" required /><br />



				<div style=" padding-left:45px; padding-top:15px;"><input type="submit" class="orange-button subscribe-button"  value="Send Request"  /></div>

				

				</form>

				<script language="javascript">

				function demoVerify3(){

				regExp=/^[A-Za-z_0-9\.\-]+@[A-Za-z0-9\.\-]+\.[A-Za-z]{2,}$/;

				email=document.forms['demoForm2'].elements['request_email'].value;

				if(email==''||!regExp.test(email))

				{

				alert('Enter Email'); 

				document.forms['demoForm2'].elements['request_email'].focus();return false; 

				

				}

				

				}

				</script>

					<?php }

					  ?>                  </td>

                     

                 </tr>

               

                                </table>*/  ?>

             </div>

       <?

				  $cond="";

				  $head="All Exams";

				  $srch=@$_REQUEST['srch'];

				  if($srch!='')

				  {

				  	$cond=" and exam_name  like '%$srch%' or exam_fullname like '%$srch%'";

					$head="Exams Search Results";

				  }

					?>



            </div>

        </div>

	</div>    

           

                <?php

				

		if(isset($_POST['request_email']))

		{

		$exmreq[1] = $_POST['request_ecode']; 

		$exmreq[2] = $_POST['request_email'];

		$objUser->examRequest_email($exmreq);

		}

              	 	

               	?>



<!-- hidden form contains page number parameter -->

<form name="frmPaging" action="" method="post">

<input type="hidden" name="current_page" id="current_page" value="<?php echo $currentpage?>" />

</form>

              <? include("includes/footer.php");?>     

