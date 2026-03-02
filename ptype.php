<?php
ob_start();
session_start();

// Capture the current URL
// $currentUrl = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
// $_SESSION['lasturl'] = $currentUrl;
// var_dump($currentUrl);

function getYoutubeId($url)
{
    $url = str_replace('\/', '/', $url);
    if (preg_match('~youtu\.be/([^?&]+)~', $url, $m)) return $m[1];
    if (preg_match('~v=([^&]+)~', $url, $m)) return $m[1];
    if (preg_match('~/embed/([^?&]+)~', $url, $m)) return $m[1];
    return null;
}

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

function getDailymotionId($url)
{
    if (preg_match('~dailymotion\.com/video/([^_?&/]+)~i', $url, $m)) return $m[1];
    if (preg_match('~dai\.ly/([^?&/]+)~i', $url, $m)) return $m[1];
    return null;
}

function getRumbleEmbedUrl($url)
{
    if (preg_match('~rumble\.com/embed/([a-z0-9]+)~i', $url, $m)) {
        return "https://rumble.com/embed/" . strtolower($m[1]);
    }
    if (preg_match('~rumble\.com/(v[a-z0-9]+)(?:[-./?]|$)~i', $url, $m)) {
        return "https://rumble.com/embed/" . strtolower($m[1]);
    }
    return null;
}

function getOdyseeEmbedUrl($url)
{
    if (preg_match('~odysee\.com/\$/embed/([^?&#]+(?:/[^?&#]+)?)~i', $url, $m)) {
        return 'https://odysee.com/$/embed/' . $m[1];
    }

    $parts = parse_url($url);
    if (!isset($parts['host']) || stripos($parts['host'], 'odysee.com') === false) {
        return null;
    }

    $path = isset($parts['path']) ? ltrim($parts['path'], '/') : '';
    if ($path === '') {
        return null;
    }

    return 'https://odysee.com/$/embed/' . $path;
}

function getVideoEmbedUrl($service, $url)
{
    $service = normalizeVideoService($service);
    $url = trim((string)$url);
    if ($url === '') {
        return null;
    }

    if ($service === 'youtube') {
        $id = getYoutubeId($url);
        return $id ? "https://www.youtube.com/embed/$id" : null;
    }
    if ($service === 'rumble') {
        return getRumbleEmbedUrl($url);
    }
    if ($service === 'dailymotion') {
        $id = getDailymotionId($url);
        return $id ? "https://www.dailymotion.com/embed/video/$id" : null;
    }
    if ($service === 'odysee') {
        return getOdyseeEmbedUrl($url);
    }
    return null;
}

include ('includes/config/classDbConnection.php');
include ('includes/common/classes/classmain.php');
include ('includes/common/classes/classProductMain.php');
include ('includes/common/classes/classcart.php');
include ('includes/common/classes/classUser.php');
include ('functions.php');

include ('includes/common/classes/classEmailvalidate.php');

// -------------------------------------------------//
$objDBcon = new classDbConnection;
$objMain = new classMain($objDBcon);
$objPro = new classProduct($objDBcon);
$objCart = new classCart($objDBcon);
$objUser = new classUser($objDBcon);
$objPricess = new classPrices();

$emailValidator = new classEmailvalidate();

// ------------------------------------------------//

$getPage = $objMain->getContent(1);

$guest = session_id();

$isLoggedIn = isset($_SESSION['uid']);

$_SESSION['guestId'] = $guest;

$examURL = $_GET['pcode'];

$exam = $objPro->getProductId($examURL);

$examID = $exam['exam_id'];

// // Fetch one row:
// $result = mysql_query(
//   "SELECT demo_images 
//      FROM tbl_exam 
//     WHERE exam_id = '" . $examID . "'"
// );
// $row = mysql_fetch_assoc($result);

// // Decode JSON into PHP array
// $demo_images = json_decode($row['demo_images'], true) ?: [];

// Fetch one row:
$result = mysql_query(
  "SELECT youtube_links 
     FROM tbl_exam 
    WHERE exam_id = '" . $examID . "'"
);
$row = mysql_fetch_assoc($result);

// Decode JSON into PHP array (new format + old youtube-only format)
$video_links = normalizeVideoLinksFromStorage($row['youtube_links']);

$price = $objPricess->getProductPrice($examID, 'p', '0');
$eprice = $objPricess->getProductPrice($examID, 'sp', '0');
$bprice = $objPricess->getProductPrice($examID, 'both', '0');
$examName = $exam['exam_fullname'];

if ($examName == '') {
	header('location:index.html');
}

$examCode = $exam['exam_name'];  // Exam Code
$freeDumpPdf = isset($exam['free_dump_pdf']) ? $exam['free_dump_pdf'] : '';
$freeDumpAbsolutePath = __DIR__ . '/uploads/free_dumps/' . $freeDumpPdf;
$freeDumpWebPath = 'uploads/free_dumps/' . $freeDumpPdf;
$freeDumpError = '';
$freeDumpAvailable = ($freeDumpPdf !== '');

if (!function_exists('getUserDetails')) {
	function getUserDetails($userID) {
		$userID = intval($userID);
		$q = mysql_query("SELECT user_email, user_fname, user_lname, user_phone FROM tbl_user WHERE user_id = '{$userID}'");
		if ($q && mysql_num_rows($q) > 0) {
			return mysql_fetch_assoc($q);
		}
		return false;
	}
}

if (isset($_POST['download_free_dump'])) {
	if (!$isLoggedIn) {
		$_SESSION['lasturl'] = $_SERVER['REQUEST_URI'];
		header("Location: " . BASE_URL . "login.php");
		exit;
	}
	if ($freeDumpPdf === '' || !file_exists($freeDumpAbsolutePath)) {
		$freeDumpError = "Free dump file is not available right now.";
	} else {
		$userId = intval($_SESSION['uid']);
		$userData = getUserDetails($userId);
		$userFirst = isset($userData['user_fname']) ? $userData['user_fname'] : '';
		$userLast = isset($userData['user_lname']) ? $userData['user_lname'] : '';
		$userEmail = isset($userData['user_email']) ? $userData['user_email'] : '';
		$userPhone = isset($userData['user_phone']) ? $userData['user_phone'] : '';
		$userName = trim($userFirst . ' ' . $userLast);
		$userIp = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
		$timestamp = date('Y-m-d H:i:s');
		$baseUrl = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
		$currentUrl = $baseUrl . $_SERVER['REQUEST_URI'];
		$pdfUrl = $baseUrl . '/' . $freeDumpWebPath;

// 		$adminEmail = "webdeveloper@nexgeno.in";
		$adminEmail = "sales@chinesedumps.com";
		$subject = "Free dump download: {$examName} ({$examCode})";

		$message = "
        <html>
        <head><title>Free Dump Download Notification</title></head>
        <body>
            <p><strong>User Details:</strong></p>
            <ul>
                <li><strong>Name:</strong> " . htmlspecialchars($userName) . "</li>
                <li><strong>Email:</strong> " . htmlspecialchars($userEmail) . "</li>" .
                (!empty($userPhone) ? "<li><strong>Phone:</strong> " . htmlspecialchars($userPhone) . "</li>" : '') . "
                <li><strong>IP Address:</strong> " . htmlspecialchars($userIp) . "</li>
            </ul>

            <p><strong>Download Details:</strong></p>
            <ul>
                <li><strong>Exam:</strong> " . htmlspecialchars($examName) . " (" . htmlspecialchars($examCode) . ")</li>
                <li><strong>File:</strong> " . htmlspecialchars($freeDumpPdf) . "</li>
                <li><strong>Timestamp:</strong> " . htmlspecialchars($timestamp) . "</li>
                <li><strong>Page URL:</strong> <a href=\"" . htmlspecialchars($currentUrl) . "\" target=\"_blank\">" . htmlspecialchars($currentUrl) . "</a></li>
            </ul>
            <p><strong>View PDF:</strong> <a href=\"" . htmlspecialchars($pdfUrl) . "\" target=\"_blank\">" . htmlspecialchars($freeDumpPdf) . "</a></p>
        </body>
        </html>";

		$headers = "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
		$headers .= "From: noreply@chinesedumps.com\r\n";

		// Try primary mailer; fallback to PHP mail
		try {
			if (function_exists('sendEmail')) {
				sendEmail($adminEmail, $subject, $message, $headers);
			} else {
				@mail($adminEmail, $subject, $message, $headers);
			}
		} catch (Exception $e) {
			@mail($adminEmail, $subject, $message, $headers);
		}

		if (file_exists($freeDumpAbsolutePath)) {
			header('Content-Type: application/pdf');
			header('Content-Disposition: attachment; filename="' . basename($freeDumpPdf) . '"');
			header('Content-Length: ' . filesize($freeDumpAbsolutePath));
			readfile($freeDumpAbsolutePath);
			exit;
		} else {
			$freeDumpError = "Unable to locate the free dump file.";
		}
	}
}

$pCode = $examCode;

$venID = $exam['ven_id'];

$vendor_result = $objPro->get_vendorName($venID);

$certID = $exam['cert_id'];

$cert_result = $objPro->get_certName($certID);

$venName = $vendor_result['ven_name'];
$venSlug = $vendor_result['ven_url'];

$certName = $cert_result[0];

$spam[0] = $examID;

$spam[1] = $examCode;

$spam[4] = $venName;

$demoAvailable = $exam['exam_demo'];

$url = $base_path . 'braindumps-' . str_replace(' ', '-', $vendor_result['ven_url']) . '.htm';

$getPage[0] = $exam['exam_title'];

$getPage[1] = $exam['exam_fullname'];

$getPage[2] = $exam['exam_descr'];

$getPage[9] = $exam['exam_tests'];

$getPage[10] = $exam['exam_status'];

// $getPage[2]	=	$objMain->htmlwrap($exam['exam_descr']);

$getPage[4] = $exam['exam_keywords'];

$getPage[5] = $exam['exam_desc_seo'];

$getglobart = mysql_fetch_array(mysql_query("SELECT * from tbl_globlecontents where page_id='3'"));

if ($getPage[0] == '') {
	$getPage000 = str_replace('[ExamCode]', $pCode, $getglobart['page_title']);

	$getPage00 = str_replace('[ExamName]', $getPage[1], $getPage000);

	$getPage0 = str_replace('[CertName]', $certName, $getPage00);

	$getPage[0] = str_replace('[VendorName]', $venName, $getPage0);
}

if ($getPage[1] == '') {
	$getPage111 = str_replace('[ExamCode]', $pCode, $getglobart['content_title']);

	$getPage11 = str_replace('[ExamName]', $getPage[1], $getPage111);

	$getPage1 = str_replace('[CertName]', $certName, $getPage11);

	$getPage[1] = str_replace('[VendorName]', $venName, $getPage1);
}

if ($getPage[5] == '') {
	$getPage222 = str_replace('[ExamCode]', $pCode, $getglobart['meta_descx']);

	$getPage22 = str_replace('[ExamName]', $getPage[1], $getPage222);

	$getPage2 = str_replace('[CertName]', $certName, $getPage22);

	$getPage[5] = str_replace('[VendorName]', $venName, $getPage2);
}

if ($getPage[4] == '') {
	$getPage333 = str_replace('[ExamCode]', $pCode, $getglobart['meta_keywords']);

	$getPage33 = str_replace('[ExamName]', $getPage[1], $getPage333);

	$getPage3 = str_replace('[CertName]', $certName, $getPage33);

	$getPage[4] = str_replace('[VendorName]', $venName, $getPage3);
}

if ($getPage[2] == '' || strlen($getPage[2]) <= '15') {
	$getPage222 = str_replace('[ExamCode]', $pCode, $getglobart['page_contents']);

	$getPage22 = str_replace('[ExamName]', $getPage[1], $getPage222);

	$getPage2 = str_replace('[CertName]', $certName, $getPage22);

	$getPage[2] = str_replace('[VendorName]', $venName, $getPage2);

	$getPage777 = str_replace('[ExamCode]', $pCode, $getglobart['page_contents1']);

	$getPage77 = str_replace('[ExamName]', $getPage[1], $getPage777);

	$getPage7 = str_replace('[CertName]', $certName, $getPage77);

	$getPage[7] = str_replace('[VendorName]', $venName, $getPage7);

	$getPage888 = str_replace('[ExamCode]', $pCode, $getglobart['page_contents2']);

	$getPage88 = str_replace('[ExamName]', $getPage[1], $getPage888);

	$getPage8 = str_replace('[CertName]', $certName, $getPage88);

	$getPage[8] = str_replace('[VendorName]', $venName, $getPage8);
} else {
	//		$getPage[2]	.=	"<br />".str_replace("[TOKEN]",$pCode,$getglobart['page_contents']);
}

$firstlink = " <a href='" . $url . "' class='producttitletext' title='" . $venName . "'><strong>" . $venName . ' ></strong></a>';

//		$secondlink	=	" <a href='".$url2."' class='producttitletext' title='".$examsultvendor['productName']."'><strong>".$examsultvendor['productName']." ></strong></a>";

$thirdlink = ' ' . $examCode . '';

$_SESSION['globlearticle'] = $examCode;

if ($exam['exam_upddate'] == '0000-00-00') {
	$latestdate = $exam['exam_date'];
} else {
	$latestdate = $exam['exam_upddate'];
}

$new_date = date('M j, Y', strtotime($latestdate));

$getrelexam = "SELECT * from tbl_exam where cert_id='" . $exam['cert_id'] . "' and exam_name!='" . $examCode . "' and exam_status='1'";

$exerel = mysql_query($getrelexam);

$my_exerel = mysql_num_rows($exerel);

$getrelcert = "SELECT * from tbl_cert where ven_id='" . $exam['ven_id'] . "' and cert_id !='" . $exam['cert_id'] . "' and cert_status='1'";

$certrel = mysql_query($getrelcert);

$my_certrel = mysql_num_rows($certrel);

// ------------------------Demo Download--------------------------//

if (isset($_POST['email'])) {
	$usrdm[1] = $_POST['ecode'];

	$usrdm[2] = $_POST['email'];

	$usrdm[3] = $demoAvailable;

	$usrdm[4] = $_POST['epcd'];

	$objUser->add_demo_email($usrdm);
}

// ------------------------Demo Download--------------------------//

if (isset($_POST['request_email'])) {
    function sanitizeInput($data) {
        return htmlspecialchars(strip_tags(trim($data)));
    }

    $errors = [];
    

    $code = sanitizeInput($_POST['request_ecode']);
    $email = sanitizeInput($_POST['request_email']);


    // if (empty($code) || preg_match('/[^a-zA-Z0-9\s]/', $code)) {
    //     $errors[] = "Invalid Exam Code. No special characters allowed.";
    // }
    

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    
    if (!isset($_POST['g-recaptcha-response']) || empty($_POST['g-recaptcha-response'])) {
        $errors[] = "Please fill reCAPTCHA.";
    }
    
    $response = $emailValidator->email_validate_api($email);
    
    $data = json_decode($response, true);

    if ($data && isset($data['result'])) {
        if($data['result'] == 'undeliverable'){
            $errors[] = "Email Address is not valid please try again";
        } 
    } else {
        $errors[] = "Somthing Went Wrong";
    }
    
    if (!empty($errors) && count($errors) > 0) {
        $_SESSION['errors'] = $errors;
    }
    
    if (empty($errors) && count($errors) == 0) {
    	$exmreq[1] = $_POST['request_ecode'];
    
    	$exmreq[2] = $_POST['request_email'];
    
    	$objUser->examRequest_email($exmreq);
    } else {
        header('Location: ' . BASE_URL);
    }
}

// /////////////////////////////////Check Engine Entry///////////////////////////////////

$r = mysql_query("select * from tbl_package_engine where package_id='" . $examID . "'");

if (mysql_num_rows($r) != '0') {
} else {
	$pth = $venName . '/' . $pCode . 'Sim.zip';

	mysql_query("insert into tbl_package_engine (package_id, version, type, path, os, price) values ('" . $examID . "','6.0','Simulator','" . $pth . "','Win','74.99')" . '');
}

$examURL = $exam['exam_url'];

$examports = mysql_query("select * from report_card where r_exam='" . $examURL . "'");

if (mysql_num_rows($examports) != 0) {
	$examportimg = 1;
} else {
	$examportimg = 0;
}

?>
	<? include ('includes/header.php'); ?>
		<script language="JavaScript" type="text/javascript" src="js/addcart3.js"></script>
		<style>
		.line_height_36 {
    line-height: 36px !important;
}
.carts_button {
    padding: 5px 15px !important;
    font-size: 14px;
}.carts_button img {
    width: 15px !important;
}
		.course-description p {
    font-size: 16px;
    font-weight: normal;
}

			.heading2 {
			margin: 0px;
			padding: 20px 0px 10px 0px;
			font-size: 14px;
			font-weight: bold;
			font-family: Arial, Helvetica, sans-serif
		}
		
		.lightdemogray2 {
			height: 310px;
			float: left;
			border: none;
			font-size: 14px;
			background: #FFF;
			padding: 10px;
		}
		
		.lightdemogray2 ul {
			padding: 0px;
			font-size: 14px;
			list-style: none;
		}
		
		.lightdemogray2 ul li {
			padding: 8px 10px 8px 30px;
			background: url(../images/bult.png) left center no-repeat;
			font-size: 12px;
		}
		
		.lightdemogray2 .download {
			padding: 15px;
			border-top: #D9D9D9 solid 1px;
		}
		
		.error-message {
			padding: 2px 0px;
			color: #FF0000;
		}
		
		.AutoDiv {
			float: left;
			width: auto;
			height: auto;
		}
		
		.MarginTop10 {
			margin-top: 10px;
		}
		
		.white_content2 {
			display: none;
			position: fixed;
			top: 15%;
			left: 55%;
			margin-left: -340px;
			width: 620px;
			padding: 0px;
			border: 1px solid #ccc;
			background-color: white;
			z-index: 1002;
			overflow: auto;
			font-size: 14px;
			-moz-border-radius: 5px;
			-webkit-border-radius: 5px;
			-khtml-border-radius: 5px;
			border-radius: 5px;
		}
		
		.white_content2 .heading2 {
			padding: 20px;
			color: #000;
			font-size: 26px;
			margin: 0px;
			background: #EEEEEE;
		}
		
		.white_content2 span {
			color: #606060;
			font-size: 12px;
		}
		
		.lightdemogray2 {
			height: 310px;
			float: left;
			border: none;
			font-size: 14px;
			background: #FFF;
			padding: 10px;
		}
		
		.lightdemogray2 ul {
			padding: 0px;
			font-size: 14px;
			list-style: none;
		}
		
		.lightdemogray2 ul li {
			padding: 7px 10px 5px 31px;
			background: url(images/bullet.png) left center no-repeat;
		}
		
		.lightdemogray2 .download {
			padding: 15px;
			border-top: #D9D9D9 solid 1px;
		}
		
		.SideBar .download {
			float: left;
			width: 226px;
			height: 135px;
			padding: 10px;
			color: #FFFFFF;
			background: url(../images/pattern_03.jpg) repeat;
		}
		
		.download .heading {
			padding: 10px 0px;
			font-size: 18px;
		}
		
		.download p {
			padding: 0px 0px;
			color: #FFFFFF;
		}
		
		.black_overlay {
			display: none;
			position: fixed;
			top: 0%;
			left: 0%;
			width: 100%;
			height: 100%;
			background-color: black;
			z-index: 1001;
			-moz-opacity: 0.4;
			opacity: .40;
			filter: alpha(opacity=80);
		}
		
		.textbox {
			margin: 3px 0px;
			padding: 5px;
			width: 230px;
			color: #666666;
			border: #D6D6D6 solid 1px;
			height: 29px;
			background: url(images/txtfld_bg.jpg) repeat-x;
		}
		
		h2.exam-heading {
			font-weight: bold;
			font-size: 32px;
			text-align: left !important;
			color: #333;
		}
		
		div#labSlider {
			margin-bottom: 45px;
		}
		
		.sideimage .exam-pkg-image01 {
			float: left;
			width: 40% !important;
			margin-top: 30px;
		}
		
		.exam-pkg-list {
			width: 100%;
			float: left;
			margin-top: 0px;
		}
		
		#writtenSlider {
			position: relative;
			margin: 40px 0px;
			/* box-shadow: 0px 0px 1px 0px; */
			padding: 10px;
			border: 1px dashed rgba(204, 204, 204, 0.6);
		}
		
		.prd_ctn ul li {
			font-size: 18px;
			line-height: 27px;
		}
		
		.prd_ctn ul li i {
			padding-right: 10px;
			font-size: 14px;
		}
		
		.prd_ctn h4 {
			font-size: 24px;
			padding-bottom: 10px;
		}
		
		.popular-list-heading {
			width: 270px;
			background: #f3f2ed;
			font-size:20px;
			color: #363636;
			padding: 20px 15px;
			-webkit-border-radius: 5px;
			-moz-border-radius: 5px;
			border-radius: 5px;
			-webkit-box-shadow: 5px 5px 0px #cdccc4;
			-moz-box-shadow: 5px 5px 0px #cdccc4;
			box-shadow: 5px 5px 0px #cdccc4;
		}
		
		.popular-list-box.sidetext {
			float: none;
			margin-bottom: 15px important;
		}
		
		.popular-heading {
			font: 17px/ 22px 'vagroundedstd-light' !important;
			color: #151515;
			margin-bottom: 15px !important;
		}
		
		.pro_rht_img {
			margin-top: 9%;
		}
		
		.pro_rht_img img {
			margin-left: auto;
			/* margin-right: auto; */
			display: block;
			/* border-bottom: 1px solid #ccc; */
			width: 83%;
			margin-bottom: 20px;
		}
		
		.col-md-12.img_alp {
			margin-top: 4%;
		}
		
		
		
		@media(max-width:767px) {
			h2.exam-heading {
				font-weight: bold;
				font-size: 18px;
				text-align: left !important;
				color: #333;
			}
			.exam-pkg-list {
				width: 100%;
				float: left;
				margin-top: 20px;
			}
			.exam-pkg-list {
				width: 100%;
				float: left;
				margin-top: 10px;
			}
		
			.package-box {
				width: 100%;
				float: none;
				text-align: left;
			}
			.pkg-name-box {
				display: block;
			}
			.pdf-image {
				background: url(../images/pdf-pkg-ico.png) center no-repeat;
				background-size: 40%;
				width: 35px;
				height: 35px;
				margin-left: 0px;
				margin-top: 0px;
			}
			.pkg-name-box {
				width: 35px;
				height: 35px;
				float: left;
				background: #505050;
				-webkit-border-radius: 50px;
				-moz-border-radius: 50px;
				border-radius: 50px;
				color: #fff;
				text-align: center;
				position: relative;
				margin: 0 10px 20px 0;
			}
			.vendor-image {
				background: url(../images/vender-pkg-ico.png) center no-repeat;
				background-size: 40%;
				width: 35px;
				height: 35px;
				margin-left: 0px;
				margin-top: 0px;
			}
			.unlimite-image {
				background-size: 40% !important;
				width: 35px;
				height: 35px;
				margin-left: 0px;
				margin-top: 0px;
				background: url(../images/unlimte-pkg-ico.png) center no-repeat;
			}
			li.main_cert .package-box {
				width: 280px !important;
				font-size: 14px !important;
			}
			.pkg-name.pro_cet {
				padding-top: 12px;
				font-size: 14px;
			}
			.pro_cet_1 .black-button {
				height: 25px !important;
				margin-top: 4px;
				font-size: 11px !important;
			}
			.buy-package .black-button {
				width: 65px;
				min-width: 65px;
			}
			li.main_cert .package-box {
				width: 100% !important;
				font-size: 14px !important;
				float: left;
			}
			li.main_cert .package-price {
				font-size: 24px;
				float: left;
				font-size: 18px;
			}
			.max-width {
				max-width: 1210px;
				padding: 0 10px;
				margin: 0 auto;
			}
			.pro_cet_1 {
				float: left !important;
				padding-top: 6px;
			}
			
			.package-box {
				width: 100% !important;
			}
			.prd_ctn ul li {
				font-size: 16px;
				line-height: 27px;
			}
			.prd_ctn ul li i {
				padding-right: 10px;
				font-size: 12px;
			}
			.col-md-12.img_alp {
				margin-top: 10%;
			}
			.pro_rht_img img {
				margin-left: auto;
				/* margin-right: auto; */
				display: block;
				/* border-bottom: 1px solid #ccc; */
				width: 100%;
				margin-bottom: 0px;
			}
			.exam-group-box {
				width: 100%;
				background: #FFF;
				padding: 0px 0px 0px 0px;
				-webkit-box-shadow: inset 0px -5px 5px -5px rgba(0, 0, 0, 0, );
				-moz-box-shadow: inset 0px -5px 5px -5px rgba(0, 0, 0, 0, );
				box-shadow: inset 0px -5px 5px -5px rgba(0, 0, 0, 0, );
			}
			.center-heading span {
				padding: 8px 0px;
				background: #f3f2ed;
				color: #45A4F2;
				line-height: 25px;
				font-size: 20px;
			}
			.list-box li:first-child {
				width: 60%;
			}
			.list-elements > li {
				height: 79px;
				overflow: hidden;
			}
		}
		
		.exam-group-box input[type="submit"] {
			width: 100%;
		}
		
		.ed_morden_search_form form {
			padding: 15px 0px 10px;
		}
		
		.ti_eroll_heading {
			margin-top: 12px;
		}
		
		.exam-pkg-list .group > li {
			margin-bottom: 0px;}
			
		@media(max-width:767px)
		{
		    	ul.list-box02 ul.list-box.list-elements li:nth-child(1) {
    width: 70% !important;
    height: auto !important;
}
ul.list-box02 ul.list-box.list-elements li p {
    font-size: 12px !important;
}
ul.list-box02 ul.list-box.list-elements li:nth-child(2) {
    width: 30% !important;
}
		}
		
		.bootcampLab p {
    font-size: 16px;
}

.bootcampLab h1 span {
    font-size: 20px !important;
    font-weight: 600 !important;
}
.bootcampLab h1 {
    font-size: 20px !important;
    font-weight: 600 !important;
}
.bootcampLab h2 {
    font-size: 20px !important;
    font-weight: 600 !important;
}
.bootcampLab h2 span {
    font-size: 20px !important;
    font-weight: 600 !important;
}

ul.courses_pass_img.simply-scroll-list li {
    width: 350px !important;!I;!;
}

ul.courses_pass_img.simply-scroll-list li img {
    width: 350px;
}
.passing_dumps .simply-scroll-clip {
    height: 204px !important;
}

p.exam_reallab_desc {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    height: 74px !important;
    margin-bottom: 20px;
}

.flash-text {
    color: #ff0000;
    font-weight: bold;
    animation: flash 1s infinite;
}

@keyframes flash {
    0%   { opacity: 1; }
    50%  { opacity: 0; }
    100% { opacity: 1; }
}

.simply-scroll {
    margin-bottom: 0em !important;
}

		</style>
		<div id="main_body">
			<?php if ($getPage[10] == '1') { ?>
				<!--Content Start -->
				<div class="content-box">
					<div class="certification-box  certification-box03">
						<div class="container">
							<div id="destination" class="blutext" style="padding-left:5px;"> <a href="<?php echo $websiteURL; ?>" class="blutext">Home</a> >
								<a href="<?php echo str_replace(' ', '- ', $venSlug); ?>-certification-dumps.html" class="blutext">
									<?php echo $venName; ?>
								</a> >
								<?php

								$len = count($cert_result);

								$i = 0;

								foreach ($cert_result as $k => $v) {
									$page = strtolower($v);

									$page1 = str_replace(' ', '-', $v);

									$page2 = str_replace(':', '', $page1);

									$url = $websiteURL . '' . $page2 . '-cert.htm';

									if ($i == ($len - 1)) {
										echo "<a href=\"$url\" class=\"blutext\">$v</a> ";

										$certName = $v;
									} else {
										echo "<a href=\"$url\" class=\"blutext\">$v</a>  | ";

										$certName = $v;
									}

									$i++;
								}

								?> >
									<?php echo $pCode; ?>
							</div>
							<?php include ('includes/unlimitedpackage.php'); ?>
						</div>
					</div>
					<div class="exam-group-box">
						<div class="">
							<div class="group">
								<div class="courses_main_boxex1 paddtop60 paddbottom60 ">
								    <div class="container">
								        <div class="row">
								    
									<!-- #labSlider -->
									<?php
									$uri = explode('-', $_SERVER['REQUEST_URI']);
                                    //$uri[0] == '/CISSP.htm' ||
									if ($uri[1] == 'Bootcamp.htm' || $uri[2] == 'Lab.htm' || $uri[1] == 'Lab.htm' || $uri[5] == 'lab.htm' || $uri[6] == 'lab.htm' || $uri[6] == 'workbook.htm' || $uri[5] == 'workbook.htm' || $uri[5] == 'bootcamp.htm' || $uri[6] == 'bootcamp.htm') {
										echo '<style>
.courses_main_boxex1.paddtop60 {
    background: #3C85BA0D;
    display: inline-block;
    width: 100%;
}
.workbook_listing_box {
    width: 100%;
}
.workbookLab div {
    background: #3C85BA1A !important;
    margin-bottom: 7px;
    padding: 3px 8px;
    border-radius: 5px;
}
.bootcampLab div {
        background: #3C85BA1A !important;
    margin-bottom: 7px;
    padding: 3px 8px;
    border-radius: 5px;
}
.bootcampLab span {
    background: transparent !important;
    font-weight: 500 !important;
    font-size: 12px;
    color: #000;
}
.basicLab div
{
     background: #3C85BA1A !important;
    margin-bottom: 7px;
    padding: 3px 8px;
    border-radius: 5px;
}

.basicLab span {
    background: transparent !important;
    font-weight: 500 !important;
    font-size: 12px;
    color: #000;
}
.courses_headings
{
    text-align: center;
        padding-bottom: 10px;
}
.popular-list-box.sidetext {
    width: 100% !important;
    text-align: center;
    padding-bottom: 20px;
}

.popular-list-box.sidetext .popular-list-heading {
    width: 100%;
}
.basicLab p, .basicLab div, .basicLab p, .basicLab span {
    font-size: 16px !important;
}
.pkg-name.pro_cet {
    font-size: 18px;
    font-weight: 600;
    color: #363636;
}
li.main_cert .package-price {
    font-size: 18px;
    float: left;
    font-weight: 600;
}
.basicLab span {
    background: transparent !important;
    font-weight: 500 !important;
    font-size: 12px !important;
    color: #000;
}

.workbookLab span {
    background: transparent !important;
    font-weight: 500 !important;
    font-size: 12px;
    color: #000;
}

.bootcampLab span {
    background: transparent !important;
    font-weight: 500 !important;
    font-size: 12px;
    color: #000;
}
.package-box {
    width: 100%;
}

</style>';
									} else {
										?>
                                        <div class="col-md-4">
                                            <div class="course_image_bg">
                                            <?php
                                            // Vendor image path
                                            $vendorImgMain = isset($vendor_result['default_image']) ? trim($vendor_result['default_image']) : '';
                                            $vendorIsUrl = is_absolute_url($vendorImgMain);
                                        
                                            // Exam image path
                                            $examImagePath = '/images/slider/' . $examID . '/' . $exam['course_image'];
                                        
                                            // Build absolute paths
                                            $examDiskPath   = base_path($examImagePath);
                                            $vendorDiskPath = $vendorIsUrl ? '' : base_path($vendorImgMain);
                                        
                                            // Show exam image if exists
                                            if (!empty($exam['course_image']) && file_exists($examDiskPath)) {
                                                echo '<img src="' . asset_url($examImagePath) . '" style="width:100%;">';
                                            }
                                            // Else show vendor image if exists
                                            elseif (!empty($vendorImgMain) && ($vendorIsUrl || file_exists($vendorDiskPath))) {
                                                echo '<img src="' . asset_url($vendorImgMain) . '" style="width:100%;">';
                                            }
                                            // Else show placeholder
                                            else {
                                                echo '<div class="exam-pkg-image01">&nbsp;</div>';
                                            }
                                            ?>
                                            </div>
                                        </div>
										
										<?php } ?>
											<div class="col-md-8 workbook_listing_box">
											<div class="exam-pkg-list group">
												<?php
												$strSql11 = "SELECT id FROM tbl_package_engine WHERE package_id='" . $examID . "'";

												$examsult11 = mysql_query($strSql11) or die(mysql_error());

												if (mysql_num_rows($examsult11) > 0) {
													?>
													<ul class="group">
													   
														
														<?php if ($exam['QA'] == '0') { ?>
															<li>
																<div style="width:95%">
																	<span style="padding-bottom:12px;">Exam Request</span>
																	<form action="" name="demoForm2" method="post" onsubmit="return demoVerify3();"> Exam Code:
																		<br />
																		<input type="text" class="textbox" name="request_ecode" id="request_ecode" value="<?php echo $pCode; ?>" />
																		<br /> Email:
																		<br />
																		<input type="text" class="textbox" name="request_email" id="request_email" />
																		<br />
																		<br />
																		<input type="submit" value="Send Request" class="black-button" /> </form>
																</div>
																<script language="javascript">
																function demoVerify3() {
																	regExp = /^[A-Za-z_0-9\.\-]+@[A-Za-z0-9\.\-]+\.[A-Za-z]{2,}$/;
																	email = document.forms['demoForm2'].elements['request_email'].value;
																	if(email == '' || !regExp.test(email)) {
																		alert('Enter Email');
																		document.getElementById('emailErr2').style.display = 'block';
																		document.forms['demoForm2'].elements['request_email'].focus();
																		return false;
																	}
																	document.getElementById('emailErr2').style.display = 'none';
																}
																</script>
																<?php } else { ?>
															</li>
															<li>
																<div class="courses_headings"><strong><?php // echo $exam["exam_name"]; ?> <?php echo $exam['exam_fullname']; ?> 



                                <?php
								$uri = explode('-', $_SERVER['REQUEST_URI']);
                                //$uri[0] == '/CISSP.htm' ||
								if ($uri[1] == 'Bootcamp.htm' || $uri[2] == 'Lab.htm' || $uri[1] == 'Lab.htm' || $uri[5] == 'lab.htm' || $uri[6] == 'lab.htm' || $uri[6] == 'workbook.htm' || $uri[5] == 'workbook.htm' || $uri[6] == 'bootcamp.htm' || $uri[5] == 'bootcamp.htm') {
								} else {
									?>  Exam Dumps & Study Guide	<?php } ?></strong> </div>
																<!--<div class="popular-heading">Last Updated <span style="color:red;"> <? php /* echo date('d-m-y', strtotime("-2 days")); */  ?></span></div>-->
																<div class="popular-list-box sidetext">
																	<div class="popular-list-heading">

																		<?php
																		$uri = explode('-', $_SERVER['REQUEST_URI']);
																		//$uri[0] == '/CISSP.htm' ||
																		if ($uri[1] == 'Bootcamp.htm' || $uri[2] == 'Lab.htm' || $uri[1] == 'Lab.htm' || $uri[5] == 'lab.htm' || $uri[6] == 'lab.htm' || $uri[6] == 'workbook.htm' || $uri[5] == 'workbook.htm' || $uri[6] == 'bootcamp.htm' || $uri[5] == 'bootcamp.htm') {
																		} else {
																			?> <strong>Exam Code: </strong>
																			<span class="green_clr"><?= $exam['QA'] ?></span>
																			
																				<br>
																				<?php } ?>
																					<?php
																					$get_course = mysql_fetch_assoc(mysql_query("SELECT * from tbl_course where name='" . $exam['exam_name'] . "'"));
																					if (!empty($get_course)) {
																						if ($get_course['status'] == 1) {
																							$status = '<span style="color:green">Stable</span>';
																						} else {
																							$status = '<span style="color:red">Unstable</span>';
																						}
																					}
																					?> <strong>Status: </strong>
																						<?= (!empty($status) ? $status : '<span style="color:green">Stable</span>'); ?> <br>
																						
																						
																																							    <?php
																		$uri = explode('-', $_SERVER['REQUEST_URI']);
																		//$uri[0] == '/CISSP.htm' ||
																		if ($uri[1] == 'Bootcamp.htm' || $uri[2] == 'Lab.htm' || $uri[1] == 'Lab.htm' || $uri[5] == 'lab.htm' || $uri[6] == 'lab.htm' || $uri[6] == 'workbook.htm' || $uri[5] == 'workbook.htm' || $uri[6] == 'bootcamp.htm' || $uri[5] == 'bootcamp.htm') {
																		} else {
																			?> <strong>Support days :</strong>
																			<span class="green_clr"> 15 days</span>
																			
																				<br>
																				<?php } ?>
																						
																						</div>
																</div>
															
																	<?php
																	$uri = explode('-', $_SERVER['REQUEST_URI']);
																	//$uri[0] == '/CISSP.htm' ||
																	if ($uri[1] == 'Bootcamp.htm' || $uri[2] == 'Lab.htm' || $uri[1] == 'Lab.htm' || $uri[5] == 'lab.htm' || $uri[6] == 'lab.htm' || $uri[6] == 'workbook.htm' || $uri[5] == 'workbook.htm' || $uri[6] == 'bootcamp.htm' || $uri[5] == 'bootcamp.htm') {
																	} else {
																		?>
																		<!--<div class="package-box">-->
																			
																		<!--	<div class="list-discription courses_discriptions">-->
																		<!--	 Interactive Testing Engine Tool that enables customize-->
																		<!--		<?php echo $Vendorname; ?>-->
																		<!--			<?php echo $exam['exam_name']; ?>-->
																		<!--				<?php echo $certName; ?> questions into Topics and Objectives. Real-->
																		<!--					<?php echo $exam['exam_name']; ?> Exam Questions with 100% Money back Guarantee. </div>-->
																		<!--</div>-->
																		<?php } ?>
															</li>
															<?php
															$uri = explode('-', $_SERVER['REQUEST_URI']);
															//$uri[0] == '/CISSP.htm' ||
															if ($uri[1] == 'Bootcamp.htm' || $uri[2] == 'Lab.htm' || $uri[1] == 'Lab.htm' || $uri[5] == 'lab.htm' || $uri[6] == 'lab.htm' || $uri[6] == 'workbook.htm' || $uri[5] == 'workbook.htm' || $uri[6] == 'bootcamp.htm' || $uri[5] == 'bootcamp.htm') {
																$pieces = explode('[BREAK]', $exam['exam_descr']);

																?>
									 
									 <!-- template 1 -->
									 <div class="row">
									     <div class="col-md-4 col-sm-4">
									         <li class="main_cert bootcamp_boxex_li pttop20">
																	<div class="package-box">
																		<div class="pkg-name pro_cet">
                                                                    <?php
																	// Original content
																	$fullname = $exam['exam_fullname'];

																	// Split the string into an array of words
																	$words = explode(' ', $fullname);

																	// Remove the last two words
																	array_splice($words, -2);

																	// Join the remaining words back into a string
																	$updatedFullname = implode(' ', $words);

																	// Output the result
																	echo $updatedFullname;

																	// Check if the current URL matches the specific one
																	if ($_SERVER['REQUEST_URI'] == '/CCDE-Lab.htm') {
																		// Output custom text for the specific URL
																		echo ' Written';
																	} else {
																		// Otherwise, output 'Real Lab Workbook'
																		echo ' Real Lab Workbook';
																	}

																	?> 
                                                                    
                                                                    
                                                                    
																		</div>
																	</div>
																	
																	<div class=" products_img_labs"> <img src="/images/slider/<?= $examID ?>/<?= $exam['course_image'] ?>" style="width: 100%;"> </div>
																	<div class="package-box">
																		
																		<div style="clear:both;"></div>
																		<div class="basicLab">
																			<?= $pieces[0]; ?>
																		</div>
																	</div>
																	
																	<div class="labs_blocks">
    																	<div class="labs_flex">
        																	 <div class="package-price">
        																	       Price: <span class="green_clr">$ <?= $price ?></span>
        																	</div>
        																	<div class="buy-package pro_cet_1">
        																		<!--<button class="carts_button" value="7" name="ptype" id="type_7" onclick='submitProexam(<?= $examID ?>,"7");'>Buy Now <img src="images/new-image/new_cart_icons.png"></button>-->
        																		<button class="carts_button" value="7" name="ptype" id="type_7" onclick="<?= $isLoggedIn ? "submitProexam($examID, '7');" : "redirectToLogin();" ?>">Buy Now <img src="images/new-image/new_cart_icons.png"></button>
        																	</div>
    																	</div>
																	</div>
																	
																</li>
									     </div>
									     
									     <div class="col-md-4 col-sm-4 padd000">
									         <li class="main_cert bootcamp_boxex_li">
									                  	<div class="package-box">
																		<div class="pkg-name pro_cet">
																			<?php
																			// Original content
																			$fullname = $exam['exam_fullname'];

																			// Split the string into an array of words
																			$words = explode(' ', $fullname);

																			// Remove the last two words
																			array_splice($words, -2);

																			// Join the remaining words back into a string
																			$updatedFullname = implode(' ', $words);

																			// Output the result
																			echo $updatedFullname;
																			// Check if the current URL matches the specific one
																			if ($_SERVER['REQUEST_URI'] == '/CCDE-Lab.htm') {
																				// Output custom text for the specific URL
																				echo ' Real Lab Workbook + Bootcamp';
																			} else {
																				// Otherwise, output 'Real Lab Workbook'
																				echo ' Real Lab Workbook + Racks + Bootcamp';
																			}
																			?>
                                                                    
																		</div>
																	
																	</div>
																	
																	
																	<div class="products_img_labs"> <img src="/images/slider/<?= $examID ?>/<?= $exam['course_image'] ?>" style="width: 100%;"> </div>
																	<div class="package-box">
																	
																		<div class="bootcampLab">
																			<?= $pieces[2]; ?>
																		</div>
																	</div>
																	
																		<div class="labs_blocks">
        																	<div class="labs_flex">
        																	    <div class="package-price">
        																	        Price: <span class="green_clr">$ <?= $bprice ?> </span>
            																	</div>
            																	<div class="buy-package pro_cet_1">
            																		<!--<button class="carts_button" value="9" name="ptype" id="type_9" onclick='submitProexam(<?= $examID ?>,"9");'>Buy Now <img src="images/new-image/new_cart_icons.png"></button>-->
            																		<button class="carts_button" value="9" name="ptype" id="type_9" onclick="<?= $isLoggedIn ? "submitProexam($examID, '9');" : "redirectToLogin();" ?>">Buy Now <img src="images/new-image/new_cart_icons.png"></button>
            																	</div>
        																	</div>
    																	</div>
    																	
    																	
																
																</li>
									     </div>
									     
									     <div class="col-md-4 col-sm-4">
									         <li class="main_cert bootcamp_boxex_li pttop20">
									             	<div class="package-box">
																		<div class="pkg-name pro_cet">
																			<?php
																			// Original content
																			$fullname = $exam['exam_fullname'];

																			// Split the string into an array of words
																			$words = explode(' ', $fullname);

																			// Remove the last two words
																			array_splice($words, -2);

																			// Join the remaining words back into a string
																			$updatedFullname = implode(' ', $words);

																			// Output the result
																			echo $updatedFullname;

																			// Check if the current URL matches the specific one
																			if ($_SERVER['REQUEST_URI'] == '/CCDE-Lab.htm') {
																				// Output custom text for the specific URL
																				echo ' Real Lab Workbook';
																			} else {
																				// Otherwise, output 'Real Lab Workbook'
																				echo ' Real Lab Workbook + Racks';
																			}
																			?>
                                                                    
																		</div>
																		</div>
																	<div class="products_img_labs"> <img src="/images/slider/<?= $examID ?>/<?= $exam['course_image'] ?>" style="width:100%;"> </div>
																	<div class="package-box">
																		
																		<div class="workbookLab">
																			<?= $pieces[1]; ?>
																		</div>
																	</div>
																	
																	
																		<div class="labs_blocks">
        																	<div class="labs_flex">
        																	    <div class="package-price">
        																	        Price: <span class="green_clr">$ <?= $eprice ?> </span>
            																	</div>
            																	<div class="buy-package pro_cet_1">
            																		<!--<button class="carts_button" value="8" name="ptype" id="type_8" onclick='submitProexam(<?= $examID ?>,"8");'>Buy Now <img src="images/new-image/new_cart_icons.png"></button>-->
            																		<button class="carts_button" value="8" name="ptype" id="type_8" onclick="<?= $isLoggedIn ? "submitProexam($examID, '8');" : "redirectToLogin();" ?>">Buy Now <img src="images/new-image/new_cart_icons.png"></button>
            																	</div>
        																	</div>
    																	</div>
    																	
																	
																</li>
									     </div>
									     
									 </div>
																
																
																
																<?php } else { ?>
															    	<!-- template 2 | Return Dumps -->
															    	
																    <?/*php if ($freeDumpAvailable) { */  ?>
                                                                        <li class="main_cert courses_listing_prc free_dump_row">
                                                                            <div class="row align_centers">
                                                                                <div class="col-md-1 col-xs-2"><img src="images/new-image/demo_pdf2.svg" alt="50 Real Demo Question"></div>
                                                                                <div class="col-md-6 col-xs-10">
                                                                                    <div class="pkg-name pro_cet">
                                                                                <span class="flash-text"><?php echo $exam['exam_fullname']; ?> Demo Question Download Free</span>
                                                                                </div>
                                                                                </div>
                                                                                <div class="col-md-5 col-xs-12">
                                                                                    <div class="flex_boxex">
                                                                                        <div class="green_clr price_sizes">$0</div>
                                                                                        <div class="buy-package pro_cet_1">
                                                                                            <form method="post" id="freeDumpForm">
                                                                                                <input type="hidden" name="download_free_dump" value="1">
                                                                                            </form>
                                                                                            <button class="carts_button" type="button" onclick="<?= $isLoggedIn ? "document.getElementById('freeDumpForm').submit();" : "redirectToLogin();" ?>">Buy Now <img src="images/new-image/new_cart_icons.png"></button>
                                                                                        </div>
                                                                                    </div>
                                                                                    <?php if ($freeDumpError !== '') { ?>
                                                                                        <div class="error-message" style="color:#ff4d4f; padding-top:6px;"><?php echo $freeDumpError; ?></div>
                                                                                    <?php } ?>
                                                                                </div>
                                                                            </div>
                                                                        </li>
                                                                        
															    	
																	<li class="main_cert courses_listing_prc">
																	    <div class="row align_centers">
																	        <div class="col-md-1 col-xs-2"><img src="images/new-image/secure_pdf.svg"></div>
																	        <div class="col-md-6 col-xs-10"><div class="pkg-name pro_cet">
																				<?php echo $exam['exam_fullname']; ?> Exam Secure PDC Package</div></div>
																	        <div class="col-md-5 col-xs-12">
																	            <div class="flex_boxex">
																	                	<div class="green_clr price_sizes">$<?= $price ?></div>
                																		<div class="buy-package pro_cet_1">
                																			<!--<button class="carts_button" value="p" name="ptype" id="type_p" onclick='submitProexam(<?= $examID ?>,"p");'> Buy Now <img src="images/new-image/new_cart_icons.png"></button>-->
                																			<button class="carts_button" value="p" name="ptype" id="type_p" onclick="<?= $isLoggedIn ? "submitProexam($examID, 'p');" : "redirectToLogin();" ?>"> Buy Now <img src="images/new-image/new_cart_icons.png"></button>
                																		</div>
																	            </div>
																	        </div>
																	    </div>
																	</li>
																	<li class="main_cert courses_listing_prc">
																	    
																	     <div class="row align_centers">
																	        <div class="col-md-1 col-xs-2"><img src="images/new-image/engine_package.svg"></div>
																	        <div class="col-md-6 col-xs-10">
																	            <div class="package-box">
																		     	<div class="pkg-name pro_cet">
																				<?php echo $exam['exam_fullname']; ?> Exam Engine Package</div>
																		        </div>
																	        </div>
																	         <div class="col-md-5 col-xs-12">
																	             <div class="flex_boxex">
																	                  	<div class="green_clr price_sizes">$
																			<?= $eprice ?>
																		</div>
																		<div class="buy-package pro_cet_1">
																			<!--<button class="carts_button" value="sp" name="ptype" id="type_sp" onclick='submitProexam(<?= $examID ?>,"sp");'> Buy Now <img src="images/new-image/new_cart_icons.png"></button>-->
																			<button class="carts_button" value="sp" name="ptype" id="type_sp" onclick="<?= $isLoggedIn ? "submitProexam($examID, 'sp');" : "redirectToLogin();" ?>"> Buy Now <img src="images/new-image/new_cart_icons.png"></button>
																		</div>
																	             </div>
																	         </div>
																	     </div>
																		
																		
																	
																	</li>
																	<li class="main_cert courses_listing_prc">
																	     <div class="row align_centers">
																	        <div class="col-md-1 col-xs-2"><img src="images/new-image/pdc_icons.svg"></div>
																	        <div class="col-md-6 col-xs-10">
																	            	<div class="package-box">
																			<div class="pkg-name pro_cet">
																				<?php echo $exam['exam_fullname']; ?> Exam Secure PDC + Engine Package</div>
																		</div>
																	        </div>
																	         <div class="col-md-5 col-xs-12">
																	             <div class="flex_boxex">
																	                 <div class="green_clr price_sizes">$
																			<?= $bprice ?>
																		</div>
																		<div class="buy-package pro_cet_1">
																			<!--<button class="carts_button" value="both" name="ptype" id="type_both" onclick='submitProexam(<?= $examID ?>,"both");'> Buy Now <img src="images/new-image/new_cart_icons.png"></button>-->
																			<button class="carts_button" value="both" name="ptype" id="type_both" onclick="<?= $isLoggedIn ? "submitProexam($examID, 'both');" : "redirectToLogin();" ?>"> Buy Now <img src="images/new-image/new_cart_icons.png"></button>
																		</div>
																	             </div>
																	         </div>
																	     </div>
																	
																	
																		
																	</li>
																	
																	
																	
																	
																	
																	
																	

																	<?php } ?>
																		<? } ?>
													</ul>
													<?php
												} else {
													?>
														<li>
															<?php if ($exam['QA'] == '0') { ?>
																<div style="width:95%">
																	<span class="popular-heading">This Exam <?php echo $exam['exam_name']; ?> is Under Development, Please Enter Your Email Address Below. We Will Notify You Once This Exam Ready To Download.</span>
																	<form action="" name="demoForm2" method="post" onsubmit="return demoVerify3();"> Exam Code:
																		<br />
																		<input type="text" class="textbox" name="request_ecode" id="request_ecode" value="<?php echo $pCode; ?>" />
																		<br /> Email:
																		<br />
																		<input type="text" class="textbox" name="request_email" id="request_email" />
																		<br />
																		<input type="submit" class="orange-button" width="100" vspace="" hspace="90" /> </form>
																</div>
																<script language="javascript">
																function demoVerify3() {
																	regExp = /^[A-Za-z_0-9\.\-]+@[A-Za-z0-9\.\-]+\.[A-Za-z]{2,}$/;
																	email = document.forms['demoForm2'].elements['request_email'].value;
																	if(email == '' || !regExp.test(email)) {
																		alert('Enter Email');
																		document.getElementById('emailErr2').style.display = 'block';
																		document.forms['demoForm2'].elements['request_email'].focus();
																		return false;
																	}
																	document.getElementById('emailErr2').style.display = 'none';
																}
																</script>
																<?php } else { ?>
																	<div class="pkg-name-box">
																		<div class="unlimite-image">&nbsp;</div>
																		<div class="pkg-name-box02">&nbsp;</div>
																	</div>
																	<div class="package-box">
																		<div class="pkg-name"> Single
																			<?php echo $exam['exam_name']; ?> PDF Exams Package</div>
																		<div class="list-discription">
																			<ul class="popular-feature-list">
																				<li>
																					<?php echo $venName; ?>
																						<?php echo $exam['exam_name']; ?> Exam based on Real Questions</li>
																				<li>Include Multiple Choice, Drag Drop and Lab Scenario</li>
																				<li>Easy to use and downloadable PDF for
																					<?php echo $exam['exam_name']; ?> Exam</li>
																				<li>Free Demo Available for Exam
																					<?php echo $exam['exam_name']; ?>
																				</li>
																				<li>
																					<?php echo $venName; ?> Experts Verified Answers for
																						<?php echo $exam['exam_name']; ?> Dumps</li>
																				<li>Cover all Latest and Updated Objectives from
																					<?php echo $venName; ?>
																				</li>
																				<li>100% Money Back Guarantee</li>
																				<li>Free Lifetime Updates</li>
																			</ul>
																		</div>
																	</div>
																	<div class="buy-package">
																		<div class="package-price">$
																			<?php echo $exam['exam_pri0'] ?>
																		</div>
																		<button class="black-button">Buy Now </button>
																	</div>
																	<?php
                													}
                													?>
												    <?php } ?>
														</li>
														</div>
									 
									 </div>
									 </div>
									 </div>
									 </div>
									 
									 <div class="passing_dumps display_inlines paddtop60 paddbottom60 ">
									     <div class="container">
    									 <div class="main_heading text-center paddbtm10"><?php echo $exam['exam_fullname']; ?> (<?= $exam['QA'] ?>) Passing <span>Results</span></div>
        								<ul class="courses_pass_img" id="scroller">
                                                <?php
                                                $banners = mysql_query('select * from sliders_new where group_id=' . $examID);
                                                if (mysql_num_rows($banners) == 0) {
                                                    $banners = mysql_query("select * from sliders where type='lab' AND status = 1");
                                                }
                                                
                                                while ($banner = mysql_fetch_object($banners)) {
                                                ?>
                                                    <li>
                                                        <a href="/images/slider/<?= $banner->s_image; ?>" 
                                                           data-fancybox="course-gallery"
                                                           data-caption="<?= htmlspecialchars($banner->s_alt); ?>">
                                                           
                                                            <img src="/images/slider/<?= $banner->s_image; ?>" 
                                                                 alt="<?= htmlspecialchars($banner->s_alt); ?>" />
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                                </ul>
        								<?php $uri = explode('-', $_SERVER['REQUEST_URI']);
        								//$uri[0] == '/CISSP.htm' ||
														if ($uri[1] == 'Bootcamp.htm' || $uri[2] == 'Lab.htm' || $uri[1] == 'Lab.htm' || $uri[5] == 'lab.htm' || $uri[6] == 'lab.htm' || $uri[6] == 'workbook.htm' || $uri[5] == 'workbook.htm' || $uri[6] == 'bootcamp.htm' || $uri[5] == 'bootcamp.htm') 
														{ ?>
															<br>
															<br>
															<section id="service_table" class="commom-sec grey-sec display_none2">
																<div class="">
																	<div class="table-responsive stable-unstable workbook_table">
																		<table width="100%" border="0">
																			<thead>
																				<tr>
																					<td>Products</td>
																					<td>Categories</td>
																					<td>labs</td>
																					<td>Stable/Unstable</td>
																					<td>Exam Code</td>
																					<td>Dumps</td>
																				</tr>
																			</thead>
																			<tbody>
																				<tr >
																					<td>CCIE Enterprise</td>
																					<td>
																						<dd><a href="">Written</a></dd>
																						<dd><a href="">LAB</a></dd>
																					</td>
																					<td>
																						<dd>3Design + 2Deploy</dd>
																						<dd>3Design + 2Deploy</dd>
																					</td>
																					<td>
																						<dd><span style="color:<?= (checkCourseStatus('350-401 ENCOR') == 'stable') ? '#1F733D' : '#ff0000'; ?>"><?= (checkCourseStatus('350-401 ENCOR') == 'stable') ? 'Stable' : 'Unstable'; ?></span></dd>
																						<dd><span style="color:<?= (checkCourseStatus('CCIE EI Lab') == 'stable') ? '#1F733D' : '#ff0000'; ?>"><?= (checkCourseStatus('CCIE EI Lab') == 'stable') ? 'stable' : 'Unstable'; ?></span></dd>
																					</td>
																					<td>
																						<dd>350-401</dd>
																						<dd>Lab</dd>
																					</td>
																					<td>
																						<!--<button class="carts_button" value="8" name="ptype" id="type_8" onclick="submitProexam(6794,&quot;8&quot;);">Buy Now <img src="images/new-image/new_cart_icons.png"></button>-->
																						<button class="carts_button" value="8" name="ptype" id="type_8" onclick="<?= $isLoggedIn ? "submitProexam(6794, '8');" : 'redirectToLogin();' ?>">Buy Now <img src="images/new-image/new_cart_icons.png"></button>
																					</td>
																				</tr>
																				<tr>
																					<td>CCIE Security</td>
																					<td>
																						<dd><a href="">Written</a></dd>
																						<dd> <a href="">LAB</a></dd>
																					</td>
																					<td>
																						<dd>1Design + 1Deploy</dd>
																						<dd>1Design + 1Deploy</dd>
																					</td>
																					<td>
																						<dd><span style="color:<?= (checkCourseStatus('350-701 SCOR') == 'stable') ? '#1F733D' : '#ff0000'; ?>"><?= (checkCourseStatus('350-701 SCOR') == 'stable') ? 'Stable' : 'Unstable'; ?></span></dd>
																						<dd><span style="color:<?= (checkCourseStatus('CCIE Security Lab') == 'stable') ? '#1F733D' : '#ff0000'; ?>"><?= (checkCourseStatus('CCIE Security Lab') == 'stable') ? 'Stable' : 'Unstable'; ?></span></dd>
																					</td>
																					<td>
																						<dd>350-701</dd>
																						<dd>Lab</dd>
																					</td>
																					<td>
																						<!--<button class="carts_button" value="8" name="ptype" id="type_8" onclick="submitProexam(6791,8);">Buy Now <img src="images/new-image/new_cart_icons.png"></button>-->
																						<button class="carts_button" value="8" name="ptype" id="type_8" onclick="<?= $isLoggedIn ? "submitProexam(6791, '8');" : 'redirectToLogin();' ?>">Buy Now <img src="images/new-image/new_cart_icons.png"></button>
																					</td>
																				</tr>
																				<tr>
																					<td >CCIE Data Center</td>
																					<td  >
																						<dd ><a href="">Written</a></dd>
																						 <dd><a href="">LAB</a></dd>
																					</td>
																					<td  >
																						<dd >1Design + 1Deploy</dd>
																						 <dd>1Design + 1Deploy</dd>
																					</td>
																					<td  >
																						<dd ><span style="color:<?= (checkCourseStatus('350-601 DCCOR') == 'stable') ? '#1F733D' : '#ff0000'; ?>"><?= (checkCourseStatus('350-601 DCCOR') == 'stable') ? 'Stable' : 'Unstable'; ?></span></dd>
																						 <dd><span style="color:<?= (checkCourseStatus('CCIE DC Lab') == 'stable') ? '#1F733D' : '#ff0000'; ?>"><?= (checkCourseStatus('CCIE DC Lab') == 'stable') ? 'Stable' : 'Unstable'; ?></span></dd>
																					</td>
																					<td  >
																						<dd >350-601</dd>
																						 <dd>Lab</dd>
																					</td>
																					<td >
																						<!--<button class="carts_button" value="8" name="ptype" id="type_8" onclick="submitProexam(6792,8);">Buy Now <img src="images/new-image/new_cart_icons.png"></button>-->
																						<button class="carts_button" value="8" name="ptype" id="type_8" onclick="<?= $isLoggedIn ? "submitProexam(6792, '8');" : 'redirectToLogin();' ?>">Buy Now <img src="images/new-image/new_cart_icons.png"></button>
																					</td>
																				</tr>
																				<tr  >
																					<td >CCIE Wireless Infra</td>
																					<td  >
																						<dd ><a href="">Written</a></dd>
																						 <dd><a href="https://cciedumps.chinesedumps.com/">LAB</a></dd>
																					</td>
																					<td  >
																						<dd >3Design + 3Deploy</dd>
																						 <dd>3Design + 3Deploy</dd>
																					</td>
																					<td  >
																						<dd ><span style="color:<?= (checkCourseStatus('350-401 ENCOR') == 'stable') ? '#1F733D' : '#ff0000'; ?>"><?= (checkCourseStatus('350-401 ENCOR') == 'stable') ? 'Stable' : 'Unstable'; ?></span></dd>
																						 <dd><span style="color:<?= (checkCourseStatus('CCIE Wireless Lab') == 'stable') ? '#1F733D' : '#ff0000'; ?>"><?= (checkCourseStatus('CCIE Wireless Lab') == 'stable') ? 'Stable' : 'Unstable'; ?></span></dd>
																					</td>
																					<td  >
																						<dd >350-401</dd>
																						 <dd>Lab</dd>
																					</td>
																					<td >
																						<!--<button class="carts_button" value="8" name="ptype" id="type_8" onclick="submitProexam(6793,8);">Buy Now <img src="images/new-image/new_cart_icons.png"></button>-->
																						<button class="carts_button" value="8" name="ptype" id="type_8" onclick="<?= $isLoggedIn ? "submitProexam(6793, '8');" : 'redirectToLogin();' ?>">Buy Now <img src="images/new-image/new_cart_icons.png"></button>
																					</td>
																				</tr>
																				<tr>
																					<td >CCIE Service Provider</td>
																					<td  >
																						<dd ><a href="https://cciedumps.chinesedumps.com/">Written</a></dd>
																						 <dd><a href="">LAB</a></dd>
																					</td>
																					<td  >
																						<dd >1Design + 1Deploy</dd>
																						 <dd>1Design + 1Deploy</dd>
																					</td>
																					<td  >
																						<dd ><span style="color:<?= (checkCourseStatus('350-501 SPCOR') == 'stable') ? '#1F733D' : '#ff0000'; ?>"><?= (checkCourseStatus('350-501 SPCOR') == 'stable') ? 'Stable' : 'Unstable'; ?></span></dd>
																						 <dd><span style="color:<?= (checkCourseStatus('CCIE SP Lab') == 'stable') ? '#1F733D' : '#ff0000'; ?>"><?= (checkCourseStatus('CCIE SP Lab') == 'stable') ? 'Stable' : 'Unstable'; ?></span></dd>
																					</td>
																					<td  >
																						<dd >350-501</dd>
																						 <dd>Lab</dd>
																					</td>
																					<td >
																						<!--<button class="carts_button" value="8" name="ptype" id="type_8" onclick="submitProexam(6790,&quot;8&quot;);">Buy Now <img src="images/new-image/new_cart_icons.png"></button>-->
																						<button class="carts_button" value="8" name="ptype" id="type_8" onclick="<?= $isLoggedIn ? "submitProexam(6790, '8');" : 'redirectToLogin();' ?>">Buy Now <img src="images/new-image/new_cart_icons.png"></button>
																					</td>
																				</tr>
																				<tr  >
																					<td >CCIE Collaboration</td>
																					<td  >
																						<dd ><a href="">Written</a></dd>
																						 <dd><a href="https://cciedumps.chinesedumps.com/">LAB</a></dd>
																					</td>
																					<td  >
																						<dd >1Design + 1Deploy</dd>
																						 <dd>1Design + 1Deploy</dd>
																					</td>
																					<td  >
																						<dd ><span style="color:<?= (checkCourseStatus('CLCOR 350-801') == 'stable') ? '#1F733D' : '#ff0000'; ?>"><?= (checkCourseStatus('CLCOR 350-801') == 'stable') ? 'Stable' : 'Unstable'; ?></span></dd>
																						 <dd><span style="color:<?= (checkCourseStatus('CCIE Collab Lab') == 'stable') ? '#1F733D' : '#ff0000'; ?>"><?= (checkCourseStatus('CCIE Collab Lab') == 'stable') ? 'Stable' : 'Unstable'; ?></span></dd>
																					</td>
																					<td  >
																						<dd >350-801</dd>
																						 <dd>Lab</dd>
																					</td>
																					<td >
																						<!--<button class="carts_button" value="8" name="ptype" id="type_8" onclick="submitProexam(6788,&quot;8&quot;);">Buy Now <img src="images/new-image/new_cart_icons.png"></button>-->
																						<button class="carts_button" value="8" name="ptype" id="type_8" onclick="<?= $isLoggedIn ? "submitProexam(6788, '8');" : 'redirectToLogin();' ?>">Buy Now <img src="images/new-image/new_cart_icons.png"></button>
																					</td>
																				</tr>
																				<tr >
																					<td >CCDE</td>
																					<td  >
																						<dd ><a href="">Written</a></dd>
																						 <dd><a href="">LAB</a></dd>
																					</td>
																					<td  >
																						<dd >1Design + 1Deploy</dd>
																						 <dd>1Design + 1Deploy</dd>
																					</td>
																					<td  >
																						<dd ><span style="color:<?= (checkCourseStatus('350-401 ENCOR') == 'stable') ? '#1F733D' : '#ff0000'; ?>"><?= (checkCourseStatus('350-401 ENCOR') == 'stable') ? 'Stable' : 'Unstable'; ?></span></dd>
																						<dd><span style="color:<?= (checkCourseStatus('CCDE Lab') == 'stable') ? '#1F733D' : '#ff0000'; ?>"><?= (checkCourseStatus('CCDE Lab') == 'stable') ? 'Stable' : 'Unstable'; ?></span></dd>
																					</td>
																					<td  >
																						<dd >400-007</dd>
																						 <dd>Lab</dd>
																					</td>
																					<td >
																						<!--<button class="carts_button" value="sp" name="ptype" id="type_sp" onclick="submitProexam(6807,&quot;sp&quot;);">Buy Now <img src="images/new-image/new_cart_icons.png"></button>-->
																						<button class="carts_button" value="sp" name="ptype" id="type_sp" onclick="<?= $isLoggedIn ? "submitProexam(6807, 'sp');" : 'redirectToLogin();' ?>">Buy Now <img src="images/new-image/new_cart_icons.png"></button>
																					</td>
																				</tr>
																				
																					<tr >
																					<td >CCIE DevNet</td>
																					<td  >
																						<dd ><a href="">Written</a></dd>
																						 <dd><a href="">LAB</a></dd>
																					</td>
																					<td  >
																						<dd >1Design + 1Deploy</dd>
																						 <dd>1Design + 1Deploy</dd>
																					</td>
																					<td  >
																						<dd ><span style="color:<?= (checkCourseStatus('350-401 ENCOR') == 'stable') ? '#1F733D' : '#ff0000'; ?>"><?= (checkCourseStatus('350-401 ENCOR') == 'stable') ? 'Stable' : 'Unstable'; ?></span></dd>
																						<dd><span style="color:<?= (checkCourseStatus('CCDE Lab') == 'stable') ? '#1F733D' : '#ff0000'; ?>"><?= (checkCourseStatus('CCDE Lab') == 'stable') ? 'Stable' : 'Unstable'; ?></span></dd>
																					</td>
																					<td  >
																						<dd >350-901</dd>
																						 <dd>Lab</dd>
																					</td>
																					<td >
																						<button class="carts_button" value="sp" name="ptype" id="type_sp" onclick="">Buy Now <img src="images/new-image/new_cart_icons.png"></button>
																					</td>
																				</tr>
																			</tbody>
																		</table>
																	</div>
																</div>
															</section>
														<?php } ?>	
    									</div>
									</div>
									 									
									<?php /*if($demo_images){ ?>
 									 <div class="passing_dumps paddtop60 paddbottom60 display_inlines">
									     <div class="container">
        									 <div class="main_heading text-center paddbtm10"> Demo <span>Images</span></div>
        									     <div class="exam-demo-gallery">
                                                  <?php foreach ($demo_images as $img): ?>
                                                    <img 
                                                      src="/images/exam_demo_images/<?= htmlspecialchars($img) ?>" 
                                                      alt="Demo Image" 
                                                      style="max-width: 200px; margin: 5px;"
                                                    />
                                                  <?php endforeach; ?>
                                                </div>
        									 </div>
    									 </div>
									 </div>
									 <?php }*/  ?>
									 
									<?php if($video_links){ ?>
 									 <div class="passing_dumps paddbottom60 display_inlines">
									     <div class="container">
        									 <div class="main_heading text-center paddbtm10"> <?php echo $exam['exam_fullname']; ?> (<?= $exam['QA'] ?>) <span>Live Exam Videos</span></div>
        									     <div class="exam-demo-gallery">

                                                    <div class="row">
                                                      <?php foreach ($video_links as $video):
                                                          $service = isset($video['service']) ? $video['service'] : 'youtube';
                                                          $url = isset($video['url']) ? $video['url'] : '';
                                                          $embed = getVideoEmbedUrl($service, $url);
                                                          if (!$embed) continue;
                                                      ?>
                                                        <div class="col-12 col-sm-6 col-md-4" style="margin-bottom:14px;">
                                                          <div class="video-wrap" style="position:relative;padding-top:56.25%;cursor:pointer;">
                                                            <div class="yt-thumb" data-embed="<?= htmlspecialchars($embed); ?>" aria-role="button" tabindex="0" style="position:absolute;inset:0;display:block;">
                                                                <iframe 
                                                                  src="<?php echo htmlspecialchars($embed); ?>"
                                                                  frameborder="0"
                                                                  allow="autoplay; encrypted-media; picture-in-picture"
                                                                  allowfullscreen
                                                                  loading="lazy"
                                                                  style="position:absolute; inset:0; width:100%; height:100%; border:0; border-radius:6px;">
                                                                </iframe>
                                                            </div>
                                                          </div>
                                                        </div>
                                                      <?php endforeach; ?>
                                                    </div>

                                                </div>
        									 </div>
    									 </div>
									 </div>
									 <?php } ?>									 
									 
									 <?php if (!empty($exam['telegram_url']) || !empty($exam['skype_url']) || !empty($exam['whatsapp_url'])) { ?>
                                    <div class="social_group_section display_inlines" style="background-image:url(images/new-image/bg_social_img.jpg)">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-8 col-sm-10">
                                                    <div class="main_heading text-left">
                                                        Join Our 10,000+ Learner whatsapp Community 
                                                        <img class="arrow_images" src="images/new-image/left_arrows.svg">
                                                    </div>
                                                    <p style="font-size:16px;">
                                                      Connect with fellow candidates, get free study materials, and unlock bonus resources.
                                                    </p>
                                                </div>
                                                <div class="col-md-4 col-sm-2">
                                                    <div class="mnimg_cl">
                                                        <?php if (!empty($exam['telegram_url'])) { ?>
                                                            <!--<a href="<?php echo $exam['telegram_url']; ?>">-->
                                                            <!--    <img class="ldicon_img" src="<?php echo BASE_URL; ?>images/telegram.png" />-->
                                                            <!--</a>-->
                                                        <?php } ?>
                                
                                                        <?php if (!empty($exam['whatsapp_url'])) { ?>
                                                            <a style="padding-left: 5px;" href="<?php echo $exam['whatsapp_url']; ?>">
                                                                <img class="ldicon_img" src="<?php echo BASE_URL; ?>images/whatsapp.png" />
                                                            </a>
                                                        <?php } ?>
                                                    </div>
                                                </div>				
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>

									  
									  
									  <section class="why_chooseus_sec paddtop60 paddbottom60">
   <div class="container">
       <div class="row">
           <div class="col-md-8">
                 <div class="main_heading">Why Choose Us?</div>
       <p class="paddbtm20">
           We provide regularly updated exam dumps to ensure you get the most recent and accurate questions for your certification exams.
       </p>
       
       <div class="choose_boxex">
          <div class="chooose_box_img">
              <img src="images/new-image/real_icons.svg" />
          </div>
          
           <div class="chooose_box_content">
                <h5 class="green_clr">Latest & Updated Exam Dumps</h5>
                <p class="green_clr">Our dumps are regularly updated with the latest exam patterns and real questions, so you always study relevant, up-to-date material.</p>
          </div>
       </div>
       
       <div class="choose_boxex">
          <div class="chooose_box_img">
              <img src="images/new-image/garantee_icons.svg" />
          </div>
          
           <div class="chooose_box_content">
                <h5 class="green_clr">Proven First-Attempt Success</h5>
                <p class="green_clr">Expert-reviewed and validated by recent test-takers, our dumps have helped thousands pass on their first attempt.</p>
          </div>
       </div>
       
       <div class="choose_boxex">
          <div class="chooose_box_img">
              <img src="images/new-image/why_choose_1.svg" />
          </div>
          
           <div class="chooose_box_content">
                <h5 class="green_clr">Fast, Secure & Verified Delivery</h5>
                <p class="green_clr">Your exam dumps are delivered to your registered email within 24–48 hours after payment, ensuring proper verification and quality checks.</p>
          </div>
       </div>
       
        <div class="choose_boxex">
          <div class="chooose_box_img">
              <img src="images/new-image/why_choose_3.svg" />
          </div>
          
           <div class="chooose_box_content">
                <h5 class="green_clr">Real Exam-Level Questions</h5>
                <p class="green_clr">We provide real exam-style questions that match actual structure, difficulty, and topic weightage for focused preparation.</p>
          </div>
       </div>
       
       <!-- <div class="choose_boxex">-->
       <!--   <div class="chooose_box_img">-->
       <!--       <img src="images/new-image/why_choose_5.svg" />-->
       <!--   </div>-->
          
       <!--    <div class="chooose_box_content">-->
       <!--         <h5 class="green_clr">Affordable Pricing</h5>-->
       <!--         <p class="green_clr">Get premium-quality dumps at unbeatable prices with lifetime updates.</p>-->
       <!--   </div>-->
       <!--</div>-->
       
       
       
           </div>
           
           <div class="col-md-4 position_sticky1">
              <div class="enquiry_forms_section mt_00">
                  <h4 class="entroll_class">Enqire Now</h4>
                <div role="form" class="wpcf7" id="wpcf7-f611-p6-o1" lang="en-US" dir="ltr">
                  <div class="screen-reader-response"></div>
                  <form name="myForm" action="/thanks-you.htm" method="post" onsubmit="return validateForm()" class="wpcf7-form">
                    <div class="form-group">
                      <span class="wpcf7-form-control-wrap text-128">
                        <input type="text" required name="name" value="" size="40" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required form-control" aria-required="true" aria-invalid="false" placeholder="Your Name">
                      </span>
                    </div>
                    <div class="form-group">
                      <span class="wpcf7-form-control-wrap email-415">
                        <input type="email" required name="email" value="" size="40" class="wpcf7-form-control wpcf7-text wpcf7-email wpcf7-validates-as-required wpcf7-validates-as-email form-control" aria-required="true" aria-invalid="false" placeholder="Your Email">
                      </span>
                    </div>
                     <div class="form-group numbere_fileds">
                      <span class="wpcf7-form-control-wrap tel-128">
                        <input type="mobile" id="mobile_code" required name="tel" value="" size="40" class="wpcf7-form-control wpcf7-text wpcf7-tel wpcf7-validates-as-required wpcf7-validates-as-tel form-control" aria-required="true" aria-invalid="false" placeholder="Mobile Number">
                      </span>
                    </div>
                    
                     <!--<div class="form-group" >-->
                     <!--   <select id="courses" name="courses" class="form-control">-->
                     <!--       <option value="">--Select Dumps or Real lab----</option>-->
                     <!--       <option value="ccna-200-301">CCNA 200-301</option>-->
                     <!--       <option value="ccnp-encor-350-401">CCNP ENCOR 350-401</option>-->
                     <!--       <option value="ccnp-scor-350-701">CCNP SCOR 350-701</option>-->
                     <!--       <option value="ccnp-dccor-350-601">CCNP DCCOR 350-601</option>-->
                     <!--       <option value="ccnp-spcor-350-501">CCNP SPCOR 350-501</option>-->
                     <!--       <option value="ccnp-clcor-350-801">CCNP CLCOR 350-801</option>-->
                     <!--       <option value="ccnp-devcor-350-901">CCNP DEVCOR 350-901</option>-->
                     <!--       <option value="ccde-400-007">CCDE 400-007</option>-->
                     <!--       <option value="ccie-enterprise-lab-v1-1">CCIE Enterprise Lab v1.1</option>-->
                     <!--       <option value="ccie-security-lab-v1-1">CCIE Security Lab v1.1</option>-->
                     <!--       <option value="ccie-data-center-lab-v3-1">CCIE Data Center Lab v3.1</option>-->
                     <!--       <option value="ccie-service-provider-lab-v1-1">CCIE Service Provider Lab v1.1</option>-->
                     <!--       <option value="ccie-collaboration-lab-v3-1">CCIE Collaboration Lab v3.1</option>-->
                     <!--       <option value="ccie-wireless-lab-v1-1">CCIE Wireless Lab v1.1</option>-->
                     <!--       <option value="ccde-lab">CCDE Lab</option>-->
                     <!--       <option value="cybersecurity-ops">Cybersecurity Ops</option>-->
                     <!--       <option value="fortinet-nse8">Fortinet NSE8</option>-->
                     <!--       <option value="juniper">Juniper</option>-->
                     <!--       <option value="comptia">CompTIA</option>-->
                     <!--       <option value="aws">AWS</option>-->
                     <!--       <option value="pmp">PMP</option>-->
                     <!--       <option value="microsoft">Microsoft</option>-->
                     <!--       <option value="other">Other</option>-->
                     <!--   </select>-->

                    
                    <div class="form-group">
                      <span class="wpcf7-form-control-wrap tel-128">
                        <textarea rows="3"
          name="message"
          class="form-control"
          placeholder="I am looking for ......"
          onfocus="if(this.value==='I am looking for ......'){this.value='';}"
          onblur="if(this.value===''){this.value='I am looking for ......';}">
</textarea>
                        <span>
                    </div>
                    <div class="g-recaptcha" data-sitekey="<?php echo htmlspecialchars((string)secret('RECAPTCHA_SITE_KEY_DEFAULT', ''), ENT_QUOTES); ?>"></div>
                    <input type="hidden" name="recaptcha" data-rule-recaptcha="true">
                    <input type="hidden" name="home_form">
                    <input type="hidden" name="type" value="home">
                    <input type="submit" value="Submit" class="wpcf7-form-control wpcf7-submit ti_btn button_cls">
                    <span class="ajax-loader"></span>
                  </form>
                  </div>
                </div>
              </div>
            </div>
       </div>
   </div>
</section>


<div class="seo_sections">
    <div class="container">
        <div style="clear:both">
            <?php 
                                                            
            $uri = explode('-', $_SERVER['REQUEST_URI']);
            
            if (
                (isset($uri[1]) && ($uri[1] == 'Bootcamp.htm' || $uri[1] == 'Lab.htm')) ||
                (isset($uri[2]) && $uri[2] == 'Lab.htm') ||
                (isset($uri[5]) && ($uri[5] == 'lab.htm' || $uri[5] == 'workbook.htm' || $uri[5] == 'bootcamp.htm')) ||
                (isset($uri[6]) && ($uri[6] == 'lab.htm' || $uri[6] == 'workbook.htm' || $uri[6] == 'bootcamp.htm'))
            ){ ?>
               <div class="course-description" style="clear:both"> <?= $exam['exam_descr2']; ?> </div>
            <?php } else { ?>
                <div class="course-description" style="clear:both"> <?= $exam['exam_descr']; ?> </div>
            <?php } ?>
                                                            
			

</div>
    </div>
</div>


																
																
																
																<!--<div class="col-md-12">-->
																<!--	<div class="row">-->
																<!--		<div class="prd_ctn">-->
																<!--			<h4>Product Advantages</h4>-->
																<!--			<div class="col-md-6">-->
																<!--				<ul>-->
																					
																<!--					<li><i class="fa fa-check"></i>Real Exam Environment</li>-->
																<!--					<li><i class="fa fa-check"></i>VIP service team Support</li>-->
																<!--					<li><i class="fa fa-check"></i>Average 7 Days to Practice & Pass</li>-->
																<!--					<li><i class="fa fa-check"></i>Update Timely</li>-->
																<!--				</ul>-->
																<!--			</div>-->
																<!--			<div class="col-md-6">-->
																<!--				<ul>-->
																					
																<!--					<li><i class="fa fa-check"></i>Real Exam Environment</li>-->
																<!--					<li><i class="fa fa-check"></i>VIP service team Support</li>-->
																<!--					<li><i class="fa fa-check"></i>Average 7 Days to Practice & Pass</li>-->
																<!--					<li><i class="fa fa-check"></i>Update Timely</li>-->
																<!--				</ul>-->
																<!--			</div>-->
																<!--		</div>-->
																<!--	</div>-->
																<!--</div>-->
																<!--<div class="col-md-12 img_alp">-->
																<!--	<div class="text-center"><img src="images/tutor_img.png" style="width:100%;"></div>-->
																<!--</div>-->
																<!--------labs open------------>
																
<?php
                                                                // Fetch initial related products into an array
                                                                $relatedProducts = [];
                                                                while ($row = mysql_fetch_array($exerel)) {
                                                                    $relatedProducts[] = $row;
                                                                }
                                                                
                                                                // If there are fewer than 2 products, fetch additional ones
                                                                if (count($relatedProducts) <= 3) {
                                                                    $relatedProducts = [];
                                                                    $additionalQuery = mysql_query("SELECT * FROM tbl_exam WHERE ven_id='31' AND exam_status='1'");
                                                                    while ($row = mysql_fetch_array($additionalQuery)) {
                                                                        $relatedProducts[] = $row;
                                                                    }
                                                                }
                                                                
                                                                // If there are products, display the section
                                                                if (!empty($relatedProducts)) {
                                                                ?>
                                                                <section class="related_products paddtop60 owl_buttons">
                                                                    <div class="container">
                                                                        <div class="main_heading text-center pbbtm20">Related <span> Products </span></div>
                                                                        <div class="owl-carousel owl-theme related_products_slider">
                                                                            <?php
                                                                            foreach ($relatedProducts as $rowallitems2) {
                                                                                $examName = $rowallitems2['exam_name'];
                                                                                $eurl = $base_path . $rowallitems2['exam_url'] . '.htm';
                                                                                $venID         = $rowallitems2['ven_id'];
                                                                                $vendor_result = $objPro->get_vendorName($venID);
                                                                                $venName       = $vendor_result['ven_name'];
                                                                                $vendorImg     = isset($vendor_result['default_image']) ? trim($vendor_result['default_image']) : '';
                                                                                ?>
                                                                                <div class="item">
                                                                                    <div class="related_products_box">
                                                                                        <div class="related_img">
                                                                                            <?php
                                                                                                $imagePath  = '/images/slider/' . $rowallitems2['exam_id'] . '/' . $rowallitems2['course_image'];
                                                                                                $hasExamImg = !empty($rowallitems2['course_image']) && file_exists(base_path($imagePath));
                                                                                                $hasVendorImg = ($vendorImg !== '' && file_exists(base_path($vendorImg)));
                                                                                            
                                                                                                if ($hasExamImg) {
                                                                                                    echo '<img src="' . $imagePath . '" style="width: 100%;">';
                                                                                                } elseif ($hasVendorImg) {
                                                                                                    echo '<img src="' . $vendorImg . '" style="width: 100%;">';
                                                                                                } else {
                                                                                                    echo '<div class="exam-pkg-image01">&nbsp;</div>';
                                                                                                }
                                                                                            ?>
                                                                                            <?php/*
                                                                                            $imagePath = '/images/slider/' . $rowallitems2['exam_id'] . '/' . $rowallitems2['course_image'];
                                                                                            if (!empty($rowallitems2['course_image']) && file_exists(base_path($imagePath))) {
                                                                                                echo '<img src="' . $imagePath . '" style="width: 100%;">';
                                                                                            } else {
                                                                                                echo '<div class="exam-pkg-image01">&nbsp;</div>';
                                                                                            }*/  ?>
                                                                                        </div>
                                                                                        <div class="realted_box_content">
                                                                                            <h5 class=""><a title="<?php echo $examName ?>" href="<?php echo $eurl ?>">
                                                                                                <?php echo $rowallitems2['exam_fullname'] ?></a></h5>
                                                                
                                                                                            <div class="exam_code_sec">
                                                                                                <?php 
                                                                                                    $certID = $rowallitems2['cert_id'];
                                                                                                    // $venID = $rowallitems2['ven_id'];
                                                                                                    // $vendor_result = $objPro->get_vendorName($venID);
                                                                                                    // $venName = $vendor_result['ven_name'];
                                                                                                    $cert_result = $objPro->get_certName($certID);
                                                                                                    $certName = $cert_result[0];
                                                                
                                                                                                    $uri = explode('-', $_SERVER['REQUEST_URI']);
                                                                                                    //(isset($uri[0]) && ($uri[0] == '/CISSP.htm')) ||
                                                                                                    if (
                                                                                                        
                                                                                                        (isset($uri[1]) && ($uri[1] == 'Bootcamp.htm' || $uri[1] == 'Lab.htm')) ||
                                                                                                        (isset($uri[2]) && $uri[2] == 'Lab.htm') ||
                                                                                                        (isset($uri[5]) && ($uri[5] == 'lab.htm' || $uri[5] == 'workbook.htm' || $uri[5] == 'bootcamp.htm')) ||
                                                                                                        (isset($uri[6]) && ($uri[6] == 'lab.htm' || $uri[6] == 'workbook.htm' || $uri[6] == 'bootcamp.htm'))
                                                                                                    ) {
                                                                                                        // Do nothing
                                                                                                    } else { ?>
                                                                                                        <div class="related_flex">
                                                                                                            <img src="images/new-image/exam_code_icons.svg" />
                                                                                                            <p>Exam Code: <span class="green_clr"><?php echo $examName; ?></span></p>
                                                                                                        </div>  
                                                                                                    <?php } ?>
                                                                
                                                                                                
                                                                                                <div class="related_flex">
                                                                                                    <img src="images/new-image/status_icons.svg" />
                                                                                                    <p>    
                                                                                                    <?php
                                                                                                    $get_course = mysql_fetch_assoc(mysql_query("SELECT * FROM tbl_course WHERE name='" . $examName . "'"));
                                                                                                    if (!empty($get_course)) {
                                                                                                        $status2 = ($get_course['status'] == 1) ? '<span style="color:green">Stable</span>' : '<span style="color:red">Unstable</span>';
                                                                                                    }
                                                                                                    ?>
                                                                                                    <strong>Status: </strong><?= (!empty($status2) ? $status2 : '<span style="color:green">Stable</span>'); ?>
                                                                                                    </p>
                                                                                                </div>
                                                                                            </div>
                                                                                            <?php 
                                                                                            if (!in_array($uri[1], ['Bootcamp.htm', 'Lab.htm']) && !in_array($uri[2], ['Lab.htm']) && !in_array($uri[5], ['lab.htm', 'workbook.htm', 'bootcamp.htm']) && !in_array($uri[6], ['lab.htm', 'workbook.htm', 'bootcamp.htm'])) { ?>
                                                                                                <div class="package-box">
                                                                                                    <div class="list-discription courses_discriptions">
                                                                                                        Interactive Testing Engine Tool that enables customize
                                                                                                        <?php echo $venName; ?>
                                                                                                        <?php echo $examName; ?>
                                                                                                        <?php echo $certName; ?> questions into Topics and Objectives. Real
                                                                                                        <?php echo $examName; ?> Exam Questions with 100% Money back Guarantee.
                                                                                                    </div>
                                                                                                </div>
                                                                                            <?php } else { ?>
                                                                                                <p class="exam_reallab_desc"><?php echo $rowallitems2['exam_related_descr'] ?></p>
                                                                                            <?php } ?>
                                                                                        </div>
                                                                                        <a class="related_buttons" href="<?php echo $eurl ?>">View Details</a>
                                                                                    </div>
                                                                                </div>
                                                                            <?php } ?>
                                                                        </div>
                                                                    </div>
                                                                </section>
                                                                <?php } ?>



                                                           <?/* php 
																<div class="passing_dumps paddtop60 paddbottom60 green_bg_clr display_inlines">
																    <div class="container">
																	<div style="text-align: center; ">
																	    
																	    <div class="main_heading text-center paddbtm10"> Other <span>Dumps</span></div>
																			<ul id="scroller2" class="courses_pass_img">
																			<?php $banners = mysql_query("select * from sliders where type='written' AND status = 1");
																			for ($sr = 0; $sr < mysql_num_rows($banners); $sr++) {
																				$banner = mysql_fetch_object($banners); ?>
																				<li><img src="/images/slider/<?= $banner->s_image; ?>" alt="<?= $banner->s_alt; ?>" /></li>
																				<?php } ?>
																		</ul>
																		<!-- #labSlider -->
																		<script>
																		$('#labSlider').multislider({
																			continuous: true,
																			duration: 10000,
																		});
																		</script>
																		</div>
																	</div>
																</div>
																
															*/  ?>
																
																<section class="courses_slider_sec paddtop60 paddbottom60 owl_buttons green_bg_clr">
                                                               <div class="container">
                                                                   
                                                                   <?php 
                                                                    /* $uri = explode('-', $_SERVER['REQUEST_URI']);
                                                                    
                                                                    if (
                                                                        (isset($uri[1]) && ($uri[1] == 'Bootcamp.htm' || $uri[1] == 'Lab.htm')) ||
                                                                        (isset($uri[2]) && $uri[2] == 'Lab.htm') ||
                                                                        (isset($uri[5]) && ($uri[5] == 'lab.htm' || $uri[5] == 'workbook.htm' || $uri[5] == 'bootcamp.htm')) ||
                                                                        (isset($uri[6]) && ($uri[6] == 'lab.htm' || $uri[6] == 'workbook.htm' || $uri[6] == 'bootcamp.htm'))
                                                                    ){ ?>
                                                                    
                                                                   <div class="main_heading text-center paddbtm10">Other <span>Dumps</span> </div>
                                                                    <div class="owl-carousel owl-theme best_selling">
                                                                        <div class="item"><a href="/ccie-enterprise-infrastructure-v1-1-lab-workbook.htm"><img class="hvr-bounce-in" src="images/new-image/ei_ccie_1.svg" alt="ei_ccie_1"></a></div>
                                                                        <div class="item"><a href="/ccie-enterprise-infrastructure-v1-1-lab-bootcamp.htm"><img class="hvr-bounce-in" src="images/new-image/ei_ccie_2.svg" alt="ei_ccie_2"></a></div>
                                                                        <div class="item"><a href="/ccie-security-v6-1-lab-workbook.htm"><img class="hvr-bounce-in" src="images/new-image/sec_ccie_1.svg" alt="sec_ccie_1"></a></div>
                                                                        <div class="item"><a href="/ccie-security-v6-1-lab-bootcamp.htm"><img class="hvr-bounce-in" src="images/new-image/sec_ccie_2.svg" alt="sec_ccie_2"></a></div>
                                                                        <div class="item"><a href="/CCIE-DC-Lab.htm"><img class="hvr-bounce-in" src="images/new-image/dc_ccie_1.svg" alt="dc_ccie_1"></a></div>
                                                                        <div class="item"><a href="/DC-Bootcamp.htm"><img class="hvr-bounce-in" src="images/new-image/dc_ccie_2.svg" alt="dc_ccie_2"></a></div>
                                                                    </div>
                                                                     <?php } else { ?>
                                                                    
                                                                    <div class="main_heading text-center paddbtm10">Other <span>Dumps</span> </div>
                                                                    <div class="owl-carousel owl-theme best_selling">
                                                                        <div class="item"><a href="/encor-350-401-dumps.htm"><img class="hvr-bounce-in" src="images/new-image/350_401.svg" alt="350_401"></a></div>
                                                                        <div class="item"><a href="/300-410-ccnp-ent-enarsi-dumps.htm"><img class="hvr-bounce-in"src="images/new-image/300_410.svg" alt="300_410"></a></div>
                                                                        <div class="item"><a href="/300-415-ccnp-ent-ensdwi-dumps.htm"><img class="hvr-bounce-in" src="images/new-image/300_415.svg" alt="300_415"></a></div>
                                                                        <div class="item"><a href="/300-420-ccnp-ent-ensld-dumps.htm"><img class="hvr-bounce-in" src="images/new-image/350_420.svg" alt="350_420"></a></div>
                                                                    </div>
                                                                     <?php }*/  ?>
                                                                     
                                                                        <?php
                                                                        $sql = "
                                                                            SELECT 
                                                                                e.exam_id,
                                                                                e.exam_name,
                                                                                e.exam_url,
                                                                                e.course_image,
                                                                                v.default_image
                                                                            FROM tbl_exam e
                                                                            INNER JOIN tbl_vendors v ON v.ven_id = e.ven_id
                                                                            WHERE e.exam_status = 1
                                                                            ORDER BY RAND()
                                                                            LIMIT 12
                                                                        ";
                                                                        
                                                                        $result = mysql_query($sql) or die(mysql_error());
                                                                        ?>
                                                                        
                                                                        <div class="main_heading text-center paddbtm10">
                                                                            Other <span>Dumps</span>
                                                                        </div>
                                                                        
                                                                        <div class="owl-carousel owl-theme best_selling">
                                                                        <?php
                                                                        while ($row = mysql_fetch_assoc($result)) {
                                                                        
                                                                            // Exam image paths
                                                                            $examImgWeb  = "/images/slider/{$row['exam_id']}/{$row['course_image']}";
                                                                            $examImgDisk = base_path($examImgWeb);
                                                                        
                                                                            // Vendor image paths
                                                                            $vendorImgWeb  = $row['default_image'];
                                                                            $vendorIsUrl   = is_absolute_url($vendorImgWeb);
                                                                            $vendorImgDisk = $vendorIsUrl ? '' : base_path($vendorImgWeb);
                                                                        
                                                                            // Decide final image
                                                                            if (!empty($row['course_image']) && file_exists($examImgDisk)) {
                                                                                $finalImage = $examImgWeb;
                                                                            } elseif (!empty($vendorImgWeb) && ($vendorIsUrl || file_exists($vendorImgDisk))) {
                                                                                $finalImage = $vendorImgWeb;
                                                                            } else {
                                                                                $finalImage = "/images/exam-book-ico.png"; // fallback
                                                                            }
                                                                        ?>
                                                                            <div class="item">
                                                                                <a href="<?php echo $row['exam_url']; ?>.htm">
                                                                                    <img
                                                                                        class="hvr-bounce-in"
                                                                                        src="<?php echo $finalImage; ?>"
                                                                                        alt="<?php echo htmlspecialchars($row['exam_name'], ENT_QUOTES); ?>"
                                                                                    >
                                                                                </a>
                                                                            </div>
                                                                        <?php } ?>
                                                                        </div>
                                                                     
                                                                    
                                                               </div>
                                                            </section>


																
																
																<section class="faqs_sections paddtop60 paddbottom60">
   <div class="container">
       <div class="main_heading text-center">Frequently <span> Asked Questions </span></div>
       <p class="text-center pbtm20">Find answers to common questions about our exam dumps, payment options, guarantees, and more!</p>
    
       <div class="row">
           <div class="col-md-10 col-md-offset-1">
               <div class="panel-group" id="accordion">
                   
                   <div class="panel panel-default">
                       <div class="panel-heading">
                           <a data-toggle="collapse" data-parent="#accordion" href="#faq1">
                           <h4 class="panel-title">
                               
                                  Are these dumps 100% real and updated?
                                   <i class="fa fa-plus pull-right"></i>
                           </h4>
                           </a>
                       </div>
                       <div id="faq1" class="panel-collapse collapse">
                           <div class="panel-body">
                               <p>Yes, our exam dumps are based on real exam questions and are continuously updated to match the latest exam patterns, syllabus changes, and question formats. This ensures you always prepare with current, accurate, and exam-relevant content.</p>
                           </div>
                       </div>
                   </div>

                   <div class="panel panel-default">
                       <div class="panel-heading">
                           <a data-toggle="collapse" data-parent="#accordion" href="#faq2">
                           <h4 class="panel-title">
                                   Can I pass my exam just by using these dumps?
                                   <i class="fa fa-plus pull-right"></i>
                              
                           </h4>
                            </a>
                       </div>
                       <div id="faq2" class="panel-collapse collapse">
                           <div class="panel-body">
                               <p>Yes. Our exam dumps are built from real exam questions with verified answers, giving you exactly what appears in the exam and helping you pass with confidence when used properly.</p>
                              
                           </div>
                       </div>
                   </div>

                   <div class="panel panel-default">
                       <div class="panel-heading">
                           <a data-toggle="collapse" data-parent="#accordion" href="#faq3">
                           <h4 class="panel-title">
                               How soon can I access my dumps after purchase?
                                   <i class="fa fa-plus pull-right"></i>
                               
                           </h4>
                           </a>
                       </div>
                       <div id="faq3" class="panel-collapse collapse">
                           <div class="panel-body">
                               <p>
After your purchase is confirmed, the study materials are typically delivered to your registered email address. In most cases, customers receive access within a few hours of completing the payment. However, because we serve customers across different time zones and process orders manually for quality verification, we maintain a delivery window of 24 to 48 hours as a standard buffer. If you need your exam dumps urgently or on priority, we’re happy to help. You can contact our support team directly at 
<a href="https://api.whatsapp.com/send/?phone=447591437400&text&type=phone_number&app_absent=0" target="_blank">
+44 7591 437400
</a> for faster delivery assistance.
</p>

                           </div>
                       </div>
                   </div>

                   <div class="panel panel-default">
                       <div class="panel-heading">
                           <a data-toggle="collapse" data-parent="#accordion" href="#faq4">
                           <h4 class="panel-title">
                               Are these dumps suitable for first-time test takers?
                                   <i class="fa fa-plus pull-right"></i>
                               
                           </h4>
                           </a>
                       </div>
                       <div id="faq4" class="panel-collapse collapse">
                           <div class="panel-body">
                               <p>Yes, our dumps are designed for both beginners and experienced professionalsYes. Our exam dumps are designed to be easy to understand and exam-focused, making them ideal for first-time candidates as well as experienced professionals preparing for certification success.</p>
                           </div>
                       </div>
                   </div>

                   <div class="panel panel-default">
                       <div class="panel-heading">
                           <a data-toggle="collapse" data-parent="#accordion" href="#faq5"><h4 class="panel-title">
                               Do you offer a passing guarantee?
                                   <i class="fa fa-plus pull-right"></i>
                               
                           </h4>
                           </a>
                       </div>
                       <div id="faq5" class="panel-collapse collapse">
                           <div class="panel-body">
                               <p>While we don’t offer a formal passing guarantee, our exam dumps are built from highly accurate, real exam questions with verified answers, giving candidates everything they need to prepare with confidence and significantly improve their chances of success on the first attempt.</p>
                           </div>
                       </div>
                   </div>

                   <div class="panel panel-default">
                       <div class="panel-heading">
                           <a data-toggle="collapse" data-parent="#accordion" href="#faq6">
                           <h4 class="panel-title">
                               What format are the dumps provided in?
                                   <i class="fa fa-plus pull-right"></i>
                               
                           </h4>
                           </a>
                       </div>
                       <div id="faq6" class="panel-collapse collapse">
                           <div class="panel-body">
                               <p>Our exam dumps are provided in Secure PDF and VCE formats, making them easy to access on desktops, laptops, and mobile devices for flexible and convenient exam preparation.
 </p>
                           </div>
                       </div>
                   </div>
                       <div id="faq7" class="panel-collapse collapse">
                           <div class="panel-body">
                               <p>Yes, we offer lifetime updates to keep your study material up to date.</p>
                           </div>
                       </div>
                   </div>

                   <div class="panel panel-default">
                       <div class="panel-heading">
                           <a data-toggle="collapse" data-parent="#accordion" href="#faq8">
                           <h4 class="panel-title">
                               Can I access these dumps on my mobile device?
                                   <i class="fa fa-plus pull-right"></i>
                              
                           </h4>
                            </a>
                       </div>
                       <div id="faq8" class="panel-collapse collapse">
                           <div class="panel-body">
                               <p>Yes. Our exam dumps are fully compatible with mobile phones, tablets, and laptops, allowing you to study anytime, anywhere with complete convenience.</p>
                           </div>
                       </div>
                   </div>

                   <div class="panel panel-default">
                       <div class="panel-heading">
                           <a data-toggle="collapse" data-parent="#accordion" href="#faq9">
                           <h4 class="panel-title">
                               Is my purchase secure?
                                   <i class="fa fa-plus pull-right"></i>
                               
                           </h4>
                           </a>
                       </div>
                       <div id="faq9" class="panel-collapse collapse">
                           <div class="panel-body">
                               <p>Yes. All transactions on our platform are 100% secure and processed through trusted payment gateways such as Razorpay, PayPal, wire transfer, Western Union, purchase orders, Cisco vouchers, and Bitcoin, ensuring your payment information remains fully protected.</p>
                           </div>
                       </div>
                   </div>

                   <div class="panel panel-default">
                       <div class="panel-heading">
                           <a data-toggle="collapse" data-parent="#accordion" href="#faq10">
                           <h4 class="panel-title">
                               
                                 Do you offer customer support?
                                   <i class="fa fa-plus pull-right"></i>
                               
                           </h4>
                           </a>
                       </div>
                       <div id="faq10" class="panel-collapse collapse">
                           <div class="panel-body">
                               <p>Questions or issues don’t follow office hours and neither do we. Our 24/7 support team is always available to help with access, updates, or any concerns before and after your purchase.
</div>
                       </div>
                   </div>
                   <div class="panel panel-default">
                       <div class="panel-heading">
                           <a data-toggle="collapse" data-parent="#accordion" href="#faq11">
                           <h4 class="panel-title">
                                 Mode of Payments
                                   <i class="fa fa-plus pull-right"></i>
                           </h4>
                           </a>
                       </div>
                       <div id="faq11" class="panel-collapse collapse">
                           <div class="panel-body">
<p>
  Payments on ChineseDumps can be made securely using Razorpay or PayPal, as well as wire transfer, Western Union, credit/debit cards, purchase orders, Cisco vouchers, and Bitcoin, ensuring flexible and safe payment options for all customers.
  <a href="https://ccierack.rentals/payment-modes/">View Payment Mode</a>
</p>                           </div>
                       </div>
                   </div>

               </div>
           </div>
       </div>
   </div>
</section>


																<!-----------labs close---------->
																<?php if ($uri[0] == '/pmp.htm') { ?>
																	<div class="row">
																		<div class="col-md-6"> <img class="cirti_img" src="images/critificate_examimg.jpg" alt="critificate_examimg"> </div>
																		<div class="col-md-6"> <img class="cirti_img" src="images/chinese_dumps.jpg" alt="critificate_examimg"> </div>
																	</div>
																	<?php } ?>
											</div>
											
											
											<script>
											$('#writtenSlider').multislider({
												continuous: true,
												duration: 10000,
											});
											</script>
											
											
									<?php  /*
																				                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              * <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
																				                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              * 									<div class="pro_rht_img">
																				                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              * 										<div class="col-md-12"> <img src="images/chinesedumps-logo.png"> </div>
																				                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              * 										<div class="col-md-12 col-sm-6 col-xs-6"> <img src="images/ccnp-security.png"> </div>
																				                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              * 										<div class="col-md-12 col-sm-6 col-xs-6"> <img src="images/ccnp-service-provider.png"> </div>
																				                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              * 										<div class="col-md-12 col-sm-6 col-xs-6"> <img src="images/ccnp-data-center.png"> </div>
																				                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              * 										<div class="col-md-12 col-sm-6 col-xs-6"> <img src="images/ccie-wireless.png"> </div>
																				                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              * 										<div class="col-md-12 col-sm-6 col-xs-6"> <img src="images/ccie-collaboration.png"> </div>
																				                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              * 										<?php
																				                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              * if(isset($_POST['home_form'])){
																				                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              *    $to = "support@chinesedumps.com";
																				                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              *    $subject = "Chinese Dumps Exam page enquiry";
																				                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              *    $txt = "Name: ".$_POST['name']."\r\n";
																				                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              *    $txt .= "Email: ".$_POST['email']."\r\n";
																				                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              *    $txt .= "Tel: ".$_POST['tel']."\r\n";
																				                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              *    $headers = "From: ".$_POST['email'];
																				                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              *
																				                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              *    $mail_send=mail($to,$subject,$txt,$headers);
																				                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              *    if($mail_send){
																				                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              *       echo "<script>alert('Message Send successfully');</script>";
																				                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              *       header('Refresh: 1;url=/thanks-you.htm');
																				                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              *    }
																				                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              * }
																				                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              * ?>
																				                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              *
																				                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              * 									</div>
																				                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              * 								</div>
																				                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              */  ?>
										
								</div>
							</div>
							<?php 
							/*
							<div class="about-chinesedumps related-cert">
								<div class=""> <strong class="center-heading"><span><?php echo $venName; ?> Related Certification</span></strong>
									<div class="black-heading">
										<?php echo $venName; ?> Practice Exams Dumps Question Answers</div>
										
									<p>The followings list
										<?php echo $venName; ?> Certifications in Chinesedumps, If you have other
											<?php echo $venName; ?> certifications you want added please contact us.</p>
									<ul class="list-box list-title">
										<li>Exams</li>
										<li>Buy Now</li>
										<li></li>
									</ul>
                                    <ul class="list-box02">
                                    	<?
										$examl = '1';
										while ($rowallitems = mysql_fetch_array($exerel)) {
											$eurl = $base_path . $rowallitems['exam_name'] . '.htm';
											$exam_link = " <a href='" . $eurl . "' title='" . $rowallitems['exam_fullname'] . "'>" . $rowallitems['exam_name'] . '</a> - ' . $rowallitems['exam_fullname'] . ' ';
											?>
                                    		<li>
                                    			<ul class='list-box list-elements'>
                                    				<li>
                                    					<a title="<?php echo $rowallitems['exam_name'] ?>" href="<?php echo $eurl ?>">
                                    						<?php echo $rowallitems['exam_name'] ?>
                                    					</a>
                                    					<?php echo $rowallitems['exam_fullname'] ?>
                                    				</li>
                                    				<li>
                                    					<form action="<?php echo $eurl ?>">
                                    						<button type='submit' class='black-button'>View Details</button>
                                    					</form>
                                    				</li>
                                    				<li></li>
                                    			</ul>
                                    		</li>
                                    	<? } ?>
                                    </ul>
								</div>
							</div>
							*/  ?>
							
							
					


                
							
							
						</div>
						<!------------------------------ -->
						<? include ('includes/footer.php'); ?>
                        <script>
                            function redirectToLogin() {
                                alert("Please login to continue.");
                                var cookiePath = "<?php echo rtrim(parse_url(BASE_URL, PHP_URL_PATH) ?: '/', '/'); ?>/";
                                document.cookie = "redirect_after_login=" + encodeURIComponent(window.location.pathname + window.location.search) + ";path=" + cookiePath;
                                window.location.href = "<?php echo BASE_URL; ?>login.php";
                            }
                        </script>
						<script>
    						document.addEventListener("DOMContentLoaded", function () {
                                document.querySelector("form[name='myForm']").addEventListener("submit", function (event) {
                                    var recaptchaResponse = grecaptcha.getResponse();
                                    if (recaptchaResponse.length === 0) {
                                        event.preventDefault(); // Prevent form submission
                                        alert("Please complete the Google reCAPTCHA verification.");
                                    }
                                });
                            });
                            $(document).ready(function(){
                                $("form[name='myForm']").submit(function() {
                                    var countryCode = $("#mobile_code").intlTelInput("getSelectedCountryData").dialCode;
                                    $("#mobile_code").val("+" + countryCode + " " + $("#mobile_code").val());
                                });
                            });
                        </script>
							<?php } else {
                            	echo "<script language=javascript>self.location.href='<?php echo BASE_URL; ?>'</script>";
                            } ?>
								<script type="text/javascript">
								(function($) {
									$(function() {
										$("#scroller").simplyScroll();
									});
								})(jQuery);
								(function($) {
									$(function() {
										$("#scroller2").simplyScroll();
									});
								})(jQuery);
								</script>																	<?php // var_dump($exam) ?>


