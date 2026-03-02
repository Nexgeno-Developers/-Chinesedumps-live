<? include("includes/header.php");?>

<?php $this_page = "demo" ?>
<?php
ob_start();
session_start();
include("includes/config/classDbConnection.php");

include("includes/common/classes/classmain.php");
include("includes/common/classes/classProductMain.php");
include("includes/common/classes/classcart.php");

include("includes/shoppingcart.php");

$objDBcon   =   new classDbConnection; 
$objMain	=	new classMain($objDBcon);
$objPro		=	new classProduct($objDBcon);
$objCart	=	new classCart($objDBcon);

$getPage	=	$objMain->getContent(26);

$quer	=	$objDBcon->Sql_Query_Exec('*','website','');
$exec	=	mysql_fetch_array($quer);

$copyright	=	$exec['copyright'];

$firstlink	=	" ".$getPage[1];

$cert_id = isset($_GET['cert_id']) ? intval($_GET['cert_id']) : 0;
$demoName = isset($_GET['name']) ? htmlspecialchars($_GET['name']) : '';

$demoFileHtml = '';

if ($cert_id > 0) {
    $result = mysql_query("SELECT demo_data FROM tbl_cert WHERE cert_id = '$cert_id'");
    $row = mysql_fetch_assoc($result);

    $demoList = json_decode($row['demo_data'], true);

    if (!empty($demoList)) {
        foreach ($demoList as $demo) {
            $file = htmlspecialchars($demo['file']);
            $name = htmlspecialchars($demo['name']);

            // Optional: Clean up file path if needed
            $filenameOnly = basename($file);

            // The link will still pass file name only
            $downloadLink = "download_demo.php?file=" . urlencode($filenameOnly);

            // $demoFileHtml .= '<div class="groupbox">
            //     <a class="submit_btn download-demo-btn" href="' . $downloadLink . '" download="' . $filenameOnly . '">' . $name . '</a>
            // </div>';
            
            $demoFileHtml .= '<div class="groupbox">
                <a href="#" class="submit_btn download-demo-btn"
                   data-file="' . $filenameOnly . '"
                   data-name="' . $name . '"
                   data-cert-id="' . $cert_id . '">' . $name . ' <i class="fa fa-download"></i></a>
            </div>';

            // $demoFileHtml .= '<div class="groupbox">
            //     <a href="#" class="submit_btn download-demo-btn"
            //       data-file="' . $filenameOnly . '"
            //       data-cert-id="' . $cert_id . '">' . $name . ' <i class="fa fa-download"></i></a>
            // </div>';
        }
    }
}

include("html/demo.html");
?>

<? include("includes/footer.php");?>