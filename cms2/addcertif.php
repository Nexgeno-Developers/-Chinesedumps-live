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
    $spram[12]	=	$_POST['cmb_cate'];
    $spram[13]	=	'0';
    $spram[29]	=	$_POST['startdate'];
    
    $demoPdfData = array();
    
    if (isset($_FILES['demo_file']['name']) && is_array($_FILES['demo_file']['name'])) {
        $uploadDir = "../uploads/demo_pdfs/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
    
        $allowed = array('pdf', 'doc', 'docx', 'jpg', 'png');
        $downloadNames = isset($_POST['demo_download_name']) ? $_POST['demo_download_name'] : array();
    
        for ($i = 0; $i < count($_FILES['demo_file']['name']); $i++) {
            $tmpFile      = $_FILES['demo_file']['tmp_name'][$i];
            $originalFile = $_FILES['demo_file']['name'][$i];
            $newName      = isset($downloadNames[$i]) ? trim($downloadNames[$i]) : '';
    
            if (!empty($originalFile) && is_uploaded_file($tmpFile)) {
                $ext = strtolower(pathinfo($originalFile, PATHINFO_EXTENSION));
    
                if (in_array($ext, $allowed)) {
                    $uniqueFile = uniqid("demoCert_") . '.' . $ext;
                    $destination = $uploadDir . $uniqueFile;
    
                    if (move_uploaded_file($tmpFile, $destination)) {
                        $demoPdfData[] = array(
                            'name' => !empty($newName) ? $newName : $originalFile,
                            'file' => $destination
                        );
                    }
                }
            }
        }
    }
    
    // Final JSON string to save (escaped for DB)
    $spram[30] = !empty($demoPdfData)
        ? mysql_real_escape_string(json_encode($demoPdfData))
        : '';
        
    $objValid->add_text_field("Date", $_POST['startdate'], "text", "y");
    $objValid->add_text_field("Certification Name", $_POST['vname'], "text", "y");
    $objValid->add_text_field("Vendor Name", $_POST['cmb_cate'], "text", "y");
    
    if (!$objValid->validation())
    
    $strError = $objValid->create_msg();
    
    $getven	=	mysql_num_rows(mysql_query("SELECT * from tbl_cert where cert_name='".$_POST['vname']."'"));
    if($getven!='0'){
        $strError	.=	"<b>Error! Certification Name already Exist.</b></br>";
    }
    
    if(empty($strError))
    {
    
        $addevent = $objEvent->addCertif($spram);
        $strError	.= "Certification has been added successfully";
        //header("location:certifmanage.php");
    }
}
$category = $objEvent->fillComboCategory($_POST['cmb_cate']);
?>

<? include ("header.php"); ?>
<table cellpadding="0" cellspacing="0" width="100%">
<tr>
<td width="190" class="leftside"><?php include ("menu.php"); ?></td>

<td width="810" class="rightside"><h2>Add Certification(Bundle)!</h2>

Welcome to your<?=$websitename?> Website control panel. Here you can manage and modify every aspect of your <?=$websitename?>.<br />

<form name="Form" id="Form" method="post" action="" enctype="multipart/form-data"><br />

  <table cellpadding='0' cellspacing='0' width='100%'>
    <tr>
      <td></td>
    </tr>
    <tr>
      <td class='header'>Add Certification(Bundle) </td>
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
          <td colspan="2"><input  name="vname" id="vname" type="text" onblur="setenable();"  value="<?php if(isset($_POST['vname'])){ echo  $_POST['vname'];} ?>" /></td>
        </tr>
       <tr>
              <td align="right">* Date: </td>
              <td colspan="2"><input name="startdate" type="text" readonly="readonly"  id="startdate" value="<?php if(isset($_POST['startdate'])){ echo  $_POST['startdate'];} ?>" />&nbsp;<img src="images/calendar-icon.gif" alt="Start Time" onclick="displayCalendarSe(document.Form.startdate,'yyyy-mm-dd',this)" /></td>
            </tr>
            
           	<tr>
          <td align="right"> Is Home:</td>
          <td colspan="2"><input type="checkbox" value="1" <? if(isset($_POST['home']) && $_POST['home']=="1"){ echo "checked='checked'";}?> name="home"  id="home" /></td>
        </tr>
        <tr>
          <td align="right"> Is Hot:</td>
          <td colspan="2"><input type="checkbox" value="1" <? if(isset($_POST['hot']) && $_POST['hot']=="1"){ echo "checked='checked'";}?>  name="hot"  id="hot" /></td>
        </tr>
        
       
         <tr>
          <td valign="top" nowrap="nowrap" align="right">Certification Description:</td>
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
          <td align="right"> Certification SEO Title:</td>
          <td colspan="2"><input  name="vtitle" type="text" value="<?php if(isset($_POST['vtitle'])){ echo  $_POST['vtitle'];} ?>" /></td>
        </tr>
        
         <tr>
          <td align="right"> Certification SEO Keywords:</td>
          <td colspan="2"><input  name="vkey" type="text" value="<?php if(isset($_POST['vkey'])){ echo  $_POST['vkey'];} ?>" /></td>
        </tr>
		 <tr>
          <td align="right"> Certification SEO Description:</td>
          <td colspan="2"><textarea name="edesc" cols="22" rows="6"><?php if(isset($_POST['edesc'])){ echo  $_POST['edesc'];} ?></textarea></td>
        </tr>	
		<tr>
          <td align="right"> Certification Status:</td>
          <td><select name="vendor_status" id="vendor_status">
                <option value="1"<? if(isset($_POST['vendor_status']) && $_POST['vendor_status']=="1"){ echo "selected='selected'";}?>>Active</option>
                <option value="0"<? if(isset($_POST['vendor_status']) && $_POST['vendor_status']=="0"){ echo "selected='selected'";}?>>In Active</option>
                </select></td>
        </tr>
        <tr>
            <td align="right">Demo PDF Name, File:</td>
            <td>
                <div id="certification_wrapper">
                    <div class="cert-group">
                        <input type="text" name="demo_download_name[]" placeholder="Button Name" required />
                        <input type="file" name="demo_file[]" accept=".pdf,.doc,.docx" required />
                    </div>
                </div>
                <button type="button" onclick="addMore()">Add More</button>
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
function addMore() {
    var wrapper = document.getElementById('certification_wrapper');
    var newGroup = document.createElement('div');
    newGroup.className = 'cert-group';
    newGroup.innerHTML = `
        <input type="text" name="demo_download_name[]" placeholder="Certificate Name" required />
        <input type="file" name="demo_file[]" accept=".pdf,.doc,.docx" required />
        <button type="button" onclick="this.parentNode.remove()">Remove</button>
    `;
    wrapper.appendChild(newGroup);
}
</script>
<? include("footer.php")?>
</body>
</html>