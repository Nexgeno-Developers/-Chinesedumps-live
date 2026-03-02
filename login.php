<?php

ob_start();

session_start();
include("includes/config/classDbConnection.php");



include("includes/common/classes/classmain.php");

include("includes/common/classes/classProductMain.php");

include("includes/common/classes/classcart.php");

include("includes/common/classes/classUser.php");

include("functions.php");

include("includes/shoppingcart.php");

include ('includes/common/classes/classEmailvalidate.php');



$objDBcon   =   new classDbConnection; 

$objMain	=	new classMain($objDBcon);

$objPro		=	new classProduct($objDBcon);

$objCart	=	new classCart($objDBcon);

$objUser	=	new classUser($objDBcon);

$emailValidator = new classEmailvalidate();



$getPage	=	$objMain->getContent(5);



if(isset($_SESSION['uid']) ){
    // Default redirect URL
    $redirectUrl = 'mydownloads.html';
    
    // Check if cookie exists
    if (isset($_COOKIE['redirect_after_login'])) {
    $redirectFromCookie = $_COOKIE['redirect_after_login'];
    
    // Optional: check if it’s the demo page (you can use strpos)
    if (strpos($redirectFromCookie, 'demo') !== false) {
    $redirectUrl = $redirectFromCookie;
    }
    
    // Unset the cookie (expire it)
    setcookie('redirect_after_login', '', time() - 3600, '/');
    }
    
    // Perform redirect
    header("Location: " . $redirectUrl);
    exit;
}

// if(isset($_SESSION['uid']) ){
//     echo '<SCRIPT LANGUAGE="JavaScript">window.location="mydownloads.html" </script>';
// }
// if (isset($_SESSION['uid'])) {

//     // Priority: If "redirect" is set in the query string
//     if (isset($_GET['redirect']) && !empty($_GET['redirect'])) {
//         $redirectUrl = $_GET['redirect'];
//     } 
//     // Secondary check: if coming from a known demo page (e.g., set via hidden field or referrer)
//     elseif (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'demo') !== false) {
//         $redirectUrl = $_SERVER['HTTP_REFERER'];
//     } 
//     // Fallback
//     else {
//         $redirectUrl = 'mydownloads.html';
//     }

//     // Use proper redirect header
//     header("Location: " . $redirectUrl);
//     exit;
// }
    $quer	=	$objDBcon->Sql_Query_Exec('*','website','');

	$exec	=	mysql_fetch_array($quer);

	

	$copyright	=	$exec['copyright'];

	$from_email	=	$exec['from_email'];

	

	$firstlink	=	' '.$getPage[1];	

	

	///////////////////////////////sign in portion//////////////////////////////////////

	if(isset($_POST['Signin']))

	{
	    $errors = [];

	    $response = $emailValidator->email_validate_api($_POST['Email']);
        $email_valid_data = $response ? json_decode($response, true) : null;

        if ($email_valid_data && isset($email_valid_data['result'])) {
            if ($email_valid_data['result'] === 'undeliverable') {
                $errors[] = "Email Address is not valid please try again";
            }
        } elseif ($response === false) {
            error_log('Email validation API call failed during login for: ' . $_POST['Email']);
        } elseif (!empty($response)) {
            error_log('Email validation unexpected response during login: ' . $response);
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header('Location: ' . BASE_URL . 'login.html');
            exit;
        }


	 	$spram[1]		 =  $_POST['Email'];

	 	$spram[2]		 =  $_POST['Password']; 



		if($objUser->ValidUserLogin($spram))

		{

		

		 $row	= $objUser->getUserID($spram);

		

		$_SESSION['email']    = $spram[1];

		$_SESSION['uid']      = $row['user_id'];

 		$_SESSION['Uname']    = $row['user_fname'];

 		$_SESSION['password'] = $spram[2];

		

		$rty	=	"UPDATE temp_order set UserID='".$row['user_id']."' where CartID='".session_id()."'";

		mysql_query($rty);

        // Prefer redirect_after_login cookie (relative same-site) after successful login
        if (isset($_COOKIE['redirect_after_login'])) {
            $redirectFromCookie = $_COOKIE['redirect_after_login'];
            $isRelative = (strpos($redirectFromCookie, '://') === false);
            if ($isRelative && strlen($redirectFromCookie) > 0) {
                setcookie('redirect_after_login', '', time() - 3600, '/');
                header("Location: " . $redirectFromCookie);
                exit;
            }
            setcookie('redirect_after_login', '', time() - 3600, '/');
        }

		if(isset($_SESSION['check'])){

		echo '<SCRIPT LANGUAGE="JavaScript">

		window.location="'.$base_path.'cart.html" </script>';

		}

		if(isset($_SESSION['checkout'])){

		echo '<SCRIPT LANGUAGE="JavaScript">

		window.location="'.$base_path.'checkout.php" </script>';

		}

		//echo '<SCRIPT LANGUAGE="JavaScript">window.location="'.$base_path.'checkout.htm";

		if(isset($_SESSION['lasturl'])&& $_SESSION['lasturl']!=''){

		

		echo '<SCRIPT LANGUAGE="JavaScript">

		window.location="'.$base_path.''.$_SESSION['lasturl'].'.html" </script>';

		}

		

		if(!isset($_SESSION['lasturl']) && !$_SESSION['check']!=''){

		echo '<SCRIPT LANGUAGE="JavaScript">

		window.location="mydownloads.html" </script>';

		}

		}

		else{

		$msg2 = "Invalid Login Email or Password";

		}

	}

	

	///////////////////////////////////////sign up portion//////////////////////////////////

	if(isset($_POST['emails']) && isset($_POST['fName']) && isset($_POST['passwords']))

	{
	    
	    $response = $emailValidator->email_validate_api($_POST['emails']);
        
        $email_valid_data = json_decode($response, true);

        if ($email_valid_data && isset($email_valid_data['result'])) {
            if($email_valid_data['result'] == 'undeliverable'){
               $errors[] = "Email Address is not valid please try again";
            }
        } else {
            $errors[] = "Somthing Went Wrong";
        }

        if (!empty($errors) && count($errors) > 0) {
            $_SESSION['errors'] = $errors;
            header('Location: ' . BASE_URL . 'login.html');
            exit;
        }

	

$sparm[1]	=	$_POST['fName'];

$sparm[2]	=	$_POST['lastname'];

$sparm[5]	=	$_POST['emails'];
$sparm[11]	=	$_POST['concode'];
$sparm[12]	=	$_POST['pnumber'];
$sparm[13] = $sparm[11]."-".$sparm[12];

$sparm[9]	=	$_POST['passwords'];



$sparm[6]	=   date('Y-m-d');

$sparm[7]	=   $_SERVER['REMOTE_ADDR'];

include_once("securimage/securimage.php");

$securimage = new Securimage();



$code = $_POST['captcha_code'];

if ($securimage->check($code) == false)

{

	$msg = "<font color='#FF0000' size='2'>Please enter values shown in image for validation (Reload image if possible)<font>";

}

else

{

if(empty($arrValid))

{

	$sql	= "select user_email,user_password from tbl_user where user_email='".$sparm[5]."'";

	$rs		= mysql_query($sql);

	$num	= mysql_num_rows($rs);

	

	if($num >=1){

		$msg="Error! Email address already exist.";

		}else{

		

		$lastsignupd	=	$objUser->addUser($sparm);

		

		$row	= $objUser->getUserIDsignup($lastsignupd);

		

		$_SESSION['email']    = $row['user_email'];
		$_SESSION['uid']      = $row['user_id'];
 		$_SESSION['Uname']    = $row['user_fname'];
 		$_SESSION['password'] = $row['user_password'];
		$_SESSION['uphone'] = $row['user_phone'];

		$sparm[3]	=	$_POST['passwords'];

		signupemail($sparm,$from_email);

		mysql_query("UPDATE temp_order set UserID='".$lastsignupd."' where CartID='".session_id()."'");

        // Prefer redirect_after_login cookie (relative same-site) after successful login
        if (isset($_COOKIE['redirect_after_login'])) {
            $redirectFromCookie = $_COOKIE['redirect_after_login'];
            $isRelative = (strpos($redirectFromCookie, '://') === false);
            if ($isRelative && strlen($redirectFromCookie) > 0) {
                setcookie('redirect_after_login', '', time() - 3600, '/');
                header("Location: " . $redirectFromCookie);
                exit;
            }
            setcookie('redirect_after_login', '', time() - 3600, '/');
        }

		if(isset($_SESSION['check'])){

		echo '<SCRIPT LANGUAGE="JavaScript">

		window.location="'.$base_path.'checkout.html" </script>';

		}

		if(isset($_SESSION['checkout'])){

		echo '<SCRIPT LANGUAGE="JavaScript">

		window.location="'.$base_path.'checkout.php" </script>';

		}

		if(isset($_SESSION['lasturl'])&& $_SESSION['lasturl']!=''){

		

		echo '<SCRIPT LANGUAGE="JavaScript">

		window.location="'.$base_path.''.$_SESSION['lasturl'].'.html" </script>';

		}

		

		if(!isset($_SESSION['lasturl']) && !$_SESSION['check']!=''){

		echo '<SCRIPT LANGUAGE="JavaScript">

		window.location="myorders.html" </script>';

		}

		

		$msg="Data save successfully now you can Sign in";

		}

	}

}

}

	//////////////////////////////////////////////////////////////////////////

	

		

include("html/login.html");

?>
