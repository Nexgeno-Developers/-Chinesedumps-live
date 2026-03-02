<?PHP

error_reporting(E_ALL ^ E_DEPRECATED);
/*

	
*/
	//--------------------------------------------------------------------------------
	//					<<<<Creation of the Class Admin class>>>>	
	error_reporting(15);
	//--------------------------------------------------------------------------------
	//					<<<<Headers including area>>>>>
	//===================================================================================

	class classMain
	{
		//--------------------------------------------------------------------------------
								// Class Properties (Class Level Variables)
		//--------------------------------------------------------------------------------
			var $objDbConn		;
		//..........................................................................................................

		/*Constructor initialize all the variables*/
		
		function classMain($objDbConn)
		{
			$this->objDbConn	= 	$objDbConn; # Populate the Connection property with the Connection Class Object.
		}//

		function getContent($pageId){
			$select_con	=	"SELECT * from content_pages where page_id=".$pageId."";
			$exeselect	=	mysql_query($select_con);
			$get_fetch	=	mysql_fetch_array($exeselect);
			$content[0]	=	$get_fetch['page_title'];
			$content[1]	=	$get_fetch['content_title'];
			$content[2]	=	$get_fetch['page_contents'];
			$content[4]	=	$get_fetch['meta_keywords'];
			$content[5]	=	$get_fetch['meta_descx'];
			return $content;
		}
		
		
		
//.................................................................................................



		///////////////////////////////////Get Data from Table///////////////////////////////////////
		function get_all_Tabledate($LIMIT,$tableName,$condition)
			{
			 	$strQury="Select * from ".$tableName." ".$condition."".$LIMIT;
				$rsResult = $this->objDbConn->Dml_Query_Parser($strQury);
				return $rsResult;
			}
		////////////////////////////////////Delete single ID/////////////////////////////////////////
		function delete_singleID($pro_id,$tblName,$fieldName)
			{
				$strQury 	= 	"DELETE FROM  ".$tblName." WHERE ".$fieldName." = '".$pro_id."'";
				$sqlResult	=	$this->objDbConn->Dml_Query_Parser($strQury);
				
			}
		/////////////////////////////////////////////////////////////////////////////////////////////


		
			

	//////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function htmlwrap($str, $width = 95, $break = "\n", $nobreak = "") {
 
  // Split HTML content into an array delimited by < and >
  // The flags save the delimeters and remove empty variables
  $content = preg_split("/([<>])/", $str, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
 
  // Transform protected element lists into arrays
  $nobreak = explode(" ", strtolower($nobreak));
 
  // Variable setup
  $intag = false;
  $innbk = array();
  $drain = "";
 
  // List of characters it is "safe" to insert line-breaks at
  // It is not necessary to add < and > as they are automatically implied
  $lbrks = "/?!%)-}]\\\"':;&";
  // Is $str a UTF8 string?
  $utf8 = (preg_match("/^([\x09\x0A\x0D\x20-\x7E]|[\xC2-\xDF][\x80-\xBF]|\xE0[\xA0-\xBF][\x80-\xBF]|[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}|\xED[\x80-\x9F][\x80-\xBF]|\xF0[\x90-\xBF][\x80-\xBF]{2}|[\xF1-\xF3][\x80-\xBF]{3}|\xF4[\x80-\x8F][\x80-\xBF]{2})*$/", $str)) ? "u" : "";
 
  while (list(, $value) = each($content)) {
    switch ($value) {
 
      // If a < is encountered, set the "in-tag" flag
      case "<": $intag = true; break;
 
      // If a > is encountered, remove the flag
      case ">": $intag = false; break;
 
      default:
 
        // If we are currently within a tag...
        if ($intag) {
 
          // Create a lowercase copy of this tag's contents
          $lvalue = strtolower($value);
 
          // If the first character is not a / then this is an opening tag
          if ($lvalue{0} != "/") {
 
            // Collect the tag name   
            preg_match("/^(\w*?)(\s|$)/", $lvalue, $t);
 
            // If this is a protected element, activate the associated protection flag
            if (in_array($t[1], $nobreak)) array_unshift($innbk, $t[1]);
 
          // Otherwise this is a closing tag
          } else {
 
            // If this is a closing tag for a protected element, unset the flag
            if (in_array(substr($lvalue, 1), $nobreak)) {
              reset($innbk);
              while (list($key, $tag) = each($innbk)) {
                if (substr($lvalue, 1) == $tag) {
                  unset($innbk[$key]);
                  break;
                }
              }
              $innbk = array_values($innbk);
            }
          }
 
        // Else if we're outside any tags...
        } else if ($value) {
 
          // If unprotected...
          if (!count($innbk)) {
 
            // Use the ACK (006) ASCII symbol to replace all HTML entities temporarily
            $value = str_replace("\x06", "", $value);
            preg_match_all("/&([a-z\d]{2,7}|#\d{2,5});/i", $value, $ents);
            $value = preg_replace("/&([a-z\d]{2,7}|#\d{2,5});/i", "\x06", $value);
 
            // Enter the line-break loop
            do {
              $store = $value;
 
              // Find the first stretch of characters over the $width limit
              if (preg_match("/^(.*?\s)?([^\s]{".$width."})(?!(".preg_quote($break, "/")."|\s))(.*)$/s{$utf8}", $value, $match)) {
 
                if (strlen($match[2])) {
                  // Determine the last "safe line-break" character within this match
                  for ($x = 0, $ledge = 0; $x < strlen($lbrks); $x++) $ledge = max($ledge, strrpos($match[2], $lbrks{$x}));
                  if (!$ledge) $ledge = strlen($match[2]) - 1;
 
                  // Insert the modified string
                  $value = $match[1].substr($match[2], 0, $ledge + 1).$break.substr($match[2], $ledge + 1).$match[4];
                }
              }
 
            // Loop while overlimit strings are still being found
            } while ($store != $value);
 
            // Put captured HTML entities back into the string
            foreach ($ents[0] as $ent) $value = preg_replace("/\x06/", $ent, $value, 1);
          }
        }
    }
 
    // Send the modified segment down the drain
    $drain .= $value;
  }
 
  // Return contents of the drain
  return $drain;
}

	
	
/////////////////////////////////////////////////////////////////////////////////////////////
		
	}
?>