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


//--------------------Creat objects---------------------------

	$objDBcon    = new classDbConnection; 			// VALIDATION CLASS OBJECT
	$objValid 	 = new Validate_fields;
	$objEvent	=	new classEvents($objDBcon);	// VALIDATION CLASS classCategory

	$strError	=	"";
	$spram[0]		 =  $_GET['ven_id'];
	$farr			=	array("'");
	$aarr			=	array("\'");
	if(isset($_POST['Submit']) && $_POST['Submit']  == "Update")

	{
		  $spram[1]	=	$_POST['vname'];
		  $spram[2]	=	$_POST['vurl'];
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
		  $spram[12]	=	'0';
	  
	 
		$objValid->add_text_field("Vendor Name", $_POST['vname'], "text", "y");

		if (!$objValid->validation())

		$strError = $objValid->create_msg();
		if($_POST['vnamehidden']!= $_POST['vname']){
		$getven	=	mysql_num_rows(mysql_query("SELECT * from tbl_vendors where ven_name='".$_POST['vname']."'"));
		if($getven!='0'){
		$strError	.=	"<b>Error! Vendor Name already Exist.</b></br>";
		}
		}
		
		if(empty($strError))
		{
		                // ---------- DEFAULT IMAGE UPLOAD ----------
            // keep old image by default
            $oldDefaultImg = isset($_POST['existing_default_image']) ? trim($_POST['existing_default_image']) : '';

            if (isset($_FILES['default_image']) && isset($_FILES['default_image']['name']) && $_FILES['default_image']['error'] == UPLOAD_ERR_OK) {
                $dName = $_FILES['default_image']['name'];
                $dTmp  = $_FILES['default_image']['tmp_name'];
                $dSize = (int)$_FILES['default_image']['size'];

                // allow only images
                $allowedImg = array('image/jpeg','image/png','image/gif','image/webp');
                $mime = '';
                if (function_exists('finfo_open')) {
                    $finfo = @finfo_open(FILEINFO_MIME_TYPE);
                    if ($finfo) {
                        $mime = @finfo_file($finfo, $dTmp);
                        @finfo_close($finfo);
                    }
                }
                if ($mime == '' || $mime === false) {
                    $extGuess = strtolower(pathinfo($dName, PATHINFO_EXTENSION));
                    $map = array(
                        'jpg'  => 'image/jpeg',
                        'jpeg' => 'image/jpeg',
                        'png'  => 'image/png',
                        'gif'  => 'image/gif',
                        'webp' => 'image/webp',
                    );
                    $mime = isset($map[$extGuess]) ? $map[$extGuess] : 'application/octet-stream';
                }

                if (in_array($mime, $allowedImg, true)) {
                    $uploadDirImg = dirname(__FILE__) . '/../uploads/vendors/';
                    if (!is_dir($uploadDirImg)) {
                        @mkdir($uploadDirImg, 0775, true);
                    }
                    $ext      = pathinfo($dName, PATHINFO_EXTENSION);
                    $base     = pathinfo($dName, PATHINFO_FILENAME);
                    $safeBase = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $base);
                    $newName  = $safeBase . '_thumb_' . time() . '_' . mt_rand(1000,9999) . '.' . $ext;
                    $dest     = $uploadDirImg . $newName;

                    if (@move_uploaded_file($dTmp, $dest)) {
                        // save relative path to DB
                        $spram['default_image'] = '/uploads/vendors/' . $newName;
                    } else {
                        // keep old image if move failed
                        $spram['default_image'] = $oldDefaultImg;
                    }
                } else {
                    // not allowed mime -> keep old
                    $spram['default_image'] = $oldDefaultImg;
                    $strError .= "<div>Default image must be JPG/PNG/GIF/WebP.</div>";
                }
            } else {
                // no new file -> keep old
                $spram['default_image'] = $oldDefaultImg;
            }


		    // ---------- Rebuild demo_json from form (PHP 5.6 safe) ----------
            $attachments = array();
            $types = isset($_POST['attachment_type']) ? (array)$_POST['attachment_type'] : array();
            $links = isset($_POST['attachment_link']) ? (array)$_POST['attachment_link'] : array();
            
            // existing file metadata from hidden inputs (same index as rows)
            $exist_file_name  = isset($_POST['attachment_existing_file_name'])  ? (array)$_POST['attachment_existing_file_name']  : array();
            $exist_stored_as  = isset($_POST['attachment_existing_stored_as'])  ? (array)$_POST['attachment_existing_stored_as']  : array();
            $exist_path       = isset($_POST['attachment_existing_path'])       ? (array)$_POST['attachment_existing_path']       : array();
            $exist_mime       = isset($_POST['attachment_existing_mime'])       ? (array)$_POST['attachment_existing_mime']       : array();
            $exist_size       = isset($_POST['attachment_existing_size'])       ? (array)$_POST['attachment_existing_size']       : array();
            
            $hasFiles = isset($_FILES['attachment_file']) && isset($_FILES['attachment_file']['name']) && is_array($_FILES['attachment_file']['name']);
            
            $allowedMimes = array('image/jpeg','image/png','image/gif','image/webp','application/pdf');
            $extToMime = array(
                'jpg'=>'image/jpeg','jpeg'=>'image/jpeg','png'=>'image/png','gif'=>'image/gif','webp'=>'image/webp','pdf'=>'application/pdf'
            );
            
            $uploadDir = dirname(__FILE__) . '/../uploads/vendors/';
            if (!is_dir($uploadDir)) { @mkdir($uploadDir, 0775, true); }
            
            $total = count($types);
            for ($i = 0; $i < $total; $i++) {
                $type = trim((string)$types[$i]);
            
                if ($type === 'link') {
                    $url = isset($links[$i]) ? trim($links[$i]) : '';
                    if ($url !== '') {
                        $attachments[] = array('mode' => 'link', 'url' => $url);
                    }
                    continue;
                }
            
                if ($type === 'file') {
                    // New upload for this row?
                    $hasNew = false;
                    $fname = ''; $ftmp = ''; $ferr = UPLOAD_ERR_NO_FILE; $fsize = 0;
            
                    if ($hasFiles) {
                        $fname = isset($_FILES['attachment_file']['name'][$i]) ? $_FILES['attachment_file']['name'][$i] : '';
                        $ftmp  = isset($_FILES['attachment_file']['tmp_name'][$i]) ? $_FILES['attachment_file']['tmp_name'][$i] : '';
                        $ferr  = isset($_FILES['attachment_file']['error'][$i]) ? $_FILES['attachment_file']['error'][$i] : UPLOAD_ERR_NO_FILE;
                        $fsize = isset($_FILES['attachment_file']['size'][$i]) ? (int)$_FILES['attachment_file']['size'][$i] : 0;
                        $hasNew = ($ferr === UPLOAD_ERR_OK && $fname !== '' && $ftmp !== '');
                    }
            
                    if ($hasNew) {
                        // Detect MIME
                        $mime = '';
                        if (function_exists('finfo_open')) {
                            $finfo = @finfo_open(FILEINFO_MIME_TYPE);
                            if ($finfo) {
                                $mime = @finfo_file($finfo, $ftmp);
                                @finfo_close($finfo);
                            }
                        }
                        if ($mime === '' || $mime === false) {
                            $extGuess = strtolower(pathinfo($fname, PATHINFO_EXTENSION));
                            $mime = isset($extToMime[$extGuess]) ? $extToMime[$extGuess] : 'application/octet-stream';
                        }
                        if (!in_array($mime, $allowedMimes, true)) {
                            $strError .= "<div>File type not allowed on attachment #".($i+1)." (".htmlspecialchars($mime).")</div>";
                            continue;
                        }
            
                        // Save
                        $ext      = pathinfo($fname, PATHINFO_EXTENSION);
                        $base     = pathinfo($fname, PATHINFO_FILENAME);
                        $safeBase = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $base);
                        $newName  = $safeBase . '_' . time() . '_' . mt_rand(1000,9999) . '.' . $ext;
                        $dest     = $uploadDir . $newName;
            
                        if (!@move_uploaded_file($ftmp, $dest)) {
                            $strError .= "<div>Failed to save file #".($i+1)." (".htmlspecialchars($fname).")</div>";
                            continue;
                        }
            
                        $publicRel = '/uploads/vendors/' . $newName;
                        $attachments[] = array(
                            'mode'      => 'file',
                            'file_name' => $fname,
                            'stored_as' => $newName,
                            'path'      => $publicRel,
                            'mime'      => $mime,
                            'size'      => $fsize
                        );
                    } else {
                        // Keep existing file metadata, if present for this row
                        $ex_stored = isset($exist_stored_as[$i]) ? trim($exist_stored_as[$i]) : '';
                        $ex_path   = isset($exist_path[$i]) ? trim($exist_path[$i]) : '';
                        if ($ex_stored !== '' && $ex_path !== '') {
                            $attachments[] = array(
                                'mode'      => 'file',
                                'file_name' => isset($exist_file_name[$i]) ? $exist_file_name[$i] : '',
                                'stored_as' => $ex_stored,
                                'path'      => $ex_path,
                                'mime'      => isset($exist_mime[$i]) ? $exist_mime[$i] : '',
                                'size'      => isset($exist_size[$i]) ? (int)$exist_size[$i] : 0
                            );
                        }
                        // If nothing existing and no new file -> skip row
                    }
                }
            }
            
            // Save JSON & YouTube links to $spram for updateVendor()
            $spram['attachments']   = json_encode($attachments);

    		$youtube_links = isset($_POST['youtube_links']) ? array_filter($_POST['youtube_links']) : [];
            $spram['youtube_links'] = json_encode($youtube_links);
		    $addevent = $objEvent->updateVendor($spram);
		
		    $strError	.= "Vendor has been updated successfully";
		    header("location:vendormanage.php");
		}
		}elseif(isset($_GET['ven_id']) && is_numeric($_GET['ven_id']))
		{
	
			$spram[0]	=	$_GET['ven_id'];
			$resultSet		=	$objEvent->get_vendor_by_id($spram);
			
			while($row		=	mysql_fetch_array($resultSet))
			{
				$spram[1]	=	$row['ven_name'];
				$spram[2]	=	$row['ven_url'];
				$spram[5]	=	$row['ven_home'];
				$spram[6]	= 	$row['ven_hot'];
				
				$spram[7]	= 	$row['ven_descr'];
				$spram[8]	= 	$row['ven_title'];
				$spram[9]	= 	$row['ven_keywords'];
				$spram[10]	= 	$row['ven_desc'];
				$spram[11]	= 	$row['ven_status'];
				$spram[12]	= 	$row['allvendors'];
				$youtube_links = array();
                if (!empty($row['youtube_links'])) {
                    $youtube_links = json_decode($row['youtube_links'], true);
                }
                $attachments_rows = array();
                if (!empty($row['demo_json'])) {
                    $tmp = json_decode($row['demo_json'], true);
                    if (is_array($tmp)) {
                        $attachments_rows = $tmp;
                    }
                }
                $spram['default_image'] = !empty($row['default_image']) ? $row['default_image'] : '';

			}
	}
		
?>

<? include ("header.php"); ?>
<table cellpadding="0" cellspacing="0" width="102%">
<tr>
<td width="190" class="leftside"><?php include ("menu.php"); ?></td>

<td width="810" class="rightside"><h2>Edit Vendor!</h2>

Welcome to your<?=$websitename?> Website control panel. Here you can manage and modify every aspect of your <?=$websitename?>.<br />

<form name="Form" id="Form" method="post" action="" enctype="multipart/form-data"><br />

  <table cellpadding='0' cellspacing='0' width='100%'>
    <tr>
      <td></td>
    </tr>
    <tr>
      <td class='header'>Edit Vendor </td>
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
          <td colspan="2"><input  name="vname" id="vname" type="text" onblur="setenable();"  value="<?php if(isset($spram[1])){ echo  $spram[1];} ?>" /></td>
        </tr>
       
          
            <tr>
              <td align="right"> Vendor URL:</td>
              <td colspan="2"><input name="vurl" type="text" id="vurl" value="<?php if(isset($spram[2])){ echo  $spram[2];} ?>" />&nbsp;</td>
            </tr>
           
           	<tr>
          <td align="right"> Is Home:</td>
          <td colspan="2"><input type="checkbox" value="1" <? if(isset($spram[5]) && $spram[5]=="1"){ echo "checked='checked'";}?> name="home"  id="home" /></td>
        </tr>
        <tr>
          <td align="right"> Is Hot:</td>
          <td colspan="2"><input type="checkbox" value="1" <? if(isset($spram[6]) && $spram[6]=="1"){ echo "checked='checked'";}?>  name="hot"  id="hot" /></td>
        </tr>
        
        <tr>
          <td align="right" valign="top">Default Image:</td>
          <td colspan="2">
            <?php if (!empty($spram['default_image'])) { ?>
                <div style="margin-bottom:6px;">
                    <img src="<?= htmlspecialchars($spram['default_image']); ?>" alt="Current image" style="max-width:150px; border:1px solid #ccc; padding:3px;">
                </div>
            <?php } ?>
            <input type="file" name="default_image" accept="image/*">
            <input type="hidden" name="existing_default_image" value="<?= !empty($spram['default_image']) ? htmlspecialchars($spram['default_image']) : '' ?>">
            <div style="color:#666; font-size:11px;">Upload JPG / PNG / GIF / WebP. Leave blank to keep existing.</div>
          </td>
        </tr>

         <tr>
          <td valign="top" nowrap="nowrap" align="right">Vendor Description:</td>
          <td height="29" colspan="2" align="left" valign="middle"><textarea id="pro_desc" name="pro_desc" rows=4 cols=30><?=stripslashes($spram[7])?></textarea>
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
          <td align="right"> Vendor SEO Title:</td>
          <td colspan="2"><input  name="vtitle" type="text" value="<?php if(isset($spram[8])){ echo  $spram[8];} ?>" /></td>
        </tr>
        
         <tr>
          <td align="right"> Vendor SEO Keywords:</td>
          <td colspan="2"><input  name="vkey" type="text" value="<?php if(isset($spram[9])){ echo  $spram[9];} ?>" /></td>
        </tr>
		 <tr>
          <td align="right"> Vendor SEO Description:</td>
          <td colspan="2"><textarea name="edesc" cols="22" rows="6"><?php if(isset($spram[10])){ echo  $spram[10];} ?></textarea></td>
        </tr>	
		<tr>
          <td align="right"> Vendor Status:</td>
          <td><select name="vendor_status" id="vendor_status">
                <option value="1"<? if(isset($spram[11]) && $spram[11]=="1"){ echo "selected='selected'";}?>>Active</option>
                <option value="0"<? if(isset($spram[11]) && $spram[11]=="0"){ echo "selected='selected'";}?>>In Active</option>
                </select><input type="hidden" name="vnamehidden" id="vnamehidden" value="<?=$spram[1]?>" /></td>
        </tr>
        <tr>
        <td align="right" valign="top">YouTube Links:</td>
        <td colspan="2">
        <div id="youtube_links_wrapper">
          <?php
          if (!empty($youtube_links)) {
              foreach ($youtube_links as $i => $link) {
                  ?>
                  <div class="youtube_link_row" style="margin-bottom:8px;">
                    <input type="text" name="youtube_links[]" value="<?= htmlspecialchars($link) ?>" placeholder="Enter YouTube URL" style="width:400px;">
                    <button type="button" class="remove_row">Remove</button>
                  </div>
                  <?php
              }
          } else {
              // Show one empty row by default
              ?>
              <div class="youtube_link_row" style="margin-bottom:8px;">
                <input type="text" name="youtube_links[]" placeholder="Enter YouTube URL" style="width:400px;">
                <button type="button" class="remove_row">Remove</button>
              </div>
              <?php
          }
          ?>
        </div>
        <button type="button" id="add_more_youtube">Add More</button>
        </td>
        </tr>
        
        <tr id="attachments_row">
          <td align="right" valign="top">Demos:</td>
          <td colspan="2">
            <div id="attachments_wrapper">
              <?php
              // Render existing attachments if any; else render one blank row
              if (!empty($attachments_rows)) {
                  foreach ($attachments_rows as $idx => $att) {
                      $mode = isset($att['mode']) ? $att['mode'] : 'link';
                      $isLink = ($mode === 'link');
                      $isFile = ($mode === 'file');
        
                      $url        = $isLink ? (isset($att['url']) ? $att['url'] : '') : '';
                      $file_name  = $isFile ? (isset($att['file_name']) ? $att['file_name'] : '') : '';
                      $stored_as  = $isFile ? (isset($att['stored_as']) ? $att['stored_as'] : '') : '';
                      $path       = $isFile ? (isset($att['path']) ? $att['path'] : '') : '';
                      $mime       = $isFile ? (isset($att['mime']) ? $att['mime'] : '') : '';
                      $size       = $isFile ? (isset($att['size']) ? (int)$att['size'] : 0) : 0;
                      ?>
                      <div class="attach_row" style="margin-bottom:10px; padding:8px; border:1px dashed #ccc;">
                        <div style="margin-bottom:6px;">
                          <label>Type:
                            <select name="attachment_type[]" class="attachment_type">
                              <option value="link" <?php echo $isLink ? 'selected' : ''; ?>>Link</option>
                              <option value="file" <?php echo $isFile ? 'selected' : ''; ?>>File (image/pdf)</option>
                            </select>
                          </label>
                        </div>
        
                        <div class="attach_link_box" style="<?php echo $isLink ? '' : 'display:none;'; ?> margin-bottom:6px;">
                          <input type="text" name="attachment_link[]" value="<?php echo htmlspecialchars($url); ?>" placeholder="https://example.com" style="width:420px;">
                        </div>
        
                        <div class="attach_file_box" style="<?php echo $isFile ? '' : 'display:none;'; ?> margin-bottom:6px;">
                          <?php if ($isFile && $path != '') { ?>
                            <div style="margin-bottom:6px;">
                              Current: <a href="<?php echo htmlspecialchars($path); ?>" target="_blank"><?php echo htmlspecialchars(basename($path)); ?></a>
                            </div>
                          <?php } ?>
                          <input type="file" name="attachment_file[]" accept="image/*,application/pdf">
                          <small>Leave empty to keep current file. Accepted: images/PDF.</small>
        
                          <!-- keep existing meta in hidden fields so we can preserve if no new upload -->
                          <input type="hidden" name="attachment_existing_file_name[]" value="<?php echo htmlspecialchars($file_name); ?>">
                          <input type="hidden" name="attachment_existing_stored_as[]" value="<?php echo htmlspecialchars($stored_as); ?>">
                          <input type="hidden" name="attachment_existing_path[]" value="<?php echo htmlspecialchars($path); ?>">
                          <input type="hidden" name="attachment_existing_mime[]" value="<?php echo htmlspecialchars($mime); ?>">
                          <input type="hidden" name="attachment_existing_size[]" value="<?php echo (int)$size; ?>">
                        </div>
        
                        <button type="button" class="remove_attach_row">Remove</button>
                      </div>
                      <?php
                  }
              } else {
                  // One blank row if none exist
                  ?>
                  <div class="attach_row" style="margin-bottom:10px; padding:8px; border:1px dashed #ccc;">
                    <div style="margin-bottom:6px;">
                      <label>Type:
                        <select name="attachment_type[]" class="attachment_type">
                          <option value="link" selected>Link</option>
                          <option value="file">File (image/pdf)</option>
                        </select>
                      </label>
                    </div>
        
                    <div class="attach_link_box" style="margin-bottom:6px;">
                      <input type="text" name="attachment_link[]" placeholder="https://example.com" style="width:420px;">
                    </div>
        
                    <div class="attach_file_box" style="display:none; margin-bottom:6px;">
                      <input type="file" name="attachment_file[]" accept="image/*,application/pdf">
                      <small>Accepted: images/PDF.</small>
        
                      <!-- keep hidden fields empty for blank row -->
                      <input type="hidden" name="attachment_existing_file_name[]" value="">
                      <input type="hidden" name="attachment_existing_stored_as[]" value="">
                      <input type="hidden" name="attachment_existing_path[]" value="">
                      <input type="hidden" name="attachment_existing_mime[]" value="">
                      <input type="hidden" name="attachment_existing_size[]" value="0">
                    </div>
        
                    <button type="button" class="remove_attach_row">Remove</button>
                  </div>
                  <?php
              }
              ?>
            </div>
            <button type="button" id="add_more_attachments">Add More</button>
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
<script>
document.addEventListener('DOMContentLoaded', function() {
  const wrapper = document.getElementById('youtube_links_wrapper');
  const addBtn = document.getElementById('add_more_youtube');

  // Add new row
  addBtn.addEventListener('click', function() {
    const div = document.createElement('div');
    div.classList.add('youtube_link_row');
    div.style.marginBottom = '8px';
    div.innerHTML = `
      <input type="text" name="youtube_links[]" placeholder="Enter YouTube URL" style="width:400px;">
      <button type="button" class="remove_row">Remove</button>
    `;
    wrapper.appendChild(div);
  });

  // Remove row
  wrapper.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove_row')) {
      const row = e.target.closest('.youtube_link_row');
      row.remove();
    }
  });
  
  /* ---------------------- ATTACHMENTS ---------------------- */
  var attWrapper = document.getElementById('attachments_wrapper');
  var attAddBtn  = document.getElementById('add_more_attachments');

  if (attWrapper && attAddBtn) {
    function newAttachRowHtml() {
      // include empty hidden fields so indexes line up with files/links
      return '' +
      '<div class="attach_row" style="margin-bottom:10px; padding:8px; border:1px dashed #ccc;">' +
        '<div style="margin-bottom:6px;">' +
          '<label>Type:' +
            '<select name="attachment_type[]" class="attachment_type">' +
              '<option value="link" selected>Link</option>' +
              '<option value="file">File (image/pdf)</option>' +
            '</select>' +
          '</label>' +
        '</div>' +
        '<div class="attach_link_box" style="margin-bottom:6px;">' +
          '<input type="text" name="attachment_link[]" placeholder="https://example.com" style="width:420px;">' +
        '</div>' +
        '<div class="attach_file_box" style="display:none; margin-bottom:6px;">' +
          '<input type="file" name="attachment_file[]" accept="image/*,application/pdf">' +
          '<small>Accepted: images/PDF.</small>' +
          '<input type="hidden" name="attachment_existing_file_name[]" value="">' +
          '<input type="hidden" name="attachment_existing_stored_as[]" value="">' +
          '<input type="hidden" name="attachment_existing_path[]" value="">' +
          '<input type="hidden" name="attachment_existing_mime[]" value="">' +
          '<input type="hidden" name="attachment_existing_size[]" value="0">' +
        '</div>' +
        '<button type="button" class="remove_attach_row">Remove</button>' +
      '</div>';
    }

    function setRowMode(row) {
      var sel    = row.querySelector('.attachment_type');
      var linkBx = row.querySelector('.attach_link_box');
      var fileBx = row.querySelector('.attach_file_box');
      if (!sel || !linkBx || !fileBx) return;

      if (sel.value === 'file') {
        linkBx.style.display = 'none';
        fileBx.style.display = '';
        var linkInput = linkBx.querySelector('input[name="attachment_link[]"]');
        if (linkInput) linkInput.value = '';
      } else {
        linkBx.style.display = '';
        fileBx.style.display = 'none';
        var fileInput = fileBx.querySelector('input[type="file"]');
        if (fileInput) fileInput.value = '';
      }
    }

    // init current rows
    var rows = attWrapper.querySelectorAll('.attach_row');
    for (var r = 0; r < rows.length; r++) {
      (function(row){
        var sel = row.querySelector('.attachment_type');
        if (sel) {
          sel.addEventListener('change', function(){ setRowMode(row); });
        }
        setRowMode(row);
      })(rows[r]);
    }

    // add new
    attAddBtn.addEventListener('click', function(){
      var temp = document.createElement('div');
      temp.innerHTML = newAttachRowHtml();
      var row = temp.firstChild;
      attWrapper.appendChild(row);
      var sel = row.querySelector('.attachment_type');
      if (sel) sel.addEventListener('change', function(){ setRowMode(row); });
      setRowMode(row);
    });

    // remove
    attWrapper.addEventListener('click', function(e){
      if (e.target && e.target.classList.contains('remove_attach_row')) {
        var row = e.target.closest('.attach_row');
        if (row) row.parentNode.removeChild(row);
      }
    });
  }
});
</script>

<? include("footer.php")?>
</body>
</html>