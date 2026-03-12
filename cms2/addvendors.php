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
		$spram[1]		 =  "";
		$spram[2]		 =  "";
		$spram[3]		 =  ""; 
		$spram[4]		 =  "";
		$spram[5]		 =  "";
		$spram[6]		 =  "";
	$farr			=	array("'");
	$aarr			=	array("\'");
	if(isset($_POST['Submit']) && $_POST['Submit']  == "Submit"){
		  $spram[0]	=	$_POST['vname'];

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
		  
		  $spram[4]	=	$_POST['home'];
		  
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
	      $spram[6]	=	stripslashes($_POST['pro_desc']);
	      $spram[7]	=	stripslashes($_POST['vtitle']);
		  $spram[8]	=	stripslashes($_POST['vkey']);
		  $spram[9]	=	stripslashes($_POST['edesc']);
		  $spram[10]	=	$_POST['vendor_status'];
		  $spram[11]	=	'0';
		  $spram[13]	=	$_POST['swreg'];
	  
	 
		$objValid->add_text_field("Vendor Name", $_POST['vname'], "text", "y");

		if (!$objValid->validation())

		$strError = $objValid->create_msg();
		
		$getven	=	mysql_num_rows(mysql_query("SELECT * from tbl_vendors where ven_name='".$_POST['vname']."'"));
		if($getven!='0'){
		$strError	.=	"<b>Error! Vendor Name already Exist.</b></br>";
		}
		
		if(empty($strError))
		{
            // ---------- DEFAULT IMAGE UPLOAD (add vendor) ----------
            $spram['default_image'] = ''; // default empty

            if (isset($_FILES['default_image']) && isset($_FILES['default_image']['name']) && $_FILES['default_image']['error'] == UPLOAD_ERR_OK) {
                $dName = $_FILES['default_image']['name'];
                $dTmp  = $_FILES['default_image']['tmp_name'];
                $dSize = (int)$_FILES['default_image']['size'];

                $allowedImg = array('image/jpeg','image/png','image/gif','image/webp');

                // detect mime
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
                        $spram['default_image'] = '/uploads/vendors/' . $newName;
                    }
                } else {
                    $strError .= "<div>Default image must be JPG/PNG/GIF/WebP.</div>";
                }
            }

		    
		    // ---------- Build demo_json from the Attachments UI (PHP 5.6 safe) ----------
            $attachments = array();
            $types = isset($_POST['attachment_type']) ? (array)$_POST['attachment_type'] : array();
            $links = isset($_POST['attachment_link']) ? (array)$_POST['attachment_link'] : array();
            $hasFiles = isset($_FILES['attachment_file']) && isset($_FILES['attachment_file']['name']) && is_array($_FILES['attachment_file']['name']);
            
            $allowedMimes = array(
                'image/jpeg','image/png','image/gif','image/webp','application/pdf'
            );
            $extToMime = array( // fallback if fileinfo is missing
                'jpg' => 'image/jpg', 'jpeg' => 'image/jpeg',
                'png' => 'image/png',  'gif'  => 'image/gif',
                'webp'=> 'image/webp','pdf'  => 'application/pdf'
            );
            
            // upload dir (adjust if needed)
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
            
                if ($type === 'file' && $hasFiles) {
                    $fname = isset($_FILES['attachment_file']['name'][$i]) ? $_FILES['attachment_file']['name'][$i] : '';
                    $ftmp  = isset($_FILES['attachment_file']['tmp_name'][$i]) ? $_FILES['attachment_file']['tmp_name'][$i] : '';
                    $ferr  = isset($_FILES['attachment_file']['error'][$i]) ? $_FILES['attachment_file']['error'][$i] : UPLOAD_ERR_NO_FILE;
                    $fsize = isset($_FILES['attachment_file']['size'][$i]) ? (int)$_FILES['attachment_file']['size'][$i] : 0;
            
                    if ($ferr === UPLOAD_ERR_NO_FILE || $fname === '' || $ftmp === '') {
                        continue; // no file selected for this row
                    }
                    if ($ferr !== UPLOAD_ERR_OK || !is_uploaded_file($ftmp)) {
                        $strError .= "<div>Upload error on attachment #".($i+1)."</div>";
                        continue;
                    }
            
                    // MIME detection (prefer fileinfo, else fallback by extension)
                    $mime = '';
                    if (function_exists('finfo_open')) {
                        $finfo = @finfo_open(FILEINFO_MIME_TYPE);
                        if ($finfo) {
                            $mime = @finfo_file($finfo, $ftmp);
                            @finfo_close($finfo);
                        }
                    }
                    if ($mime === '' || $mime === false) {
                        $ext = strtolower(pathinfo($fname, PATHINFO_EXTENSION));
                        $mime = isset($extToMime[$ext]) ? $extToMime[$ext] : 'application/octet-stream';
                    }
            
                    if (!in_array($mime, $allowedMimes, true)) {
                        $strError .= "<div>File type not allowed on #".($i+1)." (".htmlspecialchars($mime).")</div>";
                        continue;
                    }
            
                    // safe filename
                    $ext      = pathinfo($fname, PATHINFO_EXTENSION);
                    $base     = pathinfo($fname, PATHINFO_FILENAME);
                    $safeBase = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $base);
                    $newName  = $safeBase . '_' . time() . '_' . mt_rand(1000, 9999) . '.' . $ext;
                    $dest     = $uploadDir . $newName;
            
                    if (!@move_uploaded_file($ftmp, $dest)) {
                        $strError .= "<div>Failed to save file #".($i+1)." (".htmlspecialchars($fname).")</div>";
                        continue;
                    }
            
                    // public/relative path for later use on site
                    $publicRel = '/uploads/vendors/' . $newName;
            
                    $attachments[] = array(
                        'mode'      => 'file',
                        'file_name' => $fname,
                        'stored_as' => $newName,
                        'path'      => $publicRel,
                        'mime'      => $mime,
                        'size'      => $fsize
                    );
                }
            }
            
            // Save JSON for DB column `demo_json` via $spram as expected by addVendor()
            $spram['attachments']   = json_encode($attachments); // keep simple for PHP 5.6

    		$youtube_links = isset($_POST['youtube_links']) ? array_filter($_POST['youtube_links']) : [];
            $spram['youtube_links'] = json_encode($youtube_links);

		    $addevent = $objEvent->addVendor($spram);
		
    		$strError	.= "Vendor has been added successfully";
    		//header("location:vendormanage.php");
		}
    }
		
?>

<? include ("header.php"); ?>
<table cellpadding="0" cellspacing="0" width="100%">
<tr>
<td width="190" class="leftside"><?php include ("menu.php"); ?></td>

<td width="810" class="rightside"><h2>Add Vendors!</h2>

Welcome to your<?=$websitename?> Website control panel. Here you can manage and modify every aspect of your <?=$websitename?>.<br />

<form name="Form" id="Form" method="post" action="" enctype="multipart/form-data"><br />

  <table cellpadding='0' cellspacing='0' width='100%'>
    <tr>
      <td></td>
    </tr>
    <tr>
      <td class='header'>Add Vendors </td>
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
          <td colspan="2"><input  name="vname" id="vname" type="text" onblur="setenable();"  value="<?php if(isset($_POST['vname'])){ echo  $_POST['vname'];} ?>" /></td>
        </tr>
           	<tr>
          <td align="right"> Is Home:</td>
          <td colspan="2"><input type="checkbox" value="1" <? if(isset($_POST['home']) && $_POST['home']=="1"){ echo "checked='checked'";}?> name="home"  id="home" /></td>
        </tr>
        <tr>
          <td align="right"> Is Hot:</td>
          <td colspan="2"><input type="checkbox" value="1" <? if(isset($_POST['hot']) && $_POST['hot']=="1"){ echo "checked='checked'";}?>  name="hot"  id="hot" /></td>
        </tr>
        
       <!-- <tr>
          <td align="right"> All Vendor:</td>
          <td colspan="2"><input type="checkbox" value="1" <? if(isset($_POST['allvendor']) && $_POST['allvendor']=="1"){ echo "checked='checked'";}?>  name="allvendor"  id="allvendor" /></td>
        </tr>-->
        
       <tr>
          <td align="right" valign="top">Default Image:</td>
          <td colspan="2">
            <input type="file" name="default_image" accept="image/*">
            <div style="color:#666; font-size:11px;">Upload JPG / PNG / GIF / WebP.</div>
          </td>
        </tr>

         <tr>
          <td valign="top" nowrap="nowrap" align="right">Vendor Description:</td>
          <td height="29" colspan="2" align="left" valign="middle"><textarea id="pro_desc" name="pro_desc" rows=4 cols=30><?=stripslashes($_POST['pro_desc'])?></textarea>
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
          <td colspan="2"><input  name="vtitle" type="text" value="<?php if(isset($_POST['vtitle'])){ echo  $_POST['vtitle'];} ?>" /></td>
        </tr>
        
         <tr>
          <td align="right"> Vendor SEO Keywords:</td>
          <td colspan="2"><input  name="vkey" type="text" value="<?php if(isset($_POST['vkey'])){ echo  $_POST['vkey'];} ?>" /></td>
        </tr>
		 <tr>
          <td align="right"> Vendor SEO Description:</td>
          <td colspan="2"><textarea name="edesc" cols="22" rows="6"><?php if(isset($_POST['edesc'])){ echo  $_POST['edesc'];} ?></textarea></td>
        </tr>	
		<tr>
          <td align="right"> Vendor Status:</td>
          <td><select name="vendor_status" id="vendor_status">
                <option value="1"<? if(isset($_POST['vendor_status']) && $_POST['vendor_status']=="1"){ echo "selected='selected'";}?>>Active</option>
                <option value="0"<? if(isset($_POST['vendor_status']) && $_POST['vendor_status']=="0"){ echo "selected='selected'";}?>>In Active</option>
                </select></td>
        </tr>
        <tr>
          <td align="right" valign="top">YouTube Links:</td>
          <td colspan="2">
            <div id="youtube_links_wrapper">
              <!-- Default one empty row -->
              <div class="youtube_link_row" style="margin-bottom:8px;">
                <input type="text" name="youtube_links[]" placeholder="Enter YouTube URL" style="width:400px;">
                <button type="button" class="remove_row">Remove</button>
              </div>
            </div>
            <button type="button" id="add_more_youtube">Add More</button>
          </td>
        </tr>
        
        <tr id="attachments_row">
            <td align="right" valign="top">Demos:</td>
            <td colspan="2">
            <div id="attachments_wrapper">
              <!-- default one row -->
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
                  <small>Accepted: images (jpg/png/gif/webp) or PDF. Max ~5–10MB recommended.</small>
                </div>
            
                <button type="button" class="remove_attach_row">Remove</button>
              </div>
            </div>
            <button type="button" id="add_more_attachments">Add More</button>
            </td>
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
<script>
document.addEventListener('DOMContentLoaded', function () {
  /* ---------------------- ATTACHMENTS ---------------------- */
  const attWrapper = document.getElementById('attachments_wrapper');
  const attAddBtn  = document.getElementById('add_more_attachments');

  if (attWrapper && attAddBtn) {
    // template for a new attachment row
    function newAttachRow() {
      const div = document.createElement('div');
      div.className = 'attach_row';
      div.style.cssText = 'margin-bottom:10px; padding:8px; border:1px dashed #ccc;';
      div.innerHTML = `
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
          <small>Accepted: images (jpg/png/gif/webp) or PDF. Max ~5–10MB recommended.</small>
        </div>
        <button type="button" class="remove_attach_row">Remove</button>
      `;
      return div;
    }

    function setRowMode(row) {
      const sel    = row.querySelector('.attachment_type');
      const linkBx = row.querySelector('.attach_link_box');
      const fileBx = row.querySelector('.attach_file_box');
      if (!sel || !linkBx || !fileBx) return;

      if (sel.value === 'file') {
        linkBx.style.display = 'none';
        fileBx.style.display = '';
        const linkInput = linkBx.querySelector('input[name="attachment_link[]"]');
        if (linkInput) linkInput.value = '';
      } else {
        linkBx.style.display = '';
        fileBx.style.display = 'none';
        const fileInput = fileBx.querySelector('input[type="file"]');
        if (fileInput) fileInput.value = '';
      }
    }

    // init existing rows
    attWrapper.querySelectorAll('.attach_row').forEach(row => {
      const sel = row.querySelector('.attachment_type');
      if (sel) sel.addEventListener('change', () => setRowMode(row));
      setRowMode(row);
    });

    // add new row
    attAddBtn.addEventListener('click', function () {
      const row = newAttachRow();
      attWrapper.appendChild(row);
      const sel = row.querySelector('.attachment_type');
      if (sel) sel.addEventListener('change', () => setRowMode(row));
      setRowMode(row);
    });

    // remove row
    attWrapper.addEventListener('click', function (e) {
      if (e.target.classList.contains('remove_attach_row')) {
        const row = e.target.closest('.attach_row');
        if (row) row.remove();
      }
    });
  }

  /* ---------------------- YOUTUBE LINKS ---------------------- */
  const ytWrapper = document.getElementById('youtube_links_wrapper');
  const ytAddBtn  = document.getElementById('add_more_youtube');

  if (ytWrapper && ytAddBtn) {
    ytAddBtn.addEventListener('click', function () {
      const div = document.createElement('div');
      div.className = 'youtube_link_row';
      div.style.marginBottom = '8px';
      div.innerHTML = `
        <input type="text" name="youtube_links[]" placeholder="Enter YouTube URL" style="width:400px;">
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