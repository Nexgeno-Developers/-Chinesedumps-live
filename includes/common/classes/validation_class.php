<?php 
/************************************************************************
Validate_fields Class - verion 1.35
Easy to use form field validation

Copyright (c) 2004 - 2006, Olaf Lederer
All rights reserved.

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

    * Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
    * Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.
    * Neither the name of the finalwebsites.com nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

______________________________________________________________________
available at http://www.finalwebsites.com 
Comments & suggestions: http://www.finalwebsites.com/contact.php

Updates:
version 1.01 - Changed the method error_text($num, $fieldname = "") a little, to get a string with spaces in place of an underscore.

version 1.02 - Added the method check_decimal() to the class. This method checks for values like: -0.50 or 123.567 The method's validation() and add_field() are changed, too. (to take care of the new method)

version 1.04 - Added the Dutch translations to the error_text() method and also the German translations, thanks Theo good job!

version 1.05 - New method for URL validation, it's called check_url(). Now you can validate urls like http:/www.domain.com. The method create_msg() is called now insite teh constructor.

version 1.10 - I splitted the method add_field() into three methods: add_text_field(), add_num_field(), add_link_field() and add_date_field()
to make more clear which properties are needed.

version 1.11 - now is it possible to check european and US formatted dates just use the extra version parameter.

version 1.2 - I Added a new validation method: check_html_tags(), if the var $check_4html is set to true all fields will be validated for html tags. Negativ integers will be validated, too.

version 1.3 - I optimized / modified the method for the url check. Now is it possible to validate (nearly) all kind of urls from the 
HTTP protocol.

version 1.31 - the validation of e-mail addresses was not good. I changed the regex pattern to validate the most common cases. 

version 1.32 - there was a (last) small bug inside the email pattern, it's fixed now. The Danish translations are added in this version too, thanks to Niels Fanøe for the translation and the help with the e-mail validation.

version 1.33 - The regular expression pattern of the check for html code wasn't good, It's working now. The example page is modified to test general text inside a textarea (usefull to test the html code check). There are also spanish translations for the messages now, thank you Mark for doing this job.

version 1.34 - A small improvement in the method create_msg() makes it possible to switch between the xhml version and the simple html version. In the fields array one key was named "name", it's renamed to "value" to make it more clear. I removed the variable declarations at the begin of the method validation (thank you Mike Carter for pointing me on this). Because the value of a checkbox (radio) type field is only available if the element is checked there are new functions to validate this elements.

version 1.35 - In this version the date validation is more flexible to accept also day and month numbers without a leading zero. While using the PHP function "checkdate()" there is a additional real date check. Several translations are added now: polish, portuguese and czech. Thank you all for the translations. And at last there was a small bug inside the create_msg method, I used in this version a different function to sort the array.
*************************************************************************/

//error_reporting(E_ALL);

class Validate_fields {
	
	var $fields = array();
	var $messages = array();
	var $check_4html = false;
	var $language;
	
	function Validate_fields() {
		$this->language = "en";
		$this->create_msg();
	}
	function validation() {
		$status = 0; 
		foreach ($this->fields as $key => $val) {
			switch ($val['type']) {
				case "email":
				if (!$this->check_email($val['value'], $key, $val['required'])) {
					$status++;
				}
				break;
				case "decimalnumber":
				if (!$this->check_decimal_num($val['value'], $key,  $val['decimals'],$val['length'], $val['required'])) 
				{
					$status++;
				}
				break;
				case "number":
				if (!$this->check_num_val($val['value'], $key, $val['length'], $val['required'])) {
					$status++;
				}
				break;
				case "usphone":
				if (!$this->check_us_phone($val['value'], $key, $val['length'], $val['required'])) {
					$status++;
				}
				break;
				case "uszipcode":
				if (!$this->check_us_zipcode($val['value'], $key, $val['length'], $val['required'])) {
					$status++;
				}
				break;
				case "decimal":
				if (!$this->check_decimal($val['value'], $key, $val['decimals'], $val['required'])) {
					$status++;
				}
				break;
				case "date":
				if (!$this->check_date($val['value'], $key, $val['version'], $val['required'])) {
					$status++;
				}
				break;
				case "url":
				if (!$this->check_url($val['value'], $key, $val['required'])) {
					$status++;
				}
				break;
				case "text":
				if (!$this->check_text($val['value'], $key, $val['length'], $val['required'])) {
					$status++;
				}
				break;
				case "checkbox":
				case "radio":
				if (!$this->check_check_box($val['value'], $key, $val['element'])) {
					$status++;
				}
			} 
			if ($this->check_4html) {
				if (!$this->check_html_tags($val['value'], $key)) {
					$status++;
				}
			}
		}
		if ($status == 0) {
			return true;
		} else {
			$this->messages[] = $this->error_text(0);
			return false;
		}
	}
	function add_text_field($name, $val, $type = "text", $required = "y", $length = 0) {
		$this->fields[$name]['value'] = $val;
		$this->fields[$name]['type'] = $type;
		$this->fields[$name]['required'] = $required;
		$this->fields[$name]['length'] = $length;
	}
	function add_num_field($name, $val, $type = "number", $required = "y", $decimals = 0, $length = 0) {
		$this->fields[$name]['value'] = $val;
		$this->fields[$name]['type'] = $type;
		$this->fields[$name]['required'] = $required;
		$this->fields[$name]['decimals'] = $decimals;
		$this->fields[$name]['length'] = $length;
	}
	function add_phone_field($name, $val, $type = "usphone", $required = "y", $decimals = 0, $length = 0) {
		$this->fields[$name]['value'] = $val;
		$this->fields[$name]['type'] = $type;
		$this->fields[$name]['required'] = $required;
		$this->fields[$name]['decimals'] = $decimals;
		$this->fields[$name]['length'] = $length;
	}
	function add_zip_field($name, $val, $type = "usphone", $required = "y", $decimals = 0, $length = 0) {
		$this->fields[$name]['value'] = $val;
		$this->fields[$name]['type'] = $type;
		$this->fields[$name]['required'] = $required;
		$this->fields[$name]['decimals'] = $decimals;
		$this->fields[$name]['length'] = $length;
	}
	function add_link_field($name, $val, $type = "email", $required = "y") {
		$this->fields[$name]['value'] = $val;
		$this->fields[$name]['type'] = $type;
		$this->fields[$name]['required'] = $required;
	}
	function add_date_field($name, $val, $type = "date", $version = "us", $required = "y") {
		$this->fields[$name]['value'] = $val;
		$this->fields[$name]['type'] = $type;
		$this->fields[$name]['version'] = $version;
		$this->fields[$name]['required'] = $required;
	}
	function add_check_box($name, $element_name, $type = "checkbox", $required_value = "") {
		$this->fields[$name]['value'] = $required_value;
		$this->fields[$name]['type'] = $type;
		$this->fields[$name]['element'] = $element_name;
	}
	function check_url($url_val, $field, $req = "y") {
		if ($url_val == "") {
			if ($req == "y") {
				$this->messages[] = $this->error_text(1, $field);
				return false;
			} else {
				return true;
			}
		} else {
			$url_pattern = "http\:\/\/[[:alnum:]\-\.]+(\.[[:alpha:]]{2,4})+";
			$url_pattern .= "(\/[\w\-]+)*"; // folders like /val_1/45/
			$url_pattern .= "((\/[\w\-\.]+\.[[:alnum:]]{2,4})?"; // filename like index.html
			$url_pattern .= "|"; // end with filename or ?
			$url_pattern .= "\/?)"; // trailing slash or not
			$error_count = 0;
			if (strpos($url_val, "?")) {
				$url_parts = explode("?", $url_val);
				if (!preg_match("/^".$url_pattern."$/", $url_parts[0])) {
					$error_count++;
				}
				if (!preg_match("/^(&?[\w\-]+=\w*)+$/", $url_parts[1])) {
					$error_count++;
				}
			} else {
				if (!preg_match("/^".$url_pattern."$/", $url_val)) {
					$error_count++;
				}
			}
			if ($error_count > 0) {
				$this->messages[] = $this->error_text(14, $field);
					return false;
			} else {
				return true;
			}
		}
	}
//preg_replace("/[\s|-]/", "", $code);
	function check_us_zipcode($num_val, $field, $num_len = 0, $req = "n") {
		if ($num_val == "") {
			if ($req == "y") {
				$this->messages[] = $this->error_text(1, $field);
				return false;
			} else {
				return true;
			}
		} else {
			$pattern = ($num_len == 0) ? "/[\s|-]/" : "/[\s|-]/";
			if (preg_match($pattern, $num_val)) {
				return true;
			} else {
				$this->messages[] = $this->error_text(12, $field);
				return false;
			}
		}
	}

//preg_match("/\d{3}\-\d{3}\-\d{4}/",$aPhone)
	function check_us_phone($num_val, $field, $num_len = 0, $req = "n") {
		if ($num_val == "") {
			if ($req == "y") {
				$this->messages[] = $this->error_text(1, $field);
				return false;
			} else {
				return true;
			}
		} else {
			echo $pattern = ($num_len == 0) ? "/\d{3}\-\d{3}\-\d{4}/" : "/\d{3}\-\d{3}\-\d{4}/";
			if (preg_match($pattern, $num_val)) {
				return true;
			} else {
				$this->messages[] = $this->error_text(12, $field);
				return false;
			}
		}
	}

	function check_num_val($num_val, $field, $num_len = 0, $req = "n") {
		if ($num_val == "") {
			if ($req == "y") {
				$this->messages[] = $this->error_text(1, $field);
				return false;
			} else {
				return true;
			}
		} else {
			$pattern = ($num_len == 0) ? "/^\-?[0-9]*$/" : "/^\-?[0-9]{0,".$num_len."}$/";
			if (preg_match($pattern, $num_val)) {
				return true;
			} else {
				$this->messages[] = $this->error_text(12, $field);
				return false;
			}
		}
	}
	function check_text($text_val, $field, $text_len = 0, $req = "y") {
		if (empty($text_val)) {
			if ($req == "y") {
				$this->messages[] = $this->error_text(1, $field);
				return false;
			} else {
				return true; // in case only the text length is validated
			}
		} else {
			if ($text_len > 0) {
				if (strlen($text_val) > $text_len) {
					$this->messages[] = $this->error_text(13, $field);
					return false;
				} else {
					return true;
				}
			} else {
				return true;
			}
		}
	}
	function check_check_box($req_value, $field, $element) {
		if (empty($_REQUEST[$element])) {
			$this->messages[] = $this->error_text(12, $field);
			return false;
		} else {
			if (!empty($req_value)) {
				if ($req_value != $_REQUEST[$element]) {
					$this->messages[] = $this->error_text(12, $field);
					return false;
				} else {
					return true;
				}
			} else {
				return true;
			}
		}
	}
	function check_decimal($dec_val, $field, $decimals = 2, $req = "n") {
		if ($dec_val == "") {
			if ($req == "y") {
				$this->messages[] = $this->error_text(1, $field);
				return false;
			} else {
				return true;
			}
		} else {
			$pattern = "/^[-]*[0-9][0-9]*\.[0-9]{".$decimals."}$/";
			if (preg_match($pattern, $dec_val)) {
				return true;
			} else {
				$this->messages[] = $this->error_text(12, $field);
				return false;
			}
		}
	}
	//check_decimal_num($val['value'], $key,  $val['decimals'],$val['length'], $val['required']))
	function check_decimal_num($dec_val, $field, $decimals = 2,$num_len, $req = "n") {
		if ($dec_val == "") {
			if ($req == "y") {
				$this->messages[] = $this->error_text(1, $field);
				return false;
			} else {
				return true;
			}
		} else {
			$pattern1 = "/^[-]*[0-9][0-9]*\.[0-9]{".$decimals."}$/";
			$pattern2 = ($num_len == 0) ? "/^\-?[0-9]*$/" : "/^\-?[0-9]{0,".$num_len."}$/";
			if ((preg_match($pattern1, $dec_val)) || (preg_match($pattern2, $dec_val))) 
			{
				return true;
			} else {
				$this->messages[] = $this->error_text(12, $field);
				return false;
			}
		}
	}
	function check_date($date, $field, $version = "us", $req = "n") { 
		if ($date == "") {
			if ($req == "y") {
				$this->messages[] = $this->error_text(1, $field);
				return false;
			} else {
				return true;
			}
		} else {
			$date_parts = explode("-", $date);
			 $month = $date_parts[0];
			if ($version == "eu") {
				$pattern = "/^(0?[1-9]|[1-2][0-9]|3[0-1])[-](0?[1-9]|1[0-2])[-](19|20)[0-9]{2}$/";
				$day  = $date_parts[1];
				$year = $date_parts[2];
				
			} else {
				 $pattern ="/[0-9]{1,2}\-[0-9]{1,2}\-[0-9]{4}/";
				 $day  = $date_parts[1];
				 $year = $date_parts[2];
				 
			}
			if (preg_match($pattern,$date) && checkdate(intval($month), intval($day), $year)) {
				return true;
			} else {
				$this->messages[] = $this->error_text(10, $field);
				return false;
			}
		}
	}
	function check_email($mail_address, $field, $req = "y") {
		if ($mail_address == "") {
			if ($req == "y") {
				$this->messages[] = $this->error_text(1, $field);
				return false;
			} else {
				return true;
			}
		} else {
			if (preg_match("/^[0-9a-z]+(([\.\-_])[0-9a-z]+)*@[0-9a-z]+(([\.\-])[0-9a-z-]+)*\.[a-z]{2,4}$/i", $mail_address)) {
				return true;
			} else {
				$this->messages[] = $this->error_text(11, $field);
				return false;
			}
		}
	}
	function check_html_tags($value, $field) {
		if (preg_match("/<[a-z]+(\s[a-z]{2,}=['\"]?(.*)['\"]?)+(\s?\/)?>(<\/[a-z]>)?/i", $value)) {
			$this->messages[] = $this->error_text(15, $field);
			return false;
		} else {
			return true;
		}
	}
	function create_msg($break_elem = "<br />") {
		$the_msg = "";
		krsort($this->messages); // modified in 1.35
		reset($this->messages); 
		foreach ($this->messages as $value) {
			$the_msg .= $value.$break_elem."\n";
		}
		return $the_msg;
	}
	function error_text($num, $fieldname = "") {
		$fieldname = str_replace("_", " ", $fieldname);
		switch ($this->language) {
			case "de":
			$msg[0]  = "Verbessern Sie bitte folgende Fehler:";
			$msg[1]  = "Das Feld <b>".$fieldname."</b> ist leer.";
			$msg[10] = "Das Datum im Feld <b>".$fieldname."</b> ist ung&uuml;tig.";
			$msg[11] = "Die Email Adresse im Feld <b>".$fieldname."</b> ist ung&uuml;ltig.";
			$msg[12] = "Der Wert im Feld <b>".$fieldname."</b> ist ung&uuml;ltig.";
			$msg[13] = "Der Text im Feld <b>".$fieldname."</b> ist zu lang.";
			$msg[14] = "Die internetadresse im Feld <b>".$fieldname."</b> ist ung&uuml;ltig.";
			$msg[15] = "Das Feld <b>".$fieldname."</b> enth&auml;lt html-Zeichen, die sind nicht erlaubt.";
			break;
			case "nl":
			$msg[0] = "Corrigeer de volgende fouten:";
			$msg[1] = "Het veld <b>".$fieldname."</b> mag niet leeg zijn.";
			$msg[10] = "Het datum in veld <b>".$fieldname."</b> is niet geldig.";
			$msg[11] = "Het e-mail adres in veld <b>".$fieldname."</b> is niet geldig.";
			$msg[12] = "De waarde van veld <b>".$fieldname."</b> is niet geldig.";
			$msg[13] = "De tekst in veld <b>".$fieldname."</b> is te lang.";
			$msg[14] = "De internetadres in het veld <b>".$fieldname."</b> is niet geldig.";
			$msg[15] = "In het veld <b>".$fieldname."</b> is html-code, dit is niet toegestaan.";
			break;
			case "dk":
			$msg[0] = "Ret følgende fejl:";
			$msg[1] = "Feltet <b>".$fieldname."</b> er tomt.";
			$msg[10] = "Datoen i feltet <b>".$fieldname."</b> er ikke gyldig.";
			$msg[11] = "E-mail-adressen i feltet <b>".$fieldname."</b> er ikke gyldig.";
			$msg[12] = "Værdien i feltet <b>".$fieldname."</b> er ikke gyldig.";
			$msg[13] = "Teksten i feltet <b>".$fieldname."</b> er for lang.";
			$msg[14] = "URL'en i feltet <b>".$fieldname."</b> er ikke gyldig.";
			$msg[15] = "Feltet <b>".$fieldname."</b> indeholder HTML-koder, hvilket ikke er tilladt.";
			break;
			case "es":
			$msg[0] = "Por favor corrija los siguientes errores:";
			$msg[1] = "El campo <b>".$fieldname."</b> est&aacute; vac&iacute;o.";
			$msg[10] = "La fecha del campo <b>".$fieldname."</b> no es v&aacute;lida.";
			$msg[11] = "La direcci&oacute;n de correo electr&oacute;nico del campo <b>".$fieldname."</b> no es v&aacute;lida.";
			$msg[12] = "El valor en el campo <b>".$fieldname."</b> no es v&aacute;lido.";
			$msg[13] = "El texto en el campo <b>".$fieldname."</b> es demasiado largo.";
			$msg[14] = "La URL en el campo <b>".$fieldname."</b> no es v&aacute;lida.";
			$msg[15] = "Hay c&oacute;digo HTML en el campo <b>".$fieldname."</b>, esto no est&aacute; permitido.";
            break;
			case "pl":
            $msg[0] = "Wyst±pi&#322;y nast&#281;puj±ce b&#322;&#281;dy w formularzu:";
			$msg[1] = "Pole <b>".$fieldname."</b> jest puste.";
			$msg[10] = "Data w polu <b>".$fieldname."</b> nie jest poprawna.";
			$msg[11] = "Adres e-mail w polu <b>".$fieldname."</b> nie jest poprawny.";
			$msg[12] = "Warto¶&#263; w polu <b>".$fieldname."</b> nie jest poprawna.";
			$msg[13] = "Text w polu <b>".$fieldname."</b> jest za d&#322;ugi.";
			$msg[14] = "Adres strony w polu <b>".$fieldname."</b> nie jest poprawny.";
			$msg[15] = "W polu <b>".$fieldname."</b> znaleziono kod HTML, nie jest to dozwolone.";
			break;
			case "cz":
			$msg[0] = "Opravte prosím následující chyby:";
			$msg[1] = "Pole <b>".$fieldname."</b> je prázdné";
			$msg[10] = "Datum v poli <b>".$fieldname."</b> není ve správném formátu";
			$msg[11] = "E-mailová adresa v poli <b>".$fieldname."</b> není platná adresa";
			$msg[12] = "Hodnota v poli <b>".$fieldname."</b> není správná";
			$msg[13] = "Text v poli <b>".$fieldname."</b> je p&#345;íliš dlouhý";
			$msg[14] = "Odkaz v poli <b>".$fieldname."</b> není správn&#283; zapsán";
			$msg[15] = "V poli <b>".$fieldname."</b> se vyskytuje nepovolený HTML kód";
			break;
			case "pt":
			$msg[0] = "Por favor corrija o seguinte:";
			$msg[1] = "O campo em <b>".$fieldname."</b> está vazio.";
			$msg[10] = "A data no campo <b>".$fieldname."</b> não é válida.";
			$msg[11] = "O endereço de E-Mail no campo <b>".$fieldname."</b> não é válido.";
			$msg[12] = "O valor no campo <b>".$fieldname."</b> não é válido.";
			$msg[13] = "O texto no campo <b>".$fieldname."</b> é longo demais.";
			$msg[14] = "O url no campo <b>".$fieldname."</b> não é válido.";
			$msg[15] = "Há códigos de HTML no campo <b>".$fieldname."</b>, que não são permitidos.";
			break;
			default:
			$msg[0] = "Please correct the following error(s):";
			$msg[1] = "The field <b>".$fieldname."</b> is empty.";
			$msg[10] = "The date in field <b>".$fieldname."</b> is not valid.";
			$msg[11] = "The e-mail address in field <b>".$fieldname."</b> is not valid.";
			$msg[12] = "The value in field <b>".$fieldname."</b> is not valid.";
			$msg[13] = "The text in field <b>".$fieldname."</b> is too long.";
			$msg[14] = "The url in field <b>".$fieldname."</b> is not valid.";
			$msg[15] = "There is html code in field <b>".$fieldname."</b>, this is not allowed.";
		}
		return $msg[$num];
	}
}
?>