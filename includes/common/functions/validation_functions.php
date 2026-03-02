<?Php
	//All these Functions checks only US Related Informations.
	
	//This functoin checks the user name.
	//Takes one Parameter
	// 1: $userName --> User name
	// Return "1" if valid and "0" if not valid
	function CheckUsername($userName)
	{
		 if(!eregi("^[a-zA-Z0-9._-]+$", $userName)) 
			  return false; 
		 else
	   		 return true;
	}
	//*****************************************************************************************
	//This functoin checks the Digit Number.
	//Takes one Parameter
	// 1: $Number -->  Number
	// Return true if valid and false if not valid

	function isDigits($Number) 
	{
		if(!preg_match ("/^[0-9]+$/", $Number))
			return false;
		else	
		   return true;
	}
	
	//*****************************************************************************************
	//This functoin checks the US Zip Code.
	//Takes one Parameter
	// 1: $code --> US zip Code
	// Return true if valid and false if not valid
	function checkUSZipCode($code)
	{
	  
	  $code = preg_replace("/[\s|-]/", "", $code);
	  $length = strlen ($code);

      if (($length != 5) && ($length != 9)) 
    	  return false;
	  return isDigits($code);
	}
	//*****************************************************************************************
	//This functoin checks the UK Post Code.
	//Takes one Parameter
	// 1: $code --> US zip Code
	// Return true if valid and false if not valid
	function checkUKZipCode($code)
	{
		if(!preg_match("/^([A-PR-UWYZ0-9][A-HK-Y0-9][AEHMNPRTVXY0-9]?[ABEHMNPRVWXY0-9]? {1,2}[0-9][ABD-HJLN-UW-Z]{2}|GIR 0AA)$/", $code)) 
        	return false; 
		else
			return true;		
	}
	/*function checkUKZipCode($code)
	{
	  
		$banned = array('EX10 0AF', 'EX10 0JX', 'EX10 0LB', 'EX10 0LD', 'EX10 0LJ', 'EX10 0LW', 'EX10 0LD', 'EX10 0NG', 'EX10 0SF', 'EX10 9PN', 'EX10 0LD', 'EX11 1LU', 'EX11 1NF', 'EX12 4AF', 'EX13 5RS', 'EX13 7LF', 'EX13 7LN', 'EX13 7LW', 'EX13 7NN', 'EX13 7PE', 'EX13 7PG', 'EX13 7PJ', 'EX13 7PN', 'EX13 7PP', 'EX13 7PQ', 'EX13 7RG', 'EX14 3HE', 'EX14 3NZ', 'EX15 1BH', 'EX15 1BS', 'EX15 1BW', 'EX15 1NX', 'EX15 1PA', 'EX15 1QL', 'EX15 1XL', 'EX15 2LQ', 'EX15 2ND', 'EX15 2NW', 'EX15 2RB', 'EX15 3AR', 'EX15 3DB', 'EX15 3DR', 'EX15 3HG', 'EX15 3JJ', 'EX15 3JL', 'EX16 4NA', 'EX16 5AA', 'EX16 5AD', 'EX16 5AE', 'EX16 5HY', 'EX16 5JL', 'EX16 5JN', 'EX16 5JP', 'EX16 5JR', 'EX16 5JT', 'EX16 5JU', 'EX16 5JW', 'EX16 5LE', 'EX16 5LF', 'EX16 5LG', 'EX16 5LH', 'EX16 5LJ', 'EX16 5LQ', 'EX16 5LZ', 'EX16 5QG', 'EX16 6HA', 'EX16 6HX', 'EX16 6JQ', 'EX16 6RZ', 'EX16 6SB', 'EX16 6SW', 'EX16 6TG', 'EX16 6TR', 'EX16 7JH', 'EX16 7JQ', 'EX16 7RA', 'EX16 7RB', 'EX16 7RJ', 'EX16 8HD', 'EX16 8HJ', 'EX16 8LA', 'EX16 8PU', 'EX16 8RG', 'EX16 8R		P', 'EX16 8RW', 'EX16 8SA', 'EX16 9AF', 'EX16 9AY', 'EX16 9DX', 'EX16 9JA', 'EX16 9PD', 'EX16 9PY', 'EX17 3DH', 'EX17 3PS', 'EX17 3QN', 'EX17 4SL', 'EX17 5AX', 'EX17 5HU', 'EX17 5JB', 'EX17 5LW', 'EX17 5PW', 'EX17 6HZ', 'EX18 7EA', 'EX18 7LF', 'EX18 7PL', 'EX18 7SL', 'EX19 8AZ', 'EX2 6LH', 'EX2 6LL', 'EX2 6LR', 'EX2 6LW', 'EX2 6LX', 'EX2 6LY', 'EX20 2AB', 'EX20 2AH', 'EX20 2AJ', 'EX20 2EE', 'EX20 2EF', 'EX20 2LZ', 'EX20 2NH', 'EX20 2NP', 'EX20 2NR', 'EX20 2SE', 'EX20 3EG', 'EX20 3NE', 'EX22 7UW', 'EX23 9BN', 'EX23 9BP', 'EX23 9BW', 'EX24 6QF', 'EX3 0PA', 'EX3 0PB', 'EX31 3JH', 'EX31 4AP', 'EX31 4AW', 'EX31 4HG', 'EX31 4LR', 'EX31 4QB', 'EX31 4RU', 'EX31 4ST', 'EX31 4TT', 'EX32 0LX', 'EX32 0LZ', 'EX32 0ND', 'EX32 0RJ', 'EX33 2NX', 'EX34 0AE', 'EX34 0AF', 'EX34 0AJ', 'EX34 0AN', 'EX34 0AQ', 'EX34 0NA', 'EX34 0PJ', 'EX35 6NX', 'EX36 3DT', 'EX36 3EP', 'EX36 3JH', 'EX36 3JJ', 'EX36 3JL', 'EX36 3LE', 'EX36 3LZ', 'EX36 3NW', 'EX36 3PS', 'EX36 4HX', 'EX36 4JJ', 'EX36 4JL', 'EX36 4LG', 'EX36 4LQ', 'EX36 4PN', 'EX3		6 4	QD', 'EX36 4RT', 'EX37 9AB', 'EX37 9AG', 'EX37 9AR', 'EX37 9DA', 'EX37 9HR', 'EX37 9JS', 'EX37 9NB', 'EX37 9RE', 'EX38 7EJ', 'EX38 7HD', 'EX38 8AS', 'EX38 8AT', 'EX38 8AW', 'EX38 8JD', 'EX38 8JE', 'EX39 4QT', 'EX39 6DS', 'EX39 6DY', 'EX39 6EA', 'EX39 6HL', 'EX4 2HA', 'EX4 5AD', 'EX5 1BT', 'EX5 1BX', 'EX5 2NG', 'EX5 2NH', 'EX5 2NJ', 'EX5 4BR', 'EX5 4BS', 'EX5 4BT', 'EX5 4LD', 'EX5 5AB', 'EX5 5AE', 'EX5 5EQ', 'EX5 5LX', 'EX5 5LY', 'EX6 7HE', 'EX6 7PW', 'EX6 7QL', 'EX6 7QN', 'EX6 7TA', 'EX6 7TB', 'EX6 7TD', 'EX6 7TF', 'EX6 7TG', 'EX6 7TH', 'EX6 7TJ', 'EX6 7TL', 'EX6 7TN', 'EX6 7UT', 'EX6 8JS', 'EX7 9AE', 'EX7 9BJ', 'EX7 9PY', 'EX8 5ER', 'EX8 5EY', 'EX8 5EZ', 'EX8 5HH', 'EX8 5HQ', 'EX9 7AZ', 'PL10 1BY', 'PL12 5BG', 'PL13 2EP', 'PL13 2ER', 'PL13 2ES', 'PL13 2EX', 'PL14 3LJ', 'PL14 4QX', 'PL14 6NG', 'PL15 7NW', 'PL15 8DH', 'PL15 8EX', 'PL15 8UW', 'PL15 9QN', 'PL15 9QP', 'PL16 0AH', 'PL16 0AJ', 'PL16 0AL', 'PL16 0EL', 'PL17 8LQ', 'PL17 8NJ', 'PL17 8NL', 'PL19 9PR', 'PL20 6SG', 'PL20 7SL', 'PL20 7SP', 'PL20 7		SS', 'PL20 7TG', 'PL20 7TJ', 'PL20 7TZ', 'PL21 0LL', 'PL21 9NT', 'PL24 2AD', 'PL24 2AE', 'PL24 2AF', 'PL24 2AG', 'PL24 2AH', 'PL24 2AJ', 'PL24 2AN', 'PL24 2AQ', 'PL24 2AR', 'PL24 2AT', 'PL24 2AW', 'PL24 2AX', 'PL24 2AY', 'PL24 2BB', 'PL24 2BD', 'PL24 2DH', 'PL24 2DN', 'PL24 2HY', 'PL24 2JA', 'PL24 2JB', 'PL24 2JD', 'PL24 2JF', 'PL24 2JG', 'PL24 2JH', 'PL24 2JQ', 'PL24 2LU', 'PL24 2LX', 'PL24 2LZ', 'PL24 2ND', 'PL24 2NF', 'PL24 2NJ', 'PL24 2NS', 'PL24 2NU', 'PL24 2NX', 'PL24 2NY', 'PL24 2PA', 'PL24 2PB', 'PL24 2PD', 'PL24 2PE', 'PL24 2RF', 'PL24 2RL', 'PL24 2RN', 'PL24 2TW', 'PL25 5BU', 'PL26 6BT', 'PL26 6BU', 'PL26 6BX', 'PL26 6BZ', 'PL26 6DA', 'PL26 6DB', 'PL26 6DD', 'PL26 6DG', 'PL26 6RZ', 'PL26 7AA', 'PL26 7AD', 'PL26 7AE', 'PL26 7AR', 'PL26 7AS', 'PL26 7AX', 'PL26 7AY', 'PL26 7LL', 'PL3 6EE', 'PL30 5LF', 'PL30 5LL', 'PL32 9PB', 'PL32 9PD', 'PL32 9PG', 'PL32 9TL', 'PL5 4AQ', 'PL5 4LD', 'PL5 4NB', 'PL5 4NG', 'PL5 4NH', 'PL5 4NZ', 'PL7 1YB', 'PL8 2DY', 'PL8 2DZ', 'PL8 2EY', 'PL8 2LS', 'PL8 2LX', 'PL8 2NA		', 'TQ10 9EF', 'TQ10 9ET', 'TQ10 9NB', 'TQ11 0AH', 'TQ11 0BA', 'TQ11 0BS', 'TQ11 0BT', 'TQ11 0BU', 'TQ11 0BY', 'TQ11 0BZ', 'TQ11 0EA', 'TQ11 0HE', 'TQ11 0NN', 'TQ11 0PF', 'TQ11 0QA', 'TQ12 3PF', 'TQ12 5UP', 'TQ12 6NL', 'TQ13 0NJ', 'TQ13 7DY', 'TQ13 7EJ', 'TQ13 7JG', 'TQ13 7QH', 'TQ13 7QL', 'TQ13 7QP', 'TQ13 7QW', 'TQ13 7RF', 'TQ13 7RN', 'TQ13 7TF', 'TQ13 7TG', 'TQ13 8JZ', 'TQ13 8LA', 'TQ13 8QY', 'TQ13 8SD', 'TQ13 9SS', 'TQ13 9SW', 'TQ13 9TB', 'TQ13 9TS', 'TQ14 8AB', 'TQ14 8AD', 'TQ14 8AE', 'TQ14 8AF', 'TQ14 8AH', 'TQ14 8AJ', 'TQ14 8AL', 'TQ14 8AS', 'TQ14 8AT', 'TQ14 8AU', 'TQ14 8AW', 'TQ14 8AX', 'TQ14 8AY', 'TQ14 8BG', 'TQ14 8BJ', 'TQ14 8BQ', 'TQ14 8BR', 'TQ14 8BT', 'TQ14 8BU', 'TQ14 8BX', 'TQ14 8BZ', 'TQ14 8DA', 'TQ14 8DB', 'TQ14 8DD', 'TQ14 8DE', 'TQ14 8DJ', 'TQ14 8DN', 'TQ14 8DS', 'TQ14 8EA', 'TQ14 8EB', 'TQ14 8EE', 'TQ14 8EF', 'TQ14 8EG', 'TQ14 8EN', 'TQ14 8EP', 'TQ14 8ES', 'TQ14 8FG', 'TQ14 8HH', 'TQ14 8HR', 'TQ14 8HT', 'TQ14 8HW', 'TQ14 8PE', 'TQ14 8SJ', 'TQ14 8SN', 'TQ14 8ST', 'TQ14 8SU', 'TQ14 8SW		', 'TQ14 8SX', 'TQ14 8SY', 'TQ14 8SZ', 'TQ14 8TB', 'TQ7 2DN', 'TQ7 2QD', 'TQ7 2RE', 'TQ7 4DS', 'TQ9 6AH', 'TQ9 6NE', 'TQ9 6PD', 'TQ9 6RH', 'TQ9 6RL', 'TQ9 7AE', 'TQ9 7JT', 'TQ9 7LN', 'TQ9 7SR', 'TQ9 7SS', 'TQ9 7SU', 'TQ9 7SX', 'TQ9 7SZ', 'TQ9 7TA', 'TQ9 7TB', 'TQ9 7TH', 'TQ9 7TJ', 'TQ9 7TL', 'TQ9 7TP', 'TQ9 7TQ', 'TQ9 7TR', 'TQ9 7TS', 'TQ9 7TT', 'TQ9 7TU', 'TQ9 7TX', 'TQ9 7TY', 'TQ9 7UH', 'TR13 0PF', 'TR13 0RA', 'TR13 8HG', 'TR13 8HP', 'TR13 8HR', 'TR13 8HW', 'TR13 8HZ', 'TR17 0HJ', 'TR17 0HN', 'TR17 0HQ', 'TR18 3LL', 'TR19 7PG', 'TR19 7PH', 'TR19 7PN', 'TR19 7PQ', 'TR19 7PX', 'TR19 7TW', 'TR2 4AU', 'TR2 4EX', 'TR2 4HX', 'TR2 4PG', 'TR2 4PH', 'TR2 4PP', 'TR2 4PQ', 'TR2 4QB', 'TR2 4RU', 'TR2 4RX', 'TR2 4SB', 'TR20 9BL', 'TR3 6AA', 'TR3 7BT', 'TR3 7BU', 'TR3 7BX', 'TR3 7BY', 'TR3 7ND', 'TR4 8QZ', 'TR4 8RA', 'TR4 8RE', 'TR4 8RL', 'TR4 8RN', 'TR4 8SU', 'TR4 9PE', 'TR4 9QT', 'TR7 2HX', 'TR7 2RW', 'TR7 2TG', 'TR7 3BS', 'TR7 3PE', 'TR8 4BJ', 'TR8 4EP', 'TR8 4JW', 'TR8 4PU', 'TR8 4QE', 'TR8 5PS', 'TR9 6BD', 'TR9 		6BY'); 


		$banned = '/^('.implode('|', $banned).')/i'; 
		if(preg_match($banned, $code)) 
			return false; 

	    $prefixes = '/^(ex|pl|tq|tr)/i'; 

    	if(!preg_match($prefixes, $code)) 
    	    return false; 
	
	    $syntax = '/^([a-z]{1,2}[0-9]{1,2})\s?([0-9]{1}[a-z]{2})$/i'; 

    	if(!preg_match($syntax, $postcode, $matches)) 
        	return false; 
		
	  	return true;
	}*/
	//*****************************************************************************************
	//This functoin checks the US Phone Number.
	//Takes one Parameter
	// 1: $aPhone --> US Phone Number
	// Return true if valid and false if not valid

	function checkUSPhone($aPhone)
	{
		//if(!preg_match ("/^\(\d{3}\)\d{3}-\d{4}$/",$aPhone))
		if(!preg_match("/\d{3}\-\d{3}\-\d{4}/",$aPhone))
			return false;
		else	
			return true;
	}
	//*****************************************************************************************
	//This functoin checks the UK Phone Number.
	//Takes one Parameter
	// 1: $aPhone --> US Phone Number
	// Return true if valid and false if not valid

	function checkUKPhone($aPhone)
	{
		
		//echo preg_match ("/^(((\+44\s?\d{4}|\(?0\d{4}\)?)\s?\d{3}\s?\d{3})|((\+44\s?\d{3}|\(?0\d{3}\)?)\s?\d{3}\s?\d{4})|((\+44\s?\d{2}|\(?0\d{2}\)?)\s?\d{4}\s?\d{4}))(\s?\#(\d{4}|\d{3}))?$/",$aPhone)."   aaa";
		
//		if(1 == preg_match ("/^(((\+44\s?\d{4}|\(?0\d{4}\)?)\s?\d{3}\s?\d{3})|((\+44\s?\d{3}|\(?0\d{3}\)?)\s?\d{3}\s?\d{4})|((\+44\s?\d{2}|\(?0\d{2}\)?)\s?\d{4}\s?\d{4}))(\s?\#(\d{4}|\d{3}))?$/",$aPhone))
		if(1 == preg_match ("/^(((\d{4}|\(?0\d{4}\)?)\s?\d{3}\s?\d{3})|((\d{3}|\(?0\d{3}\)?)\s?\d{3}\s?\d{4})|((\d{2}|\(?0\d{2}\)?)\s?\d{4}\s?\d{4}))(\s?\#(\d{4}|\d{3}))?$/",$aPhone))
			return true;
		else	
			return false;
	}
	
	//*****************************************************************************************
	//This functoin checks the Valid URLs.
	//Takes one Parameter
	// 1: $URL --> URL
	// Return true if valid and false if not valid

	function checkURL($URL)
	{
		 if (!preg_match ("/http:\/\/(.*)\.(.*)/i", $URL)) {
		    return false;
		  }

/*		  $parts = parse_url($url);

		  $fp = fsockopen($parts['host'], 80, $errno, $errstr, 10);
		  if(!$fp) {
			return false;
		  }
		  fclose($fp);*/
		  return true;
	}
	//*****************************************************************************************
	//This functoin checks the Valid Strings.
	//Takes one Parameter
	// 1: $string --> Input String
	// Return true if valid and false if not valid

	function checkString($string)
	{
		 if (!preg_match ("/^[A-Za-z \._,\/]+$/", $string)) {
		    return false;
		  }
		  return true;
	}
	//*****************************************************************************************
	//This functoin checks the Valid Alpha + Special chars Strings.
	//Takes one Parameter
	// 1: $string --> Input String
	// Return true if valid and false if not valid

	function checkASString($string)
	{
		 if (!preg_match ("/^[A-Za-z0-9 \\\.\-&_,#\/:]+$/", $string)) {
		    return false;
		  }
		  return true;
	}
	
	//*****************************************************************************************
	//This functoin checks the Valid Alphanumeric Strings.
	//Takes one Parameter
	// 1: $string --> Input String
	// Return true if valid and false if not valid

	function checkAlphaString($string)
	{
		 if (!preg_match ("/^[A-Za-z0-9]+$/", $string)) {
		    return false;
		  }
		  return true;
	}
	
	//*****************************************************************************************
	//This functoin checks the Valid Alphanumeric Strings.
	//Takes one Parameter
	// 1: $string --> Input String
	// Return true if valid and false if not valid

	function checkAlphaStringWithAnd($string)
	{
		 if (!preg_match ("/^[A-Za-z0-9& ]+$/", $string)) {
		    return false;
		  }
		  return true;
	}
	
	//*****************************************************************************************
    //This Function Checks Email... 
	   function checkEmail($Email)
	   {
		 
		 $Arr=explode("@",$Email); $serStr=$Arr[0]; 
		
		   //..................Basically Email Checking is done here. 
		   $len=strlen($serStr); 
		   if(!eregi("^[_a-zA-Z0-9-]+(\.[_a-z0-9-]+)*@[a-zA-Z0-9-]{3,20}(\.[a-z0-9-]+)*(\.[a-z]{3,20})*(\.[a-z]{2,3})$", $Email) || $serStr[0]=="-" || $serStr[0]=="_" || $serStr[$len-1]=="-" || $serStr[$len-1]=="_" || $serStr[0]>="0" && $serStr[0]<="9") 
			  return 0; 
		   else
	   			return 1;
		}//End of function

	//*****************************************************************************************
		function GenerateMailBody ($TextBody, $HTMLBody)
		{
			$body 	= "--=_NextPart_000_002C_01BFABBF.4A7D6BA0\n"
					 ."Content-Type: text/plain; charset=\"iso-8859-1\"\n"
					 ."Content-Transfer-Encoding: 7bit\n\n"
					 ."$TextBody\n\n"
					 ."--=_NextPart_000_002C_01BFABBF.4A7D6BA0\n"
					 ."Content-Type: text/html; charset=\"iso-8859-1\"\n"
					 ."Content-Transfer-Encoding: 7bit\n\n"
					 ."$HTMLBody\n\n"
					 ."--=_NextPart_000_002C_01BFABBF.4A7D6BA0--";
					 
			return $body;
		}
	//*****************************************************************************************
		function GenerateMailHeader ($FromName, $FromEmail, $ReplyToName, $ReplyToEmail)
		{
			$Header	= ""
					. "From:" . $FromName ."<" .$FromEmail .">\n"
					. "Reply-To:" .$ReplyToName ."<" . $ReplyToEmail .">\n"
					. "Content-Type: multipart/alternative;\n"
					. "\tboundary=\"=_NextPart_000_002C_01BFABBF.4A7D6BA0\"\n"
					. "\nThis is a multi-part message in MIME format.";
			return $Header;
		}
		
	//*****************************************************************************************
	//*****************************************************************************************	
	// this function replaces the the character ' \ \\ ; 
	function validateString($strString)
	{
		$strString = 	str_replace("'", "''", $strString);
		$strString = 	str_replace("\'", "'", $strString);
		$strString = 	str_replace(";", "\;", $strString);
		$strString = 	str_replace("\\", "",  $strString);
		$strString = 	trim($strString);
		
		return $strString;
	}
	//*****************************************************************************************	
	//This function is also used for sending emails along with attachments.
	function mail_attachment($txtFiletoAttach, $txtFilePath, $txtMailTo, $txtMailFrom, $txtFromName, $replyto, $txtSubject, $txtMessage) { 
		$file = $txtFilePath.$txtFiletoAttach; 
		$file_size = filesize($file); 
		$handle = fopen($file, "r"); 
		$content = fread($handle, $file_size); 
		fclose($handle); 
		$content = chunk_split(base64_encode($content)); 
		$uid = md5(uniqid(time())); 
		$name = basename($file); 
		$header = "From: ".$txtFromName." <".$txtMailFrom.">\r\n"; 
		$header .= "Reply-To: ".$replyto."\r\n"; 
		$header .= "MIME-Version: 1.0\r\n"; 
		$header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n"; 
		$header .= "This is a multi-part message in MIME format.\r\n"; 
		$header .= "--".$uid."\r\n"; 
		$header .= "Content-type:text/html; charset=iso-8859-1\r\n"; 
		$header .= "Content-Transfer-Encoding: 7bit\r\n\r\n"; 
		$header .= $txtMessage."\r\n\r\n"; 
		$header .= "--".$uid."\r\n"; 
		$header .= "Content-Type: application/octet-stream; name=\"".$txtFiletoAttach."\"\r\n"; // use diff. tyoes here 
		$header .= "Content-Transfer-Encoding: base64\r\n"; 
		$header .= "Content-Disposition: attachment; filename=\"".$txtFiletoAttach."\"\r\n\r\n"; 
		$header .= $content."\r\n\r\n"; 
		$header .= "--".$uid."--"; 
		if (mail($txtMailTo, $txtSubject, "", $header)) { 
			return true; // or use booleans here 
		} else { 
			return false; 
		} 
		
	} 
	//*****************************************************************************************	
?>