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

function normalizeFeatureRowsFromPost()
{
    $rows = isset($_POST['features']) ? (array)$_POST['features'] : array();
    $normalized = array();

    foreach ($rows as $row) {
        if (!is_array($row)) {
            continue;
        }

        $name = isset($row['name']) ? trim((string)$row['name']) : '';
        if ($name === '') {
            continue;
        }

        $normalized[] = array(
            'name' => $name,
            'workbook' => !empty($row['workbook']),
            'racks' => !empty($row['racks']),
            'bootcamp' => !empty($row['bootcamp'])
        );
    }

    return $normalized;
}

function normalizeFeatureRowsFromStorage($rawValue)
{
    $decoded = json_decode((string)$rawValue, true);
    if (!is_array($decoded)) {
        return array();
    }

    $normalized = array();
    foreach ($decoded as $row) {
        if (!is_array($row)) {
            continue;
        }

        $name = isset($row['name']) ? trim((string)$row['name']) : '';
        if ($name === '') {
            continue;
        }

        $normalized[] = array(
            'name' => $name,
            'workbook' => !empty($row['workbook']),
            'racks' => !empty($row['racks']),
            'bootcamp' => !empty($row['bootcamp'])
        );
    }

    return $normalized;
}





//--------------------Creat objects---------------------------



			$objDBcon    = new classDbConnection; 			// VALIDATION CLASS OBJECT

			$objValid 	 = new Validate_fields;

			$objEvent	=	new classEvents($objDBcon);	// VALIDATION CLASS classCategory



	$strError	=	"";

		$spram[1]		 =  "";

		$spram[2]		 =  "";

		$spram[3]		 =  ""; 

		$spram[4]		 =  "";

		$spram[5]		 =  "";

		$spram[6]		 =  "";

	$farr			=	array("'");

	$aarr			=	array("\'");

	if(isset($_POST['Submit']) && $_POST['Submit']  == "Submit")

	{

		  $spram[0]	=	str_replace(" ","-",$_POST['vname']);

		  

		  $uname = $spram[0];

		  $url_name=str_replace(" ", "-", trim($uname));
          $url_name=str_replace("+", "p", $url_name);
		  $url_name=str_replace(":", "", $url_name);
		  $url_name=str_replace(".", "-", $url_name);
		  $url_name=str_replace(",", "-", $url_name);
		  $url_name=str_replace("/", "-", $url_name);
		  $url_name=str_replace("&", "and", $url_name);
		  $url_name=str_replace("(", "", $url_name);
		  $url_name=str_replace(")", "", $url_name);
		  $url_name=str_replace("[", "", $url_name);
		  $url_name=str_replace("]", "", $url_name);
		  $url_name=str_replace("---", "-", $url_name);
		  $url_name=str_replace("--", "-", $url_name);
		  $spram[1] = strtolower($url_name);

		  

		  $spram[2]	=	$_POST['pr3'];
		  $spram[22]	=	$_POST['sp_pri'];
		  $spram[3]	=	$_POST['both_pri'];

	      $spram[40]	=	$spram[1];

		  if($_POST['home']!='1'){

		  $spram[4]	=	'0';

		  }else{

	      $spram[4]	=	$_POST['home'];

		  }

		  

		  if($_POST['hot']!='1'){

		  $spram[5]	=	'0';

		  }else{

	      $spram[5]	=	$_POST['hot'];

		  }

	      $featureRows = normalizeFeatureRowsFromPost();
	      $spram[6]	=	!empty($featureRows) ? json_encode($featureRows) : '';
	      $spram[7]	=	stripslashes($_POST['vtitle']);
		  $spram[8]	=	stripslashes($_POST['vkey']);
		  $spram[9]	=	stripslashes($_POST['edesc']);
		  $spram[10]	=	$_POST['vendor_status'];
		  $spram[12]	=	$_POST['cmb_cate'];
		  $spram[13]	=	$_POST['cert_id'];
		  $spram[14]	=	$_POST['vnamefull'];
		  $spram['alias_name'] = isset($_POST['alias_name']) ? trim($_POST['alias_name']) : '';
          $spram['exam_type'] = (isset($_POST['exam_type']) && $_POST['exam_type'] === 'lab') ? 'lab' : 'written';
          $spram['free_dump_label'] = isset($_POST['free_dump_label']) ? trim($_POST['free_dump_label']) : '';
          $spram['demo_practice_label'] = isset($_POST['demo_practice_label']) ? trim($_POST['demo_practice_label']) : '';
		  $faqQuestions = isset($_POST['faq_question']) ? (array)$_POST['faq_question'] : array();
		  $faqAnswers = isset($_POST['faq_answer']) ? (array)$_POST['faq_answer'] : array();
		  $faqItems = array();
		  $faqCount = max(count($faqQuestions), count($faqAnswers));
		  for ($faqIdx = 0; $faqIdx < $faqCount; $faqIdx++) {
		      $question = isset($faqQuestions[$faqIdx]) ? trim((string)$faqQuestions[$faqIdx]) : '';
		      $answer = isset($faqAnswers[$faqIdx]) ? trim((string)$faqAnswers[$faqIdx]) : '';
		      if ($question === '' || $answer === '') {
		          continue;
		      }
		      $faqItems[] = array('question' => $question, 'answer' => $answer);
		  }
		  if (!empty($faqItems)) {
		      $spram['faq_json'] = json_encode($faqItems);
		  } else {
		      $spram['faq_json'] = isset($_POST['faq_json']) ? trim($_POST['faq_json']) : '';
		  }
		  $spram[20]	=	$_POST['qa'];
		  $spram[29]	=	$_POST['startdate'];
		  $spram[30]	=	$_POST['whatsapp_url'];
		  $spram[31]	=	$_POST['skype_url'];
		  //$spram[42]	=	$_POST['demo_images'];
		  $spram[32]	=	stripslashes($_POST['labName']);
		  
		$video_links = buildVideoLinksFromPost();
        $spram['youtube_links'] = json_encode($video_links);		  
                    
        // Handle free dump PDF upload
        $spram['free_dump_pdf'] = '';
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

        // Handle demo practice PDF upload
        $spram['demo_practice_file'] = '';
        if (!empty($_FILES['demo_practice_file']['name'])) {
            $ext = strtolower(pathinfo($_FILES['demo_practice_file']['name'], PATHINFO_EXTENSION));
            if ($ext !== 'pdf') {
                $strError .= "<b>Error!</b> Demo practice file must be a PDF.<br/>";
            } else {
                $uploadDir = "../uploads/demo_practice/";
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $newPdfName = uniqid("demoPractice_") . ".pdf";
                $destPath = $uploadDir . $newPdfName;
                if (move_uploaded_file($_FILES['demo_practice_file']['tmp_name'], $destPath)) {
                    $spram['demo_practice_file'] = $newPdfName;
                } else {
                    $strError .= "<b>Error!</b> Unable to upload demo practice PDF. Please try again.<br/>";
                }
            }
        }
        
            $spram[43]  =   $_POST["oldExamDemoImages"];
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

// 		$objValid->add_text_field("Date", $_POST['startdate'], "text", "y");

		$objValid->add_text_field("Exam Code", $_POST['vname'], "text", "y");

		$objValid->add_text_field("Vendor Name", $_POST['cmb_cate'], "text", "y");

		

		



		if (!$objValid->validation())



		$strError = $objValid->create_msg();

		

		$getven	=	mysql_num_rows(mysql_query("SELECT * from tbl_exam where exam_name='".$_POST['vname']."'"));

		if($getven!='0'){

		$strError	.=	"<b>Error! Exam Name already Exist.</b></br>";

		}

		$strQury="Select ven_name from tbl_vendors where ven_id ='".$spram[12]."'";

		$rsResult = $objDBcon->Dml_Query_Parser($strQury);

		$ven_name = $rsResult['ven_name'];

			if(empty($strError)){

				$addevent = $objEvent->addExam($spram);

				$strError	.= "Exam has been added successfully";

				//header("location:exammanage.php");

			}

		}

		

		$category = $objEvent->fillComboCategory_exam($_POST['cmb_cate']);

		if(isset($_POST['cmb_cate'])){

		$subcat	=	$objEvent->fillComboSubCategory($_POST['cmb_subcategory'],$_POST['cmb_cate']);

		}else{

		$subcat	=	"<select name='cert_id' class='csstxtfield2'><option value=''>---Select Vendor---</label>";

		}

        $featureRowsForForm = normalizeFeatureRowsFromPost();
        if (empty($featureRowsForForm)) {
            $featureRowsForForm = array(
                array(
                    'name' => '',
                    'workbook' => false,
                    'racks' => false,
                    'bootcamp' => false
                )
            );
        }

?>

<? include ("header.php"); ?>

<script language="Javascript" src="js/category.js"></script>

<script language="JavaScript" src="../includes/js/calendar.js"></script>



<table cellpadding="0" cellspacing="0" width="102%">

<tr>

<td width="190" class="leftside"><?php include ("menu.php"); ?></td>



<td width="810" class="rightside"><h2>Add Exam!</h2>



Welcome to your<?=$websitename?> Website control panel. Here you can manage and modify every aspect of your <?=$websitename?>.<br />



<form name="Form" id="Form" method="post" action="" onSubmit="return validateCert(this)" enctype="multipart/form-data"><br />



  <table cellpadding='0' cellspacing='0' width='100%'>

    <tr>

      <td></td>

    </tr>

    <tr>

      <td class='header'>Add Exam </td>

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

          <td colspan="2"><input  name="vname" id="vname" type="text" onblur="setenable();"  value="<?php if(isset($_POST['vname'])){ echo  $_POST['vname'];} ?>" /></td>

        </tr>

        <tr>

          <td align="right">Exam Name:</td>

          <td colspan="2"><input  name="vnamefull" id="vnamefull" type="text"  value="<?php if(isset($_POST['vnamefull'])){ echo  $_POST['vnamefull'];} ?>" /></td>

        </tr>
        <tr>
          <td align="right">* Exam Type:</td>
          <td colspan="2">
            <select name="exam_type" id="exam_type">
              <option value="written" <?php echo (!isset($_POST['exam_type']) || $_POST['exam_type'] === 'written') ? "selected='selected'" : ""; ?>>Written</option>
              <option value="lab" <?php echo (isset($_POST['exam_type']) && $_POST['exam_type'] === 'lab') ? "selected='selected'" : ""; ?>>Lab</option>
            </select>
          </td>
        </tr>
        <tr>

          <td align="right">Alias Name:</td>

          <td colspan="2"><input name="alias_name" id="alias_name" type="text" value="<?php if(isset($_POST['alias_name'])){ echo $_POST['alias_name'];} ?>" /></td>

        </tr>
        <tr>
          <td align="right" valign="top">FAQs:</td>
          <td colspan="2">
            <div id="faq_wrapper">
              <?php
              $postedFaqQuestions = isset($_POST['faq_question']) ? (array)$_POST['faq_question'] : array();
              $postedFaqAnswers = isset($_POST['faq_answer']) ? (array)$_POST['faq_answer'] : array();
              $hasPostedFaqRows = !empty($postedFaqQuestions) || !empty($postedFaqAnswers);
              if ($hasPostedFaqRows) {
                  $faqRowCount = max(count($postedFaqQuestions), count($postedFaqAnswers));
                  for ($faqRowIndex = 0; $faqRowIndex < $faqRowCount; $faqRowIndex++) {
                      $qVal = isset($postedFaqQuestions[$faqRowIndex]) ? $postedFaqQuestions[$faqRowIndex] : '';
                      $aVal = isset($postedFaqAnswers[$faqRowIndex]) ? $postedFaqAnswers[$faqRowIndex] : '';
                      ?>
                      <div class="faq_row" style="margin-bottom:8px;">
                        <input type="text" name="faq_question[]" placeholder="Question" style="width:400px;" value="<?php echo htmlspecialchars($qVal, ENT_QUOTES); ?>">
                        <input type="text" name="faq_answer[]" placeholder="Answer" style="width:400px;" value="<?php echo htmlspecialchars($aVal, ENT_QUOTES); ?>">
                        <button type="button" class="remove_faq_row">Remove</button>
                      </div>
                      <?php
                  }
              } else {
                  ?>
                  <div class="faq_row" style="margin-bottom:8px;">
                    <input type="text" name="faq_question[]" placeholder="Question" style="width:400px;">
                    <input type="text" name="faq_answer[]" placeholder="Answer" style="width:400px;">
                    <button type="button" class="remove_faq_row">Remove</button>
                  </div>
                  <?php
              }
              ?>
            </div>
            <button type="button" id="add_more_faq">Add More FAQ</button>
            <input type="hidden" name="faq_json" id="faq_json" value="<?php if(isset($_POST['faq_json'])){ echo htmlspecialchars($_POST['faq_json'], ENT_QUOTES); } ?>">
          </td>
        </tr>

         <tr>

          <td align="right">Exam Q &amp; As:</td>

          <td colspan="2"><input  name="qa" id="qa" type="text"  value="<?php if(isset($_POST['qa'])){ echo  $_POST['qa'];} ?>" /> Question and Answers</td>

        </tr>

        <tr>

              <td align="right">* Date: </td>

              <td colspan="2"><input name="startdate" type="text" readonly="readonly"  id="startdate" value="<?php if(isset($_POST['startdate'])){ echo  $_POST['startdate'];} ?>" />&nbsp;<img src="images/calendar-icon.gif" alt="Start Time" onclick="displayCalendarSe(document.Form.startdate,'yyyy-mm-dd',this)" /></td>

            </tr>
			<tr>
              <td align="right"> Lab Name:</td>
              <td colspan="2"><input name="labName" type="text" id="labName" value="<?php if(isset($_POST['labName'])){ echo  $_POST['labName'];} ?>" />&nbsp;</td>
            </tr>

            <tr>
              <td align="right"> PDF Price:</td>
              <td colspan="2"><input name="pr3" type="text" id="pr3" value="<?php if(isset($_POST['pr3'])){ echo  $_POST['pr3'];} ?>" />&nbsp;$</td>
            </tr>

            <tr>
              <td align="right"> Engine Price:</td>
              <td colspan="2"><input name="sp_pri" type="text" id="pr3" value="<?php if(isset($_POST['sp_pri'])){ echo  $_POST['sp_pri'];} ?>" />&nbsp;$</td>
            </tr>

			<tr>
              <td align="right"> Both Price:</td>
              <td colspan="2"><input name="both_pri" type="text" id="both_pri" value="<?php if(isset($_POST['both_pri'])){ echo  $_POST['both_pri'];} ?>" />&nbsp;$</td>
            </tr>
            
            <tr>
              <td align="right">Free Dump PDF:</td>
              <td colspan="2">
                <input type="text" name="free_dump_label" placeholder="Label to display on site" style="width:320px" value="<?php if(isset($_POST['free_dump_label'])){ echo htmlspecialchars($_POST['free_dump_label'], ENT_QUOTES); } ?>" /><br />
                <input type="file" name="free_dump_pdf" accept="application/pdf" />
              </td>
            </tr>
            <tr>
              <td align="right">Demo Practice PDF:</td>
              <td colspan="2">
                <input type="text" name="demo_practice_label" placeholder="Label to display on site" style="width:320px" value="<?php if(isset($_POST['demo_practice_label'])){ echo htmlspecialchars($_POST['demo_practice_label'], ENT_QUOTES); } ?>" /><br />
                <input type="file" name="demo_practice_file" accept="application/pdf" />
              </td>
            </tr>
		

           	<tr>

          <td align="right"> Is Home:</td>

          <td colspan="2"><input type="checkbox" value="1" <? if(isset($_POST['home']) && $_POST['home']=="1"){ echo "checked='checked'";}?> name="home"  id="home" /></td>

        </tr>

        <tr>

          <td align="right"> Is Hot:</td>

          <td colspan="2"><input type="checkbox" value="1" <? if(isset($_POST['hot']) && $_POST['hot']=="1"){ echo "checked='checked'";}?>  name="hot"  id="hot" /></td>

        </tr>

       
   		<tr style="display:none;">
			<td align="right"> Multiple Demo Images:</td>
			<td colspan="2"><input type="file" name="exam_demo_images[]" multiple accept="image/*"/></td>
		</tr>
		
        <tr>
          <td align="right" valign="top">Video Links:</td>
          <td colspan="2">
            <div id="youtube_links_wrapper">
              <!-- Default one empty row -->
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
            </div>
            <button type="button" id="add_more_youtube">Add More Video</button>
          </td>
        </tr>		

         <tr>
          <td valign="top" nowrap="nowrap" align="right">Exam Description:</td>
          <td height="29" colspan="2" align="left" valign="middle">
            <style>
              .feature-comparison-table {
                width: 100%;
                border-collapse: collapse;
              }
              .feature-comparison-table th,
              .feature-comparison-table td {
                border: 1px solid #cfcfcf;
                padding: 8px;
                vertical-align: middle;
              }
              .feature-comparison-table th {
                background: #f7f7f7;
                text-align: left;
              }
              .feature-comparison-table td.feature-flag-cell,
              .feature-comparison-table th.feature-flag-cell {
                text-align: center;
                width: 140px;
              }
              .feature-comparison-table .feature-name-input {
                width: 100%;
                box-sizing: border-box;
              }
              .feature-comparison-add {
                margin-top: 10px;
              }
            </style>
            <table class="feature-comparison-table">
              <thead>
                <tr>
                  <th>Feature Name</th>
                  <th class="feature-flag-cell">Real Lab Workbook</th>
                  <th class="feature-flag-cell">Real Lab Workbook + Racks</th>
                  <th class="feature-flag-cell">Real Lab Workbook + Racks + Bootcamp</th>
                  <th class="feature-flag-cell">Action</th>
                </tr>
              </thead>
              <tbody id="features_table_body" data-next-index="<?php echo count($featureRowsForForm); ?>">
                <?php foreach ($featureRowsForForm as $featureIndex => $featureRow) { ?>
                <tr>
                  <td><input type="text" class="feature-name-input" name="features[<?php echo $featureIndex; ?>][name]" value="<?php echo htmlspecialchars($featureRow['name'], ENT_QUOTES); ?>"></td>
                  <td class="feature-flag-cell"><input type="checkbox" name="features[<?php echo $featureIndex; ?>][workbook]" value="1" <?php echo !empty($featureRow['workbook']) ? 'checked="checked"' : ''; ?>></td>
                  <td class="feature-flag-cell"><input type="checkbox" name="features[<?php echo $featureIndex; ?>][racks]" value="1" <?php echo !empty($featureRow['racks']) ? 'checked="checked"' : ''; ?>></td>
                  <td class="feature-flag-cell"><input type="checkbox" name="features[<?php echo $featureIndex; ?>][bootcamp]" value="1" <?php echo !empty($featureRow['bootcamp']) ? 'checked="checked"' : ''; ?>></td>
                  <td class="feature-flag-cell"><button type="button" class="remove_feature_row">Remove</button></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
            <button type="button" id="add_feature_row" class="feature-comparison-add">Add Row</button>
          </td>
        </tr>

         <tr>

          <td align="right"> Exam SEO Title:</td>

          <td colspan="2"><input  name="vtitle" type="text" /></td>

        </tr>

        

         <tr>

          <td align="right"> Exam SEO Keywords:</td>

          <td colspan="2"><input  name="vkey" type="text"  /></td>

        </tr>

		 <tr>

          <td align="right"> Exam SEO Description:</td>

          <td colspan="2"><textarea name="edesc" cols="22" rows="6"></textarea></td>

        </tr>	
				         <tr>

          <td align="right"> Whatsapp URL:</td>

          <td colspan="2"><input  name="whatsapp_url" type="text"  /></td>

        </tr>
				         <tr>

          <td align="right"> Skype URL:</td>

          <td colspan="2"><input  name="skype_url" type="text"  /></td>

        </tr>

		<tr>

          <td align="right"> Exam Status:</td>

          <td><select name="vendor_status" id="vendor_status">

                <option value="1"<? if(isset($_POST['vendor_status']) && $_POST['vendor_status']=="1"){ echo "selected='selected'";}?>>Active</option>

                <option value="0"<? if(isset($_POST['vendor_status']) && $_POST['vendor_status']=="0"){ echo "selected='selected'";}?>>In Active</option>

                </select></td>

        </tr>

        <tr align="right">

          <td align="left">&nbsp;</td>

          <td colspan="2" align="left"><input type="submit" name="Submit" value="Submit" /></td>

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

  /* ---------------------- FAQ ROWS ---------------------- */
  const faqWrapper = document.getElementById('faq_wrapper');
  const faqAddBtn = document.getElementById('add_more_faq');
  const featuresTableBody = document.getElementById('features_table_body');
  const addFeatureBtn = document.getElementById('add_feature_row');
  const formEl = document.getElementById('Form');

  function buildFeatureRow(index) {
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td><input type="text" class="feature-name-input" name="features[${index}][name]"></td>
      <td class="feature-flag-cell"><input type="checkbox" name="features[${index}][workbook]" value="1"></td>
      <td class="feature-flag-cell"><input type="checkbox" name="features[${index}][racks]" value="1"></td>
      <td class="feature-flag-cell"><input type="checkbox" name="features[${index}][bootcamp]" value="1"></td>
      <td class="feature-flag-cell"><button type="button" class="remove_feature_row">Remove</button></td>
    `;
    return tr;
  }

  if (featuresTableBody && addFeatureBtn) {
    addFeatureBtn.addEventListener('click', function () {
      const currentIndex = parseInt(featuresTableBody.getAttribute('data-next-index') || '0', 10);
      featuresTableBody.appendChild(buildFeatureRow(currentIndex));
      featuresTableBody.setAttribute('data-next-index', String(currentIndex + 1));
    });

    featuresTableBody.addEventListener('click', function (e) {
      if (e.target.classList.contains('remove_feature_row')) {
        const row = e.target.closest('tr');
        if (row) {
          row.remove();
        }
      }
    });
  }

  if (faqWrapper && faqAddBtn) {
    faqAddBtn.addEventListener('click', function () {
      const div = document.createElement('div');
      div.className = 'faq_row';
      div.style.marginBottom = '8px';
      div.innerHTML = `
        <input type="text" name="faq_question[]" placeholder="Question" style="width:400px;">
        <input type="text" name="faq_answer[]" placeholder="Answer" style="width:400px;">
        <button type="button" class="remove_faq_row">Remove</button>
      `;
      faqWrapper.appendChild(div);
    });

    faqWrapper.addEventListener('click', function (e) {
      if (e.target.classList.contains('remove_faq_row')) {
        const row = e.target.closest('.faq_row');
        if (row) row.remove();
      }
    });
  }

  if (formEl) {
    formEl.addEventListener('submit', function () {
      const rows = faqWrapper ? faqWrapper.querySelectorAll('.faq_row') : [];
      const data = [];
      rows.forEach(function (row) {
        const q = row.querySelector('input[name="faq_question[]"]');
        const a = row.querySelector('input[name="faq_answer[]"]');
        const question = q ? q.value.trim() : '';
        const answer = a ? a.value.trim() : '';
        if (question && answer) {
          data.push({ question: question, answer: answer });
        }
      });
      const hidden = document.getElementById('faq_json');
      if (hidden) {
        hidden.value = JSON.stringify(data);
      }
    });
  }
});    
</script>

<? include("footer.php")?>

</body>

</html>
