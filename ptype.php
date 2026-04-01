<?php
    require_once __DIR__ . '/includes/config/load_secrets.php';
    ob_start();
    session_start();

    function getYoutubeId($url)
    {
    $url = str_replace('\/', '/', $url);
    if (preg_match('~youtu\.be/([^?&]+)~', $url, $m)) {
        return $m[1];
    }

    if (preg_match('~v=([^&]+)~', $url, $m)) {
        return $m[1];
    }

    if (preg_match('~/embed/([^?&]+)~', $url, $m)) {
        return $m[1];
    }

    return null;
    }

    function normalizeVideoService($service)
    {
    $allowed = ['youtube', 'rumble', 'dailymotion', 'odysee'];
    $service = strtolower(trim((string) $service));
    return in_array($service, $allowed, true) ? $service : 'youtube';
    }

    function normalizeVideoLinksFromStorage($rawValue)
    {
    $items = json_decode($rawValue, true);
    if (! is_array($items)) {
        return [];
    }

    $normalized = [];
    foreach ($items as $item) {
        if (is_array($item) && isset($item['url'])) {
            $url = trim((string) $item['url']);
            if ($url === '') {
                continue;
            }
            $normalized[] = [
                'service' => normalizeVideoService(isset($item['service']) ? $item['service'] : 'youtube'),
                'url'     => $url,
            ];
            continue;
        }

        if (is_string($item)) {
            $url = trim($item);
            if ($url === '') {
                continue;
            }
            $normalized[] = [
                'service' => 'youtube',
                'url'     => $url,
            ];
        }
    }

    return $normalized;
    }

    function getDailymotionId($url)
    {
    if (preg_match('~dailymotion\.com/video/([^_?&/]+)~i', $url, $m)) {
        return $m[1];
    }

    if (preg_match('~dai\.ly/([^?&/]+)~i', $url, $m)) {
        return $m[1];
    }

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
    if (! isset($parts['host']) || stripos($parts['host'], 'odysee.com') === false) {
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
    $url     = trim((string) $url);
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

    function replaceExamPlaceholders($template, $examCode, $examName, $certName, $vendorName)
    {
    return strtr((string) $template, [
        '[ExamCode]'   => (string) $examCode,
        '[ExamName]'   => (string) $examName,
        '[CertName]'   => (string) $certName,
        '[VendorName]' => (string) $vendorName,
    ]);
    }

    function isLabBootcampWorkbookRequest($requestUri)
    {
    $parts = explode('-', (string) $requestUri);

    $p1 = isset($parts[1]) ? $parts[1] : '';
    $p2 = isset($parts[2]) ? $parts[2] : '';
    $p5 = isset($parts[5]) ? $parts[5] : '';
    $p6 = isset($parts[6]) ? $parts[6] : '';

    return (
        $p1 === 'Bootcamp.htm' ||
        $p2 === 'Lab.htm' ||
        $p1 === 'Lab.htm' ||
        $p5 === 'lab.htm' ||
        $p6 === 'lab.htm' ||
        $p6 === 'workbook.htm' ||
        $p5 === 'workbook.htm' ||
        $p5 === 'bootcamp.htm' ||
        $p6 === 'bootcamp.htm'
    );
    }

    function removeLastWords($text, $count)
    {
    $count = (int) $count;
    $text  = trim((string) $text);
    if ($text === '' || $count <= 0) {
        return $text;
    }

    $words = preg_split('/\s+/', $text);
    if (! is_array($words) || count($words) <= $count) {
        return $text;
    }

    $words = array_slice($words, 0, -$count);
    return trim(implode(' ', $words));
    }

    function getLabWorkbookTitlePrefix($examFullname)
    {
    return removeLastWords($examFullname, 2);
    }

    function resolveExamVendorImagePath($examId, $courseImage, $vendorImage, $fallback = '')
    {
    $courseImage = trim((string) $courseImage);
    $vendorImage = trim((string) $vendorImage);

    if ($courseImage !== '') {
        $examImagePath = '/images/slider/' . $examId . '/' . $courseImage;
        if (file_exists(base_path($examImagePath))) {
            return $examImagePath;
        }
    }

    if ($vendorImage !== '') {
        $vendorIsUrl    = is_absolute_url($vendorImage);
        $vendorDiskPath = $vendorIsUrl ? '' : base_path($vendorImage);
        if ($vendorIsUrl || file_exists($vendorDiskPath)) {
            return $vendorImage;
        }
    }

    return (string) $fallback;
    }

    function getCourseStatusLabelHtml($examName)
    {
    $examName = trim((string) $examName);
    if ($examName === '') {
        return '<span style="color:green">Stable</span>';
    }

    $get_course = mysql_fetch_assoc(mysql_query("SELECT * FROM tbl_course WHERE name='" . $examName . "'"));
    if (! empty($get_course) && isset($get_course['status']) && (int) $get_course['status'] !== 1) {
        return '<span style="color:red">Unstable</span>';
    }

    return '<span style="color:green">Stable</span>';
    }

function normalizeExamType($examType)
{
    $examType = strtolower(trim((string)$examType));
    return ($examType === 'lab') ? 'lab' : 'written';
}

    include 'includes/config/classDbConnection.php';
    include 'includes/common/classes/classmain.php';
    include 'includes/common/classes/classProductMain.php';
    include 'includes/common/classes/classcart.php';
    include 'includes/common/classes/classUser.php';
    include 'functions.php';

    include 'includes/common/classes/classEmailvalidate.php';

    // -------------------------------------------------//
    $objDBcon   = new classDbConnection;
    $objMain    = new classMain($objDBcon);
    $objPro     = new classProduct($objDBcon);
    $objCart    = new classCart($objDBcon);
    $objUser    = new classUser($objDBcon);
    $objPricess = new classPrices();

    $emailValidator = new classEmailvalidate();

    // ------------------------------------------------//

    $getPage = $objMain->getContent(1);

    $guest = session_id();

    $isLoggedIn = isset($_SESSION['uid']);

    $_SESSION['guestId'] = $guest;

    $examURL = $_GET['pcode'];

    $exam = $objPro->getProductId($examURL);
$examType = normalizeExamType(isset($exam['exam_type']) ? $exam['exam_type'] : 'written');
$isLabLayout = ($examType === 'lab');

    $examID = $exam['exam_id'];

    // Fetch one row:
    $result = mysql_query(
    "SELECT youtube_links
     FROM tbl_exam
    WHERE exam_id = '" . $examID . "'"
    );
    $row = mysql_fetch_assoc($result);

    // Decode JSON into PHP array (new format + old youtube-only format)
    $video_links = normalizeVideoLinksFromStorage($row['youtube_links']);

    $price    = $objPricess->getProductPrice($examID, 'p', '0');
    $eprice   = $objPricess->getProductPrice($examID, 'sp', '0');
    $bprice   = $objPricess->getProductPrice($examID, 'both', '0');
    $examName = $exam['exam_fullname'];

    if ($examName == '') {
    header('location:index.html');
    }

    $examCode             = $exam['exam_name']; // Exam Code
    $freeDumpPdf          = isset($exam['free_dump_pdf']) ? $exam['free_dump_pdf'] : '';
    $freeDumpAbsolutePath = __DIR__ . '/uploads/free_dumps/' . $freeDumpPdf;
    $freeDumpWebPath      = 'uploads/free_dumps/' . $freeDumpPdf;
    $freeDumpError        = '';
    $freeDumpAvailable    = ($freeDumpPdf !== '');

    if (! function_exists('getUserDetails')) {
    function getUserDetails($userID)
    {
        $userID = intval($userID);
        $q      = mysql_query("SELECT user_email, user_fname, user_lname, user_phone FROM tbl_user WHERE user_id = '{$userID}'");
        if ($q && mysql_num_rows($q) > 0) {
            return mysql_fetch_assoc($q);
        }
        return false;
    }
    }

    if (isset($_POST['download_free_dump'])) {
    if (! $isLoggedIn) {
        $_SESSION['lasturl'] = $_SERVER['REQUEST_URI'];
        header("Location: " . BASE_URL . "login.php");
        exit;
    }
    if ($freeDumpPdf === '' || ! file_exists($freeDumpAbsolutePath)) {
        $freeDumpError = "Free dump file is not available right now.";
    } else {
        $userId     = intval($_SESSION['uid']);
        $userData   = getUserDetails($userId);
        $userFirst  = isset($userData['user_fname']) ? $userData['user_fname'] : '';
        $userLast   = isset($userData['user_lname']) ? $userData['user_lname'] : '';
        $userEmail  = isset($userData['user_email']) ? $userData['user_email'] : '';
        $userPhone  = isset($userData['user_phone']) ? $userData['user_phone'] : '';
        $userName   = trim($userFirst . ' ' . $userLast);
        $userIp     = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
        $timestamp  = date('Y-m-d H:i:s');
        $baseUrl    = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
        $currentUrl = $baseUrl . $_SERVER['REQUEST_URI'];
        $pdfUrl     = $baseUrl . '/' . $freeDumpWebPath;
        $adminEmail = "sales@chinesedumps.com";
        $subject    = "Free dump download: {$examName} ({$examCode})";

        $message = "
        <html>
        <head><title>Free Dump Download Notification</title></head>
        <body>
            <p><strong>User Details:</strong></p>
            <ul>
                <li><strong>Name:</strong> " . htmlspecialchars($userName) . "</li>
                <li><strong>Email:</strong> " . htmlspecialchars($userEmail) . "</li>" .
        (! empty($userPhone) ? "<li><strong>Phone:</strong> " . htmlspecialchars($userPhone) . "</li>" : '') . "
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

    $getPage[4] = $exam['exam_keywords'];

    $getPage[5] = $exam['exam_desc_seo'];

    $getglobart = mysql_fetch_array(mysql_query("SELECT * from tbl_globlecontents where page_id='3'"));

    $placeholderAppliedTitle        = replaceExamPlaceholders($getglobart['page_title'], $pCode, $getPage[1], $certName, $venName);
    $placeholderAppliedContentTitle = replaceExamPlaceholders($getglobart['content_title'], $pCode, $getPage[1], $certName, $venName);
    $placeholderAppliedMetaDesc     = replaceExamPlaceholders($getglobart['meta_descx'], $pCode, $getPage[1], $certName, $venName);
    $placeholderAppliedMetaKeywords = replaceExamPlaceholders($getglobart['meta_keywords'], $pCode, $getPage[1], $certName, $venName);

    if ($getPage[0] == '') {
    $getPage[0] = $placeholderAppliedTitle;
    }

    if ($getPage[1] == '') {
    $getPage[1] = $placeholderAppliedContentTitle;
    }

    if ($getPage[5] == '') {
    $getPage[5] = $placeholderAppliedMetaDesc;
    }

    if ($getPage[4] == '') {
    $getPage[4] = $placeholderAppliedMetaKeywords;
    }

    if ($getPage[2] == '' || strlen($getPage[2]) <= '15') {
    $getPage[2] = replaceExamPlaceholders($getglobart['page_contents'], $pCode, $getPage[1], $certName, $venName);
    $getPage[7] = replaceExamPlaceholders($getglobart['page_contents1'], $pCode, $getPage[1], $certName, $venName);
    $getPage[8] = replaceExamPlaceholders($getglobart['page_contents2'], $pCode, $getPage[1], $certName, $venName);
    }

    $firstlink = " <a href='" . $url . "' class='producttitletext' title='" . $venName . "'><strong>" . $venName . ' ></strong></a>';

    $thirdlink = ' ' . $examCode . '';

    $_SESSION['globlearticle'] = $examCode;

    if ($exam['exam_upddate'] == '0000-00-00') {
    $latestdate = $exam['exam_date'];
    } else {
    $latestdate = $exam['exam_upddate'];
    }

    $new_date = date('M j, Y', strtotime($latestdate));

    if ($isLabLayout) {
    $getrelexam = "SELECT * from tbl_exam where ven_id='" . $exam['ven_id'] . "' and exam_id!='" . $examID . "' and exam_status='1' and LOWER(TRIM(exam_type))='lab'";
    } else {
    $getrelexam = "SELECT * from tbl_exam where cert_id='" . $exam['cert_id'] . "' and exam_name!='" . $examCode . "' and exam_status='1'";
    }

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
    function sanitizeInput($data)
    {
        return htmlspecialchars(strip_tags(trim($data)));
    }

    $errors = [];

    $code  = sanitizeInput($_POST['request_ecode']);
    $email = sanitizeInput($_POST['request_email']);

    if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (! isset($_POST['g-recaptcha-response']) || empty($_POST['g-recaptcha-response'])) {
        $errors[] = "Please fill reCAPTCHA.";
    }

    $response = $emailValidator->email_validate_api($email);

    $data = json_decode($response, true);

    if ($data && isset($data['result'])) {
        if ($data['result'] == 'undeliverable') {
            $errors[] = "Email Address is not valid please try again";
        }
    } else {
        $errors[] = "Somthing Went Wrong";
    }

    if (! empty($errors) && count($errors) > 0) {
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
    if (mysql_num_rows($r) == 0) {
        $pth = $venName . '/' . $pCode . 'Sim.zip';
        mysql_query("insert into tbl_package_engine (package_id, version, type, path, os, price) values ('" . $examID . "','6.0','Simulator','" . $pth . "','Win','74.99')" . '');
    }

    $examURL = $exam['exam_url'];

    $examports = mysql_query("select * from report_card where r_exam='" . $examURL . "'");
    $examportimg = (mysql_num_rows($examports) != 0) ? 1 : 0;

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
			<?php if ($getPage[10] == '1') {?>
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
							<?php include 'includes/unlimitedpackage.php'; ?>
						</div>
					</div>
					<div class="exam-group-box">
						<?php include __DIR__ . ($isLabLayout ? '/lab.php' : '/written.php'); ?>
					</div>
						<!------------------------------ -->
						<? include ('includes/footer.php'); ?>
                        <script>
                            function demoVerify3() {
                                var regExp = /^[A-Za-z_0-9.\-]+@[A-Za-z0-9.\-]+\.[A-Za-z]{2,}$/;
                                var emailInput = document.forms['demoForm2'].elements['request_email'];
                                var errorNode = document.getElementById('emailErr2');
                                var email = emailInput ? emailInput.value : '';

                                if (email === '' || !regExp.test(email)) {
                                    alert('Enter Email');
                                    if (errorNode) {
                                        errorNode.style.display = 'block';
                                    }
                                    if (emailInput) {
                                        emailInput.focus();
                                    }
                                    return false;
                                }

                                if (errorNode) {
                                    errorNode.style.display = 'none';
                                }
                                return true;
                            }

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
                            }?>
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
								</script>


