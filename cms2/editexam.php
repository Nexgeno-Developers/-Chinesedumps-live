	<?PHP 

ob_start();

session_start();



	include ("../includes/config/classDbConnection.php");

	include("../includes/common/classes/validation_class.php");

	include("../includes/common/functions/func_uploadimg.php");

	include ("../includes/common/inc/sessionheader.php");

	include("../includes/common/classes/classEvents.php");



include_once 'functions.php';

$ccAdminData = get_session_data();

function normalizeVideoService($service)
{
    $allowed = array('youtube', 'rumble', 'dailymotion', 'odysee');
    $service = strtolower(trim((string)$service));
    return in_array($service, $allowed, true) ? $service : 'youtube';
}

function normalizeVideoLinksFromStorage($rawValue)
{
    $items = json_decode($rawValue, true);
    if (!is_array($items)) {
        return array();
    }

    $normalized = array();
    foreach ($items as $item) {
        if (is_array($item) && isset($item['url'])) {
            $url = trim((string)$item['url']);
            if ($url === '') {
                continue;
            }
            $normalized[] = array(
                'service' => normalizeVideoService(isset($item['service']) ? $item['service'] : 'youtube'),
                'url' => $url
            );
            continue;
        }

        if (is_string($item)) {
            $url = trim($item);
            if ($url === '') {
                continue;
            }
            $normalized[] = array(
                'service' => 'youtube',
                'url' => $url
            );
        }
    }

    return $normalized;
}

function buildVideoLinksFromPost()
{
    $videoLinks = array();
    $services = isset($_POST['video_service']) ? (array)$_POST['video_service'] : array();
    $urls = isset($_POST['video_url']) ? (array)$_POST['video_url'] : array();
    $hasStructured = !empty($services) || !empty($urls);

    if ($hasStructured) {
        $max = max(count($services), count($urls));
        for ($i = 0; $i < $max; $i++) {
            $url = isset($urls[$i]) ? trim((string)$urls[$i]) : '';
            if ($url === '') {
                continue;
            }
            $service = isset($services[$i]) ? $services[$i] : 'youtube';
            $videoLinks[] = array(
                'service' => normalizeVideoService($service),
                'url' => $url
            );
        }
    } else {
        $legacyLinks = isset($_POST['youtube_links']) ? (array)$_POST['youtube_links'] : array();
        foreach ($legacyLinks as $legacyUrl) {
            $legacyUrl = trim((string)$legacyUrl);
            if ($legacyUrl === '') {
                continue;
            }
            $videoLinks[] = array(
                'service' => 'youtube',
                'url' => $legacyUrl
            );
        }
    }

    return $videoLinks;
}





//--------------------Creat objects---------------------------



			$objDBcon    = new classDbConnection; 			// VALIDATION CLASS OBJECT

			$objValid 	 = new Validate_fields;

			$objEvent	=	new classEvents($objDBcon);	// VALIDATION CLASS classCategory



	$strError	=	"";

	$spram[0]		 =  $_GET['exm_id'];

	$farr			=	array("'");

	$aarr			=	array("\'");

	if(isset($_POST['Submit']) && $_POST['Submit']  == "Update")

	{

		  $spram[1]	=	str_replace(" ","-",$_POST['vname']);
		  $spram[2]	=	$_POST['exam_url'];
		  $spram[3]	=	$_POST['pr3'];
		  $spram[33]	=	$_POST['engn_pri3'];
		  $spram[4]	=	$_POST['both_pri'];


	      $spram[40]	=	$spram[2];

		  

		  if($_POST['home']!='1'){

		  $spram[5]	=	'0';

		  }else{

	      $spram[5]	=	$_POST['home'];

		  }

		  

		  if($_POST['hot']!='1'){

		  $spram[6]	=	'0';

		  }else{

	      $spram[6]	=	$_POST['hot'];

		  }

		  

            $spram[7]	=	stripslashes($_POST['pro_desc']);
            
            $spram[8]	=	stripslashes($_POST['vtitle']);
            
            $spram[9]	=	stripslashes($_POST['vkey']);
            
            $spram[10]	=	stripslashes($_POST['edesc']);
            
            $spram[11]	=	$_POST['vendor_status'];
            
            	  
            
            $spram[12]	=	$_POST['cmb_cate'];
            
            $spram[13]	=	$_POST['cert_id'];//$_POST['cmb_subcategory'];
            
            
            
            $spram[14]	=	$_POST['vnamefull'];
            
            // $spram[15]	=	$_FILES['exam_demo'];
            
            $spram[20]	=	$_POST['qa'];
            
            $spram[29]	=	$_POST['startdate'];

            $spram[39]	=	stripslashes($_POST['pro_desc2']);

            $spram[77]	=	$_POST['pro_tests']; 
            $spram[38]  =    $_POST['telegram_url'];
            $spram[30]	=	$_POST['whatsapp_url'];
            $spram[31]	=	$_POST['skype_url'];
            $spram[37]	=	$_POST['video_code'];
            $spram[32]	=	$_POST['labName'];

            $examType = isset($_POST['exam_type']) ? strtolower(trim((string)$_POST['exam_type'])) : 'written';
            $spram['exam_type'] = ($examType === 'lab') ? 'lab' : 'written';
            $spram[35]  =   $_POST["oldBanner"];
            $spram[34]	=	$_FILES['course_image'];
            
            $spram[43]  =   $_POST["oldExamDemoImages"];
            
            $spram[41]	=	$_POST['exam_related_descr'];
            
    		$video_links = buildVideoLinksFromPost();
            $spram['youtube_links'] = json_encode($video_links);            
            $spram['free_dump_pdf'] = isset($_POST['old_free_dump_pdf']) ? $_POST['old_free_dump_pdf'] : '';
            if (!empty($_POST['remove_free_dump_pdf'])) {
                // delete physical file if it exists
                $oldPath = "../uploads/free_dumps/" . $spram['free_dump_pdf'];
                if ($spram['free_dump_pdf'] && file_exists($oldPath)) {
                    @unlink($oldPath);
                }
                $spram['free_dump_pdf'] = ''; // clear DB value
            }
            if (!empty($_FILES['free_dump_pdf']['name'])) {
                $ext = strtolower(pathinfo($_FILES['free_dump_pdf']['name'], PATHINFO_EXTENSION));
                if ($ext !== 'pdf') {
                    $strError .= "<b>Error!</b> Free dump file must be a PDF.<br/>";
                } else {
                    $uploadDir = "../uploads/free_dumps/";
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
                    $newPdfName = uniqid("freeDump_") . ".pdf";
                    $destPath = $uploadDir . $newPdfName;
                    if (move_uploaded_file($_FILES['free_dump_pdf']['tmp_name'], $destPath)) {
                        $spram['free_dump_pdf'] = $newPdfName;
                    } else {
                        $strError .= "<b>Error!</b> Unable to upload free dump PDF. Please try again.<br/>";
                    }
                }
            }
    	    $demoImages = [];
            
            if (!empty($_FILES['exam_demo_images']['name'])) {
                $uploadDir = "../images/exam_demo_images/";
            
                // Ensure directory exists
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
            
                // Loop through uploaded files
                for ($i = 0; $i < count($_FILES['exam_demo_images']['tmp_name']); $i++) {
                    $originalName = $_FILES['exam_demo_images']['name'][$i];
                    $tmpName = $_FILES['exam_demo_images']['tmp_name'][$i];
            
                    if (!empty($originalName) && is_uploaded_file($tmpName)) {
                        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
                        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
                        if (!in_array($extension, $allowedTypes)) {
                            continue;
                        }
            
                        $newName = uniqid("examDemo_") . '.' . $extension;
                        $destinationPath = $uploadDir . $newName;
            
                        if (move_uploaded_file($tmpName, $destinationPath)) {
                            $demoImages[] = $newName;
                        }
                    }
                }
                
                if (!empty($demoImages)) {
                    $jsonDemoImages = json_encode($demoImages);
                    $spram[42] = $jsonDemoImages;
                
                    // Escape data before query
                    $jsonForDB = mysql_real_escape_string($jsonDemoImages);
                    $examName = mysql_real_escape_string($_POST['vname']);
                
                    // Update the exam record
                    $updateQuery = "UPDATE tbl_exam SET demo_images = '$jsonForDB' WHERE exam_name = '$examName'";
                    mysql_query($updateQuery);
                }
    
            } else {
                // If no new files uploaded, use old value
                $spram[42] = $spram[43];
            }
            
    	    if($_FILES['course_image']['name']!=''){
    
    			$demotype	=	$_FILES['course_image']['type'];
    			if($demotype=='image/jpeg' || $demotype=='image/png' || $demotype=='image/JPG'){
    
    				if (!file_exists("../images/slider/".$spram[0])){mkdir("../images/slider/".$spram[0]);} 
    				move_uploaded_file($_FILES["course_image"]["tmp_name"], "../images/slider/".$spram[0]."/".basename($_FILES["course_image"]["name"]));
    
    			}else{
    				$strError	.=	"<b>Error! Banner type is not valid.</b>";
    			}
    			$spram[36] = $_FILES["course_image"]["name"]; 
    		}else{
    			$spram[36] = $spram[35];
    		}

		$objValid->add_text_field("Exam Code", $_POST['vname'], "text", "y");

		$objValid->add_text_field("Vendor Name", $_POST['cmb_cate'], "text", "y");



		

		if (!$objValid->validation())



		$strError = $objValid->create_msg();

		if($_POST['vnamehidden']!= $_POST['vname']){

		$getven	=	mysql_num_rows(mysql_query("SELECT * from tbl_exam where exam_name='".$_POST['vname']."'"));

		if($getven!='0'){

		$strError	.=	"<b>Error! Exam Name already Exist.</b></br>";

		}

		}

		

		$strQury="Select ven_name from tbl_vendors where ven_id ='".$spram[12]."'";

		$rsResult = $objDBcon->Dml_Query_Parser($strQury);

		$ven_name = $rsResult['ven_name'];

		if(empty($strError))

		{

		
//var_dump($spram[36]);exit;
		$addevent = $objEvent->updateExam($spram);

		

		$strError	.= "Exam has been updated successfully";
        // header("location:exammanage.php");
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;


		}

		}elseif(isset($_GET['exm_id']) && is_numeric($_GET['exm_id']))

		{

	

			$spram[0]	=	$_GET['exm_id'];

			$resultSet		=	$objEvent->get_exam_by_id($spram);

			

			while($row		=	mysql_fetch_array($resultSet))

			{
				$spram[1]	=	$row['exam_name'];
				$spram[2]	=	$row['exam_url'];
				$spram[3]	=	$row['exam_pri3'];
				$spram[32]	=	$row['engn_pri3'];
				$spram[4]	=	$row['both_pri'];
				$spram[40]	=	$spram[2];
				$spram[5]	=	$row['exam_home'];
				$spram[6]	= 	$row['exam_hot'];
				$spram[7]	= 	$row['exam_descr'];
				$spram[8]	= 	$row['exam_title'];
				$spram[9]	= 	$row['exam_keywords'];
				$spram[10]	= 	$row['exam_desc_seo'];
				$spram[11]	= 	$row['exam_status'];
				$spram[12]	= 	$row['ven_id'];
				$spram[13]	= 	$row['cert_id'];
				$spram[14]	= 	$row['exam_fullname'];
				$spram[20]	= 	$row['QA'];
				$spram[29]	= 	$row['exam_date'];						

                $spram[77]	= 	$row['exam_tests'];	
                $spram[38]  =   $row['telegram_url'];	
                $spram[30]	=	$row['whatsapp_url'];
                $spram[31]	=	$row['skype_url'];				
                $spram[37]	=	$row['video_code'];				
                $spram[33]	=	$row['labName'];
                $spram[36]	=	$row['course_image'];

                $spram['exam_type'] = isset($row['exam_type']) ? strtolower(trim((string)$row['exam_type'])) : 'written';
                $spram['exam_type'] = ($spram['exam_type'] === 'lab') ? 'lab' : 'written';
                $spram[39]	= 	$row['exam_descr2'];
                $spram[41]	= 	$row['exam_related_descr'];
                $spram['free_dump_pdf'] = $row['free_dump_pdf'];
				$youtube_links = array();
                if (!empty($row['youtube_links'])) {
                    $youtube_links = normalizeVideoLinksFromStorage($row['youtube_links']);
                }                
			}
	}

	$category = $objEvent->fillComboCategory_exam($spram[12]);
	$subcat	=	$objEvent->fillComboSubCategory($spram[13],$spram[12]);

?>



<? include ("header.php"); ?>

<script language="Javascript" src="js/category.js"></script>

<script language="javascript"  type="text/javascript">

	function validateCert(frm)

	{

		var bundleCount = frm.elements['cmb_subcategory[]'].length;

		var bundleSel   = false;

		for(i = 0; i < bundleCount; i++)

		{

			if(frm.elements['cmb_subcategory[]'][i].checked)

			{

				return true;

				break;

			}

		}

		if(!bundleSel)

		{

			alert('Select one or more Certification');

			return false;

		}

		return false;

	}

</script>

<table cellpadding="0" cellspacing="0" width="102%">

<tr>

<td width="190" class="leftside"><?php include ("menu.php"); ?></td>



<td width="810" class="rightside"><h2>Edit Exam!</h2>



Welcome to your<?=$websitename?> Website control panel. Here you can manage and modify every aspect of your <?=$websitename?>.<br />



<form name="Form" id="Form" method="post" action="" enctype="multipart/form-data" onsubmit="validateCert(this)"><br />



  <table cellpadding='0' cellspacing='0' width='100%'>

    <tr>

      <td></td>

    </tr>

    <tr>

      <td class='header'>Edit Exam </td>

    </tr>

    <tr>

      <td class='setting1'><table width="80%" border="0" align="center" cellpadding="2" cellspacing="2">

        <tr>

          <td align="left">&nbsp;</td>

          <td colspan="2" style="color: #FF0000;"><?=$strError?></td>

        </tr>

        <tr>

          <td width="24%" align="left" nowrap="nowrap"><div align="right"></div></td>

          <td colspan="2">* Required information</td>

        </tr>

         <tr>

          <td align="right">* Vendor Name:</td>

          <td colspan="2"><?=$category?></td>

        </tr>

        <tr>

          <td align="right">* Certification Name:</td>

          <td colspan="2"><div id="txtHint"><?=$subcat?></div></td>

        </tr>

        <tr>

          <td align="right">* Exam Code:</td>

          <td colspan="2"><input  name="vname" id="vname" type="text" onblur="setenable();"  value="<?php if(isset($spram[1])){ echo  $spram[1];} ?>" /></td>

        </tr>

        <tr>

          <td align="right">* Exam URL:</td>

          <td colspan="2"><input  name="exam_url" id="exam_url" type="text" onblur="setenable();"  value="<?php if(isset($spram[2])){ echo  $spram[2];} ?>" /></td>

        </tr>

        <tr>
          <td align="right"> Exam Name:</td>
          <td colspan="2"><input  name="vnamefull" id="vnamefull" type="text"  value="<?php if(isset($spram[14])){ echo  $spram[14];} ?>" /></td>
        </tr>
		<tr>
          <td align="right"> Lab Name:</td>
          <td colspan="2"><input  name="labName" id="labName" type="text"  value="<?php if(isset($spram[33])){ echo  $spram[33];} ?>" /></td>
        </tr>

					<tr>
          <td align="right">* Exam Type:</td>
          <td colspan="2">
            <select name="exam_type" id="exam_type">
              <option value="written" <?php echo (!isset($spram['exam_type']) || $spram['exam_type'] === 'written') ? 'selected="selected"' : ''; ?>>Written (bootcamp.htm)</option>
              <option value="lab" <?php echo (isset($spram['exam_type']) && $spram['exam_type'] === 'lab') ? 'selected="selected"' : ''; ?>>Lab (workbook.htm, lab.htm)</option>
            </select>
          </td>
        </tr>
					<tr>
              <td align="right"> Course Image:</td>
              <td colspan="2"><input name="course_image" type="file" id="course_image" value="" />
							<input type="hidden" name="oldBanner" value="<?php if(isset($spram[36])){ echo  $spram[36];} ?>">
							</td>
            </tr>
         <tr>

          <td align="right"> Exam Q &amp; As:</td>

          <td colspan="2"><input  name="qa" id="qa" type="text"  value="<?php if(isset($spram[20])){ echo  $spram[20];} ?>" /> Question and Answers</td>

        </tr>

       <tr>

              <td align="right">* Date: </td>

              <td colspan="2"><input name="startdate" type="text" readonly="readonly"  id="startdate" value="<?php if(isset($spram[29])){ echo  $spram[29];} ?>" />&nbsp;<img src="images/calendar-icon.gif" alt="Start Time" onclick="displayCalendarSe(document.Form.startdate,'yyyy-mm-dd',this)" /></td>

            </tr>

            <tr>
              <td align="right"> Secure PDC Price:</td>
              <td colspan="2"><input name="pr3" type="text" id="pr3" value="<?php if(isset($spram[3])){ echo  $spram[3];} ?>" />&nbsp;$</td>
            </tr>

            <tr>
              <td align="right"> Engine Price:</td>
              <td colspan="2"><input name="engn_pri3" type="text" id="engn_pri3" value="<?php if(isset($spram[32])){ echo  $spram[32];} ?>" />&nbsp;$</td>
            </tr>

			<tr>
              <td align="right"> Both Price:</td>
              <td colspan="2"><input name="both_pri" type="text" id="both_pri" value="<?php if(isset($spram[4])){ echo  $spram[4];} ?>" />&nbsp;$</td>
            </tr>
            
            <tr>
              <td align="right">Free Dump PDF:</td>
              <td colspan="2">
                <?php if(!empty($spram['free_dump_pdf'])) { ?>
                  <div style="margin-bottom:6px;">
                    Current: <a href="../uploads/free_dumps/<?php echo $spram['free_dump_pdf']; ?>" target="_blank">
                      <?php echo $spram['free_dump_pdf']; ?>
                    </a>
                  </div>
                  <label><input type="checkbox" name="remove_free_dump_pdf" value="1"> Remove current PDF</label><br>
                <?php } ?>
                <input type="file" name="free_dump_pdf" accept="application/pdf" />
                <input type="hidden" name="old_free_dump_pdf" value="<?php echo $spram['free_dump_pdf']; ?>">
              </td>
            </tr>
            
           	<tr>

          <td align="right"> Is Home:</td>

          <td colspan="2"><input type="checkbox" value="1" <? if(isset($spram[5]) && $spram[5]=="1"){ echo "checked='checked'";}?> name="home"  id="home" /></td>

        </tr>

        <tr>

          <td align="right"> Is Hot:</td>

          <td colspan="2"><input type="checkbox" value="1" <? if(isset($spram[6]) && $spram[6]=="1"){ echo "checked='checked'";}?>  name="hot"  id="hot" /></td>

        </tr>
       
		<tr style="display:none;">
			<td align="right"> Multiple Demo Images:</td>
			<td colspan="2"><input type="file" name="exam_demo_images[]" multiple accept="image/*"/></td>
			<input type="hidden" name="oldExamDemoImages" value="<?php if(isset($spram[42])){ echo  $spram[42];} ?>">
		</tr>
		
        <tr>
        <td align="right" valign="top">Video Links:</td>
        <td colspan="2">
        <div id="youtube_links_wrapper">
          <?php
          if (!empty($youtube_links)) {
              foreach ($youtube_links as $i => $videoItem) {
                  $service = isset($videoItem['service']) ? $videoItem['service'] : 'youtube';
                  $link = isset($videoItem['url']) ? $videoItem['url'] : '';
                  ?>
                  <div class="youtube_link_row" style="margin-bottom:8px;">
                    <select name="video_service[]" style="width:160px;">
                      <option value="youtube" <?= ($service === 'youtube') ? 'selected="selected"' : '' ?>>YouTube</option>
                      <option value="rumble" <?= ($service === 'rumble') ? 'selected="selected"' : '' ?>>Rumble</option>
                      <option value="dailymotion" <?= ($service === 'dailymotion') ? 'selected="selected"' : '' ?>>DailyMotion</option>
                      <option value="odysee" <?= ($service === 'odysee') ? 'selected="selected"' : '' ?>>Odysee</option>
                    </select>
                    <input type="text" name="video_url[]" value="<?= htmlspecialchars($link) ?>" placeholder="Enter video URL" style="width:400px;">
                    <button type="button" class="remove_row">Remove</button>
                  </div>
                  <?php
              }
          } else {
              // Show one empty row by default
              ?>
              <div class="youtube_link_row" style="margin-bottom:8px;">
                <select name="video_service[]" style="width:160px;">
                  <option value="youtube">YouTube</option>
                  <option value="rumble">Rumble</option>
                  <option value="dailymotion">DailyMotion</option>
                  <option value="odysee">Odysee</option>
                </select>
                <input type="text" name="video_url[]" placeholder="Enter video URL" style="width:400px;">
                <button type="button" class="remove_row">Remove</button>
              </div>
              <?php
          }
          ?>
        </div>
        <button type="button" id="add_more_youtube">Add More Video</button>
        </td>
        </tr>		
		
         <tr>

          <td valign="top" nowrap="nowrap" align="right">Exam Description:</td>

          <td height="29" colspan="2" align="left" valign="middle">

		  <textarea id="pro_desc" name="pro_desc" rows=4 cols=30><?php echo stripslashes($spram[7]) ?></textarea>

                    <script>

    var oEdit1 = new InnovaEditor("oEdit1");



    /***************************************************

      SETTING EDITOR DIMENSION (WIDTH x HEIGHT)

    ***************************************************/



    oEdit1.width=600;//You can also use %, for example: oEdit1.width="100%"

    oEdit1.height=350;





    /***************************************************

      SHOWING DISABLED BUTTONS

    ***************************************************/



    oEdit1.btnPrint=true;

    oEdit1.btnPasteText=true;

    oEdit1.btnFlash=true;

    oEdit1.btnMedia=true;

    oEdit1.btnLTR=true;

    oEdit1.btnRTL=true;

    oEdit1.btnSpellCheck=true;

    oEdit1.btnStrikethrough=true;

    oEdit1.btnSuperscript=true;

    oEdit1.btnSubscript=true;

    oEdit1.btnClearAll=true;

    oEdit1.btnSave=true;

    oEdit1.btnStyles=true; //Show "Styles/Style Selection" button



    /***************************************************

      APPLYING STYLESHEET

      (Using external css file)

    ***************************************************/



    oEdit1.css="style/test.css"; //Specify external css file here



    /***************************************************

      APPLYING STYLESHEET

      (Using predefined style rules)

    ***************************************************/

    /*

    oEdit1.arrStyle = [["BODY",false,"","font-family:Verdana,Arial,Helvetica;font-size:x-small;"],

          [".ScreenText",true,"Screen Text","font-family:Tahoma;"],

          [".ImportantWords",true,"Important Words","font-weight:bold;"],

          [".Highlight",true,"Highlight","font-family:Arial;color:red;"]];



    If you'd like to set the default writing to "Right to Left", you can use:



    oEdit1.arrStyle = [["BODY",false,"","direction:rtl;unicode-bidi:bidi-override;"]];

    */





    /***************************************************

      ENABLE ASSET MANAGER ADD-ON

    ***************************************************/



    oEdit1.cmdAssetManager = "modalDialogShow('<?=$websiteURL?>/monitor/Editor3/assetmanager/assetmanager.php',640,465)"; //Command to open the Asset Manager add-on.

    //Use relative to root path (starts with "/")



    /***************************************************

      ADDING YOUR CUSTOM LINK LOOKUP

    ***************************************************/



    oEdit1.cmdInternalLink = "modelessDialogShow('links.htm',365,270)"; //Command to open your custom link lookup page.



    /***************************************************

      ADDING YOUR CUSTOM CONTENT LOOKUP

    ***************************************************/



    oEdit1.cmdCustomObject = "modelessDialogShow('objects.htm',365,270)"; //Command to open your custom content lookup page.



    /***************************************************

      USING CUSTOM TAG INSERTION FEATURE

    ***************************************************/



    oEdit1.arrCustomTag=[["First Name","{%first_name%}"],

        ["Last Name","{%last_name%}"],

        ["Email","{%email%}"]];//Define custom tag selection



    /***************************************************

      SETTING COLOR PICKER's CUSTOM COLOR SELECTION

    ***************************************************/



    oEdit1.customColors=["#ff4500","#ffa500","#808000","#4682b4","#1e90ff","#9400d3","#ff1493","#a9a9a9"];//predefined custom colors



    /***************************************************

      SETTING EDITING MODE



      Possible values:

        - "HTMLBody" (default)

        - "XHTMLBody"

        - "HTML"

        - "XHTML"

    ***************************************************/



    oEdit1.mode="XHTMLBody";





    oEdit1.REPLACE("pro_desc");

              </script>

                </td>

        </tr>

         <tr>

          <td valign="top" nowrap="nowrap" align="right">Seo Section (H1 & H2 with Description) :</td>

          <td height="29" colspan="2" align="left" valign="middle">

		  <textarea id="pro_desc2" name="pro_desc2" rows=4 cols=30><?php echo stripslashes($spram[39]) ?></textarea>

                    <script>

    var oEdit3 = new InnovaEditor("oEdit3");



    /***************************************************

      SETTING EDITOR DIMENSION (WIDTH x HEIGHT)

    ***************************************************/



    oEdit3.width=600;//You can also use %, for example: oEdit3.width="100%"

    oEdit3.height=350;





    /***************************************************

      SHOWING DISABLED BUTTONS

    ***************************************************/



    oEdit3.btnPrint=true;

    oEdit3.btnPasteText=true;

    oEdit3.btnFlash=true;

    oEdit3.btnMedia=true;

    oEdit3.btnLTR=true;

    oEdit3.btnRTL=true;

    oEdit3.btnSpellCheck=true;

    oEdit3.btnStrikethrough=true;

    oEdit3.btnSuperscript=true;

    oEdit3.btnSubscript=true;

    oEdit3.btnClearAll=true;

    oEdit3.btnSave=true;

    oEdit3.btnStyles=true; //Show "Styles/Style Selection" button



    /***************************************************

      APPLYING STYLESHEET

      (Using external css file)

    ***************************************************/



    oEdit3.css="style/test.css"; //Specify external css file here



    /***************************************************

      APPLYING STYLESHEET

      (Using predefined style rules)

    ***************************************************/

    /*

    oEdit3.arrStyle = [["BODY",false,"","font-family:Verdana,Arial,Helvetica;font-size:x-small;"],

          [".ScreenText",true,"Screen Text","font-family:Tahoma;"],

          [".ImportantWords",true,"Important Words","font-weight:bold;"],

          [".Highlight",true,"Highlight","font-family:Arial;color:red;"]];



    If you'd like to set the default writing to "Right to Left", you can use:



    oEdit3.arrStyle = [["BODY",false,"","direction:rtl;unicode-bidi:bidi-override;"]];

    */





    /***************************************************

      ENABLE ASSET MANAGER ADD-ON

    ***************************************************/



    oEdit3.cmdAssetManager = "modalDialogShow('<?=$websiteURL?>/monitor/Editor3/assetmanager/assetmanager.php',640,465)"; //Command to open the Asset Manager add-on.

    //Use relative to root path (starts with "/")



    /***************************************************

      ADDING YOUR CUSTOM LINK LOOKUP

    ***************************************************/



    oEdit3.cmdInternalLink = "modelessDialogShow('links.htm',365,270)"; //Command to open your custom link lookup page.



    /***************************************************

      ADDING YOUR CUSTOM CONTENT LOOKUP

    ***************************************************/



    oEdit3.cmdCustomObject = "modelessDialogShow('objects.htm',365,270)"; //Command to open your custom content lookup page.



    /***************************************************

      USING CUSTOM TAG INSERTION FEATURE

    ***************************************************/



    oEdit3.arrCustomTag=[["First Name","{%first_name%}"],

        ["Last Name","{%last_name%}"],

        ["Email","{%email%}"]];//Define custom tag selection



    /***************************************************

      SETTING COLOR PICKER's CUSTOM COLOR SELECTION

    ***************************************************/



    oEdit3.customColors=["#ff4500","#ffa500","#808000","#4682b4","#1e90ff","#9400d3","#ff1493","#a9a9a9"];//predefined custom colors



    /***************************************************

      SETTING EDITING MODE



      Possible values:

        - "HTMLBody" (default)

        - "XHTMLBody"

        - "HTML"

        - "XHTML"

    ***************************************************/



    oEdit3.mode="XHTMLBody";





    oEdit3.REPLACE("pro_desc2");

              </script>

                </td>

        </tr>

        <tr>
          <td align="right"> Related product description:</td>
          <td colspan="2"><textarea id="exam_related_descr" name="exam_related_descr" cols="22" rows="6"><?php if(isset($spram[41])){echo $spram[41];} ?></textarea></td>
        </tr>
            
        <tr>

          <td valign="top" nowrap="nowrap" align="right">User Testimonials:</td>

          <td height="29" colspan="2" align="left" valign="middle">

		  <textarea id="pro_tests" name="pro_tests" rows=4 cols=30><?php echo stripslashes($spram[77]) ?></textarea>

                    <script>

    var oEdit2 = new InnovaEditor("oEdit2");



    /***************************************************

      SETTING EDITOR DIMENSION (WIDTH x HEIGHT)

    ***************************************************/



    oEdit2.width=600;//You can also use %, for example: oEdit2.width="100%"

    oEdit2.height=350;





    /***************************************************

      SHOWING DISABLED BUTTONS

    ***************************************************/



    oEdit2.btnPrint=true;

    oEdit2.btnPasteText=true;

    oEdit2.btnFlash=true;

    oEdit2.btnMedia=true;

    oEdit2.btnLTR=true;

    oEdit2.btnRTL=true;

    oEdit2.btnSpellCheck=true;

    oEdit2.btnStrikethrough=true;

    oEdit2.btnSuperscript=true;

    oEdit2.btnSubscript=true;

    oEdit2.btnClearAll=true;

    oEdit2.btnSave=true;

    oEdit2.btnStyles=true; //Show "Styles/Style Selection" button



    /***************************************************

      APPLYING STYLESHEET

      (Using external css file)

    ***************************************************/



    oEdit2.css="style/test.css"; //Specify external css file here



    /***************************************************

      APPLYING STYLESHEET

      (Using predefined style rules)

    ***************************************************/

    /*

    oEdit2.arrStyle = [["BODY",false,"","font-family:Verdana,Arial,Helvetica;font-size:x-small;"],

          [".ScreenText",true,"Screen Text","font-family:Tahoma;"],

          [".ImportantWords",true,"Important Words","font-weight:bold;"],

          [".Highlight",true,"Highlight","font-family:Arial;color:red;"]];



    If you'd like to set the default writing to "Right to Left", you can use:



    oEdit2.arrStyle = [["BODY",false,"","direction:rtl;unicode-bidi:bidi-override;"]];

    */





    /***************************************************

      ENABLE ASSET MANAGER ADD-ON

    ***************************************************/



    oEdit2.cmdAssetManager = "modalDialogShow('<?=$websiteURL?>/monitor/Editor3/assetmanager/assetmanager.php',640,465)"; //Command to open the Asset Manager add-on.

    //Use relative to root path (starts with "/")



    /***************************************************

      ADDING YOUR CUSTOM LINK LOOKUP

    ***************************************************/



    oEdit2.cmdInternalLink = "modelessDialogShow('links.htm',365,270)"; //Command to open your custom link lookup page.



    /***************************************************

      ADDING YOUR CUSTOM CONTENT LOOKUP

    ***************************************************/



    oEdit2.cmdCustomObject = "modelessDialogShow('objects.htm',365,270)"; //Command to open your custom content lookup page.



    /***************************************************

      USING CUSTOM TAG INSERTION FEATURE

    ***************************************************/



    oEdit2.arrCustomTag=[["First Name","{%first_name%}"],

        ["Last Name","{%last_name%}"],

        ["Email","{%email%}"]];//Define custom tag selection



    /***************************************************

      SETTING COLOR PICKER's CUSTOM COLOR SELECTION

    ***************************************************/



    oEdit2.customColors=["#ff4500","#ffa500","#808000","#4682b4","#1e90ff","#9400d3","#ff1493","#a9a9a9"];//predefined custom colors



    /***************************************************

      SETTING EDITING MODE



      Possible values:

        - "HTMLBody" (default)

        - "XHTMLBody"

        - "HTML"

        - "XHTML"

    ***************************************************/



    oEdit2.mode="XHTMLBody";

    oEdit2.REPLACE("pro_tests");

              </script>

                </td>

        </tr>

         <tr>

          <td align="right"> Exam SEO Title:</td>

          <td colspan="2"><input  name="vtitle" type="text" value="<?php if(isset($spram[8])){ echo $spram[8];} ?>" /></td>

        </tr>

        

         <tr>

          <td align="right"> Exam SEO Keywords:</td>

          <td colspan="2"><input  name="vkey" type="text" value="<?php if(isset($spram[9])){ echo $spram[9];} ?>" /></td>

        </tr>

		 <tr>

          <td align="right"> Exam SEO Description:</td>

          <td colspan="2"><textarea name="edesc" cols="22" rows="6"><?php if(isset($spram[10])){echo $spram[10];} ?></textarea></td>

        </tr>	
        <tr>

<td align="right">Telegram URL:</td>

<td colspan="2"><input  name="telegram_url" type="text" value="<?php  echo $spram[38]; ?>" /></td>

</tr>
								         <tr>

          <td align="right"> Whatsapp URL:</td>

          <td colspan="2"><input  name="whatsapp_url" type="text" value="<?php  echo $spram[30]; ?>" /></td>

        </tr>
				         <tr>

          <td align="right"> Skype URL:</td>

          <td colspan="2"><input  name="skype_url" type="text" value="<?php echo $spram[31];?>"  /></td>

        </tr>
		</tr>
				         <tr>

          <td align="right"> Video code:</td>

          <td colspan="2"><input  name="video_code" type="text" value="<?php echo $spram[37];?>"  /></td>

        </tr>

		<tr>
 
          <td align="right"> Exam Status:</td>

          <td><select name="vendor_status" id="vendor_status">

                <option value="1"<? if(isset($spram[11]) && $spram[11]=="1"){ echo "selected='selected'";}?>>Active</option>

                <option value="0"<? if(isset($spram[11]) && $spram[11]=="0"){ echo "selected='selected'";}?>>In Active</option>

                </select><input type="hidden" name="vnamehidden" id="vnamehidden" value="<?=$spram[1]?>" />

                <input type="hidden" name="demohidden" id="demohidden" value="<?=$spram[15]?>" />

                <input type="hidden" name="fullhidden" id="fullhidden" value="<?=$spram[16]?>" />

                </td>

        </tr>
        
        <tr align="right">

          <td align="left">&nbsp;</td>

          <td colspan="2" align="left"><input type="submit" name="Submit" value="Update" /></td>

        </tr>

      </table>      

      <p>&nbsp;</p>

      </td>

    </tr>

  </table>

	  </form>

<br /></td>

</tr>

</table>

</td>

</tr>

</table>

<script defer>
  /* ---------------------- VIDEO LINKS ---------------------- */
document.addEventListener('DOMContentLoaded', function () {  
  const ytWrapper = document.getElementById('youtube_links_wrapper');
  const ytAddBtn  = document.getElementById('add_more_youtube');

  if (ytWrapper && ytAddBtn) {
    ytAddBtn.addEventListener('click', function () {
      const div = document.createElement('div');
      div.className = 'youtube_link_row';
      div.style.marginBottom = '8px';
      div.innerHTML = `
        <select name="video_service[]" style="width:160px;">
          <option value="youtube">YouTube</option>
          <option value="rumble">Rumble</option>
          <option value="dailymotion">DailyMotion</option>
          <option value="odysee">Odysee</option>
        </select>
        <input type="text" name="video_url[]" placeholder="Enter video URL" style="width:400px;">
        <button type="button" class="remove_row">Remove</button>
      `;
      ytWrapper.appendChild(div);
    });

    ytWrapper.addEventListener('click', function (e) {
      if (e.target.classList.contains('remove_row')) {
        const row = e.target.closest('.youtube_link_row');
        if (row) row.remove();
      }
    });
  }
});    
</script>

<? include("footer.php")?>

</body>

</html>